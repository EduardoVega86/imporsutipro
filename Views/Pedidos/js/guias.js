let dataTable;
let dataTableIsInitialized = false;
let actualizarCards = true; //por defecto si se actualizan

const dataTableOptions = {
  columnDefs: [
    {
      className: "centered",
      targets: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13],
    },
    { orderable: false, targets: 0 }, // Asegúrate de que esta sea la columna correcta
    { visible: false, targets: 12 },
    { visible: false, targets: 13 },
  ],
  order: [[2, "desc"]], // Ordenar por la primera columna (fecha) en orden descendente
  pageLength: 10,
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

const initDataTable = async () => {
  showTableLoader();

  try{
    if(dataTableIsInitialized){
      dataTable.destroy();
    }
  
 //Realiza solicitud para obtener los datos
  await listGuias();

  //Inicializamos datatable con los nuevos datos
  dataTable = $("#datatable_guias").DataTable(dataTableOptions);
  dataTableIsInitialized = true;

  // Maneja el checkbox de "selleccionar todos"
  document.getElementById("selectAll").addEventListener("change", function () {
    const checkboxes = document.querySelectorAll(".selectCheckbox");
    checkboxes.forEach((checkbox) => (checkbox.checked = this.checked));
  });
  }catch (error){
    console.error("Error al cargar la tabla:", error)
  }finally{
    hideTableLoader();
  }
};

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

    const response = await fetch(`${SERVERURL}pedidos/obtener_guias`, {
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
      let despachado = "";
      if (transporte == 2) {
        transporte_content =
          '<span style="background-color: #28C839; color: white; padding: 5px; border-radius: 0.3rem;">SERVIENTREGA</span>';
        ruta_descarga = `<a class="w-100" href="https://guias.imporsuitpro.com/Servientrega/guia/${guia.numero_guia}" target="_blank">${guia.numero_guia}</a>`;
        ruta_traking = `https://www.servientrega.com.ec/Tracking/?guia=${guia.numero_guia}&tipo=GUIA`;
        funcion_anular = `anular_guiaServi('${guia.numero_guia}')`;
        estado = validar_estadoServi(guia.estado_guia_sistema);
      } else if (transporte == 1) {
        transporte_content =
          '<span style="background-color: #E3BC1C; color: white; padding: 5px; border-radius: 0.3rem;">LAAR</span>';
        ruta_descarga = `<a class="w-100" href="https://api.laarcourier.com:9727/guias/pdfs/DescargarV2?guia=${guia.numero_guia}" target="_blank">${guia.numero_guia}</a>`;
        ruta_traking = `https://fenixoper.laarcourier.com/Tracking/Guiacompleta.aspx?guia=${guia.numero_guia}`;
        funcion_anular = `anular_guiaLaar('${guia.numero_guia}')`;
        estado = validar_estadoLaar(guia.estado_guia_sistema);
      } else if (transporte == 4) {
        if (guia.numero_guia.includes("MKL")) {
          transporte_content =
            '<span style="background-color: red; color: white; padding: 5px; border-radius: 0.3rem;">MerkaLogistic</span>';
        } else if (guia.numero_guia.includes("SPD")) {
          transporte_content =
            '<span style="background-color: red; color: white; padding: 5px; border-radius: 0.3rem;">SPEED</span>';
        }
        ruta_descarga = `<a class="w-100" href="https://guias.imporsuitpro.com/Speed/descargar/${guia.numero_guia}" target="_blank">${guia.numero_guia}</a>`;
        ruta_traking = ``;
        funcion_anular = `anular_guiaSpeed('${guia.numero_guia}')`;
        estado = validar_estadoSpeed(guia.estado_guia_sistema);
        if (CARGO == 10) {
          select_speed = `
          <select class="form-select" style="max-width: 130px;" id="select_estadoSpeed">
              <option value="0" selected>-- Selecciona estado --</option>
              <option value="2" selected>Generado</option>
              <option value="3" selected>Transito</option>
              <option value="7" selected>Entregado</option>
              <option value="9" selected>Devuelto</option>
          </select>`;
        }
      } else if (transporte == 3) {
        transporte_content =
          '<span style="background-color: red; color: white; padding: 5px; border-radius: 0.3rem;">GINTRACOM</span>';
        ruta_descarga = `<a class="w-100" href="https://guias.imporsuitpro.com/Gintracom/label/${guia.numero_guia}" target="_blank">${guia.numero_guia}</a>`;
        ruta_traking = `https://ec.gintracom.site/web/site/tracking?guia=${guia.numero_guia}`;
        funcion_anular = `anular_guiaGintracom('${guia.numero_guia}')`;
        estado = validar_estadoGintracom(guia.estado_guia_sistema);
      } else {
        transporte_content =
          '<span style="background-color: #E3BC1C; color: white; padding: 5px; border-radius: 0.3rem;">Guia no enviada</span>';
      }

      var span_estado = estado.span_estado;
      var estado_guia = estado.estado_guia;

      let ciudad = "Ciudad no especificada";
      let ciudadCompleta = guia.ciudad;
      if (ciudadCompleta) {
        let ciudadArray = ciudadCompleta.split("/");
        ciudad = ciudadArray[0];
      } else {
        console.log("La ciudad no está definida o está vacía");
      }

      console.log("Ciudad:", ciudad);

      novedad = "";
      if (guia.estado_guia_sistema == 14 && transporte == 1) {
        novedad = `<button id="downloadExcel" class="btn btn_novedades" onclick="gestionar_novedad()">Gestionar novedad</button>`;
      } else if (guia.estado_guia_sistema == 6 && transporte == 3) {
        novedad = `<button id="downloadExcel" class="btn btn_novedades" onclick="gestionar_novedad()">Gestionar novedad</button>`;
      } else if (guia.estado_guia_sistema == 14 && transporte == 4) {
        novedad = `<button id="downloadExcel" class="btn btn_novedades" onclick="gestionar_novedad()">Gestionar novedad</button>`;
      }

      if (
        ((guia.estado_novedad >= 1 && guia.estado_novedad <= 7) ||
          guia.estado_novedad == 15 ||
          guia.estado_novedad == 27) &&
        transporte == 3
      ) {
        novedad = `<span>Esta novedad es de tipo operativo y no puede ser solventada</span>`;
      } else if (guia.solucionada == 1) {
        novedad = `<span>Novedad solventada</span>`;
      }

      if (
        guia.estado_guia_sistema >= 318 &&
        guia.estado_guia_sistema <= 351 &&
        transporte == 2 &&
        guia.solucionada == 0
      ) {
        novedad = `<button id="downloadExcel" class="btn btn_novedades" onclick="gestionar_novedad()">Gestionar novedad</button>`;
      } else if (
        guia.estado_guia_sistema >= 318 &&
        guia.estado_guia_sistema <= 351 &&
        transporte == 2 &&
        guia.terminado == 1
      ) {
        novedad = `<span> Proceso de guia terminado</span>`;
      } else if (
        guia.estado_guia_sistema >= 318 &&
        guia.estado_guia_sistema <= 351 &&
        transporte == 2 &&
        guia.solucionada == 1
      ) {
        novedad = `<span>Novedad solventada</span>`;
      }

      let plataforma = procesarPlataforma(guia.plataforma);
      let boton_anular = ``;
      if (guia.impreso == 0) {
        impresiones = `<box-icon name='printer' color="red"></box-icon>`;
        boton_anular = `<li><span class="dropdown-item" style="cursor: pointer;" onclick="${funcion_anular}">Anular</span></li>`;
      } else {
        impresiones = `<box-icon name='printer' color="#28E418"></box-icon>`;
        boton_anular = ``;
      }

      despachado = "";
      if (guia.estado_factura == 2) {
        despachado = `<i class='bx bx-check' style="color:#28E418; font-size: 30px;"></i>`;
      } else if (guia.estado_factura == 1) {
        despachado = `<i class='bx bx-x' style="color:red; font-size: 30px;"></i>`;
      } else if (guia.estado_factura == 3) {
        despachado = `<i class="fa-solid fa-arrow-rotate-right" style="color:red; font-size: 21px;"></i>`;
      }
      let mostrar_tienda = `<td><span class="link-like" id="plataformaLink" onclick="abrirModal_infoTienda('${guia.plataforma}')">${plataforma}</span></td>`;
      mostrar_tienda = "";

      let acreditado = guia.pagado === "1" 
          ? `<i class='bx bx-check' style="color:#28E418; font-size: 30px;"></i>` 
          : `<i class='bx bx-x' style="color:red; font-size: 30px;"></i>`;
      content += `
                <tr>
                    <td><input type="checkbox" class="selectCheckbox" data-id="${guia.id_factura}"></td>
                    <td>
                      <div>
                        ${ruta_descarga}
                      </div>
                    </td>
                    <td>
                      <div><button onclick="ver_detalle_cot('${guia.id_factura}')" class="btn btn-sm btn-outline-primary"> Ver detalle</button></div>
                      <div>${guia.fecha_guia}</div>
                    </td>
                    <td>
                      <div><strong>${guia.nombre}</strong></div>
                      <div>${guia.c_principal} y ${guia.c_secundaria}</div>
                      <div>telf: ${guia.telefono}</div>
                    </td>
                    <td>${guia.provinciaa}-${ciudad}</td>
                    ${mostrar_tienda}
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
                        <div>
                          ${novedad}
                        </div>
                      </div>
                    </td>
                    <td>${despachado}</td>
                    <td>${acreditado}</td>
                    <td>${impresiones}</td>
                    <td>${guia.contiene}</td>
                    <td>
                      <div class="dropdown">
                        <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                          <i class="fa-solid fa-gear"></i>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                          ${boton_anular}
                          <li><span class="dropdown-item" style="cursor: pointer;">Información</span></li>
                        </ul>
                      </div>
                    </td>
                    <td>${guia.monto_factura}</td>
                    <td>${guia.costo_producto}</td>
                </tr>`;
    });
    document.getElementById("tableBody_guias").innerHTML = content;
    
    if(actualizarCards){
      // Actualiza las cards con los totales enviados desde el servidor
      const elementos = {
        "num_pedidos": "total",
        "num_generadas": "generada",
        "num_transito": "en_transito",
        "num_entregadas": "entregada",
        "num_novedad": "novedad",
        "num_devolucion": "devolucion",
        "num_zona_entrega": "zona_entrega"
      };
    
      Object.entries(elementos).forEach(([id, key]) => {
          let elemento = document.getElementById(id);
          if (elemento) {
              elemento.innerText = totals[key];
          }
      });

      // Totals.total es el total de guías
      if (totals.total > 0) {
        // 1) Calculamos valores en decimales (sin redondear)
        let valGeneradas    = (totals.generada      / totals.total) * 100;
        let valTransito     = (totals.en_transito   / totals.total) * 100;
        let valEntregaZona  = (totals.zona_entrega  / totals.total) * 100;
        let valEntrega      = (totals.entregada     / totals.total) * 100;
        let valNovedad      = (totals.novedad       / totals.total) * 100;
        let valDevolucion   = (totals.devolucion    / totals.total) * 100;
      
        // 2) Sumamos para ver qué falta o sobra
        let sum = valGeneradas + valTransito + valEntregaZona + valEntrega + valNovedad + valDevolucion;
        let diff = 100 - sum; // puede ser positivo o negativo
      
        // 3) Metemos en un array
        let arr = [valGeneradas, valTransito, valEntregaZona, valEntrega, valNovedad, valDevolucion];
      
        // 4) Encontramos el que tenga el mayor valor
        let maxIndex = 0;
        let maxVal = arr[0];
        for (let i = 1; i < arr.length; i++) {
          if (arr[i] > maxVal) {
            maxVal = arr[i];
            maxIndex = i;
          }
        }
      
        // 5) Ajustamos la diferencia en el mayor
        arr[maxIndex] += diff;
      
        // 6) Finalmente, redondeamos
        arr = arr.map(v => Math.round(v));
      
        // Desempaquetamos de nuevo:
        let porcentajeGeneradas    = arr[0];
        let porcentajeTransito     = arr[1];
        let porcentajeEntregaZona  = arr[2];
        let porcentajeEntrega      = arr[3];
        let porcentajeNovedad      = arr[4];
        let porcentajeDevolucion   = arr[5];
      
        // 7) Asignamos a las barras
        document.getElementById("progress_generadas").style.width = porcentajeGeneradas + "%";
        document.getElementById("percent_generadas").innerText    = porcentajeGeneradas + "%";
      
        document.getElementById("progress_transito").style.width  = porcentajeTransito + "%";
        document.getElementById("percent_transito").innerText     = porcentajeTransito + "%";
      
        document.getElementById("progress_zonaentrega").style.width = porcentajeEntregaZona + "%";
        document.getElementById("percent_zonaentrega").innerText    = porcentajeEntregaZona + "%";
      
        document.getElementById("progress_entrega").style.width    = porcentajeEntrega + "%";
        document.getElementById("percent_entrega").innerText       = porcentajeEntrega + "%";
      
        document.getElementById("progress_novedad").style.width    = porcentajeNovedad + "%";
        document.getElementById("percent_novedad").innerText       = porcentajeNovedad + "%";
      
        document.getElementById("progress_devolucion").style.width = porcentajeDevolucion + "%";
        document.getElementById("percent_devolucion").innerText    = porcentajeDevolucion + "%";
      } else {
        // Si totals.total == 0 => limpiar todas las barras
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
    }    
  } catch (ex) {
    alert(ex);
  }
};

$("#buscar_guia").on("keyup", function () {
  let searchTerm = $(this).val();
  if (dataTable) {
    dataTable.search(searchTerm).draw();
  }
});

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
      $("#fecha_detalleFac").text(response[0].fecha_guia);
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

// Function to handle the click event for sending selected items
document.getElementById("imprimir_guias").addEventListener("click", () => {
  const selectedGuias = [];
  const checkboxes = document.querySelectorAll(".selectCheckbox:checked");

  checkboxes.forEach((checkbox) => {
    selectedGuias.push(checkbox.getAttribute("data-id"));
  });

  // Convert the selected items to JSON and log it to the console
  const selectedGuiasJson = JSON.stringify(selectedGuias);
  console.log(selectedGuiasJson);

  let formData = new FormData();
  formData.append("facturas", selectedGuiasJson);

  $.ajax({
    type: "POST",
    url: SERVERURL + "/Manifiestos/generar", // Asegúrate de que SERVERURL esté definida
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
        link.download = ""; // Puedes poner un nombre de archivo aquí si lo deseas
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);

        let formData_cambiarImpreso = new FormData();
        formData_cambiarImpreso.append("guias", selectedGuiasJson); // Añadir el SKU al FormData

        $.ajax({
          url: SERVERURL + "Manifiestos/cambiarImpreso",
          type: "POST", // Cambiar a POST para enviar FormData
          data: formData_cambiarImpreso,
          processData: false, // No procesar los datos
          contentType: false, // No establecer ningún tipo de contenido
          success: function (response) {},
          error: function (jqXHR, textStatus, errorThrown) {
            alert(errorThrown);
          },
        });

        // Cerrar el Swal después de hacer clic en el enlace
        initDataTable();
        Swal.close();
      }
    },
    error: function (xhr, status, error) {
      console.error("Error en la solicitud AJAX:", error);
      alert("Hubo un problema al imprimir manifiesto");
    },
  });
});

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

  const response = await fetch(`${SERVERURL}pedidos/exportarGuias`, {
    method: "POST",
    body: formData,
  });

  const blob = await response.blob();
  const url = window.URL.createObjectURL(blob);
  const a = document.createElement("a");
  a.href = url;
  a.download = (formato === 'csv') ? 'guias.csv' : 'guias.xlsx';
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

async function descargarReportePorFila(formato, extension) {
  const formData = new FormData();
  formData.append("fecha_inicio", fecha_inicio);
  formData.append("fecha_fin", fecha_fin);
  formData.append("transportadora", $("#transporte").val());
  formData.append("estado", $("#estado_q").val());
  formData.append("estado_pedido", $("#estado_pedido").val() || "");
  formData.append("drogshipin", $("#tienda_q").val());
  formData.append("impreso", $("#impresion").val());
  formData.append("despachos", $("#despachos").val());
  formData.append("buscar_guia", $("#buscar_guia").val().trim());
  formData.append("formato", formato); // 'excel' o 'csv'

  // Ahora apuntamos a TU NUEVA ruta
  const response = await fetch(`${SERVERURL}pedidos/exportarGuiasPorFila`, {
    method: "POST",
    body: formData,
  });

  const blob = await response.blob();
  const url = window.URL.createObjectURL(blob);
  const a = document.createElement("a");
  a.href = url;
  a.download = (formato === 'csv') ? 'guias_por_fila.csv' : 'guias_por_fila.xlsx';
  document.body.appendChild(a);
  a.click();
  a.remove();
  window.URL.revokeObjectURL(url);
}

document
  .getElementById("downloadExcelOptionPorFila")
  .addEventListener("click", async (e) => {
    e.preventDefault(); // Evita navegación
    await descargarReportePorFila("excel", "xlsx"); 
  });

const loader = document.getElementById("modalFilterLoader");

window.addEventListener("load", async () => {
  await initDataTable();

  const btnAplicar = document.getElementById("btnAplicarFiltros");
  if(btnAplicar){
    btnAplicar.addEventListener("click", async function () {
      //Deshabilitamos el boton al comenzar
      btnAplicar.disabled = true;
      loader.style.display = "inline-block"; // Mostrar el loader
      try{
        let rangoFechas = $("#daterange").val();
        if (rangoFechas){
            let fechas = rangoFechas.split(" - ");
            fecha_inicio = fechas[0] + " 00:00:00";
            fecha_fin = fechas[1] + " 23:59:59";
        }
        await initDataTable();
        // Cierra el modal después de aplicar los filtros
        const modalInstance = bootstrap.Modal.getInstance(document.getElementById('modalFiltros'));
        if (modalInstance) {
          modalInstance.hide();
        }
      } finally {
        btnAplicar.disabled = false;
        loader.style.display = "none"; // Ocultar el loader
      }
    });
  }

  //Cards interactivas, al dar click filtrar por tal.
  let estadoSeleccionado = null;
  
  document.querySelectorAll(".card-filtro").forEach((card) => {
    card.addEventListener("click", async function () {
      const estado = this.getAttribute("data-estado");
  
      if (estado === "" || estadoSeleccionado === estado) {
        // 👉 Mostrar todos (sin filtro)
        estadoSeleccionado = null;
        $("#estado_q").val(""); // Resetea filtro estado
        // Quitar resaltado de todas las cards
        document.querySelectorAll(".card-filtro").forEach(c => c.classList.remove("selected"));
      } else {
        // 👉 Aplicar filtro
        estadoSeleccionado = estado;
        $("#estado_q").val(estado);
        // Resaltar solo la card actual
        document.querySelectorAll(".card-filtro").forEach(c => c.classList.remove("selected"));
        this.classList.add("selected");
      }
  
      actualizarCards = false;
      await initDataTable();
      actualizarCards = true; // restaurar para futuros usos
    });
  });
});

function formatPhoneNumber(number) {
  // Eliminar caracteres no numéricos excepto el signo +
  number = number.replace(/[^\d+]/g, "");

  // Verificar si el número ya tiene el código de país +593
  if (/^\+593/.test(number)) {
    // El número ya está correctamente formateado con +593
    return number;
  } else if (/^593/.test(number)) {
    // El número tiene 593 al inicio pero le falta el +
    return "+" + number;
  } else {
    // Si el número comienza con 0, quitarlo
    if (number.startsWith("0")) {
      number = number.substring(1);
    }
    // Agregar el código de país +593 al inicio del número
    number = "+593" + number;
  }

  return number;
}

//anular guia
function anular_guiaLaar(numero_guia) {
  let formData = new FormData();
  formData.append("guia", numero_guia);

  $.ajax({
    url: SERVERURL + "guias/anularGuia",
    type: "POST",
    data: formData,
    processData: false, // No procesar los datos
    contentType: false, // No establecer ningún tipo de contenido
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

        initDataTable();
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
    success: function (response) {},
    error: function (xhr, status, error) {
      /* alert("Hubo un problema al anular la guia de Servientrega"); */
    },
  });
  $.ajax({
    type: "GET",
    /* url: "https://guias.imporsuitpro.com/Servientrega/Anular/" + numero_guia, */
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

        initDataTable();
      }
    },
    error: function (xhr, status, error) {
      alert("Hubo un problema al anular la guia de Servientrega");
    },
  });
}

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

        initDataTable();
      }
    },
    error: function (xhr, status, error) {
      alert("Hubo un problema al anular la guia de Speed");
    },
  });
}

function anular_guiaGintracom(numero_guia) {
  $.ajax({
    type: "POST",
    url: "https://guias.imporsuitpro.com/Gintracom/anular/" + numero_guia,
    dataType: "json",
    success: function (response) {
      if (response.status == 200) {
        toastr.success("GUIA ANULADA CORRECTAMENTE", "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });

        initDataTable();
      } else {
        toastr.error("LA GUIA NO SE ANULO CORRECTAMENTE", "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });
      }
    },
    error: function (xhr, status, error) {
      console.error("Error en la solicitud AJAX:", error);
      alert("Hubo un problema al anular guia de gintracom");
    },
  });
}

//fin anular guia
//modal novedades
function gestionar_novedad() {
  window.location.href = SERVERURL+'Pedidos/novedades_2';
}

function resetModalInputs(modalId) {
  // Selecciona el modal por su ID
  const modal = document.querySelector(`#${modalId}`);

  if (modal) {
    // Selecciona todos los inputs y los limpia
    const inputs = modal.querySelectorAll("input");
    inputs.forEach((input) => {
      input.value = "";
    });

    // Selecciona todos los select y los reinicia al valor predeterminado
    const selects = modal.querySelectorAll("select");
    selects.forEach((select) => {
      select.selectedIndex = 0; // Reinicia al primer option
    });

    // Oculta las secciones opcionales que estén configuradas con "display: none"
    const optionalSections = modal.querySelectorAll('[style*="display"]');
    optionalSections.forEach((section) => {
      section.style.display = "none";
    });

    console.log("Modal inputs and selects reset successfully.");
  } else {
    console.error("Modal not found!");
  }
}

function hiden_laar() {
  $("#telefono_laar_novedad").hide();
  $("#calle_principal_laar_novedad").hide();
  $("#calle_secundaria_laar_novedad").hide();
  $("#observacion_laar_novedad").hide();
  $("#solucionl_laar_novedad").hide();
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

  $("#tipo_laar").change(function () {
    var tipo = $("#tipo_laar").val();
    if (tipo == "NI") {
      $("#telefono_laar_novedad").show();
      $("#solucionl_laar_novedad").show();

      $("#calle_principal_laar_novedad").hide();
      $("#calle_secundaria_laar_novedad").hide();
      $("#observacion_laar_novedad").hide();
    } else if (tipo == "DI") {
      $("#calle_principal_laar_novedad").show();
      $("#calle_secundaria_laar_novedad").show();
      $("#solucionl_laar_novedad").show();

      $("#telefono_laar_novedad").hide();
      $("#observacion_laar_novedad").hide();
    } else if ((tipo = "OG")) {
      $("#observacion_laar_novedad").show();
      $("#solucionl_laar_novedad").show();

      $("#telefono_laar_novedad").hide();
      $("#calle_principal_laar_novedad").hide();
      $("#calle_secundaria_laar_novedad").hide();
    }
  });
});

function validados_numero(observacion) {
  // Definir una expresión regular para encontrar números de teléfono
  // Este ejemplo considera números con formato de 10 dígitos, con o sin separadores.
  const regexTelefono =
    /(\+?\d{1,3})?[-.\s]?\(?\d{1,4}\)?[-.\s]?\d{1,4}[-.\s]?\d{1,9}/;

  // Comprobar si en la observación existe un número que coincida con la expresión regular
  if (regexTelefono.test(observacion)) {
    return true;
  } else {
    return false;
  }
}
