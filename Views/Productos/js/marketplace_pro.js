let formData_filtro;let currentPage = 1;          // Página actual
const pageSize = 35;          // Cantidad de productos por página
let isLoading = false;        // Para evitar clicks múltiples
let products = [];            // Acumularemos aquí todos los productos que se han ido cargando

let currentAPI = "marketplace/obtener_productos_paginados";

// "requestId" global para ignorar respuestas viejas (tanto en fetchProducts como en fetchProductDetails)
let lastFetchId = 0;

/************************************************
 * FUNCIONES FUERA DE DOMContentLoaded
 * (para poder llamarlas con onclick, etc.)
 ************************************************/
function copyToClipboard(id) {
  navigator.clipboard.writeText(id).then(
    function () {
      toastr.success("ID " + id + " COPIADA CON ÉXITO", "NOTIFICACIÓN", {
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
        toastr.error("NO SE AGREGO CORRECTAMENTE a Shopify", "NOTIFICACIÓN", {
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

// Redirige a ver producto
function verProducto(id) {
  window.location.href = SERVERURL + "Productos/products_page?id=" + id;
}

// Abrir modal de selección de producto con atributo específico
function abrir_modalSeleccionAtributo(id) {
  $("#id_productoSeleccionado").val(id);
  initDataTableSeleccionProductoAtributo();
  $("#seleccionProdcutoAtributoModal").modal("show");
}

// Abrir modal id Inventario
function abrir_modal_idInventario(id) {
  $("#id_productoIventario").val(id);
  initDataTableTablaIdInventario();
  $("#tabla_idInventarioModal").modal("show");
}

// Enviar cliente
function enviar_cliente(id, sku, pvp, id_inventario) {
  const formData = new FormData();
  formData.append("cantidad", 1);
  formData.append("precio", pvp);
  formData.append("id_producto", id);
  formData.append("sku", sku);
  formData.append("id_inventario", id_inventario);

  $.ajax({
    type: "POST",
    url: SERVERURL + "marketplace/agregarTmp",
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

/************************************************
 * MAIN DOMContentLoaded
 ************************************************/
document.addEventListener("DOMContentLoaded", function () {
  // Inicializamos el formData para filtros
  formData_filtro = new FormData();
  formData_filtro.append("nombre", "");
  formData_filtro.append("linea", "");
  formData_filtro.append("plataforma", "");
  formData_filtro.append("min", "");
  formData_filtro.append("max", "");
  formData_filtro.append("favorito", "0");
  formData_filtro.append("vendido", "0");
  formData_filtro.append("id", "");

  const cardContainer = document.getElementById("card-container");
  const loadingIndicator = document.getElementById("loading-indicator");
  const loadMoreButton = document.getElementById("load-more");
  const sliderProveedores = document.getElementById("sliderProveedores");
  const rightArrow = document.getElementById("sliderProveedoresRight");

  // Revisamos si llegó algún parámetro en la URL (plataforma=xxx)
  const urlParams = new URLSearchParams(window.location.search);
  const plataformaParam = urlParams.get("plataforma");
  if (plataformaParam) {
    formData_filtro.set("plataforma", plataformaParam);
  }

  // Vaciar tmp pedidos al arrancar
  const vaciarTmpPedidos = async () => {
    try {
      const response = await fetch(SERVERURL + "marketplace/vaciarTmp");
      if (!response.ok) {
        throw new Error("Error al vaciar los pedidos temporales");
      }
      const data = await response.json();
      console.log("Respuesta de vaciarTmp:", data);
    } catch (error) {
      console.error("Error al hacer la solicitud:", error);
    }
  };

  // Flecha derecha para scrollear proveedores
  rightArrow.addEventListener("click", () => {
    sliderProveedores.scrollBy({ left: 200, behavior: "smooth" });
  });

  /************************************************
   * FUNCIÓN PRINCIPAL DE PAGINACIÓN
   ************************************************/
  async function fetchProducts(reset = false) {
    // Incrementamos el requestId global y almacenamos
    const thisFetchId = ++lastFetchId;
    console.log(
      "Llamada a fetchProducts. reset:", reset,
      "fetchId:", thisFetchId,
      "formData_filtro:",
      [...formData_filtro.entries()]
    );

    if (reset) {
      // Reiniciamos estados
      isLoading = true;
      loadingIndicator.style.display = "block";
      cardContainer.innerHTML = "";
      products = [];
      currentPage = 1;
      loadMoreButton.style.display = "none";
      document.getElementById("no-more-products").style.display = "none";
    }

    // Establecemos en el formData la página y el límite
    formData_filtro.set("page", currentPage);
    formData_filtro.set("limit", pageSize);

    try {
      const response = await fetch(SERVERURL + currentAPI, {
        method: "POST",
        body: formData_filtro,
      });
      if (!response.ok) {
        throw new Error("Error al obtener los productos");
      }

      const newProducts = await response.json();

      // Verificamos si sigue siendo la petición activa
      if (thisFetchId !== lastFetchId) {
        console.warn("Descartando respuesta obsoleta de fetchProducts");
        return;
      }

      console.log("Respuesta de fetch:", newProducts, " fetchId:", thisFetchId);

      if (!Array.isArray(newProducts) || newProducts.length === 0) {
        loadMoreButton.style.display = "none";
        document.getElementById("no-more-products").style.display = "block";
        return;
      }

      // Agregamos al arreglo global
      products = [...products, ...newProducts];

      // Mostramos en pantalla (por cada producto, pide detalles)
      displayProducts(newProducts, thisFetchId);

      if (newProducts.length < pageSize) {
        loadMoreButton.style.display = "none";
        document.getElementById("no-more-products").style.display = "block";
      } else {
        loadMoreButton.style.display = "block";
        document.getElementById("no-more-products").style.display = "none";
      }
    } catch (error) {
      console.error("Error al obtener los productos:", error);
    } finally {
      isLoading = false;
      loadingIndicator.style.display = "none";
    }
  }

  /************************************************
   * Mostrar en la página (llama a detalles por producto)
   ************************************************/
  // Nota: Ahora también le pasamos "fetchId" a displayProducts.
  function displayProducts(productsArray, fetchId) {
    for (const product of productsArray) {
      // Para cada producto, hacemos una llamada a fetchProductDetails(productId, fetchId).
      fetchProductDetails(product.id_producto, fetchId).then((productDetails) => {
        // Si la respuesta de "detalles" llega tarde y ya no coincide el fetchId, lo ignoramos.
        if (fetchId !== lastFetchId) {
          console.warn("Descartando detalle obsoleto (productId=" + product.id_producto + ")");
          return;
        }

        if (productDetails && productDetails.length > 0) {
          createProductCard(product, productDetails[0]);
        }
      });
    }
  }

  // Ahora fetchProductDetails recibe también "fetchId", aunque aquí solo lo usamos
  // si quisiéramos abortar o depurar. Realmente la comparación final la hacemos en el .then() de arriba.
  async function fetchProductDetails(productId, fetchId) {
    try {
      const response = await fetch(SERVERURL + "marketplace/obtener_producto/" + productId);
      if (!response.ok) {
        return null;
      }
      return response.json();
    } catch (error) {
      console.error("Error al obtener detalles del producto:", error);
      return null;
    }
  }

  function createProductCard(product, productDetails) {
    const cardContainer = document.getElementById("card-container");
    const { pcp, pvp, saldo_stock, url_imporsuit } = productDetails;

    let boton_enviarCliente = "";
    let botonId_inventario = "";

    if (product.producto_variable == 0) {
      boton_enviarCliente = `
        <button class="btn btn-import d-flex align-items-center justify-content-center w-100" 
          onclick="enviar_cliente(${product.id_producto},'${product.sku}',${product.pvp},${product.id_inventario})">
          <i class='bx bx-send me-2'></i> Enviar a cliente
        </button>
      `;
      botonId_inventario = `
        <div class="card-id-container" onclick="copyToClipboard(${product.id_inventario})">
          <span class="card-id">ID: ${product.id_inventario}</span>
        </div>
      `;
    } else {
      // Producto variable
      boton_enviarCliente = `
        <button class="btn btn-import d-flex align-items-center justify-content-center w-100" 
          onclick="abrir_modalSeleccionAtributo(${product.id_producto})">
          <i class='bx bx-send me-2'></i> Enviar a cliente
        </button>
      `;
      botonId_inventario = `
        <div class="card-id-container" onclick="abrir_modal_idInventario(${product.id_producto})">
          <span class="card-id">Ver IDs de producto variable</span>
        </div>
      `;
    }

    const esFavorito = product.Es_Favorito === "1";

    // Creación del contenedor principal
    const card = document.createElement("div");
    card.className = "card-custom position-relative card-clickable";

    // Imagen principal
    let imagePath = obtenerURLImagen(productDetails.image_path, SERVERURL);

    // Verificamos si la imagen realmente existe
    // Verificamos si la imagen realmente existe
    let validador_imagen = verificarImagen(imagePath);
    if (validador_imagen == 0) {
      imagePath = SERVERURL + "public/img/broken-image.png";
    }

    // Estructura HTML de la tarjeta
    card.innerHTML = `
      <div class="image-container position-relative">
        ${botonId_inventario}
        <img src="${imagePath}" class="card-img-top" alt="Imagen del producto">
        <div class="add-to-store-button ${product.agregadoTienda ? "added" : ""}" 
            data-product-id="${product.id_producto}">
          <span class="plus-icon">+</span>
          <span class="add-to-store-text">
            ${product.agregadoTienda ? "Quitar de tienda" : "Añadir a tienda"}
          </span>
        </div>
        <div class="add-to-funnel-button ${product.agregadoFunnel ? "added" : ""}" 
            data-funnel-id="${product.id_inventario}">
          <span class="plus-icon">+</span>
          <span class="add-to-funnel-text">
            ${product.agregadoFunnel ? "Quitar de funnel" : "Añadir a funnel"}
          </span>
        </div>
        <button class="btn-heart ${esFavorito ? "clicked" : ""}" 
                onclick="handleHeartClick(${product.id_producto}, ${esFavorito})">
          <i class="fas fa-heart"></i>
        </button>
      </div>
      <div class="card-header">
        <span class="card-category">${product.categoria || "Sin Categoría"}</span>
        <span class="card-stock text-success">Stock: <strong>${saldo_stock}</strong></span>
      </div>
      <div class="card-body text-center d-flex flex-column justify-content-between">
        <div>
          <h6 class="card-title">${product.nombre_producto}</h6>
          <p class="card-subtitle">
            Proveedor: 
            <a href="#" onclick="abrirModal_infoTienda('${url_imporsuit}')" style="font-size: 15px;">
              ${productDetails.nombre_tienda || "Proveedor desconocido"}
            </a>
          </p>
        </div>
        <div class="card-pricing">
          <span class="precio-proveedor">Precio proveedor: <strong>$${pcp}</strong></span>
          <span class="precio-sugerido">Precio sugerido: <strong>$${pvp}</strong></span>
        </div>
        <div class="card-buttons d-flex flex-column gap-2">
          <button class="btn btn-description d-flex align-items-center justify-content-center w-100">
            <i class='bx bx-info-circle me-2'></i> Ver producto
          </button>
          ${boton_enviarCliente}
        </div>
      </div>
    `;

    // Click general en la tarjeta (salvo en botones específicos)
    card.addEventListener("click", (e) => {
      if (
        e.target.closest(".btn-heart") ||
        e.target.closest(".add-to-store-button") ||
        e.target.closest(".add-to-funnel-button") ||
        e.target.closest(".btn-import") ||
        e.target.closest(".card-id-container")
      ) {
        return;
      }
      verProducto(product.id_producto);
    });

    cardContainer.appendChild(card);
  }

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
   * Botón para mostrar/ocultar input de búsqueda proveedor
   ************************************************/
  document.getElementById("toggleSearch").addEventListener("click", function () {
    const input = document.getElementById("buscar_proveedor");
    if (input.style.display === "none") {
      input.style.display = "block";
      input.focus();
    } else {
      input.style.display = "none";
    }
  });

  /************************************************
   * PREPARAMOS SLIDER DE PRECIO (noUiSlider)
   ************************************************/
  var slider = document.getElementById("price-range-slider");
  var priceMin = document.getElementById("price-min");
  var priceMax = document.getElementById("price-max");

  // Función para aplicar un "debounce"
  function debounce(func, wait) {
    let timeout;
    return function (...args) {
      const context = this;
      clearTimeout(timeout);
      timeout = setTimeout(() => func.apply(context, args), wait);
    };
  }

  // Al iniciar, vaciamos tmp
  vaciarTmpPedidos().then(() => {
    // Obtenemos el precio máximo para el slider
    fetch(SERVERURL + "marketplace/obtenerMaximo")
      .then((response) => {
        if (!response.ok) {
          throw new Error("Network response was not ok");
        }
        return response.json();
      })
      .then((data) => {
        let data_precioMaximo = parseFloat(data);
        if (isNaN(data_precioMaximo)) {
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

        // Ajustamos en el formData_filtro para que inicialmente sea [0, máx]
        formData_filtro.set("min", 0);
        formData_filtro.set("max", data_precioMaximo);

        slider.noUiSlider.on("update", function (values, handle) {
          if (handle === 0) {
            priceMin.value = values[0];
          } else {
            priceMax.value = values[1];
          }
        });

        slider.noUiSlider.on("change", function (values) {
          let min = values[0].replace("$", "").replace(",", "");
          let max = values[1].replace("$", "").replace(",", "");
          formData_filtro.set("min", min);
          formData_filtro.set("max", max);

          // Reiniciar y pedir la página 1
          fetchProducts(true);
        });

        // Finalmente, cargamos la primera página
        fetchProducts(true); // reset = true
      })
      .catch((error) => {
        console.error("Error fetching max price:", error);
        // Si ocurre error, definimos un rango default
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
   * FILTROS DE TEXTO, SELECT, CHECKBOX, ETC.
   ************************************************/
  $("#buscar_nombre").on(
    "input",
    debounce(function () {
      let q = $("#buscar_nombre").val().trim();
      if (/^\d+$/.test(q)) {
        // Si es numérico => buscar por ID inventario
        formData_filtro.set("id", q);
        formData_filtro.set("nombre", "");
      } else {
        // Si es texto => buscar por nombre
        formData_filtro.delete("id");
        formData_filtro.set("nombre", q);
      }
      fetchProducts(true);
    }, 300)
  );

  // Filtro por categoría
  $("#categoria_filtroMarketplace").change(
    debounce(function () {
      let categoria = $("#categoria_filtroMarketplace").val();
      formData_filtro.set("linea", categoria);
      fetchProducts(true);
    }, 300)
  );

  // Filtro por proveedor (select)
  $("#proveedor_filtroMarketplace").change(
    debounce(function () {
      let proveedor = $("#proveedor_filtroMarketplace").val();
      formData_filtro.set("plataforma", proveedor);
      fetchProducts(true);
    }, 300)
  );

  // Switch de favoritos
  $("#favoritosSwitch").change(function () {
    let estado = $(this).is(":checked") ? 1 : 0;
    formData_filtro.set("favorito", estado);
    fetchProducts(true);
  });

  // Switch de vendidos
  $("#vendidosSwitch").change(function () {
    let estado = $(this).is(":checked") ? 1 : 0;
    formData_filtro.set("vendido", estado);
    fetchProducts(true);
  });

  // Switch de privados
  $("#privadosSwitch").change(function () {
    let estado = $(this).is(":checked") ? 1 : 0;
    currentAPI = (estado === 1)
      ? "marketplace/obtener_productos_privados"
      : "marketplace/obtener_productos_paginados";

    fetchProducts(true);
  });

  /************************************************
   * BOTÓN "CARGAR MÁS"
   ************************************************/
  loadMoreButton.addEventListener("click", () => {
    if (!isLoading) {
      isLoading = true;
      loadingIndicator.style.display = "block";
      currentPage++;
      fetchProducts(false);
    }
  });

  /************************************************
   * Eventos de click global (para Añadir a Tienda / Funnel)
   ************************************************/
  document.getElementById("card-container").addEventListener("click", (event) => {
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
      // Redirección a tu funnel
      window.location.href = SERVERURL + "funnelish/constructor_vista/" + funnelId;
    }
  });

  /*****************************************************
   * Cargar chips de categorías y proveedores (dinámicos)
   *****************************************************/
  // Cargar categorías en el <select> "categoria_filtroMarketplace"
  $.ajax({
    url: SERVERURL + "productos/cargar_categorias",
    type: "GET",
    dataType: "json",
    success: function (response) {
      if (Array.isArray(response)) {
        response.forEach(function (categoria) {
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

  // Cargar chips de proveedores
  $.ajax({
    url: SERVERURL + "marketplace/obtenerProveedoresConProductosCategorias",
    type: "GET",
    dataType: "json",
    success: function (response) {
      if (Array.isArray(response)) {
        sliderProveedores.innerHTML = ""; // Limpia antes de insertar

        response.forEach((proveedor) => {
          const chipProv = document.createElement("div");
          chipProv.classList.add("slider-chip");
          chipProv.dataset.provId = proveedor.id_plataforma;

          const imageSrc = proveedor.image
            ? SERVERURL + proveedor.image
            : SERVERURL + "public/img/icons/proveedor.png";

          // Truncar el nombre si es muy largo
          let nombreTienda = proveedor.nombre_tienda
            ? proveedor.nombre_tienda.toUpperCase()
            : "SIN NOMBRE";
          if (nombreTienda.length > 20) {
            nombreTienda = nombreTienda.substring(0, 17) + "...";
          }

          // Convertir string de categorías a array
          const categoriasArray = proveedor.categorias
            ? proveedor.categorias.split(",").map((cat) => cat.trim())
            : [];
          let categoriasMostradas = categoriasArray.length
            ? categoriasArray.slice(0, 3).join(", ")
            : "Sin categorías";
          if (categoriasMostradas.length > 30) {
            categoriasMostradas = categoriasMostradas.substring(0, 27) + "...";
          }

          chipProv.innerHTML = `
            <div class="chip-content">
              <img src="${imageSrc}" class="icon-chip" alt="Logo"> 
              <div class="chip-text">
                <span class="chip-title">${nombreTienda}</span>
                <span class="chip-count">${proveedor.cantidad_productos} productos</span>
                <span class="chip-categories">${categoriasMostradas}</span>
              </div>
            </div>
          `;

          // Toggle logic para seleccionar/deseleccionar
          chipProv.addEventListener("click", function (e) {
            const clickedProvChip = e.currentTarget;
            const isSelected = clickedProvChip.classList.contains("selected");

            // Deseleccionar todos
            document
              .querySelectorAll("#sliderProveedores .slider-chip")
              .forEach((el) => el.classList.remove("selected"));

            if (isSelected) {
              clickedProvChip.classList.remove("selected");
              formData_filtro.set("plataforma", "");
            } else {
              clickedProvChip.classList.add("selected");
              formData_filtro.set("plataforma", clickedProvChip.dataset.provId);
            }
            // Reiniciamos la paginación
            fetchProducts(true);
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

  // Búsqueda de proveedores en el slider
  $("#buscar_proveedor").on("input", function () {
    let searchValue = $(this).val().toLowerCase().trim();
    let providerToScroll = null;

    $("#sliderProveedores .slider-chip").each(function () {
      let providerName = $(this).find(".chip-title").text().toLowerCase();
      if (providerName.includes(searchValue)) {
        providerToScroll = $(this);
        return false; // salir del bucle al encontrar la coincidencia
      }
    });

    // Si el input está vacío, quitamos toda selección
    if (searchValue === "") {
      $("#sliderProveedores .slider-chip").removeClass("selected");
      formData_filtro.set("plataforma", "");
      fetchProducts(true);
    }

    // Hacer scroll al proveedor encontrado
    if (providerToScroll) {
      let container = $("#sliderProveedores");
      let containerOffsetLeft = container.offset().left;
      let itemOffsetLeft = providerToScroll.offset().left;
      let currentScrollLeft = container.scrollLeft();
      let scrollValue = currentScrollLeft + (itemOffsetLeft - containerOffsetLeft) - 30;
      container.animate({ scrollLeft: scrollValue }, 400);
    }
  });
});
