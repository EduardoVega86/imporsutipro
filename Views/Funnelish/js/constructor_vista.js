document.addEventListener("DOMContentLoaded", function () {
  // Obtener elementos
  const imgContainer = document.getElementById("trigger-container");
  const loadingBelow = document.getElementById("loading-below");
  const enlaceSection = document.getElementById("enlace-section");

  // Ocultar inicialmente la sección de generación de enlaces
  enlaceSection.style.display = "none";

  // Evento de clic en el contenedor de la imagen
  imgContainer.addEventListener("click", function () {
      // Mostrar la animación de carga
      loadingBelow.style.display = "block";

      // Esperar 2 segundos y luego mostrar la sección de enlace
      setTimeout(() => {
          loadingBelow.style.display = "none"; // Ocultar el cargando
          enlaceSection.style.display = "block"; // Mostrar la sección de enlace
          enlaceSection.style.opacity = "1"; // Transición visual
      }, 2000); // Tiempo en milisegundos
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
