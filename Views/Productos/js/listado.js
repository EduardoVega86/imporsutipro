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
    const response = await fetch(`${SERVERURL}productos/listar_atributos`);
    const atributos = await response.json();
    const caracteristicas = await listarCaracteristicas();

    let content = ``;
    atributos.forEach((atributo, index) => {
      const tags = caracteristicas
        .filter(caracteristica => caracteristica.id_atributo === atributo.id_atributo)
        .map(caracteristica => `
          <span class="tag">
            ${caracteristica.variedad} <span class="remove-tag" data-atributo-id="${atributo.id_atributo}" data-valor="${caracteristica.variedad}">&times;</span>
          </span>`).join('');

      content += `
        <tr>
          <td>${atributo.nombre_atributo}</td>
          <td>${tags}</td>
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
            event.target.value = ''; // Clear the input after submission
            await listAtributos();  // Refresh the list of attributes
          }
        }
      });
    });

    // Agregar event listeners a todos los botones de eliminar etiqueta
    document.querySelectorAll('.remove-tag').forEach(span => {
      span.addEventListener('click', async (event) => {
        const atributoId = event.target.getAttribute('data-atributo-id');
        const valor = event.target.getAttribute('data-valor');

        await eliminarCaracteristica(atributoId, valor);
        await listAtributos();  // Refresh the list of attributes
      });
    });
  } catch (ex) {
    alert('Error al cargar los atributos: ' + ex.message);
  }
};

const listarCaracteristicas = async () => {
  try {
    const response = await fetch(`${SERVERURL}productos/listar_caracteristicas`);
    if (response.ok) {
      const data = await response.json();
      return data;
    } else {
      throw new Error('Error al listar las características');
    }
  } catch (ex) {
    alert('Error al conectarse a la API: ' + ex.message);
    return [];
  }
};

const agregarCaracteristica = async (atributoId, valor) => {
  try {
    const formData = new FormData();
    formData.append('id_atributo', atributoId);
    formData.append('variedad', valor);

    const response = await fetch(`${SERVERURL}productos/agregar_caracteristica`, {
      method: 'POST',
      body: formData,
    });

    if (response.ok) {
      alert('Característica agregada exitosamente');
    } else {
      const error = await response.json();
      alert('Error al agregar la característica: ' + error.message);
    }
  } catch (ex) {
    alert('Error al conectarse a la API: ' + ex.message);
  }
};

const eliminarCaracteristica = async (atributoId, valor) => {
  try {
    const formData = new FormData();
    formData.append('id_atributo', atributoId);
    formData.append('variedad', valor);

    const response = await fetch(`${SERVERURL}productos/eliminar_caracteristica`, {
      method: 'POST',
      body: formData,
    });

    if (response.ok) {
      alert('Característica eliminada exitosamente');
    } else {
      const error = await response.json();
      alert('Error al eliminar la característica: ' + error.message);
    }
  } catch (ex) {
    alert('Error al conectarse a la API: ' + ex.message);
  }
};

window.addEventListener("load", async () => {
  await initDataTable();
});
