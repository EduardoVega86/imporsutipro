let dataTableHistorial;
let dataTableHistorialIsInitialized = false;

const dataTableHistorialOptions = {
  //scrollX: "2000px",
  /* lengthMenu: [5, 10, 15, 20, 100, 200, 500], */
  columnDefs: [
    { className: "centered", targets: [0, 1, 2, 3, 4] },
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

      if (VALIDAR_CONFIG_CHAT) {
        if (historialPedido.contactado == 0) {
          boton_automatizador = `<button class="btn btn-sm btn-success" onclick="enviar_mensaje_automatizador(
          '${historialPedido.producto}',
          '${historialPedido.telefono}',
          ${historialPedido.id_abandonado},
          )"><i class="fa-brands fa-whatsapp"></i></button>`;
        }
      }

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
                        ${boton_automatizador}
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

function boton_editarPedido(id) {
  window.location.href = "" + SERVERURL + "Pedidos/editar/" + id;
}

function boton_vista_anadir_sin_producto(id) {
  window.location.href =
    "" + SERVERURL + "Pedidos/vista_anadir_sin_producto/" + id;
}

function enviar_mensaje_automatizador(
  contiene,
  celular,
  id_abandonado
) {
  let formData = new FormData();
  formData.append("celular", celular);
  formData.append("contiene", contiene);
  formData.append("id_abandonado", id_abandonado);

  $.ajax({
    url: SERVERURL + "pedidos/enviar_abandonado_automatizador",
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
