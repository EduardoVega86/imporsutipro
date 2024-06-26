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
    { className: "centered", targets: [0, 1, 2, 3, 4, 5, 6] },
    { orderable: false, targets: 0 }, //ocultar para columna 0 el ordenar columna
  ],
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
        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
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
        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
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
      "" + SERVERURL + "productos/obtener_productos"
    );
    const productos = await response.json();
    let content = ``;
    let cargar_imagen = "";
    let subir_marketplace = "";
    let producto_variable = "";
    let enlace_imagen = "";
    let enviaCliente= "";
    productos.forEach((producto, index) => {
      enlace_imagen = obtenerURLImagen(producto.image_path, SERVERURL);
      if (!producto.image_path) {
        cargar_imagen = `<i class="bx bxs-camera-plus" onclick="agregar_imagenProducto(${producto.id_producto},'${enlace_imagen}')"></i>`;
      } else {
        cargar_imagen = `<img src="${enlace_imagen}" class="icon-button" onclick="agregar_imagenProducto(${producto.id_producto},'${enlace_imagen}')" alt="Agregar imagen" width="50px">`;
      }
      if (producto.drogshipin == 0) {
        subir_marketplace = `<box-icon name='cloud-upload' color='#54DD10' id="icono_subida_${producto.id_producto}" onclick="subir_marketplace(${producto.id_producto})"></box-icon>`;
      } else {
        subir_marketplace = `<box-icon name='cloud-download' color='red' id="icono_bajada_${producto.id_producto}" onclick="bajar_marketplace(${producto.id_producto})"></box-icon>`;
      }

      if (producto.producto_variable == 0) {
        producto_variable = ``;
        enviaCliente = `<i class="fa-regular fa-paper-plane" onclick="enviar_cliente(${producto.id_producto},'${producto.sku}',${producto.pvp},${producto.id_inventario})""></i>`;
      } else {
        producto_variable = `<img src="https://new.imporsuitpro.com/public/img/atributos.png" width="30px" id="buscar_traking" alt="buscar_traking" onclick="abrir_modalInventarioVariable(${producto.id_producto})">`;
        enviaCliente = `<i style="color:red;" class="fa-regular fa-paper-plane" onclick="abrir_modalSeleccionAtributo(${producto.id_producto},'${producto.sku}',${producto.pvp},${producto.id_inventario})"></i>`;
        
      }
      content += `
                <tr>
                    <td><input type="checkbox" class="selectCheckbox" data-id="${producto.id_producto}"></td>
                    <td>${producto.id_producto}</td>
                    <td>${cargar_imagen}</td>
                    <td>${producto.codigo_producto}</td>
                    <td>${producto.nombre_producto}</td>
                    <td>${producto.destacado}</td>
                    <td>${producto.saldo_stock}</td>
                    <td>${producto.costo_producto}</td>
                    <td>${producto.pcp}</td>
                    <td>${producto.pvp}</td>
                    <td>${producto.pref}</td>
                    <td>logo landing</td>
                    <td><i class="bx bxs-camera-plus" onclick="agregar_imagenProducto(${producto.id_producto}, '${enlace_imagen}')"></i></td>
                    <td>${subir_marketplace}</td>
                    <td>${enviaCliente}</td>
                    <td>${producto_variable}</td>
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

//abrir modal de seleccion de producto con atributo especifico
function abrir_modalSeleccionAtributo(id) {
  $("#id_productoSeleccionado").val(id);
  initDataTableSeleccionProductoAtributo();
  $("#seleccionProdcutoAtributoModal").modal("show");
}

//enviar cliente
function enviar_cliente(id, sku, pvp, id_inventario) {
  // Crear un objeto FormData y agregar los datos
  const formData = new FormData();
  formData.append("cantidad", 1);
  formData.append("precio", pvp);
  formData.append("id_producto", id);
  formData.append("sku", sku);
  formData.append("id_inventario", id_inventario);

  $.ajax({
    type: "POST",
    url: "" + SERVERURL + "marketplace/agregarTmp",
    data: formData,
    processData: false,
    contentType: false,
    success: function (response2) {
      response2 = JSON.parse(response2);
      console.log(response2);
      console.log(response2[0]);
      if (response2.status == 500) {
        Swal.fire({
          icon: "error",
          title: response2.title,
          text: response2.message,
        });
      } else if (response2.status == 200) {
        window.location.href =
          SERVERURL + "Pedidos/nuevo?id_producto=" + id + "&sku=" + sku;
      }
    },
    error: function (xhr, status, error) {
      console.error("Error en la solicitud AJAX:", error);
      alert("Hubo un problema al agregar el producto temporalmente");
    },
  });
}

// Función para vaciar temporalmente los pedidos
const vaciarTmpPedidos = async () => {
  try {
    const response = await fetch("" + SERVERURL + "marketplace/vaciarTmp");
    if (!response.ok) {
      throw new Error("Error al vaciar los pedidos temporales");
    }
    const data = await response.json();
    console.log("Respuesta de vaciarTmp:", data);
  } catch (error) {
    console.error("Error al hacer la solicitud:", error);
  }
};

//subida Masiva
function customizeButtons() {
  document.querySelectorAll(".buttons-html5").forEach((element) => {
    element.classList.remove(
      "btn",
      "btn-secondary",
      "buttons-excel",
      "buttons-html5"
    );
    element.classList.add(
      "btn",
      "btn-primary",
      "px-2",
      "py-1",
      "rounded",
      "mx-1"
    );
  });
}

function toggleSelectAll() {
  const selectAllCheckbox = document.getElementById("selectAll");
  const checkboxes = document.querySelectorAll(".selectCheckbox");
  checkboxes.forEach(
    (checkbox) => (checkbox.checked = selectAllCheckbox.checked)
  );
}

document
  .getElementById("subidaMasiva_marketplace")
  .addEventListener("click", async () => {
    const selectedCheckboxes = document.querySelectorAll(
      ".selectCheckbox:checked"
    );
    const ids = Array.from(selectedCheckboxes).map((checkbox) =>
      checkbox.getAttribute("data-id")
    );

    if (ids.length > 0) {
      for (let id of ids) {
        $.ajax({
          type: "POST",
          url: SERVERURL + "productos/subir_marketplace",
          data: { id: id }, // Enviar el ID como un objeto
          dataType: "json", // Asegurarse de que la respuesta se trata como JSON
          success: function (response) {},
          error: function (xhr, status, error) {
            console.error("Error en la solicitud AJAX:", error);
            alert("Hubo un problema al subir al marketplace");
          },
        });
      }
      toastr.success("Subida masiva completada", "NOTIFICACIÓN", {
        positionClass: "toast-bottom-center",
      });
      reloadDataTableProductos();
    } else {
      alert("No hay productos seleccionados");
    }
  });

function eliminarProducto(id) {
  $.ajax({
    type: "POST",
    url: SERVERURL + "productos/eliminar_producto",
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
          $("#categoria").append(
            new Option(categoria.nombre_linea, categoria.id_linea)
          );
          $("#editar_categoria").append(
            new Option(categoria.nombre_linea, categoria.id_linea)
          );
          $("#categoria_filtro").append(
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

//cargar select de bodega
$(document).ready(function () {
  // Realiza la solicitud AJAX para obtener la lista de bodegas
  $.ajax({
    url: SERVERURL + "productos/listar_bodegas",
    type: "GET",
    dataType: "json",
    success: function (response) {
      // Asegúrate de que la respuesta es un array
      if (Array.isArray(response)) {
        response.forEach(function (bodega) {
          // Agrega una nueva opción al select por cada bodega
          $("#bodega").append(new Option(bodega.nombre, bodega.id));
          $("#editar_bodega").append(new Option(bodega.nombre, bodega.id));
        });
      } else {
        console.log("La respuesta de la API no es un array:", response);
      }
    },
    error: function (error) {
      console.error("Error al obtener la lista de bodegas:", error);
    },
  });

  // Evento change para el select de categoría y filtrar por categorias
  $("#categoria_filtro").change(function () {
    let categoriaId = $(this).val();
    filtrarProductosPorCategoria(categoriaId);
  });
});

//filtrar por categorias
const filtrarProductosPorCategoria = async (categoriaId) => {
  try {
    const response = await fetch(
      `${SERVERURL}productos/obtener_productos_categoria/${categoriaId}` // falta que me creen esta api
    );
    const productos = await response.json();

    let content = ``;
    let cargar_imagen = "";
    let subir_marketplace = "";
    let producto_variable = "";
    productos.forEach((producto, index) => {
      if (!producto.image_path) {
        cargar_imagen = `<i class="bx bxs-camera-plus" onclick="agregar_imagenProducto(${producto.id_producto})"></i>`;
      } else {
        cargar_imagen = `<img src="${SERVERURL}${producto.image_path}" class="icon-button" onclick="agregar_imagenProducto(${producto.id_producto})" alt="Agregar imagen" width="50px">`;
      }
      if (producto.drogshipin == 0) {
        subir_marketplace = `<box-icon name='cloud-upload' id="icono_subida_${producto.id_producto}" onclick="subir_marketplace(${producto.id_producto})"></box-icon>`;
      } else {
        subir_marketplace = `<box-icon name='cloud-download' id="icono_bajada_${producto.id_producto}" onclick="bajar_marketplace(${producto.id_producto})"></box-icon>`;
      }

      if (producto.producto_variable == 0) {
        producto_variable = ``;
      } else {
        producto_variable = `<img src="https://new.imporsuitpro.com/public/img/atributos.png" width="30px" id="buscar_traking" alt="buscar_traking" onclick="abrir_modalInventarioVariable(${producto.id_producto})">`;
      }
      content += `
                  <tr>
                      <td>${producto.id_producto}</td>
                      <td>${cargar_imagen}</td>
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
                      <td>${subir_marketplace})</td>
                      <td>${producto_variable}</td>
                      <td>
                          <button class="btn btn-sm btn-primary" onclick="editarProducto(${producto.id_producto})"><i class="fa-solid fa-pencil"></i>Editar</button>
                          <button class="btn btn-sm btn-danger" onclick="eliminarProducto(${producto.id_producto})"><i class="fa-solid fa-trash-can"></i>Borrar</button>
                      </td>
                  </tr>`;
    });
    document.getElementById("tableBody_productos").innerHTML = content;
    dataTableProductos.clear().rows.add($(content)).draw(); // Actualiza la DataTable
  } catch (ex) {
    alert(ex);
  }
};

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

function editarProducto(id) {
  $.ajax({
    type: "GET",
    url: SERVERURL + "productos/obtener_producto/" + id,
    dataType: "json",
    success: function (response) {
      console.log(response); // Depuración: Mostrar la respuesta en la consola

      if (response && response.length > 0) {
        const data = response[0];

        if (
          $("#editar_codigo").length > 0 &&
          $("#editar_nombre").length > 0 &&
          $("#editar_descripcion").length > 0 &&
          $("#editar_categoria").length > 0 &&
          $("#editar_formato_pagina").length > 0 &&
          $("#editar_ultimo_costo").length > 0 &&
          $("#editar_precio_proveedor").length > 0 &&
          $("#editar_precio_venta").length > 0 &&
          $("#editar_precio_referencial").length > 0 &&
          $("#editar_maneja_inventario").length > 0 &&
          $("#editar_stock_inicial").length > 0
        ) {
          console.log("Elementos encontrados, actualizando valores...");
          // Llenar los inputs del modal con los datos recibidos
          $("#editar_id_producto").val(data.id_producto);
          $("#editar_codigo").val(data.codigo_producto);
          $("#editar_nombre").val(data.nombre_producto);
          $("#editar_descripcion").val(data.descripcion_producto);
          $("#editar_categoria").val(data.id_linea_producto);
          $("#editar_bodega").val(data.bodega);
          $("#editar_producto_variable").val(data.id_variante);
          $("#editar_formato_pagina").val(data.formato);
          $("#editar_ultimo_costo").val(data.costo_producto);
          $("#editar_precio_proveedor").val(data.pcp);
          $("#editar_precio_venta").val(data.pvp);
          $("#editar_precio_referencial").val(data.pref);
          $("#editar_maneja_inventario").val(data.inv_producto);
          $("#editar_stock_inicial").val(data.stock_inicial);

          // Abrir el modal
          $("#editar_productoModal").modal("show");
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

function subir_marketplace(id) {
  $.ajax({
    type: "POST",
    url: SERVERURL + "productos/subir_marketplace",
    data: { id: id }, // Enviar el ID como un objeto
    dataType: "json", // Asegurarse de que la respuesta se trata como JSON
    success: function (response) {
      // Mostrar alerta de éxito
      if (response.status == 500) {
        toastr.error(
          "EL PRODUCTO NO SE AGREGRO AL MARKETPLACE CORRECTAMENTE",
          "NOTIFICACIÓN",
          {
            positionClass: "toast-bottom-center",
          }
        );
      } else if (response.status == 200) {
        toastr.success("PRODUCTO AGREGADO CORRECTAMENTE", "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });
        /* initDataTableProductos(); */
        /* $("#icono_subida_" + id).hide(); */
        reloadDataTableProductos();
      }
    },
    error: function (xhr, status, error) {
      console.error("Error en la solicitud AJAX:", error);
      alert("Hubo un problema al subir al marketplace");
    },
  });
}

function bajar_marketplace(id) {
  $.ajax({
    type: "POST",
    url: SERVERURL + "productos/bajar_marketplace",
    data: { id: id }, // Enviar el ID como un objeto
    dataType: "json", // Asegurarse de que la respuesta se trata como JSON
    success: function (response) {
      // Mostrar alerta de éxito
      if (response.status == 500) {
        toastr.error(
          "EL PRODUCTO NO SE BAJO DEL MARKETPLACE CORRECTAMENTE",
          "NOTIFICACIÓN",
          {
            positionClass: "toast-bottom-center",
          }
        );
      } else if (response.status == 200) {
        toastr.success(
          "PRODUCTO BAJADO DEL MARKETPLACE CORRECTAMENTE",
          "NOTIFICACIÓN",
          {
            positionClass: "toast-bottom-center",
          }
        );
        /* initDataTableProductos(); */
        /* $("#icono_bajada_" + id).hide(); */
        reloadDataTableProductos();
      }
    },
    error: function (xhr, status, error) {
      console.error("Error en la solicitud AJAX:", error);
      alert("Hubo un problema al subir al marketplace");
    },
  });
}

function agregar_imagenProducto(id, imagen) {
  $("#id_imagenproducto").val(id);

  if (imagen) {
    $("#imagePreview").attr("src", imagen).show();
  } else {
    $("#imagePreview").hide();
  }

  $("#imagen_productoModal").modal("show");
}
window.addEventListener("load", async () => {
  await initDataTableProductos();
});

function abrir_modalInventarioVariable(id) {
  $("#id_productoVariable").val(id);
  initDataTableDetalleInventario();
  $("#inventario_variableModal").modal("show");
}

// Ejecutar la función cuando la página se haya cargado
window.addEventListener("load", vaciarTmpPedidos);
