let formData_filtro;

document.addEventListener("DOMContentLoaded", function () {
  formData_filtro = new FormData();
  formData_filtro.append("nombre", "");
  formData_filtro.append("linea", "");
  formData_filtro.append("plataforma", "");
  formData_filtro.append("min", "");
  formData_filtro.append("max", "");
  formData_filtro.append("favorito", "0");

  const initialProductsPerPage = 24;
  const additionalProductsPerPage = 24;
  let currentPage = 1;
  let products = [];
  let displayedProducts = new Set();

  const cardContainer = document.getElementById("card-container");
  const loadingIndicator = document.getElementById("loading-indicator");
  const loadMoreButton = document.getElementById("load-more");
  let isLoading = false;
  let currentFetchController = null;
  let currentDisplayController = null;

  /************************************************
   * Función para vaciar pedidos temporales al cargar
   ************************************************/
  const vaciarTmpPedidos = async () => {
    try {
      const response = await fetch("" + SERVERURL + "marketplace/vaciarTmp");
      if (!response.ok) {
        throw new Error("Error al vaciar los pedidos temporales");
      }
      const data = await response.json();
      console.log("Respuesta de vaciarTmp:", data);
    } catch (error) {
      console.error("Error al hacer la solicitud:", error);
    }
  };

  /************************************************
   * Filtrar, limpiar y recargar productos
   ************************************************/
  async function clearAndFetchProducts() {
    // Cancel any ongoing fetch
    if (currentFetchController) {
      currentFetchController.abort();
    }

    // Cancel any ongoing display task
    if (currentDisplayController) {
      currentDisplayController.abort();
    }

    // Clear previous products
    clearProductList();

    // Fetch new products after a brief delay to ensure clearing is done
    setTimeout(() => fetchProducts(true), 100);
  }

  function clearProductList() {
    cardContainer.innerHTML = "";
    displayedProducts.clear();
    products = [];
    currentPage = 1;
  }

  async function fetchProducts(reset = true) {
    if (currentFetchController) {
      currentFetchController.abort(); // Cancel the previous request if any
    }

    currentFetchController = new AbortController();
    const { signal } = currentFetchController;

    if (reset) {
      isLoading = true; // Prevent further actions until the list is reset
      loadingIndicator.style.display = "block";
      clearProductList(); // Clear the container immediately
    }

    try {
      const response = await fetch(
        `${SERVERURL}marketplace/obtener_productos`,
        {
          method: "POST",
          body: formData_filtro,
          signal,
        }
      );
      const newProducts = await response.json();

      if (reset) {
        products = newProducts;
        currentPage = 1; // Reset the current page
      } else {
        products = [...products, ...newProducts];
      }

      displayProducts(
        products,
        currentPage,
        reset ? initialProductsPerPage : additionalProductsPerPage
      );
    } catch (error) {
      if (error.name === "AbortError") {
        console.log("Fetch request canceled");
      } else {
        console.error("Error al obtener los productos:", error);
      }
    } finally {
      isLoading = false;
      loadingIndicator.style.display = "none";
      loadMoreButton.style.display = products.length ? "block" : "none";
    }
  }

  /************************************************
   * Mostrar productos en la página
   ************************************************/
  const displayProducts = (products, page, perPage) => {
    if (currentDisplayController) {
      currentDisplayController.abort();
    }

    currentDisplayController = new AbortController();
    const { signal } = currentDisplayController;

    const start = (page - 1) * perPage;
    const end = start + perPage;
    const paginatedProducts = products.slice(start, end);

    paginatedProducts.forEach((product) => {
      if (displayedProducts.has(product.id_producto)) return;
      displayedProducts.add(product.id_producto);

      fetchProductDetails(product.id_producto, signal)
        .then((productDetails) => {
          if (productDetails) {
            createProductCard(product, productDetails[0]);
          }
        })
        .catch((error) => {
          if (error.name === "AbortError") {
            console.log("Display task canceled");
          } else {
            console.error("Error al obtener los detalles del producto:", error);
          }
        });
    });
  };

  async function fetchProductDetails(productId, signal) {
    const response = await fetch(
      SERVERURL + "marketplace/obtener_producto/" + productId,
      { signal }
    );
    return await response.json();
  }

  function createProductCard(product, productDetails) {
    const { costo_producto, pvp, saldo_stock, url_imporsuit } = productDetails;

    let boton_enviarCliente = ``;
    let botonId_inventario = ``;
    if (product.producto_variable == 0) {
      boton_enviarCliente = `<button class="btn btn-import" onclick="enviar_cliente(${product.id_producto},'${product.sku}',${product.pvp},${product.id_inventario})">Enviar a cliente</button>`;
      botonId_inventario = `<div class="card-id-container" onclick="copyToClipboard(${product.id_inventario})">
        <span class="card-id">ID: ${product.id_inventario}</span>
      </div>`;
    } else if (product.producto_variable == 1) {
      boton_enviarCliente = `<button class="btn btn-import" onclick="abrir_modalSeleccionAtributo(${product.id_producto},'${product.sku}',${product.pvp},${product.id_inventario})">Enviar a cliente</button>`;
      botonId_inventario = `<div class="card-id-container" onclick="abrir_modal_idInventario(${product.id_producto})">
      <span class="card-id">Ver IDs de producto variable </span>
    </div>`;
    }

    const esFavorito = product.Es_Favorito === "1"; // Conversión a booleano

    const card = document.createElement("div");
    card.className = "card card-custom position-relative";
    // Usar la función para obtener la URL de la imagen
    const imagePath = obtenerURLImagen(productDetails.image_path, SERVERURL);

    // Lógica para verificar si la imagen existe
    verificarImagen(imagePath).then((validador_imagen) => {
      let finalImage = imagePath;
      if (validador_imagen === 0) {
        finalImage = SERVERURL + "public/img/broken-image.png";
      }
      card.innerHTML = `
        <div class="image-container position-relative">
          ${botonId_inventario}
          <img src="${finalImage}" class="card-img-top" alt="Product Image">
          <div class="add-to-store-button ${
            product.agregadoTienda ? "added" : ""
          }" data-product-id="${product.id_producto}">
            <span class="plus-icon">+</span>
            <span class="add-to-store-text">${
              product.agregadoTienda ? "Quitar de tienda" : "Añadir a tienda"
            }</span>
          </div>
          <div class="add-to-funnel-button" ${
            product.agregadoFunnel ? "added" : ""
          } data-funnel-id="${product.id_inventario}">
            <span class="plus-icon">+</span>
            <span class="add-to-funnel-text">${
              product.agregadoFunnel ? "Quitar de funnel" : "Añadir a funnel"
            }</span>
          </div>
        </div>
        <button class="btn btn-heart ${
          esFavorito ? "clicked" : ""
        }" onclick="handleHeartClick(${product.id_producto}, ${esFavorito})">
          <i class="fas fa-heart"></i>
        </button>
        <div class="card-body text-center d-flex flex-column justify-content-between">
          <div>
            <h6 class="card-title"><strong>${
              product.nombre_producto
            }</strong></h6>
            <p class="card-text">Stock: <strong style="color:green">${saldo_stock}</strong></p>
            <p class="card-text">Precio Proveedor: <strong>${
              productDetails.pcp
            }</strong></p>
            <p class="card-text">Precio Sugerido: <strong>$${pvp}</strong></p>
            <p class="card-text">Proveedor: <a href="#" onclick="abrirModal_infoTienda('${url_imporsuit}')" style="font-size: 15px;">${
        productDetails.nombre_tienda
      }</a></p>
          </div>
          <div>
            <button class="btn btn-description" onclick="agregarModal_marketplace(${
              product.id_producto
            })">Descripción</button>
            ${boton_enviarCliente}
          </div>
        </div>
      `;
    });

    cardContainer.appendChild(card);
  }

  function debounce(func, wait) {
    let timeout;
    return function (...args) {
      const context = this;
      clearTimeout(timeout);
      timeout = setTimeout(() => func.apply(context, args), wait);
    };
  }

  // Evento de click “global” para los botones “añadir a tienda” y “añadir a funnel”
  cardContainer.addEventListener("click", function (event) {
    const target = event.target;
    if (
      target.classList.contains("add-to-store-button") ||
      target.closest(".add-to-store-button")
    ) {
      const button = target.closest(".add-to-store-button");
      const productId = button.getAttribute("data-product-id");
      const isAdded = button.classList.contains("added");
      toggleAddToStore(productId, isAdded);
    }
    if (
      target.classList.contains("add-to-funnel-button") ||
      target.closest(".add-to-funnel-button")
    ) {
      const button = target.closest(".add-to-funnel-button");
      const funnelId = button.getAttribute("data-funnel-id");
      window.location.href =
        "" + SERVERURL + "funnelish/constructor_vista/" + funnelId;
    }
  });

  /************************************************
   * Revisar si la imagen existe
   ************************************************/
  async function verificarImagen(url) {
    try {
      const response = await fetch(url);
      if (response.ok) {
        return 1; // La imagen existe
      } else {
        return 0; // La imagen no existe
      }
    } catch (error) {
      return 0; // La imagen no existe
    }
  }

  /************************************************
   * Añadir/quitar producto a tienda
   ************************************************/
  function toggleAddToStore(productId, isAdded) {
    let formData = new FormData();
    formData.append("id_producto", productId);
    $.ajax({
      url: SERVERURL + "Productos/importar_productos_tienda",
      method: "POST",
      data: formData,
      processData: false,
      contentType: false,
      success: function (response) {
        response = JSON.parse(response);
        if (response.status == 500) {
          toastr.warning("" + response.message, "NOTIFICACIÓN", {
            positionClass: "toast-bottom-center",
          });
        } else if (response.status == 200) {
          toastr.success("" + response.message, "NOTIFICACIÓN", {
            positionClass: "toast-bottom-center",
          });
        }
      },
      error: function (error) {
        console.error("Error al actualizar el estado del producto:", error);
      },
    });
  }

  /************************************************
   * Añadir/quitar producto a funnel
   ************************************************/
  function toggleAddToFunnel(funnelId, funnelIdInput, isAdded) {
    let formData = new FormData();
    formData.append("id_inventario", funnelId);
    formData.append("id_funnel", funnelIdInput.value);
    $.ajax({
      url: SERVERURL + "Productos/importar_productos_funnel",
      method: "POST",
      data: formData,
      processData: false,
      contentType: false,
      success: function (response) {
        response = JSON.parse(response);
        if (response.status == 500) {
          toastr.warning("" + response.message, "NOTIFICACIÓN", {
            positionClass: "toast-bottom-center",
          });
        } else if (response.status == 200) {
          toastr.success("" + response.message, "NOTIFICACIÓN", {
            positionClass: "toast-bottom-center",
          });
        }
      },
      error: function (error) {
        console.error("Error al actualizar el estado del producto:", error);
      },
    });
  }

  /************************************************
   * Filtro de precio con noUiSlider
   ************************************************/
  var slider = document.getElementById("price-range-slider");
  var priceMin = document.getElementById("price-min");
  var priceMax = document.getElementById("price-max");

  // 1) Primero vaciamos pedidos
  vaciarTmpPedidos().then(() => {
    // 2) Obtenemos el precio máximo para el slider
    fetch(`${SERVERURL}marketplace/obtenerMaximo`)
      .then((response) => {
        if (!response.ok) {
          throw new Error("Network response was not ok");
        }
        return response.json();
      })
      .then((data) => {
        let data_precioMaximo = parseFloat(data);
        if (isNaN(data_precioMaximo)) {
          // En caso de que la API devuelva algo inesperado
          data_precioMaximo = 999999;
        }

        noUiSlider.create(slider, {
          start: [0, data_precioMaximo],
          connect: true,
          range: {
            min: 0,
            max: data_precioMaximo,
          },
          step: 1,
          format: wNumb({
            decimals: 0,
            thousand: ",",
            prefix: "$",
          }),
        });

        // Ajustamos formData_filtro para mostrar todo inicialmente
        formData_filtro.set("min", 0); // <-- CAMBIO: usar 0
        formData_filtro.set("max", data_precioMaximo); // <-- CAMBIO: usar precioMáximo

        // Cada vez que el usuario mueva los sliders
        slider.noUiSlider.on("update", function (values, handle) {
          if (handle === 0) {
            priceMin.value = values[0];
          } else {
            priceMax.value = values[1];
          }
        });

        slider.noUiSlider.on("change", function (values) {
          var min = values[0].replace("$", "").replace(",", "");
          var max = values[1].replace("$", "").replace(",", "");
          formData_filtro.set("min", min);
          formData_filtro.set("max", max);
          clearAndFetchProducts();
        });

        // 3) Una vez configurado el slider y formData_filtro, llamamos fetchProducts para mostrar todo
        fetchProducts(true); //Se muestra todo al iniciar
      })
      .catch((error) => {
        console.error("Error fetching max price:", error);
        // Si ocurre un error, ponemos un rango por defecto y fetch
        noUiSlider.create(slider, {
          start: [0, 1000],
          connect: true,
          range: {
            min: 0,
            max: 1000,
          },
          step: 5,
          format: wNumb({
            decimals: 0,
            thousand: ",",
            prefix: "$",
          }),
        });
        formData_filtro.set("min", 0);
        formData_filtro.set("max", 1000);
        fetchProducts(true);
      });
  });

  /************************************************
   * Filtro por nombre, categoría, proveedor, etc.
   ************************************************/
  const handleSelectChange = debounce(function () {
    clearAndFetchProducts();
  }, 300);

  // Filtro por texto (nombre)
  $("#buscar_nombre").on(
    "input",
    debounce(function () {
      var q = $("#buscar_nombre").val();
      formData_filtro.set("nombre", q);
      clearAndFetchProducts();
    }, 300)
  );

  // Filtro por categoría (si usas select)
  $("#categoria_filtroMarketplace").change(function () {
    var categoria = $("#categoria_filtroMarketplace").val();
    formData_filtro.set("linea", categoria);
    handleSelectChange();
  });

  // Filtro por proveedor (si usas select)
  $("#proveedor_filtroMarketplace").change(function () {
    var proveedor = $("#proveedor_filtroMarketplace").val();
    formData_filtro.set("plataforma", proveedor);
    handleSelectChange();
  });

  // Switch de favoritos
  $("#favoritosSwitch").change(function () {
    var estado = $(this).is(":checked") ? 1 : 0;
    formData_filtro.set("favorito", estado);
    clearAndFetchProducts();
  });

  // Botón “Cargar más”
  loadMoreButton.addEventListener("click", () => {
    if (!isLoading) {
      isLoading = true;
      loadingIndicator.style.display = "block";
      currentPage++;
      fetchProducts(false);
    }
  });

  /*****************************************************
   * Cargar chips de categorías y proveedores (chips)
   * (Toggling: si clicas el chip ya seleccionado, lo quita)
   *****************************************************/
  // Cargar categorías
  $.ajax({
    url: SERVERURL + "productos/cargar_categorias",
    type: "GET",
    dataType: "json",
    success: function (response) {
      if (Array.isArray(response)) {
        const sliderCategorias = document.getElementById("sliderCategorias");
        sliderCategorias.innerHTML = ""; // Limpia antes de insertar

        response.forEach(function (categoria) {
          const chip = document.createElement("div");
          chip.classList.add("slider-chip");
          chip.textContent = categoria.nombre_linea;
          chip.dataset.catId = categoria.id_linea;

          // Agregar el ícono de categoría (FontAwesome)
          chip.innerHTML = `<i class="fas fa-tags"></i> ${categoria.nombre_linea}`;

          // Toggle logic
          chip.addEventListener("click", function (e) {
            const clickedChip = e.currentTarget;

            // ¿Ya estaba seleccionado?
            if (clickedChip.classList.contains("selected")) {
              // Lo des-seleccionamos
              clickedChip.classList.remove("selected");
              formData_filtro.set("linea", ""); // Limpia el filtro de categoría
            } else {
              // Deseleccionar otros chips
              document
                .querySelectorAll("#sliderCategorias .slider-chip")
                .forEach((el) => el.classList.remove("selected"));
              // Seleccionar el clicado
              clickedChip.classList.add("selected");
              // Asignar filtro
              formData_filtro.set("linea", clickedChip.dataset.catId);
            }
            clearAndFetchProducts();
          });

          sliderCategorias.appendChild(chip);
        });
      } else {
        console.log("La respuesta de la API no es un array:", response);
      }
    },
    error: function (error) {
      console.error("Error al obtener la lista de categorias:", error);
    },
  });

  // Cargar proveedores
  $.ajax({
    url: SERVERURL + "marketplace/obtenerProveedores",
    type: "GET",
    dataType: "json",
    success: function (response) {
      console.log("Respuesta de obtener proveedores:", response);
      if (Array.isArray(response)) {
        const sliderProveedores = document.getElementById("sliderProveedores");
        sliderProveedores.innerHTML = ""; // Limpia antes de insertar

        response.forEach(function (proveedor) {
          const chipProv = document.createElement("div");
          chipProv.classList.add("slider-chip");
          chipProv.textContent = proveedor.nombre_tienda.toUpperCase();
          chipProv.dataset.provId = proveedor.id_plataforma;

          // Agregar el ícono de un camión o tienda (FontAwesome)
          chipProv.innerHTML = `<i class="fas fa-truck-moving"></i> ${proveedor.nombre_tienda.toUpperCase()}`;


          // Toggle logic
          chipProv.addEventListener("click", function (e) {
            const clickedProvChip = e.currentTarget;
            // ¿Ya estaba seleccionado?
            if (clickedProvChip.classList.contains("selected")) {
              // Lo des-seleccionamos
              clickedProvChip.classList.remove("selected");
              formData_filtro.set("plataforma", "");
            } else {
              // Deseleccionar otros
              document
                .querySelectorAll("#sliderProveedores .slider-chip")
                .forEach((el) => el.classList.remove("selected"));
              clickedProvChip.classList.add("selected");
              formData_filtro.set("plataforma", clickedProvChip.dataset.provId);
            }
            clearAndFetchProducts();
          });

          sliderProveedores.appendChild(chipProv);
        });
      } else {
        console.log("La respuesta de la API no es un array:", response);
      }
    },
    error: function (error) {
      console.error("Error al obtener la lista de proveedores:", error);
    },
  });
}); // Fin DOMContentLoaded

/************************************************
 * FUNCIONES FUERA DE DOMContentLoaded
 * (para poder llamarlas con onclick, etc.)
 ************************************************/

function copyToClipboard(id) {
  navigator.clipboard.writeText(id).then(
    function () {
      toastr.success("ID " + id + " COPIADA CON EXITO", "NOTIFICACIÓN", {
        positionClass: "toast-bottom-center",
      });
    },
    function (err) {
      console.error("Error al copiar al portapapeles: ", err);
    }
  );

  /* mandar a shopify */
  let formData = new FormData();
  formData.append("id_inventario", id);
  $.ajax({
    url: SERVERURL + "Productos/importar_productos_shopify",
    type: "POST",
    data: formData,
    processData: false,
    contentType: false,
    success: function (response) {
      if (response.status == 500) {
        toastr.error("NO SE AGREGO CORRECTAMENTE a shopify", "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });
      } else if (response.status == 200) {
        toastr.success("PRODUCTO AGREGADO CORRECTAMENTE", "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      alert(errorThrown);
    },
  });
}

// Función para manejar el clic en el botón de corazón
function handleHeartClick(productId, esFavorito) {
  const heartButton = event.currentTarget;
  const newFavoritoStatus = !heartButton.classList.toggle("clicked");

  let formData_favoritos = new FormData();
  formData_favoritos.append("id_producto", productId);
  formData_favoritos.append("favorito", newFavoritoStatus ? 1 : 0);

  $.ajax({
    url: SERVERURL + "marketplace/agregarFavoritos",
    type: "POST",
    data: formData_favoritos,
    processData: false,
    contentType: false,
    success: function (response) {
      console.log("Producto actualizado:", response);
    },
    error: function (error) {
      console.error("Error al actualizar el producto:", error);
    },
  });
}

//agregar informacion al modal descripcion marketplace
function agregarModal_marketplace(id) {
  // Limpiar el carrusel y las miniaturas antes de agregar nuevas imágenes
  $(".carousel-inner").html("");
  $(".carousel-thumbnails").html("");

  $.ajax({
    type: "POST",
    url: SERVERURL + "marketplace/obtener_producto/" + id,
    dataType: "json",
    success: function (response) {
      if (response) {
        const data = response[0];

        $("#codigo_producto").text(data.codigo_producto);
        $("#nombre_producto").text(data.nombre_producto);
        $("#precio_proveedor").text(data.pcp);
        $("#precio_sugerido").text(data.pvp);
        $("#stock").text(data.saldo_stock);
        $("#nombre_proveedor").text(data.contacto);
        $("#telefono_proveedor").text(formatPhoneNumber(data.whatsapp));
        $("#descripcion").text(data.descripcion_producto);

        var imagen_descripcion = obtenerURLImagen(data.image_path, SERVERURL);

        // Agregar la imagen principal al carrusel y su miniatura
        $(".carousel-inner").append(`
          <div class="carousel-item active">
            <img src="${imagen_descripcion}" class="d-block w-100 fixed-size-img" alt="Product Image 1">
          </div>
        `);

        $(".carousel-thumbnails").append(`
          <img src="${imagen_descripcion}" class="img-thumbnail mx-1" alt="Thumbnail 1" data-bs-target="#productCarousel" data-bs-slide-to="0">
        `);

        let formData = new FormData();
        formData.append("id_producto", id);

        // Hacer la solicitud para obtener las imágenes adicionales
        $.ajax({
          url: SERVERURL + "Productos/listar_imagenAdicional_productos",
          type: "POST",
          data: formData,
          processData: false,
          contentType: false,
          dataType: "json",
          success: function (response) {
            if (response && response.length > 0) {
              response.forEach(function (imgData, index) {
                var imgURL = obtenerURLImagen(imgData.url, SERVERURL);

                $(".carousel-inner").append(`
                  <div class="carousel-item">
                    <img src="${imgURL}" class="d-block w-100 fixed-size-img" alt="Product Image ${index + 2}">
                  </div>
                `);

                $(".carousel-thumbnails").append(`
                  <img src="${imgURL}" class="img-thumbnail mx-1" alt="Thumbnail ${index + 2}" data-bs-target="#productCarousel" data-bs-slide-to="${index + 1}">
                `);
              });
            } else {
              console.error("No se encontraron imágenes adicionales.");
            }
          },
          error: function (jqXHR, textStatus, errorThrown) {
            console.error(
              "Error al obtener imágenes adicionales:",
              errorThrown
            );
          },
        });

        // Abrir el modal
        $("#descripcion_productModal").modal("show");
      } else {
        console.error("La respuesta está vacía o tiene un formato incorrecto.");
      }
    },
    error: function (xhr, status, error) {
      console.error("Error en la solicitud AJAX:", error);
      alert("Hubo un problema al obtener la información del producto");
    },
  });
}

function procesarPlataforma(url) {
  let sinProtocolo = url.replace("https://", "");
  let primerPunto = sinProtocolo.indexOf(".");
  let baseNombre = sinProtocolo.substring(0, primerPunto);
  return baseNombre.toUpperCase();
}

//abrir modal de seleccion de producto con atributo especifico
function abrir_modalSeleccionAtributo(id) {
  $("#id_productoSeleccionado").val(id);
  initDataTableSeleccionProductoAtributo();
  $("#seleccionProdcutoAtributoModal").modal("show");
}

function abrir_modal_idInventario(id) {
  $("#id_productoIventario").val(id);
  initDataTableTablaIdInventario();
  $("#tabla_idInventarioModal").modal("show");
}

//enviar cliente
function enviar_cliente(id, sku, pvp, id_inventario) {
  const formData = new FormData();
  formData.append("cantidad", 1);
  formData.append("precio", pvp);
  formData.append("id_producto", id);
  formData.append("sku", sku);
  formData.append("id_inventario", id_inventario);

  $.ajax({
    type: "POST",
    url: "" + SERVERURL + "marketplace/agregarTmp",
    data: formData,
    processData: false,
    contentType: false,
    success: function (response2) {
      response2 = JSON.parse(response2);
      if (response2.status == 500) {
        Swal.fire({
          icon: "error",
          title: response2.title,
          text: response2.message,
        });
      } else if (response2.status == 200) {
        window.location.href =
          SERVERURL + "Pedidos/nuevo?id_producto=" + id + "&sku=" + sku;
      }
    },
    error: function (xhr, status, error) {
      console.error("Error en la solicitud AJAX:", error);
      alert("Hubo un problema al agregar el producto temporalmente");
    },
  });
}

function formatPhoneNumber(number) {
  // Eliminar caracteres no numéricos excepto el signo +
  number = number.replace(/[^\d+]/g, "");
  if (/^\+593/.test(number)) {
    return number;
  } else if (/^593/.test(number)) {
    return "+" + number;
  } else {
    if (number.startsWith("0")) {
      number = number.substring(1);
    }
    return "+593" + number;
  }
}

function abrirModal_infoTienda(tienda) {
  let formData = new FormData();
  formData.append("tienda", tienda);

  $.ajax({
    url: SERVERURL + "pedidos/datosPlataformas",
    type: "POST",
    data: formData,
    processData: false,
    contentType: false,
    success: function (response) {
      response = JSON.parse(response);
      $("#nombreTienda").val(response[0].nombre_tienda);
      $("#telefonoTienda").val(response[0].whatsapp);
      $("#correoTienda").val(response[0].email);
      $("#enlaceTienda").val(response[0].url_imporsuit);

      $("#infoTiendaModal").modal("show");
    },
    error: function (jqXHR, textStatus, errorThrown) {
      alert(errorThrown);
    },
  });
}

function obtenerURLImagen(imagePath, serverURL) {
  if (imagePath) {
    // Verificar si el imagePath ya es una URL completa
    if (imagePath.startsWith("http://") || imagePath.startsWith("https://")) {
      return imagePath;
    } else {
      if (
        imagePath.includes("../") ||
        imagePath.includes("..\\") ||
        imagePath === "" ||
        imagePath === "."
      ) {
        return serverURL + "public/img/broken-image.png";
      }
      return `${serverURL}${imagePath}`;
    }
  } else {
    console.error("imagePath es null o undefined");
    return serverURL + "public/img/broken-image.png";
  }
}
