let dataTableProductos;
let dataTableProductosIsInitialized = false;

function getFecha() {
  let fecha = new Date();
  let mes = fecha.getMonth() + 1;
  let dia = fecha.getDate();
  let anio = fecha.getFullYear();
  let fechaHoy = anio + "-" + mes + "-" + dia;
  return fechaHoy;
}

const dataTableProductosOptions = {
  columnDefs: [
    { className: "centered", targets: [0, 1, 2, 3, 4, 5] },
    { orderable: false, targets: 0 }, //ocultar para columna 0 el ordenar columna
  ],
  order: [[2, "desc"]], // Ordenar por la primera columna (fecha) en orden descendente
  pageLength: 25,
  lengthMenu: [25, 50, 100, 200],
  destroy: true,
  responsive: true,
  autoWidth: true,
  bAutoWidth: true,
  dom: '<"d-flex w-full justify-content-between"lBf><t><"d-flex justify-content-between"ip>',
  buttons: [
    {
      extend: "excelHtml5",
      text: 'Excel <i class="fa-solid fa-file-excel"></i>',
      title: "Panel de Control: Usuarios",
      titleAttr: "Exportar a Excel",
      exportOptions: {
        columns: [0, 1, 2, 3, 4, 5],
      },
      filename: "Productos" + "_" + getFecha(),
      footer: true,
      className: "btn-excel",
    },
    {
      extend: "csvHtml5",
      text: 'CSV <i class="fa-solid fa-file-csv"></i>',
      title: "Panel de Control: Productos",
      titleAttr: "Exportar a CSV",
      exportOptions: {
        columns: [0, 1, 2, 3, 4, 5],
      },
      filename: "Productos" + "_" + getFecha(),
      footer: true,
      className: "btn-csv",
    },
  ],
  language: {
    lengthMenu: "Mostrar _MENU_ ",
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

const reloadDataTableProductos = async () => {
  const currentPage = dataTableProductos.page();
  dataTableProductos.destroy();
  await listProductos();
  dataTableProductos = $("#datatable_productos").DataTable(
    dataTableProductosOptions
  );
  dataTableProductos.page(currentPage).draw(false);
  dataTableProductosIsInitialized = true;
  customizeButtons();
};

const initDataTableProductos = async () => {
  if (dataTableProductosIsInitialized) {
    dataTableProductos.destroy();
  }
  await listProductos();
  dataTableProductos = $("#datatable_productos").DataTable(
    dataTableProductosOptions
  );
  dataTableProductosIsInitialized = true;
  customizeButtons();
  document
    .getElementById("selectAll")
    .addEventListener("change", toggleSelectAll);
};

const listProductos = async () => {
  try {
    const response = await fetch(
      "" + SERVERURL + "productos/obtener_productos_tienda"
    );
    const productos = await response.json();
    let content = ``;
    let cargar_imagen = "";
    let enlace_imagen = "";

    productos.forEach((producto) => {
      enlace_imagen = obtenerURLImagen(producto.imagen_principal_tienda, SERVERURL);
      if (!producto.imagen_principal_tienda) {
        cargar_imagen = `<i class="bx bxs-camera-plus"></i>`;
      } else {
        cargar_imagen = `<img src="${enlace_imagen}" class="icon-button" alt="Agregar imagen" width="50px">`;
      }

      let destacadoBtn = "";
      if (producto.destacado_tienda == 0){
        destacadoBtn = `<button class="btn-destacado-no" onclick="toggleDestacado(${producto.id_producto_tienda}, 1)">NO</button>`;
      } else if (producto.destacado_tienda == 1){
        destacadoBtn = `<button class="btn-destacado-si" onclick="toggleDestacado(${producto.id_producto_tienda}, 0)">SI</button>`
      }
      

      content += `
          <tr>
            <td>${producto.nombre_producto_tienda}</td>
            <td>${cargar_imagen}</td>
            <td>${destacadoBtn}</td>
            <td><a href='' role='button'><i class="fa-solid fa-laptop-code" style="font-size:25px;"></i></a></td>
            <td>${producto.pvp_tienda}</td>
            <td>${producto.pref_tienda}</td>
            <td>
              <button class="btn btn-sm btn-primary" onclick="editarProducto_tienda(${producto.id_producto_tienda})"><i class="fa-solid fa-pencil"></i> Editar</button>
              <button class="btn btn-sm btn-danger" onclick="eliminarProducto_tienda(${producto.id_producto_tienda})"><i class="fa-solid fa-trash-can"></i> Borrar</button>
            </td>
          </tr>`;
    });
    document.getElementById("tableBody_productos").innerHTML = content;
  } catch (ex) {
    alert(ex);
  }
};

const toggleDestacado = async (idProducto, nuevoEstado) => {
    try {
      const formData = new FormData();
      formData.append('id_producto_tienda', idProducto);
      formData.append('destacado', nuevoEstado);
  
      const response = await fetch(`${SERVERURL}productos/agregarDestacado`, {
        method: 'POST',
        body: formData
      });
  
      if (response.ok) {
        listProductos(); // Actualizar la lista de productos
      } else {
        alert('Error al actualizar el estado destacado');
      }
    } catch (ex) {
      alert(ex);
    }
  };

//abrir modal de seleccion de producto con atributo especifico
function abrir_modalSeleccionAtributo(id) {
  $("#id_productoSeleccionado").val(id);
  initDataTableSeleccionProductoAtributo();
  $("#seleccionProdcutoAtributoModal").modal("show");
}

function eliminarProducto_tienda(id) {
  $.ajax({
    type: "POST",
    url: SERVERURL + "productos/eliminar_producto_tienda/"+id,
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
          initDataTableProductos();
        });
      }
    },
    error: function (xhr, status, error) {
      console.error("Error en la solicitud AJAX:", error);
      alert("Hubo un problema al eliminar la categoría");
    },
  });
}

//cargar select categoria
$(document).ready(function () {
  // Realiza la solicitud AJAX para obtener la lista de categorias
  $.ajax({
    url: SERVERURL + "productos/cargar_categorias",
    type: "GET",
    dataType: "json",
    success: function (response) {
      // Asegúrate de que la respuesta es un array
      if (Array.isArray(response)) {
        response.forEach(function (categoria) {
          // Agrega una nueva opción al select por cada categoria
          $("#editar_categoria").append(
            new Option(categoria.nombre_linea, categoria.id_linea)
          );
        });
      } else {
        console.log("La respuesta de la API no es un array:", response);
      }
    },
    error: function (error) {
      console.error("Error al obtener la lista de categorias:", error);
    },
  });
});

function obtenerURLImagen(imagePath, serverURL) {
  // Verificar si el imagePath no es null
  if (imagePath) {
    // Verificar si el imagePath ya es una URL completa
    if (imagePath.startsWith("http://") || imagePath.startsWith("https://")) {
      // Si ya es una URL completa, retornar solo el imagePath
      return imagePath;
    } else {
      // Si no es una URL completa, agregar el serverURL al inicio
      return `${serverURL}${imagePath}`;
    }
  } else {
    // Manejar el caso cuando imagePath es null
    console.error("imagePath es null o undefined");
    return null; // o un valor por defecto si prefieres
  }
}

function editarProducto_tienda(id) {
  $.ajax({
    type: "GET",
    url: SERVERURL + "productos/obtener_producto_tienda/" + id,
    dataType: "json",
    success: function (response) {
      console.log(response);

      if (response && response.length > 0) {
        const data = response[0];

        // Llenar los inputs del modal con los datos recibidos
        $("#editar_id_producto").val(data.id_producto_tienda);
        $("#editar_nombre_productoTienda").val(data.nombre_producto_tienda);
        $("#editar_pvpTienda").val(data.pvp_tienda);
        $("#editar_prefTienda").val(data.pref_tienda);
        $("#editar_categoria").val(response[0].id_categoria_tienda).change();

        // Abrir el modal
        $("#editar_productoTiendaModal").modal("show");
      } else {
        console.error("La respuesta está vacía o tiene un formato incorrecto.");
      }
    },
    error: function (xhr, status, error) {
      console.error("Error en la solicitud AJAX:", error);
      alert("Hubo un problema al obtener la información del producto");
    },
  });
}

window.addEventListener("load", async () => {
  await initDataTableProductos();
});
