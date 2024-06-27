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
  
        // Limpia el tbody antes de agregar los nuevos datos
        $("#facturas-body").empty();
  
        // Recorre el array de facturas y crea filas de tabla
        response.facturas.forEach(function(factura) {
            let row = `<tr>
                <td>${factura.numero_factura}</td>
                <td>${factura.fecha_factura}</td>
                <td>${factura.monto_factura}</td>
            </tr>`;
            $("#facturas-body").append(row);
        });
  
        // Preparar los datos para el gráfico
        let labels = response.ventas_diarias.map(venta => venta.dia);
        let ventasData = response.ventas_diarias.map(venta => venta.ventas !== null ? venta.ventas : 0);
        let gananciasData = response.ventas_diarias.map(venta => venta.ganancias !== null ? venta.ganancias : 0);
        let enviosData = response.ventas_diarias.map(venta => venta.envios !== null ? venta.envios : 0);
  
        // Crear el gráfico con Chart.js
        let ctx = document.getElementById('salesChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Ventas',
                        data: ventasData,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Ganancias',
                        data: gananciasData,
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Envíos',
                        data: enviosData,
                        backgroundColor: 'rgba(255, 206, 86, 0.2)',
                        borderColor: 'rgba(255, 206, 86, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
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
