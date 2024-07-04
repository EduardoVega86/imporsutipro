let dataTableNovedades;
let dataTableNovedadesIsInitialized = false;

const dataTableNovedadesOptions = {
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

const initDataTableNovedades = async () => {
  if (dataTableNovedadesIsInitialized) {
    dataTableNovedades.destroy();
  }

  await listNovedades();

  dataTableNovedades = $("#datatable_novedades").DataTable(
    dataTableNovedadesOptions
  );

  dataTableNovedadesIsInitialized = true;
};

const listNovedades = async () => {
  try {
    const response = await fetch("" + SERVERURL + "novedades/cargarNovedades");
    const novedades = await response.json();

    let content = ``;
    let transportadora = ``;
    novedades.forEach((novedad, index) => {
        if (novedad.guia_novedad.includes("I")){
            transportadora = "GINTRACOM";
        } else if (novedad.guia_novedad.includes("IMP")){
            transportadora = "LAAR";
        } else if (novedad.guia_novedad.includes("SPD")){
            transportadora = "SPEED";
        } else {
            transportadora = "SERVIENTREGA";
        }

      content += `
                <tr>
                    <td>${novedad.id_novedad}</td>
                    <td>${novedad.guia_novedad}</td>
                    <td>${novedad.fecha}</td>
                    <td>${transportadora}</td>
                    <td>${novedad.cliente_novedad}</td>
                    <td>${novedad.novedad}</td>
                    <td></td>
                    <td>${novedad.estado_novedad}</td>
                    <td>
                    <button id="downloadExcel" class="btn btn-success" onclick="descargarExcel_general('${novedad.tienda}')">Gestionar</button>
                    </td>
                    <td>${novedad.tracking}</td>
                </tr>`;
    });
    document.getElementById("tableBody_novedades").innerHTML = content;
  } catch (ex) {
    alert(ex);
  }
};

window.addEventListener("load", async () => {
  await initDataTableNovedades();
});
