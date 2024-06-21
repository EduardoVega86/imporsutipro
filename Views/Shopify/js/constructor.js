document.getElementById("trigger-container").addEventListener("click", function () {
    // Mostrar la animación de carga de botón Shopify
    document.getElementById("loading").style.display = "block";

    // Esperar 3 segundos y luego mostrar la sección de enlace generado
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

document.getElementById('verify-button').addEventListener('click', function() {
    // Mostrar la animación de carga debajo del input
    document.getElementById('loading-below').style.display = 'block';

    // Iniciar el bucle de verificación
    let intervalId = setInterval(function() {
        $.ajax({
            url: SERVERURL + 'shopify/ultimoJson',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                if (data && data.id && data.confirmed) {
                    // Ocultar la animación de carga debajo del input
                    document.getElementById('loading-below').style.display = 'none';

                    // Llenar los selects con las claves del JSON
                    fillSelectsWithKeys(data);

                    // Abrir el siguiente acordeón
                    var collapseTwo = new bootstrap.Collapse(document.getElementById('collapseTwo'), {
                        toggle: true
                    });

                    // Terminar el intervalo
                    clearInterval(intervalId);
                } else {
                    // La condición no se cumple, mantener la animación de carga o mostrar un mensaje de error
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
    const container = document.getElementById('dynamic-select-container');
    container.innerHTML = ''; // Limpiar el contenedor antes de llenarlo

    const createSelect = (key, data) => {
        const selectContainer = document.createElement('div');
        selectContainer.className = 'form-group w-100 hidden-field';
        selectContainer.style.display = 'none'; // Ocultarlo inicialmente

        const label = document.createElement('label');
        label.textContent = key;
        selectContainer.appendChild(label);

        const select = document.createElement('select');
        select.className = 'form-select';
        select.id = `select-${key}`;
        select.innerHTML = '<option value="" selected>-- Seleccione --</option>';

        for (let subkey in data) {
            if (data.hasOwnProperty(subkey)) {
                const option = document.createElement('option');
                option.value = subkey;
                option.text = subkey;
                select.appendChild(option);
            }
        }

        selectContainer.appendChild(select);
        container.appendChild(selectContainer);
        $(`#select-${key}`).select2({ width: '100%' });

        select.addEventListener('change', function() {
            const selectedKey = this.value;
            if (data[selectedKey] && typeof data[selectedKey] === 'object' && !Array.isArray(data[selectedKey])) {
                document.getElementById(`dynamic-${key}-${selectedKey}`).style.display = 'block';
            }
        });
    };

    const processKeys = (data, prefix = '') => {
        for (let key in data) {
            if (data.hasOwnProperty(key)) {
                if (typeof data[key] === 'object' && !Array.isArray(data[key])) {
                    createSelect(`${prefix}${key}`, data[key]);
                    processKeys(data[key], `${key}-`);
                }
            }
        }
    };

    processKeys(data);
}
