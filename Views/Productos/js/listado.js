let dataTable;
let dataTableIsInitialized = false;

const dataTableOptions = {
  paging: false,
  searching: false,
  info: false,
  lengthChange: false,
  destroy: true,
  autoWidth: false,
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
                    <td><input id="agregar_atributo_${index}" name="agregar_atributo" class="form-control agregar_atributo" type="text" data-atributo-id="${atributo.id_atributo}"></td>
                </tr>`;
    });
    document.getElementById("tableBody_atributos").innerHTML = content;

    // Agregar event listeners a todos los inputs recién creados
    document.querySelectorAll('.agregar_atributo').forEach(input => {
      input.addEventListener('keypress', async (event) => {
        if (event.key === 'Enter') {
          event.preventDefault();  // Previene el comportamiento por defecto del Enter
          const atributoId = event.target.getAttribute('data-atributo-id');
          const valor = event.target.value;

          if (valor.trim() !== '') {
            await agregarCaracteristica(atributoId, valor);
          }
        }
      });
    });
  } catch (ex) {
    alert(ex);
  }
};

const agregarCaracteristica = async (atributoId, valor) => {
    try {
      const formData = new FormData();
      formData.append('id_atributo', atributoId);
      formData.append('variedad', valor);
  
      const response = await fetch(''+ SERVERURL +'/productos/agregar_caracteristica', {
        method: 'POST',
        body: formData,
      });
  
      if (response.ok) {
        const result = await response.json();
        alert('Característica agregada exitosamente');
      } else {
        const error = await response.json();
        alert('Error al agregar la característica: ' + error.message);
      }
    } catch (ex) {
      alert('Error al conectarse a la API: ' + ex.message);
    }
  };

window.addEventListener("load", async () => {
  await initDataTable();
});
