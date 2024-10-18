document.addEventListener("DOMContentLoaded", function () {
  const initialProductsPerPage = 24;
  const additionalProductsPerPage = 24;
  let currentPage = 1;
  let products = [];
  let displayedProducts = new Set();
  let formData_filtro = new FormData();
  formData_filtro.append("nombre", "");
  formData_filtro.append("linea", "");
  formData_filtro.append("plataforma", "");
  formData_filtro.append("min", "");
  formData_filtro.append("max", "");
  formData_filtro.append("favorito", "0");

  const cardContainer = document.getElementById("card-container");
  const loadingIndicator = document.getElementById("loading-indicator");
  const loadMoreButton = document.getElementById("load-more");
  let isLoading = false;
  let currentFetchController = null;
  let currentDisplayController = null;

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
    let validador_imagen = 1;
    validador_imagen = verificarImagen(imagePath);

    if (validador_imagen == 0) {
      imagePath = SERVERURL + "public/img/broken-image.png";
    }

    card.innerHTML = `
      <div class="image-container position-relative">
        ${botonId_inventario}
        <img src="${imagePath}" class="card-img-top" alt="Product Image">
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
        } data-funnel-id="${product.id_producto}">
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

  fetchProducts();

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
  });

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

  /* Filtros */

  var slider = document.getElementById("price-range-slider");
  var priceMin = document.getElementById("price-min");
  var priceMax = document.getElementById("price-max");

  fetch(`${SERVERURL}marketplace/obtenerMaximo`)
    .then((response) => {
      if (!response.ok) {
        throw new Error("Network response was not ok");
      }
      return response.json();
    })
    .then((data) => {
      let data_precioMaximo = parseFloat(data);

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
        clearAndFetchProducts(); // Reset and fetch products based on new filter
      });
    })
    .catch((error) => {
      console.error("Error fetching max price:", error);
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
    });

  /* Fin Filtros */

  const handleSelectChange = debounce(function () {
    clearAndFetchProducts();
  }, 300);

  $("#buscar_nombre").on(
    "input",
    debounce(function () {
      var q = $("#buscar_nombre").val();
      formData_filtro.set("nombre", q);
      clearAndFetchProducts(); // Reset and fetch products based on new filter
    }, 300)
  );

  $("#categoria_filtroMarketplace").change(function () {
    var categoria = $("#categoria_filtroMarketplace").val();
    formData_filtro.set("linea", categoria);
    handleSelectChange();
  });

  $("#proveedor_filtroMarketplace").change(function () {
    var proveedor = $("#proveedor_filtroMarketplace").val();
    formData_filtro.set("plataforma", proveedor);
    handleSelectChange();
  });

  $("#favoritosSwitch").change(function () {
    var estado = $(this).is(":checked") ? 1 : 0;
    formData_filtro.set("favorito", estado);
    clearAndFetchProducts(); // Reset and fetch products based on new filter
  });

  loadMoreButton.addEventListener("click", () => {
    if (!isLoading) {
      isLoading = true;
      loadingIndicator.style.display = "block";
      currentPage++;
      fetchProducts(false);
    }
  });
});

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
    processData: false, // No procesar los datos
    contentType: false, // No establecer ningún tipo de contenido
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

  /* fin mandar a shopify */
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
    processData: false, // No procesar los datos
    contentType: false, // No establecer ningún tipo de contenido
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
  $(".carousel-inner").html(""); // Limpia todas las imágenes del carrusel
  $(".carousel-thumbnails").html(""); // Limpia todas las miniaturas del carrusel

  $.ajax({
    type: "POST",
    url: SERVERURL + "marketplace/obtener_producto/" + id,
    dataType: "json",
    success: function (response) {
      if (response) {
        // Obtener el primer objeto de la respuesta
        const data = response[0];

        // Llenar los elementos <span> del modal con los datos recibidos
        $("#codigo_producto").text(data.codigo_producto);
        $("#nombre_producto").text(data.nombre_producto);
        $("#precio_proveedor").text(data.pcp);
        $("#precio_sugerido").text(data.pvp);
        $("#stock").text(data.saldo_stock);
        $("#nombre_proveedor").text(data.contacto);
        $("#telefono_proveedor").text(formatPhoneNumber(data.whatsapp));
        $("#descripcion").text(data.descripcion_producto);

        // Obtener la URL de la imagen principal desde la primera llamada AJAX
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
          processData: false, // No procesar los datos
          contentType: false, // No establecer ningún tipo de contenido
          dataType: "json",
          success: function (response) {
            if (response && response.length > 0) {
              // Recorrer las imágenes adicionales y añadirlas al carrusel y a las miniaturas
              response.forEach(function (imgData, index) {
                var imgURL = obtenerURLImagen(imgData.url, SERVERURL);

                // Agregar las imágenes adicionales al carrusel
                $(".carousel-inner").append(`
                  <div class="carousel-item">
                    <img src="${imgURL}" class="d-block w-100 fixed-size-img" alt="Product Image ${index + 2}">
                  </div>
                `);

                // Agregar las miniaturas adicionales
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
  // Eliminar el "https://"
  let sinProtocolo = url.replace("https://", "");

  // Encontrar la posición del primer punto
  let primerPunto = sinProtocolo.indexOf(".");

  // Obtener la subcadena desde el inicio hasta el primer punto
  let baseNombre = sinProtocolo.substring(0, primerPunto);

  // Convertir a mayúsculas
  let resultado = baseNombre.toUpperCase();

  return resultado;
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
  // Crear un objeto FormData y agregar los datos
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
      console.log(response2);
      console.log(response2[0]);
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

  // Verificar si el número ya tiene el código de país +593
  if (/^\+593/.test(number)) {
    // El número ya está correctamente formateado con +593
    return number;
  } else if (/^593/.test(number)) {
    // El número tiene 593 al inicio pero le falta el +
    return "+" + number;
  } else {
    // Si el número comienza con 0, quitarlo
    if (number.startsWith("0")) {
      number = number.substring(1);
    }
    // Agregar el código de país +593 al inicio del número
    number = "+593" + number;
  }

  return number;
}

// Función para vaciar temporalmente los pedidos
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

//cargar select categoria
$(document).ready(function () {
  // Realiza la solicitud AJAX para obtener la lista de categorias
  $.ajax({
    url: SERVERURL + "productos/cargar_categorias",
    type: "GET",
    dataType: "json",
    success: function (response) {
      // Asegúrate de que la respuesta es un array
      if (Array.isArray(response)) {
        response.forEach(function (categoria) {
          // Agrega una nueva opción al select por cada categoria
          $("#categoria_filtroMarketplace").append(
            new Option(categoria.nombre_linea, categoria.id_linea)
          );
        });
      } else {
        console.log("La respuesta de la API no es un array:", response);
      }
    },
    error: function (error) {
      console.error("Error al obtener la lista de categorias:", error);
    },
  });

  // Realiza la solicitud AJAX para obtener la lista de proveedores
  $.ajax({
    url: SERVERURL + "marketplace/obtenerProveedores",
    type: "GET",
    dataType: "json",
    success: function (response) {
      // Asegúrate de que la respuesta es un array
      if (Array.isArray(response)) {
        response.forEach(function (proveedor) {
          // Agrega una nueva opción al select por cada proveedor
          $("#proveedor_filtroMarketplace").append(
            new Option(
              proveedor.nombre_tienda.toUpperCase(),
              proveedor.id_plataforma
            )
          );
        });
      } else {
        console.log("La respuesta de la API no es un array:", response);
      }
    },
    error: function (error) {
      console.error("Error al obtener la lista de proveedores:", error);
    },
  });
});

// Ejecutar la función cuando la página se haya cargado
window.addEventListener("load", vaciarTmpPedidos);

/* abrir modal */
function abrirModal_infoTienda(tienda) {
  let formData = new FormData();
  formData.append("tienda", tienda);

  $.ajax({
    url: SERVERURL + "pedidos/datosPlataformas",
    type: "POST",
    data: formData,
    processData: false, // No procesar los datos
    contentType: false, // No establecer ningún tipo de contenido
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
  // Verificar si el imagePath no es null o undefined
  if (imagePath) {
    // Verificar si el imagePath ya es una URL completa
    if (imagePath.startsWith("http://") || imagePath.startsWith("https://")) {
      // Si ya es una URL completa, retornar solo el imagePath
      return imagePath;
    } else {
      // Verificar si el imagePath incluye rutas relativas inválidas
      if (
        imagePath.includes("../") ||
        imagePath.includes("..\\") ||
        imagePath === "" ||
        imagePath === "."
      ) {
        return serverURL + "public/img/broken-image.png"; // Ruta de imagen por defecto
      }
      // Si no es una URL completa, agregar el serverURL al inicio
      return `${serverURL}${imagePath}`;
    }
  } else {
    // Manejar el caso cuando imagePath es null o undefined
    console.error("imagePath es null o undefined");
    return serverURL + "public/img/broken-image.png"; // Ruta de imagen por defecto
  }
}
