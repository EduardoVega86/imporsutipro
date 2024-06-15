let dataTable;
let dataTableIsInitialized = false;

const dataTableOptions = {
  columnDefs: [
    { className: "centered", targets: [1, 2, 3, 4, 5, 6, 7, 8, 9] },
    { orderable: false, targets: 0 }, //ocultar para columna 0 el ordenar columna
  ],
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

const initDataTable = async () => {
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
};

const listGuias = async () => {
  try {
    const response = await fetch("" + SERVERURL + "pedidos/obtener_guias");
    const guias = await response.json();

    let content = ``;
    let impresiones = "";
    guias.forEach((guia, index) => {
      let transporte = guia.id_transporte;
      let transporte_content = "";
      if (transporte == 3) {
        transporte_content =
          '<span style="background-color: #28C839; color: white; padding: 5px; border-radius: 0.3rem;">SERVIENTREGA</span>';
      } else if (transporte == 1) {
        transporte_content =
          '<span style="background-color: #E3BC1C; color: white; padding: 5px; border-radius: 0.3rem;">LAAR</span>';
      } else if (transporte == 2) {
        transporte_content =
          '<span style="background-color: red; color: white; padding: 5px; border-radius: 0.3rem;">SPEED</span>';
      } else if (transporte == 4) {
        transporte_content =
          '<span style="background-color: red; color: white; padding: 5px; border-radius: 0.3rem;">GINTRACOM</span>';
      } else {
        transporte_content =
          '<span style="background-color: #E3BC1C; color: white; padding: 5px; border-radius: 0.3rem;">Guia no enviada</span>';
      }

      estado = validar_estado(guia.estado_guia_sistema);
      var span_estado = estado.span_estado;
      var estado_guia = estado.estado_guia;

      if (guia.impreso == 0) {
        impresiones = `<box-icon name='printer' color= "red"></box-icon>`;
      } else {
        impresiones = `<box-icon name='printer' color= "green"></box-icon>`;
      }
      content += `
                <tr>
                    <td><input type="checkbox" class="selectCheckbox" data-id="${
                      guia.numero_factura
                    }"></td>
                    <td>${guia.numero_factura}</td>
                    <td>${guia.fecha_factura}</td>
                    <td>
                        <div><strong>${guia.nombre}</strong></div>
                        <div>${guia.c_principal} y ${guia.c_secundaria}</div>
                        <div>telf: ${guia.telefono}</div>
                    </td>
                    <td>${guia.provinciaa}</td>
                    <td>${guia.ciudad}</td>
                    <td>${guia.plataforma}</td>
                    <td>${transporte_content}</td>
                    <td>
                     <div style="text-align: center;">
                     <div>
                      <span class="w-100 ${span_estado}">${estado_guia}</span>
                     </div>
                     <div>
                      <a class="w-100" href="https://api.laarcourier.com:9727/guias/pdfs/DescargarV2?guia=${
                        guia.numero_guia
                      }" target="_blank">${guia.numero_guia}</a>
                     </div>
                     <div style="position: relative; display: inline-block;">
                      <a href="https://fenix.laarcourier.com/Tracking/Guiacompleta.aspx?guia=${
                        guia.numero_guia
                      }" target="_blank" style="vertical-align: middle;">
                        <img src="https://new.imporsuitpro.com/public/img/tracking.png" width="30px" id="buscar_traking" alt="buscar_traking">
                      </a>
                      <a href="https://wa.me/${formatPhoneNumber(
                        guia.telefono
                      )}" target="_blank" style="font-size: 50px; vertical-align: middle; margin-left: 10px;" target="_blank">
                        <box-icon type='logo' name='whatsapp-square' color="green"></box-icon>
                      </a>
                     </div>
                     </div>
                    </td>
                    <td>${impresiones}</td>
                    <td>
                        <button class="btn btn-sm btn-primary"><i class="fa-solid fa-pencil"></i></button>
                        <button class="btn btn-sm btn-danger"><i class="fa-solid fa-trash-can"></i></button>
                    </td>
                </tr>`;
    });
    document.getElementById("tableBody_guias").innerHTML = content;
  } catch (ex) {
    alert(ex);
  }
};

function validar_estado(estado) {
  var span_estado = "";
  var estado_guia = "";
  if (estado == 1) {
    span_estado = "badge_danger";
    estado_guia = "Anulado";
  } else if (estado == 2) {
    span_estado = "badge_purple";
    estado_guia = "Por recolectar";
  } else if (estado == 3) {
    span_estado = "badge_purple";
    estado_guia = "Por recolectar";
  } else if (estado == 4) {
    span_estado = "badge_purple";
    estado_guia = "Por recolectar";
  } else if (estado == 5) {
    span_estado = "badge_warning";
    estado_guia = "En transito";
  } else if (estado == 6) {
    span_estado = "badge_purple";
    estado_guia = "Por recolectar";
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
    estado_guia = "Con novedad";
  } else if (estado == 9) {
    span_estado = "badge_danger";
    estado_guia = "Devuelto";
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
    success: function (response) {
      console.log("Respuesta del servidor:", response);
      // Manejar la respuesta exitosa aquí
    },
    error: function (xhr, status, error) {
      console.error("Error en la solicitud AJAX:", error);
      alert("Hubo un problema al obtener la información de la categoría");
    },
  });
});


window.addEventListener("load", async () => {
  await initDataTable();
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
