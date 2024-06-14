let dataTableSeleccionProductoAtributo;
let dataTableSeleccionProductoAtributoIsInitialized = false;

const dataTableSeleccionProductoAtributoOptions = {
  columnDefs: [
    { className: "centered", targets: [1, 2, 3, 4] },
    { orderable: false, targets: 0 }, //ocultar para columna 0 el ordenar columna
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

const initDataTableSeleccionProductoAtributo = async () => {
  if (dataTableSeleccionProductoAtributoIsInitialized) {
    dataTableSeleccionProductoAtributo.destroy();
  }

  await listGuiasSeleccionProductoAtributo();

  dataTableSeleccionProductoAtributo = $("#datatable_seleccionProductoAtributo").DataTable(
    dataTableSeleccionProductoAtributoOptions
  );

  dataTableSeleccionProductoAtributoIsInitialized = true;
};

const listGuiasSeleccionProductoAtributo = async () => {
  var id_productoSeleccionado = $("#id_productoSeleccionado").val();

  try {
    const response = await fetch(
      "" + SERVERURL + "productos/mostrarVariedades/" + id_productoSeleccionado
    );
    const seleccion_Protuctos = await response.json();

    let content = ``;
    seleccion_Protuctos.forEach((seleccion_Protucto, index) => {
      content += `
                <tr>
                    <td></td>
                    <td>
                        <button class="btn btn-sm btn-danger"><i class="fa-solid fa-trash-can"></i></button>
                    </td>
                </tr>`;
    });
    document.getElementById("tableBody_seleccionProductoAtributo").innerHTML =
      content;
  } catch (ex) {
    alert(ex);
  }
};

window.addEventListener("load", async () => {
  await initDataTableSeleccionProductoAtributo();
});
