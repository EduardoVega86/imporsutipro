let dataTableHistorial;
let dataTableHistorialIsInitialized = false;

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
    formData.append("filtro", currentAPI);

    const response = await fetch(
      `${SERVERURL}shopify/obtenerAbandonados/${ID_PLATAFORMA}`,
      {
        method: "POST",
        body: formData,
      }
    );

    const historialPedidos = await response.json();

    let content = ``;
    historialPedidos.data.forEach((historialPedido, index) => {
      let boton_automatizador = "";

      /* if (VALIDAR_CONFIG_CHAT) {
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
      } */

      let acciones = "";

      acciones = `${boton_automatizador}`;

      let contactado = "";

      if (historialPedido.contactado == 0) {
        contactado = `<i class='bx bx-x' style="color:red; font-size: 30px;"></i>`;
      } else {
        contactado = `<i class='bx bx-check' style="color:#28E418; font-size: 30px;"></i>`;
      }

      content += `
                <tr>
                    <td>${historialPedido.fecha}</td>
                    <td>${historialPedido.producto}</td>
                    <td>${historialPedido.telefono}</td>
                    <td>${contactado}</td>
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

// Manejo de botones para cambiar API y recargar la tabla
document.getElementById("btnContactados").addEventListener("click", () => {
  currentAPI = "2";
  cambiarBotonActivo("btnContactados");
  initDataTableHistorial();
});

document.getElementById("btnTodos").addEventListener("click", () => {
  currentAPI = "";
  cambiarBotonActivo("btnTodos");
  initDataTableHistorial();
});

document.getElementById("btnNo_Contactados").addEventListener("click", () => {
  currentAPI = "1";
  cambiarBotonActivo("btnNo_Contactados");
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
