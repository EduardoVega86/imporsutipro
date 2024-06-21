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
        fillSelectWithKeys('select-nombre', data);
        fillSelectWithKeys('select-apellido', data);
        fillSelectWithKeys('select-principal', data);
        fillSelectWithKeys('select-secundario', data);
        fillSelectWithKeys('select-provincia', data);
        fillSelectWithKeys('select-ciudad', data);
        fillSelectWithKeys('select-codigo_postal', data);
        fillSelectWithKeys('select-pais', data);
        fillSelectWithKeys('select-telefono', data);
        fillSelectWithKeys('select-email', data);
        fillSelectWithKeys('select-total', data);
        fillSelectWithKeys('select-descuento', data);
    }
    
    function fillSelectWithKeys(selectId, data) {
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
            $(`#${selectId}`).select2({ width: '100%' }).on('change', function() {
                const selectedKey = select.value;
                console.log(`Cambio detectado en ${selectId}, valor seleccionado: ${selectedKey}`);
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
    
        const dynamicSelectId = `${parentSelectId}-dynamic-${Date.now()}`;
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
    
        console.log(`Opciones dinámicas creadas para ${parentSelectId}:`, dynamicSelect.innerHTML);
    
        $(`#${dynamicSelectId}`).select2({ width: '100%' }).on('change', function() {
            const selectedKey = dynamicSelect.value;
            console.log(`Cambio detectado en ${dynamicSelectId}, valor seleccionado: ${selectedKey}`);
            removeDynamicSelects(dynamicSelectId);
            if (selectedKey && nestedData[selectedKey] && typeof nestedData[selectedKey] === 'object') {
                createDynamicSelect(dynamicSelectId, nestedData[selectedKey]);
            }
        });
    }
    
    function removeDynamicSelects(parentSelectId) {
        console.log(`Eliminando selects dinámicos relacionados con ${parentSelectId}`);
        const parentSelect = document.getElementById(parentSelectId);
        if (!parentSelect) return;
    
        const dynamicSelects = parentSelect.parentNode.querySelectorAll(`select[id^='${parentSelectId}-dynamic']`);
        dynamicSelects.forEach(select => {
            console.log(`Eliminando select dinámico: ${select.id}`);
            select.parentNode.removeChild(select);
        });
    }
    
    // Escuchar cambios en cualquier select del documento
    document.addEventListener('change', function(event) {
        if (event.target && event.target.nodeName === 'SELECT') {
            console.log(`Cambio detectado en select con id ${event.target.id}`);
        }
    });
    
});
