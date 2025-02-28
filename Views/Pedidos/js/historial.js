let dataTableHistorial;
let dataTableHistorialIsInitialized = false;

// Opciones de configuración para DataTable
const dataTableHistorialOptions = {
  columnDefs: [
    { className: "centered", targets: [0, 1, 2, 3, 4, 5, 6] },
  ],
  order: [[1, "desc"]],
  pageLength: 10,
  destroy: true,
  responsive: true,
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

// Inicializa la DataTable: carga la data y luego crea la tabla
const initDataTableHistorial = async () => {
  showTableLoader();
  try {
    if (dataTableHistorialIsInitialized) {
      dataTableHistorial.destroy();
    }
    await listHistorialPedidos();
    dataTableHistorial = $("#datatable_historialPedidos").DataTable(dataTableHistorialOptions);
    dataTableHistorialIsInitialized = true;
  } catch (error) {
    console.error("Error al cargar la tabla:", error);
  } finally {
    hideTableLoader();
  }
};

// Realiza la solicitud al endpoint (usando la variable global currentAPI) y construye el contenido de la tabla
const listHistorialPedidos = async () => {
  try {
    const formData = new FormData();
    formData.append("fecha_inicio", fecha_inicio);
    formData.append("fecha_fin", fecha_fin);
    formData.append("estado_pedido", $("#estado_pedido").val());

    const response = await fetch(`${SERVERURL}${currentAPI}`, {
      method: "POST",
      body: formData,
    });

    const historialPedidos = await response.json();
    let content = ``;

    historialPedidos.forEach((historialPedido) => {
      // Opcional: lógica para mostrar contenido según id_transporte (comentada en este ejemplo)
      let transporte_content = "";
      // if (historialPedido.id_transporte == 2) { ... }

      // Construcción del select para cambiar el estado del pedido
      let color_estadoPedido = "";
      switch (historialPedido.estado_pedido) {
        case 1:
          color_estadoPedido = "#ff8301";
          break;
        case 2:
          color_estadoPedido = "#0d6efd";
          break;
        case 3:
          color_estadoPedido = "red";
          break;
        case 4:
        case 5:
        case 6:
          color_estadoPedido = "green";
          break;
        default:
          color_estadoPedido = "";
      }

      let select_estados_pedidos = `
        <select class="form-select select-estado-pedido" style="max-width: 90%; margin-top: 10px; color: white; background:${color_estadoPedido};" data-id-factura="${historialPedido.id_factura}">
          <option value="0" ${historialPedido.estado_pedido == 0 ? "selected" : ""}>-- Selecciona estado --</option>
          <option value="1" ${historialPedido.estado_pedido == 1 ? "selected" : ""}>Pendiente</option>
          <option value="2" ${historialPedido.estado_pedido == 2 ? "selected" : ""}>Gestionado</option>
          <option value="3" ${historialPedido.estado_pedido == 3 ? "selected" : ""}>No desea</option>
          <option value="4" ${historialPedido.estado_pedido == 4 ? "selected" : ""}>1ra llamada</option>
          <option value="5" ${historialPedido.estado_pedido == 5 ? "selected" : ""}>2da llamada</option>
          <option value="6" ${historialPedido.estado_pedido == 6 ? "selected" : ""}>Observación</option>
        </select>`;

      if (historialPedido.estado_pedido == 3) {
        select_estados_pedidos += `<span>${historialPedido.detalle_noDesea_pedido}</span>`;
      } else if (historialPedido.estado_pedido == 6) {
        select_estados_pedidos += `<span>${historialPedido.observacion_pedido}</span>`;
      }

      // Procesar ciudad: se toma la parte anterior a la barra ("/")
      let ciudad = "";
      if (historialPedido.ciudad) {
        let ciudadArray = historialPedido.ciudad.split("/");
        ciudad = ciudadArray[0];
      }

      // Procesar plataforma y proveedor
      let plataforma = "";
      if (historialPedido.plataforma) {
        plataforma = procesarPlataforma(historialPedido.plataforma);
      }
      let plataforma_proveedor = obtenerSubdominio(historialPedido.plataforma_proveedor);

      // Determinar canal de venta y factura
      let canal_venta = "";
      let numero_orden_shopify = "";
      let factura = historialPedido.numero_factura;
      if (historialPedido.importado == 0) {
        canal_venta = "manual";
      } else if (historialPedido.plataforma_importa == "Funnelish") {
        canal_venta = "Funnelish";
      } else if (historialPedido.plataforma_importa == "Shopify") {
        canal_venta = "Shopify";
        let partes = historialPedido.comentario.split("número de orden: ");
        numero_orden_shopify = partes.length > 1 ? partes[1].trim() : null;
        factura = numero_orden_shopify;
      }

      // Botón para automatizar (si está habilitado)
      let boton_automatizador = "";
      if (VALIDAR_CONFIG_CHAT && historialPedido.automatizar_ws == 0) {
        boton_automatizador = `<button class="btn btn-sm btn-success" onclick="enviar_mensaje_automatizador(
          ${historialPedido.id_factura},
          '${historialPedido.ciudad_cot}',
          '${historialPedido.celular}',
          '${historialPedido.nombre}',
          '${historialPedido.c_principal}',
          '${historialPedido.c_secundaria}',
          '${historialPedido.contiene}',
          ${historialPedido.monto_factura}
        )"><i class="fa-brands fa-whatsapp"></i></button>`;
      }

      // Acciones según el endpoint que se esté utilizando
      let acciones = "";
      if (currentAPI == "pedidos/cargarPedidos_imporsuit") {
        acciones = `
          <button class="btn btn-sm btn-primary" onclick="boton_editarPedido(${historialPedido.id_factura})"><i class="fa-solid fa-pencil"></i></button>
          <button class="btn btn-sm btn-danger" onclick="boton_anularPedido(${historialPedido.id_factura})"><i class="fa-solid fa-trash-can"></i></button>
          ${boton_automatizador}`;
      } else if (currentAPI == "pedidos/cargar_pedidos_sin_producto") {
        acciones = `
          <button class="btn btn-sm btn-primary" onclick="boton_vista_anadir_sin_producto(${historialPedido.id_factura})"><i class="fa-solid fa-pencil"></i></button>
          ${boton_automatizador}`;
      }

      // Construir la fila de la tabla
      content += `
        <tr>
          <td>${factura}</td>
          <td>${historialPedido.fecha_factura}</td>
          <td>${canal_venta}</td>
          <td>
            <div><strong>${historialPedido.nombre}</strong></div>
            <div>telf: ${historialPedido.telefono}</div>
          </td>
          <td>
            <div>${historialPedido.c_principal} - ${historialPedido.c_secundaria}</div>
            <div>${historialPedido.provinciaa} - ${ciudad}</div>
          </td>
          <td>
            <div><strong>${plataforma_proveedor}</strong></div>
            <div>${historialPedido.contiene}</div>
          </td>
          <td>$ ${historialPedido.monto_factura}</td>
          <td>
            <div style="text-align: -webkit-center;">
              ${transporte_content}
              ${select_estados_pedidos}
            </div>
          </td>
          <td>
            ${acciones}
          </td>
        </tr>`;
    });
    document.getElementById("tableBody_historialPedidos").innerHTML = content;
  } catch (ex) {
    alert(ex);
  }
};

// Al cargar la página, inicializa la tabla y asigna el evento para el botón de filtros
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

// Funciones para mostrar y ocultar el loader de la tabla
function showTableLoader() {
  $("#tableLoader")
    .html('<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Cargando...</span></div>')
    .css("display", "flex");
}

function hideTableLoader() {
  $("#tableLoader").css("display", "none");
}

// Asigna los eventos a los botones para cambiar de API
document.getElementById("btnPedidos").addEventListener("click", () => {
  currentAPI = "pedidos/cargarPedidos_imporsuit";
  cambiarBotonActivo("btnPedidos");
  initDataTableHistorial();
});

document.getElementById("btnAnulados").addEventListener("click", () => {
  currentAPI = "pedidos/cargarPedidosAnulados";
  cambiarBotonActivo("btnAnulados");
  initDataTableHistorial();
});

document.getElementById("btnNo_vinculados").addEventListener("click", () => {
  currentAPI = "pedidos/cargar_pedidos_sin_producto";
  cambiarBotonActivo("btnNo_vinculados");
  initDataTableHistorial();
});

// Cambia la clase activa en los botones según el seleccionado
const cambiarBotonActivo = (botonID) => {
  document.querySelectorAll(".d-flex button").forEach((btn) => {
    btn.classList.remove("active", "btn-primary");
    btn.classList.add("btn-secondary");
  });
  const botonActivo = document.getElementById(botonID);
  botonActivo.classList.remove("btn-secondary");
  botonActivo.classList.add("btn-primary", "active");
};

// Event delegation: maneja el cambio en el select para actualizar el estado del pedido
document.addEventListener("change", async (event) => {
  if (event.target && event.target.classList.contains("select-estado-pedido")) {
    const idFactura = event.target.getAttribute("data-id-factura");
    const nuevoEstado = event.target.value;
    const formData = new FormData();
    formData.append("id_factura", idFactura);
    formData.append("estado_nuevo", nuevoEstado);
    formData.append("detalle_noDesea_pedido", "");

    try {
      const response = await fetch(SERVERURL + `Pedidos/cambiar_estado_pedido`, {
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
        initDataTableHistorial();
      }
    } catch (error) {
      console.error("Error al conectar con la API", error);
      alert("Error al conectar con la API");
    }
  }
});

// Funciones auxiliares
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

function obtenerSubdominio(urlString) {
  if (!urlString) return "";
  try {
    let url = new URL(urlString);
    return url.hostname.split(".")[0];
  } catch (error) {
    console.error("URL inválida:", urlString);
    return "";
  }
}

function procesarPlataforma(url) {
  let sinProtocolo = url.replace("https://", "");
  let primerPunto = sinProtocolo.indexOf(".");
  let baseNombre = sinProtocolo.substring(0, primerPunto);
  return baseNombre.toUpperCase();
}

function boton_editarPedido(id) {
  window.location.href = SERVERURL + "Pedidos/editar/" + id;
}

function boton_vista_anadir_sin_producto(id) {
  window.location.href = SERVERURL + "Pedidos/vista_anadir_sin_producto/" + id;
}

function boton_anularPedido(id_factura) {
  $.ajax({
    type: "POST",
    url: SERVERURL + "Pedidos/eliminarPedido/" + id_factura,
    dataType: "json",
    success: function (response) {
      if (response.status == 500) {
        toastr.error("NO SE ELIMINO CORRECTAMENTE", "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });
      } else if (response.status == 200) {
        toastr.success("ELIMINADO CORRECTAMENTE", "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });
        initDataTableHistorial();
      }
    },
    error: function (xhr, status, error) {
      console.error("Error en la solicitud AJAX:", error);
      alert("Hubo un problema al elimnar pedido");
    },
  });
}

function enviar_mensaje_automatizador(nueva_factura, ciudad_cot, celular, nombre, c_principal, c_secundaria, contiene, monto_factura) {
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
    return "+593" + number;
  }
}
