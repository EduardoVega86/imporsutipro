let dataTableConfiguracionAutomatizador;
let dataTableConfiguracionAutomatizadorIsInitialized = false;

const dataTableConfiguracionAutomatizadorOptions = {
  columnDefs: [
    { className: "centered", targets: [1, 2, 3, 4] },
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
      SERVERURL + "Pedidos/configuraciones_automatizador"
    );
    const configuracionAutomatizador = await response.json();

    let content = ``;

    if (configuracionAutomatizador.length > 0) {
      $("#boton_agregar_configuracion").hide();
      $("#btnConectarWhatsApp").hide();
    } else {
      $("#boton_agregar_configuracion").show();
      $("#btnConectarWhatsApp").show();
    }

    configuracionAutomatizador.forEach((configuracion, index) => {
      const switchChecked = configuracion.metodo_pago == 1 ? "checked" : "";

      content += `
        <tr>
          <td>${configuracion.id}</td>
          <td>${configuracion.nombre_configuracion}</td>
          <td>${configuracion.id_telefono}</td>
          <td>${configuracion.webhook_url}</td>
          <td>
            <div class="form-check form-switch" style="justify-self: center;">
              <input 
                class="form-check-input" 
                type="checkbox" 
                id="switch_pago_${configuracion.id}" 
                ${switchChecked} 
                onchange="cambiarEstadoMetodoPago(${configuracion.id}, this.checked)">
            </div>
          </td>
          <td>
            <button class="btn btn-sm btn-primary" onclick="redireccion_automatizadores(${configuracion.id})">
              <i class="fa-solid fa-wand-magic-sparkles"></i> Automatizadores
            </button>
            <button class="btn btn-sm btn-success" onclick="modal_crear_automatizador(${configuracion.id})">
              <i class="fas fa-plus"></i> Crear automatizador
            </button>
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

const cambiarEstadoMetodoPago = (id_configuracion, nuevo_estado) => {
  const estado = nuevo_estado ? 1 : 0;

  let formData = new FormData();
  formData.append("estado", estado);
  formData.append("id_configuracion", id_configuracion);
  $.ajax({
    url: SERVERURL + "Pedidos/actualizar_metodo_pago",
    type: "POST",
    data: formData,
    processData: false, // No procesar los datos
    contentType: false, // No establecer ningún tipo de contenido
    success: function (response) {
      if (response.status == 500) {
        toastr.error("ERROR AL ACTUALIZAR EL METODO DE PAGO", "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });
      } else if (response.status == 200) {
        toastr.success("SE ACTUALIZO EL METODO DE PAGO CORRECTAMENTE", "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      alert(errorThrown);
    },
  });
};

function redireccion_automatizadores(id) {
  if (MATRIZ == 1) {
    window.location.href =
      "https://automatizador.imporsuitpro.com/tabla_automatizadores.php?id_configuracion=" +
      id;
  } else if (MATRIZ == 2) {
    window.location.href =
      "https://automatizador.merkapro.ec/tabla_automatizadores.php?id_configuracion=" +
      id;
  }
}

function modal_crear_automatizador(id) {
  $("#id_configuracion").val(id);

  $("#agregar_automatizadorModal").modal("show");
}
