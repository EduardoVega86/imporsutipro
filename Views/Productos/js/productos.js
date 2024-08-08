let dataTableProductos;
let dataTableProductosIsInitialized = false;

function getFecha() {
  let fecha = new Date();
  let mes = fecha.getMonth() + 1;
  let dia = fecha.getDate();
  let anio = fecha.getFullYear();
  let fechaHoy = `${anio}-${mes}-${dia}`;
  return fechaHoy;
}

const dataTableProductosOptions = {
  columnDefs: [
    {
      className: "centered",
      targets: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17],
    },
    { orderable: false, targets: 0 },
  ],
  order: [[2, "desc"]],
  pageLength: 25,
  lengthMenu: [25, 50, 100, 200],
  destroy: true,
  responsive: true,
  autoWidth: true,
  bAutoWidth: true,
  processing: true,
  serverSide: true,
  ajax: {
    url: SERVERURL + "productos/obtener_productos",
    type: "POST",
    dataSrc: function (json) {
      console.log("Data recibida:", json);
      return json.data;
    },
  },
  columns: [
    {
      data: null,
      defaultContent: '<input type="checkbox" class="selectCheckbox">',
    }, // Checkbox columna
    { data: "id_inventario" }, // ID Inventario
    {
      data: "image_path",
      render: function (data, type, row) {
        const enlace_imagen = obtenerURLImagen(data, SERVERURL);
        return data
          ? `<img src="${enlace_imagen}" class="icon-button" onclick="agregar_imagenProducto(${row.id_producto},'${enlace_imagen}')" alt="Agregar imagen" width="50px">`
          : `<i class="bx bxs-camera-plus" onclick="agregar_imagenProducto(${row.id_producto},'${enlace_imagen}')"></i>`;
      },
    }, // Imagenes
    { data: "codigo_producto" }, // Código
    { data: "nombre_producto" }, // Producto
    { data: "destacado" }, // Destacado
    { data: "saldo_stock" }, // Existencia
    { data: "costo_producto" }, // Costo
    { data: "pcp" }, // P. Proveedor
    { data: "pvp" }, // PVP
    { data: "pref" }, // Precio Referencial
    {
      data: "landing",
      render: function (data, type, row) {
        return `<a href='${
          SERVERURL + "productos/landing/" + row.id_producto
        }' role='button'><i class="fa-solid fa-laptop-code" style="font-size:25px;"></i></a>`;
      },
    }, // Landing
    {
      data: "drogshipin",
      render: function (data, type, row) {
        return data == 0
          ? `<box-icon name='cloud-upload' style='cursor:pointer' color='#54DD10' id="icono_subida_${row.id_producto}" onclick="subir_marketplace(${row.id_producto})"></box-icon></br><span>Agregar</span>`
          : `<box-icon name='cloud-download' style='cursor:pointer' color='red' id="icono_bajada_${row.id_producto}" onclick="bajar_marketplace(${row.id_producto})"></box-icon></br><span>Quitar</span>`;
      },
    }, // Marketplace
    {
      data: "producto_variable",
      render: function (data, type, row) {
        return data == 0
          ? `<i class="fa-regular fa-paper-plane" style='cursor:pointer' onclick="enviar_cliente(${row.id_producto},'${row.sku}',${row.pvp},${row.id_inventario})"></i>`
          : `<i style="color:red;" class="fa-regular fa-paper-plane" style='cursor:pointer' onclick="abrir_modalSeleccionAtributo(${row.id_producto},'${row.sku}',${row.pvp},${row.id_inventario})"></i>`;
      },
    }, // Enviar a cliente
    {
      data: "producto_variable",
      render: function (data, type, row) {
        return data == 0
          ? ``
          : `<img src="https://new.imporsuitpro.com/public/img/atributos.png" width="30px" id="buscar_traking" alt="buscar_traking" onclick="abrir_modalInventarioVariable(${row.id_producto})">`;
      },
    }, // Atributos
    {
      data: null,
      render: function (data, type, row) {
        return `<div class="btn btn-warning" onclick="abrir_modal_idInventario(${row.id_producto})"><span>Ver</span></div>`;
      },
    }, // Enviar a Tienda
    {
      data: "producto_privado",
      render: function (data, type, row) {
        return `<input type="checkbox" class="agregarPrivadoCheckbox" data-id="${
          row.id_producto
        }" onchange="toggleAgregarPrivado(this)" ${
          data == 1 ? "checked" : ""
        }>`;
      },
    }, // Agregar Privado
    {
      data: null,
      render: function (data, type, row) {
        return `<button class="btn btn-sm btn-primary" onclick="editarProducto(${row.id_producto})"><i class="fa-solid fa-pencil"></i>Editar</button>
                <button class="btn btn-sm btn-danger" onclick="eliminarProducto(${row.id_producto})"><i class="fa-solid fa-trash-can"></i>Borrar</button>`;
      },
    }, // Acciones
  ],
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
      filename: `Productos_${getFecha()}`,
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
      filename: `Productos_${getFecha()}`,
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
  const currentLength = dataTableProductos.page.len();
  dataTableProductos.destroy();
  await listProductos();
  dataTableProductos = $("#datatable_productos").DataTable(
    dataTableProductosOptions
  );
  dataTableProductos.page.len(currentLength).draw();
  dataTableProductos.page(currentPage).draw(false);
  dataTableProductosIsInitialized = true;
  customizeButtons();
};

const initDataTableProductos = async () => {
  if (dataTableProductosIsInitialized) {
    dataTableProductos.destroy();
  }
  dataTableProductos = $("#datatable_productos").DataTable(
    dataTableProductosOptions
  );
  dataTableProductosIsInitialized = true;
  customizeButtons();
  document
    .getElementById("selectAll")
    .addEventListener("change", toggleSelectAll);
};

function obtenerURLImagen(imagePath, serverURL) {
  if (imagePath) {
    if (imagePath.startsWith("http://") || imagePath.startsWith("https://")) {
      return imagePath;
    } else {
      return `${serverURL}${imagePath}`;
    }
  } else {
    console.error("imagePath es null o undefined");
    return null;
  }
}

// Función para manejar el evento de cambio del checkbox agregar_privado
function toggleAgregarPrivado(checkbox) {
  const productId = checkbox.getAttribute("data-id");
  const isChecked = checkbox.checked;
  const estado = isChecked ? 1 : 0; // Si está marcado, enviar 1; si no, enviar 0

  let formData = new FormData();
  formData.append("id_producto", productId);
  formData.append("estado", estado);

  // Llamada AJAX para enviar el estado a la API
  $.ajax({
    type: "POST",
    url: SERVERURL + "productos/habilitarPrivado",
    data: formData,
    processData: false, // No procesar los datos
    contentType: false, // No establecer ningún tipo de contenido
    dataType: "json",
    success: function (response) {
      console.log("Estado actualizado correctamente", response);
      toastr.success(
        "El estado del producto ha sido actualizado",
        "NOTIFICACIÓN",
        {
          positionClass: "toast-bottom-center",
        }
      );
    },
    error: function (xhr, status, error) {
      console.error("Error en la solicitud AJAX:", error);
      alert("Hubo un problema al actualizar el estado del producto");
    },
  });
}

function abrir_modalSeleccionAtributo(id) {
  $("#id_productoSeleccionado").val(id);
  initDataTableSeleccionProductoAtributo();
  $("#seleccionProdcutoAtributoModal").modal("show");
}

function abrir_modal_idInventario(id) {
  $("#id_productoIventario").val(id);
  initDataTableTablaIdInventario();
  $("#tabla_idInventarioModal").modal("show");
}

function enviar_cliente(id, sku, pvp, id_inventario) {
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

function importar_productos_tienda(productId) {
  let formData = new FormData();
  formData.append("id_producto", productId);
  $.ajax({
    url: SERVERURL + "Productos/importar_productos_tienda",
    method: "POST",
    data: formData,
    processData: false,
    contentType: false,
    success: function (response) {
      response = JSON.parse(response);
      if (response.status == 500) {
        toastr.warning("" + response.message, "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });
      } else if (response.status == 200) {
        toastr.success("" + response.message, "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });
      }
    },
    error: function (error) {
      console.error("Error al actualizar el estado del producto:", error);
    },
  });
}

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

function validador_bodega() {
  $.ajax({
    url: SERVERURL + "productos/obtener_bodegas",
    type: "GET",
    dataType: "json",
    success: function (response) {
      response.forEach(function (bodega) {
        if (
          bodega.localidad == null ||
          bodega.provincia == null ||
          bodega.direccion == null
        ) {
          const agregar_productoBtn =
            document.getElementById("agregar_producto");

          agregar_productoBtn.disabled = true;

          Swal.fire({
            icon: "error",
            title: "Error bodega",
            text:
              "Su bodega " +
              bodega.nombre +
              " no contiene datos de dirección y no pueden agregar Productos",
            showConfirmButton: false,
            timer: 2000,
          }).then(() => {
            window.location.href = "" + SERVERURL + "Productos/bodegas";
          });
        }
      });
    },
    error: function (error) {
      console.error("Error al obtener la lista de categorias:", error);
    },
  });
}

$(document).ready(function () {
  $.ajax({
    url: SERVERURL + "productos/cargar_categorias",
    type: "GET",
    dataType: "json",
    success: function (response) {
      if (Array.isArray(response)) {
        response.forEach(function (categoria) {
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

$(document).ready(function () {
  $.ajax({
    url: SERVERURL + "productos/listar_bodegas",
    type: "GET",
    dataType: "json",
    success: function (response) {
      if (Array.isArray(response)) {
        response.forEach(function (bodega) {
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
});

window.addEventListener("load", async () => {
  await initDataTableProductos();
});

function abrir_modalInventarioVariable(id) {
  $("#id_productoVariable").val(id);
  initDataTableDetalleInventario();
  $("#inventario_variableModal").modal("show");
}

window.addEventListener("load", vaciarTmpPedidos);
