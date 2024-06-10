document.addEventListener("DOMContentLoaded", function () {
  const productsPerPage = 10;
  let currentPage = 1;
  let products = [];

  const cardContainer = document.getElementById("card-container");
  const pagination = document.getElementById("pagination");

  async function fetchProducts() {
    try {
      const response = await fetch(
        "" + SERVERURL + "marketplace/obtener_productos"
      );
      products = await response.json();
      displayProducts(products, currentPage, productsPerPage);
      createPagination(products.length, productsPerPage);
    } catch (error) {
      console.error("Error al obtener los productos:", error);
    }
  }

  function displayProducts(products, page = 1, perPage = productsPerPage) {
    cardContainer.innerHTML = "";
    const start = (page - 1) * perPage;
    const end = start + perPage;
    const paginatedProducts = products.slice(start, end);

    paginatedProducts.forEach((product) => {
      const card = document.createElement("div");
      card.className = "card card-custom";
      card.innerHTML = `
            <img src="${product.img}" class="card-img-top" alt="Product Image">
            <div class="card-body text-center d-flex flex-column justify-content-between">
                <div>
                    <h5 class="card-title">${product.nombre_producto}</h5>
                    <p class="card-text">Stock: <strong style="color:green">${product.stock}</strong></p>
                    <p class="card-text">Precio Proveedor: <strong>$20</strong></p>
                    <p class="card-text">Precio Sugerido: <strong>$25</strong></p>
                    <p class="card-text">Proveedor: <a href="#">tony</a></p>
                </div>
                <div>
                    <button class="btn btn-description" onclick="agregarModal_marketplace(${product.id_producto})">Descripción</button>
                    <button class="btn btn-import" onclick="enviar_cliente(${product.id_producto})">Enviar a cliente</button>
                </div>
            </div>
        `;
      cardContainer.appendChild(card);
    });
  }

  function createPagination(totalProducts, perPage = productsPerPage) {
    pagination.innerHTML = "";
    const totalPages = Math.ceil(totalProducts / perPage);

    const previousPageItem = document.createElement("li");
    previousPageItem.className = "page-item";
    previousPageItem.innerHTML = `
        <button class="page-link" aria-label="Previous">
            <span aria-hidden="true">&laquo;</span>
        </button>
    `;
    previousPageItem.addEventListener("click", function () {
      if (currentPage > 1) {
        currentPage--;
        displayProducts(products, currentPage, productsPerPage);
        createPagination(totalProducts, perPage);
      }
    });
    pagination.appendChild(previousPageItem);

    for (let i = 1; i <= totalPages; i++) {
      const pageItem = document.createElement("li");
      pageItem.className = `page-item ${i === currentPage ? "active" : ""}`;
      pageItem.innerHTML = `
            <button class="page-link">${i}</button>
        `;
      pageItem.addEventListener("click", function () {
        currentPage = i;
        displayProducts(products, currentPage, productsPerPage);
        createPagination(totalProducts, perPage);
      });
      pagination.appendChild(pageItem);
    }

    const nextPageItem = document.createElement("li");
    nextPageItem.className = "page-item";
    nextPageItem.innerHTML = `
        <button class="page-link" aria-label="Next">
            <span aria-hidden="true">&raquo;</span>
        </button>
    `;
    nextPageItem.addEventListener("click", function () {
      if (currentPage < totalPages) {
        currentPage++;
        displayProducts(products, currentPage, productsPerPage);
        createPagination(totalProducts, perPage);
      }
    });
    pagination.appendChild(nextPageItem);

    updatePaginationButtons(totalPages);
  }

  function updatePaginationButtons(totalPages) {
    const previousPageItem = pagination.querySelector(".page-item:first-child");
    const nextPageItem = pagination.querySelector(".page-item:last-child");

    previousPageItem.classList.toggle("disabled", currentPage === 1);
    nextPageItem.classList.toggle("disabled", currentPage === totalPages);
  }

  fetchProducts();
});

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

//enviar cliente
function enviar_cliente(id) {
  $.ajax({
    type: "POST",
    url: SERVERURL + "marketplace/obtener_producto/" + id,
    dataType: "json",
    success: function (response) {
      if (response) {
        const data = response[0];

        // Crear un objeto FormData y agregar los datos
        const formData = new FormData();
        formData.append("cantidad", 1);
        formData.append("precio", data.pvp);
        formData.append("id_producto", data.id_producto);
        formData.append("sku", data.sku);

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
                window.location.href = SERVERURL + "Pedidos/nuevo?id_producto=" + data.id_producto + "&sku=" + data.sku;
            }
          },
          error: function (xhr, status, error) {
            console.error("Error en la solicitud AJAX:", error);
            alert("Hubo un problema al agregar el producto temporalmente");
          },
        });
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
        const response = await fetch(''+SERVERURL+'marketplace/vaciarTmp');
        if (!response.ok) {
            throw new Error('Error al vaciar los pedidos temporales');
        }
        const data = await response.json();
        console.log('Respuesta de vaciarTmp:', data);
    } catch (error) {
        console.error('Error al hacer la solicitud:', error);
    }
};

// Ejecutar la función cuando la página se haya cargado
window.addEventListener('load', vaciarTmpPedidos);