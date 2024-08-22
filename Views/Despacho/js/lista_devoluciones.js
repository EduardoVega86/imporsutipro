let dataTableListaDevoluciones;
let dataTableListaDevolucionesIsInitialized = false;

const dataTableListaDevolucionesOptions = {
  columnDefs: [
    { className: "centered", targets: [1, 2, 3, 4, 5] },
    { orderable: false, targets: 0 }, //ocultar para columna 0 el ordenar columna
  ],
  pageLength: 10,
  destroy: true,
  dom: '<"d-flex w-full justify-content-between"lBf><t><"d-flex justify-content-between"ip>',
  buttons: [
    {
      extend: "excelHtml5",
      text: 'Excel <i class="fa-solid fa-file-excel"></i>',
      title: "Panel de Control: Usuarios",
      titleAttr: "Exportar a Excel",
      exportOptions: {
        columns: [0, 1, 2, 3, 4, 5],
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
        columns: [0, 1, 2, 3, 4, 5],
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

const initDataTableListaDevoluciones = async () => {
  if (dataTableListaDevolucionesIsInitialized) {
    dataTableListaDevoluciones.destroy();
  }

  await listListaDevoluciones();

  dataTableListaDevoluciones = $("#datatable_lista_devoluciones").DataTable(
    dataTableListaDevolucionesOptions
  );

  dataTableListaDevolucionesIsInitialized = true;
};

const listListaDevoluciones = async () => {
  try {
    const response = await fetch(
      "" + SERVERURL + "despacho/listarDevoluciones"
    );
    const listaDevoluciones = await response.json();

    let content = ``;

    listaDevoluciones.forEach((devolucion, index) => {
      content += `
                <tr>
                <td>${devolucion.id_relacion_devolucion}</td>
                <td>${devolucion.id_usuario}</td>
                <td>${devolucion.id_plataforma}</td>
                <td>${devolucion.id_transportadora}</td>
                <td>${devolucion.id_bodega}</td>
                <td>${devolucion.fecha_hora}</td>
                </tr>`;
    });
    document.getElementById("tableBody_lista_devoluciones").innerHTML = content;
  } catch (ex) {
    alert(ex);
  }
};

document
  .getElementById("generarDespachoBtn")
  .addEventListener("click", function () {
    const url = SERVERURL + `despacho/devoluciones`;
    window.location.href = url;
  });

window.addEventListener("load", async () => {
  await initDataTableListaDevoluciones();
});
