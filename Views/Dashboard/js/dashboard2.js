let fecha_inicio = "";
let fecha_fin = "";
let performanceChart;

// Función para obtener las fechas por defecto (primer y último día del mes actual)
function obtenerFechasPorDefecto() {
  let now = new Date();
  let firstDay = new Date(now.getFullYear(), now.getMonth(), 1);
  let lastDay = new Date(now.getFullYear(), now.getMonth() + 1, 0);
  const pad = n => (n < 10 ? "0" + n : n);
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
    // Asigna el valor al input con el rango en formato "YYYY-MM-DD - YYYY-MM-DD"
    $("#daterange").val(fechaInicio + " - " + fechaFin);
    
    // Llama a las funciones con las fechas por defecto
    informacion_dashboard(fechaInicio, fechaFin);
    actualizarCardsPedidos(fechaInicio, fechaFin);
  });
  
  // Variables globales para almacenar las referencias a los gráficos
  let salesChart;
  let distributionChart;

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
        $("#devoluciones").text(
          response.devoluciones
            ? `$${parseFloat(response.devoluciones).toLocaleString('en-US', { minimumFractionDigits: 2 })}`
            : '$0.00'
        );
        $("#total_fletes").text(
          response.envios
            ? `$${parseFloat(response.envios).toLocaleString('en-US', { minimumFractionDigits: 2 })}`
            : '$0.00'
        );
        $("#total_recaudo").text(
          response.ganancias
            ? `$${parseFloat(response.ganancias).toLocaleString('en-US', { minimumFractionDigits: 2 })}`
            : '$0.00'
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

        // Verificar si `ventas_diarias` es un array antes de usar `.map()`
        if (!Array.isArray(response.ventas_diarias)) {
          console.error("Error: ventas_diarias no es un array", response.ventas_diarias);
          return;
        }

        // Preparar los datos para el gráfico de líneas
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
          type: "line", // Gráfico de líneas
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
        // Primero, extraer los datos de los estados desde la respuesta
        let estadosLabels = response.estados.map(
          (estado) => estado.estado_descripcion
        );
        let estadosData = response.estados.map(
          (estado) => estado.cantidad
        );

        // Define una paleta de colores para asignar a cada estado
        const paletteBackground = [
          'rgba(255, 99, 132, 0.8)',   // rojo
          'rgba(54, 162, 235, 0.8)',     // azul
          'rgba(255, 206, 86, 0.8)',     // amarillo
          'rgba(75, 192, 192, 0.8)',     // verde
          'rgba(153, 102, 255, 0.8)',    // morado
          'rgba(255, 159, 64, 0.8)'      // naranja
        ];
        // Asigna los colores a cada estado en función de su posición en la lista
        let estadosBackgroundColors = estadosLabels.map((label, index) => {
          return paletteBackground[index % paletteBackground.length];
        });
 
        // (Opcional) Si necesitas colores de borde, puedes definirlos así:
        const estadoBorderColors = {
          Anulado: "rgba(255, 0, 0, 1)",
          "En Transito": "rgba(255, 255, 0, 1)",
          Entregado: "rgba(144, 238, 144, 1)",
          Generado: "rgba(0, 0, 255, 1)",
          Otro: "rgba(128, 128, 128, 1)",
          "Por Recolectar": "rgba(128, 0, 128, 1)",
        };
        let estadosBorderColorsDynamic = estadosLabels.map((label) => {
          return estadoBorderColors[label] || 'rgba(0, 0, 0, 1)';
        });

        // Crear el gráfico de barras horizontales
        if (distributionChart) {
          distributionChart.destroy();
        }
        const ctxDistribution = document.getElementById("distributionChart").getContext("2d");
        distributionChart = new Chart(ctxDistribution, {
          type: "bar",
          data: {
            labels: estadosLabels, // Ejemplo: ["Anulado", "En Transito", ...]
            datasets: [{
              label: "Cantidad de guías",
              data: estadosData,    // Ejemplo: [10, 25, 40, ...]
              backgroundColor: estadosBackgroundColors,
              borderWidth: 0
            }]
          },
          options: {
            indexAxis: "y", // Barras horizontales
            scales: {
              x: {
                beginAtZero: true,
                ticks: {
                  precision: 0
                }
              }
            },
            plugins: {
              legend: {
                display: false
              },
              tooltip: {
                callbacks: {
                  label: function(context) {
                    return context.label + ": " + context.raw;
                  }
                }
              }
            }
          }
        });
        
        /* seccion de productos despachados */
        let total_despachos = 0;

        // Recorremos todos los productos y sumamos aquellos que tengan cantidad_despacho > 0
        response.productos_despachos.forEach((product) => {
          var cantidad_despachos = parseFloat(product.cantidad_despachos);

          if (cantidad_despachos > 0) {
            total_despachos += cantidad_despachos;
          }
        });

        // Limpiar el contenedor de productos antes de cargar los nuevos
        document.getElementById("products-container").innerHTML = "";

        // Supongamos que el API retorna un array de objetos con los datos
        response.productos_despachos.forEach((product) => {
          var cantidad_despachos = parseFloat(product.cantidad_despachos);
          var nombre_producto = product.nombre_producto;
          var imagen = product.image_path;
          var porcentaje = calcularPorcentaje(
            parseFloat(product.cantidad_despachos),
            total_despachos
          );

          // Llamamos a la función para actualizar el DOM
          updateProductProgressBar(
            cantidad_despachos,
            nombre_producto,
            imagen,
            porcentaje
          );
        });
        /* Fin seccion de productos despachados */

        /* seccion de productos entregados */
        let total_entregados = 0;

        // Recorremos todos los productos y sumamos aquellos que tengan cantidad_despacho > 0
        response.productos_despachos_entregados.forEach((product) => {
          var cantidad_despachos = parseFloat(product.cantidad_despachos);

          if (cantidad_despachos > 0) {
            total_entregados += cantidad_despachos;
          }
        });

        // Limpiar el contenedor de productos antes de cargar los nuevos
        document.getElementById("productsEntregados-container").innerHTML = "";

        // Supongamos que el API retorna un array de objetos con los datos
        response.productos_despachos_entregados.forEach((product) => {
          var cantidad_despachos = parseFloat(product.cantidad_despachos);
          var nombre_producto = product.nombre_producto;
          var imagen = product.image_path;
          var porcentaje = calcularPorcentaje(
            parseFloat(product.cantidad_despachos),
            total_entregados
          );

          // Llamamos a la función para actualizar el DOM
          updateProductProgressBar_entrega(
            cantidad_despachos,
            nombre_producto,
            imagen,
            porcentaje
          );
        });
        /* Fin seccion de productos entregados */

        /* seccion de productos devolucion */
        let total_devolucion = 0;

        // Recorremos todos los productos y sumamos aquellos que tengan cantidad_despacho > 0
        response.productos_despachos_devueltos.forEach((product) => {
          var cantidad_despachos = parseFloat(product.cantidad_despachos);

          if (cantidad_despachos > 0) {
            total_devolucion += cantidad_despachos;
          }
        });

        // Limpiar el contenedor de productos antes de cargar los nuevos
        document.getElementById("productsDevolucion-container").innerHTML = "";

        // Supongamos que el API retorna un array de objetos con los datos
        response.productos_despachos_devueltos.forEach((product) => {
          var cantidad_despachos = parseFloat(product.cantidad_despachos);
          var nombre_producto = product.nombre_producto;
          var imagen = product.image_path;
          var porcentaje = calcularPorcentaje(
            parseFloat(product.cantidad_despachos),
            total_devolucion
          );

          // Llamamos a la función para actualizar el DOM
          updateProductProgressBar_devolucion(
            cantidad_despachos,
            nombre_producto,
            imagen,
            porcentaje
          );
        });
        /* Fin seccion de productos devolucion */

        /* seccion de ciudad despachados */
        let total_despachos_ciudad = 0;

        // Recorremos todos los ciudad y sumamos aquellos que tengan cantidad_despacho > 0
        response.ciudad_pedidos.forEach((city) => {
          var cantidad_pedidos = parseFloat(city.cantidad_pedidos);

          if (cantidad_pedidos > 0) {
            total_despachos_ciudad += cantidad_pedidos;
          }
        });

        // Limpiar el contenedor de ciudad antes de cargar los nuevos
        document.getElementById("ciudades-container").innerHTML = "";

        // Supongamos que el API retorna un array de objetos con los datos
        response.ciudad_pedidos.forEach((city) => {
          var cantidad_pedidos = parseFloat(city.cantidad_pedidos);
          var ciudad = city.ciudad;
          var porcentaje = calcularPorcentaje(
            parseFloat(city.cantidad_pedidos),
            total_despachos_ciudad
          );

          // Llamamos a la función para actualizar el DOM
          updateCityProgressBar(cantidad_pedidos, ciudad, porcentaje);
        });
        /* Fin seccion de ciudad despachados */
        /* seccion de ciudad entregado */
        let total_despachos_ciudad_entregado = 0;

        // Recorremos todos los ciudad y sumamos aquellos que tengan cantidad_despacho > 0
        response.ciudades_entregas.forEach((city) => {
          var cantidad_entregas = parseFloat(city.cantidad_entregas);

          if (cantidad_entregas > 0) {
            total_despachos_ciudad_entregado += cantidad_entregas;
          }
        });

        // Limpiar el contenedor de ciudad antes de cargar los nuevos
        document.getElementById("ciudadesEntregadas-container").innerHTML = "";

        // Supongamos que el API retorna un array de objetos con los datos
        response.ciudades_entregas.forEach((city) => {
          var cantidad_entregas = parseFloat(city.cantidad_entregas);
          var ciudad = city.ciudad;
          var porcentaje = calcularPorcentaje(
            parseFloat(city.cantidad_entregas),
            total_despachos_ciudad_entregado
          );

          // Llamamos a la función para actualizar el DOM
          updateCityProgressBar_entregar(cantidad_entregas, ciudad, porcentaje);
        });
        /* Fin seccion de ciudad entregado */
        /* seccion de ciudad devolucion */
        let total_despachos_ciudad_devolucion = 0;

        // Recorremos todos los ciudad y sumamos aquellos que tengan cantidad_despacho > 0
        response.ciudades_devoluciones.forEach((city) => {
          var cantidad_entregas = parseFloat(city.cantidad_entregas);

          if (cantidad_entregas > 0) {
            total_despachos_ciudad_devolucion += cantidad_entregas;
          }
        });

        // Limpiar el contenedor de ciudad antes de cargar los nuevos
        document.getElementById("ciudadesDevolucion-container").innerHTML = "";

        // Supongamos que el API retorna un array de objetos con los datos
        response.ciudades_devoluciones.forEach((city) => {
          var cantidad_entregas = parseFloat(city.cantidad_entregas);
          var ciudad = city.ciudad;
          var porcentaje = calcularPorcentaje(
            parseFloat(city.cantidad_entregas),
            total_despachos_ciudad_devolucion
          );

          // Llamamos a la función para actualizar el DOM
          updateCityProgressBar_devolucion(
            cantidad_entregas,
            ciudad,
            porcentaje
          );
        });
        /* Fin seccion de ciudad devolucion */
      },
      error: function (jqXHR, textStatus, errorThrown) {
        alert(errorThrown);
      },
    });
  }

  $(document).ready(function () {
    informacion_dashboard("", "");
    actualizarCardsPedidos("","");
  });

  // Función para calcular el porcentaje (opcional según el formato de tus datos)
  function calcularPorcentaje(cantidad, total) {
    return (cantidad / total) * 100;
  }

  /* funcion productos por cantidad */
  // Función para actualizar la barra de progreso en "Productos por cantidad"
  function updateProductProgressBar(
    cantidad_despacho,
    nombre_producto,
    imagen,
    porcentaje
  ) {
    // Creamos el contenedor del producto
    const productElement = document.createElement("div");
    productElement.classList.add("product");

    // Creamos la información del producto
    productElement.innerHTML = `
        <div class="product-info">
            <img src="${SERVERURL}${imagen}" alt="${nombre_producto}" class="product-icon">
            <span>${nombre_producto}</span>
            <span class="quantity">${cantidad_despacho} (${porcentaje.toFixed(
      2
    )}%)</span>
        </div>
        <div class="progress-bar">
            <div class="progress" style="width: ${porcentaje}%;"></div>
        </div>
    `;

    // Añadimos el producto al contenedor principal
    document.getElementById("products-container").appendChild(productElement);
  }

  /* funcion ciudades con mas despachos */
  // Función para actualizar la barra de progreso en "Ciudades con más despachos"
  function updateCityProgressBar(cantidad_pedidos, ciudad, porcentaje) {
    // Creamos el contenedor del producto
    const productElement = document.createElement("div");
    productElement.classList.add("product");

    // Creamos la información del producto
    productElement.innerHTML = `
        <div class="product-info">
            <span>${ciudad}</span>
            <span class="quantity">${cantidad_pedidos} (${porcentaje.toFixed(
      2
    )}%)</span>
        </div>
        <div class="progress-bar">
            <div class="progress" style="width: ${porcentaje}%;"></div>
        </div>
    `;

    // Añadimos el producto al contenedor principal
    document.getElementById("ciudades-container").appendChild(productElement);
  }

  /* funcion productos por entrega */
  // Función para actualizar la barra de progreso en "Productos por entrega"
  function updateProductProgressBar_entrega(
    cantidad_despacho,
    nombre_producto,
    imagen,
    porcentaje
  ) {
    // Creamos el contenedor del producto
    const productElement = document.createElement("div");
    productElement.classList.add("product");

    // Creamos la información del producto
    productElement.innerHTML = `
        <div class="product-info">
            <img src="${SERVERURL}${imagen}" alt="${nombre_producto}" class="product-icon">
            <span>${nombre_producto}</span>
            <span class="quantity">${cantidad_despacho} (${porcentaje.toFixed(
      2
    )}%)</span>
        </div>
        <div class="progress-bar">
            <div class="progress" style="width: ${porcentaje}%;"></div>
        </div>
    `;

    // Añadimos el producto al contenedor principal
    document
      .getElementById("productsEntregados-container")
      .appendChild(productElement);
  }

  /* funcion ciudades con mas entrega */
  // Función para actualizar la barra de progreso en "Ciudades con más entrega"
  function updateCityProgressBar_entregar(
    cantidad_entregas,
    ciudad,
    porcentaje
  ) {
    // Creamos el contenedor del producto
    const productElement = document.createElement("div");
    productElement.classList.add("product");

    // Creamos la información del producto
    productElement.innerHTML = `
        <div class="product-info">
            <span>${ciudad}</span>
            <span class="quantity">${cantidad_entregas} (${porcentaje.toFixed(
      2
    )}%)</span>
        </div>
        <div class="progress-bar">
            <div class="progress" style="width: ${porcentaje}%;"></div>
        </div>
    `;

    // Añadimos el producto al contenedor principal
    document
      .getElementById("ciudadesEntregadas-container")
      .appendChild(productElement);
  }

  /* funcion productos por devolucion */
  // Función para actualizar la barra de progreso en "Productos por devolucion"
  function updateProductProgressBar_devolucion(
    cantidad_despacho,
    nombre_producto,
    imagen,
    porcentaje
  ) {
    // Creamos el contenedor del producto
    const productElement = document.createElement("div");
    productElement.classList.add("product");

    // Creamos la información del producto
    productElement.innerHTML = `
        <div class="product-info">
            <img src="${SERVERURL}${imagen}" alt="${nombre_producto}" class="product-icon">
            <span>${nombre_producto}</span>
            <span class="quantity">${cantidad_despacho} (${porcentaje.toFixed(
      2
    )}%)</span>
        </div>
        <div class="progress-bar">
            <div class="progress" style="width: ${porcentaje}%;"></div>
        </div>
    `;

    // Añadimos el producto al contenedor principal
    document
      .getElementById("productsDevolucion-container")
      .appendChild(productElement);
  }

  /* funcion ciudades con mas devolucion */
  // Función para actualizar la barra de progreso en "Ciudades con más devolucion"
  function updateCityProgressBar_devolucion(
    cantidad_entregas,
    ciudad,
    porcentaje
  ) {
    // Creamos el contenedor del producto
    const productElement = document.createElement("div");
    productElement.classList.add("product");

    // Creamos la información del producto
    productElement.innerHTML = `
        <div class="product-info">
            <span>${ciudad}</span>
            <span class="quantity">${cantidad_entregas} (${porcentaje.toFixed(
      2
    )}%)</span>
        </div>
        <div class="progress-bar">
            <div class="progress" style="width: ${porcentaje}%;"></div>
        </div>
    `;

    // Añadimos el producto al contenedor principal
    document
      .getElementById("ciudadesDevolucion-container")
      .appendChild(productElement);
  }

  function actualizarCardsPedidos(fecha_inicio, fecha_fin) {
    let formData = new FormData();
    // Usamos los mismos nombres de campo que el endpoint espera
    formData.append("fecha_inicio", fecha_inicio);
    formData.append("fecha_fin", fecha_fin);
    
    $.ajax({
      url: SERVERURL + "Pedidos/cargar_cards_pedidos_mes",
      type: "POST",
      data: formData,
      processData: false,
      contentType: false,
      dataType: "json", // Si tu backend ya devuelve JSON, esto ayuda
      success: function(response) {
        // En caso de que no se parseé automáticamente, lo hacemos:
        let data = typeof response === "object" ? response : JSON.parse(response);
  
        // Actualizamos los elementos correspondientes con una lógica similar al "if"
        $("#total_ventas").text(
          data.valor_pedidos
            ? `$${parseFloat(data.valor_pedidos).toLocaleString('en-US', { minimumFractionDigits: 2 })}`
            : '$0.00'
        );
        $("#total_pedidos").text(data.total_pedidos || 0);
        $("#total_guias").text(data.total_guias || 0);
        $("#total_entregado").text(data.total_guias_entregadas || 0);
        $("#num_confirmaciones").text(
          data.porcentaje_confirmacion
            ? `${parseFloat(data.porcentaje_confirmacion).toFixed(2)}%`
            : '0%'
        );
        $("#id_confirmacion").text("de " + (data.mensaje || ""));

        //Creamos un nuevo gráfico de barras para representar los valores
        const chartLabels = [
          "Valor Pedidos",
          "Guías Generadas",
          "Guías Entregadas",
          "Total Pedidos"
        ]

        const chartData = [
          parseFloat(data.valor_pedidos) || 0,
          parseFloat(data.total_guias) || 0,
          parseFloat(data.total_guias_entregadas) || 0,
          parseFloat(data.total_pedidos) || 0
        ];

        //destuir si existe alguno, para redibujarlo
        if(performanceChart){
          performanceChart.destroy();
        }
      // Seleccionamos el <canvas> donde dibujar (reutilizamos salesChart)
      let ctxPerf = document.getElementById("salesChart").getContext("2d");
      performanceChart = new Chart(ctxPerf, {
        type: "bar",
        data: {
          labels: chartLabels,
          datasets: [{
            label: "Rendimiento (según Cards)",
            data: chartData,
            backgroundColor: [
              "rgba(75, 192, 192, 0.7)",
              "rgba(255, 206, 86, 0.7)",
              "rgba(54, 162, 235, 0.7)",
              "rgba(255, 99, 132, 0.7)"
            ]
          }]
        },
        options: {
          scales: {
            y: {
              beginAtZero: true,
              ticks: {
                // para evitar decimales raros
                precision: 0
              }
            }
          }
        }
      });
      },
      error: function(jqXHR, textStatus, errorThrown) {
        console.error("Error al actualizar las cards:", errorThrown);
      }
    });
  }  
});
