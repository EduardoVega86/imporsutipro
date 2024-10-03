let dataTableConfiguracionAutomatizador;
let dataTableConfiguracionAutomatizadorIsInitialized = false;

const dataTableConfiguracionAutomatizadorOptions = {
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

const initDataTableConfiguracionAutomatizador = async () => {
  if (dataTableConfiguracionAutomatizadorIsInitialized) {
    dataTableConfiguracionAutomatizador.destroy();
  }

  await listConfiguracionAutomatizador();

  dataTableConfiguracionAutomatizador = $(
    "#datatable_configuracion_automatizador"
  ).DataTable(dataTableConfiguracionAutomatizadorOptions);

  dataTableConfiguracionAutomatizadorIsInitialized = true;
};

const listConfiguracionAutomatizador = async () => {
  try {
    const response = await fetch(
      "" + SERVERURL + "Pedidos/configuraciones_automatizador"
    );
    const configuracionAutomatizador = await response.json();

    let content = ``;

    if (configuracionAutomatizador.length > 0) {
      $("#boton_agregar_configuracion").hide();
    } else {
      $("#boton_agregar_configuracion").show();
    }

    configuracionAutomatizador.forEach((configuracion, index) => {
      content += `
                <tr>
                <td>${configuracion.id}</td>
                <td>${configuracion.nombre_configuracion}</td>
                <td>${configuracion.id_telefono}</td>
                <td>${configuracion.webhook_url}</td>
                <!-- <td>${configuracion.token}</td> -->
                <td>
                <button class="btn btn-sm btn-primary" onclick="redireccion_automatizadores(${configuracion.id})"><i class="fa-solid fa-wand-magic-sparkles"></i>Automatizadores</button>
                <button class="btn btn-sm btn-success" onclick="modal_crear_automatizador(${configuracion.id})"><i class="fas fa-plus"></i>Crear automatizador</button>
                </td>
                </tr>`;
    });
    document.getElementById("tableBody_configuracion_automatizador").innerHTML =
      content;
  } catch (ex) {
    alert(ex);
  }
};

window.addEventListener("load", async () => {
  await initDataTableConfiguracionAutomatizador();
});

function redireccion_automatizadores(id) {
  window.location.href =
    "https://automatizador.imporsuitpro.com/tabla_automatizadores.php?id_configuracion=" +
    id;
}

function modal_crear_automatizador(id) {
  $("#id_configuracion").val(id);

  $("#agregar_automatizadorModal").modal("show");
}
