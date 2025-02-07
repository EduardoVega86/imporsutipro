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
      let editar = "";
      let placa = "";
      if (pedido.cargo_users == 35) {
        editar = `<button class="btn btn-sm btn-primary" onclick="abrir_editar_motorizado(${pedido.id_users})"><i class="fa-solid fa-pencil"></i>Editar</button>`;

        placa = `<i class="fa-solid fa-store" style='cursor:pointer' onclick="abrir_modal_subirPlaca(${pedido.id_users})"></i>`;
      } else {
        editar = `<button class="btn btn-sm btn-primary" onclick="abrir_editar_usuario(${pedido.id_users})"><i class="fa-solid fa-pencil"></i>Editar</button>`;
      }

      content += `
                <tr>
                <td>${pedido.id_users}</td>
                <td>${pedido.nombre_users}</td>
                <td>${pedido.usuario_users}</td>
                <td>${pedido.email_users}</td>
                <td>
                <a href="https://wa.me/${formatPhoneNumber(
                  pedido.whatsapp
                )}" target="_blank" style="font-size: 45px; vertical-align: middle; margin-left: 10px;" target="_blank">
                <i class='bx bxl-whatsapp-square' style="color: green;"></i>
                </a></td>
                <td>${pedido.nombre_tienda}</td>
                <td>${pedido.date_added}</td>
                <td>${placa}</td>
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
