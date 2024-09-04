document.addEventListener("DOMContentLoaded", function () {
  const apiURL = SERVERURL + "/shopify/obtenerConfiguracion";

  async function fetchData() {
    try {
      const response = await fetch(apiURL);
      const data = await response.json();
      displayData(data);
    } catch (error) {
      console.error("Error fetching data:", error);
    }
  }

  function displayData(data) {
    const container = document.querySelector(".datos_shopify");
    container.innerHTML = "";

    data.forEach((config) => {
      const configDiv = document.createElement("div");
      configDiv.classList.add("config-item");

      const configHtml = generateHtmlFromJson(config);
      configDiv.innerHTML = configHtml;
      container.appendChild(configDiv);
    });
  }

  function generateHtmlFromJson(obj, level = 0) {
    let html = "<ul>";

    for (let key in obj) {
      if (obj.hasOwnProperty(key)) {
        const value = obj[key];
        const formattedKey = `<strong>${key.replace(
          "/",
          ":</strong> <span>"
        )}</span>`;

        if (typeof value === "object" && value !== null) {
          html += `<li>${formattedKey}:<ul class="nested">${generateHtmlFromJson(
            value,
            level + 1
          )}</ul></li>`;
        } else if (typeof value === "string" && value.includes("/")) {
          html += `<li>${formattedKey}:</li>`;
          html += formatComplexString(value, level);
        } else {
          html += `<li>${formattedKey}: <span>${value}</span></li>`;
        }
      }
    }

    html += "</ul>";
    return html;
  }

  function formatComplexString(value, level) {
    const parts = value.split("/");
    let html = '<ul class="nested">';

    parts.forEach((part) => {
      html += `<li style="padding-left: 5px;"><strong>${part}</strong></li>`;
    });

    html += "</ul>";
    return html;
  }

  fetchData();
});

let dataTableProductosShopify;
let dataTableProductosShopifyIsInitialized = false;

const dataTableProductosShopifyOptions = {
  columnDefs: [
    { className: "centered", targets: [1, 2, 3, 4, 5] },
    { orderable: false, targets: 0 }, //ocultar para columna 0 el ordenar columna
  ],
  pageLength: 10,
  destroy: true,
  language: {
    lengthMenu: "Mostrar _MENU_ registros por página",
    zeroRecords: "Ningún producto encontrado",
    info: "Mostrando de _START_ a _END_ de un total de _TOTAL_ registros",
    infoEmpty: "Ningún producto encontrado",
    infoFiltered: "(filtrados desde _MAX_ registros totales)",
    search: "Buscar:",
    loadingRecords: "Cargando...",
    paginate: {
      first: "Primero",
      last: "Último",
      next: "Siguiente",
      previous: "Anterior",
    },
  },
};

const initDataTableProductosShopify = async () => {
  if (dataTableProductosShopifyIsInitialized) {
    dataTableProductosShopify.destroy();
  }

  await listProductosShopify();

  dataTableProductosShopify = $("#datatable_productos_shopify").DataTable(
    dataTableProductosShopifyOptions
  );

  dataTableProductosShopifyIsInitialized = true;
};

const listProductosShopify = async () => {
  try {
    const response = await fetch(
      "" + SERVERURL + "Productos/obtener_productos_shopify"
    );
    const productosShopify = await response.json();

    let content = ``;

    productosShopify.forEach((producto, index) => {
      content += `
                <tr>
                    <td><a class="dropdown-item link-like" href="${SERVERURL}shopify/verProducto?producto=${producto.id}">${producto.nombre}</a></td>
                    <td>${producto.precio}</td>
                    <td>${producto.stock}</td>
                    <td>${producto.categoria}</td>
                    <td>
                    <button id="downloadExcel" class="btn btn-success" onclick="descargarExcel_general('${producto.id}')">Descargar Excel general</button>
                    <button id="downloadExcel" class="btn btn-success" onclick="descargarExcel('${producto.id}')">Descargar Excel</button>
                    </td>
                    <td>
                    <div class="dropdown">
                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-gear"></i>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <li><a class="dropdown-item" style="cursor: pointer;" href="${SERVERURL}shopify/editarProducto?producto=${producto.id}"><i class='bx bx-edit'></i>Editar</a></li>
                        <li><a class="dropdown-item" style="cursor: pointer;" href="${SERVERURL}shopify/eliminarProducto?producto=${producto.id}"><i class='bx bx-trash'></i>Eliminar</a></li>
                    </ul>
                    </div>
                    </td>
                </tr>`;
    });
    document.getElementById("tableBody_productos_shopify").innerHTML = content;
  } catch (ex) {
    alert(ex);
  }
};

window.addEventListener("load", async () => {
  await initDataTableProductosShopify();
});
