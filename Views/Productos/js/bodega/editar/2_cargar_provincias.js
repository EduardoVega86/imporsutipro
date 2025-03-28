async function cargarProvincias() {
    // Habilitar el select y deshabilitar el input
    document.getElementById('ciudad').disabled = true;
    let response = await impAxios(SERVERURL + 'Ubicaciones/obtenerProvincias');
    let provincias = response.data;
    let provinciaSelect = $('#provincia');
    provinciaSelect.empty();
    provinciaSelect.append('<option value="">Provincia *</option>');
    provincias.forEach(function (provincia) {
        provinciaSelect.append(`<option value="${provincia.codigo_provincia}">${provincia.provincia}</option>`);
    });
    $('#provincia').select2({
        placeholder: 'Provincia *', allowClear: true
    });
}
