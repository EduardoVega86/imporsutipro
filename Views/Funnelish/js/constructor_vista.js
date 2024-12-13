document.addEventListener("DOMContentLoaded", function () {
  const url_principal = window.location.href;
  const id_inventario_principal = url_principal.split("/").pop();

  if (isNaN(id_inventario_principal) || id_inventario_principal === "") {
    // Oculta el elemento con el ID "enlazar_funnel"
    const enlazar_funnel = document.getElementById("enlazar_funnel");
    if (enlazar_funnel) {
      enlazar_funnel.style.display = "none";
    }
  }
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
    url: SERVERURL + "funnelish/ultimoProductos/" + ID_PLATAFORMA,
    type: "POST",
    data: formData,
    processData: false, // No procesar los datos
    contentType: false, // No establecer ningún tipo de contenido
    dataType: "json",
    success: function (response) {
      $("#generador_enlace").val(response.enlace);

      // Ejecutar la consulta cada 10 segundos y guardar el ID del intervalo
      const intervalId = setInterval(() => {
        checkAPIStatus(
          loadingBelow,
          intervalId,
          response.enlace,
          id_inventario
        );
      }, 10000);
    },
    error: function (jqXHR, textStatus, errorThrown) {
      alert(errorThrown);
    },
  });
}

function checkAPIStatus(loadingBelow, intervalId, enlace, id_inventario) {
  $.ajax({
    url: SERVERURL + "funnelish/validarPedido/" + enlace, // Cambia a tu endpoint real
    type: "POST",
    processData: false,
    contentType: false,
    dataType: "json",
    success: function (response) {
      if (response.encontrado === true && response.id_producto) {
        // Detener la repetición
        clearInterval(intervalId);

        // Ocultar el cargando
        loadingBelow.style.display = "none";

        /* Swal para confirmacion de datos y enlaze de producto */
        Swal.fire({
          icon: "success",
          title: "Pedido encontrado",
          text: "¿Desea enlazar el producto?",
          input: "text", // Tipo de input, en este caso texto
          inputPlaceholder: "Ingresa el ID del producto",
          showConfirmButton: true,
          confirmButtonText: "Sí, enlazar producto",
          cancelButtonText: "No",
          showCancelButton: true,
          preConfirm: (inputValue) => {
            if (!inputValue) {
              Swal.showValidationMessage("Por favor, ingresa un valor");
            }
            return inputValue; // Devuelve el valor ingresado
          },
        }).then((result) => {
          if (result.isConfirmed) {
            // Dividir la URL por "/"
            let partes = enlace.split("/");

            // Tomar el último elemento
            let id_registro = partes[partes.length - 1];

            // Si el usuario confirma (Sí)
            enlazarProducto(
              result.value,
              id_inventario,
              id_registro,
              response.id_producto
            ); // Llama a tu función pasando el valor del input
            Swal.close(); // Cierra el Swal
            window.location.href = SERVERURL + "funnelish/constructor_vista";
          } else if (result.dismiss === Swal.DismissReason.cancel) {
            // Si el usuario cancela (No)
            Swal.close(); // Cierra el Swal
          }
        });
        /* fin Swal para confimacion de datos y enlaze de producto */
      } else if (response.encontrado === true) {
        Swal.fire({
          icon: "error",
          title: "Error al realizar la conexion",
          text: response.mensaje,
        });
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.error("Error al consultar el estado:", errorThrown);
    },
  });
}

function enlazarProducto(inputValue, id_inventario, id_registro, id_funnel) {
  let formData = new FormData();
  formData.append("id_inventario", id_inventario);
  formData.append("id_registro", id_registro);
  formData.append("id_funnel", id_funnel);
  formData.append("sku", inputValue);
  $.ajax({
    url: SERVERURL + "funnelish/asignarProducto/" + ID_PLATAFORMA,
    type: "POST",
    data: formData,
    processData: false, // No procesar los datos
    contentType: false, // No establecer ningún tipo de contenido
    dataType: "json",
    success: function (response) {},
    error: function (jqXHR, textStatus, errorThrown) {
      alert(errorThrown);
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
