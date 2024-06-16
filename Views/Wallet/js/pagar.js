// Obtener la URL actual
const urlActual = window.location.href;
// Crear un objeto URL
const url = new URL(urlActual);
// Obtener el valor del parámetro 'tienda'
const tienda = url.searchParams.get('tienda');

// Añadimos un evento que se ejecuta cuando el DOM ha sido completamente cargado
document.addEventListener('DOMContentLoaded', function() {
    cargarDashboard_wallet();
});

$(document).ready(function(){
    $('#regresar').click(function() {
        window.location.href = SERVERURL+'wallet';
    });
});

function cargarDashboard_wallet(){
    let formData = new FormData();
  formData.append("tienda", tienda);

  $.ajax({
    url: SERVERURL + "wallet/obtenerDetalles",
    type: "POST",
    data: formData,
    processData: false, // No procesar los datos
    contentType: false, // No establecer ningún tipo de contenido
    success: function (response) {
        $('#image_tienda').attr('src', SERVERURL+'public/img/profile_wallet.png');
        $("#totalVentas_wallet").text(response.ventas);
        $("#utilidadGenerada_wallet").text(response.utilidad);
        $("#descuentoDevolucion_wallet").text(response.devoluciones);
        $("#retirosAcreditados_wallet").text(response.abonos_registrados);
        $("#saldoBilletera_wallet").text(response.saldo);
    },
    error: function (jqXHR, textStatus, errorThrown) {
      alert(errorThrown);
    },
  });
}