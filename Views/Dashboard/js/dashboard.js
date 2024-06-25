let fecha_inicio = "";
let fecha_fin = "";
$(function () {
  $("#daterange").daterangepicker({
    opens: "right",
    locale: {
      format: "YYYY-MM-DD",
      separator: " - ",
      applyLabel: "Aplicar",
      cancelLabel: "Cancelar",
      fromLabel: "Desde",
      toLabel: "Hasta",
      customRangeLabel: "Custom",
      weekLabel: "S",
      daysOfWeek: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
      monthNames: [
        "Enero",
        "Febrero",
        "Marzo",
        "Abril",
        "Mayo",
        "Junio",
        "Julio",
        "Agosto",
        "Septiembre",
        "Octubre",
        "Noviembre",
        "Diciembre",
      ],
      firstDay: 1,
    },
    autoUpdateInput: false,
  });

  // Evento que se dispara cuando se aplica un nuevo rango de fechas
  $("#daterange").on("apply.daterangepicker", function (ev, picker) {
    // Actualiza el valor del input con el rango de fechas seleccionado
    $(this).val(
      picker.startDate.format("YYYY-MM-DD") +
        " - " +
        picker.endDate.format("YYYY-MM-DD")
    );

    fecha_inicio = picker.startDate.format("YYYY-MM-DD");
    fecha_fin = picker.endDate.format("YYYY-MM-DD");
    informacion_dashboard(fecha_inicio, fecha_fin);
  });

  function informacion_dashboard(fecha_inicio, fecha_fin) {
    let formData = new FormData();
    formData.append("fechai", fecha_inicio);
    formData.append("fechaf", fecha_fin);
    $.ajax({
      url: SERVERURL + "dashboard/filtroInicial",
      type: "POST",
      data: formData,
      processData: false, // No procesar los datos
      contentType: false, // No establecer ningún tipo de contenido
      success: function (response) { 
        response = JSON.parse(response);
        $("#devoluciones").text(response.devoluciones);
        $("#total_fletes").text(response.envios);
        $("#total_recaudo").text(response.ganancias);
        $("#total_pedidos").text(response.pedidos);
        $("#total_guias").text(response.total_guias);
        $("#total_ventas").text(response.ventas);
      },
      error: function (jqXHR, textStatus, errorThrown) {
        alert(errorThrown);
      },
    });
  }

  $(document).ready(function () {
    informacion_dashboard("", "");
  });
});
