// Definir las constantes globales
let cabeceras_principal = [];
let referidos_principal = [];

$(document).ready(function () {
  $.ajax({
    url: SERVERURL + "referidos/getReferidos",
    type: "GET",
    dataType: "json",
    success: function (response) {
      cabeceras_principal = response.cabeceras;
      referidos_principal = response.referidos;

      $("#cantidad_referidos").text(response.cantidad);
      $("#ganancia_historico_referidos").text(response.ganancias);
      $("#ganancias_referidos").text(response.saldo);
    },
    error: function (error) {
      console.error("Error al obtener la lista de bodegas:", error);
    },
  });
});

function generar_referido() {
  $.ajax({
    url: SERVERURL + "referidos/crearReferido",
    type: "GET",
    dataType: "json",
    success: function (response) {
      $("#link_referido").show();
    },
    error: function (error) {
      console.error("Error al obtener la lista de bodegas:", error);
    },
  });
}

// TABLA REFERIDOS
let dataTableReferidos;
let dataTableReferidosIsInitialized = false;

const dataTableReferidosOptions = {
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

const initDataTableReferidos = async () => {
  if (dataTableReferidosIsInitialized) {
    dataTableReferidos.destroy();
  }

  await listReferidos();

  dataTableReferidos = $("#datatable_referidos").DataTable(
    dataTableReferidosOptions
  );

  dataTableReferidosIsInitialized = true;
};

const listReferidos = async () => {
  try {
    
    const referidos = await referidos_principal;

    let content = ``;

    referidos.forEach((referido, index) => {
      content += `
                <tr>
                    <td><a class="dropdown-item link-like" href="${SERVERURL}wallet/pagar?tienda=${referido.tienda}">${referido.tienda}</a></td>
                    <td>${referido.ventas}</td>
                    <td>${referido.utilidad}</td>
                    <td>${referido.count_visto_0}</td>
                    <td>
                    <button id="downloadExcel" class="btn btn-success" onclick="descargarExcel_general('${referido.tienda}')">Descargar Excel general</button>
                    <button id="downloadExcel" class="btn btn-success" onclick="descargarExcel('${referido.tienda}')">Descargar Excel</button>
                    </td>
                    <td>
                    <div class="dropdown">
                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-gear"></i>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <li><a class="dropdown-item" style="cursor: pointer;" href="${SERVERURL}wallet/pagar?tienda=${referido.tienda}"><i class='bx bx-wallet'></i>Pagar</a></li>
                    </ul>
                    </div>
                    </td>
                </tr>`;
    });
    document.getElementById("tableBody_referidos").innerHTML = content;
  } catch (ex) {
    alert(ex);
  }
};

window.addEventListener("load", async () => {
  await initDataTableReferidos();
});

//TABLA GUIAS REFERIDOS
let dataTableGuiasReferidos;
let dataTableGuiasReferidosIsInitialized = false;

const dataTableGuiasReferidosOptions = {
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

const initDataTableGuiasReferidos = async () => {
  if (dataTableGuiasReferidosIsInitialized) {
    dataTableGuiasReferidos.destroy();
  }

  await listGuiasReferidos();

  dataTableGuiasReferidos = $("#datatable_guias_referidos").DataTable(
    dataTableGuiasReferidosOptions
  );

  dataTableGuiasReferidosIsInitialized = true;
};

const listGuiasReferidos = async () => {
  try {
  
    const guiasReferidos = await cabeceras_principal;

    let content = ``;

    guiasReferidos.forEach((guia, index) => {
      content += `
                <tr>
                    <td><a class="dropdown-item link-like" href="${SERVERURL}wallet/pagar?tienda=${guia.tienda}">${guia.tienda}</a></td>
                    <td>${guia.ventas}</td>
                    <td>${guia.utilidad}</td>
                    <td>${guia.count_visto_0}</td>
                    <td>
                    <button id="downloadExcel" class="btn btn-success" onclick="descargarExcel_general('${guia.tienda}')">Descargar Excel general</button>
                    <button id="downloadExcel" class="btn btn-success" onclick="descargarExcel('${guia.tienda}')">Descargar Excel</button>
                    </td>
                    <td>
                    <div class="dropdown">
                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-gear"></i>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <li><a class="dropdown-item" style="cursor: pointer;" href="${SERVERURL}wallet/pagar?tienda=${guia.tienda}"><i class='bx bx-wallet'></i>Pagar</a></li>
                    </ul>
                    </div>
                    </td>
                </tr>`;
    });
    document.getElementById("tableBody_guias_referidos").innerHTML = content;
  } catch (ex) {
    alert(ex);
  }
};

window.addEventListener("load", async () => {
  await initDataTableGuiasReferidos();
});
