let dataTable;
let dataTableIsInitialized = false;

const dataTableOptions = {
  columnDefs: [
    { className: "text-center", targets: "_all" },
    { orderable: false, targets: 0 },
  ],
  order: [[1, "asc"]],
  pageLength: 10,
  lengthMenu: [10, 25, 50, 100],
  destroy: true,
  responsive: true,
  dom: '<"d-flex w-full justify-content-between"lBf><t><"d-flex justify-content-between"ip>',
  buttons: [
    {
      extend: "excelHtml5",
      text: 'Excel <i class="fa-solid fa-file-excel"></i>',
      title: "Panel de Control: Usuarios",
      titleAttr: "Exportar a Excel",
      exportOptions: {
        columns: "_all",
      },
      filename: "Productos" + "_" + getFecha(),
      footer: true,
      className: "btn-excel",
    },
    {
      extend: "csvHtml5",
      text: 'CSV <i class="fa-solid fa-file-csv"></i>',
      title: "Panel de Control: Productos",
      titleAttr: "Exportar a CSV",
      exportOptions: {
        columns: "_all",
      },
      filename: "Productos" + "_" + getFecha(),
      footer: true,
      className: "btn-csv",
    },
  ],
  language: {
    lengthMenu: "Mostrar _MENU_ registros por página",
    zeroRecords: "Ningún usuario encontrado",
    info: "Mostrando de _START_ a _END_ de un total de _TOTAL_ registros",
    infoEmpty: "Ningún usuario encontrado",
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

function getFecha() {
  let fecha = new Date();
  let mes = fecha.getMonth() + 1;
  let dia = fecha.getDate();
  let anio = fecha.getFullYear();
  let fechaHoy = anio + "-" + mes + "-" + dia;
  return fechaHoy;
}
const initDataTable = async () => {
  if (dataTableIsInitialized) {
    dataTable.destroy();
  }

  await listProductos();

  dataTable = $("#tableProductos").DataTable(dataTableOptions);

  dataTableIsInitialized = true;
};

const listProductos = async () => {
  try {
    const response = await fetch(SERVERURL + "/funnelish/listar");

    const data = await response.json();

    console.log(data);

    let content = "";

    data.forEach((producto) => {
      content += `
            <tr>
            <td>
                <span>${producto.id}</span>
            </td>
            <td>
                <span>${producto.nombre}</span>
            </td>
            <td>
                <span>${producto.codigo_funnelish}</span>
            </td>
            <td>
                <span>${producto.codigo_producto}</span>
            </td>
            <td>
                <div class="d-flex justify-content-center">
                    <button class="btn btn-primary btn-sm" onclick="editarProducto(${producto.id})">
                        <i class="fa-solid fa-pencil"></i>
                    </button>
                    <button class="btn btn-danger btn-sm" onclick="eliminarProducto(${producto.id})">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </div>
            </td>
            </tr>
        `;
    });

    $("#tableProductos tbody").html(content);
  } catch (error) {
    console.error(error);
  }
};

const editarProducto = async (id) => {
  try {
    const response = await fetch(`/funnelish/obtener/${id}`);

    const data = await response.json();

    $("#modalProducto").modal("show");

    $("#id").val(data.id);
    $("#nombre").val(data.nombre);
    $("#codigo_funnelish").val(data.codigo_funnelish);
    $("#codigo_producto").val(data.codigo_producto);
  } catch (error) {
    console.error(error);
  }
};

const eliminarProducto = async (id) => {
  try {
    const response = await fetch(`/funnelish/eliminar/${id}`);

    const data = await response.json();

    if (data.ok) {
      initDataTable();
    }
  } catch (error) {
    console.error(error);
  }
};

$(document).ready(async () => {
  await initDataTable();
});
