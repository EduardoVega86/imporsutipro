document.addEventListener('DOMContentLoaded', function() {
    const productoVariableSelect = document.getElementById('producto-variable');
    const manejaInventarioSelect = document.getElementById('maneja-inventario');
    const inventarioVariableTab = document.getElementById('inventario-variable-tab');
    const bodegaField = document.getElementById('bodega-field');
    const precioReferencialCheckbox = document.getElementById('precio-referencial');
    const precioReferencialInput = document.getElementById('precio-referencial-valor');

    function toggleInventarioVariableTab() {
        if (productoVariableSelect.value === '1') { // 1 para "Sí"
            inventarioVariableTab.classList.remove('hidden-tab');
        } else {
            inventarioVariableTab.classList.add('hidden-tab');
        }
    }

    function toggleBodegaField() {
        if (manejaInventarioSelect.value === '1' && productoVariableSelect.value === '2') { // 1 para "Sí" y 2 para "No"
            bodegaField.classList.remove('hidden-field');
        } else {
            bodegaField.classList.add('hidden-field');
        }
    }

    function togglePrecioReferencialInput() {
        precioReferencialInput.disabled = !precioReferencialCheckbox.checked;
    }

    productoVariableSelect.addEventListener('change', function() {
        toggleInventarioVariableTab();
        toggleBodegaField();
    });

    manejaInventarioSelect.addEventListener('change', toggleBodegaField);
    precioReferencialCheckbox.addEventListener('change', togglePrecioReferencialInput);

    toggleInventarioVariableTab(); // Llama a la función al cargar la página para ajustar la visibilidad inicial
    toggleBodegaField(); // Llama a la función al cargar la página para ajustar la visibilidad inicial
    togglePrecioReferencialInput(); // Llama a la función al cargar la página para ajustar la visibilidad inicial
});

//enviar datos a base de datos
$(document).ready(function() {
    $('#agregar_producto_form').submit(function(event) {
        event.preventDefault(); // Evita que el formulario se envíe de la forma tradicional

        // Crea un objeto FormData
        var formData = new FormData();
        formData.append('codigo_producto', $('#codigo').val());
        formData.append('nombre_producto', $('#nombre').val());
        formData.append('descripcion_producto', $('#descripcion').val());
        formData.append('id_linea_producto', $('#categoria').val());
        formData.append('inv_producto', $('#maneja-inventario').val());
        formData.append('producto_variable', $('#producto-variable').val());
        formData.append('costo_producto', $('#costo').val());
        formData.append('aplica_iva', 1); // Suponiendo que siempre aplica IVA
        formData.append('estado_producto', 1); // Suponiendo que el estado es activo
        formData.append('date_added', new Date().toISOString().split('T')[0]);
        formData.append('image_path', ''); // Asumiendo que no hay imagen por ahora
        formData.append('pagina_web', $('#formato-pagina').val());
        formData.append('formato', 'Formato 1'); // Suponiendo que siempre es Formato 1
        formData.append('drogshipin', 0); // Suponiendo que no es dropshipping
        formData.append('destacado', 0); // Suponiendo que no es destacado
        formData.append('stock_inicial', $('#stock-inicial').val());
        formData.append('bodega', $('#bodega').val());
        formData.append('pcp', $('#precio-proveedor').val());
        formData.append('pvp', $('#precio-venta').val());
        formData.append('pref', $('#precio-referencial-valor').val());

        // Realiza la solicitud AJAX
        $.ajax({
            url: '' + SERVERURL + 'productos/agregar_producto',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                alert('Producto agregado exitosamente');
                console.log(response);
            },
            error: function(error) {
                alert('Hubo un error al agregar el producto');
                console.log(error);
            }
        });
    });
});

//cargar select de bodega 
$(document).ready(function() {
    // Realiza la solicitud AJAX para obtener la lista de bodegas
    $.ajax({
        url: '' + SERVERURL + 'productos/listar_bodegas',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            // Asegúrate de que la respuesta es un array
            if (Array.isArray(response)) {
                response.forEach(function(bodega) {
                    // Agrega una nueva opción al select por cada bodega
                    $('#bodega').append(new Option(bodega.nombre, bodega.id));
                });
            } else {
                console.log('La respuesta de la API no es un array:', response);
            }
        },
        error: function(error) {
            console.error('Error al obtener la lista de bodegas:', error);
        }
    });
});

//cargar select categoria
$(document).ready(function() {
    // Realiza la solicitud AJAX para obtener la lista de categorias
    $.ajax({
        url: '' + SERVERURL + 'productos/cargar_categorias',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            // Asegúrate de que la respuesta es un array
            if (Array.isArray(response)) {
                response.forEach(function(categoria) {
                    // Agrega una nueva opción al select por cada categoria
                    $('#categoria').append(new Option(categoria.nombre_linea, categoria.id_linea));
                });
            } else {
                console.log('La respuesta de la API no es un array:', response);
            }
        },
        error: function(error) {
            console.error('Error al obtener la lista de categorias:', error);
        }
    });
});

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
                    <span class="tag">
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

        // Agregar event listeners a todos los atributos (excepto la "x")
        document.querySelectorAll('.tag').forEach(span => {
            span.addEventListener('click', async (event) => {
                const atributoId = event.target.getAttribute('data-atributo-id');
                const valor = event.target.textContent.trim();

                await agregarFilaDetalleInventario(atributoId, valor);
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

const agregarFilaDetalleInventario = async (atributoId, valor) => {
    const codigoProducto = $('#codigo').val();
    const numeroIncremental = document.querySelectorAll('#tableBody_detalleInventario tr').length + 1;
    const idVariable = `${codigoProducto}-${numeroIncremental}`;

    const nuevaFila = `
        <tr>
            <td>${atributoId}</td>
            <td><input type="text" class="form-control" value="${valor}"></td>
            <td><input type="text" class="form-control"></td>
            <td><input type="text" class="form-control"></td>
            <td><input type="text" class="form-control"></td>
            <td><input type="text" class="form-control"></td>
            <td><input type="text" class="form-control"></td>
            <td>${idVariable}</td>
        </tr>
    `;

    document.getElementById('tableBody_detalleInventario').insertAdjacentHTML('beforeend', nuevaFila);
};

window.addEventListener("load", async () => {
    await initDataTableInventario();
});