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
        $("#editar_nombre_combo").val(response[0].nombre);
        $("#select_productos_editar").val(response[0].id_producto_combo);

        $("#preview-imagen_editar")
        .attr("src", SERVERURL + response[0].image_path)
        .show();

        $("#editar_comboModal").modal("show");
    },
    error: function (jqXHR, textStatus, errorThrown) {
      alert(errorThrown);
    },
  });
}

// tabla con informacion de inventario de producto individual
let dataTableStockIndividual;
let dataTableStockIndividualIsInitialized = false;

const dataTableStockIndividualOptions = {
  columnDefs: [
    { className: "centered", targets: [0, 1, 2, 3, 4] },
    { orderable: false, targets: 0 }, //ocultar para columna 0 el ordenar columna
  ],
  order: [[0, "desc"]], // Ordenar por la primera columna (fecha) en orden descendente
  pageLength: 10,
  destroy: true,
  responsive: true,
  autoWidth: true,
  bAutoWidth: true,
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

const initDataTableStockIndividual = async (id_producto) => {
  if (dataTableStockIndividualIsInitialized) {
    dataTableStockIndividual.destroy();
  }

  await listStockIndividual(id_producto);

  dataTableStockIndividual = $("#datatable_stockIndividual").DataTable(
    dataTableStockIndividualOptions
  );

  dataTableStockIndividualIsInitialized = true;
};

const listStockIndividual = async (id_producto) => {
  try {
    const formData = new FormData();
    formData.append("id_producto", id_producto);

    const response = await fetch(
      `${SERVERURL}Productos/obtener_tiendas_productosPrivados`,
      {
        method: "POST",
        body: formData,
      }
    );
    const stockIndividuals = await response.json();

    let content = ``;
    let tipo = "";
    stockIndividuals.forEach((stockIndividual, index) => {
      content += `
        <tr>
        <td>${stockIndividual.nombre_tienda}</td>
      <td>${stockIndividual.email}</td>
      <td>${stockIndividual.whatsapp}</td>
      <td>${stockIndividual.url_imporsuit}</td>
      <td>
      <button class="btn btn-sm btn-danger" onclick="eliminar_tiendaProductoPrivado(${stockIndividual.id_producto_privado}, ${id_producto})"><i class="fa-solid fa-trash-can"></i>Borrar</button>
      </td>
        </tr>`;
    });
    document.getElementById("tableBody_stockIndividual").innerHTML = content;
  } catch (ex) {
    alert(ex);
  }
};

function seleccionar_combo(id_combo) {
  $("#id_producto_privado").val(id_combo);
  initDataTableStockIndividual(id_combo);
  document.getElementById("inventarioSection").classList.remove("hidden");
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
