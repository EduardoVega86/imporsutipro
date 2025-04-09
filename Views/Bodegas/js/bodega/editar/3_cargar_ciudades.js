function cargarCiudades(provinciaId, ciudadId = null) {
    if (provinciaId) {
        return $.ajax({
            url: SERVERURL + 'Ubicaciones/obtenerCiudades/' + provinciaId,
            method: 'GET',
            success: function (response) {
                let ciudades = JSON.parse(response);
                let ciudadSelect = $('#ciudad');
                ciudadSelect.empty();
                ciudadSelect.append('<option value="">Ciudad *</option>');
                ciudades.forEach(function (ciudad) {
                    ciudadSelect.append(`<option value="${ciudad.id_cotizacion}">${ciudad.ciudad}</option>`);
                });
                if (ciudadId) {
                    ciudadSelect.val(ciudadId).trigger('change');
                }
                ciudadSelect.prop('disabled', false).select2({
                    placeholder: 'Ciudad *', allowClear: true
                });
            },
            error: function (error) {
                console.log('Error al cargar ciudades:', error);
            }
        });
    }
}
