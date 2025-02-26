let dataTable;
let dataTableIsInitialized = false;

const dataTableOptions = {
  columnDefs: [
    { className: "centered", targets: [1, 2, 3, 4, 5, 6, 7, 8, 9] },
    { orderable: false, targets: 0 }, //ocultar para columna 0 el ordenar
  ],
  order: [[1, "desc"]], // Ordenar por la primera columna (fecha) en orden descendente
  pageLength: 25,
  lengthMenu: [25, 50, 100, 200],
  destroy: true,
  responsive: true,
  dom: '<"d-flex w-full justify-content-between"lBf><t><"d-flex justify-content-between"ip>',
  buttons: [
    {
      extend: "excelHtml5",
      text: 'Excel <i class="fa-solid fa-file-excel"></i>',
      title: "Panel de Control: Usuarios",
      titleAttr: "Exportar a Excel",
      exportOptions: {
        columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15],
      },
      filename: "guias" + "_" + getFecha(),
      footer: true,
      className: "btn-excel",
    },
    {
      extend: "csvHtml5",
      text: 'CSV <i class="fa-solid fa-file-csv"></i>',
      title: "Panel de Control: guias",
      titleAttr: "Exportar a CSV",
      exportOptions: {
        columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15],
      },
      filename: "guias" + "_" + getFecha(),
      footer: true,
      className: "btn-csv",
    },
  ],
  language: {
    lengthMenu: "Mostrar _MENU_ registros por página",
    zeroRecords: "Ningún usuario encontrado",
    info: "Mostrando de _START_ a _END_ de un total de _TOTAL_ registros",
    infoEmpty: "Ningún usuario encontrado",
    infoFiltered: "(filtrados desde _MAX_ registros totales)",
    search: "Buscar:",
    loadingRecords: "Cargando...",
    paginate: {
      first: "Primero",
      last: "Último",
      next: "Siguiente",
      previous: "Anterior",
    },
  },
};

// Devuelve la fecha actual en formato YYYY-MM-DD
function getFecha() {
  let fecha = new Date();
  let mes = fecha.getMonth() + 1;
  let dia = fecha.getDate();
  let anio = fecha.getFullYear();
  let fechaHoy = anio + "-" + mes + "-" + dia;
  return fechaHoy;
}

// Muestra spinner de carga
function showTableLoader() {
  $("#tableLoader")
    .html(
      '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Cargando...</span></div>'
    )
    .css("display", "flex");
}

// Oculta spinner de carga
function hideTableLoader() {
  $("#tableLoader").css("display", "none");
}

/**
 * Inicializa el DataTable
 */
const initDataTable = async () => {
  showTableLoader();
  try {
    if (dataTableIsInitialized) {
      dataTable.destroy();
    }

    await listGuias();

    dataTable = $("#datatable_guias").DataTable(dataTableOptions);
    dataTableIsInitialized = true;

    // Handle select all checkbox
    document.getElementById("selectAll").addEventListener("change", function () {
      const checkboxes = document.querySelectorAll(".selectCheckbox");
      checkboxes.forEach((checkbox) => (checkbox.checked = this.checked));
    });
  } catch (error) {
    console.error("Error al cargar la tabla", error);
  } finally {
    hideTableLoader();
  }
};

// Nueva función para recargar el DataTable manteniendo la paginación y el pageLength
const reloadDataTable = async () => {
  const currentPage = dataTable.page();
  const currentLength = dataTable.page.len();
  dataTable.destroy();
  await listGuias();
  dataTable = $("#datatable_guias").DataTable(dataTableOptions);
  dataTable.page.len(currentLength).draw();
  dataTable.page(currentPage).draw(false);
  dataTableIsInitialized = true;

  document.getElementById("selectAll").addEventListener("change", function () {
    const checkboxes = document.querySelectorAll(".selectCheckbox");
    checkboxes.forEach((checkbox) => (checkbox.checked = this.checked));
  });
};

/**
 * Llama al backend para obtener las guías y pinta la tabla
 */
const listGuias = async () => {
  try {
    const formData = new FormData();
    formData.append("fecha_inicio", fecha_inicio);
    formData.append("fecha_fin", fecha_fin);
    formData.append("estado", $("#estado_q").val());
    formData.append("drogshipin", $("#tienda_q").val());
    formData.append("transportadora", $("#transporte").val());
    formData.append("impreso", $("#impresion").val());
    formData.append("despachos", $("#despachos").val());

    const response = await fetch(
      `${SERVERURL}pedidos/obtener_guiasAdministrador`,
      {
        method: "POST",
        body: formData,
      }
    );

    let content = ``;
    let impresiones = "";
    let novedad = "";

    // Respuesta del fetch
    const result = await response.json();
    // El array real está en result.data
    const guias = result.data;   // aquí sí es un array
    const totals = result.totals; // objeto con los totales

    guias.forEach((guia, index) => {
      const transporte = parseInt(guia.id_transporte);
      const estadoNum  = parseInt(guia.estado_guia_sistema);

      let transporte_content = "";
      let ruta_descarga = "";
      let ruta_traking = "";
      let funcion_anular = "";
      let select_speed = "";
      let drogshipin = "";
      let despachado = "";

      // 1) Determinar “content” de la columna transporte / enlaces / funciones anular / tracking
      if (transporte === 1) {
        // SPEED (o MerkaLogistic si usan la misma API)
        // detecta si la guía es “MKL” o “SPDMX”
        if (guia.numero_guia.includes("MKL")) {
          transporte_content =
            '<span style="background-color: red; color: white; padding: 5px; border-radius: 0.3rem;">MerkaLogistic</span>';
        } else if (guia.numero_guia.includes("SPDMX")) {
          transporte_content =
            '<span style="background-color: red; color: white; padding: 5px; border-radius: 0.3rem;">SPEED</span>';
        } else {
          transporte_content =
            '<span style="background-color: orange; color: white; padding: 5px; border-radius: 0.3rem;">Speed sin prefijo</span>';
        }
        ruta_descarga  = `<a class="w-100" href="https://guias.imporsuitpro.com/Speed/descargar/${guia.numero_guia}" target="_blank">${guia.numero_guia}</a>`;
        ruta_traking   = ``; // Ajusta si hay un enlace de tracking
        funcion_anular = `anular_guiaSpeed('${guia.numero_guia}')`;
      } 
      else if (transporte === 2) {
        // FIGGO / GO
        transporte_content =
          '<span style="background-color: #0fcab7; color: white; padding: 5px; border-radius: 0.3rem;">GO</span>';
        ruta_descarga  = `<a class="w-100" href="https://app.imporsuit.mx/figgo/descargar/${guia.numero_guia}" target="_blank">${guia.numero_guia}</a>`;
        ruta_traking   = `https://ec.gintracom.site/web/site/tracking`; 
        funcion_anular = `anular_guiaGO('${guia.numero_guia}')`;
      } 
      else if (transporte === 3) {
        // TIUI
        transporte_content =
          '<span style="background-color: #3fa7a9; color: white; padding: 5px; border-radius: 0.3rem;">TIUI</span>';
        ruta_descarga  = `<a class="w-100 text-nowrap" href="https://api.tiui.app/api/guia/guiaReport/${guia.numero_guia}" target="_blank">${guia.numero_guia}</a>`;
        ruta_traking   = `https://tiuiamigo.tiui.mx/rastreo/${guia.numero_guia}`;
        funcion_anular = `anular_guiaGO('${guia.numero_guia}')`; // O la que corresponda a tu API
      } 
      else if (transporte === 4) {
        // UPS
        transporte_content =
          '<span style="background-color: #feb501; color: white; padding: 5px; border-radius: 0.3rem;">UPS</span>';
        ruta_descarga  = `<a class="w-100 text-nowrap" href="https://app.imporsuit.mx/ups/getLabel/${guia.numero_guia}" target="_blank">${guia.numero_guia}</a>`;
        ruta_traking   = `https://www.ups.com/track?loc=es_MX&requester=ST/`;
        // Para UPS, si no hay anulación implementada, podrías dejar vacía la función
        funcion_anular = ``;
      }
      else {
        // Desconocido
        transporte_content =
          '<span style="background-color: #E3BC1C; color: white; padding: 5px; border-radius: 0.3rem;">Sin transporte</span>';
      }

      // 2) Validar el estado de la guía según su transportadora
      let { span_estado, estado_guia } = { span_estado: "", estado_guia: "" };
      if (transporte === 1) {
        // SPEED
        ({ span_estado, estado_guia } = validar_estadoSpeed(estadoNum));
      } else if (transporte === 2) {
        // FIGGO
        ({ span_estado, estado_guia } = validar_estadoFiggo(estadoNum));
      } else if (transporte === 3) {
        // TIUI
        ({ span_estado, estado_guia } = validar_estadoTiui(estadoNum));
      } else if (transporte === 4) {
        // UPS
        ({ span_estado, estado_guia } = validar_estadoUps(estadoNum));
      } else {
        // desconocido
        span_estado = "badge-secondary";
        estado_guia = "Estado sin identificar";
      }

      // 3) Otras lógicas de tu fila
      if (guia.drogshipin == 0) {
        drogshipin = "Local";
      } else if (guia.drogshipin == 1) {
        drogshipin = "Drogshipin";
      }

      // ciudad
      let ciudad = "Ciudad no especificada";
      if (guia.municipio) {
        let ciudadArray = guia.municipio.split("/");
        ciudad = ciudadArray[0];
      }

      // Novedad
      novedad = "";
      // Ejemplo de tu condición anterior
      if (estadoNum === 14 && transporte === 1) {
        // Speed con estado = 14
        novedad = `<button id="downloadExcel" class="btn btn_novedades" onclick="gestionar_novedad('${guia.numero_guia}')">Gestionar novedad</button>`;
      }
      // etc... (agrega otras condiciones si tu lógica lo requiere)

      // Plataforma
      let plataforma = procesarPlataforma(guia.plataforma);

      // Impreso
      if (guia.impreso == 0) {
        impresiones = `<box-icon name='printer' color="red"></box-icon>`;
      } else {
        impresiones = `<box-icon name='printer' color="#28E418"></box-icon>`;
      }

      // Despachado
      let iconoDespacho = "";
      if (guia.estado_factura == 2) {
        iconoDespacho = `<i class='bx bx-check' style="color:#28E418; font-size: 30px;"></i>`;
      } else if (guia.estado_factura == 1) {
        iconoDespacho = `<i class='bx bx-x' style="color:red; font-size: 30px;"></i>`;
      } else if (guia.estado_factura == 3) {
        iconoDespacho = `<i class='bx bxs-truck' style="color:red; font-size: 30px;"></i>`;
      }

      content += `
        <tr>
          <td><input type="checkbox" class="selectCheckbox" data-id="${guia.id_factura}"></td>
          <td>
            <div>${ruta_descarga}</div>
          </td>
          <td>
            <div>${guia.numero_factura}</div>
            <div>${drogshipin}</div>
          </td>
          <td>
            <div>
              <button onclick="ver_detalle_cot('${guia.id_factura}')" 
                      class="btn btn-sm btn-outline-primary">Ver detalle</button>
            </div>
            <div>${guia.fecha_factura}</div>
          </td>
          <td>
            <div><strong>${guia.nombre}</strong></div>
            <div>${guia.direccion} y ${guia.entre_calles}</div>
            <div>telf: ${guia.telefono}</div>
          </td>
          <td>${generarDetallesProductos(guia.detalles_productos)}</td>
          <td>
            ${guia.estadoa} - ${ciudad}
            <div>Colonia: ${guia.colonia}</div>
            <div>Codigo Postal: ${guia.codigo_postal}</div>
          </td>
          <td>Zona ${guia.region}</td>
          <td>
            <div>
              <strong>Tienda:</strong> <span class="link-like">${guia.tienda}</span>
            </div>
            <div>
              <strong>Proveedor:</strong> <span class="link-like">${guia.nombre_proveedor}</span>
            </div>
          </td>
          <td>${transporte_content}</td>
          <td>
            <div style="text-align: center;">
              <span class="w-100 text-nowrap ${span_estado}">${estado_guia}</span>
              <div style="position: relative; display: inline-block;">
                <a href="https://wa.me/${formatPhoneNumber(
                  guia.telefono
                )}" target="_blank" style="font-size: 45px; vertical-align: middle; margin-left: 10px;">
                  <i class='bx bxl-whatsapp-square' style="color: green;"></i>
                </a>
              </div>
              <div style="text-align: -webkit-center;">
                ${select_speed}
              </div>
              <div>
                ${novedad}
              </div>
            </div>
          </td>
          <td>${impresiones}</td>
          <td>${guia.costo_producto}</td>
          <td>${guia.costo_flete}</td>
          <td>${guia.monto_factura}</td>
          <td>${iconoDespacho}</td>
          <td>
            <div class="dropdown">
              <button class="btn btn-sm btn-secondary dropdown-toggle" 
                      type="button" id="dropdownMenuButton" 
                      data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa-solid fa-gear"></i>
              </button>
              <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <li>
                  <span class="dropdown-item" style="cursor: pointer;" 
                        onclick="${funcion_anular}">
                    Anular
                  </span>
                </li>
                <li>
                  <span class="dropdown-item" style="cursor: pointer;">Información</span>
                </li>
                <li>
                  <span class="dropdown-item" style="cursor: pointer;" 
                        onclick='transito(${guia.id_factura})'>
                    Tránsito
                  </span>
                </li>
                <li>
                  <span class="dropdown-item" style="cursor: pointer;" 
                        onclick='entregar(${guia.id_factura})'>
                    Entregado
                  </span>
                </li>
                <li>
                  <span class="dropdown-item" style="cursor: pointer;" 
                        onclick='devolucion(${guia.id_factura})'>
                    Devolución
                  </span>
                </li>
              </ul>
            </div>
          </td>
        </tr>
      `;
    });

    // Insertamos todo el contenido armado
    document.getElementById("tableBody_guias").innerHTML = content;

    // Actualiza las cards con los totales
    const elementos = {
      "num_pedidos" : "total",
      "num_generadas" : "generada",
      "num_transito" : "en_transito",
      "num_entregadas" : "entregada",
      "num_novedad" : "novedad",
      "num_devolucion" : "devolucion",
      "num_zona_entrega": "zona_entrega"
    };
    Object.entries(elementos).forEach(([id, key]) => {
      let elemento = document.getElementById(id);
      if (elemento) {
        elemento.innerText = totals[key];
      }
    });

    // Ajustar barras de progreso
    if (totals.total > 0) {
      let porcentajeGeneradas    = Math.round((totals.generada    / totals.total) * 100);
      let porcentajeTransito     = Math.round((totals.en_transito / totals.total) * 100);
      let porcentajeEntregaZona  = Math.round((totals.zona_entrega / totals.total) * 100);
      let porcentajeEntrega      = Math.round((totals.entregada   / totals.total) * 100);
      let porcentajeNovedad      = Math.round((totals.novedad     / totals.total) * 100);

      let porcentajeDevolucion = 100 - (
        porcentajeGeneradas + 
        porcentajeTransito + 
        porcentajeEntregaZona + 
        porcentajeEntrega + 
        porcentajeNovedad
      );

      document.getElementById("progress_generadas").style.width    = porcentajeGeneradas   + "%";
      document.getElementById("percent_generadas").innerText       = porcentajeGeneradas   + "%";

      document.getElementById("progress_transito").style.width     = porcentajeTransito    + "%";
      document.getElementById("percent_transito").innerText        = porcentajeTransito    + "%";

      document.getElementById("progress_zonaentrega").style.width  = porcentajeEntregaZona + "%";
      document.getElementById("percent_zonaentrega").innerText     = porcentajeEntregaZona + "%";

      document.getElementById("progress_entrega").style.width      = porcentajeEntrega     + "%";
      document.getElementById("percent_entrega").innerText         = porcentajeEntrega     + "%";

      document.getElementById("progress_novedad").style.width      = porcentajeNovedad     + "%";
      document.getElementById("percent_novedad").innerText         = porcentajeNovedad     + "%";

      document.getElementById("progress_devolucion").style.width   = porcentajeDevolucion  + "%";
      document.getElementById("percent_devolucion").innerText      = porcentajeDevolucion  + "%";
    } else {
      // Si total es 0, dejamos todo en 0%
      let progressBars = [
        "progress_generadas",
        "progress_transito",
        "progress_zonaentrega",
        "progress_entrega",
        "progress_novedad",
        "progress_devolucion",
      ];
      let percentTexts = [
        "percent_generadas",
        "percent_transito",
        "percent_zonaentrega",
        "percent_entrega",
        "percent_novedad",
        "percent_devolucion",
      ];
      progressBars.forEach((id) => {
        document.getElementById(id).style.width = "0%";
      });
      percentTexts.forEach((id) => {
        document.getElementById(id).innerText = "0%";
      });
    }
  } catch (ex) {
    alert(ex);
  }
};

// Genera el HTML para los productos de cada pedido (decodifica JSON)
function generarDetallesProductos(detallesProductosJSON) {
  let detallesProductos = JSON.parse(detallesProductosJSON);
  let contenido = "";
  detallesProductos.forEach((detalle) => {
    contenido += `
      <div><strong>SKU:</strong> ${detalle.sku}</div>
      <div><strong>Producto:</strong> ${detalle.cantidad} x ${detalle.nombre_producto}</div>
    `;
  });
  return contenido;
}

// Cambiar estado a "en tránsito"
function transito(id_cabecera) {
  $.ajax({
    type: "POST",
    url: SERVERURL + "pedidos/transito/" + id_cabecera,
    dataType: "json",
    success: function (response) {
      if (response.status == 500) {
        toastr.error("ERROR AL REALIZAR EL CAMBIO", "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });
      } else if (response.status == 200) {
        toastr.success("CAMBIO REALIZADO CORRECTAMENTE", "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });
      }
    },
    error: function (xhr, status, error) {
      console.error("Error en la solicitud AJAX:", error);
      alert("Hubo un problema al realizar el cambio");
    },
  });
}

// Cambiar estado a "entregado"
function entregar(id_cabecera) {
  $.ajax({
    type: "POST",
    url: SERVERURL + "pedidos/entregar/" + id_cabecera,
    dataType: "json",
    success: function (response) {
      if (response.status == 500) {
        toastr.error("ERROR AL REALIZAR EL CAMBIO", "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });
      } else if (response.status == 200) {
        toastr.success("CAMBIO REALIZADO CORRECTAMENTE", "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });
      }
    },
    error: function (xhr, status, error) {
      console.error("Error en la solicitud AJAX:", error);
      alert("Hubo un problema al realizar el cambio");
    },
  });
}

// Cambiar estado a "devolución"
function devolucion(id_cabecera) {
  $.ajax({
    type: "POST",
    url: SERVERURL + "pedidos/devolucion/" + id_cabecera,
    dataType: "json",
    success: function (response) {
      if (response.status == 500) {
        toastr.error("ERROR AL REALIZAR EL CAMBIO", "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });
      } else if (response.status == 200) {
        toastr.success("CAMBIO REALIZADO CORRECTAMENTE", "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });
      }
    },
    error: function (xhr, status, error) {
      console.error("Error en la solicitud AJAX:", error);
      alert("Hubo un problema al realizar el cambio");
    },
  });
}

/*******************************************
 * FUNCIONES DE VALIDACIÓN DE ESTADOS
 * (coinciden con lo que el modelo usa)
 *******************************************/

/**
 * SPEED: id_transporte = 1
 * Generada: [1,2]
 * En tránsito: [5,11,12]
 * Entregada: 7
 * Novedad: 14
 * Devolución: 9
 */
function validar_estadoSpeed(estado) {
  let span_estado = "";
  let estado_guia = "";

  if (estado === 1 || estado === 2) {
    span_estado = "badge_purple";
    estado_guia = "Generada";
  } else if ([5, 11, 12].includes(estado)) {
    span_estado = "badge_warning";
    estado_guia = "En tránsito";
  } else if (estado === 7) {
    span_estado = "badge_green";
    estado_guia = "Entregada";
  } else if (estado === 14) {
    span_estado = "badge_warning";
    estado_guia = "Novedad";
  } else if (estado === 9) {
    span_estado = "badge_danger";
    estado_guia = "Devolución";
  } else {
    span_estado = "badge_secondary";
    estado_guia = "Desconocido (" + estado + ")";
  }

  return { span_estado, estado_guia };
}

/**
 * FIGGO/GO: id_transporte = 2
 * Generada: [100,102,103]
 * En tránsito: [300..317] excepto 307
 * Zona de entrega: estado=307
 * Entregada: [400..403]
 * Novedad: [320..351]
 * Devolución: [500..502]
 */
function validar_estadoFiggo(estado) {
  let span_estado = "";
  let estado_guia = "";

  if ([100, 102, 103].includes(estado)) {
    span_estado = "badge_purple";
    estado_guia = "Generada";
  } else if (estado >= 300 && estado <= 317 && estado !== 307) {
    span_estado = "badge_warning";
    estado_guia = "En tránsito";
  } else if (estado === 307) {
    span_estado = "badge_warning";
    estado_guia = "Zona de entrega";
  } else if (estado >= 400 && estado <= 403) {
    span_estado = "badge_green";
    estado_guia = "Entregada";
  } else if (estado >= 320 && estado <= 351) {
    span_estado = "badge_warning";
    estado_guia = "Novedad";
  } else if (estado >= 500 && estado <= 502) {
    span_estado = "badge_danger";
    estado_guia = "Devolución";
  } else {
    span_estado = "badge_secondary";
    estado_guia = "Desconocido (" + estado + ")";
  }

  return { span_estado, estado_guia };
}

/**
 * TIUI: id_transporte = 3
 * Generada: [1,2,3]
 * En tránsito: [4]
 * Zona de entrega: [5]
 * Entregada: [7]
 * Novedad: [6]
 * Devolución: [8,9,13]
 */
function validar_estadoTiui(estado) {
  let span_estado = "";
  let estado_guia = "";

  if ([1, 2, 3].includes(estado)) {
    span_estado = "badge_purple";
    estado_guia = "Generada";
  } else if (estado === 4) {
    span_estado = "badge_warning";
    estado_guia = "En tránsito";
  } else if (estado === 5) {
    span_estado = "badge_warning";
    estado_guia = "Zona de entrega";
  } else if (estado === 7) {
    span_estado = "badge_green";
    estado_guia = "Entregada";
  } else if (estado === 6) {
    span_estado = "badge_warning";
    estado_guia = "Novedad";
  } else if ([8, 9, 13].includes(estado)) {
    span_estado = "badge_danger";
    estado_guia = "Devolución";
  } else {
    span_estado = "badge_secondary";
    estado_guia = "Desconocido (" + estado + ")";
  }

  return { span_estado, estado_guia };
}

/**
 * UPS: id_transporte = 4
 * Generada: [2]
 * En tránsito: [3]
 * Entregada: [7]
 * Novedad: [14]
 * Devolución: [9]
 */
function validar_estadoUps(estado) {
  let span_estado = "";
  let estado_guia = "";

  if (estado === 2) {
    span_estado = "badge_purple";
    estado_guia = "Generada";
  } else if (estado === 3) {
    span_estado = "badge_warning";
    estado_guia = "En tránsito";
  } else if (estado === 7) {
    span_estado = "badge_green";
    estado_guia = "Entregada";
  } else if (estado === 14) {
    span_estado = "badge_warning";
    estado_guia = "Novedad";
  } else if (estado === 9) {
    span_estado = "badge_danger";
    estado_guia = "Devolución";
  } else {
    span_estado = "badge-secondary";
    estado_guia = "Desconocido (" + estado + ")";
  }

  return { span_estado, estado_guia };
}

// ------------------- Eventos para "Select" (ej. cambiar estado Speed) -------------------
document.addEventListener("change", async (event) => {
  if (event.target && event.target.classList.contains("select-estado-speed")) {
    const numeroGuia  = event.target.getAttribute("data-numero-guia");
    const nuevoEstado = event.target.value;
    console.log(`Cambiando estado para la guía ${numeroGuia} a ${nuevoEstado}`);
    const formData = new FormData();
    formData.append("estado", nuevoEstado);

    if (nuevoEstado == 9) {
      $("#tipo_speed").val("recibir").change();
    }

    try {
      const response = await fetch(
        `https://guias.imporsuitpro.com/Speed/estado/${numeroGuia}`,
        {
          method: "POST",
          body: formData,
        }
      );
      const result = await response.json();
      if (result.status == 200) {
        toastr.success("ESTADO ACTUALIZADO CORRECTAMENTE", "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });

        $("#gestionar_novedadSpeedModal").modal("show");
        reloadDataTable();
      }
    } catch (error) {
      console.error("Error al conectar con la API", error);
      alert("Error al conectar con la API");
    }
  }
});

// ------------------- Funciones de anulación por transportadora -------------------
function anular_guiaSpeed(numero_guia) {
  $.ajax({
    type: "GET",
    url: "https://guias.imporsuitpro.com/Speed/anular/" + numero_guia,
    dataType: "json",
    success: function (response) {
      if (response.status == 500) {
        toastr.error("LA GUIA NO SE ANULÓ CORRECTAMENTE", "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });
      } else if (response.status == 200) {
        toastr.success("GUIA ANULADA CORRECTAMENTE", "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });
        reloadDataTable();
      }
    },
    error: function (xhr, status, error) {
      alert("Hubo un problema al anular la guía en Speed");
    },
  });
}

function anular_guiaGO(numero_guia) {
  $.ajax({
    type: "POST",
    url: SERVERURL + "Guias/anularFiggo/" + numero_guia,
    dataType: "json",
    success: function (response) {
      if (response.status == 500) {
        toastr.error("LA IMAGEN NO SE AGREGÓ CORRECTAMENTE", "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });
      } else if (response.status == 200) {
        toastr.success("GUIA ANULADA CORRECTAMENTE", "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });
        initDataTable();
      }
    },
    error: function (xhr, status, error) {
      console.error("Error en la solicitud AJAX:", error);
      alert("Hubo un problema al anular la guía de Gintracom");
    },
  });
}

/**
 * Muestra datos de la tienda (llamando un modal) - si lo usas
 */
function abrirModal_infoTienda(tienda) {
  let formData = new FormData();
  formData.append("tienda", tienda);

  $.ajax({
    url: SERVERURL + "pedidos/datosPlataformas",
    type: "POST",
    data: formData,
    processData: false,
    contentType: false,
    success: function (response) {
      response = JSON.parse(response);
      $("#nombreTienda").val(response[0].nombre_tienda);
      $("#telefonoTienda").val(response[0].whatsapp);
      $("#correoTienda").val(response[0].email);
      $("#enlaceTienda").val(response[0].url_imporsuit);

      $("#infoTiendaModal").modal("show");
    },
    error: function (jqXHR, textStatus, errorThrown) {
      alert(errorThrown);
    },
  });
}

/**
 * Ver detalle de la factura
 */
function ver_detalle_cot(id_factura) {
  let formData = new FormData();
  formData.append("id_factura", id_factura);

  $.ajax({
    url: SERVERURL + "Pedidos/obtenerDetalle",
    type: "POST",
    data: formData,
    processData: false,
    contentType: false,
    success: function (response) {
      response = JSON.parse(response);

      $("#ordePara_detalleFac").text(response[0].nombre);
      $("#direccion_detalleFac").text(
        `${response[0].direccion}, ${response[0].entre_calles}, ${response[0].colonia}`
      );
      $("#telefono_detalleFac").text(response[0].telefono);
      $("#numOrden_detalleFac").text(response[0].numero_factura);
      $("#fecha_detalleFac").text(response[0].fecha_factura);
      $("#companiaEnvio_detalleFac").text(response[0].transporte);
      if (response[0].cod == 1) {
        $("#tipoEnvio_detalleFac").text("Con Recaudo");
      } else {
        $("#tipoEnvio_detalleFac").text("Sin Recaudo");
      }

      if (response.length > 0) {
        let tableBody = $("#tabla_body");
        tableBody.empty();

        let total = 0;
        response.forEach(function (detalle) {
          let subtotal = detalle.cantidad * detalle.precio_venta;
          let descuentoTotal = subtotal * (detalle.desc_venta / 100);
          let precioFinal = subtotal - descuentoTotal;
          total += precioFinal;

          let rowHtml = `
            <tr>
              <td>${detalle.nombre_producto}</td>
              <td>${detalle.cantidad}</td>
              <td>${detalle.precio_venta}</td>
              <td>${detalle.desc_venta}</td>
              <td>${precioFinal.toFixed(2)}</td>
            </tr>
          `;
          tableBody.append(rowHtml);
        });

        let totalRowHtml = `
          <tr class="custom-total-row">
            <td colspan="3" class="text-right">Total</td>
            <td>${total.toFixed(2)}</td>
          </tr>
        `;
        tableBody.append(totalRowHtml);
      }

      $("#detalles_facturaModal").modal("show");
    },
    error: function (error) {
      console.error("Error al obtener la lista de bodegas:", error);
    },
  });
}

/**
 * Extrae la subcadena de la URL de la tienda (p.e., "GO", "TIUI", etc.)
 */
function procesarPlataforma(url) {
  if (!url) {
    return "La tienda ya no existe";
  }
  let sinProtocolo = url.replace("https://", "");
  let primerPunto = sinProtocolo.indexOf(".");
  let baseNombre = sinProtocolo.substring(0, primerPunto);
  let resultado = baseNombre.toUpperCase();
  return resultado;
}

// Botón para imprimir manifiestos de los items seleccionados
document.getElementById("imprimir_guias").addEventListener("click", () => {
  const selectedGuias = [];
  const checkboxes = document.querySelectorAll(".selectCheckbox:checked");
  checkboxes.forEach((checkbox) => {
    selectedGuias.push(checkbox.getAttribute("data-id"));
  });

  const selectedGuiasJson = JSON.stringify(selectedGuias);
  console.log(selectedGuiasJson);

  let formData = new FormData();
  formData.append("facturas", selectedGuiasJson);

  $.ajax({
    type: "POST",
    url: SERVERURL + "/Manifiestos/generar",
    data: formData,
    processData: false,
    contentType: false,
    dataType: "json",
    beforeSend: function () {
      Swal.fire({
        title: "Cargando",
        text: "Creando lista de productos",
        allowOutsideClick: false,
        showConfirmButton: false,
        willOpen: () => {
          Swal.showLoading();
        },
      });
    },
    success: function (response) {
      if (response.status == 200) {
        const link = document.createElement("a");
        link.href = response.download;
        link.download = "";
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);

        // Cerrar el Swal después de hacer clic
        reloadDataTable();
        Swal.close();
      }
    },
    error: function (xhr, status, error) {
      console.error("Error en la solicitud AJAX:", error);
      alert("Hubo un problema al imprimir manifiesto");
    },
  });
});

// Al cargar la ventana, se inicializa la tabla
window.addEventListener("load", async () => {
  await initDataTable();
  document.getElementById("btnAplicarFiltros").addEventListener("click", () => {
    initDataTable();
  });
});

/**
 * Ajusta número de teléfono a +52
 */
function formatPhoneNumber(number) {
  number = number.replace(/[^\d+]/g, "");
  if (/^\+52/.test(number)) {
    return number; 
  } else if (/^52/.test(number)) {
    return "+" + number;
  } else {
    if (number.startsWith("0")) {
      number = number.substring(1);
    }
    number = "+52" + number;
  }
  return number;
}

// --------------------------------------
// Funciones genéricas de anulación extra
// --------------------------------------
function anular_guiaLaar(numero_guia) {
  let formData = new FormData();
  formData.append("guia", numero_guia);

  $.ajax({
    url: SERVERURL + "guias/anularGuia",
    type: "POST",
    data: formData,
    processData: false,
    contentType: false,
    success: function (response) {
      response = JSON.parse(response);
      if (response.status == 500) {
        toastr.error("LA GUIA NO SE ANULÓ CORRECTAMENTE", "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });
      } else if (response.status == 200) {
        toastr.success("GUIA ANULADA CORRECTAMENTE", "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });
        reloadDataTable();
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      alert(errorThrown);
    },
  });
}

function anular_guiaServi(numero_guia) {
  // Si en verdad usas Speed en lugar de Servientrega, podrías borrar esto.
  $.ajax({
    type: "GET",
    url: "https://guias.imporsuitpro.com/Servientrega/Anular/" + numero_guia,
    dataType: "json",
    success: function (response) {},
    error: function (xhr, status, error) {
      // alert("Hubo un problema al anular la guia de Servientrega");
    },
  });

  $.ajax({
    type: "GET",
    url: SERVERURL + "Guias/anularServi_temporal/" + numero_guia,
    dataType: "json",
    success: function (response) {
      if (response.status == 500) {
        toastr.error("LA GUIA NO SE ANULÓ CORRECTAMENTE", "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });
      } else if (response.status == 200) {
        toastr.success("GUIA ANULADA CORRECTAMENTE", "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });
        reloadDataTable();
      }
    },
    error: function (xhr, status, error) {
      alert("Hubo un problema al anular la guia de Servientrega");
    },
  });
}

function anular_guiaGintracom(numero_guia) {
  $.ajax({
    type: "POST",
    url: "https://guias.imporsuitpro.com/Gintracom/anular/" + numero_guia,
    dataType: "json",
    success: function (response) {
      if (response == "1") {
        toastr.success("GUIA ANULADA CORRECTAMENTE", "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });
        $("#imagen_categoriaModal").modal("hide");
        reloadDataTable();
      } else {
        toastr.error("LA GUIA NO SE ANULÓ CORRECTAMENTE", "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });
      }
    },
    error: function (xhr, status, error) {
      console.error("Error en la solicitud AJAX:", error);
      alert("Hubo un problema al anular gintracom");
    },
  });
}

// --------------------
// Modal Novedades
// --------------------
function gestionar_novedad(guia_novedad) {
  let transportadora = "";
  $.ajax({
    url: SERVERURL + "novedades/datos/" + guia_novedad,
    type: "GET",
    dataType: "json",
    success: function (response) {
      if (
        response.novedad[0].guia_novedad.includes("IMP") ||
        response.novedad[0].guia_novedad.includes("MKP")
      ) {
        transportadora = "LAAR";
        $("#seccion_laar").show();
        $("#seccion_servientrega").hide();
        $("#seccion_gintracom").hide();
      } else if (response.novedad[0].guia_novedad.includes("I")) {
        transportadora = "GINTRACOM";
        $("#seccion_laar").hide();
        $("#seccion_servientrega").hide();
        $("#seccion_gintracom").show();
      } else if (response.novedad[0].guia_novedad.includes("SPD")) {
        transportadora = "SPEED";
        $("#seccion_laar").hide();
        $("#seccion_servientrega").hide();
        $("#seccion_gintracom").hide();
      } else {
        transportadora = "SERVIENTREGA";
        $("#seccion_laar").hide();
        $("#seccion_servientrega").show();
        $("#seccion_gintracom").hide();
      }

      $("#id_gestionarNov").text(response.novedad[0].id_novedad);
      $("#cliente_gestionarNov").text(response.novedad[0].cliente_novedad);
      $("#estado_gestionarNov").text(response.novedad[0].estado_novedad);
      $("#transportadora_gestionarNov").text(transportadora);
      $("#novedad_gestionarNov").text(response.novedad[0].novedad);
      $("#tracking_gestionarNov").attr("href", response.novedad[0].tracking);

      $("#id_novedad").val(response.novedad[0].id_novedad);
      $("#numero_guia").val(response.novedad[0].guia_novedad);

      $("#gestionar_novedadModal").modal("show");
    },
    error: function (error) {
      console.error("Error al obtener la novedad:", error);
    },
  });
}

// Manejo de algunos selects y envío de novedades
$(document).ready(function () {
  $("#tipo_gintracom").change(function () {
    var tipo = $("#tipo_gintracom").val();
    if (tipo == "recaudo") {
      $("#valor_recaudoGintra").show();
      $("#fecha_gintra").show();
    } else if (tipo == "rechazar") {
      $("#valor_recaudoGintra").hide();
      $("#fecha_gintra").hide();
    } else {
      $("#valor_recaudoGintra").hide();
      $("#fecha_gintra").show();
    }
  });
});

function enviar_gintraNovedad() {
  var button = document.getElementById("boton_gintra");
  button.disabled = true;

  var guia = $("#numero_guia").val();
  var observacion = $("#Solucion_novedad").val();
  var id_novedad = $("#id_novedad").val();
  var tipo = $("#tipo_gintracom").val();
  var recaudo = "";
  var fecha = "";

  if (tipo == "recaudo") {
    recaudo = $("#Valor_recaudar").val();
  }
  if (tipo !== "rechazar") {
    fecha = $("#datepicker").val();
  }

  let formData = new FormData();
  formData.append("guia", guia);
  formData.append("observacion", observacion);
  formData.append("id_novedad", id_novedad);
  formData.append("tipo", tipo);
  formData.append("recaudo", recaudo);
  formData.append("fecha", fecha);

  $.ajax({
    url: SERVERURL + "novedades/solventarNovedadGintracom",
    type: "POST",
    data: formData,
    processData: false,
    contentType: false,
    success: function (response) {
      response = JSON.parse(response);
      if (response.error === true) {
        toastr.error(response.message, "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });
        button.disabled = false;
      } else if (response.error === false) {
        toastr.success(response.message, "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });
        $("#gestionar_novedadModal").modal("hide");
        button.disabled = false;
        initDataTableNovedades(); // o reloadDataTable(), según tu caso
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      alert(errorThrown);
      button.disabled = false;
    },
  });
}

function enviar_serviNovedad() {
  var button = document.getElementById("boton_servi");
  button.disabled = true;

  var guia = $("#numero_guia").val();
  var observacion = $("#observacion_nov").val();
  var id_novedad = $("#id_novedad").val();

  let formData = new FormData();
  formData.append("guia", guia);
  formData.append("observacion", observacion);
  formData.append("id_novedad", id_novedad);

  $.ajax({
    url: SERVERURL + "novedades/solventarNovedadServientrega",
    type: "POST",
    data: formData,
    processData: false,
    contentType: false,
    success: function (response) {
      response = JSON.parse(response);
      if (response.status == 500) {
        toastr.error("Novedad no enviada correctamente", "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });
        button.disabled = false;
      } else if (response.status == 200) {
        toastr.success("Novedad enviada correctamente", "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });
        $("#gestionar_novedadModal").modal("hide");
        button.disabled = false;
        initDataTableNovedades(); 
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      alert(errorThrown);
      button.disabled = false;
    },
  });
}

function enviar_laarNovedad() {
  var button = document.getElementById("boton_laar");
  button.disabled = true;

  var guia = $("#numero_guia").val();
  var id_novedad = $("#id_novedad").val();
  var ciudad = $("#ciudad_novedadesServi").val();
  var nombre_novedadesServi = $("#nombre_novedadesServi").val();
  var callePrincipal_novedadesServi = $("#callePrincipal_novedadesServi").val();
  var calleSecundaria_novedadesServi = $("#calleSecundaria_novedadesServi").val();
  var numeracion_novedadesServi = $("#numeracion_novedadesServi").val();
  var referencia_novedadesServi = $("#referencia_novedadesServi").val();
  var telefono_novedadesServi = $("#telefono_novedadesServi").val();
  var celular_novedadesServi = $("#celular_novedadesServi").val();
  var observacion_novedadesServi = $("#observacion_novedadesServi").val();
  var observacionA = $("#observacionA").val();

  let formData = new FormData();
  formData.append("guia", guia);
  formData.append("observacionA", observacionA);
  formData.append("id_novedad", id_novedad);
  formData.append("ciudad", ciudad);
  formData.append("nombre", nombre_novedadesServi);
  formData.append("callePrincipal", callePrincipal_novedadesServi);
  formData.append("calleSecundaria", calleSecundaria_novedadesServi);
  formData.append("numeracion", numeracion_novedadesServi);
  formData.append("referencia", referencia_novedadesServi);
  formData.append("telefono", telefono_novedadesServi);
  formData.append("celular", celular_novedadesServi);
  formData.append("observacion", observacion_novedadesServi);

  $.ajax({
    url: SERVERURL + "novedades/solventarNovedadLaar",
    type: "POST",
    data: formData,
    processData: false,
    contentType: false,
    success: function (response) {
      response = JSON.parse(response);
      if (response.status == 500) {
        toastr.error("Novedad no enviada correctamente", "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });
        button.disabled = false;
      } else if (response.status == 200) {
        toastr.success("Novedad enviada correctamente", "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });
        $("#gestionar_novedadModal").modal("hide");
        button.disabled = false;
        initDataTableNovedades();
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      alert(errorThrown);
      button.disabled = false;
    },
  });
}
