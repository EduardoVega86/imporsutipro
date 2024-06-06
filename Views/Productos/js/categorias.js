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

  await listCategorias();

  dataTable = $("#datatable_categorias").DataTable(dataTableOptions);

  dataTableIsInitialized = true;
};

const listCategorias = async () => {
  try {
    const response = await fetch(
      "" + SERVERURL + "productos/cargar_categorias"
    );
    const categorias = await response.json();

    let content = ``;
    categorias.forEach((categoria, index) => {
      content += `
                <tr>
                    <td>${categoria.nombre_linea}</td>
                    <td>${categoria.imagen}</td>
                    <td>${categoria.online}</td>
                    <td>${categoria.descripcion_linea}</td>
                    <td>${categoria.tipo}</td>
                    <td>${categoria.padre}</td>
                    <td>${categoria.estado_linea}</td>
                    <td>
                        <button class="btn btn-sm btn-primary" onclick="editar_categoria(${categoria.id_linea})"><i class="fa-solid fa-pencil"></i>Editar</button>
                        <button class="btn btn-sm btn-danger" onclick="eliminar_categoria(${categoria.id_linea})"><i class="fa-solid fa-trash-can"></i>Borrar</button>

                    </td>
                </tr>`;
    });
    document.getElementById("tableBody_categorias").innerHTML = content;
  } catch (ex) {
    alert(ex);
  }
};

function eliminar_categoria(id) {
  $.ajax({
    type: "POST",
    url: SERVERURL + "productos/eliminarCategoria",
    data: { id: id }, // Enviar el ID como un objeto
    dataType: 'json', // Asegurarse de que la respuesta se trata como JSON
    success: function (response) {
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
      alert("Hubo un problema al eliminar la categoría");
    },
  });
}

function editar_categoria(id) {
  $.ajax({
    type: "POST",
    url: SERVERURL + "productos/listarCategoria",
    data: { id: id },
    dataType: 'json',
    success: function (response) {
      console.log(response); // Depuración: Mostrar la respuesta en la consola

      if (response && response.length > 0) {
        const data = response[0];
        console.log(data); // Depuración: Mostrar los datos obtenidos

        // Verificar y asignar valores a los inputs
        const nombreLineaInput = $('#nombre_linea');
        if (nombreLineaInput.length) {
          nombreLineaInput.val(data.nombre_linea);
          console.log('Nombre línea asignado:', nombreLineaInput.val()); // Depuración
        }

        const descripcionLineaInput = $('#descripcion_linea');
        if (descripcionLineaInput.length) {
          descripcionLineaInput.val(data.descripcion_linea);
          console.log('Descripción línea asignada:', descripcionLineaInput.val()); // Depuración
        }

        const onlineSelect = $('#online');
        if (onlineSelect.length) {
          onlineSelect.val(data.online);
          console.log('Online asignado:', onlineSelect.val()); // Depuración
        }

        const tipoSelect = $('#tipo');
        if (tipoSelect.length) {
          tipoSelect.val(data.tipo);
          console.log('Tipo asignado:', tipoSelect.val()); // Depuración
        }

        const padreSelect = $('#padre');
        if (padreSelect.length) {
          padreSelect.val(data.padre);
          console.log('Padre asignado:', padreSelect.val()); // Depuración
        }

        const estadoSelect = $('#estado');
        if (estadoSelect.length) {
          estadoSelect.val(data.estado_linea);
          console.log('Estado asignado:', estadoSelect.val()); // Depuración
        }

        // Abrir el modal
        $('#editar_categoriaModal').modal('show');
      } else {
        console.error("La respuesta está vacía o tiene un formato incorrecto.");
      }
    },
    error: function (xhr, status, error) {
      console.error("Error en la solicitud AJAX:", error);
      alert("Hubo un problema al obtener la información de la categoría");
    },
  });
}


window.addEventListener("load", async () => {
  await initDataTable();
});
