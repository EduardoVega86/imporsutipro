let dataTableNuevosPedidos;
let dataTableNuevosPedidosIsInitialized = false;

function getParameterByName(name) {
  const url = new URL(window.location.href);
  return url.searchParams.get(name);
}
// Obtener el id_producto de la URL
const id_producto = getParameterByName("id_producto");
const sku = getParameterByName("sku");

const dataTableNuevosPedidosOptions = {
  //scrollX: "2000px",
  /* lengthMenu: [5, 10, 15, 20, 100, 200, 500], */
  columnDefs: [
    { className: "centered", targets: [0, 1, 2, 3, 4, 5, 6] },
    /* { orderable: false, targets: [5, 6] }, */
    /* { searchable: false, targets: [1] } */
    //{ width: "50%", targets: [0] }
  ],
  pageLength: 10,
  destroy: true,
  language: {
    lengthMenu: "Mostrar _MENU_ registros por página",
    zeroRecords: "Ningún pedido encontrado",
    info: "Mostrando de _START_ a _END_ de un total de _TOTAL_ registros",
    infoEmpty: "Ningún pedido encontrado",
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

const initDataTableNuevosPedidos = async () => {
  if (dataTableNuevosPedidosIsInitialized) {
    dataTableNuevosPedidos.destroy();
  }

  await listNuevosPedidos();

  dataTableNuevosPedidos = $("#datatable_nuevosPedidos").DataTable(
    dataTableNuevosPedidosOptions
  );

  dataTableNuevosPedidosIsInitialized = true;
};

const listNuevosPedidos = () => {
  let formData = new FormData();
  formData.append("sku", sku);

  $.ajax({
    url: SERVERURL + "pedidos/buscarProductosBodega/" + id_producto,
    type: "POST",
    data: formData,
    processData: false,
    contentType: false,
    success: function (response) {
      console.log("Respuesta del servidor:", response);

      let nuevosPedidos = response;
      if (typeof response === "string") {
        nuevosPedidos = JSON.parse(response);
      }

      if (Array.isArray(nuevosPedidos)) {
        // Limpiar cualquier dato anterior en la tabla
        dataTableNuevosPedidos.clear();

        // Crear un array con los datos en el formato que DataTables espera
        const datos = nuevosPedidos.map((nuevoPedido, index) => {
          let imagen = obtenerURLImagen(nuevoPedido.image_path, SERVERURL);
          return [
            `<img src="${imagen}" class="icon-button" width="50px">`,
            nuevoPedido.id_producto,
            nuevoPedido.nombre_producto,
            nuevoPedido.stock_inicial,
            `<input type="number" class="form-control" value="1" min="1" id="cantidad_${index}">`,
            nuevoPedido.pvp,
            `<button class="btn btn-sm btn-success" onclick="enviar_cliente(${nuevoPedido.id_producto}, ${index},'${nuevoPedido.sku}', ${nuevoPedido.pvp}, ${nuevoPedido.id_inventario})">
                <i class="fa-solid fa-plus"></i></button>`,
          ];
        });

        // Añadir los nuevos datos a la tabla
        dataTableNuevosPedidos.rows.add(datos).draw();
      } else {
        console.error("La respuesta no es un array:", nuevosPedidos);
        alert("Error: La respuesta no tiene el formato esperado.");
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      alert(errorThrown);
    },
  });
};

function obtenerURLImagen(imagePath, serverURL) {
  // Verificar si el imagePath no es null o undefined
  if (imagePath) {
    // Verificar si el imagePath ya es una URL completa
    if (imagePath.startsWith("http://") || imagePath.startsWith("https://")) {
      // Si ya es una URL completa, retornar solo el imagePath
      return imagePath;
    } else {
      // Verificar si el imagePath incluye rutas relativas inválidas
      if (
        imagePath.includes("../") ||
        imagePath.includes("..\\") ||
        imagePath === "" ||
        imagePath === "."
      ) {
        return serverURL + "public/img/broken-image.png"; // Ruta de imagen por defecto
      }
      // Si no es una URL completa, agregar el serverURL al inicio
      return `${serverURL}${imagePath}`;
    }
  } else {
    // Manejar el caso cuando imagePath es null o undefined
    console.error("imagePath es null o undefined");
    return serverURL + "public/img/broken-image.png"; // Ruta de imagen por defecto
  }
}

// Abrir modal
function buscar_productos_nuevoPedido() {
  $("#nuevosPedidosModal").modal("show");
}

//enviar cliente
//enviar cliente
function enviar_cliente(id, index, sku, pvp, id_inventario) {
  // Obtener el valor del input cantidad correspondiente
  let cantidad = $(`#cantidad_${index}`).val();

  // Crear un objeto FormData y agregar los datos
  const formData = new FormData();
  formData.append("cantidad", cantidad); // Utilizar la cantidad obtenida
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
        toastr.error("NO SE AGREGRO CORRECTAMENTE", "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });
      } else if (response2.status == 200) {
        toastr.success("PRODUCTO AGREGADO CORRECTAMENTE", "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });
        initDataTableNuevoPedido();
      }
    },
    error: function (xhr, status, error) {
      console.error("Error en la solicitud AJAX:", error);
      alert("Hubo un problema al agregar el producto temporalmente");
    },
  });
}

window.addEventListener("load", async () => {
  await initDataTableNuevosPedidos();
});
