document.addEventListener("DOMContentLoaded", function () {
  const initialProductsPerPage = 24;
  const additionalProductsPerPage = 24;
  let currentPage = 1;
  let products = [];
  let displayedProducts = new Set();

  // FormData para tus filtros
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

  // --------------------------------------------------------------------------
  // 1) Cargar la lista completa de productos (nuevo endpoint)
  // --------------------------------------------------------------------------
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
    }

    try {
      // **** IMPORTANTE: "marketplace/obtener_productos_completos" ****
      const response = await fetch(
        SERVERURL + "marketplace/obtener_productos_completos",
        {
          method: "POST",
          body: formData_filtro,
          signal,
        }
      );
      const newProducts = await response.json();

      if (reset) {
        products = newProducts;
        currentPage = 1;
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

  // Limpia la lista
  function clearProductList() {
    cardContainer.innerHTML = "";
    displayedProducts.clear();
    products = [];
    currentPage = 1;
  }

  // --------------------------------------------------------------------------
  // 2) displayProducts: NO hace fetch extra. Solo recorre y crea tarjetas
  // --------------------------------------------------------------------------
  function displayProducts(productArray, page, perPage) {
    if (currentDisplayController) {
      currentDisplayController.abort();
    }
    currentDisplayController = new AbortController();

    const start = (page - 1) * perPage;
    const end = start + perPage;
    const paginatedProducts = productArray.slice(start, end);

    paginatedProducts.forEach((product) => {
      if (displayedProducts.has(product.id_producto)) return;
      displayedProducts.add(product.id_producto);

      // Llamamos directamente a createProductCard(product)
      createProductCard(product);
    });
  }

  // --------------------------------------------------------------------------
  // 3) createProductCard(product): construye la tarjeta con la info completita
  // --------------------------------------------------------------------------
  function createProductCard(product) {
    // Extraemos los campos que necesitas
    const {
      id_producto,
      nombre_producto,
      producto_variable,
      id_inventario,
      sku,
      pvp,
      saldo_stock,
      pcp,
      url_imporsuit,
      nombre_tienda,
      Es_Favorito,
      image_path,
      // etc. (si tienes más campos que quieras usar)
    } = product;

    const esFavorito = Es_Favorito === 1 || Es_Favorito === "1";

    let boton_enviarCliente = ``;
    let botonId_inventario = ``;
    if (producto_variable == 0) {
      boton_enviarCliente = `
        <button class="btn btn-import" onclick="enviar_cliente(${id_producto},'${sku}',${pvp},${id_inventario})">
          Enviar a cliente
        </button>`;
      botonId_inventario = `
        <div class="card-id-container" onclick="copyToClipboard(${id_inventario})">
          <span class="card-id">ID: ${id_inventario}</span>
        </div>`;
    } else if (producto_variable == 1) {
      boton_enviarCliente = `
        <button class="btn btn-import" onclick="abrir_modalSeleccionAtributo(${id_producto},'${sku}',${pvp},${id_inventario})">
          Enviar a cliente
        </button>`;
      botonId_inventario = `
        <div class="card-id-container" onclick="abrir_modal_idInventario(${id_producto})">
          <span class="card-id">Ver IDs de producto variable</span>
        </div>`;
    }

    // Asumimos que si tu DB dice "agregadoTienda" o "agregadoFunnel",
    // habrías de meterlo también a tu SELECT,
    // pero si no, quítalo.
    // Ejemplo:
    let agregadoTienda = product.agregadoTienda; // si tu SELECT lo retornara
    let agregadoFunnel = product.agregadoFunnel; // si tu SELECT lo retornara

    // URL de imagen principal
    let finalImagePath = obtenerURLImagen(image_path, SERVERURL);

    // Verificar la imagen
    verificarImagen(finalImagePath).then((validador_imagen) => {
      if (validador_imagen === 0) {
        finalImagePath = SERVERURL + "public/img/broken-image.png";
      }

      const card = document.createElement("div");
      card.className = "card card-custom position-relative";

      card.innerHTML = `
        <div class="image-container position-relative">
          ${botonId_inventario}
          <img src="${finalImagePath}" class="card-img-top" alt="Product Image">
          <div class="add-to-store-button ${
            agregadoTienda ? "added" : ""
          }" data-product-id="${id_producto}">
            <span class="plus-icon">+</span>
            <span class="add-to-store-text">
              ${agregadoTienda ? "Quitar de tienda" : "Añadir a tienda"}
            </span>
          </div>
          <div class="add-to-funnel-button ${
            agregadoFunnel ? "added" : ""
          }" data-funnel-id="${id_inventario}">
            <span class="plus-icon">+</span>
            <span class="add-to-funnel-text">
              ${agregadoFunnel ? "Quitar de funnel" : "Añadir a funnel"}
            </span>
          </div>
        </div>

        <button class="btn btn-heart ${esFavorito ? "clicked" : ""}"
                onclick="handleHeartClick(${id_producto}, ${esFavorito})">
          <i class="fas fa-heart"></i>
        </button>

        <div class="card-body text-center d-flex flex-column justify-content-between">
          <div>
            <h6 class="card-title"><strong>${nombre_producto}</strong></h6>
            <p class="card-text">Stock: <strong style="color:green">${saldo_stock}</strong></p>
            <p class="card-text">Precio Proveedor: <strong>$${
              pcp || 0
            }</strong></p>
            <p class="card-text">Precio Sugerido: <strong>$${
              pvp || 0
            }</strong></p>
            <p class="card-text">
              Proveedor: 
              <a href="#" onclick="abrirModal_infoTienda('${
                url_imporsuit || ""
              }')" style="font-size: 15px;">
                ${nombre_tienda || "Nombre tienda"}
              </a>
            </p>
          </div>
          <div>
            <button class="btn btn-description" onclick="agregarModal_marketplace(${id_producto})">
              Descripción
            </button>
            ${boton_enviarCliente}
          </div>
        </div>
      `;

      cardContainer.appendChild(card);
    });
  }

  // --------------------------------------------------------------------------
  // 4) Al cargar la página, primer fetch
  // --------------------------------------------------------------------------
  fetchProducts();

  // Botón “Cargar más”
  loadMoreButton.addEventListener("click", () => {
    if (!isLoading) {
      isLoading = true;
      loadingIndicator.style.display = "block";
      currentPage++;
      fetchProducts(false);
    }
  });

  // --------------------------------------------------------------------------
  // 5) Resto de funciones (filtros, etc.)
  // --------------------------------------------------------------------------
  function clearAndFetchProducts() {
    if (currentFetchController) {
      currentFetchController.abort();
    }
    if (currentDisplayController) {
      currentDisplayController.abort();
    }
    clearProductList();
    setTimeout(() => fetchProducts(true), 100);
  }

  function debounce(func, wait) {
    let timeout;
    return function (...args) {
      const context = this;
      clearTimeout(timeout);
      timeout = setTimeout(() => func.apply(context, args), wait);
    };
  }

  // Filtro precio (noUiSlider)
  var slider = document.getElementById("price-range-slider");
  var priceMin = document.getElementById("price-min");
  var priceMax = document.getElementById("price-max");

  fetch(SERVERURL + "marketplace/obtenerMaximo")
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
        clearAndFetchProducts();
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

  // Filtro Nombre
  $("#buscar_nombre").on(
    "input",
    debounce(function () {
      var q = $("#buscar_nombre").val();
      formData_filtro.set("nombre", q);
      clearAndFetchProducts();
    }, 300)
  );

  // Filtro categoría
  $("#categoria_filtroMarketplace").change(function () {
    var categoria = $("#categoria_filtroMarketplace").val();
    formData_filtro.set("linea", categoria);
    clearAndFetchProducts();
  });

  // Filtro proveedor
  $("#proveedor_filtroMarketplace").change(function () {
    var proveedor = $("#proveedor_filtroMarketplace").val();
    formData_filtro.set("plataforma", proveedor);
    clearAndFetchProducts();
  });

  // Filtro favoritos
  $("#favoritosSwitch").change(function () {
    var estado = $(this).is(":checked") ? 1 : 0;
    formData_filtro.set("favorito", estado);
    clearAndFetchProducts();
  });

  // --------------------------------------------------------------------------
  // 6) Listeners para "Add to Store"/"Funnel"
  // --------------------------------------------------------------------------
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
        SERVERURL + "funnelish/constructor_vista/" + funnelId;
    }
  });

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

  // --------------------------------------------------------------------------
  // 7) Resto de funciones: handleHeartClick, copyToClipboard, etc.
  // --------------------------------------------------------------------------
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

  // etc. ...
  // (mantén aquí tu función "agregarModal_marketplace" sólo si la usas,
  //  o adáptala para leer las imágenes desde "imagenes_adicionales" que ya
  //  vendrá en el array. Depende de tu preferencia.)

  async function verificarImagen(url) {
    try {
      const response = await fetch(url);
      if (response.ok) {
        return 1;
      } else {
        return 0;
      }
    } catch (error) {
      return 0;
    }
  }

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

  // Vaciar pedidos temporales cuando cargue la página
  window.addEventListener("load", () => {
    vaciarTmpPedidos();
  });

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

  // Cargar categorías y proveedores (podrías unificarlos también en un único endpoint,
  // pero aquí lo dejamos como lo tenías.)
  $(document).ready(function () {
    // Categorías
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

    // Proveedores
    $.ajax({
      url: SERVERURL + "marketplace/obtenerProveedores",
      type: "GET",
      dataType: "json",
      success: function (response) {
        if (Array.isArray(response)) {
          response.forEach(function (proveedor) {
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
});

{
  /* // --------------------------------------------------------------------------
// Helper para procesar la URL de la imagen
// -------------------------------------------------------------------------- */
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
      return serverURL + imagePath;
    }
  } else {
    console.error("imagePath es null o undefined");
    return serverURL + "public/img/broken-image.png";
  }
}
