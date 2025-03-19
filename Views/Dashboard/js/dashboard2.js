let fecha_inicio = "";
let fecha_fin = "";
let performanceChart; // Gráfico final (barras con datos de las cards)
let distributionChart; // Gráfico de estados (si lo usas)

// Función para obtener las fechas por defecto (primer y último día del mes actual)
function obtenerFechasPorDefecto() {
  let now = new Date();
  let firstDay = new Date(now.getFullYear(), now.getMonth(), 1);
  let lastDay = new Date(now.getFullYear(), now.getMonth() + 1, 0);
  const pad = (n) => (n < 10 ? "0" + n : n);

  let fechaInicio = `${firstDay.getFullYear()}-${pad(firstDay.getMonth() + 1)}-${pad(firstDay.getDate())}`;
  let fechaFin = `${lastDay.getFullYear()}-${pad(lastDay.getMonth() + 1)}-${pad(lastDay.getDate())}`;
  return { fechaInicio, fechaFin };
}

$(function () {
  // Inicializar el DateRangePicker
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

  // Cada vez que se selecciona un rango
  $("#daterange").on("apply.daterangepicker", function (ev, picker) {
    $(this).val(
      picker.startDate.format("YYYY-MM-DD") + " - " + picker.endDate.format("YYYY-MM-DD")
    );

    // Asignar fechas
    fecha_inicio = picker.startDate.format("YYYY-MM-DD");
    fecha_fin = picker.endDate.format("YYYY-MM-DD") + " 23:59:59";

    // Llamar a las funciones
    informacion_dashboard(fecha_inicio, fecha_fin);
    actualizarCardsPedidos(fecha_inicio, fecha_fin);
  });

  // Al cargar la página
  $(document).ready(function () {
    let { fechaInicio, fechaFin } = obtenerFechasPorDefecto();
    $("#daterange").val(fechaInicio + " - " + fechaFin);

    // Llamadas iniciales
    informacion_dashboard(fechaInicio, fechaFin);
    actualizarCardsPedidos(fechaInicio, fechaFin);
  });

  /**
   * Función para el panel general del dashboard (productos, ciudades, etc.)
   * Mantiene la lógica que ya tenías (tabla facturas, distributionChart, etc.).
   */
  function informacion_dashboard(fecha_inicio, fecha_fin) {
    let formData = new FormData();
    formData.append("fechai", fecha_inicio);
    formData.append("fechaf", fecha_fin);

    $.ajax({
      url: SERVERURL + "dashboard/filtroInicial",
      type: "POST",
      data: formData,
      processData: false,
      contentType: false,
      success: function (response) {
        response = JSON.parse(response);

        // Actualizar algunos datos de texto
        $("#devoluciones").text(
          response.devoluciones
            ? `$${parseFloat(response.devoluciones).toLocaleString("en-US", {
                minimumFractionDigits: 2,
              })}`
            : "$0.00"
        );
        $("#total_fletes").text(
          response.envios
            ? `$${parseFloat(response.envios).toLocaleString("en-US", {
                minimumFractionDigits: 2,
              })}`
            : "$0.00"
        );
        $("#total_recaudo").text(
          response.ganancias
            ? `$${parseFloat(response.ganancias).toLocaleString("en-US", {
                minimumFractionDigits: 2,
              })}`
            : "$0.00"
        );
        $("#ticket_promedio").text(
          parseFloat(response.ticket_promedio).toFixed(2)
        );
        $("#flete_promedio").text(
          parseFloat(response.flete_promedio).toFixed(2)
        );
        $("#devolucion_promedio").text(
          parseFloat(response.devolucion_promedio).toFixed(2)
        );

        // Últimas facturas
        $("#facturas-body").empty();
        response.facturas.forEach(function (factura) {
          let row = `<tr>
                <td>${factura.numero_factura}</td>
                <td>${factura.fecha_factura}</td>
                <td>${factura.monto_factura}</td>
            </tr>`;
          $("#facturas-body").append(row);
        });

        // (Comentado) Gráfico de líneas (ventas_diarias) que no queremos mostrar
        /*
        if (salesChart) {
          salesChart.destroy();
        }
        // ... tu código anterior de 'ventas_diarias' ...
        */

        // ***** Gráfico de Estados (distributionChart) *****
        if (distributionChart) {
          distributionChart.destroy();
        }
        let estadosLabels = response.estados.map(
          (estado) => estado.estado_descripcion
        );
        let estadosData = response.estados.map(
          (estado) => estado.cantidad
        );

        const paletteBackground = [
          "rgba(255, 99, 132, 0.8)",
          "rgba(54, 162, 235, 0.8)",
          "rgba(255, 206, 86, 0.8)",
          "rgba(75, 192, 192, 0.8)",
          "rgba(153, 102, 255, 0.8)",
          "rgba(255, 159, 64, 0.8)",
        ];
        let estadosBackgroundColors = estadosLabels.map((label, index) => {
          return paletteBackground[index % paletteBackground.length];
        });

        const ctxDistribution = document
          .getElementById("distributionChart")
          .getContext("2d");
        distributionChart = new Chart(ctxDistribution, {
          type: "bar",
          data: {
            labels: estadosLabels,
            datasets: [
              {
                label: "Cantidad de guías",
                data: estadosData,
                backgroundColor: estadosBackgroundColors,
                borderWidth: 0,
              },
            ],
          },
          options: {
            indexAxis: "y",
            scales: {
              x: {
                beginAtZero: true,
                ticks: {
                  precision: 0,
                },
              },
            },
            plugins: {
              legend: {
                display: false,
              },
              tooltip: {
                callbacks: {
                  label: function (context) {
                    return context.label + ": " + context.raw;
                  },
                },
              },
            },
          },
        });

        // ***** Sección de productos despachados *****
        let total_despachos = 0;
        response.productos_despachos.forEach((product) => {
          var cant = parseFloat(product.cantidad_despachos);
          if (cant > 0) total_despachos += cant;
        });
        document.getElementById("products-container").innerHTML = "";
        response.productos_despachos.forEach((product) => {
          var cant = parseFloat(product.cantidad_despachos);
          if (cant > 0) {
            let porcentaje = calcularPorcentaje(cant, total_despachos);
            updateProductProgressBar(
              cant,
              product.nombre_producto,
              product.image_path,
              porcentaje
            );
          }
        });

        // ***** Sección de productos entregados *****
        let total_entregados = 0;
        response.productos_despachos_entregados.forEach((product) => {
          var cant = parseFloat(product.cantidad_despachos);
          if (cant > 0) total_entregados += cant;
        });
        document.getElementById("productsEntregados-container").innerHTML = "";
        response.productos_despachos_entregados.forEach((product) => {
          var cant = parseFloat(product.cantidad_despachos);
          if (cant > 0) {
            let porcentaje = calcularPorcentaje(cant, total_entregados);
            updateProductProgressBar_entrega(
              cant,
              product.nombre_producto,
              product.image_path,
              porcentaje
            );
          }
        });

        // ***** Sección de productos devueltos *****
        let total_devolucion = 0;
        response.productos_despachos_devueltos.forEach((product) => {
          var cant = parseFloat(product.cantidad_despachos);
          if (cant > 0) total_devolucion += cant;
        });
        document.getElementById("productsDevolucion-container").innerHTML = "";
        response.productos_despachos_devueltos.forEach((product) => {
          var cant = parseFloat(product.cantidad_despachos);
          if (cant > 0) {
            let porcentaje = calcularPorcentaje(cant, total_devolucion);
            updateProductProgressBar_devolucion(
              cant,
              product.nombre_producto,
              product.image_path,
              porcentaje
            );
          }
        });

        // ***** Sección de ciudades despachos *****
        let total_despachos_ciudad = 0;
        response.ciudad_pedidos.forEach((city) => {
          var cant = parseFloat(city.cantidad_pedidos);
          if (cant > 0) total_despachos_ciudad += cant;
        });
        document.getElementById("ciudades-container").innerHTML = "";
        response.ciudad_pedidos.forEach((city) => {
          var cant = parseFloat(city.cantidad_pedidos);
          if (cant > 0) {
            let porcentaje = calcularPorcentaje(cant, total_despachos_ciudad);
            updateCityProgressBar(cant, city.ciudad, porcentaje);
          }
        });

        // ***** Sección de ciudades entregadas *****
        let total_despachos_ciudad_entregado = 0;
        response.ciudades_entregas.forEach((city) => {
          var cant = parseFloat(city.cantidad_entregas);
          if (cant > 0) total_despachos_ciudad_entregado += cant;
        });
        document.getElementById("ciudadesEntregadas-container").innerHTML = "";
        response.ciudades_entregas.forEach((city) => {
          var cant = parseFloat(city.cantidad_entregas);
          if (cant > 0) {
            let porcentaje = calcularPorcentaje(cant, total_despachos_ciudad_entregado);
            updateCityProgressBar_entregar(cant, city.ciudad, porcentaje);
          }
        });

        // ***** Sección de ciudades con devoluciones *****
        let total_despachos_ciudad_devolucion = 0;
        response.ciudades_devoluciones.forEach((city) => {
          var cant = parseFloat(city.cantidad_entregas);
          if (cant > 0) total_despachos_ciudad_devolucion += cant;
        });
        document.getElementById("ciudadesDevolucion-container").innerHTML = "";
        response.ciudades_devoluciones.forEach((city) => {
          var cant = parseFloat(city.cantidad_entregas);
          if (cant > 0) {
            let porcentaje = calcularPorcentaje(cant, total_despachos_ciudad_devolucion);
            updateCityProgressBar_devolucion(cant, city.ciudad, porcentaje);
          }
        });
      },
      error: function (jqXHR, textStatus, errorThrown) {
        alert(errorThrown);
      },
    });
  }

  // ======== Funciones auxiliares para las barras de progreso (productos, ciudades) ========
  function calcularPorcentaje(cantidad, total) {
    if (!total) return 0;
    return (cantidad / total) * 100;
  }

  function updateProductProgressBar(cant, nombre_producto, imagen, porcentaje) {
    const productElement = document.createElement("div");
    productElement.classList.add("product");
    productElement.innerHTML = `
      <div class="product-info">
        <img src="${SERVERURL}${imagen}" alt="${nombre_producto}" class="product-icon">
        <span>${nombre_producto}</span>
        <span class="quantity">${cant} (${porcentaje.toFixed(2)}%)</span>
      </div>
      <div class="progress-bar">
        <div class="progress" style="width: ${porcentaje}%;"></div>
      </div>
    `;
    document.getElementById("products-container").appendChild(productElement);
  }

  function updateCityProgressBar(cant, ciudad, porcentaje) {
    const productElement = document.createElement("div");
    productElement.classList.add("product");
    productElement.innerHTML = `
      <div class="product-info">
        <span>${ciudad}</span>
        <span class="quantity">${cant} (${porcentaje.toFixed(2)}%)</span>
      </div>
      <div class="progress-bar">
        <div class="progress" style="width: ${porcentaje}%;"></div>
      </div>
    `;
    document.getElementById("ciudades-container").appendChild(productElement);
  }

  function updateProductProgressBar_entrega(cant, nombre_producto, imagen, porcentaje) {
    const productElement = document.createElement("div");
    productElement.classList.add("product");
    productElement.innerHTML = `
      <div class="product-info">
        <img src="${SERVERURL}${imagen}" alt="${nombre_producto}" class="product-icon">
        <span>${nombre_producto}</span>
        <span class="quantity">${cant} (${porcentaje.toFixed(2)}%)</span>
      </div>
      <div class="progress-bar">
        <div class="progress" style="width: ${porcentaje}%;"></div>
      </div>
    `;
    document.getElementById("productsEntregados-container").appendChild(productElement);
  }

  function updateCityProgressBar_entregar(cant, ciudad, porcentaje) {
    const productElement = document.createElement("div");
    productElement.classList.add("product");
    productElement.innerHTML = `
      <div class="product-info">
        <span>${ciudad}</span>
        <span class="quantity">${cant} (${porcentaje.toFixed(2)}%)</span>
      </div>
      <div class="progress-bar">
        <div class="progress" style="width: ${porcentaje}%;"></div>
      </div>
    `;
    document.getElementById("ciudadesEntregadas-container").appendChild(productElement);
  }

  function updateProductProgressBar_devolucion(cant, nombre_producto, imagen, porcentaje) {
    const productElement = document.createElement("div");
    productElement.classList.add("product");
    productElement.innerHTML = `
      <div class="product-info">
        <img src="${SERVERURL}${imagen}" alt="${nombre_producto}" class="product-icon">
        <span>${nombre_producto}</span>
        <span class="quantity">${cant} (${porcentaje.toFixed(2)}%)</span>
      </div>
      <div class="progress-bar">
        <div class="progress" style="width: ${porcentaje}%;"></div>
      </div>
    `;
    document.getElementById("productsDevolucion-container").appendChild(productElement);
  }

  function updateCityProgressBar_devolucion(cant, ciudad, porcentaje) {
    const productElement = document.createElement("div");
    productElement.classList.add("product");
    productElement.innerHTML = `
      <div class="product-info">
        <span>${ciudad}</span>
        <span class="quantity">${cant} (${porcentaje.toFixed(2)}%)</span>
      </div>
      <div class="progress-bar">
        <div class="progress" style="width: ${porcentaje}%;"></div>
      </div>
    `;
    document.getElementById("ciudadesDevolucion-container").appendChild(productElement);
  }

  /**
   * Función que carga/actualiza las "cards" y pinta un GRÁFICO DE BARRAS
   * basándose SOLO en la información de las cards.
   */
  function actualizarCardsPedidos(fecha_inicio, fecha_fin) {
    let formData = new FormData();
    formData.append("fecha_inicio", fecha_inicio);
    formData.append("fecha_fin", fecha_fin);

    $.ajax({
      url: SERVERURL + "Pedidos/cargar_cards_pedidos_mes",
      type: "POST",
      data: formData,
      processData: false,
      contentType: false,
      dataType: "json",
      success: function (data) {
        // Si la respuesta no es objeto, parsear
        if (typeof data !== "object") {
          data = JSON.parse(data);
        }

        // 1) Actualizar las 4 cards
        // => "Valor Total" (#total_ventas)
        $("#total_ventas").text(
          data.valor_pedidos
            ? `$${parseFloat(data.valor_pedidos).toLocaleString("en-US", {
                minimumFractionDigits: 2,
              })}`
            : "$0.00"
        );

        // => "Guías Generadas" (#total_guias)
        $("#total_guias").text(data.total_guias || 0);

        // => "Utilidad Total" (#total_recaudo)
        $("#total_recaudo").text(
          data.ganancias
            ? `$${parseFloat(data.ganancias).toLocaleString("en-US", {
                minimumFractionDigits: 2,
              })}`
            : "$0.00"
        );

        // => "Guías Entregadas" (#total_entregado)
        $("#total_entregado").text(data.total_guias_entregadas || 0);

        // 2) Dibujar un gráfico de BARRAS con esos mismos valores
        // Armamos un array con 4 barras
        const chartLabels = [
          "Valor Total",
          "Guías Generadas",
          "Utilidad Total",
          "Guías Entregadas",
        ];

        const chartData = [
          parseFloat(data.valor_pedidos) || 0,
          parseFloat(data.total_guias) || 0,
          parseFloat(data.ganancias) || 0,
          parseFloat(data.total_guias_entregadas) || 0,
        ];

        // Destruimos el gráfico anterior si existe
        if (performanceChart) {
          performanceChart.destroy();
        }

        let ctxPerf = document.getElementById("salesChart").getContext("2d");
        performanceChart = new Chart(ctxPerf, {
          type: "bar",
          data: {
            labels: chartLabels,
            datasets: [
              {
                label: "Rendimiento (según Cards)",
                data: chartData,
                backgroundColor: "rgba(75, 192, 192, 0.7)",
                borderColor: "rgba(75, 192, 192, 1)",
                borderWidth: 1,
              },
            ],
          },
          options: {
            responsive: true,
            scales: {
              y: {
                beginAtZero: true,
                ticks: {
                  precision: 0,
                },
              },
            },
          },
        });
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.error("Error al actualizar las cards:", errorThrown);
      },
    });
  }
});
