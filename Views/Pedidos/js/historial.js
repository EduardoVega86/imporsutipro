let dataTableHistorial;
let dataTableHistorialIsInitialized = false;
let filtro = 1; // 1: Pedidos | 2: Abandonados (Valor inicial: Pedidos)

const dataTableHistorialOptions = {
  //scrollX: "2000px",
  /* lengthMenu: [5, 10, 15, 20, 100, 200, 500], */
  columnDefs: [
    { className: "centered", targets: [0, 1, 2, 3, 4, 5, 6] },
    /* { orderable: false, targets: [5, 6] }, */
    /* { searchable: false, targets: [1] } */
    //{ width: "50%", targets: [0] }
  ],
  order: [[1, "desc"]], // Ordenar por la primera columna (fecha) en orden descendente
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

const initDataTableHistorial = async () => {
  if (dataTableHistorialIsInitialized) {
    dataTableHistorial.destroy();
  }

  await listHistorialPedidos();

  dataTableHistorial = $("#datatable_historialPedidos").DataTable(
    dataTableHistorialOptions
  );

  dataTableHistorialIsInitialized = true;
};

const listHistorialPedidos = async () => {
  try {
    const formData = new FormData();
    formData.append("fecha_inicio", fecha_inicio);
    formData.append("fecha_fin", fecha_fin);
    formData.append("filtro", filtro); // 🔹 Se envía el filtro a la API (1 o 2)

    const response = await fetch(
      `${SERVERURL}pedidos/cargarPedidos_imporsuit`,
      {
        method: "POST",
        body: formData,
      }
    );

    const historialPedidos = await response.json();

    let content = ``;
    historialPedidos.forEach((historialPedido, index) => {
      let transporte = historialPedido.id_transporte;
      console.log(transporte);
      let transporte_content = "";
      /* if (transporte == 2) {
        transporte_content =
          '<span text-nowrap style="background-color: #28C839; color: white; padding: 5px; border-radius: 0.3rem;">SERVIENTREGA</span>';
      } else if (transporte == 1) {
        transporte_content =
          '<span text-nowrap style="background-color: #E3BC1C; color: white; padding: 5px; border-radius: 0.3rem;">LAAR</span>';
      } else if (transporte == 4) {
        transporte_content =
          '<span text-nowrap style="background-color: red; color: white; padding: 5px; border-radius: 0.3rem;">SPEED</span>';
      } else if (transporte == 3) {
        transporte_content =
          '<span text-nowrap style="background-color: red; color: white; padding: 5px; border-radius: 0.3rem;">GINTRACOM</span>';
      } else if (transporte == 0) {
      transporte_content =
        '<span text-nowrap style="background-color: #E3BC1C; color: white; padding: 5px; border-radius: 0.3rem;">Guia no enviada</span>';
            } */

      let select_estados_pedidos = "";

      color_estadoPedido = "";

      if (historialPedido.estado_pedido == 1) {
        color_estadoPedido = "#ff8301";
      } else if (historialPedido.estado_pedido == 2) {
        color_estadoPedido = "#0d6efd";
      } else if (historialPedido.estado_pedido == 3) {
        color_estadoPedido = "red";
      } else if (historialPedido.estado_pedido == 4) {
        color_estadoPedido = "green";
      } else if (historialPedido.estado_pedido == 5) {
        color_estadoPedido = "green";
      } else if (historialPedido.estado_pedido == 6) {
        color_estadoPedido = "green";
      }

      select_estados_pedidos = `
                    <select class="form-select select-estado-pedido" style="max-width: 90%; margin-top: 10px; color: white; background:${color_estadoPedido} ;" data-id-factura="${
        historialPedido.id_factura
      }">
                        <option value="0" ${
                          historialPedido.estado_pedido == 0 ? "selected" : ""
                        }>-- Selecciona estado --</option>
                        <option value="1" ${
                          historialPedido.estado_pedido == 1 ? "selected" : ""
                        }>Pendiente</option>
                        <option value="2" ${
                          historialPedido.estado_pedido == 2 ? "selected" : ""
                        }>Gestionado</option>
                        <option value="3" ${
                          historialPedido.estado_pedido == 3 ? "selected" : ""
                        }>No desea</option>
                        <option value="4" ${
                          historialPedido.estado_pedido == 4 ? "selected" : ""
                        }>1ra llamada</option>
                        <option value="5" ${
                          historialPedido.estado_pedido == 5 ? "selected" : ""
                        }>2da llamada</option>
                        <option value="6" ${
                          historialPedido.estado_pedido == 6 ? "selected" : ""
                        }>Observación</option>
                    </select>`;

      //tomar solo la ciudad

      let boton_automatizador = "";

      if (
        ID_PLATAFORMA == 1251 ||
        ID_PLATAFORMA == 1206 ||
        ID_PLATAFORMA == 2293
      ) {
        if (historialPedido.automatizar_ws == 0){
        boton_automatizador = `<button class="btn btn-sm btn-success" onclick="enviar_mensaje_automatizador(
          ${historialPedido.id_factura},
          '${historialPedido.ciudad_cot}', // Si es string, ponlo entre comillas
          '${historialPedido.celular}', // Lo mismo aquí si es string
          '${historialPedido.nombre}',
          '${historialPedido.c_principal}',
          '${historialPedido.c_secundaria}',
          '${historialPedido.contiene}',
          ${historialPedido.monto_factura} // Si es número, no necesita comillas
          )"><i class="fa-brands fa-whatsapp"></i></button>`;
        }
      }

      if (historialPedido.estado_pedido == 3) {
        select_estados_pedidos += `<span>${historialPedido.detalle_noDesea_pedido}</span>`;
      }

      let ciudadCompleta = historialPedido.ciudad;
      let ciudad = "";
      if (ciudadCompleta !== null) {
        let ciudadArray = ciudadCompleta.split("/");
        ciudad = ciudadArray[0];
      }

      let plataforma = "";
      if (
        historialPedido.plataforma == "" ||
        historialPedido.plataforma == null
      ) {
        plataforma = "";
      } else {
        plataforma = procesarPlataforma(historialPedido.plataforma);
      }

      let plataforma_proveedor = obtenerSubdominio(
        historialPedido.plataforma_proveedor
      );

      let canal_venta;
      let color_canal_venta;
      let numero_orden_shopify = "";

      let factura = historialPedido.numero_factura;

      if (historialPedido.importado == 0) {
        canal_venta = "manual";
        color_canal_venta = "red";
      } else if (historialPedido.plataforma_importa == "Funnelish") {
        canal_venta = "Funnelish";
        color_canal_venta = "#5e81f4";
      } else if (historialPedido.plataforma_importa == "Shopify") {
        canal_venta = "Shopify";
        color_canal_venta = "#79b258";

        let comentario = historialPedido.comentario;

        // Dividir la cadena en partes usando "número de orden: "
        let partes = comentario.split("número de orden: ");

        // Si se encontró la frase, tomar la segunda parte y limpiar espacios
        numero_orden_shopify = partes.length > 1 ? partes[1].trim() : null;

        factura = numero_orden_shopify;
      }

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
                    <div>${historialPedido.provinciaa}-${ciudad}</div>
                    </td>
                    <td>
                    <div>
                    <strong>${plataforma_proveedor}</strong>
                    </div>
                    <div>
                    ${historialPedido.contiene}
                    </div>
                    </td>
                    <td>$ ${historialPedido.monto_factura}</td>
                    <td>
                    <div style = "text-align: -webkit-center;">
                    ${transporte_content}
                    ${select_estados_pedidos}
                    </div>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-primary" onclick="boton_editarPedido(${historialPedido.id_factura})"><i class="fa-solid fa-pencil"></i></button>
                        <button class="btn btn-sm btn-danger" onclick="boton_anularPedido(${historialPedido.id_factura})"><i class="fa-solid fa-trash-can"></i></button>
                        ${boton_automatizador}
                    </td>
                </tr>`;
    });
    document.getElementById("tableBody_historialPedidos").innerHTML = content;
  } catch (ex) {
    alert(ex);
  }
};

// 🚀 **Evento para cambiar entre Pedidos y Abandonados**
document.getElementById("btnPedidos").addEventListener("click", () => {
  filtro = 1; // Se establece como "Pedidos"
  actualizarBotones();
  initDataTableHistorial();
});

document.getElementById("btnAbandonados").addEventListener("click", () => {
  filtro = 2; // Se establece como "Abandonados"
  actualizarBotones();
  initDataTableHistorial();
});

const actualizarBotones = () => {
  if (filtro === 1) {
    document.getElementById("btnPedidos").classList.add("active", "btn-primary");
    document.getElementById("btnPedidos").classList.remove("btn-secondary");

    document.getElementById("btnAbandonados").classList.remove("active", "btn-primary");
    document.getElementById("btnAbandonados").classList.add("btn-secondary");
  } else {
    document.getElementById("btnAbandonados").classList.add("active", "btn-primary");
    document.getElementById("btnAbandonados").classList.remove("btn-secondary");

    document.getElementById("btnPedidos").classList.remove("active", "btn-primary");
    document.getElementById("btnPedidos").classList.add("btn-secondary");
  }
};

// Fin 🚀 **Evento para cambiar entre Pedidos y Abandonados**

// Event delegation for select change
document.addEventListener("change", async (event) => {
  if (event.target && event.target.classList.contains("select-estado-pedido")) {
    const idFactura = event.target.getAttribute("data-id-factura");
    const nuevoEstado = event.target.value;
    const formData = new FormData();
    formData.append("id_factura", idFactura);
    formData.append("estado_nuevo", nuevoEstado);
    formData.append("detalle_noDesea_pedido", "");

    try {
      const response = await fetch(
        SERVERURL + `Pedidos/cambiar_estado_pedido`,
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

function obtenerSubdominio(urlString) {
  // Verificar si urlString es nulo, indefinido o vacío
  if (!urlString) {
    return ""; // Devolver cadena vacía si no hay valor
  }

  try {
    // Crear un objeto URL y descomponer el hostname
    let url = new URL(urlString);
    return url.hostname.split(".")[0]; // Devolver el subdominio
  } catch (error) {
    console.error("URL inválida:", urlString);
    return ""; // Devolver cadena vacía si la URL es inválida
  }
}

function procesarPlataforma(url) {
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

function boton_editarPedido(id) {
  window.location.href = "" + SERVERURL + "Pedidos/editar/" + id;
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
    processData: false, // No procesar los datos
    contentType: false, // No establecer ningún tipo de contenido
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

window.addEventListener("load", async () => {
  await initDataTableHistorial();
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
