let dataTable;
let dataTableIsInitialized = false;

const dataTableOptions = {
  paging: false,
  searching: false,
  info: false,
  lengthChange: false,
  destroy: true,
  autoWidth: false, // Asegúrate de que DataTables no controle el ancho automáticamente
  language: {
    emptyTable: "No hay datos disponibles en la tabla",
    loadingRecords: "Cargando...",
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
