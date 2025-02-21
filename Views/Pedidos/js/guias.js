let formData_filtro;
let lastLoadedProductId = null; // Último ID de producto cargado para control

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

  // Mandar a Shopify
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
    }
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
    }
  });
}

function verProducto(id) {
  window.location.href = SERVERURL + "Productos/products_page?id=" + id;
}

function procesarPlataforma(url) {
  if (url == null || url == "") {
    return "La tienda ya no existe";
  }
  let sinProtocolo = url.replace("https://", "");
  let primerPunto = sinProtocolo.indexOf(".");
  let baseNombre = sinProtocolo.substring(0, primerPunto);
  return baseNombre.toUpperCase();
}

// Abrir modal de selección de producto con atributo específico
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
    }
  });
}

function formatPhoneNumber(number) {
  number = number.replace(/[^\d+]/g, "");
  if (/^\+52/.test(number)) {
    return number;
  } else if (/^52/.test(number)) {
    return "+" + number;
  } else {
    if (number.startsWith("0")) {
      number = number.substring(1);
    }
    return "+52" + number;
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
    }
  });
}

function obtenerURLImagen(imagePath, serverURL) {
  if (imagePath) {
    if (imagePath.startsWith("http://") || imagePath.startsWith("https://")) {
      return imagePath;
    } else {
      if (imagePath.includes("../") || imagePath.includes("..\\") || imagePath === "" || imagePath === ".") {
        return serverURL + "public/img/broken-image.png";
      }
      return `${serverURL}${imagePath}`;
    }
  } else {
    console.error("imagePath es null o undefined");
    return serverURL + "public/img/broken-image.png";
  }
}

document.addEventListener("DOMContentLoaded", function () {
  formData_filtro = new FormData();
  formData_filtro.append("nombre", "");
  formData_filtro.append("linea", "");
  formData_filtro.append("plataforma", "");
  formData_filtro.append("min", "");
  formData_filtro.append("max", "");
  formData_filtro.append("favorito", "0");
  formData_filtro.append("vendido", "0");
  formData_filtro.append("id", "");

  const initialProductsPerPage = 24;
  const additionalProductsPerPage = 24;
  let currentPage = 1;
  let products = [];
  let displayedProducts = new Set();

  const cardContainer = document.getElementById("card-container");
  const loadingIndicator = document.getElementById("loading-indicator");
  const loadMoreButton = document.getElementById("load-more");
  const sliderProveedores = document.getElementById("sliderProveedores");
  const leftArrow = document.getElementById("sliderProveedoresLeft");
  const rightArrow = document.getElementById("sliderProveedoresRight");

  let isLoading = false;
  let currentFetchController = null;
  let currentDisplayController = null;

  // Revisamos si llegó algún parámetro en la URL (plataforma=xxx)
  const urlParams = new URLSearchParams(window.location.search);
  const plataformaParam = urlParams.get("plataforma");
  if (plataformaParam) {
    formData_filtro.set("plataforma", plataformaParam);
  }

  // Flecha derecha para el slider de proveedores
  rightArrow.addEventListener("click", () => {
    sliderProveedores.scrollBy({
      left: 200,
      behavior: "smooth"
    });
  });

  /************************************************
   * Función para vaciar pedidos temporales al cargar
   ************************************************/
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

  /************************************************
   * Filtrar, limpiar y recargar productos
   ************************************************/
  async function clearAndFetchProducts() {
    if (currentFetchController) {
      currentFetchController.abort();
    }
    if (currentDisplayController) {
      currentDisplayController.abort();
    }
    clearProductList();
    // Ocultar botón y mensaje antes de recargar
    loadMoreButton.style.display = "none";
    document.getElementById("no-more-products").style.display = "none";
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
      currentFetchController.abort();
    }
    currentFetchController = new AbortController();
    const { signal } = currentFetchController;
    if (reset) {
      isLoading = true;
      loadingIndicator.style.display = "block";
      clearProductList();
      lastLoadedProductId = null;
      loadMoreButton.style.display = "none";
      document.getElementById("no-more-products").style.display = "none";
    }
    try {
      const response = await fetch(`${SERVERURL}marketplace/obtener_productos`, {
        method: "POST",
        body: formData_filtro,
        signal,
      });
      const newProducts = await response.json();
      if (newProducts.length === 0) {
        loadMoreButton.style.display = "none";
        document.getElementById("no-more-products").style.display = "block";
        return;
      }
      lastLoadedProductId = newProducts[newProducts.length - 1].id_producto;
      if (reset) {
        products = newProducts;
        currentPage = 1;
      } else {
        products = [...products, ...newProducts];
      }
      displayProducts(products, currentPage, reset ? initialProductsPerPage : additionalProductsPerPage);
      if (newProducts.length < additionalProductsPerPage) {
        loadMoreButton.style.display = "none";
        document.getElementById("no-more-products").style.display = "block";
      } else {
        loadMoreButton.style.display = "block";
        document.getElementById("no-more-products").style.display = "none";
      }
    } catch (error) {
      if (error.name === "AbortError") {
        console.log("Fetch request canceled");
      } else {
        console.error("Error al obtener los productos:", error);
      }
    } finally {
      isLoading = false;
      loadingIndicator.style.display = "none";
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
    const response = await fetch(SERVERURL + "marketplace/obtener_producto/" + productId, { signal });
    return await response.json();
  }

  function createProductCard(product, productDetails) {
    const { pcp, pvp, saldo_stock, url_imporsuit, categoria } = productDetails;
    let boton_enviarCliente = ``;
    let botonId_inventario = ``;
    if (product.producto_variable == 0) {
      boton_enviarCliente = `
        <button class="btn btn-import d-flex align-items-center justify-content-center w-100" onclick="enviar_cliente(${product.id_producto},'${product.sku}',${product.pvp},${product.id_inventario})">
          <i class='bx bx-send me-2'></i> Enviar a cliente
        </button>
      `;
      botonId_inventario = `
        <div class="card-id-container" onclick="copyToClipboard(${product.id_inventario})">
          <span class="card-id">ID: ${product.id_inventario}</span>
        </div>
      `;
    } else if (product.producto_variable == 1) {
      boton_enviarCliente = `
        <button class="btn btn-import d-flex align-items-center justify-content-center w-100" onclick="abrir_modalSeleccionAtributo(${product.id_producto},'${product.sku}',${product.pvp},${product.id_inventario})">
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
    const card = document.createElement("div");
    card.className = "card-custom position-relative";
    let imagePath = obtenerURLImagen(productDetails.image_path, SERVERURL);
    let validador_imagen = 1;
    validador_imagen = verificarImagen(imagePath);
    if (validador_imagen == 0) {
      imagePath = SERVERURL + "public/img/broken-image.png";
    }
    card.innerHTML = `
      <div class="image-container position-relative">
        ${botonId_inventario}
        <img src="${imagePath}" class="card-img-top" alt="Imagen del producto">
        <div class="add-to-store-button ${product.agregadoTienda ? "added" : ""}" data-product-id="${product.id_producto}">
          <span class="plus-icon">+</span>
          <span class="add-to-store-text">${product.agregadoTienda ? "Quitar de tienda" : "Añadir a tienda"}</span>
        </div>
        <div class="add-to-funnel-button" ${product.agregadoFunnel ? "added" : ""} data-funnel-id="${product.id_inventario}">
          <span class="plus-icon">+</span>
          <span class="add-to-funnel-text">${product.agregadoFunnel ? "Quitar de funnel" : "Añadir a funnel"}</span>
        </div>
        <button class="btn-heart ${esFavorito ? "clicked" : ""}" onclick="handleHeartClick(${product.id_producto}, ${esFavorito})">
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
          <p class="card-subtitle">Proveedor: <a href="#" onclick="abrirModal_infoTienda('${url_imporsuit}')" style="font-size: 15px;">${productDetails.nombre_tienda || "Proveedor desconocido"}</a></p>
        </div>
        <div class="card-pricing">
          <span class="precio-proveedor">Precio proveedor: <strong>$${pcp}</strong></span>
          <span class="precio-sugerido">Precio sugerido: <strong>$${pvp}</strong></span>
        </div>
        <div class="card-buttons d-flex flex-column gap-2">
          <button class="btn btn-description d-flex align-items-center justify-content-center w-100" onclick="verProducto(${product.id_producto})">
            <i class='bx bx-info-circle me-2'></i> Ver producto
          </button>
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

  // Evento de click “global” para los botones “añadir a tienda” y “añadir a funnel”
  cardContainer.addEventListener("click", function (event) {
    const target = event.target;
    if (target.classList.contains("add-to-store-button") || target.closest(".add-to-store-button")) {
      const button = target.closest(".add-to-store-button");
      const productId = button.getAttribute("data-product-id");
      const isAdded = button.classList.contains("added");
      toggleAddToStore(productId, isAdded);
    }
    if (target.classList.contains("add-to-funnel-button") || target.closest(".add-to-funnel-button")) {
      const button = target.closest(".add-to-funnel-button");
      const funnelId = button.getAttribute("data-funnel-id");
      window.location.href = SERVERURL + "funnelish/constructor_vista/" + funnelId;
    }
  });

  /************************************************
   * Revisar si la imagen existe
   ************************************************/
  async function verificarImagen(url) {
    try {
      const response = await fetch(url);
      return response.ok ? 1 : 0;
    } catch (error) {
      return 0;
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
          toastr.warning(response.message, "NOTIFICACIÓN", { positionClass: "toast-bottom-center" });
        } else if (response.status == 200) {
          toastr.success(response.message, "NOTIFICACIÓN", { positionClass: "toast-bottom-center" });
        }
      },
      error: function (error) {
        console.error("Error al actualizar el estado del producto:", error);
      }
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
          toastr.warning(response.message, "NOTIFICACIÓN", { positionClass: "toast-bottom-center" });
        } else if (response.status == 200) {
          toastr.success(response.message, "NOTIFICACIÓN", { positionClass: "toast-bottom-center" });
        }
      },
      error: function (error) {
        console.error("Error al actualizar el estado del producto:", error);
      }
    });
  }

  // Botón que muestra/oculta el input de búsqueda
  document.getElementById("toggleSearch").addEventListener("click", function () {
    const input = document.getElementById("buscar_proveedor");
    input.style.display = input.style.display === "none" ? "block" : "none";
    if (input.style.display === "block") input.focus();
  });
  
  /************************************************
   * Filtro de precio con noUiSlider
   ************************************************/
  var slider = document.getElementById("price-range-slider");
  var priceMin = document.getElementById("price-min");
  var priceMax = document.getElementById("price-max");

  vaciarTmpPedidos().then(() => {
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
          var min = values[0].replace("$", "").replace(",", "");
          var max = values[1].replace("$", "").replace(",", "");
          formData_filtro.set("min", min);
          formData_filtro.set("max", max);
          clearAndFetchProducts();
        });
        fetchProducts(true);
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

  $("#buscar_nombre").on(
    "input",
    debounce(function () {
      var q = $("#buscar_nombre").val().trim();
      if (/^\d+$/.test(q)) {
        formData_filtro.set("id", q);
        formData_filtro.set("nombre", "");
      } else {
        formData_filtro.set("nombre", q);
        formData_filtro.delete("id");
      }
      clearAndFetchProducts();
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
    clearAndFetchProducts();
  });

  $("#vendidosSwitch").change(function(){
    const estado = $(this).is(":checked") ? 1 : 0;
    formData_filtro.set("vendido", estado);
    clearAndFetchProducts();
  });

  loadMoreButton.addEventListener("click", () => {
    if (!isLoading) {
      isLoading = true;
      loadingIndicator.style.display = "block";
      currentPage++;
      fetchProducts(false).then(() => {
        if (products.length % additionalProductsPerPage !== 0) {
          loadMoreButton.style.display = "none";
          document.getElementById("no-more-products").style.display = "block";
        }
      });
    }
  });

  /*****************************************************
   * Función para actualizar los contadores de las cards
   *****************************************************/
  const actualizarCards = (guias) => {
    // Actualiza el total de guías
    document.getElementById("num_pedidos").innerText = guias.length;
    // Aquí asumimos que cada guía posee una propiedad "estado" que coincide con los valores del select.
    // Ajusta las condiciones de filtrado según la estructura real de tu objeto "guia"
    const generadas = guias.filter(guia => {
      let estado = guia.estado ? guia.estado.toLowerCase() : "";
      return estado === "generada" || estado === "por recolectar";
    }).length;
    const enTransito = guias.filter(guia => {
      let estado = guia.estado ? guia.estado.toLowerCase() : "";
      return estado === "en_transito";
    }).length;
    const entregadas = guias.filter(guia => {
      let estado = guia.estado ? guia.estado.toLowerCase() : "";
      return estado === "entregada";
    }).length;
    const novedad = guias.filter(guia => {
      let estado = guia.estado ? guia.estado.toLowerCase() : "";
      return estado === "novedad";
    }).length;
    const devolucion = guias.filter(guia => {
      let estado = guia.estado ? guia.estado.toLowerCase() : "";
      return estado === "devolucion";
    }).length;

    // Actualiza cada card; asegúrate de que en tu vista los IDs sean únicos:
    // Si en tu vista actualmente tienes "num_guias" para "Generadas", cámbialo por "num_guias" o similar.
    document.getElementById("num_guias").innerText = generadas;
    document.getElementById("num_transito").innerText = enTransito;
    document.getElementById("num_entrega").innerText = entregadas;
    document.getElementById("num_novedad").innerText = novedad;
    document.getElementById("num_devolucion").innerText = devolucion;
  };

  /*****************************************************
   * Cargar chips de categorías y proveedores (chips)
   *****************************************************/
  $(document).ready(function () {
    $.ajax({
      url: SERVERURL + "productos/cargar_categorias",
      type: "GET",
      dataType: "json",
      success: function (response) {
        if (Array.isArray(response)) {
          response.forEach(function (categoria) {
            $("#categoria_filtroMarketplace").append(new Option(categoria.nombre_linea, categoria.id_linea));
          });
        } else {
          console.log("La respuesta de la API no es un array:", response);
        }
      },
      error: function (error) {
        console.error("Error al obtener la lista de categorias:", error);
      },
    });
  },
  $.ajax({
    url: SERVERURL + "marketplace/obtenerProveedoresConProductosCategorias",
    type: "GET",
    dataType: "json",
    success: function (response) {
      if (Array.isArray(response)) {
        const sliderProveedores = document.getElementById("sliderProveedores");
        sliderProveedores.innerHTML = "";
        response.forEach((proveedor) => {
          const chipProv = document.createElement("div");
          chipProv.classList.add("slider-chip");
          chipProv.dataset.provId = proveedor.id_plataforma;
          const imageSrc = proveedor.image ? SERVERURL + proveedor.image : SERVERURL + "public/img/icons/proveedor.png";
          let nombreTienda = proveedor.nombre_tienda ? proveedor.nombre_tienda.toUpperCase() : "SIN NOMBRE";
          if (nombreTienda.length > 20) {
            nombreTienda = nombreTienda.substring(0, 17) + "...";
          }
          const categoriasArray = proveedor.categorias ? proveedor.categorias.split(",").map((cat) => cat.trim()) : [];
          let categoriasMostradas = categoriasArray.length > 0 ? categoriasArray.slice(0, 3).join(", ") : "Sin categorías";
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
          chipProv.addEventListener("click", function (e) {
            const clickedProvChip = e.currentTarget;
            document.querySelectorAll("#sliderProveedores .slider-chip").forEach((el) => el.classList.remove("selected"));
            clickedProvChip.classList.add("selected");
            formData_filtro.set("plataforma", clickedProvChip.dataset.provId);
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
    }
  },
  $(document).ready(function () {
    $("#buscar_proveedor").on("input", function () {
      let searchValue = $(this).val().toLowerCase().trim();
      let providerToScroll = null;
      $("#sliderProveedores .slider-chip").each(function () {
        let providerName = $(this).find(".chip-title").text().toLowerCase();
        if (providerName.includes(searchValue)) {
          $("#sliderProveedores .slider-chip").removeClass("selected");
          $(this).addClass("selected");
          providerToScroll = $(this);
          return false;
        }
      });
      if (searchValue === "") {
        $("#sliderProveedores .slider-chip").removeClass("selected");
      }
      if (providerToScroll) {
        let container = $("#sliderProveedores");
        let containerOffsetLeft = container.offset().left;
        let itemOffsetLeft = providerToScroll.offset().left;
        let currentScrollLeft = container.scrollLeft();
        let scrollValue = currentScrollLeft + (itemOffsetLeft - containerOffsetLeft) - 30;
        container.animate({ scrollLeft: scrollValue }, 400);
      }
    });
  })));
});

window.addEventListener("load", async () => {
  await initDataTable();

  const btnAplicar = document.getElementById("btnAplicarFiltros");
  if (btnAplicar) {
    btnAplicar.addEventListener("click", async function () {
      let rangoFechas = $("#daterange").val();
      if (rangoFechas) {
        let fechas = rangoFechas.split(" - ");
        fecha_inicio = fechas[0] + " 00:00:00";
        fecha_fin = fechas[1] + " 23:59:59";
      }
      await initDataTable();
    });
  }
});

// Función para formatear el teléfono para Ecuador (ajústala si fuera necesario para México)
function formatPhoneNumber(number) {
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

// Anular guías según la transportadora
function anular_guiaLaar(numero_guia) {
  let formData = new FormData();
  formData.append("guia", numero_guia);
  $.ajax({
    url: SERVERURL + "guias/anularGuia",
    type: "POST",
    data: formData,
    processData: false,
    contentType: false,
    success: function (response) {
      response = JSON.parse(response);
      if (response.status == 500) {
        toastr.error("LA GUIA NO SE ANULO CORRECTAMENTE", "NOTIFICACIÓN", { positionClass: "toast-bottom-center" });
      } else if (response.status == 200) {
        toastr.success("GUIA ANULADA CORRECTAMENTE", "NOTIFICACIÓN", { positionClass: "toast-bottom-center" });
        initDataTable();
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      alert(errorThrown);
    }
  });
}

function anular_guiaServi(numero_guia) {
  $.ajax({
    type: "GET",
    url: "https://guias.imporsuitpro.com/Servientrega/Anular/" + numero_guia,
    dataType: "json",
    success: function (response) {},
    error: function (xhr, status, error) {}
  });
  $.ajax({
    type: "GET",
    url: SERVERURL + "Guias/anularServi_temporal/" + numero_guia,
    dataType: "json",
    success: function (response) {
      if (response.status == 500) {
        toastr.error("LA GUIA NO SE ANULO CORRECTAMENTE", "NOTIFICACIÓN", { positionClass: "toast-bottom-center" });
      } else if (response.status == 200) {
        toastr.success("GUIA ANULADA CORRECTAMENTE", "NOTIFICACIÓN", { positionClass: "toast-bottom-center" });
        initDataTable();
      }
    },
    error: function (xhr, status, error) {
      alert("Hubo un problema al anular la guia de Servientrega");
    }
  });
}

function anular_guiaSpeed(numero_guia) {
  $.ajax({
    type: "GET",
    url: "https://guias.imporsuitpro.com/Speed/anular/" + numero_guia,
    dataType: "json",
    success: function (response) {
      if (response.status == 500) {
        toastr.error("LA GUIA NO SE ANULO CORRECTAMENTE", "NOTIFICACIÓN", { positionClass: "toast-bottom-center" });
      } else if (response.status == 200) {
        toastr.success("GUIA ANULADA CORRECTAMENTE", "NOTIFICACIÓN", { positionClass: "toast-bottom-center" });
        initDataTable();
      }
    },
    error: function (xhr, status, error) {
      alert("Hubo un problema al anular la guia de Speed");
    }
  });
}

function anular_guiaGintracom(numero_guia) {
  $.ajax({
    type: "POST",
    url: "https://guias.imporsuitpro.com/Gintracom/anular/" + numero_guia,
    dataType: "json",
    success: function (response) {
      if (response.status == 200) {
        toastr.success("GUIA ANULADA CORRECTAMENTE", "NOTIFICACIÓN", { positionClass: "toast-bottom-center" });
        initDataTable();
      } else {
        toastr.error("LA GUIA NO SE ANULO CORRECTAMENTE", "NOTIFICACIÓN", { positionClass: "toast-bottom-center" });
      }
    },
    error: function (xhr, status, error) {
      console.error("Error en la solicitud AJAX:", error);
      alert("Hubo un problema al anular guia de gintracom");
    }
  });
}

function gestionar_novedad() {
  window.location.href = SERVERURL + "Pedidos/novedades_2";
}

function resetModalInputs(modalId) {
  const modal = document.querySelector(`#${modalId}`);
  if (modal) {
    const inputs = modal.querySelectorAll("input");
    inputs.forEach((input) => { input.value = ""; });
    const selects = modal.querySelectorAll("select");
    selects.forEach((select) => { select.selectedIndex = 0; });
    const optionalSections = modal.querySelectorAll('[style*="display"]');
    optionalSections.forEach((section) => { section.style.display = "none"; });
    console.log("Modal inputs and selects reset successfully.");
  } else {
    console.error("Modal not found!");
  }
}

function hiden_laar() {
  $("#telefono_laar_novedad").hide();
  $("#calle_principal_laar_novedad").hide();
  $("#calle_secundaria_laar_novedad").hide();
  $("#observacion_laar_novedad").hide();
  $("#solucionl_laar_novedad").hide();
}

$(document).ready(function () {
  $("#tipo_gintracom").change(function () {
    var tipo = $("#tipo_gintracom").val();
    if (tipo == "recaudo") {
      $("#valor_recaudoGintra").show();
      $("#fecha_gintra").show();
    } else if (tipo == "rechazar") {
      $("#valor_recaudoGintra").hide();
      $("#fecha_gintra").hide();
    } else {
      $("#valor_recaudoGintra").hide();
      $("#fecha_gintra").show();
    }
  });

  $("#tipo_laar").change(function () {
    var tipo = $("#tipo_laar").val();
    if (tipo == "NI") {
      $("#telefono_laar_novedad").show();
      $("#solucionl_laar_novedad").show();
      $("#calle_principal_laar_novedad").hide();
      $("#calle_secundaria_laar_novedad").hide();
      $("#observacion_laar_novedad").hide();
    } else if (tipo == "DI") {
      $("#calle_principal_laar_novedad").show();
      $("#calle_secundaria_laar_novedad").show();
      $("#solucionl_laar_novedad").show();
      $("#telefono_laar_novedad").hide();
      $("#observacion_laar_novedad").hide();
    } else if ((tipo = "OG")) {
      $("#observacion_laar_novedad").show();
      $("#solucionl_laar_novedad").show();
      $("#telefono_laar_novedad").hide();
      $("#calle_principal_laar_novedad").hide();
      $("#calle_secundaria_laar_novedad").hide();
    }
  });
});

// --- FIN DEL ARCHIVO guias.js ---
