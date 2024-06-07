let dataTable;
let dataTableIsInitialized = false;

const dataTableOptions = {
  //scrollX: "2000px",
  /* lengthMenu: [5, 10, 15, 20, 100, 200, 500], */
  columnDefs: [
    /* { className: "centered", targets: [0, 1, 2, 3, 4, 5, 6] }, */
    /* { orderable: false, targets: [5, 6] }, */
    /* { searchable: false, targets: [1] } */
    //{ width: "50%", targets: [0] }
  ],
  paging: false, // Deshabilita la paginación
  searching: false, // Deshabilita la caja de búsqueda
  info: false, // Deshabilita la información de registros
  lengthChange: false, // Deshabilita el menú de cambio de longitud
  destroy: true,
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

  await listAtributos();

  dataTable = $("#datatable_atributos").DataTable(dataTableOptions);

  dataTableIsInitialized = true;
};

const listAtributos = async () => {
  try {
    const response = await fetch("" + SERVERURL + "productos/listar_atributos");
    const atributos = await response.json();

    let content = ``;
    atributos.forEach((atributo, index) => {
      content += `
                <tr>
                    <td>${atributo.nombre_atributo}</td>
                    <td></td>
                    <td><input id="agregar_atributo" name="agregar_atributo" class="form-control " type="text"></td>
                </tr>`;
    });
    document.getElementById("tableBody_atributos").innerHTML = content;
  } catch (ex) {
    alert(ex);
  }
};

window.addEventListener("load", async () => {
  await initDataTable();
});
