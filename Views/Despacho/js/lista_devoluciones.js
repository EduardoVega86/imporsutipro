let dataTableListaDevoluciones;
let dataTableListaDevolucionesIsInitialized = false;

const dataTableListaDevolucionesOptions = {
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

const initDataTableListaDevoluciones = async () => {
  if (dataTableListaDevolucionesIsInitialized) {
    dataTableListaDevoluciones.destroy();
  }

  await listListaDevoluciones();

  dataTableListaDevoluciones = $("#datatable_lista_devoluciones").DataTable(dataTableListaDevolucionesOptions);

  dataTableListaDevolucionesIsInitialized = true;
};

const listListaDevoluciones = async () => {
  try {
    const response = await fetch("" + SERVERURL + "despacho/listarDevoluciones");
    const listaDevoluciones = await response.json();

    let content = ``;

    listaDevoluciones.forEach((devolucion, index) => {

      content += `
                <tr>
                    <td><a class="dropdown-item link-like" href="${SERVERURL}wallet/pagar?tienda=${devolucion.tienda}">${devolucion.tienda}</a></td>
                    <td>${devolucion.ventas}</td>
                    <td>${devolucion.utilidad}</td>
                    <td>${devolucion.count_visto_0}</td>
                    <td>
                    <button id="downloadExcel" class="btn btn-success" onclick="descargarExcel_general('${devolucion.tienda}')">Descargar Excel general</button>
                    <button id="downloadExcel" class="btn btn-success" onclick="descargarExcel('${devolucion.tienda}')">Descargar Excel</button>
                    </td>
                    <td>
                    <div class="dropdown">
                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-gear"></i>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <li><a class="dropdown-item" style="cursor: pointer;" href="${SERVERURL}wallet/pagar?tienda=${devolucion.tienda}"><i class='bx bx-wallet'></i>Pagar</a></li>
                    </ul>
                    </div>
                    </td>
                </tr>`;
    });
    document.getElementById("tableBody_lista_devoluciones").innerHTML = content;
  } catch (ex) {
    alert(ex);
  }
};

window.addEventListener("load", async () => {
  await initDataTableListaDevoluciones();
});
