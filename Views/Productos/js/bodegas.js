let dataTable;
let dataTableIsInitialized = false;

const dataTableOptions = {
  //scrollX: "2000px",
  /* lengthMenu: [5, 10, 15, 20, 100, 200, 500], */
  columnDefs: [
    { className: "centered", targets: [0, 1, 2, 3, 4, 5, 6] },
    /* { orderable: false, targets: [5, 6] }, */
    /* { searchable: false, targets: [1] } */
    //{ width: "50%", targets: [0] }
  ],
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

const initDataTable = async () => {
  if (dataTableIsInitialized) {
    dataTable.destroy();
  }

  await listBodegas();

  dataTable = $("#datatable_bodegas").DataTable(dataTableOptions);

  dataTableIsInitialized = true;
};

const listBodegas = async () => {
  try {
    const response = await fetch("" + SERVERURL + "productos/cargarBodegas");
    const bodegas = await response.json();

    let content = ``;
    const ciudadPromises = bodegas.map((bodega) =>
      cargarCiudad(bodega.localidad)
    );

    // Esperar a que todas las promesas se resuelvan
    const ciudades = await Promise.all(ciudadPromises);

    bodegas.forEach((bodega, index) => {
      const ciudad = ciudades[index];

      let editar = ``;

      if (bodega.id_plataforma == ID_PLATAFORMA) {
        editar = `<li><span class="dropdown-item" style="cursor: pointer;" onclick="editar_bodegas(${bodega.id})"><i class="fa-solid fa-pencil"></i>Editar</span></li>
                        <li><span class="dropdown-item" style="cursor: pointer;" onclick="eliminarBodega(${bodega.id})"><i class="fa-solid fa-trash-can"></i>Eliminar</span></li>`;
      }

      content += `
                <tr>
                    <td>${bodega.id}</td>
                    <td>${bodega.nombre}</td>
                    <td>${bodega.direccion}</td>
                    <td>${ciudad}</td>
                    <td>${bodega.responsable}</td>
                    <td>${bodega.contacto}</td>
                    <td>
                    <div class="dropdown">
                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-gear"></i>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <li><span class="dropdown-item" style="cursor: pointer;" onclick="ver_inventario(${bodega.id})"><i class='bx bxs-file-find'></i>Ver Inventario</span></li>
                        ${editar}
                    </ul>
                    </div>
                    </td>
                </tr>`;
    });
    document.getElementById("tableBody_bodegas").innerHTML = content;
  } catch (ex) {
    alert(ex);
  }
};

function editar_bodegas(id) {
  const url = "" + SERVERURL + "Productos/editar_bodegas?id=" + id;
  window.location.href = url;
}

async function cargarCiudad(id_ciudad) {
  const url = "" + SERVERURL + "Ubicaciones/obtenerCiudad/" + id_ciudad;
  try {
    const response = await fetch(url);
    const data = await response.json();
    return data[0].ciudad;
  } catch (error) {
    console.error("Error:", error);
    return null;
  }
}

function eliminarBodega(id) {
  let formData = new FormData();
  formData.append("id", id); // Añadir el SKU al FormData

  $.ajax({
    type: "POST",
    url: SERVERURL + "productos/eliminarBodega",
    data: formData,
    processData: false, // No procesar los datos
    contentType: false, // No establecer ningún tipo de contenido
    success: function (response) {
      response = JSON.parse(response);
      // Mostrar alerta de éxito
      if (response.status == 500) {
        Swal.fire({
          icon: "error",
          title: response.title,
          text: response.message,
        });
      } else {
        Swal.fire({
          icon: "success",
          title: response.title,
          text: response.message,
          showConfirmButton: false,
          timer: 2000,
        }).then(() => {
          // Recargar la DataTable
          initDataTable();
        });
      }
    },
    error: function (xhr, status, error) {
      console.error("Error en la solicitud AJAX:", error);
      alert("Hubo un problema al eliminar la bodega");
    },
  });
}

function ver_inventario(id_bodega) {
  window.location.href =
    SERVERURL + "Productos/inventario_bodega?id_bodega=" + id_bodega;
}

window.addEventListener("load", async () => {
  await initDataTable();
});
