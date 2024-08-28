function getFecha() {
  let fecha = new Date();
  let mes = fecha.getMonth() + 1;
  let dia = fecha.getDate();
  let anio = fecha.getFullYear();
  let fechaHoy = anio + "-" + mes + "-" + dia;
  return fechaHoy;
}
let dataTable;
let dataTableIsInitialized = false;

const dataTableOptions = {
  processing: true, // Mostrar indicador de procesamiento
  serverSide: true, // Habilitar la paginación del lado del servidor
  ajax: {
    url: `${SERVERURL}pedidos/obtener_guiasAdministrador2`,
    type: "POST",
    data: function (d) {
      d.fecha_inicio = fecha_inicio;
      d.fecha_fin = fecha_fin;
      d.estado = $("#estado_q").val();
      d.transportadora = $("#transporte").val();
      d.impreso = $("#impresion").val();
    },
  },
  columns: [
    { data: "numero_factura" }, // Columna 0: Número de factura
    { data: "fecha_factura" }, // Columna 1: Fecha de factura
    { data: "nombre" }, // Columna 2: Nombre del cliente
    { data: "ciudad" }, // Columna 3: Ciudad
    { data: "tienda" }, // Columna 4: Tienda
    { data: "nombre_proveedor" }, // Columna 5: Nombre del proveedor
    { data: "id_transporte" }, // Columna 6: Transporte
    { data: "estado_guia_sistema" }, // Columna 7: Estado de la guía
    { data: "estado_factura" }, // Columna 8: Estado de la factura
    { data: "impreso" }, // Columna 9: Impreso
    {
      data: null,
      defaultContent:
        '<button class="btn btn-sm btn-primary">Acciones</button>',
    }, // Columna 10: Acciones
  ],
  order: [[1, "desc"]], // Ordenar por la columna de fecha
  pageLength: 25,
  lengthMenu: [25, 50, 100, 200],
  destroy: true,
  responsive: true,
  dom: '<"d-flex w-full justify-content-between"lBf><t><"d-flex justify-content-between"ip>',
  buttons: [
    {
      extend: "excelHtml5",
      text: 'Excel <i class="fa-solid fa-file-excel"></i>',
      exportOptions: {
        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8],
      },
      filename: "guias" + "_" + getFecha(),
      footer: true,
      className: "btn-excel",
    },
    {
      extend: "csvHtml5",
      text: 'CSV <i class="fa-solid fa-file-csv"></i>',
      exportOptions: {
        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8],
      },
      filename: "guias" + "_" + getFecha(),
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

const initDataTable = async () => {
  if (dataTableIsInitialized) {
    dataTable.destroy();
  }
  dataTable = $("#datatable_guias").DataTable(dataTableOptions);
  dataTableIsInitialized = true;

  // Handle select all checkbox
  document.getElementById("selectAll").addEventListener("change", function () {
    const checkboxes = document.querySelectorAll(".selectCheckbox");
    checkboxes.forEach((checkbox) => (checkbox.checked = this.checked));
  });
};

// Iniciar DataTable al cargar la página
window.addEventListener("load", async () => {
  await initDataTable();
});
