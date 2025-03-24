let dataTableHistorial;
let dataTableHistorialIsInitialized = false;
let fecha_inicio = "";
let fecha_fin = "";
let currentAPI = "";

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
  dom: '<"top"l>rt<"bottom"ip><"clear">', // Eliminamos 'f' para quitar el input de búsqueda de DataTables
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

const initDataTableHistorial = async () => {
  showTableLoader();
  try {
    if (dataTableHistorialIsInitialized) {
      dataTableHistorial.destroy();
    }

    await listHistorialPedidos();

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

const listHistorialPedidos = async () => {
  try {
    const formData = new FormData();
    formData.append("fecha_inicio", fecha_inicio);
    formData.append("fecha_fin", fecha_fin);
    formData.append("estado_pedido", $("#estado_pedido").val());

    //Obtener el valor del campo búsqueda
    let buscar_pedido = $("#buscar_pedido").val().trim();
    formData.append("buscar_pedido", buscar_pedido); //agreamos al request

    const response = await fetch(`${SERVERURL}${currentAPI}`, {
      method: "POST",
      body: formData,
    });

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
      } else if (historialPedido.estado_pedido == 7) {
        color_estadoPedido = "red";
      }

      let disabled = (historialPedido.estado_pedido == 7) ? "disabled" : "";

      select_estados_pedidos = `
                    <select class="form-select select-estado-pedido" style="max-width: 90%; margin-top: 10px; color: white; background:${color_estadoPedido} ;" data-id-factura="${
        historialPedido.id_factura}" ${disabled}>
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
                        <option value="7" ${
                          historialPedido.estado_pedido == 7 ? "selected" : ""
                        }>Anulado</option>
                    </select>`;

      //tomar solo la ciudad

      let boton_automatizador = "";

      if (VALIDAR_CONFIG_CHAT) {
        if (historialPedido.automatizar_ws == 0) {
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
      } else if (historialPedido.estado_pedido == 6) {
        select_estados_pedidos += `<span>${historialPedido.observacion_pedido}</span>`;
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

      let acciones = "";
      if (currentAPI == "pedidos/cargarTodosLosPedidos") {
        acciones = `
          <button class="btn btn-sm btn-primary" onclick="boton_editarPedido(${historialPedido.id_factura})"><i class="fa-solid fa-pencil"></i></button>
          ${boton_automatizador}`;
      } else if (currentAPI == "pedidos/cargar_pedidos_sin_producto") {
        acciones = `
          <button class="btn btn-sm btn-primary" onclick="boton_vista_anadir_sin_producto(${historialPedido.id_factura})"><i class="fa-solid fa-pencil"></i></button>
          ${boton_automatizador}`;
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
                        ${acciones}
                    </td>
                </tr>`;
    });
    document.getElementById("tableBody_historialPedidos").innerHTML = content;
  } catch (ex) {
    alert(ex);
  }
};

// Capturar evento en el input de búsqueda
$("#buscar_pedido").on("keyup", function () {
  let searchTerm = $(this).val();
  if (dataTableHistorial) {
    dataTableHistorial.search(searchTerm).draw();
  }
});

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

//Cargando
function showTableLoader() {
  // Inserta siempre el HTML del spinner y luego muestra el contenedor
  $("#tableLoader")
    .html(
      '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Cargando...</span></div>'
    )
    .css("display", "flex");
}

function hideTableLoader() {
  $("#tableLoader").css("display", "none");
}

// Manejo de botones para cambiar API y recargar la tabla
document.getElementById("btnPedidos").addEventListener("click", () => {
  currentAPI = "pedidos/cargarTodosLosPedidos";
  cambiarBotonActivo("btnPedidos");
  initDataTableHistorial();
});

document.getElementById("btnAnulados").addEventListener("click", () => {
  currentAPI = "pedidos/cargarPedidosAnulados"; // Nuevo endpoint para pedidos anulados
  cambiarBotonActivo("btnAnulados");
  initDataTableHistorial();
});


/* document.getElementById("btnAbandonados").addEventListener("click", () => {
  currentAPI = "pedidos/cargar_pedidos_abandonados"; // Ajusta la API correspondiente
  cambiarBotonActivo("btnAbandonados");
  initDataTableHistorial();
}); */

document.getElementById("btnNo_vinculados").addEventListener("click", () => {
  currentAPI = "pedidos/cargar_pedidos_sin_producto";
  cambiarBotonActivo("btnNo_vinculados");
  initDataTableHistorial();
});

const cambiarBotonActivo = (botonID) => {
  document.querySelectorAll(".d-flex button").forEach((btn) => {
    btn.classList.remove("active", "btn-primary");
    btn.classList.add("btn-secondary"); // Agregar btn-secondary a todos
  });

  const botonActivo = document.getElementById(botonID);
  botonActivo.classList.remove("btn-secondary"); // Quitar secundario al botón activo
  botonActivo.classList.add("btn-primary", "active"); // Agregar primario y activo
};

// Fin Manejo de botones para cambiar API y recargar la tabla

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
        // Estado 7 = anulado => llamamos a eliminarPedido
        if (nuevoEstado == 7) {
          await eliminarPedido(idFactura);
          return; // Sales de la función para evitar la recarga extra
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

function boton_vista_anadir_sin_producto(id) {
  window.location.href =
    "" + SERVERURL + "Pedidos/vista_anadir_sin_producto/" + id;
}

// function boton_anularPedido(id_factura) {
//   $.ajax({
//     type: "POST",
//     url: SERVERURL + "Pedidos/eliminarPedido/" + id_factura,
//     dataType: "json",
//     success: function (response) {
//       if (response.status == 500) {
//         toastr.error("NO SE ELIMINO CORRECTAMENTE", "NOTIFICACIÓN", {
//           positionClass: "toast-bottom-center",
//         });
//       } else if (response.status == 200) {
//         toastr.success("ELIMINADO CORRECTAMENTE", "NOTIFICACIÓN", {
//           positionClass: "toast-bottom-center",
//         });

//         initDataTableHistorial();
//       }
//     },
//     error: function (xhr, status, error) {
//       console.error("Error en la solicitud AJAX:", error);
//       alert("Hubo un problema al elimnar pedido");
//     },
//   });
// }

async function eliminarPedido(idFactura) {
  try {
    const response = await fetch(
      SERVERURL + `Pedidos/eliminarPedido/${idFactura}`,
      {
        method: "GET",
      }
    );
    const result = await response.json();

    if (result.status == 200) {
      toastr.success("PEDIDO ANULADO CORRECTAMENTE", "NOTIFICACIÓN", {
        positionClass: "toast-bottom-center",
      });
      // SOLO recargas la tabla dentro de esta función
      await initDataTableHistorial();
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
