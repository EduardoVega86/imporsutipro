let dataTableHistorial;
let dataTableHistorialIsInitialized = false;

const dataTableHistorialOptions = {
  columnDefs: [
    { className: "centered", targets: [0, 1, 2, 3, 4, 5, 6] },
  ],
  order: [[1, "desc"]], // Ordenar por la columna Fecha (2da col) en orden descendente
  pageLength: 10,
  dom: '<"top"l>rt<"bottom"ip><"clear">', // Sin el input de búsqueda integrado de DataTables
  destroy: true,
  responsive: true,
  language: {
    lengthMenu: "Mostrar _MENU_ registros por página",
    zeroRecords: "Ningún usuario encontrado",
    info: "Mostrando de _START_ a _END_ de un total de _TOTAL_ registros",
    infoEmpty: "Ningún usuario encontrado",
    infoFiltered: "(filtrados desde _MAX_ registros totales)",
    loadingRecords: "Cargando...",
    paginate: {
      first: "Primero",
      last: "Último",
      next: "Siguiente",
      previous: "Anterior",
    },
  },
};

// Variables para controlar la inicialización del DataTable
let fecha_inicio = "";
let fecha_fin = "";

/* ======================================
   INICIALIZAR DATATABLE
====================================== */
const initDataTableHistorial = async () => {
  showTableLoader();
  try {
    // Si ya estaba inicializado, destruirlo antes
    if (dataTableHistorialIsInitialized) {
      dataTableHistorial.destroy();
    }

    // Cargar datos desde el servidor y dibujar en la tabla
    await listHistorialPedidos();

    // Inicializar DataTable con las opciones definidas
    dataTableHistorial = $("#datatable_historialPedidos").DataTable(
      dataTableHistorialOptions
    );

    dataTableHistorialIsInitialized = true;
  } catch (error) {
    console.error("Error al cargar la tabla:", error);
  } finally {
    hideTableLoader();
  }
};

/* ======================================
   LISTAR PEDIDOS (Cargar del Servidor)
====================================== */
const listHistorialPedidos = async () => {
  try {
    // Preparar los datos a enviar al backend
    const formData = new FormData();
    formData.append("fecha_inicio", fecha_inicio);
    formData.append("fecha_fin", fecha_fin);
    formData.append("estado_pedido", $("#estado_pedido").val());

    // Capturar el valor de búsqueda (input #buscar_pedido)
    let buscar_pedido = $("#buscar_pedido").val().trim();
    formData.append("buscar_pedido", buscar_pedido);

    const response = await fetch(`${SERVERURL}${currentAPI}`, {
      method: "POST",
      body: formData,
    });

    const historialPedidos = await response.json(); // Respuesta JSON del servidor

    let content = ``;

    // Función para procesar los pedidos y generar las filas
    const processPedidos = (pedidos) => {
      if (Array.isArray(pedidos)) {
        pedidos.forEach((historialPedido) => {
          // Color de fondo según el estado
          let colorEstado = "#ccc";
          switch (historialPedido.estado_pedido) {
            case '1': colorEstado = "#ff8301"; break; // Pendiente
            case '2': colorEstado = "#0d6efd"; break; // Gestionado
            case '3': colorEstado = "red";      break; // No desea
            case '4': colorEstado = "green";    break; // 1ra llamada
            case '5': colorEstado = "green";    break; // 2da llamada
            case '6': colorEstado = "green";    break; // Observación
            case '7': colorEstado = "red";      break; // Anulado
            default:  colorEstado = "#ccc";     break; 
          }

          // Generar el <select> de estados
          // No usamos "disabled" para que al elegir "Anulado (7)" también abra el modal
          let selectEstados = `
            <select class="form-select select-estado-pedido"
                    style="max-width: 90%; margin-top: 10px; color: white; background:${colorEstado};"
                    data-id-factura="${historialPedido.id_factura}">
              <option value="0" ${historialPedido.estado_pedido == 0 ? "selected" : ""}>-- Selecciona estado --</option>
              <option value="1" ${historialPedido.estado_pedido == 1 ? "selected" : ""}>Pendiente</option>
              <option value="2" ${historialPedido.estado_pedido == 2 ? "selected" : ""}>Gestionado</option>
              <option value="3" ${historialPedido.estado_pedido == 3 ? "selected" : ""}>No desea</option>
              <option value="4" ${historialPedido.estado_pedido == 4 ? "selected" : ""}>1ra llamada</option>
              <option value="5" ${historialPedido.estado_pedido == 5 ? "selected" : ""}>2da llamada</option>
              <option value="6" ${historialPedido.estado_pedido == 6 ? "selected" : ""}>Observación</option>
              <option value="7" ${historialPedido.estado_pedido == 7 ? "selected" : ""}>Anulado</option>
            </select>`;

          // Añadir los textos de motivo si existen
          // (Igual que "No desea" y "Observación", ahora para "Anulado")
          if (historialPedido.estado_pedido == 3 && historialPedido.detalle_noDesea_pedido) {
            selectEstados += `<div style="margin-top:5px;"><strong>Motivo:</strong> ${historialPedido.detalle_noDesea_pedido}</div>`;
          }
          if (historialPedido.estado_pedido == 6 && historialPedido.observacion_pedido) {
            selectEstados += `<div style="margin-top:5px;"><strong>Obs:</strong> ${historialPedido.observacion_pedido}</div>`;
          }
          if (historialPedido.estado_pedido == 7 && historialPedido.motivo_anulado_pedido) {
            selectEstados += `<div style="margin-top:5px; color:red;"><strong>Anulado:</strong> ${historialPedido.motivo_anulado_pedido}</div>`;
          }

          // Botón de WhatsApp (si corresponde)
          let botonAutomatizador = "";
          if (VALIDAR_CONFIG_CHAT && historialPedido.automatizar_ws == 0) {
            botonAutomatizador = `
              <button class="btn btn-sm btn-success" onclick="enviar_mensaje_automatizador(
                ${historialPedido.id_factura},
                '${historialPedido.ciudad_cot}',
                '${historialPedido.celular}',
                '${historialPedido.nombre}',
                '${historialPedido.c_principal}',
                '${historialPedido.c_secundaria}',
                '${historialPedido.contiene}',
                ${historialPedido.monto_factura}
              )">
                <i class="fa-brands fa-whatsapp"></i>
              </button>`;
          }

          // Construir la fila
          content += `
            <tr>
              <td>${historialPedido.numero_factura}</td>
              <td>${historialPedido.fecha_factura}</td>
              <td>${historialPedido.plataforma_importa}</td>
              <td>
                <strong>${historialPedido.nombre}</strong><br>
                telf: ${historialPedido.telefono}
              </td>
              <td>
                ${historialPedido.c_principal} - ${historialPedido.c_secundaria}
                <br>
                ${historialPedido.provinciaa}-${historialPedido.ciudad_cot}
              </td>
              <td>${historialPedido.contiene}</td>
              <td>$${parseFloat(historialPedido.monto_factura).toFixed(2)}</td>
              <td>${selectEstados}</td>
              <td>
                ${botonAutomatizador}
                <button class="btn btn-sm btn-primary" onclick="boton_editarPedido(${historialPedido.id_factura})">
                  <i class="fa-solid fa-pencil"></i>
                </button>
              </td>
            </tr>`;
        });
      }
    };

    processPedidos(historialPedidos);

    // Reemplazar el contenido del tbody (si existe)
    const tableBody = document.getElementById("tableBody_historialPedidos");
    if (!tableBody) {
      console.warn("No se encontró 'tableBody_historialPedidos' en el DOM.");
      return;
    }

    tableBody.innerHTML = content;

  } catch (ex) {
    alert(ex);
  }
};

/* ======================================
   DOMContentLoaded
====================================== */
document.addEventListener("DOMContentLoaded", async () => {
  const btnAplicar = document.getElementById("btnAplicarFiltros");
  if (btnAplicar) {
    btnAplicar.addEventListener("click", async function () {
      let rangoFechas = $("#daterange").val();
      if (rangoFechas) {
        let fechas = rangoFechas.split(" - ");
        fecha_inicio = fechas[0] + " 00:00:00";
        fecha_fin = fechas[1] + " 23:59:59";
      }
      await initDataTableHistorial();
      cargarCardsPedidos();
    });
  }
});

/* ======================================
   BÚSQUEDA EN CLIENTE (DataTables)
====================================== */
$("#buscar_pedido").on("keyup", function () {
  let searchTerm = $(this).val();
  if (dataTableHistorial) {
    dataTableHistorial.search(searchTerm).draw();
  }
});

/* ======================================
   WINDOW.LOAD
====================================== */
window.addEventListener("load", async () => {
  await initDataTableHistorial();

  const btnAplicar = document.getElementById("btnAplicarFiltros");
  if (btnAplicar) {
    btnAplicar.addEventListener("click", async function () {
      let rangoFechas = $("#daterange").val();
      if (rangoFechas) {
        let fechas = rangoFechas.split(" - ");
        fecha_inicio = fechas[0] + " 00:00:00";
        fecha_fin = fechas[1] + " 23:59:59";
      }
      await initDataTableHistorial();
      cargarCardsPedidos();
    });
  }
});

/* ======================================
   FUNCIONES DE LOADER
====================================== */
function showTableLoader() {
  $("#tableLoader")
    .html('<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Cargando...</span></div>')
    .css("display", "flex");
}

function hideTableLoader() {
  $("#tableLoader").css("display", "none");
}

/* ======================================
   BOTONES DE PLATAFORMAS / ESTADOS
====================================== */
const cambiarBotonActivo = (botonID) => {
  document.querySelectorAll(".d-flex button").forEach((btn) => {
    btn.classList.remove("active", "btn-primary");
    btn.classList.add("btn-secondary");
  });
  const botonActivo = document.getElementById(botonID);
  botonActivo.classList.remove("btn-secondary");
  botonActivo.classList.add("btn-primary", "active");
};

/* ======================================
   EVENTO CHANGE PARA SELECT-ESTADO
   (No desea, Observación, Anulado, etc.)
====================================== */
document.addEventListener("change", async (event) => {
  if (event.target && event.target.classList.contains("select-estado-pedido")) {
    const idFactura = event.target.getAttribute("data-id-factura");
    const nuevoEstado = event.target.value;

    // Enviar el nuevo estado al servidor
    const formData = new FormData();
    formData.append("id_factura", idFactura);
    formData.append("estado_nuevo", nuevoEstado);
    formData.append("detalle_noDesea_pedido", "");

    try {
      const response = await fetch(`${SERVERURL}Pedidos/cambiar_estado_pedido`, {
        method: "POST",
        body: formData,
      });
      const result = await response.json();

      if (result.status == 200) {
        toastr.success("ESTADO ACTUALIZADO CORRECTAMENTE", "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });

        if (nuevoEstado == 3) {
          $("#id_factura_ingresar_motivo").val(idFactura);

          $("#ingresar_nodDesea_pedidoModal").modal("show");
        }

        if (nuevoEstado == 6) {
          $("#id_factura_ingresar_observacion").val(idFactura);

          $("#ingresar_observacion_pedidoModal").modal("show");
        }

        // Recargar la tabla para mostrar el cambio de estado
        initDataTableHistorial();
      }
    } catch (error) {
      console.error("Error al conectar con la API", error);
      alert("Error al conectar con la API");
    }
  }
});

/* ======================================
   OTRAS FUNCIONES
====================================== */
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

function boton_editarPedido(id) {
  window.location.href = SERVERURL + "Pedidos/editar/" + id;
}

async function eliminarPedido(idFactura) {
  try {
    const response = await fetch(`${SERVERURL}Pedidos/eliminarPedido/${idFactura}`, {
      method: "GET",
    });
    const result = await response.json();

    if (result.status == 200) {
      toastr.success("PEDIDO ELIMINADO CORRECTAMENTE", "NOTIFICACIÓN", {
        positionClass: "toast-bottom-center",
      });
      const tableBody = document.getElementById("tableBody_historialPedidos");
      if (tableBody) {
        await initDataTableHistorial();
      } else {
        console.error("El elemento de la tabla no fue encontrado");
      }
    } else {
      toastr.error("No se pudo eliminar el pedido", "NOTIFICACIÓN", {
        positionClass: "toast-bottom-center",
      });
    }
  } catch (error) {
    console.error("Error al eliminar el pedido", error);
    toastr.error("Hubo un error al eliminar el pedido", "NOTIFICACIÓN", {
      positionClass: "toast-bottom-center",
    });
  }
}

function enviar_mensaje_automatizador(
  nueva_factura,
  ciudad_cot,
  celular,
  nombre,
  c_principal,
  c_secundaria,
  contiene,
  monto_factura
) {
  let formData = new FormData();
  formData.append("nueva_factura", nueva_factura);
  formData.append("ciudad_cot", ciudad_cot);
  formData.append("celular", celular);
  formData.append("nombre", nombre);
  formData.append("c_principal", c_principal);
  formData.append("c_secundaria", c_secundaria);
  formData.append("contiene", contiene);
  formData.append("monto_factura", monto_factura);

  $.ajax({
    url: SERVERURL + "pedidos/enviar_mensaje_automatizador",
    type: "POST",
    data: formData,
    processData: false,
    contentType: false,
    dataType: "json",
    success: function (response) {
      if (response.status == 500) {
        toastr.error("NO SE ENVIO CORRECTAMENTE", "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });
      } else if (response.status == 200) {
        toastr.success("ENVIADO CORRECTAMENTE", "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });
        initDataTableHistorial();
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      alert(errorThrown);
    },
  });
}

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
