let fecha_inicio = "";
let fecha_fin = "";
let performanceChart; // Gráfico de rendimiento (Cards)
let distributionChart; // Gráfico de estados

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

  // Cuando se selecciona un rango de fechas
  $("#daterange").on("apply.daterangepicker", function (ev, picker) {
    // Actualiza el input con el rango seleccionado
    $(this).val(
      picker.startDate.format("YYYY-MM-DD") +
        " - " +
        picker.endDate.format("YYYY-MM-DD")
    );

    // Asignamos las fechas seleccionadas
    fecha_inicio = picker.startDate.format("YYYY-MM-DD");
    fecha_fin = picker.endDate.format("YYYY-MM-DD") + " 23:59:59";

    // Llamamos a ambas funciones con el rango seleccionado
    informacion_dashboard(fecha_inicio, fecha_fin);
    actualizarCardsPedidos(fecha_inicio, fecha_fin);
  });

  // Al cargar la página, obtenemos las fechas por defecto (mes actual) y las mostramos en el input
  $(document).ready(function () {
    let { fechaInicio, fechaFin } = obtenerFechasPorDefecto();
    $("#daterange").val(fechaInicio + " - " + fechaFin);

    // Llama a las funciones con las fechas por defecto
    informacion_dashboard(fechaInicio, fechaFin);
    actualizarCardsPedidos(fechaInicio, fechaFin);
  });

  // Función que obtiene la información para el dashboard (productos, ciudades, etc.)
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

        // Actualizar datos de texto
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
        $("#total_productos").text(response.productos_vendidos|| 0);
        $("#topProductsList").text(response.top_productos|| 0);
        $("#topCategoriesList").text(response.top_categorias|| 0);
        $("#topCitiesList").text(response.top_categorias|| 0);

        $("#ticket_promedio").text(
          parseFloat(response.ticket_promedio).toFixed(2)
        );
        $("#flete_promedio").text(
          parseFloat(response.flete_promedio).toFixed(2)
        );
        $("#devolucion_promedio").text(
          parseFloat(response.devolucion_promedio).toFixed(2)
        );

        // Tabla de facturas
        $("#facturas-body").empty();
        response.facturas.forEach(function (factura) {
          let row = `<tr>
                <td>${factura.numero_factura}</td>
                <td>${factura.fecha_factura}</td>
                <td>${factura.monto_factura}</td>
            </tr>`;
          $("#facturas-body").append(row);
        });

        // --- COMENTAMOS el gráfico de líneas basado en ventas diarias ---
        /*
        if (salesChart) {
          salesChart.destroy();
        }
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
        let ctx = document.getElementById("salesChart").getContext("2d");
        salesChart = new Chart(ctx, {
          type: "line",
          data: {...},
          options: {...}
        });
        */

        // ***** Gráfico de Estados (distributionChart) *****
        if (distributionChart) {
          distributionChart.destroy();
        }
        let estadosLabels = response.estados.map(
          (estado) => estado.estado_descripcion
        );
        let estadosData = response.estados.map((estado) => estado.cantidad);
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
          var cantidad_despachos = parseFloat(product.cantidad_despachos);
          if (cantidad_despachos > 0) {
            total_despachos += cantidad_despachos;
          }
        });
        document.getElementById("products-container").innerHTML = "";
        response.productos_despachos.forEach((product) => {
          var cantidad_despachos = parseFloat(product.cantidad_despachos);
          var nombre_producto = product.nombre_producto;
          var imagen = product.image_path;
          var porcentaje = calcularPorcentaje(cantidad_despachos, total_despachos);
          updateProductProgressBar(
            cantidad_despachos,
            nombre_producto,
            imagen,
            porcentaje
          );
        });

        // ***** Sección de productos entregados *****
        let total_entregados = 0;
        response.productos_despachos_entregados.forEach((product) => {
          var cantidad_despachos = parseFloat(product.cantidad_despachos);
          if (cantidad_despachos > 0) {
            total_entregados += cantidad_despachos;
          }
        });
        document.getElementById("productsEntregados-container").innerHTML = "";
        response.productos_despachos_entregados.forEach((product) => {
          var cantidad_despachos = parseFloat(product.cantidad_despachos);
          var nombre_producto = product.nombre_producto;
          var imagen = product.image_path;
          var porcentaje = calcularPorcentaje(
            cantidad_despachos,
            total_entregados
          );
          updateProductProgressBar_entrega(
            cantidad_despachos,
            nombre_producto,
            imagen,
            porcentaje
          );
        });

        // ***** Sección de productos devueltos *****
        let total_devolucion = 0;
        response.productos_despachos_devueltos.forEach((product) => {
          var cantidad_despachos = parseFloat(product.cantidad_despachos);
          if (cantidad_despachos > 0) {
            total_devolucion += cantidad_despachos;
          }
        });
        document.getElementById("productsDevolucion-container").innerHTML = "";
        response.productos_despachos_devueltos.forEach((product) => {
          var cantidad_despachos = parseFloat(product.cantidad_despachos);
          var nombre_producto = product.nombre_producto;
          var imagen = product.image_path;
          var porcentaje = calcularPorcentaje(
            cantidad_despachos,
            total_devolucion
          );
          updateProductProgressBar_devolucion(
            cantidad_despachos,
            nombre_producto,
            imagen,
            porcentaje
          );
        });

        // ***** Sección de ciudades despachos *****
        let total_despachos_ciudad = 0;
        response.ciudad_pedidos.forEach((city) => {
          var cantidad_pedidos = parseFloat(city.cantidad_pedidos);
          if (cantidad_pedidos > 0) {
            total_despachos_ciudad += cantidad_pedidos;
          }
        });
        document.getElementById("ciudades-container").innerHTML = "";
        response.ciudad_pedidos.forEach((city) => {
          var cantidad_pedidos = parseFloat(city.cantidad_pedidos);
          var ciudad = city.ciudad;
          var porcentaje = calcularPorcentaje(
            cantidad_pedidos,
            total_despachos_ciudad
          );
          updateCityProgressBar(cantidad_pedidos, ciudad, porcentaje);
        });

        // ***** Sección de ciudades entregadas *****
        let total_despachos_ciudad_entregado = 0;
        response.ciudades_entregas.forEach((city) => {
          var cantidad_entregas = parseFloat(city.cantidad_entregas);
          if (cantidad_entregas > 0) {
            total_despachos_ciudad_entregado += cantidad_entregas;
          }
        });
        document.getElementById("ciudadesEntregadas-container").innerHTML = "";
        response.ciudades_entregas.forEach((city) => {
          var cantidad_entregas = parseFloat(city.cantidad_entregas);
          var ciudad = city.ciudad;
          var porcentaje = calcularPorcentaje(
            cantidad_entregas,
            total_despachos_ciudad_entregado
          );
          updateCityProgressBar_entregar(cantidad_entregas, ciudad, porcentaje);
        });

        // ***** Sección de ciudades con devoluciones *****
        let total_despachos_ciudad_devolucion = 0;
        response.ciudades_devoluciones.forEach((city) => {
          var cantidad_entregas = parseFloat(city.cantidad_entregas);
          if (cantidad_entregas > 0) {
            total_despachos_ciudad_devolucion += cantidad_entregas;
          }
        });
        document.getElementById("ciudadesDevolucion-container").innerHTML = "";
        response.ciudades_devoluciones.forEach((city) => {
          var cantidad_entregas = parseFloat(city.cantidad_entregas);
          var ciudad = city.ciudad;
          var porcentaje = calcularPorcentaje(
            cantidad_entregas,
            total_despachos_ciudad_devolucion
          );
          updateCityProgressBar_devolucion(
            cantidad_entregas,
            ciudad,
            porcentaje
          );
        });
      },
      error: function (jqXHR, textStatus, errorThrown) {
        alert(errorThrown);
      },
    });
  }

  // Función para calcular porcentaje
  function calcularPorcentaje(cantidad, total) {
    if (!total) return 0;
    return (cantidad / total) * 100;
  }

  // Función para actualizar la barra de progreso en "Productos por cantidad"
  function updateProductProgressBar(cantidad_despacho, nombre_producto, imagen, porcentaje) {
    const productElement = document.createElement("div");
    productElement.classList.add("product");
    productElement.innerHTML = `
      <div class="product-info">
        <img src="${SERVERURL}${imagen}" alt="${nombre_producto}" class="product-icon">
        <span>${nombre_producto}</span>
        <span class="quantity">${cantidad_despacho} (${porcentaje.toFixed(2)}%)</span>
      </div>
      <div class="progress-bar">
        <div class="progress" style="width: ${porcentaje}%;"></div>
      </div>
    `;
    document.getElementById("products-container").appendChild(productElement);
  }

  // Función para actualizar la barra de progreso en "Ciudades con más despachos"
  function updateCityProgressBar(cantidad_pedidos, ciudad, porcentaje) {
    const productElement = document.createElement("div");
    productElement.classList.add("product");
    productElement.innerHTML = `
      <div class="product-info">
        <span>${ciudad}</span>
        <span class="quantity">${cantidad_pedidos} (${porcentaje.toFixed(2)}%)</span>
      </div>
      <div class="progress-bar">
        <div class="progress" style="width: ${porcentaje}%;"></div>
      </div>
    `;
    document.getElementById("ciudades-container").appendChild(productElement);
  }

  // Productos Entrega
  function updateProductProgressBar_entrega(cantidad_despacho, nombre_producto, imagen, porcentaje) {
    const productElement = document.createElement("div");
    productElement.classList.add("product");
    productElement.innerHTML = `
      <div class="product-info">
        <img src="${SERVERURL}${imagen}" alt="${nombre_producto}" class="product-icon">
        <span>${nombre_producto}</span>
        <span class="quantity">${cantidad_despacho} (${porcentaje.toFixed(2)}%)</span>
      </div>
      <div class="progress-bar">
        <div class="progress" style="width: ${porcentaje}%;"></div>
      </div>
    `;
    document.getElementById("productsEntregados-container").appendChild(productElement);
  }

  // Ciudades Entrega
  function updateCityProgressBar_entregar(cantidad_entregas, ciudad, porcentaje) {
    const productElement = document.createElement("div");
    productElement.classList.add("product");
    productElement.innerHTML = `
      <div class="product-info">
        <span>${ciudad}</span>
        <span class="quantity">${cantidad_entregas} (${porcentaje.toFixed(2)}%)</span>
      </div>
      <div class="progress-bar">
        <div class="progress" style="width: ${porcentaje}%;"></div>
      </div>
    `;
    document.getElementById("ciudadesEntregadas-container").appendChild(productElement);
  }

  // Productos Devolución
  function updateProductProgressBar_devolucion(cantidad_despacho, nombre_producto, imagen, porcentaje) {
    const productElement = document.createElement("div");
    productElement.classList.add("product");
    productElement.innerHTML = `
      <div class="product-info">
        <img src="${SERVERURL}${imagen}" alt="${nombre_producto}" class="product-icon">
        <span>${nombre_producto}</span>
        <span class="quantity">${cantidad_despacho} (${porcentaje.toFixed(2)}%)</span>
      </div>
      <div class="progress-bar">
        <div class="progress" style="width: ${porcentaje}%;"></div>
      </div>
    `;
    document.getElementById("productsDevolucion-container").appendChild(productElement);
  }

  // Ciudades Devolución
  function updateCityProgressBar_devolucion(cantidad_entregas, ciudad, porcentaje) {
    const productElement = document.createElement("div");
    productElement.classList.add("product");
    productElement.innerHTML = `
      <div class="product-info">
        <span>${ciudad}</span>
        <span class="quantity">${cantidad_entregas} (${porcentaje.toFixed(2)}%)</span>
      </div>
      <div class="progress-bar">
        <div class="progress" style="width: ${porcentaje}%;"></div>
      </div>
    `;
    document.getElementById("ciudadesDevolucion-container").appendChild(productElement);
  }

  // ***** AQUÍ VIENE LA FUNCIÓN que crea el gráfico con datos de las CARDS *****
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
      success: function (response) {
        let data = typeof response === "object" ? response : JSON.parse(response);

        // Actualiza Cards
        $("#total_ventas").text(
          data.valor_pedidos
            ? `$${parseFloat(data.valor_pedidos).toLocaleString("en-US", {
                minimumFractionDigits: 2,
              })}`
            : "$0.00"
        );
        $("#total_pedidos").text(data.total_pedidos || 0);
        $("#total_guias").text(data.total_guias || 0);
        $("#total_entregado").text(data.total_guias_entregadas || 0);
        $("#num_confirmaciones").text(
          data.porcentaje_confirmacion
            ? `${parseFloat(data.porcentaje_confirmacion).toFixed(2)}%`
            : "0%"
        );
        $("#id_confirmacion").text("de " + (data.mensaje || ""));

        // Datos para el gráfico de LÍNEAS basado en las Cards
        const chartLabels = [
          "Valor Pedidos",
          "Guías Generadas",
          "Guías Entregadas",
          "Total Pedidos",
        ];
        const chartData = [
          parseFloat(data.valor_pedidos) || 0,
          parseFloat(data.total_guias) || 0,
          parseFloat(data.total_guias_entregadas) || 0,
          parseFloat(data.total_pedidos) || 0,
        ];

        // Destruimos el gráfico anterior si existe
        if (performanceChart) {
          performanceChart.destroy();
        }

        // Creamos un nuevo gráfico de LÍNEAS en #salesChart
        let ctxPerf = document.getElementById("salesChart").getContext("2d");
        performanceChart = new Chart(ctxPerf, {
          type: "line",
          data: {
            labels: chartLabels,
            datasets: [
              {
                label: "Rendimiento",
                data: chartData,
                borderColor: "rgba(75, 192, 192, 1)",
                backgroundColor: "rgba(75, 192, 192, 0.2)",
                fill: false,
                tension: 0.1,
                borderWidth: 1,
              },
            ],
          },
          options: {
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
