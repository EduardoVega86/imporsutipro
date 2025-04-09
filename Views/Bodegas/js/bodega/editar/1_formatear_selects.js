var bodegaId = window.location.pathname.split("/")[window.location.pathname.split("/").length - 1]
$(document).ready(function () {
    // Inicializar Select2 en los selectores
    $('#provincia').select2({
        placeholder: 'Provincia *', allowClear: true
    });
    $('#ciudad').select2({
        placeholder: 'Ciudad *', allowClear: true
    });
    cargarProvincias().then(() => {
        cargarDatosBodega();
    });
    // Actualizar el evento change del selector de provincia
    $('#provincia').change(function () {
        const provinciaId = $(this).val();
        cargarCiudades(provinciaId);
    });
    Swal.fire({
        title: 'Cargando', html: 'Por favor espere...', allowOutsideClick: false, didOpen: () => {
            Swal.showLoading();
        }, showConfirmButton: false
    });
});