document.addEventListener("DOMContentLoaded", function () {
  // Obtener elementos
  const imgContainer = document.getElementById("trigger-container");
  const loading = document.getElementById("loading");
  const enlaceSection = document.getElementById("enlace-section");

  // Ocultar inicialmente la sección de generación de enlaces
  enlaceSection.style.display = "none";

  // Evento de clic en el contenedor de la imagen
  imgContainer.addEventListener("click", function () {
    // Mostrar la animación de carga
    loading.style.display = "block";

    // Esperar 2 segundos y luego mostrar la sección de enlace
    setTimeout(() => {
      loading.style.display = "none"; // Ocultar el cargando
      enlaceSection.style.display = "block"; // Mostrar la sección de enlace
      enlaceSection.style.opacity = "1"; // Transición visual
    }, 2000); // Tiempo en milisegundos
  });
});

function generar_link() {
  const loadingBelow = document.getElementById("loading-below");

  // Mostrar la animación de carga
  loadingBelow.style.display = "block";

  const url = window.location.href;
  const id_inventario = url.split("/").pop();
  let formData = new FormData();
  formData.append("id_inventario", id_inventario);

  $.ajax({
    url: SERVERURL + "pedidos/buscarProductosBodega",
    type: "POST",
    data: formData,
    processData: false, // No procesar los datos
    contentType: false, // No establecer ningún tipo de contenido
    dataType: "json",
    success: function (response) {
      $("#generador_enlace").val(
        "https://new.imporsuitpro.com/funnelish/index/" + ID_PLATAFORMA
      );

      // Ejecutar la consulta cada 10 segundos y guardar el ID del intervalo
      const intervalId = setInterval(() => {
        checkAPIStatus(loadingBelow, intervalId, formData);
      }, 10000);
    },
    error: function (jqXHR, textStatus, errorThrown) {
      alert(errorThrown);
    },
  });
}

function checkAPIStatus(loadingBelow, intervalId, formData) {
  $.ajax({
    url: SERVERURL + "api/statusCheck", // Cambia a tu endpoint real
    type: "POST",
    data: formData,
    processData: false,
    contentType: false,
    dataType: "json",
    success: function (response) {
      if (response.success === true) {
        // Detener la repetición
        clearInterval(intervalId);

        // Ocultar el cargando
        loadingBelow.style.display = "none";
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.error("Error al consultar el estado:", errorThrown);
    },
  });
}

let dataTableProductosShopify;
let dataTableProductosShopifyIsInitialized = false;

const dataTableProductosShopifyOptions = {
  columnDefs: [
    { className: "centered", targets: [0, 1, 2] },
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
    const response = await fetch("" + SERVERURL + "funnelish/get_productos");
    const productosShopify = await response.json();

    let content = ``;

    productosShopify.forEach((producto, index) => {
      const enlace_imagen = obtenerURLImagen(producto.image_path, SERVERURL);
      content += `
                <tr>
                    <td>${producto.id_funnel}</td>
                    <td><img src="${enlace_imagen}" class="icon-button" onclick="agregar_imagenProducto(${producto.id_producto},'${enlace_imagen}')" alt="Agregar imagen" width="50px"></td>
                    <td>${producto.nombre_producto}</td>
                   
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
