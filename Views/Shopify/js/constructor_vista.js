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
      const enlace_imagen = obtenerURLImagen(producto.image_path, SERVERURL);
      content += `
                <tr>
                    <td>${producto.id_inventario}</td>
                    <td><img src="${enlace_imagen}" class="icon-button" onclick="agregar_imagenProducto(${producto.id_producto},'${enlace_imagen}')" alt="Agregar imagen" width="50px"></td>
                    <td>${producto.nombre_producto}</td>
                    <td>${producto.pvp}</td>
                    <td>
                    <div class="dropdown">
                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-gear"></i>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
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

function obtenerURLImagen(imagePath, serverURL) {
  // Verificar si el imagePath no es null
  if (imagePath) {
    // Verificar si el imagePath ya es una URL completa
    if (imagePath.startsWith("http://") || imagePath.startsWith("https://")) {
      // Si ya es una URL completa, retornar solo el imagePath
      return imagePath;
    } else {
      // Si no es una URL completa, agregar el serverURL al inicio
      return `${serverURL}${imagePath}`;
    }
  } else {
    // Manejar el caso cuando imagePath es null
    console.error("imagePath es null o undefined");
    return null; // o un valor por defecto si prefieres
  }
}

window.addEventListener("load", async () => {
  await initDataTableProductosShopify();
});
