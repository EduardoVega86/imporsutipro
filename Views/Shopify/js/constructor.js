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
                select.innerHTML = '<option value="" selected>-- Seleccione --</option>';
                for (let key in data) {
                    if (data.hasOwnProperty(key)) {
                        const option = document.createElement('option');
                        option.value = key;
                        option.text = key;
                        select.appendChild(option);
                    }
                }
                $(`#${selectId}`).select2({ width: '100%' });

                // Añadir event listener para cada select
                select.addEventListener('change', function() {
                    const selectedKey = select.value;
                    if (selectedKey && data[selectedKey] && typeof data[selectedKey] === 'object') {
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

        $(`#${dynamicSelectId}`).select2({ width: '100%' });
    }
});
