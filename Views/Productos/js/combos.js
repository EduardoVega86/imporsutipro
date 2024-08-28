let dataTableCombos;
let dataTableCombosIsInitialized = false;

function getFecha() {
  let fecha = new Date();
  let mes = fecha.getMonth() + 1;
  let dia = fecha.getDate();
  let anio = fecha.getFullYear();
  let fechaHoy = anio + "-" + mes + "-" + dia;
  return fechaHoy;
}

const dataTableCombosOptions = {
  columnDefs: [
    { className: "centered", targets: [0, 1, 2, 3, 4] },
    { orderable: false, targets: 0 }, //ocultar para columna 0 el ordenar columna
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

const initDataTableCombos = async () => {
  if (dataTableCombosIsInitialized) {
    dataTableCombos.destroy();
  }

  await listCombos();

  dataTableCombos = $("#datatable_combos").DataTable(dataTableCombosOptions);

  dataTableCombosIsInitialized = true;
};

const listCombos = async () => {
  try {
    const response = await fetch("" + SERVERURL + "productos/obtener_combos");
    const combos = await response.json();

    let content = ``;
    let cargarImagen = "";
    let variedad = "";
    combos.forEach((combo, index) => {
      if (!combo.image_path) {
        cargarImagen = `<i class="bx bxs-camera-plus"></i>`;
      } else {
        cargarImagen = `<img src="${SERVERURL}${combo.image_path}" class="icon-button" alt="Agregar imagen" width="50px">`;
      }

      content += `
      <tr>
      <td>${combo.id}</td>
      <td>${cargarImagen}</td>
      <td>${combo.nombre}</td>
      <td>${combo.nombre_producto}</td>
      <td>
          <button class="btn btn-sm btn-primary" onclick="seleccionar_combo(${combo.id})"><i class="fa-solid fa-pencil"></i>Ajustar</button>
      </td>
      <td>
      <div class="dropdown">
           <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
             <i class="fa-solid fa-gear"></i>
           </button>
           <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
              <li><span class="dropdown-item" style="cursor: pointer;" onclick="editar_combo(${combo.id})">Editar</span></li>
              <li><span class="dropdown-item" style="cursor: pointer;" onclick="eliminar_combo(${combo.id})">Eliminar</span></li>
           </ul>
      </div>
      </td>
      </tr>`;
    });
    document.getElementById("tableBody_combos").innerHTML = content;
  } catch (ex) {
    alert(ex);
  }
};

window.addEventListener("load", async () => {
  await initDataTableCombos();
});

// tabla asignacion de productos
let dataTableAsignacionProducto;
let dataTableAsignacionProductoIsInitialized = false;

const dataTableAsignacionProductoOptions = {
  columnDefs: [
    { className: "centered", targets: [0, 1, 2, 3, 4] },
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

const initDataTableAsignacionProducto = async () => {
  if (dataTableAsignacionProductoIsInitialized) {
    dataTableAsignacionProducto.destroy();
  }

  await listAsignacionProducto();

  dataTableAsignacionProducto = $("#datatable_asignacion_producto").DataTable(
    dataTableAsignacionProductoOptions
  );

  dataTableAsignacionProductoIsInitialized = true;
};

const listAsignacionProducto = async () => {
  try {
    const response = await fetch(SERVERURL + "productos/obtener_productos");
    const asignacionProducto = await response.json();

    let content = ``;
    let cargarImagen = ``;
    asignacionProducto.forEach((producto, index) => {
      if (!producto.image_path) {
        cargarImagen = `<i class="bx bxs-camera-plus"></i>`;
      } else {
        cargarImagen = `<img src="${SERVERURL}${producto.image_path}" class="icon-button" alt="Agregar imagen" width="50px">`;
      }
      content += `
                  <tr>
                      <td>${producto.id_producto}</td>
                      <td>${cargarImagen}</td>
                      <td>${producto.nombre_producto}</td>
                      <td>${producto.pvp}</td>
                      <td>
                      <input type="number" id="cantidad_producto_${
                        producto.id_producto
                      }" class="form-control" style="border-radius:0.3rem !important;" value="1" min="1">
                      </td>
                      <td>
                          <button class="btn btn-sm btn-danger" onclick="mover_producto(${
                            producto.id_producto
                          }, document.getElementById('cantidad_producto_${
        producto.id_producto
      }').value, ${$(
        "#id_combo_seccion"
      ).val()})"><i class="fas fa-arrow-right"></i></button>
                      </td>
                  </tr>`;
    });
    document.getElementById("tableBody_asignacion_producto").innerHTML =
      content;
  } catch (ex) {
    alert(ex);
  }
};

function seleccionar_combo(id_combo) {
  $("#id_combo_seccion").val(id_combo);
  initDataTableAsignacionProducto();
  initDataTableDetalleCombo(id_combo);
  document.getElementById("comboSection").classList.remove("hidden");
}

//cargar select de tiendas
$(document).ready(function () {
  // Realiza la solicitud AJAX para obtener la lista de bodegas
  $.ajax({
    url: SERVERURL + "Usuarios/obtener_tiendas",
    type: "GET",
    dataType: "json",
    success: function (response) {
      // Asegúrate de que la respuesta es un array
      if (Array.isArray(response)) {
        response.forEach(function (tiendas) {
          // Agrega una nueva opción al select por cada tienda
          $("#select_tiendas").append(
            new Option(tiendas.nombre_tienda, tiendas.id_plataforma)
          );
        });

        // Inicializa Select2 en el elemento select
        $("#select_tiendas").select2({
          placeholder: "Selecciona una tienda", // Placeholder opcional
          allowClear: true, // Permite limpiar la selección
        });
      } else {
        console.log("La respuesta de la API no es un array:", response);
      }
    },
    error: function (error) {
      console.error("Error al obtener la lista de tiendas:", error);
    },
  });

  $("#select_tiendas").change(function () {
    var id_plataformaTienda = $("#select_tiendas").val();
    $("#informacion_tienda").show();

    let formData = new FormData();
    formData.append("id_plataforma", id_plataformaTienda);

    $.ajax({
      url: SERVERURL + "Usuarios/obtener_infoTienda_privada",
      type: "POST",
      data: formData,
      processData: false,
      contentType: false,
      dataType: "json",
      success: function (response) {
        if (response[0].logo_url === null) {
          $("#image_tienda").attr(
            "src",
            SERVERURL + "public/img/broken-image.png"
          );
        } else {
          $("#image_tienda").attr("src", SERVERURL + response[0].logo_url);
        }

        $("#nombre_tienda").text(response[0].nombre_tienda);
        $("#url").text(response[0].url_imporsuit);
        $("#telefono").text(response[0].whatsapp);
        $("#correo").text(response[0].email);
      },
      error: function (jqXHR, textStatus, errorThrown) {
        alert(errorThrown);
      },
    });
  });
});

function eliminar_combo(id_combo) {
  let formData = new FormData();
  formData.append("id_combo", id_combo);
  $.ajax({
    url: SERVERURL + "Productos/eliminarCombo",
    type: "POST",
    data: formData,
    processData: false, // No procesar los datos
    contentType: false, // No establecer ningún tipo de contenido
    dataType: "json",
    success: function (response) {
      if (response.status == 500) {
        toastr.error("EL COMBO NO SE ELIMINO CORRECTAMENTE", "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });
      } else if (response.status == 200) {
        toastr.success("COMBO ELIMINADO CORRECTAMENTE", "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });

        initDataTableCombos();
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      alert(errorThrown);
    },
  });
}

function mover_producto(id_producto, cantidad, id_combo) {
  let formData = new FormData();
  formData.append("id_producto", id_producto);
  formData.append("cantidad", cantidad);
  formData.append("id_combo", $("#id_combo_seccion").val());
  $.ajax({
    url: SERVERURL + "Productos/agregar_detalle_combo",
    type: "POST",
    data: formData,
    processData: false, // No procesar los datos
    contentType: false, // No establecer ningún tipo de contenido
    dataType: "json",
    success: function (response) {
      if (response.status == 500) {
        toastr.error(
          "ERROR AL ASIGNAR EL PRODUCTO CORRECTAMENTE",
          "NOTIFICACIÓN",
          {
            positionClass: "toast-bottom-center",
          }
        );
      } else if (response.status == 200) {
        toastr.success("PRODUCTO ASIGNADO CORRECTAMENTE", "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });

        initDataTableDetalleCombo(id_combo);
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      alert(errorThrown);
    },
  });
}

/* tabla detalle combo */
let dataTableDetalleCombo;
let dataTableDetalleComboIsInitialized = false;

const dataTableDetalleComboOptions = {
  columnDefs: [
    { className: "centered", targets: [0, 1, 2, 3] },
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

const initDataTableDetalleCombo = async (id_combo) => {
  if (dataTableDetalleComboIsInitialized) {
    dataTableDetalleCombo.destroy();
  }

  await listDetalleCombo(id_combo);

  dataTableDetalleCombo = $("#datatable_detalle_combo").DataTable(
    dataTableDetalleComboOptions
  );

  dataTableDetalleComboIsInitialized = true;
};

const listDetalleCombo = async (id_combo) => {
  try {
    let formData = new FormData();
    formData.append("id_combo", id_combo);

    // Realizar la solicitud fetch con método POST y enviar el FormData
    const response = await fetch(
      SERVERURL + "Productos/obtener_detalle_combo_id",
      {
        method: "POST",
        body: formData,
      }
    );
    const detalleCombo = await response.json();

    let content = ``;
    let cargarImagen = "";
    detalleCombo.forEach((combo, index) => {
        if (!combo.image_path) {
            cargarImagen = `<i class="bx bxs-camera-plus"></i>`;
          } else {
            cargarImagen = `<img src="${SERVERURL}${combo.image_path}" class="icon-button" alt="Agregar imagen" width="50px">`;
          }
      content += `
                <tr>
                    <td>${combo.id_producto}</td>
                    <td>${cargarImagen}</td>
                    <td>${combo.nombre_producto}</td>
                    <td>${combo.cantidad}</td>
                    <td>
                        <button class="btn btn-sm btn-danger" onclick="eliminar_detalle_combo(${combo.id})"><i class="fas fa-arrow-left"></i></button>
                    </td>
                </tr>`;
    });
    document.getElementById("tableBody_detalle_combo").innerHTML = content;
  } catch (ex) {
    alert(ex);
  }
};
/* fin tabla detalle combo */

function eliminar_detalle_combo(id) {
  let formData = new FormData();
  formData.append("id_detalle_combo", id);
  $.ajax({
    url: SERVERURL + "Productos/eliminar_detalleCombo",
    type: "POST",
    data: formData,
    processData: false, // No procesar los datos
    contentType: false, // No establecer ningún tipo de contenido
    dataType: "json",
    success: function (response) {
      if (response.status == 500) {
        toastr.error("EL COMBO NO SE ELIMINO CORRECTAMENTE", "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });
      } else if (response.status == 200) {
        toastr.success("COMBO ELIMINADO CORRECTAMENTE", "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });

        initDataTableCombos();
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      alert(errorThrown);
    },
  });
}

/* llenar select de productos */
document.addEventListener("DOMContentLoaded", () => {
  // Inicializa Select2 en el select
  $("#select_productos").select2({
    placeholder: "--- Elegir producto ---",
    allowClear: true,
    dropdownAutoWidth: true, // Habilita auto width para ajustarse correctamente
    templateResult: formatProduct, // Formato para mostrar los productos en el dropdown
    templateSelection: formatProductSelection, // Formato para mostrar la selección
    dropdownParent: $("#agregar_comboModal"), // Forzar que el dropdown se muestre dentro del modal
  });

  // Cuando se abra el modal, carga los productos
  $("#agregar_comboModal").on("shown.bs.modal", function () {
    fetchProductos();
  });

  function fetchProductos() {
    fetch(SERVERURL + "productos/obtener_productos")
      .then((response) => response.json())
      .then((data) => {
        const selectProductos = $("#select_productos");
        selectProductos.empty(); // Limpia el select
        selectProductos.append(new Option("--- Elegir producto ---", ""));

        // Llenar el select con los datos recibidos
        data.forEach((item) => {
          const option = new Option(
            `${item.nombre_producto} - $${item.pvp}`, // Lo que ves en el select
            item.id_producto, // El valor del option
            false, // No seleccionado por defecto
            false // No preseleccionado
          );
          option.setAttribute("data-image", SERVERURL + item.image_path); // Añadir imagen como atributo
          selectProductos.append(option);
        });

        // Refrescar Select2
        selectProductos.trigger("change");
      })
      .catch((error) => console.error("Error al cargar productos:", error));
  }

  function formatProduct(product) {
    if (!product.id) {
      return product.text;
    }

    // Obtén la imagen desde los datos
    let imgPath = $(product.element).data("image")
      ? $(product.element).data("image")
      : "default-image-path.jpg";

    var $product = $(
      `<div class='select2-result-repository clearfix'>
                <div class='select2-result-repository__avatar'>
                    <img src='${imgPath}' alt='Imagen del producto' style='width: 50px; height: 50px; margin-right: 10px;'/>
                </div>
                <div class='select2-result-repository__meta'>
                    <div class='select2-result-repository__title'>${product.text}</div>
                </div>
            </div>`
    );

    return $product;
  }

  function formatProductSelection(product) {
    return product.text || product.nombre_producto;
  }

  // Reposiciona el dropdown de select2 cuando el modal está abierto
  $("#select_productos").on("select2:open", function () {
    const modal = $("#agregar_comboModal");
    const select2Dropdown = $(".select2-container .select2-dropdown");

    // Asegura que el dropdown esté correctamente posicionado dentro del modal
    select2Dropdown.position({
      my: "top",
      at: "bottom",
      of: $("#select_productos"),
    });
  });
});
/* Fin llenar select productos */
/* llenar select productos editar */
// Función para editar el combo
function editar_combo(id_combo) {
  let formData = new FormData();
  formData.append("id", id_combo);

  $.ajax({
    url: SERVERURL + "Productos/obtener_combo_id",
    type: "POST",
    data: formData,
    processData: false, // No procesar los datos
    contentType: false, // No establecer ningún tipo de contenido
    dataType: "json",
    success: function (response) {
      // Asignar los valores en el formulario del modal
      $("#editar_nombre_combo").val(response[0].nombre);
      $("#id_combo_editar").val(response[0].id);
      $("#preview-imagen_editar")
        .attr("src", SERVERURL + response[0].image_path)
        .show();

      // Mostrar el modal
      $("#editar_comboModal").modal("show");

      // Cargar los productos en el select y luego seleccionar el producto correspondiente
      fetchProductos(response[0].id_producto_combo);
    },
    error: function (jqXHR, textStatus, errorThrown) {
      alert(errorThrown);
    },
  });
}

// Mover la función initializeSelect2 al ámbito global
function initializeSelect2() {
  // Destruir Select2 si ya existe en el select_productos_editar
  if (
    $.fn.select2 &&
    $("#select_productos_editar").hasClass("select2-hidden-accessible")
  ) {
    $("#select_productos_editar").select2("destroy");
  }

  // Inicializar Select2 en el select_productos_editar
  $("#select_productos_editar").select2({
    placeholder: "--- Elegir producto ---",
    allowClear: true,
    dropdownAutoWidth: true,
    templateResult: formatProduct, // Formato para mostrar los productos en el dropdown
    templateSelection: formatProductSelection, // Formato para mostrar la selección
    dropdownParent: $("#editar_comboModal"), // Especificar el modal correcto
  });
}

// Mover fetchProductos al ámbito global
function fetchProductos(productoId = null) {
  return fetch(SERVERURL + "productos/obtener_productos")
    .then((response) => response.json())
    .then((data) => {
      const selectProductos = $("#select_productos_editar");
      selectProductos.empty(); // Limpiar el select

      // Llenar el select con los datos recibidos
      data.forEach((item) => {
        const option = new Option(
          `${item.nombre_producto} - $${item.pvp}`, // Lo que ves en el select
          item.id_producto, // El valor del option
          false, // No seleccionado por defecto
          false // No preseleccionado
        );
        option.setAttribute("data-image", SERVERURL + item.image_path); // Añadir imagen como atributo
        selectProductos.append(option);
      });

      // Inicializar Select2 después de que los productos hayan sido cargados
      initializeSelect2();

      // Seleccionar el producto después de que Select2 esté completamente inicializado
      if (productoId) {
        setTimeout(() => {
          selectProductos.val(productoId).trigger("change"); // Seleccionar el producto específico
        }, 300); // Ajusta el tiempo si es necesario
      }
    })
    .catch((error) => console.error("Error al cargar productos:", error));
}

// Funciones auxiliares globales para Select2
function formatProduct(product) {
  if (!product.id) {
    return product.text;
  }

  // Obtener la imagen desde los datos
  let imgPath = $(product.element).data("image")
    ? $(product.element).data("image")
    : "default-image-path.jpg";

  var $product = $(
    `<div class='select2-result-repository clearfix'>
            <div class='select2-result-repository__avatar'>
                <img src='${imgPath}' alt='Imagen del producto' style='width: 50px; height: 50px; margin-right: 10px;'/>
            </div>
            <div class='select2-result-repository__meta'>
                <div class='select2-result-repository__title'>${product.text}</div>
            </div>
        </div>`
  );

  return $product;
}

function formatProductSelection(product) {
  return product.text || product.nombre_producto;
}

document.addEventListener("DOMContentLoaded", () => {
  // Reposicionar el dropdown de Select2 cuando se abre
  $("#select_productos_editar").on("select2:open", function () {
    const modal = $("#editar_comboModal");
    const select2Dropdown = $(".select2-container .select2-dropdown");

    // Asegura que el dropdown esté correctamente posicionado dentro del modal
    select2Dropdown.position({
      my: "top",
      at: "bottom",
      of: $("#select_productos_editar"),
    });
  });
});

/* Fin llenar select productos editar */
