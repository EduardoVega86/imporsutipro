document.addEventListener("DOMContentLoaded", function () {
  // Obtener los elementos relevantes
  const imgContainer = document.getElementById("trigger-container");
  const loadingBelow = document.getElementById("loading-below");
  const enlaceSection = document.getElementById("enlace-section");
  const verifyButton = document.getElementById("verify-button");

  // Inicialmente ocultar la sección de generar enlace
  enlaceSection.style.display = "none";

  // Evento al hacer clic en la imagen
  imgContainer.addEventListener("click", function () {
    // Mostrar el cargando de abajo
    loadingBelow.style.display = "block";

    // Después de 2 segundos, ocultar cargando y mostrar la sección de generar enlace
    setTimeout(() => {
      loadingBelow.style.display = "none";
      enlaceSection.style.display = "block";
    }, 2000); // 2000 ms = 2 segundos
  });

  // Evento al hacer clic en el botón de verificar
  verifyButton.addEventListener("click", function () {
    // Mostrar el cargando de abajo
    loadingBelow.style.display = "block";

    // Después de 2 segundos, ocultar cargando y registrar un mensaje en consola
    setTimeout(() => {
      loadingBelow.style.display = "none";
      console.log("Función de verificación ejecutada.");
    }, 2000); // 2000 ms = 2 segundos
  });
});

let dataTableProductosShopify;
let dataTableProductosShopifyIsInitialized = false;

const dataTableProductosShopifyOptions = {
  columnDefs: [
    { className: "centered", targets: [1, 2, 3] },
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
