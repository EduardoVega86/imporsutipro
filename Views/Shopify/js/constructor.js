document.addEventListener("DOMContentLoaded", function () {
    const SERVERURL = "your_server_url"; // Reemplaza con tu URL del servidor

    function initializeSelect(selectId, data) {
        const select = document.getElementById(selectId);
        if (select) {
            console.log(`Initializing ${selectId}`);
            select.innerHTML = '<option value="" selected>-- Seleccione --</option>';
            for (let key in data) {
                if (data.hasOwnProperty(key)) {
                    const option = document.createElement('option');
                    option.value = key;
                    option.text = key;
                    select.appendChild(option);
                }
            }
            $(`#${selectId}`).select2({ width: '100%' }).on('change', function() {
                const selectedKey = select.value;
                removeDynamicSelects(selectId);
                if (selectedKey && data[selectedKey] && typeof data[selectedKey] === 'object') {
                    createDynamicSelect(selectId, data[selectedKey]);
                }
            });
        } else {
            console.error(`El elemento con id ${selectId} no existe en el DOM.`);
        }
    }

    function createDynamicSelect(parentSelectId, nestedData) {
        const parentSelect = document.getElementById(parentSelectId);
        if (!parentSelect) return;

        const dynamicSelectId = `${parentSelectId}-dynamic`;
        removeDynamicSelects(dynamicSelectId);

        let dynamicSelect = document.createElement('select');
        dynamicSelect.id = dynamicSelectId;
        dynamicSelect.className = 'form-select mt-2';
        parentSelect.parentNode.appendChild(dynamicSelect);

        dynamicSelect.innerHTML = '<option value="" selected>-- Seleccione --</option>';
        for (let key in nestedData) {
            if (nestedData.hasOwnProperty(key)) {
                const option = document.createElement('option');
                option.value = key;
                option.text = key;
                dynamicSelect.appendChild(option);
            }
        }

        $(`#${dynamicSelectId}`).select2({ width: '100%' }).on('change', function() {
            const selectedKey = dynamicSelect.value;
            removeDynamicSelects(dynamicSelectId);
            if (selectedKey && nestedData[selectedKey] && typeof nestedData[selectedKey] === 'object') {
                createDynamicSelect(dynamicSelectId, nestedData[selectedKey]);
            }
        });
    }

    function removeDynamicSelects(parentSelectId) {
        const parentSelect = document.getElementById(parentSelectId);
        if (!parentSelect) return;

        const dynamicSelects = parentSelect.parentNode.querySelectorAll(`select[id^='${parentSelectId}-dynamic']`);
        dynamicSelects.forEach(select => {
            select.parentNode.removeChild(select);
        });
    }

    function fetchDataAndInitializeSelects() {
        $.ajax({
            url: SERVERURL + 'shopify/ultimoJson',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                if (data && data.id && data.confirmed) {
                    const selectIds = [
                        'select-nombre', 'select-apellido', 'select-principal', 'select-secundario',
                        'select-provincia', 'select-ciudad', 'select-codigo_postal', 'select-pais',
                        'select-telefono', 'select-email', 'select-total', 'select-descuento'
                    ];
                    selectIds.forEach(selectId => {
                        initializeSelect(selectId, data);
                    });
                } else {
                    console.error('No se pudo obtener información. Intentar nuevamente.');
                }
            },
            error: function(error) {
                console.error('Error al llamar a la API:', error);
            }
        });
    }

    // Event listener para el botón de verificación
    document.getElementById('verify-button').addEventListener('click', function() {
        fetchDataAndInitializeSelects();
    });

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
});
