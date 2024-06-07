let dataTableProductos;
let dataTableProductosIsInitialized = false;

const dataTableProductosOptions = {
  columnDefs: [
    { className: "centered", targets: [0, 1, 2, 3, 4, 5, 6] },
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

const initDataTableProductos = async () => {
  if (dataTableProductosIsInitialized) {
    dataTableProductos.destroy();
  }

  await listProductos();

  dataTableProductos = $("#datatable_productos").DataTable(dataTableProductosOptions);

  dataTableProductosIsInitialized = true;
};

const listProductos = async () => {
  try {
    const response = await fetch(
      "" + SERVERURL + "productos/obtener_productos"
    );
    const productos = await response.json();

    let content = ``;
    productos.forEach((producto, index) => {
      content += `
                <tr>
                    <td>${producto.id_producto}</td>
                    <td><i class="fas fa-camera icon-button" data-toggle="modal" data-target="#imagen_productoModal"></i></td>
                    <td>${producto.codigo_producto}</td>
                    <td>${producto.nombre_producto}</td>
                    <td>${producto.destacado}</td>
                    <td>${producto.saldo_stock}</td>
                    <td>${producto.costo_producto}</td>
                    <td>${producto.pcp}</td>
                    <td>${producto.pvp}</td>
                    <td>${producto.pref}</td>
                    <td>logo landing</td>
                    <td>logo agregar imagen</td>
                    <td>logo agregar a market</td>
                    <td>logo agregar atributos</td>
                    <td>
                        <button class="btn btn-sm btn-primary" onclick="editarProducto(${producto.id_producto})"><i class="fa-solid fa-pencil"></i>Editar</button>
                        <button class="btn btn-sm btn-danger" onclick="eliminarProducto(${producto.id_producto})"><i class="fa-solid fa-trash-can"></i>Borrar</button>
                    </td>
                </tr>`;
    });
    document.getElementById("tableBody_productos").innerHTML = content;
  } catch (ex) {
    alert(ex);
  }
};

function eliminarProducto(id) {
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
  
  function editarProducto(id) {
    $.ajax({
        type: "GET", // Cambiado de POST a GET ya que estamos obteniendo datos
        url: SERVERURL + "productos/obtener_producto/" + id, // Añadir el id en la URL
        dataType: 'json',
        success: function (response) {
            console.log(response); // Depuración: Mostrar la respuesta en la consola
  
            if (response) {
                const data = response;
  
                if ($('#codigo').length > 0 && 
                    $('#nombre').length > 0 && 
                    $('#descripcion').length > 0 && 
                    $('#categoria').length > 0 && 
                    $('#formato-pagina').length > 0 &&
                    $('#ultimo_costo').length > 0 &&
                    $('#utilidad').length > 0 &&
                    $('#precio-proveedor').length > 0 &&
                    $('#precio-venta').length > 0 &&
                    $('#precio-referencial-valor').length > 0 &&
                    $('#maneja-inventario').length > 0 &&
                    $('#stock-inicial').length > 0) {
                    
                    console.log('Elementos encontrados, actualizando valores...');
                    // Llenar los inputs del modal con los datos recibidos
                    $('#codigo').val(data.codigo_producto);
                    $('#nombre').val(data.nombre_producto);
                    $('#descripcion').val(data.descripcion_producto);
                    $('#categoria').val(data.id_linea_producto);
                    $('#formato-pagina').val(data.pagina_web);
                    $('#ultimo_costo').val(data.costo_producto);
                    $('#utilidad').val(data.utilidad);
                    $('#precio-proveedor').val(data.pcp);
                    $('#precio-venta').val(data.pvp);
                    $('#precio-referencial-valor').val(data.pref);
                    $('#maneja-inventario').val(data.inv_producto);
                    $('#stock-inicial').val(data.stock_inicial);

                    // Abrir el modal
                    $('#editar_productoModal').modal('show');
                } else {
                    console.error("Uno o más elementos no se encontraron en el DOM.");
                }
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
