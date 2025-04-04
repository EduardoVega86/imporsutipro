let dataTableNovedades;
let dataTableNovedadesIsInitialized = false;

const dataTableNovedadesOptions = {
  columnDefs: [
    { className: "centered", targets: [1, 2, 3, 4, 5] },
    { orderable: false, targets: 0 }, //ocultar para columna 0 el ordenar columna
  ],
  order: [[2, "desc"]],
  pageLength: 10,
  destroy: true,
  dom: '<"d-flex w-full justify-content-between"lBf><t><"d-flex justify-content-between"ip>',
  buttons: [
    {
      extend: "excelHtml5",
      text: 'Excel <i class="fa-solid fa-file-excel"></i>',
      title: "Panel de Control: Usuarios",
      titleAttr: "Exportar a Excel",
      exportOptions: {
        columns: [1, 2, 3, 4, 5, 6, 7],
      },
      filename: "Productos" + "_" + getFecha(),
      footer: true,
      className: "btn-excel",
    },
    {
      extend: "csvHtml5",
      text: 'CSV <i class="fa-solid fa-file-csv"></i>',
      title: "Panel de Control: Productos",
      titleAttr: "Exportar a CSV",
      exportOptions: {
        columns: [1, 2, 3, 4, 5, 6, 7],
      },
      filename: "Productos" + "_" + getFecha(),
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

function getFecha() {
  let fecha = new Date();
  let mes = fecha.getMonth() + 1;
  let dia = fecha.getDate();
  let anio = fecha.getFullYear();
  let fechaHoy = anio + "-" + mes + "-" + dia;
  return fechaHoy;
}

const initDataTableNovedades = async () => {
  if (dataTableNovedadesIsInitialized) {
    dataTableNovedades.destroy();
  }

  await listNovedades();

  dataTableNovedades = $("#datatable_novedades").DataTable(
    dataTableNovedadesOptions
  );

  dataTableNovedadesIsInitialized = true;
};

const listNovedades = async () => {
  try {
    const response = await fetch("" + SERVERURL + "novedades/cargarNovedades");
    const novedades = await response.json();

    let content = ``;
    let transportadora = ``;
    let ruta_traking = ``;
    novedades.forEach((novedad, index) => {
      if (
        novedad.guia_novedad.includes("IMP") ||
        novedad.guia_novedad.includes("MKP")
      ) {
        transportadora = "LAAR";
        ruta_traking = `https://fenixoper.laarcourier.com/Tracking/Guiacompleta.aspx?guia=${novedad.guia_novedad}`;
      } else if (novedad.guia_novedad.includes("I")) {
        transportadora = "GINTRACOM";
        ruta_traking = `https://ec.gintracom.site/web/site/tracking?guia=${novedad.guia_novedad}`;
      } else if (novedad.guia_novedad.includes("SPD")) {
        transportadora = "SPEED";
        ruta_traking = ``;
      } else {
        transportadora = "SERVIENTREGA";
        ruta_traking = `https://www.servientrega.com.ec/Tracking/?guia=${novedad.guia_novedad}&tipo=GUIA`;
      }

      let boton_gestionar = "";
      let boton_ver_detalle = `<div><button onclick="initDataTableNovedadesGestionadas('${novedad.guia_novedad}')" class="btn btn-sm btn-outline-primary"> Ver detalle</button></div>`;
      if (novedad.solucionada == 0) {
        if (novedad.terminado == 0) {
          let validar_estado = validar_estado_novedad(
            novedad.guia_novedad,
            novedad.estado_novedad
          );

          if (validar_estado) {
            boton_gestionar = `<button id="downloadExcel" class="btn btn_novedades" onclick="gestionar_novedad('${novedad.guia_novedad}')">Gestionar</button>`;
          } else {
            boton_gestionar = boton_ver_detalle;
          }
        }
      } else {
        boton_gestionar = boton_ver_detalle;
      }

      if (novedad.terminado == 0) {
        content += `
                <tr>
                    <td>${novedad.id_novedad}</td>
                    <td>
                     <div>
                      ${novedad.guia_novedad}
                     </div>
                    </td>
                    <td>${novedad.fecha}</td>
                    <td>${transportadora}</td>
                    <td>${novedad.cliente_novedad}</td>
                    <td>${novedad.novedad}</td>
                    <td>${novedad.estado_novedad}</td>
                    <td>
                    ${boton_gestionar}
                    </td>
                    <td><a href="${ruta_traking}" target="_blank" style="vertical-align: middle;">
                    <img src="https://new.imporsuitpro.com/public/img/tracking.png" width="40px" id="buscar_traking" alt="buscar_traking">
                  </a></td>
                </tr>`;
      }
    });
    document.getElementById("tableBody_novedades").innerHTML = content;
  } catch (ex) {
    alert(ex);
  }
};

window.addEventListener("load", async () => {
  await initDataTableNovedades();
});

function validar_estado_novedad(guia_novedad, estado_novedad) {
  // Array con los estados que deben retornar false
  const estadosInvalidos_laar = [124, 26, 117, 18];
  const estadosInvalidos_gintra = [1, 2, 3, 4, 5, 6, 7, 15, 27];

  // Verificar si guia_novedad contiene "IMP" o "MKP"
  if (guia_novedad.includes("IMP") || guia_novedad.includes("MKP")) {
    if (estadosInvalidos_laar.includes(estado_novedad)) {
      return false;
    }
  } else if (guia_novedad.includes("I00")) {
    if (estadosInvalidos_gintra.includes(estado_novedad)) {
      return false;
    }
  }
  return true;
}

function gestionar_novedad(guia_novedad) {
  Swal.fire({
    title: "¿Ya validó la información con el cliente?",
    text: "Si acepta, volveremos a ofrecer el pedido. De lo contrario, será devuelto al remitente.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Sí, volver a ofrecer",
    cancelButtonText: "No, devolver al remitente",
  }).then((result) => {
    if (result.isConfirmed) {
      // Si el usuario acepta, ejecutar toda la función normalmente
      ejecutarGestionNovedad(guia_novedad);
      Swal.close();
    } else if (result.dismiss === Swal.DismissReason.cancel) {
      // Si el usuario cancela, ejecutar el otro AJAX y terminar
      $.ajax({
        url: SERVERURL + "Pedidos/devolver_novedad/" + guia_novedad,
        type: "POST",
        dataType: "json",
        success: function (response) {
          Swal.fire({
            title: "Pedido devuelto",
            text: "El pedido ha sido devuelto correctamente al remitente.",
            icon: "success",
            timer: 2000,
            showConfirmButton: false,
          });

          initDataTableNovedades();
        },
        error: function (error) {
          Swal.fire({
            title: "Error",
            text: "Hubo un problema al devolver el pedido.",
            icon: "error",
          });
        },
      });
    }
  });
}

// Función que ejecuta toda la lógica original
function ejecutarGestionNovedad(guia_novedad) {
  resetModalInputs("gestionar_novedadModal");
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

        $("#nombre_novedadesServi").val(response.factura[0].nombre);
        $("#ciudad_novedadesServi").val(response.factura[0].ciudad);
        $("#callePrincipal_novedadesServi").val(
          response.factura[0].c_principal
        );
        $("#calleSecundaria_novedadesServi").val(
          response.factura[0].c_secundaria
        );

        $("#referencia_novedadesServi").val(response.factura[0].referencia);
        $("#telefono_novedadesServi").val(response.factura[0].telefono);
        $("#celular_novedadesServi").val(response.factura[0].telefono);

        hiden_laar();
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
      $("#guia_novedad_nodal").text(response.novedad[0].guia_novedad);
      $("#cliente_gestionarNov").text(response.novedad[0].cliente_novedad);
      $("#estado_gestionarNov").text(response.novedad[0].estado_novedad);
      $("#transportadora_gestionarNov").text(transportadora);
      $("#novedad_gestionarNov").text(response.novedad[0].novedad);

      if (response.factura[0].transporte == "LAAR") {
        $("#tracking_gestionarNov").attr(
          "href",
          `https://fenixoper.laarcourier.com/Tracking/Guiacompleta.aspx?guia=${response.novedad[0].guia_novedad}`
        );
      } else if (response.factura[0].transporte == "SERVIENTREGA") {
        $("#tracking_gestionarNov").attr(
          "href",
          `https://www.servientrega.com.ec/Tracking/?guia=${response.novedad[0].guia_novedad}&tipo=GUIA`
        );
      } else if (response.factura[0].transporte == "GINTRACOM") {
        $("#tracking_gestionarNov").attr(
          "href",
          `https://ec.gintracom.site/web/site/tracking?guia=${response.novedad[0].guia_novedad}`
        );
      } else if (response.factura[0].transporte == "SPEED") {
        $("#tracking_gestionarNov").attr("href", ``);
      }

      $("#id_novedad").val(response.novedad[0].id_novedad);
      $("#numero_guia").val(response.novedad[0].guia_novedad);

      $("#gestionar_novedadModal").modal("show");
    },
    error: function (error) {
      console.error("Error al obtener la lista de bodegas:", error);
    },
  });
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

function enviar_gintraNovedad() {
  var button = document.getElementById("boton_gintra");
  button.disabled = true; // Desactivar el botón

  var guia = $("#numero_guia").val();
  var observacion = $("#Solucion_novedad").val();
  var id_novedad = $("#id_novedad").val();
  var tipo = $("#tipo_gintracom").val();
  var estado_novedad = $("#estado_gestionarNov").text();
  var recaudo = "";
  var fecha = "";

  if (tipo == "recaudo") {
    recaudo = $("#Valor_recaudar").val();
  }
  if (tipo !== "rechazar") {
    fecha = $("#datepicker").val();
  }

  // Verificar si la fecha está vacía
  if (!fecha) {
    Swal.fire({
      icon: "warning",
      title: "Fecha requerida",
      text: "Por favor, selecciona una fecha antes de continuar.",
    });
    return; // Detener la ejecución si falta la fecha
  }

  let formData = new FormData();
  formData.append("guia", guia);
  formData.append("observacion", observacion);
  formData.append("id_novedad", id_novedad);
  formData.append("tipo", tipo);
  formData.append("recaudo", recaudo);
  formData.append("fecha", fecha);

  let validador = true;
  if (estado_novedad == 26) {
    validador = validados_numero(observacion);
  }

  if (validador) {
    $.ajax({
      url: SERVERURL + "novedades/solventarNovedadGintracom",
      type: "POST",
      data: formData,
      processData: false, // No procesar los datos
      contentType: false, // No establecer ningún tipo de contenido
      success: function (response) {
        toastr.success("Novedad enviada CORRECTAMENTE", "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });

        $("#gestionar_novedadModal").modal("hide");
        button.disabled = false;
        initDataTableNovedades();
      },
      error: function (jqXHR, textStatus, errorThrown) {
        alert(errorThrown);
        button.disabled = false;
      },
    });
  } else {
    toastr.error(
      "Necesitas registrar un numero de telefono en la novedad",
      "NOTIFICACIÓN",
      {
        positionClass: "toast-bottom-center",
      }
    );

    button.disabled = false;
  }
}

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
      toastr.success("Novedad enviada CORRECTAMENTE", "NOTIFICACIÓN", {
        positionClass: "toast-bottom-center",
      });

      $("#gestionar_novedadModal").modal("hide");
      button.disabled = false;
      initDataTableNovedades();
      /* initDataTableNovedadesGestionadas(); */
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
  var calleSecundaria_novedadesServi = $(
    "#calleSecundaria_novedadesServi"
  ).val();
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
      toastr.success("Novedad enviada CORRECTAMENTE", "NOTIFICACIÓN", {
        positionClass: "toast-bottom-center",
      });

      $("#gestionar_novedadModal").modal("hide");
      button.disabled = false;
      initDataTableNovedades();
      /* initDataTableNovedadesGestionadas(); */
    },
    error: function (jqXHR, textStatus, errorThrown) {
      alert(errorThrown);
      button.disabled = false;
    },
  });
}

let dataTableNovedadesGestionadas;
let dataTableNovedadesGestionadasIsInitialized = false;

const dataTableNovedadesGestionadasOptions = {
  columnDefs: [
    { className: "centered", targets: [0, 1, 2, 3] },
    { orderable: false, targets: 0 }, //ocultar para columna 0 el ordenar columna
  ],
  order: [[1, "desc"]],
  pageLength: 10,
  destroy: true,
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

const initDataTableNovedadesGestionadas = async (guia) => {
  if (dataTableNovedadesGestionadasIsInitialized) {
    dataTableNovedadesGestionadas.destroy();
  }

  await listNovedadesGestionadas(guia);

  dataTableNovedadesGestionadas = $(
    "#datatable_novedades_gestionadas"
  ).DataTable(dataTableNovedadesGestionadasOptions);

  dataTableNovedadesGestionadasIsInitialized = true;
};

const listNovedadesGestionadas = async (guia) => {
  try {
    // Crear el objeto FormData y agregar la variable "guia"
    const formData = new FormData();
    formData.append("guia", guia);

    // Realizar la solicitud a la API con el FormData
    const response = await fetch("" + SERVERURL + "novedades/cargarHistorial", {
      method: "POST", // Usar POST para enviar el FormData
      body: formData,
    });

    // Procesar la respuesta
    const novedadesGestionadas = await response.json();

    // Generar el contenido dinámico para la tabla
    let content = ``;

    novedadesGestionadas.forEach((novedad, index) => {
      content += `
                <tr>
                    <td>${novedad.guia}</td>
                    <td>${novedad.fecha}</td>
                    <td>${novedad.medida}</td>
                    <td>${novedad.nombre_responsable}</td>
                </tr>`;
    });

    // Insertar el contenido generado en el cuerpo de la tabla
    document.getElementById("tableBody_novedades_gestionadas").innerHTML =
      content;

    $("#vista_detalle_novedad").modal("show");
  } catch (ex) {
    alert("Error al cargar las novedades: " + ex);
  }
};

/* window.addEventListener("load", async () => {
  await initDataTableNovedadesGestionadas();
}); */
