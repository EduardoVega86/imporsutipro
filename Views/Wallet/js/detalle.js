let dataTableDetalleWallet;
let dataTableDetalleWalletIsInitialized = false;

const dataTableDetalleWalletOptions = {
  columnDefs: [
    { className: "centered", targets: [1, 2, 3, 4, 5] },
    { orderable: false, targets: 0 }, //ocultar para columna 0 el ordenar columna
  ],
  order: [[3, "desc"]], // Ordenar por la primera columna (fecha) en orden descendente
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

const initDataTableDetalleWallet = async () => {
  if (dataTableDetalleWalletIsInitialized) {
    dataTableDetalleWallet.destroy();
  }

  await listDetalleWallet();

  dataTableDetalleWallet = $("#datatable_detalleWallet").DataTable(
    dataTableDetalleWalletOptions
  );

  dataTableDetalleWalletIsInitialized = true;
};

const listDetalleWallet = async () => {
  try {
    const response = await fetch("" + SERVERURL + "wallet/obtenerDatos");
    const detallesWallet = await response.json();

    let content = ``;

    detallesWallet.forEach((detalleWallet, index) => {
      content += `
                <tr>
                    <td><a class="dropdown-item link-like" href="${SERVERURL}wallet/pagar?id_plataforma=${detalleWallet.id_plataforma}">${detalleWallet.tienda}</a></td>
                    <td>${detalleWallet.ventas}</td>
                    <td>${detalleWallet.utilidad}</td>
                    <td>${detalleWallet.count_visto_0}</td>
                    <td>
                    <button id="downloadExcel2" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalReporte" data-id_plataforma="${detalleWallet.id_plataforma}">Descargar Reporte</button>
                    <button id="downloadExcel" class="btn btn-success" onclick="descargarExcel_general('${detalleWallet.tienda}')">Descargar Excel general</button>
                    <button id="downloadExcel" class="btn btn-success" onclick="descargarExcel('${detalleWallet.tienda}')">Descargar Excel</button>
                    </td>
                    <td>
                    <div class="dropdown">
                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-gear"></i>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <li><a class="dropdown-item" style="cursor: pointer;" href="${SERVERURL}wallet/pagar?id_plataforma=${detalleWallet.id_plataforma}"><i class='bx bx-wallet'></i>Pagar</a></li>
                    </ul>
                    </div>
                    </td>
                </tr>`;
    });
    document.getElementById("tableBody_detalleWallet").innerHTML = content;
  } catch (ex) {
    alert(ex);
  }
};

// Función para determinar si un año es bisiesto
function esBisiesto(anio) {
  return (anio % 4 === 0 && anio % 100 !== 0) || anio % 400 === 0;
}

// Función que actualiza los días del mes
function actualizarDias() {
  const anio = parseInt(document.getElementById("anio_select").value, 10);
  const mes = parseInt(document.getElementById("mes_select").value, 10);
  const diaSelect = document.getElementById("dia_select");

  let dias = 31; // Valor por defecto

  switch (mes) {
    case 2: // Febrero
      dias = esBisiesto(anio) ? 29 : 28;
      break;
    case 4: // Abril
    case 6: // Junio
    case 9: // Septiembre
    case 11: // Noviembre
      dias = 30;
      break;
    default:
      dias = 31;
  }

  // Limpiamos el select de días
  diaSelect.innerHTML = "";

  // Generamos las opciones de los días
  for (let i = 1; i <= dias; i++) {
    const option = document.createElement("option");
    option.value = i;
    option.textContent = i;
    diaSelect.appendChild(option);
  }

  // actualizar dia de rango en base al dia seleccionado
  const diaRango = document.getElementById("dia_rango");
  diaRango.innerHTML = "";
  for (let i = 1; i <= dias; i++) {
    const option = document.createElement("option");
    option.value = i;
    option.textContent = i;
    diaRango.appendChild(option);
  }

  // Actualizamos el valor del día si es mayor al máximo
  const dia = parseInt(diaSelect.value, 10);
  if (dia > dias) {
    diaSelect.value = dias;
  }
}

document
  .getElementById("mes_select")
  .addEventListener("change", actualizarDias);
document
  .getElementById("anio_select")
  .addEventListener("change", actualizarDias);

document.addEventListener("DOMContentLoaded", actualizarDias);

// Lógica para mostrar/ocultar el select de día en base al checkbox "tipo_reporte"
const tipoReporteCheckbox = document.getElementById("tipo_reporte");
const diaContainer = document.getElementById("dia_container");
const tipoSelectCheckbox = document.getElementById("tipo_select");
const rangoContainer = document.getElementById("rango_container");
const tipo_select = document.getElementById("tipo_select");
const rangoCheck = document.getElementById("rango_check");
tipoReporteCheckbox.addEventListener("change", function () {
  if (this.checked) {
    // Mostrar el día
    diaContainer.classList.remove("hidden");
    rangoCheck.classList.remove("hidden");
  } else {
    // Ocultar y limpiar valor
    diaContainer.classList.add("hidden");
    document.getElementById("dia_select").selectedIndex = 0;
  }
});

// Lógica para mostrar/ocultar el campo de rango en base al checkbox "tipo_select"

tipoSelectCheckbox.addEventListener("change", function () {
  if (this.checked) {
    // Mostrar el rango
    rangoContainer.classList.remove("hidden");
    //poner tipo check en 0
    tipo_select.value = 0;
  } else {
    // Ocultar y limpiar valor
    rangoContainer.classList.add("hidden");
    document.getElementById("rango_select").value = "";
  }
});

window.addEventListener("load", async () => {
  await initDataTableDetalleWallet();
});
