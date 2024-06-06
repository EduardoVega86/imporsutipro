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
      "https://new.imporsuitpro.com/productos/cargar_categorias"
    );
    const bodegas = await response.json();

    let content = ``;
    bodegas.forEach((bodega, index) => {
      content += `
                <tr>
                    <td>${bodega.nombre_linea}</td>
                    <td>${bodega.imagen}</td>
                    <td>${bodega.online}</td>
                    <td>${bodega.descripcion_linea}</td>
                    <td>${bodega.tipo}</td>
                    <td>${bodega.padre}</td>
                    <td>${bodega.estado_linea}</td>
                    <td>
                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editar_categoriaModal"><i class="fa-solid fa-pencil"></i>Editar</button>
                        <button class="btn btn-sm btn-danger"><i class="fa-solid fa-trash-can"></i>Borrar</button>
                    </td>
                </tr>`;
    });
    document.getElementById("tableBody_bodegas").innerHTML = content;
  } catch (ex) {
    alert(ex);
  }
};

window.addEventListener("load", async () => {
  await initDataTable();
});