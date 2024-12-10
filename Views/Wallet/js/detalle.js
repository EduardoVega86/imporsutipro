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

window.addEventListener("load", async () => {
  await initDataTableDetalleWallet();
});
