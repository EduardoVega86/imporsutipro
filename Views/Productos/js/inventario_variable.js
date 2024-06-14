// Inicializa la tabla inventario variable
let dataTableInventario;
let dataTableInventarioIsInitialized = false;

const dataTableInventarioOptions = {
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

const initDataTableInventario = async () => {
    if (dataTableInventarioIsInitialized) {
        dataTableInventario.destroy();
    }

    await listAtributosInventario();

    dataTableInventario = $("#datatable_inventarioVariable").DataTable(dataTableInventarioOptions);

    dataTableInventarioIsInitialized = true;
};

const listAtributosInventario = async () => {
    try {
        const response = await fetch(`${SERVERURL}productos/listar_atributos`);
        const atributos = await response.json();
        const caracteristicas = await listarCaracteristicasInventario();

        let content = ``;
        atributos.forEach((atributo, index) => {
            const tags = caracteristicas
                .filter(caracteristica => caracteristica.id_atributo === atributo.id_atributo)
                .map(caracteristica => `
                    <span class="tag" data-atributo-id="${atributo.id_atributo}" data-valor="${caracteristica.variedad}" data-variedad-id="${caracteristica.id_variedad}">
                        ${caracteristica.variedad} <span class="remove-tag" data-atributo-id="${atributo.id_atributo}" data-valor="${caracteristica.variedad}" data-variedad-id="${caracteristica.id_variedad}">&times;</span>
                    </span>`).join('');

            content += `
                <tr>
                    <td>${atributo.nombre_atributo}</td>
                    <td>${tags}</td>
                    <td><input id="agregar_atributo_${index}" name="agregar_atributo" class="form-control agregar_atributo" type="text" data-atributo-id="${atributo.id_atributo}"></td>
                </tr>`;
        });

        document.getElementById("tableBody_inventarioVariable").innerHTML = content;

        // Agregar event listeners a todos los inputs recién creados
        document.querySelectorAll('.agregar_atributo').forEach(input => {
            input.addEventListener('keypress', async (event) => {
                if (event.key === 'Enter') {
                    event.preventDefault();  // Previene el comportamiento por defecto del Enter
                    const atributoId = event.target.getAttribute('data-atributo-id');
                    const valor = event.target.value;

                    if (valor.trim() !== '') {
                        await agregarCaracteristicaInventario(atributoId, valor);
                        event.target.value = ''; // Clear the input after submission
                        await listAtributosInventario();  // Refresh the list of attributes
                    }
                }
            });
        });

        // Agregar event listeners a todos los botones de eliminar etiqueta
        document.querySelectorAll('.remove-tag').forEach(span => {
            span.addEventListener('click', async (event) => {
                const variedadoId = event.target.getAttribute('data-variedad-id');
                await eliminarCaracteristicaInventario(variedadoId);
                await listAtributosInventario();  // Refresh the list of attributes
            });
        });

        // Agregar event listeners a todos los tags, excepto la X
        document.querySelectorAll('.tag').forEach(tag => {
            tag.addEventListener('click', (event) => {
                if (!event.target.classList.contains('remove-tag')) {
                    const atributoId = tag.getAttribute('data-atributo-id');
                    const valor = tag.getAttribute('data-valor');
                    const id_productoVariable = $('#id_productoVariable').val();
                    $.ajax({
                        url: SERVERURL + "Productos/consultarMaximo/"+id_productoVariable,
                        type: "GET",
                        dataType: "json",
                        success: function (response) {

                            $("#valor_guardar").val(valor);
                            $("#sku_guardar").val(response);

                        },
                        error: function (error) {
                          console.error("Error al obtener la lista de bodegas:", error);
                        },
                      });
                }
            });
        });

    } catch (ex) {
        alert('Error al cargar los atributos: ' + ex.message);
    }
};



const listarCaracteristicasInventario = async () => {
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

const agregarCaracteristicaInventario = async (atributoId, valor) => {
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

const eliminarCaracteristicaInventario = async (variedadoId) => {
    try {
        const formData = new FormData();
        formData.append('id', variedadoId);

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

// Agregar variedad
function agregar_variedad(){
    
}

window.addEventListener("load", async () => {
    await initDataTableInventario();
});