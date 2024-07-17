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
window.addEventListener("load", async () => {
  await initDataTableSolicitudes();
});

//audtiroria tempral
$(document).ready(function () {
  $(".filter-btn").on("click", function () {
    $(".filter-btn").removeClass("active");
    $(this).addClass("active");

    var filtro_facturas = $(this).data("filter"); // Actualizar variable con el filtro seleccionado

    initDataTableAuditoria(filtro_facturas);
  });
});

window.addEventListener("load", async () => {
  await initDataTableAuditoria(0);
});

let dataTableAuditoria;
let dataTableAuditoriaIsInitialized = false;

const dataTableAuditoriaOptions = {
  columnDefs: [
    { className: "centered", targets: [1, 2, 3, 4, 5] },
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

const initDataTableAuditoria = async (estado) => {
  if (dataTableAuditoriaIsInitialized) {
    dataTableAuditoria.destroy();
  }

  await listAuditoria(estado);

  dataTableAuditoria = $("#datatable_auditoria").DataTable(
    dataTableAuditoriaOptions
  );

  dataTableAuditoriaIsInitialized = true;
};

const listAuditoria = async (estado) => {
  try {
    const formData = new FormData();
    formData.append("estado", estado);

    const response = await fetch(
      "" + SERVERURL + "wallet/obtenerGuiasAuditoria/",
      {
        method: "POST",
        body: formData,
      }
    );
    const auditoria = await response.json();

    let content = ``;

    auditoria.forEach((item, index) => {
      content += `
                <tr>
                    <td><a class="dropdown-item link-like" href="${SERVERURL}wallet/pagar?tienda=${item.tienda}">${item.tienda}</a></td>
                    <td>${item.ventas}</td>
                    <td>${item.utilidad}</td>
                    <td>${item.count_visto_0}</td>
                    <td>
                    <button id="downloadExcel" class="btn btn-success" onclick="descargarExcel_general('${item.tienda}')">Descargar Excel general</button>
                    <button id="downloadExcel" class="btn btn-success" onclick="descargarExcel('${item.tienda}')">Descargar Excel</button>
                    </td>
                    <td>
                    <div class="dropdown">
                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-gear"></i>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <li><a class="dropdown-item" style="cursor: pointer;" href="${SERVERURL}wallet/pagar?tienda=${item.tienda}"><i class='bx bx-wallet'></i>Pagar</a></li>
                    </ul>
                    </div>
                    </td>
                </tr>`;
    });
    document.getElementById("tableBody_auditoria").innerHTML = content;
  } catch (ex) {
    alert(ex);
  }
};
