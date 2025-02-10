let dataTablePedidosSinProducto;
let dataTablePedidosSinProductoIsInitialized = false;

const dataTablePedidosSinProductoOptions = {
  columnDefs: [
    { className: "centered", targets: [1, 2, 3, 4, 5] },
    { orderable: false, targets: 0 }, // ocultar para columna 0 el ordenar columna
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

const initDataTablePedidosSinProducto = async () => {
  if (dataTablePedidosSinProductoIsInitialized) {
    dataTablePedidosSinProducto.destroy();
  }

  await listPedidosSinProducto();

  dataTablePedidosSinProducto = $(
    "#datatable_pedidos_sin_producto"
  ).DataTable(dataTablePedidosSinProductoOptions);

  dataTablePedidosSinProductoIsInitialized = true;
};

const listPedidosSinProducto = async () => {
  try {
    const formData = new FormData();
    formData.append("filtro", 1);
    
    const response = await fetch(
        `${SERVERURL}productos/obtener_productos_bps`,
        {
          method: "POST",
          body: formData,
        }
      );
    const pedidosSinProducto = await response.json();

    let content = ``;

    pedidosSinProducto.forEach((pedido, index) => {
      const enlace_imagen = obtenerURLImagen(pedido.image_path, SERVERURL);

      let cargar_imagen = pedido.image_path
        ? `<img src="${enlace_imagen}" class="icon-button" alt="Agregar imagen" width="50px">`
        : `<i class="bx bxs-camera-plus"></i>`;

      content += `
                <tr>
                <td>${pedido.sku}</td>
                <td>${pedido.nombre_producto}</td>
                <td>${pedido.pcp}</td>
                <td>${pedido.pvp}</td>
                <td>${cargar_imagen}</td>
                <td>
                ${editar}
                <button class="btn btn-sm btn-danger" onclick="eliminar_usuario(${
                  pedido.id_users
                })"><i class="fa-solid fa-trash-can"></i>Borrar</button>
                </td>
                </tr>`;
    });
    document.getElementById("tableBody_pedidos_sin_producto").innerHTML =
      content;
  } catch (ex) {
    alert(ex);
  }
};

window.addEventListener("load", async () => {
  await initDataTablePedidosSinProducto();
});

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