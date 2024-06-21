document.addEventListener("DOMContentLoaded", function () {
    // Event listener para el contenedor que activa la generación del enlace
    document.getElementById("trigger-container").addEventListener("click", function () {
        document.getElementById("loading").style.display = "block";

        setTimeout(function () {
            document.getElementById("loading").style.display = "none";
            $.ajax({
                url: SERVERURL + "shopify/generarEnlace",
                type: "GET",
                dataType: "json",
                success: function (response) {
                    $("#generador_enlace").val(response.url_imporsuit);
                    document.getElementById("enlace-section").style.display = "block";
                },
                error: function (error) {
                    console.error("Error al obtener la lista de bodegas:", error);
                },
            });
        }, 3000);
    });

    // Event listener para el botón de verificación
    document.getElementById('verify-button').addEventListener('click', function() {
        document.getElementById('loading-below').style.display = 'block';

        let intervalId = setInterval(function() {
            $.ajax({
                url: SERVERURL + 'shopify/ultimoJson',
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    if (data && data.id && data.confirmed) {
                        document.getElementById('loading-below').style.display = 'none';
                        console.log('Data received:', data); // Verificar los datos recibidos
                        fillSelectsWithKeys(data);
                        new bootstrap.Collapse(document.getElementById('collapseTwo'), { toggle: true });
                        clearInterval(intervalId);
                    } else {
                        document.getElementById('loading-below').innerHTML =
                          '<div class="spinner-border" role="status"><span class="sr-only">Cargando...</span></div><div>No se pudo obtener información. Intentar nuevamente.</div>';
                    }
                },
                error: function(error) {
                    console.error('Error al llamar a la API:', error);
                    document.getElementById('loading-below').innerHTML =
                      '<div class="spinner-border" role="status"><span class="sr-only">Cargando...</span></div><div>Error al obtener información. Intentar nuevamente.</div>';
                }
            });
        }, 5000);
    });

    function fillSelectsWithKeys(data) {
        const selectIds = [
            'select-nombre', 'select-apellido', 'select-principal', 'select-secundario',
            'select-provincia', 'select-ciudad', 'select-codigo_postal', 'select-pais',
            'select-telefono', 'select-email', 'select-total', 'select-descuento'
        ];

        selectIds.forEach(selectId => {
            const select = document.getElementById(selectId);
            if (select) {
                console.log(`Attaching event listener to ${selectId}`); // Verificar si el elemento select existe
                select.innerHTML = '<option value="" selected>-- Seleccione --</option>';
                for (let key in data) {
                    if (data.hasOwnProperty(key)) {
                        const option = document.createElement('option');
                        option.value = key;
                        option.text = key;
                        select.appendChild(option);
                    }
                }
                console.log(`Options for ${selectId}:`, select.innerHTML); // Verificar opciones añadidas

                // Inicializar select2 y luego adjuntar el event listener
                $(`#${selectId}`).select2({ width: '100%' }).on('change', function() {
                    console.log(`Change event detected on ${selectId}`); // Verificar si el evento change se detecta
                    const selectedKey = select.value;
                    removeDynamicSelects(selectId); // Remove existing dynamic selects
                    if (selectedKey && data[selectedKey] && typeof data[selectedKey] === 'object') {
                        console.log(`Creating dynamic select for ${selectedKey}`); // Verificar si se está creando el select dinámico
                        createDynamicSelect(selectId, data[selectedKey]);
                    }
                });
            } else {
                console.error(`El elemento con id ${selectId} no existe en el DOM.`);
            }
        });
    }

    function createDynamicSelect(parentSelectId, nestedData) {
        const parentSelect = document.getElementById(parentSelectId);
        if (!parentSelect) return;

        const dynamicSelectId = `${parentSelectId}-dynamic`;
        let dynamicSelect = document.getElementById(dynamicSelectId);

        if (!dynamicSelect) {
            dynamicSelect = document.createElement('select');
            dynamicSelect.id = dynamicSelectId;
            dynamicSelect.className = 'form-select mt-2';
            parentSelect.parentNode.appendChild(dynamicSelect);
        }

        dynamicSelect.innerHTML = '<option value="" selected>-- Seleccione --</option>';
        for (let key in nestedData) {
            if (nestedData.hasOwnProperty(key)) {
                const option = document.createElement('option');
                option.value = key;
                option.text = key;
                dynamicSelect.appendChild(option);
            }
        }

        console.log(`Dynamic options for ${parentSelectId}:`, dynamicSelect.innerHTML); // Verificar opciones dinámicas añadidas
        $(`#${dynamicSelectId}`).select2({ width: '100%' }).on('change', function() {
            console.log(`Change event detected on ${dynamicSelectId}`); // Verificar si el evento change se detecta
            const selectedKey = dynamicSelect.value;
            removeDynamicSelects(dynamicSelectId); // Remove existing dynamic selects
            if (selectedKey && nestedData[selectedKey] && typeof nestedData[selectedKey] === 'object') {
                console.log(`Creating dynamic select for ${selectedKey}`); // Verificar si se está creando el select dinámico
                createDynamicSelect(dynamicSelectId, nestedData[selectedKey]);
            }
        });
    }

    function removeDynamicSelects(parentSelectId) {
        const parentSelect = document.getElementById(parentSelectId);
        if (!parentSelect) return;

        // Buscar todos los selectores dinámicos relacionados con el parentSelectId
        const dynamicSelects = parentSelect.parentNode.querySelectorAll(`select[id^='${parentSelectId}-dynamic']`);
        console.log(`Removing dynamic selects for ${parentSelectId}:`, dynamicSelects); // Verificar los selectores dinámicos a eliminar
        dynamicSelects.forEach(select => {
            // Eliminar el select
            select.parentNode.removeChild(select);
        });
    }

    // Escuchar cambios en cualquier select del documento
    document.addEventListener('change', function(event) {
        if (event.target && event.target.nodeName === 'SELECT') {
            console.log(`Change detected on select with id ${event.target.id}`);
        }
    });
});
