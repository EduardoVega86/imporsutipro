let dataTableInventario;
let dataTableInventarioIsInitialized = false;

function getFecha() {
  let fecha = new Date();
  let mes = fecha.getMonth() + 1;
  let dia = fecha.getDate();
  let anio = fecha.getFullYear();
  let fechaHoy = anio + "-" + mes + "-" + dia;
  return fechaHoy;
}

const dataTableInventarioOptions = {
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

const initDataTableInventario = async () => {
  if (dataTableInventarioIsInitialized) {
    dataTableInventario.destroy();
  }

  await listInventario();

  dataTableInventario = $("#datatable_inventario").DataTable(
    dataTableInventarioOptions
  );

  dataTableInventarioIsInitialized = true;
};

const listInventario = async () => {
  try {
    const response = await fetch(
      "" + SERVERURL + "productos/obtener_productosPrivados_tienda"
    );
    const inventarios = await response.json();

    let content = ``;
    let cargarImagen = "";
    let variedad = "";
    inventarios.forEach((inventario, index) => {
      if (!inventario.image_path) {
        cargarImagen = `<i class="bx bxs-camera-plus"></i>`;
      } else {
        cargarImagen = `<img src="${SERVERURL}${inventario.image_path}" class="icon-button" alt="Agregar imagen" width="50px">`;
      }

      if (inventario.variedad == null) {
        variedad = "";
      } else {
        variedad = inventario.variedad;
      }

      content += `
      <tr>
      <td>${inventario.id_producto}</td>
      <td>${cargarImagen}</td>
      <td>${inventario.codigo_producto}</td>
      <td>${inventario.nombre_producto}</td>
      <td>
          <button class="btn btn-sm btn-primary" onclick="seleccionar_cambiarInventario(${inventario.id_producto})"><i class="fa-solid fa-pencil"></i>Ajustar</button>
      </td>
      </tr>`;
    });
    document.getElementById("tableBody_inventario").innerHTML = content;
  } catch (ex) {
    alert(ex);
  }
};

window.addEventListener("load", async () => {
  await initDataTableInventario();
});

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

function seleccionar_cambiarInventario(id_producto) {
  $("#id_producto_privado").val(id_producto);
  initDataTableStockIndividual(id_producto);
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

function eliminar_tiendaProductoPrivado(id_producto_privado, id_privado) {
  let formData = new FormData();
  formData.append("id_privado", id_producto_privado);

  $.ajax({
    url: SERVERURL + "Productos/eliminarPrivadoPlataforma",
    type: "POST", // Cambiar a POST para enviar FormData
    data: formData,
    processData: false, // No procesar los datos
    contentType: false, // No establecer ningún tipo de contenido
    dataType: "json",
    success: function (response) {
      if (response.status == 500) {
        toastr.error("LA TIENDA NO SE ELIMINO CORRECTAMENTE", "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });
      } else if (response.status == 200) {
        toastr.success("TIENDA ELIMINADO CORRECTAMENTE", "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });
        initDataTableStockIndividual(id_privado);
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      alert(errorThrown);
    },
  });
}

function agregar_tienda() {
  var id_plataformaTienda = $("#select_tiendas").val();
  var id_producto_privado = $("#id_producto_privado").val();

  let formData = new FormData();
  formData.append("id_plataforma", id_plataformaTienda);
  formData.append("id_producto", id_producto_privado);

  $.ajax({
    url: SERVERURL + "Productos/agregarPrivadoPlataforma",
    type: "POST", // Cambiar a POST para enviar FormData
    data: formData,
    processData: false, // No procesar los datos
    contentType: false, // No establecer ningún tipo de contenido
    dataType: "json",
    success: function (response) {
      if (response.status == 500) {
        toastr.error("LA TIENDA NO SE AGREGRO CORRECTAMENTE", "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });
      } else if (response.status == 200) {
        toastr.success("TIENDA AGREGADA CORRECTAMENTE", "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });
        initDataTableStockIndividual(id_producto_privado);
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      alert(errorThrown);
    },
  });
}
