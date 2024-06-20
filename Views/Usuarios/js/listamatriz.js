let dataTableListaUsuarioMatriz;
let dataTableListaUsuarioMatrizIsInitialized = false;

const dataTableListaUsuarioMatrizOptions = {
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

const initDataTableListaUsuarioMatriz = async () => {
  if (dataTableListaUsuarioMatrizIsInitialized) {
    dataTableListaUsuarioMatriz.destroy();
  }

  await listListaUsuarioMatriz();

  dataTableListaUsuarioMatriz = $("#datatable_lista_usuarioMatriz").DataTable(dataTableListaUsuarioMatrizOptions);

  dataTableListaUsuarioMatrizIsInitialized = true;
};

const listListaUsuarioMatriz = async () => {
  try {
    const response = await fetch("" + SERVERURL + "usuarios/obtener_usuarios_matriz");
    const listaUsuarioMatriz = await response.json();

    let content = ``;

    listaUsuarioMatriz.forEach((usuario, index) => {

      content += `
                <tr>
                    <td><a class="dropdown-item link-like" href="${SERVERURL}wallet/pagar?tienda=${usuario.tienda}">${usuario.tienda}</a></td>
                    <td>${usuario.ventas}</td>
                    <td>${usuario.utilidad}</td>
                    <td>${usuario.count_visto_0}</td>
                    <td>
                    <button id="downloadExcel" class="btn btn-success" onclick="descargarExcel_general('${usuario.tienda}')">Descargar Excel general</button>
                    <button id="downloadExcel" class="btn btn-success" onclick="descargarExcel('${usuario.tienda}')">Descargar Excel</button>
                    </td>
                    <td>
                    <div class="dropdown">
                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-gear"></i>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <li><a class="dropdown-item" style="cursor: pointer;" href="${SERVERURL}wallet/pagar?tienda=${usuario.tienda}"><i class='bx bx-wallet'></i>Pagar</a></li>
                    </ul>
                    </div>
                    </td>
                </tr>`;
    });
    document.getElementById("tableBody_lista_usuarioMatriz").innerHTML = content;
  } catch (ex) {
    alert(ex);
  }
};

window.addEventListener("load", async () => {
  await initDataTableListaUsuarioMatriz();
});
