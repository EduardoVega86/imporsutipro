let dataTableOfertas;
let dataTableOfertasIsInitialized = false;

const dataTableOfertasOptions = {
  columnDefs: [
    { className: "centered", targets: [1, 2, 3, 4, 5] },
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

const initDataTableOfertas = async () => {
  if (dataTableOfertasIsInitialized) {
    dataTableOfertas.destroy();
  }

  await listOfertas();

  dataTableOfertas = $("#datatable_ofertas").DataTable(dataTableOfertasOptions);

  dataTableOfertasIsInitialized = true;
};

const listOfertas = async () => {
  try {
    const response = await fetch("" + SERVERURL + "Productos/obtener_oferta");
    const ofertas = await response.json();

    let content = ``;

    ofertas.forEach((oferta, index) => {
      content += `
                <tr>
                    <td>${oferta.nombre_oferta}</td>
                    <td>${oferta.precio_oferta}</td>
                    <td>${oferta.cantidad}</td>
                    <td>${oferta.fecha_inicio}</td>
                    <td>${oferta.fecha_fin}</td>
                    <td></td>
                    <td></td>
                    <td>
                    <div class="dropdown">
                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-gear"></i>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <li><span class="dropdown-item" style="cursor: pointer;" onclick="editar_oferta(${combo.id})">Editar</span></li>
                        <li><span class="dropdown-item" style="cursor: pointer;" onclick="eliminar_oferta(${combo.id})">Eliminar</span></li>
                    </ul>
                    </div>
                    </td>
                </tr>`;
    });
    document.getElementById("tableBody_ofertas").innerHTML = content;
  } catch (ex) {
    alert(ex);
  }
};

window.addEventListener("load", async () => {
  await initDataTableOfertas();
});

/* llenar select de productos */
document.addEventListener("DOMContentLoaded", () => {
  // Inicializa Select2 en el select
  $("#select_productos").select2({
    placeholder: "--- Elegir producto ---",
    allowClear: true,
    dropdownAutoWidth: true, // Habilita auto width para ajustarse correctamente
    templateResult: formatProduct, // Formato para mostrar los productos en el dropdown
    templateSelection: formatProductSelection, // Formato para mostrar la selección
    dropdownParent: $("#agregar_ofertaModal"), // Forzar que el dropdown se muestre dentro del modal
  });

  // Cuando se abra el modal, carga los productos
  $("#agregar_ofertaModal").on("shown.bs.modal", function () {
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
    const modal = $("#agregar_ofertaModal");
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
