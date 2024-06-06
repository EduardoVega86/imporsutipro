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
                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editar_categoriaModal"><i class="fa-solid fa-pencil"></i>Editar</button>
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
window.addEventListener("load", async () => {
  await initDataTable();
});
