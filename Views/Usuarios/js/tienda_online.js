let dataTableBanner;
let dataTableBannerIsInitialized = false;

const dataTableBannerOptions = {
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

const initDataTableBanner = async () => {
  if (dataTableBannerIsInitialized) {
    dataTableBanner.destroy();
  }

  await listBanner();

  dataTableBanner = $("#datatable_banner").DataTable(dataTableBannerOptions);

  dataTableBannerIsInitialized = true;
};

const listBanner = async () => {
  try {
    const response = await fetch("" + SERVERURL + "Usuarios/obtener_bannertienda");
    const banner = await response.json();

    let content = ``;
    let alineacion = "";
    banner.forEach((item, index) => {

        if (item.alineacion == 1){
            alineacion = "izquierda";
        } else if (item.alineacion == 2){
            alineacion = "centro";
        } else if (item.alineacion == 3){
            alineacion = "derecha";
        }
      content += `
                <tr>
                    <td><img src="${SERVERURL}${item.fondo_banner}" class="img-responsive" alt="profile-image" width="100px"></td>
                    <td>${item.titulo}</td>
                    <td>${item.texto_banner}</td>
                    <td>${item.texto_boton}</td>
                    <td>${item.enlace_boton}</td>
                    <td>${alineacion}</td>
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
    document.getElementById("tableBody_banner").innerHTML = content;
  } catch (ex) {
    alert(ex);
  }
};

window.addEventListener("load", async () => {
  await initDataTableBanner();
});
