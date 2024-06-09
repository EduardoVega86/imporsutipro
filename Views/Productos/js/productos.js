let dataTableProductos;
let dataTableProductosIsInitialized = false;
function getFecha (){
  let fecha = new Date();
  let mes = fecha.getMonth() + 1;
  let dia = fecha.getDate();
  let anio = fecha.getFullYear();
  let fechaHoy = anio + "-" + mes + "-" + dia;
  return fechaHoy;
}
const dataTableProductosOptions = {
  columnDefs: [
    { className: "centered", targets: [0, 1, 2, 3, 4, 5, 6] },
  ],
  pageLength: 10,
  destroy: true,
  dom: '<"d-flex justify-content-between"lBf><t><"d-flex justify-content-between"ip>',
  buttons: [
    {
      extend: "copyHtml5",
      text: '<i class="bx bx-copy"></i>',
      titleAttr: "Copiar",
      className: "btn btn-primary btn-sm",
      exportOptions: {
        columns: [0, 1, 2, 3, 4 ,5 , 6 ,7 ,8 ,9, 10 ,11 ,12],
      },
    },
    {
      extend: "excelHtml5",
      text: 'Excel <i class="fa-solid fa-file-excel" style="color: #2e8500;"></i>',
      title: "Panel de Control: Usuarios",
      titleAttr: "Exportar a Excel",
      exportOptions: {
        columns: [0, 1, 2, 3, 4 ,5 , 6 ,7 ,8 ,9, 10 ,11 ,12],
      },
      filename: "Productos" + "_" + getFecha(),
      footer: true,
    },
    {
      extend: "csvHtml5",
      text: 'CSV <i class="fa-solid fa-file-csv" style="color: #2e8500;"></i>',
      title: "Panel de Control: Productos",
      titleAttr: "Exportar a CSV",
      exportOptions: {
        columns: [0, 1, 2, 3, 4 ,5 , 6 ,7 ,8 ,9, 10 ,11 ,12],
      },
      filename: "Productos" + "_" + getFecha(),
      footer: true,
    },
  ],
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
  
  //cargar select categoria
   $(document).ready(function() {
    // Realiza la solicitud AJAX para obtener la lista de categorias
    $.ajax({
        url: SERVERURL + 'productos/cargar_categorias',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            // Asegúrate de que la respuesta es un array
            if (Array.isArray(response)) {
                response.forEach(function(categoria) {
                    // Agrega una nueva opción al select por cada categoria
                    $('#categoria').append(new Option(categoria.nombre_linea, categoria.id_linea));
                    $('#editar_categoria').append(new Option(categoria.nombre_linea, categoria.id_linea));
                });
            } else {
                console.log('La respuesta de la API no es un array:', response);
            }
        },
        error: function(error) {
            console.error('Error al obtener la lista de categorias:', error);
        }
    });
});

//cargar select de bodega 
$(document).ready(function() {
    // Realiza la solicitud AJAX para obtener la lista de bodegas
    $.ajax({
        url: SERVERURL + 'productos/listar_bodegas',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            // Asegúrate de que la respuesta es un array
            if (Array.isArray(response)) {
                response.forEach(function(bodega) {
                    // Agrega una nueva opción al select por cada bodega
                    $('#bodega').append(new Option(bodega.nombre, bodega.id));
                    $('#editar_bodega').append(new Option(bodega.nombre, bodega.id));
                });
            } else {
                console.log('La respuesta de la API no es un array:', response);
            }
        },
        error: function(error) {
            console.error('Error al obtener la lista de bodegas:', error);
        }
    });
});


function editarProducto(id) {
    $.ajax({
        type: "GET",
        url: SERVERURL + "productos/obtener_producto/" + id,
        dataType: 'json',
        success: function (response) {
            console.log(response); // Depuración: Mostrar la respuesta en la consola
  
            if (response && response.length > 0) {
                const data = response[0];
  
                if ($('#editar_codigo').length > 0 && 
                    $('#editar_nombre').length > 0 && 
                    $('#editar_descripcion').length > 0 && 
                    $('#editar_categoria').length > 0 && 
                    $('#editar_formato-pagina').length > 0 &&
                    $('#editar_ultimo_costo').length > 0 &&
                    $('#editar_utilidad').length > 0 &&
                    $('#editar_precio_proveedor').length > 0 &&
                    $('#editar_precio_venta').length > 0 &&
                    $('#editar_precio_referencial').length > 0 &&
                    $('#editar_maneja_inventario').length > 0 &&
                    $('#editar_stock_inicial').length > 0) {
                    
                    console.log('Elementos encontrados, actualizando valores...');
                    // Llenar los inputs del modal con los datos recibidos
                    $('#editar_id_producto').val(data.id_producto);
                    $('#editar_codigo').val(data.codigo_producto);
                    $('#editar_nombre').val(data.nombre_producto);
                    $('#editar_descripcion').val(data.descripcion_producto);
                    $('#editar_categoria').val(data.id_linea_producto);
                    $('#editar_bodega').val(data.bodega);
                    $('#editar_formato-pagina').val(data.formato);
                    $('#editar_ultimo_costo').val(data.costo_producto);
                    $('#editar_precio_proveedor').val(data.pcp);
                    $('#editar_precio_venta').val(data.pvp);
                    $('#editar_precio_referencial').val(data.pref);
                    $('#editar_maneja_inventario').val(data.inv_producto);
                    $('#editar_stock_inicial').val(data.stock_inicial);

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
