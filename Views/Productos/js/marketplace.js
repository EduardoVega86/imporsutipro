document.addEventListener("DOMContentLoaded", function () {
  const initialProductsPerPage = 24;
  const additionalProductsPerPage = 4;
  let currentPage = 1;
  let products = [];
  let filteredProducts = [];
  let formData_filtro = new FormData();
  formData_filtro.append("nombre", "");
  formData_filtro.append("linea", "");
  formData_filtro.append("plataforma", "");
  formData_filtro.append("min", "");
  formData_filtro.append("max", "");
  formData_filtro.append("favorito", "0");

  const cardContainer = document.getElementById("card-container");
  let isLoading = false;

  async function fetchProducts() {
    try {
      const response = await fetch(
        `${SERVERURL}marketplace/obtener_productos`,
        {
          method: "POST",
          body: formData_filtro,
        }
      );
      products = await response.json();
      filteredProducts = products; // Initially, no filter is applied
      currentPage = 1;
      cardContainer.innerHTML = ""; // Clear the container
      displayProducts(filteredProducts, 1, initialProductsPerPage);
    } catch (error) {
      console.error("Error al obtener los productos:", error);
    }
  }

  const displayProducts = async (products, page = 1, perPage) => {
    const start = (page - 1) * perPage;
    const end = start + perPage;
    const paginatedProducts = products.slice(start, end);

    for (const product of paginatedProducts) {
      try {
        const response = await fetch(
          SERVERURL + "marketplace/obtener_producto/" + product.id_producto
        );
        const productDetails = await response.json();

        if (productDetails && productDetails.length > 0) {
          const { costo_producto, pvp, saldo_stock, url_imporsuit } =
            productDetails[0];

          let boton_enviarCliente = ``;
          if (product.producto_variable == 0) {
            boton_enviarCliente = `<button class="btn btn-import" onclick="enviar_cliente(${product.id_producto},'${product.sku}',${product.pvp},${product.id_inventario})">Enviar a cliente</button>`;
          } else if (product.producto_variable == 1) {
            boton_enviarCliente = `<button class="btn btn-import" onclick="abrir_modalSeleccionAtributo(${product.id_producto},'${product.sku}',${product.pvp},${product.id_inventario})">Enviar a cliente</button>`;
          }

          const esFavorito = product.Es_Favorito === "1"; // Conversión a booleano

          const card = document.createElement("div");
          card.className = "card card-custom position-relative";
          card.innerHTML = `
    <div class="card-id-container" onclick="copyToClipboard(${product.id_producto})">
        <span class="card-id">${product.id_producto}</span>
    </div>
    <img src="${SERVERURL}${productDetails[0].image_path}" class="card-img-top" alt="Product Image">
    <button class="btn btn-heart ${esFavorito ? "clicked" : ""}" onclick="handleHeartClick(${product.id_producto}, ${esFavorito})">
      <i class="fas fa-heart"></i>
    </button>
    <div class="card-body text-center d-flex flex-column justify-content-between">
      <div>
        <h6 class="card-title"><strong>${product.nombre_producto}</strong></h6>
        <p class="card-text">Stock: <strong style="color:green">${saldo_stock}</strong></p>
        <p class="card-text">Precio Proveedor: <strong>$${
          productDetails[0].pcp
        }</strong></p>
        <p class="card-text">Precio Sugerido: <strong>$${pvp}</strong></p>
        <p class="card-text">Proveedor: <a href="${url_imporsuit}" target="_blank" style="font-size: 15px;">${procesarPlataforma(
          url_imporsuit
        )}</a></p>
      </div>
      <div>
        <button class="btn btn-description" onclick="agregarModal_marketplace(${product.id_producto})">Descripción</button>
        ${boton_enviarCliente}
      </div>
    </div>
`;
          cardContainer.appendChild(card);
        } else {
          console.error(
            "Error: La respuesta de la API no contiene los datos esperados."
          );
        }
      } catch (error) {
        console.error("Error al obtener los detalles del producto:", error);
      }
    }
    isLoading = false;
  };

  // Función de debounce para retrasar la ejecución hasta que el usuario deje de escribir
  function debounce(func, wait) {
    let timeout;
    return function (...args) {
      const context = this;
      clearTimeout(timeout);
      timeout = setTimeout(() => func.apply(context, args), wait);
    };
  }

  fetchProducts();

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

      // Create the slider with obtained max price
      noUiSlider.create(slider, {
        start: [0, data_precioMaximo],
        connect: true,
        range: {
          min: 0,
          max: data_precioMaximo,
        },
        step: 5,
        format: wNumb({
          decimals: 0,
          thousand: ",",
          prefix: "$",
        }),
      });

      // Update price inputs on slider update
      slider.noUiSlider.on("update", function (values, handle) {
        if (handle === 0) {
          priceMin.value = values[0];
        } else {
          priceMax.value = values[1];
        }
      });

      // Handle price range change
      slider.noUiSlider.on("change", function (values) {
        var min = values[0].replace("$", "").replace(",", "");
        var max = values[1].replace("$", "").replace(",", "");
        formData_filtro.set("min", min);
        formData_filtro.set("max", max);
        fetchProducts(); // Reset and fetch products based on new filter
      });
    })
    .catch((error) => {
      console.error("Error fetching max price:", error);
      // Optionally handle error scenario, e.g., show default slider values
      noUiSlider.create(slider, {
        start: [0, 1000], // Default values
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

  $("#buscar_nombre").on(
    "input",
    debounce(function () {
      var q = $("#buscar_nombre").val();
      formData_filtro.set("nombre", q);
      fetchProducts(); // Reset and fetch products based on new filter
    }, 300)
  ); // 300 ms de espera antes de ejecutar la función

  $("#categoria_filtroMarketplace").change(function () {
    var categoria = $("#categoria_filtroMarketplace").val();
    formData_filtro.set("linea", categoria);
    fetchProducts(); // Reset and fetch products based on new filter
  });

  $("#proveedor_filtroMarketplace").change(function () {
    var proveedor = $("#proveedor_filtroMarketplace").val();
    formData_filtro.set("plataforma", proveedor);
    fetchProducts(); // Reset and fetch products based on new filter
  });

  $("#favoritosSwitch").change(function () {
    var estado = $(this).is(":checked") ? 1 : 0;
    formData_filtro.set("favorito", estado);
    fetchProducts(); // Reset and fetch products based on new filter
  });

  // Implementación del scroll infinito
  window.addEventListener("scroll", () => {
    if (window.innerHeight + window.scrollY >= document.body.offsetHeight && !isLoading) {
      isLoading = true;
      displayProducts(filteredProducts, ++currentPage, additionalProductsPerPage);
    }
  });
});

function copyToClipboard(id) {
  navigator.clipboard.writeText(id).then(function() {
      alert('ID copiado al portapapeles: ' + id);
  }, function(err) {
      console.error('Error al copiar al portapapeles: ', err);
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
        $("#precio_proveedor").text(data.costo_producto);
        $("#precio_sugerido").text(data.pvp);
        $("#stock").text(data.saldo_stock);
        $("#nombre_proveedor").text(data.contacto);
        $("#telefono_proveedor").text(formatPhoneNumber(data.whatsapp));
        $("#descripcion").text(data.descripcion_producto);

        // Actualizar el enlace con el número de teléfono del proveedor
        $('a[href^="https://wa.me/"]').attr(
          "href",
          "https://wa.me/" + formatPhoneNumber(data.whatsapp)
        );

        // Actualizar la imagenes del modal
        $("#imagen_principal").attr("src", SERVERURL + "" + data.image_path);
        $("#imagen_principalPequena").attr(
          "src",
          SERVERURL + "" + data.image_path
        );

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
            new Option(proveedor.nombre_tienda, proveedor.id_plataforma)
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
