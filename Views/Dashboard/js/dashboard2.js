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

  // Variables globales para almacenar las referencias a los gráficos
  let salesChart;
  let pastelChart;

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

        // Actualizar la información general
        $("#devoluciones").text(response.devoluciones);
        $("#total_fletes").text(response.envios);
        $("#total_recaudo").text(response.ganancias);
        $("#total_pedidos").text(response.pedidos);
        $("#total_guias").text(response.total_guias);
        $("#total_ventas").text(response.ventas);

        // Limpia el tbody antes de agregar los nuevos datos
        $("#facturas-body").empty();

        // Recorre el array de facturas y crea filas de tabla
        response.facturas.forEach(function (factura) {
          let row = `<tr>
                <td>${factura.numero_factura}</td>
                <td>${factura.fecha_factura}</td>
                <td>${factura.monto_factura}</td>
            </tr>`;
          $("#facturas-body").append(row);
        });

        // Actualizar "Ciudades con más despachos"
        response.ciudad_pedidos.forEach((ciudad, index) => {
          if (index < cityProductElements.length) {
            const percentage =
              (ciudad.cantidad_pedidos / response.pedidos) * 100;
            updateCityProgressBar(
              cityProductElements[index],
              ciudad.cantidad_pedidos,
              percentage
            );
          }
        });

        // Actualizar "Ciudades con más devoluciones"
        response.ciudades_devoluciones.forEach((ciudad, index) => {
          if (index < city_devolucionProductElements.length) {
            const percentage =
              (ciudad.cantidad_entregas / response.pedidos) * 100;
            updateCityProgressBar(
              city_devolucionProductElements[index],
              ciudad.cantidad_entregas,
              percentage
            );
          }
        });

        // Actualizar "Ciudades con más entregas"
        response.ciudades_entregas.forEach((ciudad, index) => {
          if (index < city_entregaProductElements.length) {
            const percentage =
              (ciudad.cantidad_entregas / response.pedidos) * 100;
            updateCityProgressBar(
              city_entregaProductElements[index],
              ciudad.cantidad_entregas,
              percentage
            );
          }
        });

        // Actualizar "Productos por despachos"
        response.productos_despachos.forEach((producto, index) => {
          if (index < productElements.length) {
            const percentage =
              (producto.cantidad_despachos / response.pedidos) * 100;
            updateProductProgressBar(
              productElements[index],
              producto.cantidad_despachos,
              percentage
            );
          }
        });

        // Actualizar "Productos por devoluciones"
        response.productos_despachos_devueltos.forEach((producto, index) => {
          if (index < producto_devolucionElements.length) {
            const percentage =
              (producto.cantidad_devueltos / response.pedidos) * 100;
            updateProductProgressBar(
              producto_devolucionElements[index],
              producto.cantidad_devueltos,
              percentage
            );
          }
        });

        // Actualizar "Productos por entregas"
        response.productos_despachos_entregados.forEach((producto, index) => {
          if (index < producto_entregaElements.length) {
            const percentage =
              (producto.cantidad_entregados / response.pedidos) * 100;
            updateProductProgressBar(
              producto_entregaElements[index],
              producto.cantidad_entregados,
              percentage
            );
          }
        });

        // Aquí se puede agregar cualquier otra funcionalidad necesaria (como actualizar gráficos)

        // Gráfico de líneas (Ventas Diarias)
        let labels = response.ventas_diarias.map((venta) => venta.dia);
        let ventasData = response.ventas_diarias.map((venta) =>
          venta.ventas !== null ? venta.ventas : 0
        );
        let gananciasData = response.ventas_diarias.map((venta) =>
          venta.ganancias !== null ? venta.ganancias : 0
        );
        let enviosData = response.ventas_diarias.map((venta) =>
          venta.envios !== null ? venta.envios : 0
        );
        let cantidadData = response.ventas_diarias.map((venta) =>
          venta.cantidad !== null ? venta.cantidad : 0
        );

        // Destruir el gráfico existente si ya hay uno
        if (salesChart) {
          salesChart.destroy();
        }

        // Crear el nuevo gráfico de líneas con Chart.js
        let ctx = document.getElementById("salesChart").getContext("2d");
        salesChart = new Chart(ctx, {
          type: "line",
          data: {
            labels: labels,
            datasets: [
              {
                label: "Ventas",
                data: ventasData,
                borderColor: "rgba(75, 192, 192, 1)",
                backgroundColor: "rgba(75, 192, 192, 0.2)",
                borderWidth: 1,
                fill: false,
                tension: 0.1,
              },
              {
                label: "Ganancias",
                data: gananciasData,
                borderColor: "rgba(54, 162, 235, 1)",
                backgroundColor: "rgba(54, 162, 235, 0.2)",
                borderWidth: 1,
                fill: false,
                tension: 0.1,
              },
              {
                label: "Envíos",
                data: enviosData,
                borderColor: "rgba(255, 206, 86, 1)",
                backgroundColor: "rgba(255, 206, 86, 0.2)",
                borderWidth: 1,
                fill: false,
                tension: 0.1,
              },
              {
                label: "Cantidad",
                data: cantidadData,
                borderColor: "rgba(153, 102, 255, 1)",
                backgroundColor: "rgba(153, 102, 255, 0.2)",
                borderWidth: 1,
                fill: false,
                tension: 0.1,
              },
            ],
          },
          options: {
            scales: {
              y: {
                beginAtZero: true,
              },
            },
          },
        });

        // Gráfico de pastel (Estados)
        const estadoColors = {
          Anulado: "rgba(255, 0, 0, 0.2)", // rojo
          "En Transito": "rgba(255, 255, 0, 0.2)", // amarillo
          Entregado: "rgba(144, 238, 144, 0.2)", // verde claro
          Generado: "rgba(0, 0, 255, 0.2)", // azul
          Otro: "rgba(128, 128, 128, 0.2)", // gris
          "Por Recolectar": "rgba(128, 0, 128, 0.2)", // morado
        };

        const estadoBorderColors = {
          Anulado: "rgba(255, 0, 0, 1)", // rojo
          "En Transito": "rgba(255, 255, 0, 1)", // amarillo
          Entregado: "rgba(144, 238, 144, 1)", // verde claro
          Generado: "rgba(0, 0, 255, 1)", // azul
          Otro: "rgba(128, 128, 128, 1)", // gris
          "Por Recolectar": "rgba(128, 0, 128, 1)", // morado
        };

        let estadosLabels = response.estados.map(
          (estado) => estado.estado_descripcion
        );
        let estadosData = response.estados.map((estado) => estado.cantidad);
        let estadosBackgroundColors = estadosLabels.map(
          (label) => estadoColors[label]
        );
        let estadosBorderColors = estadosLabels.map(
          (label) => estadoBorderColors[label]
        );

        if (pastelChart) {
          pastelChart.destroy();
        }

        let pastelCtx = document.getElementById("pastelChart").getContext("2d");
        pastelChart = new Chart(pastelCtx, {
          type: "pie",
          data: {
            labels: estadosLabels,
            datasets: [
              {
                data: estadosData,
                backgroundColor: estadosBackgroundColors,
                borderColor: estadosBorderColors,
                borderWidth: 1,
              },
            ],
          },
          options: {
            responsive: true,
            plugins: {
              legend: {
                position: "top",
              },
              tooltip: {
                callbacks: {
                  label: function (tooltipItem) {
                    return tooltipItem.label + ": " + tooltipItem.raw;
                  },
                },
              },
            },
          },
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
