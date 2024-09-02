let dataTableOfertas;
let dataTableOfertasIsInitialized = false;

const dataTableOfertasOptions = {
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

const initDataTableOfertas = async () => {
  if (dataTableOfertasIsInitialized) {
    dataTableOfertas.destroy();
  }

  await listOfertas();

  dataTableOfertas = $("#datatable_ofertas").DataTable(dataTableOfertasOptions);

  dataTableOfertasIsInitialized = true;
};

const listOfertas = async () => {
  try {
    const response = await fetch("" + SERVERURL + "Productos/obtener_oferta");
    const ofertas = await response.json();

    let content = ``;

    ofertas.forEach((oferta, index) => {
      content += `
                <tr>
                    <td>${oferta.nombre_oferta}</td>
                    <td>${oferta.precio_oferta}</td>
                    <td>${oferta.cantidad}</td>
                    <td>${oferta.fecha_inicio}</td>
                    <td>${oferta.fecha_fin}</td>
                    <td></td>
                    <td></td>
                    <td>
                    <div class="dropdown">
                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-gear"></i>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <li><span class="dropdown-item" style="cursor: pointer;" onclick="editar_oferta(${combo.id})">Editar</span></li>
                        <li><span class="dropdown-item" style="cursor: pointer;" onclick="eliminar_oferta(${combo.id})">Eliminar</span></li>
                    </ul>
                    </div>
                    </td>
                </tr>`;
    });
    document.getElementById("tableBody_ofertas").innerHTML = content;
  } catch (ex) {
    alert(ex);
  }
};

window.addEventListener("load", async () => {
  await initDataTableOfertas();
});
