let dataTable;
let dataTableIsInitialized = false;
let fecha_inicio = "";
let fecha_fin = "";

// Configurar rango inicial
let hoy = moment();
let haceUnaSemana = moment().subtract(6, 'days');
fecha_inicio = haceUnaSemana.format('YYYY-MM-DD') + ' 00:00:00';
fecha_fin = hoy.format('YYYY-MM-DD') + ' 23:59:59';

// Configurar daterangepicker al cargar la página
$(function() {
  $('#daterange').daterangepicker({
    opens: 'right',
    startDate: haceUnaSemana,
    endDate: hoy,
    locale: {
      format: 'YYYY-MM-DD',               
      separator: ' - ',
      applyLabel: 'Aplicar',
      cancelLabel: 'Cancelar',
      fromLabel: 'Desde',
      toLabel: 'Hasta',
      customRangeLabel: 'Personalizado',
      weekLabel: 'S',
      daysOfWeek: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
      monthNames: [
        'Enero','Febrero','Marzo','Abril','Mayo','Junio',
        'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'
      ],
      firstDay: 1
    },
    autoUpdateInput: tr  });

  // NO recargamos la tabla directamente al aplicar el rango, lo haremos con el botón "Aplicar Filtros".
  // $('#daterange').on('apply.daterangepicker', function(ev, picker) {
  //   fecha_inicio = picker.startDate.format('YYYY-MM-DD') + ' 00:00:00';
  //   fecha_fin = picker.endDate.format('YYYY-MM-DD') + ' 23:59:59';
  //   initDataTable(); 
  // });
  // Seteamos en el input la fecha inicial y final
  $('#daterange').val(
    haceUnaSemana.format('YYYY-MM-DD') + ' - ' + hoy.format('YYYY-MM-DD')
  );
});

// NUEVO: Agregamos este botón que usaremos para aplicar los filtros manualmente.
// IMPORTANTE: Asegúrate de tener un botón con id="btnAplicarFiltros" en tu HTML.

// Configuración del DataTable
const dataTableOptions = {
  columnDefs: [
    { className: "centered", targets: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11] },
    { orderable: false, targets: 0 }, // Evitar ordenar por la columna de checkboxes
  ],
  order: [[2, "desc"]], // Ordenar por la primera columna (fecha) en orden descendente
  pageLength: 25,
  lengthMenu: [25, 50, 100, 200],
  destroy: true,
  responsive: true,
  dom: '<"d-flex justify-content-between"l><t><"d-flex justify-content-between"ip>',
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

/**
 * Devuelve una cadena con la fecha actual en formato YYYY-MM-DD
 */
function getFecha() {
  let fecha = new Date();
  let mes = fecha.getMonth() + 1;
  let dia = fecha.getDate();
  let anio = fecha.getFullYear();
  let fechaHoy = anio + "-" + mes + "-" + dia;
  return fechaHoy;
}

//Cargando
// function showTableLoader() {
//   // Inserta siempre el HTML del spinner y luego muestra el contenedor
//   $("#tableLoader").html(
//     '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Cargando...</span></div>'
//   ).css("display", "flex");
// }
//Cargando loader por detras del modal filtros
function showTableLoader() {
  const modalOpen = document.querySelector('#modalFiltros.show');
  if (!modalOpen) {
    $("#tableLoader").html(
      '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Cargando...</span></div>'
    ).css("display", "flex");
  }
}

function hideTableLoader() {
  $("#tableLoader").css("display", "none");
}


/**
 * Inicializa o recarga el DataTable
 */
const initDataTable = async () => {
  showTableLoader(); 
  try {
    if (dataTableIsInitialized) {
      dataTable.destroy();
    }

    // Realiza la solicitud para obtener los datos
    await listGuias();

    // Inicializa DataTables con los nuevos datos
    dataTable = $("#datatable_guias").DataTable(dataTableOptions);
    dataTableIsInitialized = true;

    // Maneja el checkbox de "Seleccionar todos"
    document.getElementById("selectAll").addEventListener("change", function () {
      const checkboxes = document.querySelectorAll(".selectCheckbox");
      checkboxes.forEach((checkbox) => (checkbox.checked = this.checked));
    });
  } catch (error) {
    console.error("Error al cargar la tabla:", error);
  } finally {
    hideTableLoader(); // Oculta el loader cuando ya se completó la carga (o si ocurre un error)
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

  // Volver a asignar el evento "select all" al checkbox principal
  document.getElementById("selectAll").addEventListener("change", function () {
    const checkboxes = document.querySelectorAll(".selectCheckbox");
    checkboxes.forEach((checkbox) => (checkbox.checked = this.checked));
  });
};
//continuar 
/**
 * Obtiene las guías del servidor con los filtros actuales y rellena la tabla
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

    let buscar_guia = $("#buscar_guia").val().trim();
    formData.append("buscar_guia", buscar_guia); //agregamos al request

    const response = await fetch(`${SERVERURL}pedidos/obtener_guiasAdministrador`, {
      method: "POST",
      body: formData,
    });

    // Ahora el JSON debe tener "data" y "totals"
    const result = await response.json();
    const guias = result.data;
    const totals = result.totals;

    let content = ``;
    let impresiones = "";
    let novedad = "";

    guias.forEach((guia, index) => {
      let transporte = guia.id_transporte;
      let transporte_content = "";
      let ruta_descarga = "";
      let ruta_traking = "";
      let funcion_anular = "";
      let select_speed = "";
      let drogshipin = "";
      let despachado = "";

      if (transporte == 2) {
        transporte_content = `<span style="background-color: #28C839; color: white; padding: 5px; border-radius: 0.3rem;">SERVIENTREGA</span>`;
        ruta_descarga = `<a class="w-100" href="https://guias.imporsuitpro.com/Servientrega/guia/${guia.numero_guia}" target="_blank">${guia.numero_guia}</a>`;
        ruta_traking = `https://www.servientrega.com.ec/Tracking/?guia=${guia.numero_guia}&tipo=GUIA`;
        funcion_anular = `anular_guiaServi('${guia.numero_guia}')`;
        estado = validar_estadoServi(guia.estado_guia_sistema);
      } else if (transporte == 1) {
        transporte_content = `<span style="background-color: #E3BC1C; color: white; padding: 5px; border-radius: 0.3rem;">LAAR</span>`;
        ruta_descarga = `<a class="w-100" href="https://api.laarcourier.com:9727/guias/pdfs/DescargarV2?guia=${guia.numero_guia}" target="_blank">${guia.numero_guia}</a>`;
        ruta_traking = `https://fenixoper.laarcourier.com/Tracking/Guiacompleta.aspx?guia=${guia.numero_guia}`;
        funcion_anular = `anular_guiaLaar('${guia.numero_guia}')`;
        estado = validar_estadoLaar(guia.estado_guia_sistema);
      } else if (transporte == 4) {
        if (guia.numero_guia.includes("MKL")) {
          transporte_content = `<span style="background-color: red; color: white; padding: 5px; border-radius: 0.3rem;">MerkaLogistic</span>`;
        } else if (guia.numero_guia.includes("SPD")) {
          transporte_content = `<span style="background-color: red; color: white; padding: 5px; border-radius: 0.3rem;">SPEED</span>`;
        }
        ruta_descarga = `<a class="w-100" href="https://guias.imporsuitpro.com/Speed/descargar/${guia.numero_guia}" target="_blank">${guia.numero_guia}</a>`;
        ruta_traking = ``;
        funcion_anular = `anular_guiaSpeed('${guia.numero_guia}')`;
        estado = validar_estadoSpeed(guia.estado_guia_sistema);
      } else if (transporte == 3) {
        transporte_content = `<span style="background-color: red; color: white; padding: 5px; border-radius: 0.3rem;">GINTRACOM</span>`;
        ruta_descarga = `<a class="w-100" href="https://guias.imporsuitpro.com/Gintracom/label/${guia.numero_guia}" target="_blank">${guia.numero_guia}</a>`;
        ruta_traking = `https://ec.gintracom.site/web/site/tracking?guia=${guia.numero_guia}`;
        funcion_anular = `anular_guiaGintracom('${guia.numero_guia}')`;
        estado = validar_estadoGintracom(guia.estado_guia_sistema);
      } else {
        transporte_content = `<span style="background-color: #E3BC1C; color: white; padding: 5px; border-radius: 0.3rem;">Guia no enviada</span>`;
      }

      var span_estado = estado.span_estado;
      var estado_guia = estado.estado_guia;

      if (guia.drogshipin == 0) {
        drogshipin = "Local";
      } else if (drogshipin == 1) {
        drogshipin = "Drogshipin";
      }

      let ciudad = "Ciudad no especificada";
      let ciudadCompleta = guia.ciudad;
      if (ciudadCompleta) {
        let ciudadArray = ciudadCompleta.split("/");
        ciudad = ciudadArray[0];
      }

      novedad = "";
      if (guia.estado_guia_sistema == 14 && transporte == 1) {
        novedad = `<button id="downloadExcel" class="btn btn_novedades" onclick="gestionar_novedad('${guia.numero_guia}')">Gestionar novedad</button>`;
      } else if (guia.estado_guia_sistema == 6 && transporte == 3) {
        novedad = `<button id="downloadExcel" class="btn btn_novedades" onclick="gestionar_novedad('${guia.numero_guia}')">Gestionar novedad</button>`;
      } 
      if (
        guia.estado_guia_sistema >= 318 &&
        guia.estado_guia_sistema <= 351 &&
        transporte == 2
      ) {
        novedad = `<button id="downloadExcel" class="btn btn_novedades" onclick="gestionar_novedad('${guia.numero_guia}')">Gestionar novedad</button>`;
      }

      let plataforma = procesarPlataforma(guia.plataforma);
      if (guia.impreso == 0) {
        impresiones = `<box-icon name='printer' color= "red"></box-icon>`;
      } else {
        impresiones = `<box-icon name='printer' color= "#28E418"></box-icon>`;
      }

      despachado = "";
      if (guia.estado_factura == 2) {
        despachado = `<i class='bx bx-check' style="color:#28E418; font-size: 30px;"></i>`;
      } else if (guia.estado_factura == 1) {
        despachado = `<i class='bx bx-x' style="color:red; font-size: 30px;"></i>`;
      } else if (guia.estado_factura == 3) {
        despachado = `<i class="fa-solid fa-arrow-rotate-right" style="color:red; font-size: 21px;"></i>`;
      }

      let acreditado = guia.pagado === "1" 
      ? `<i class='bx bx-check' style="color:#28E418; font-size: 30px;"></i>` 
      : `<i class='bx bx-x' style="color:red; font-size: 30px;"></i>`;
      content += `
        <tr>
          <td><input type="checkbox" class="selectCheckbox" data-id="${guia.id_factura}"></td>
          <td>
            <div>${ruta_descarga}</div>
            <div>${drogshipin}</div>
          </td>
          <td>
            <div><button onclick="ver_detalle_cot('${guia.id_factura}')" class="btn btn-sm btn-outline-primary"> Ver detalle</button></div>
            <div>${guia.fecha_factura}</div>
          </td>
          <td>
            <div><strong>${guia.nombre}</strong></div>
            <div>${guia.c_principal} y ${guia.c_secundaria}</div>
            <div>telf: ${guia.telefono}</div>
          </td>
          <td>${guia.provinciaa}-${ciudad}</td>
          <td>
            <div>
              <strong>Tienda:</strong> <span class="link-like" id="plataformaLink">${guia.tienda}</span>
            </div>
            <div>
              <strong>Proveedor:</strong> <span class="link-like" id="plataformaLink">${guia.nombre_proveedor}</span>
            </div>
          </td>
          <td>${transporte_content}</td>
          <td>
            <div style="text-align: center;">
              <div>
                <span class="w-100 text-nowrap ${span_estado}">${estado_guia}</span>
              </div>
              <div style="position: relative; display: inline-block;">
                <a href="${ruta_traking}" target="_blank" style="vertical-align: middle;">
                  <img src="https://new.imporsuitpro.com/public/img/tracking.png" width="40px" id="buscar_traking" alt="buscar_traking">
                </a>
                <a href="https://wa.me/${formatPhoneNumber(guia.telefono)}" target="_blank" style="font-size: 45px; vertical-align: middle; margin-left: 10px;">
                  <i class='bx bxl-whatsapp-square' style="color: green;"></i>
                </a>
              </div>
              <div style="text-align: -webkit-center;">
                ${select_speed}
              </div>
              <div>${novedad}</div>
            </div>
          </td>
          <td>${despachado}</td>
          <td>${acreditado}</td>
          <td>${impresiones}</td>
          <td>${guia.monto_factura}</td>
          <td>${guia.costo_producto}</td>
          <td>${guia.costo_flete}</td>
          <td>${guia.utilidad}</td>
          <td></td>
          <td>${
            guia.monto_factura - guia.costo_producto - guia.costo_flete
          }</td>
          <td>${guia.cod == 1 ? "SI" : "NO"}</td>
          <td>
            <div class="dropdown">
              <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa-solid fa-gear"></i>
              </button>
              <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <li><span class="dropdown-item" style="cursor: pointer;" onclick="${funcion_anular}">Anular</span></li>
                <li><span class="dropdown-item" style="cursor: pointer;">Información</span></li>
                <li><span class="dropdown-item" style="cursor: pointer;" onclick='transito(${guia.id_factura})'>Transito</span></li>
                <li><span class="dropdown-item" style="cursor: pointer;" onclick='entregar(${guia.id_factura})'>Entregado</span></li>
                <li><span class="dropdown-item" style="cursor: pointer;" onclick='devolucion(${guia.id_factura})'>Devolución</span></li>
              </ul>
            </div>
          </td>
        </tr>`;
    });

    document.getElementById("tableBody_guias").innerHTML = content;

    // Actualiza las cards con los totales enviados desde el servidor
    const elementos = {
      "num_pedidos" : "total",
      "num_generadas" : "generada",
      "num_transito" : "en_transito",
      "num_entregadas" : "entregada",
      "num_novedad" : "novedad",
      "num_devolucion" : "devolucion",
      "num_zona_entrega": "zona_entrega"
    }

    Object.entries(elementos).forEach(([id, key])=>{
      let elemento = document.getElementById(id);
      if(elemento){
        elemento.innerText = totals[key];
      }
    })

    // Totals.total es el total de guías
    if (totals.total > 0) {
      let porcentajeGeneradas = Math.round((totals.generada / totals.total) * 100);
      let porcentajeTransito = Math.round((totals.en_transito / totals.total) * 100);
      let porcentajeEntregaZona = Math.round((totals.zona_entrega /totals.total) * 100);
      let porcentajeEntrega = Math.round((totals.entregada / totals.total) * 100);
      let porcentajeNovedad = Math.round((totals.novedad / totals.total) * 100);
  
      // Calculamos el último porcentaje ajustándolo para que la suma sea 100%
      let porcentajeDevolucion = 100 - (porcentajeGeneradas + porcentajeTransito + porcentajeEntrega + porcentajeEntregaZona + porcentajeNovedad);
  
      // Aplicamos los valores
      document.getElementById("progress_generadas").style.width = porcentajeGeneradas + "%";
      document.getElementById("percent_generadas").innerText = porcentajeGeneradas + "%";

      document.getElementById("progress_transito").style.width = porcentajeTransito + "%";
      document.getElementById("percent_transito").innerText = porcentajeTransito + "%";
 
      document.getElementById("progress_zonaentrega").style.width = porcentajeEntregaZona + "%";
      document.getElementById("percent_zonaentrega").innerText = porcentajeEntregaZona + "%";      
  
      document.getElementById("progress_entrega").style.width = porcentajeEntrega + "%";
      document.getElementById("percent_entrega").innerText = porcentajeEntrega + "%";
  
      document.getElementById("progress_novedad").style.width = porcentajeNovedad + "%";
      document.getElementById("percent_novedad").innerText = porcentajeNovedad + "%";
  
      document.getElementById("progress_devolucion").style.width = porcentajeDevolucion + "%";
      document.getElementById("percent_devolucion").innerText = porcentajeDevolucion + "%";
    } else {
      // Si total es 0 o no se encontró nada, limpia todo
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

//Capturamos evento en el input de busqueda
$("#buscar_guia").on("keyup", function () {
  let searchTerm = $(this).val();
  if (dataTable) {
    dataTable.search(searchTerm).draw();
  }
});

/**
 * Marca el estado como en tránsito
 */
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

/**
 * Marca el estado como entregado
 */
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

/**
 * Marca el estado como devolución
 */
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

// Event delegation for select change (estado-speed)
document.addEventListener("change", async (event) => {
  if (event.target && event.target.classList.contains("select-estado-speed")) {
    const numeroGuia = event.target.getAttribute("data-numero-guia");
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

function anular_guiaSpeed(numero_guia) {
  $.ajax({
    type: "GET",
    url: "https://guias.imporsuitpro.com/Speed/anular/" + numero_guia,
    dataType: "json",
    success: function (response) {
      if (response.status == 500) {
        toastr.error("LA GUIA NO SE ANULO CORRECTAMENTE", "NOTIFICACIÓN", {
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

function abrirModal_infoTienda(tienda) {
  let formData = new FormData();
  formData.append("tienda", tienda);

  $.ajax({
    url: SERVERURL + "pedidos/datosPlataformas",
    type: "POST",
    data: formData,
    processData: false, // No procesar los datos
    contentType: false, // No establecer ningún tipo de contenido
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

function ver_detalle_cot(id_factura) {
  let formData = new FormData();
  formData.append("id_factura", id_factura);

  $.ajax({
    url: SERVERURL + "Pedidos/obtenerDetalle",
    type: "POST",
    data: formData,
    processData: false, // No procesar los datos
    contentType: false, // No establecer ningún tipo de contenido
    success: function (response) {
      response = JSON.parse(response);

      // Mostrar los detalles principales de la primera factura
      $("#ordePara_detalleFac").text(response[0].nombre);
      $("#direccion_detalleFac").text(
        `${response[0].c_principal},${response[0].c_secundaria}`
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

      // Verificar si la respuesta tiene elementos y llenar la tabla
      if (response.length > 0) {
        let tableBody = $("#tabla_body");
        tableBody.empty(); // Limpiar cualquier contenido previo

        let total = 0; // Variable para calcular el total

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

        // Agregar la fila del total
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

function procesarPlataforma(url) {
  if (url == null || url == "") {
    let respuesta_error = "La tienda ya no existe";
    return respuesta_error;
  }
  // Eliminar el "https://"
  let sinProtocolo = url.replace("https://", "");
  // Encontrar la posición del primer punto
  let primerPunto = sinProtocolo.indexOf(".");
  // Obtener la subcadena desde el inicio hasta el primer punto
  let baseNombre = sinProtocolo.substring(0, primerPunto);
  // Convertir a mayúsculas
  let resultado = baseNombre.toUpperCase();
  return resultado;
}

function validar_estadoLaar(estado) {
  var span_estado = "";
  var estado_guia = "";
  if (estado == 1) {
    span_estado = "badge_purple";
    estado_guia = "Generado";
  } else if (estado == 2) {
    span_estado = "badge_purple";
    estado_guia = "Por recolectar";
  } else if (estado == 3) {
    span_estado = "badge_purple";
    estado_guia = "Recolectado";
  } else if (estado == 4) {
    span_estado = "badge_purple";
    estado_guia = "En bodega";
  } else if (estado == 5) {
    span_estado = "badge_warning";
    estado_guia = "En transito";
  } else if (estado == 6) {
    span_estado = "badge_warning";
    estado_guia = "Zona de entrega";
  } else if (estado == 7) {
    span_estado = "badge_green";
    estado_guia = "Entregado";
  } else if (estado == 8) {
    span_estado = "badge_danger";
    estado_guia = "Anulado";
  } else if (estado == 11) {
    span_estado = "badge_warning";
    estado_guia = "En transito";
  } else if (estado == 12) {
    span_estado = "badge_warning";
    estado_guia = "En transito";
  } else if (estado == 14) {
    span_estado = "badge_danger";
    estado_guia = "Novedad";
  } else if (estado == 9) {
    span_estado = "badge_danger";
    estado_guia = "Devuelto";
  }
  return {
    span_estado: span_estado,
    estado_guia: estado_guia,
  };
}

function validar_estadoServi(estado) {
  var span_estado = "";
  var estado_guia = "";
  if (estado == 101) {
    span_estado = "badge_danger";
    estado_guia = "Anulado";
  } else if (estado == 100 || estado == 102 || estado == 103) {
    span_estado = "badge_purple";
    estado_guia = "Generado";
  } else if (estado == 200 || estado == 201 || estado == 202) {
    span_estado = "badge_purple";
    estado_guia = "Recolectado";
  } else if (estado >= 300 && estado <= 316) {
    span_estado = "badge_warning";
    estado_guia = "Procesamiento";
  } else if (estado == 317){
    span_estado = "badge_warning";
    estado_guia = "Retirar en agencia";
  } else if (estado >= 400 && estado <= 403) {
    span_estado = "badge_green";
    estado_guia = "Entregado";
  } else if (estado >= 318 && estado <= 351) {
    span_estado = "badge_danger";
    estado_guia = "Novedad";
  } else if (estado >= 500 && estado <= 502) {
    span_estado = "badge_danger";
    estado_guia = "Devuelto";
  }
  return {
    span_estado: span_estado,
    estado_guia: estado_guia,
  };
}

function validar_estadoGintracom(estado) {
  var span_estado = "";
  var estado_guia = "";
  if (estado == 1) {
    span_estado = "badge_purple";
    estado_guia = "Generada";
  } else if (estado == 2) {
    span_estado = "badge_warning";
    estado_guia = "Picking";
  } else if (estado == 3) {
    span_estado = "badge_warning";
    estado_guia = "Packing";
  } else if (estado == 4) {
    span_estado = "badge_warning";
    estado_guia = "En tránsito";
  } else if (estado == 5) {
    span_estado = "badge_warning";
    estado_guia = "En reparto";
  } else if (estado == 6) {
    span_estado = "badge_purple";
    estado_guia = "Novedad";
  } else if (estado == 7) {
    span_estado = "badge_green";
    estado_guia = "Entregado";
  } else if (estado == 8) {
    span_estado = "badge_danger";
    estado_guia = "Devuelto";
  } else if (estado == 9) {
    span_estado = "badge_danger";
    estado_guia = "Devuelto";
  } else if (estado == 10) {
    span_estado = "badge_danger";
    estado_guia = "Cancelada por transportadora";
  } else if (estado == 11) {
    span_estado = "badge_danger";
    estado_guia = "Indemnización";
  } else if (estado == 12) {
    span_estado = "badge_danger";
    estado_guia = "Anulada";
  } else if (estado == 13) {
    span_estado = "badge_danger";
    estado_guia = "Devuelto";
  }
  return {
    span_estado: span_estado,
    estado_guia: estado_guia,
  };
}

function validar_estadoSpeed(estado) {
  var span_estado = "";
  var estado_guia = "";
  if (estado == 2) {
    span_estado = "badge_purple";
    estado_guia = "generado";
  } else if (estado == 3) {
    span_estado = "badge_warning";
    estado_guia = "En transito";
  } else if (estado == 7) {
    span_estado = "badge_green";
    estado_guia = "Entregado";
  } else if (estado == 9) {
    span_estado = "badge_danger";
    estado_guia = "Devuelto";
  } else if (estado == 14) {
    span_estado = "badge_purple";
    estado_guia = "Novedad";
  }
  return {
    span_estado: span_estado,
    estado_guia: estado_guia,
  };
}

// Manejador del botón para "Generar Impresión"
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
    processData: false, // Necesario para FormData
    contentType: false, // Necesario para FormData
    dataType: "json",
    beforeSend: function () {
      // Mostrar alerta de carga antes de realizar la solicitud AJAX
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

/**
 * Ajusta un número de teléfono al formato +593, si no lo tuviera
 */
function formatPhoneNumber(number) {
  number = number.replace(/[^\d+]/g, "");
  if (/^\+593/.test(number)) {
    return number;
  } else if (/^593/.test(number)) {
    return "+" + number;
  } else {
    if (number.startsWith("0")) {
      number = number.substring(1);
    }
    number = "+593" + number;
  }
  return number;
}

// =========================================================
// Funciones para anular guías
// =========================================================
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
        toastr.error("LA GUIA NO SE ANULO CORRECTAMENTE", "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });
      } else if (response.status == 200) {
        toastr.success("GUIA ANULADA CORRECTAMENTE", "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });
        $("#imagen_categoriaModal").modal("hide");
        reloadDataTable();
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      alert(errorThrown);
    },
  });
}

function anular_guiaServi(numero_guia) {
  $.ajax({
    type: "GET",
    url: "https://guias.imporsuitpro.com/Servientrega/Anular/" + numero_guia,
    dataType: "json",
    success: function (response) {
      // Petición a la API externa
    },
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
        toastr.error("LA GUIA NO SE ANULO CORRECTAMENTE", "NOTIFICACIÓN", {
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
        toastr.error("LA GUIA NO SE ANULO CORRECTAMENTE", "NOTIFICACIÓN", {
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

// =========================================================
// Funciones de gestión de novedades
// =========================================================
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
      console.error("Error al obtener la lista de bodegas:", error);
    },
  });
}

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
  button.disabled = true; // Desactivar el botón

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
    processData: false, // No procesar los datos
    contentType: false, // No establecer ningún tipo de contenido
    success: function (response) {
      response = JSON.parse(response);
      if (response.error === true) {
        toastr.error("" + response.message, "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });
        button.disabled = false;
      } else if (response.error === false) {
        toastr.success("" + response.message, "NOTIFICACIÓN", {
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

function enviar_serviNovedad() {
  var button = document.getElementById("boton_servi");
  button.disabled = true; // Desactivar el botón

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
    processData: false, // No procesar los datos
    contentType: false, // No establecer ningún tipo de contenido
    success: function (response) {
      response = JSON.parse(response);
      if (response.status == 500) {
        toastr.error("Novedad no enviada CORRECTAMENTE", "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });
        button.disabled = false;
      } else if (response.status == 200) {
        toastr.success("Novedad enviada CORRECTAMENTE", "NOTIFICACIÓN", {
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
  button.disabled = true; // Desactivar el botón

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
    processData: false, // No procesar los datos
    contentType: false, // No establecer ningún tipo de contenido
    success: function (response) {
      response = JSON.parse(response);
      if (response.status == 500) {
        toastr.error("Novedad no enviada CORRECTAMENTE", "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });
        button.disabled = false;
      } else if (response.status == 200) {
        toastr.success("Novedad enviada CORRECTAMENTE", "NOTIFICACIÓN", {
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

// Función común para descargar el reporte según el formato y extensión
async function descargarReporte(formato, extension) {
  const formData = new FormData();
  formData.append("fecha_inicio", fecha_inicio);
  formData.append("fecha_fin", fecha_fin);
  formData.append("transportadora", $("#transporte").val());
  formData.append("estado", $("#estado_q").val());
  formData.append("estado_pedido", $("#estado_pedido").val() || "");
  formData.append("drogshipin", $("#tienda_q").val());
  formData.append("impreso", $("#impresion").val());
  formData.append("despachos", $("#despachos").val());
  formData.append("formato", formato); // 'excel' o 'csv'

  const response = await fetch(`${SERVERURL}pedidos/exportarGuiasAdministrador`, {
    method: "POST",
    body: formData,
  });

  const blob = await response.blob();
  const url = window.URL.createObjectURL(blob);
  const a = document.createElement("a");
  a.href = url;
  a.download = `guias.${extension}`;
  document.body.appendChild(a);
  a.click();
  a.remove();
  window.URL.revokeObjectURL(url);
}

// Asignar eventos a las opciones del dropdown
document.getElementById("downloadExcelOption").addEventListener("click", async (e) => {
  e.preventDefault(); // Evita la acción predeterminada del enlace
  await descargarReporte("excel", "xlsx");
});

document.getElementById("downloadCsvOption").addEventListener("click", async (e) => {
  e.preventDefault();
  await descargarReporte("csv", "csv");
});

//Cargamos la tabla cuando el DOM esté listo (si quieres tener datos por defecto).

document.addEventListener("DOMContentLoaded", function () {
  // NUEVO: si deseas cargar la DataTable por defecto al entrar a la página
  initDataTable(); 

  // NUEVO: agregar el evento al botón "Aplicar Filtros"
  const btnAplicar = document.getElementById("btnAplicarFiltros");
  if (btnAplicar) {
    btnAplicar.addEventListener("click", async function () {
      btnAplicar.disabled = true;
      try{// NUEVO: Leer la fecha seleccionada en el daterangepicker
        let rangoFechas = $("#daterange").val();
        if (rangoFechas) {
          let fechas = rangoFechas.split(" - ");
          fecha_inicio = fechas[0] + " 00:00:00";
          fecha_fin = fechas[1] + " 23:59:59";
        }
        // NUEVO: Recargar la DataTable con los nuevos valores de los filtros
        //Cierra el modal después de aplicar los filtros
        await initDataTable();
        const modalInstance = bootstrap.Modal.getInstance(document.getElementById('modalFiltros'));
        if (modalInstance) {
          modalInstance.hide();
        }
      } finally{
        btnAplicar.disabled = false;
      }
    });
  }
});
