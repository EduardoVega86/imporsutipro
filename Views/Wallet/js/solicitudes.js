let dataTableSolicitudes;
let dataTableSolicitudesIsInitialized = false;

const dataTableSolicitudesOptions = {
  columnDefs: [
    { className: "centered", targets: [1, 2, 3, 4, 5] },
    { orderable: false, targets: 0 }, //ocultar para columna 0 el ordenar columna
  ],
  order: [[0, "desc"]], // Ordenar por la primera columna (fecha) en orden descendente
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

const initDataTableSolicitudes = async () => {
  if (dataTableSolicitudesIsInitialized) {
    dataTableSolicitudes.destroy();
  }

  await listSolicitudes();

  dataTableSolicitudes = $("#datatable_solicitudes").DataTable(
    dataTableSolicitudesOptions
  );

  dataTableSolicitudesIsInitialized = true;
};

const listSolicitudes = async () => {
  try {
    const response = await fetch("" + SERVERURL + "wallet/obtenerSolicitudes");
    const solicitudes = await response.json();

    let content = ``;
    let checkboxState = "";
    solicitudes.forEach((solicitud, index) => {
      if (solicitud.visto == 1) {
        checkboxState = "checked disabled";
      } else {
        checkboxState = "";
      }
      content += `
                <tr>
                    <td><input type="checkbox" class="selectCheckbox" data-id="${solicitud.id_solicitud}" ${checkboxState} onclick="toggleSolicitud(${solicitud.id_solicitud}, this.checked)"></td>
                    <td>${solicitud.nombre}</td>
                    <td>${solicitud.correo}</td>
                    <td>${solicitud.cedula}</td>
                    <td>${solicitud.fecha}</td>
                    <td>${solicitud.telefono}</td>
                    <td>${solicitud.tipo_cuenta}</td>
                    <td>${solicitud.banco}</td>
                    <td>${solicitud.numero_cuenta}</td>
                    <td>${solicitud.cantidad}</td>
                    <td>
                        <button class="btn btn-sm btn-primary" onclick="Pagar(${solicitud.id_plataforma})"><i class="fa-solid fa-sack-dollar"></i>Pagar</button>
                        <button class="btn btn-sm btn-danger" onclick="eliminarSolicitud(${solicitud.id_solicitud})"><i class="fa-solid fa-trash-can"></i>Borrar</button>
                    </td>

                </tr>`;
    });
    document.getElementById("tableBody_solicitudes").innerHTML = content;
  } catch (ex) {
    alert(ex);
  }
};

// Función para manejar el evento click del checkbox
const toggleSolicitud = async (userId, isChecked) => {
  const proveedorValue = isChecked ? 1 : 0;
  const formData = new FormData();
  formData.append("id_solicitud", userId);

  try {
    const response = await fetch(`${SERVERURL}wallet/verificarPago`, {
      method: "POST",
      body: formData,
    });

    if (!response.ok) {
      throw new Error("Error al actualizar el solicitud");
    }

    const result = await response.json();
    initDataTableSolicitudes();
  } catch (error) {
    console.error("Error:", error);
    alert("Hubo un error al actualizar el solicitud");
  }
};

function Pagar(id_plataforma) {
  window.location.href =
    "" + SERVERURL + "wallet/pagar?id_plataforma=" + id_plataforma;
}

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
      const codBtn = item.cod
        ? `<button class="btn-cod-si">SI</button>`
        : `<button class="btn-cod-no">NO</button>`;

      // Determinar si el checkbox debe estar marcado
      let check = item.valida_transportadora == 1 ? "checked" : "";

      content += `
              <tr>
                  <td>${item.numero_factura}</td>
                  <td>${item.numero_guia}</td>
                  <td>${codBtn}</td>
                  <td>${item.monto_factura}</td>
                  <td>${item.costo_flete}</td>
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
