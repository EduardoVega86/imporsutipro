let dataTable;
let dataTableIsInitialized = false;

const dataTableOptions = {
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

  await listBodegas();

  dataTable = $("#datatable_bodegas").DataTable(dataTableOptions);

  dataTableIsInitialized = true;
};

const listBodegas = async () => {
  try {
    const response = await fetch(
      ""+SERVERURL+"productos/listar_bodegas"
    );
    const bodegas = await response.json();

    let content = ``;
    bodegas.forEach((bodega, index) => {
      content += `
                <tr>
                    <td>${bodega.id}</td>
                    <td>${bodega.nombre}</td>
                    <td>${bodega.direccion}</td>
                    <td>${bodega.provincia}</td>
                    <td>${bodega.responsable}</td>
                    <td>${bodega.contacto}</td>
                    <td>
                        <button class="btn btn-sm btn-primary" onclick="editar_bodegas(${bodega.id})"><i class="fa-solid fa-pencil"></i></button>
                        <button class="btn btn-sm btn-danger"><i class="fa-solid fa-trash-can"></i></button>
                    </td>
                </tr>`;
    });
    document.getElementById("tableBody_bodegas").innerHTML = content;
  } catch (ex) {
    alert(ex);
  }
};

function editar_bodegas(id) {
  const url = ''+ SERVERURL +'Productos/editar_bodegas?id=${id}';
  window.location.href = url;
}

window.addEventListener("load", async () => {
  await initDataTable();
});