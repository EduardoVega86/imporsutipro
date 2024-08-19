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
  order: [[0, "desc"]], // Ordenar por la primera columna (fecha) en orden descendente
  pageLength: 25,
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
    let online = "";
    let cargar_imagen = "";
    let botones_accion = "";
    categorias.forEach((categoria, index) => {
      if (categoria.online == 0) {
        online =
          '<span style="background-color: #F20E0E; color: white; padding: 5px; border-radius: 0.3rem;">No</span>';
      } else {
        online =
          '<span style="background-color: #28C839; color: white; padding: 5px; border-radius: 0.3rem;">SI</span>';
      }

      if (categoria.tipo == 0) {
        tipo =
          '<span style="background-color: #F20E0E; color: white; padding: 5px; border-radius: 0.3rem;">SEGUNDARIO</span>';
      } else {
        tipo =
          '<span style="background-color: #28C839; color: white; padding: 5px; border-radius: 0.3rem;">PRINCIPAL</span>';
      }

      if (categoria.estado_linea == 0) {
        estado_linea =
          '<span style="background-color: #F20E0E; color: white; padding: 5px; border-radius: 0.3rem;">Inactivo</span>';
      } else {
        estado_linea =
          '<span style="background-color: #28C839; color: white; padding: 5px; border-radius: 0.3rem;">Activo</span>';
      }

      if (!categoria.imagen) {
        if (categoria.global == 1 && categoria.id_plataforma != ID_PLATAFORMA) {
          cargar_imagen = ``;
        } else {
          cargar_imagen = `<i class="bx bxs-camera-plus" onclick="agregar_imagenCategoria(${categoria.id_linea},'${categoria.imagen}')"></i>`;
        }
      } else {
        if (categoria.global == 1 && categoria.id_plataforma != ID_PLATAFORMA) {
          cargar_imagen = ``;
        } else {
          cargar_imagen = `<img src="${SERVERURL}${categoria.imagen}" class="icon-button" onclick="agregar_imagenCategoria(${categoria.id_linea},'${categoria.imagen}')" alt="Agregar imagen" width="50px">`;
        }
      }

      if (categoria.global == 1 && categoria.id_plataforma != ID_PLATAFORMA) {
        botones_accion = ``;
      } else {
        botones_accion = `<button class="btn btn-sm btn-primary" onclick="editar_categoria(${categoria.id_linea})"><i class="fa-solid fa-pencil"></i>Editar</button>
        <button class="btn btn-sm btn-danger" onclick="eliminar_categoria(${categoria.id_linea})"><i class="fa-solid fa-trash-can"></i>Borrar</button>`;
      }
      content += `
                <tr>
                  <td>${categoria.id_linea}</td>
                    <td>${categoria.nombre_linea}</td>
                    <td>${cargar_imagen}</td>
                    <td>${online}</td>
                    <td>${categoria.descripcion_linea}</td>
                    <td>${tipo}</td>
                    <td>${estado_linea}</td>
                    <td>
                    ${botones_accion}
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
    dataType: "json", // Asegurarse de que la respuesta se trata como JSON
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
    dataType: "json",
    success: function (response) {
      console.log(response); // Depuración: Mostrar la respuesta en la consola

      if (response && response.length > 0) {
        // Obtener el primer objeto de la respuesta
        const data = response[0];

        // Verificar que los elementos existen antes de asignarles valores
        if (
          $("#editar_nombre_linea").length > 0 &&
          $("#editar_descripcion_linea").length > 0 &&
          $("#editar_online").length > 0 &&
          $("#editar_tipo").length > 0 &&
          $("#editar_padre").length > 0 &&
          $("#editar_estado").length > 0
        ) {
          console.log("Elementos encontrados, actualizando valores...");
          // Llenar los inputs del modal con los datos recibidos
          $("#editar_id_linea").val(data.id_linea);
          $("#editar_nombre_linea").val(data.nombre_linea);
          $("#editar_descripcion_linea").val(data.descripcion_linea);
          $("#editar_online").val(data.online);
          $("#editar_tipo").val(data.tipo);
          $("#editar_padre").val(data.padre);
          $("#editar_estado").val(data.estado_linea);
          $("#orden_editar").val(data.orden);
          
          // Abrir el modal
          $("#editar_categoriaModal").modal("show");
        } else {
          console.error("Uno o más elementos no se encontraron en el DOM.");
        }
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

function agregar_imagenCategoria(id,imagen) {
  $("#id_imagenCategoria").val(id);
  $("#imagePreview")
            .attr("src", SERVERURL + imagen)
            .show();
            console.log(SERVERURL + imagen);

  $("#imagen_categoriaModal").modal("show");
}

window.addEventListener("load", async () => {
  await initDataTable();
});
