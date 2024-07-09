let dataTableSolicitudes;
let dataTableSolicitudesIsInitialized = false;

const dataTableSolicitudesOptions = {
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
                    <td><input type="checkbox" class="selectCheckbox" data-id="${
                        solicitud.id_solicitud
                    }" ${checkboxState} onclick="toggleSolicitud(${
                        solicitud.id_solicitud
                    }, this.checked)"></td>
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
      console.log("solicitud actualizado:", result);
    } catch (error) {
      console.error("Error:", error);
      alert("Hubo un error al actualizar el solicitud");
    }
  };

function Pagar(id_plataforma){
    window.location.href = '' + SERVERURL + 'wallet/pagar?tienda='+id_plataforma;
}
window.addEventListener("load", async () => {
  await initDataTableSolicitudes();
});
