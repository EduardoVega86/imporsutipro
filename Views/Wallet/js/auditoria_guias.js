//audtiroria tempral
$(document).ready(function () {
  $(".filter-btn").on("click", function () {
    $(".filter-btn").removeClass("active");
    $(this).addClass("active");

    var filtro_facturas = $(this).data("filter"); // Actualizar variable con el filtro seleccionado
    var id_transportadora = $("#transporte").val();
    initDataTableAuditoria(filtro_facturas, id_transportadora);
  });

  // Añadir event listener al select para el evento change
  $("#transporte").on("change", function () {
    var id_transportadora = $(this).val();
    var filtro_facturas = $(".filter-btn.active").data("filter"); // Obtener el filtro activo
    initDataTableAuditoria(filtro_facturas, id_transportadora);
  });
});

let dataTableAuditoria;
let dataTableAuditoriaIsInitialized = false;

const dataTableAuditoriaOptions = {
  columnDefs: [
    { className: "centered", targets: [0, 1, 2, 3, 4] },
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

const initDataTableAuditoria = async (estado, id_transporte) => {
  if (dataTableAuditoriaIsInitialized) {
    dataTableAuditoria.destroy();
  }

  await listAuditoria(estado, id_transporte);

  dataTableAuditoria = $("#datatable_auditoria").DataTable(
    dataTableAuditoriaOptions
  );

  dataTableAuditoriaIsInitialized = true;
};

const listAuditoria = async (estado, id_transporte) => {
  try {
    const formData = new FormData();
    formData.append("estado", estado);
    formData.append("transportadora", id_transporte);

    const response = await fetch(SERVERURL + "wallet/obtenerGuiasAuditoria", {
      method: "POST",
      body: formData,
    });

    const auditoria = await response.json();

    let content = ``;

    auditoria.forEach((item, index) => {
      let transporte = item.id_transporte;
      let transporte_content = "";
      let estado = "";

      if (transporte == 2) {
        transporte_content =
          '<span style="background-color: #28C839; color: white; padding: 5px; border-radius: 0.3rem;">SERVIENTREGA</span>';
        estado = validar_estadoServi(item.estado_guia_sistema);
      } else if (transporte == 1) {
        transporte_content =
          '<span style="background-color: #E3BC1C; color: white; padding: 5px; border-radius: 0.3rem;">LAAR</span>';
        estado = validar_estadoLaar(item.estado_guia_sistema);
      } else if (transporte == 4) {
        if (MATRIZ == 2) {
          transporte_content =
            '<span style="background-color: red; color: white; padding: 5px; border-radius: 0.3rem;">MerkaLogistic</span>';
        } else if (MATRIZ == 1) {
          transporte_content =
            '<span style="background-color: red; color: white; padding: 5px; border-radius: 0.3rem;">SPEED</span>';
        }
        estado = validar_estadoSpeed(item.estado_guia_sistema);
      } else if (transporte == 3) {
        transporte_content =
          '<span style="background-color: red; color: white; padding: 5px; border-radius: 0.3rem;">GINTRACOM</span>';
        estado = validar_estadoGintracom(item.estado_guia_sistema);
      } else {
        transporte_content =
          '<span style="background-color: #E3BC1C; color: white; padding: 5px; border-radius: 0.3rem;">Guia no enviada</span>';
      }

      var span_estado = estado.span_estado;
      var estado_guia = estado.estado_guia;

      const codBtn = item.cod
        ? `<button class="btn-cod-si">SI</button>`
        : `<button class="btn-cod-no">NO</button>`;

      // Determinar si el checkbox debe estar marcado
      let check = item.valida_transportadora == 1 ? "checked" : "";

      content += `
              <tr>
                  <td>${item.numero_factura}</td>
                  <td>${item.numero_guia}</td>
          <td><span class="w-100 text-nowrap ${span_estado}">${estado_guia}</span></td>
          <td>${item.drogshipin}</td>
           <td>${transporte_content}</td>
                  <td>${codBtn}</td>
                  <td>${item.monto_factura}</td>
                  <td>${item.costo_flete}</td>
                  <td>${item.precio}</td>
                  <td>${item.costo}</td>
                 <td>${item.valor_cod}</td>
           <td>${item.utilidad}</td>
          <td>${item.valor}</td>
           <td>${item.comision}</td>
                  <td><input type="checkbox" class="selectCheckbox" data-id="${item.numero_guia}" ${check}></td>
              </tr>`;
    });

    document.getElementById("tableBody_auditoria").innerHTML = content;

    // Añadir event listeners a los checkboxes
    const checkboxes = document.querySelectorAll(".selectCheckbox");
    checkboxes.forEach((checkbox) => {
      checkbox.addEventListener("click", async (event) => {
        const facturaId = event.target.getAttribute("data-id");
        const isChecked = event.target.checked ? 1 : 0; // Convertir a 1 o 0
        await handleCheckboxClick(facturaId, isChecked);
      });
    });
  } catch (ex) {
    alert(ex);
  }
};

const handleCheckboxClick = async (facturaId, isChecked) => {
  try {
    const formData = new FormData();
    formData.append("numero_guia", facturaId);
    formData.append("estado", isChecked);

    const response = await fetch(SERVERURL + "Wallet/habilitarAuditoria", {
      method: "POST",
      body: formData,
    });

    const result = await response.json();
    console.log(result); // Manejar la respuesta de la API
  } catch (error) {
    console.error("Error:", error);
  }
};

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
    estado_guia = "Por recolectar";
  } else if (estado == 4) {
    span_estado = "badge_purple";
    estado_guia = "Por recolectar";
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
  } else if (estado >= 300 && estado <= 317) {
    span_estado = "badge_warning";
    estado_guia = "Procesamiento";
  } else if (estado >= 400 && estado <= 403) {
    span_estado = "badge_green";
    estado_guia = "Entregado";
  } else if (estado >= 318 && estado <= 351) {
    span_estado = "badge_danger";
    estado_guia = "Con novedad";
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
    span_estado = "badge_generado";
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
    estado_guia = "Entregada";
  } else if (estado == 8) {
    span_estado = "badge_danger";
    estado_guia = "Devolucion";
  } else if (estado == 9) {
    span_estado = "badge_danger";
    estado_guia = "Devolución Entregada a Origen";
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
    estado_guia = "Devolucion en tránsito";
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
  }

  return {
    span_estado: span_estado,
    estado_guia: estado_guia,
  };
}
