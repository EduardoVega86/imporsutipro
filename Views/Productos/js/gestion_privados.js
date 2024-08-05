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
      
      if (inventario.variedad == null){
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
      filename: "Inventario" + "_" + getFecha(),
      footer: true,
      className: "btn-excel",
    },
    {
      extend: "csvHtml5",
      text: 'CSV <i class="fa-solid fa-file-csv"></i>',
      title: "Panel de Control: Inventario",
      titleAttr: "Exportar a CSV",
      exportOptions: {
        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
      },
      filename: "Inventario" + "_" + getFecha(),
      footer: true,
      className: "btn-csv",
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

const initDataTableStockIndividual = async (id_inventario) => {
  if (dataTableStockIndividualIsInitialized) {
    dataTableStockIndividual.destroy();
  }

  await listStockIndividual(id_inventario);

  dataTableStockIndividual = $("#datatable_stockIndividual").DataTable(
    dataTableStockIndividualOptions
  );

  dataTableStockIndividualIsInitialized = true;
};

const listStockIndividual = async (id_inventario) => {
  try {
    const formData = new FormData();
    formData.append("id_inventario", id_inventario);

    const response = await fetch(`${SERVERURL}inventarios/obtenerHistorial`, {
      method: "POST",
      body: formData,
    });
    const stockIndividuals = await response.json();

    let content = ``;
    let tipo = "";
    stockIndividuals.forEach((stockIndividual, index) => {
      console.log("1 " + stockIndividual.tipo_historial);

      if (stockIndividual.tipo_historial == 1) {
        tipo = `<span style="background-color: #28C839; color: white; padding: 5px; border-radius: 0.3rem;">Entrada</span>`;
      } else {
        tipo = `<span style="background-color: red; color: white; padding: 5px; border-radius: 0.3rem;">Salida</span>`;
      }

      content += `
        <tr>
        <td>${stockIndividual.fecha_historial}</td>
      <td>${stockIndividual.nota_historial}</td>
      <td>${stockIndividual.referencia_historial}</td>
      <td>${tipo}</td>
      <td>${stockIndividual.cantidad_historial}</td>
        </tr>`;
    });
    document.getElementById("tableBody_stockIndividual").innerHTML = content;
  } catch (ex) {
    alert(ex);
  }
};

function seleccionar_cambiarInventario(id_inventario) {
  let formData = new FormData();
  formData.append("id_inventario", id_inventario); // Añadir el ID del inventario al FormData
  $("#id_inventarioStock").val(id_inventario);

  $.ajax({
    url: SERVERURL + "inventarios/obtenerInventario",
    type: "POST",
    data: formData,
    processData: false, // No procesar los datos
    contentType: false, // No establecer ningún tipo de contenido
    dataType: "json", // Asegúrate de recibir datos JSON
    success: function (response) {
      $("#existencia_stock").text(response[0].saldo_stock);

      $("#skuStock").val(response[0].sku);
      $("#id_productoStock").val(response[0].id_producto);
      $("#id_bodegaStock").val(response[0].bodega);

      var id_producto = response[0].id_producto;

      // ajax para consultar imagen de producto
      $.ajax({
        url: SERVERURL + "productos/obtener_producto/" + id_producto,
        type: "GET",
        dataType: "json",
        success: function (response2) {
          $("#nombreeProducto_stock").text(response2[0].nombre_producto);
          $("#image_stock").attr("src", SERVERURL + response2[0].image_path);

          initDataTableStockIndividual(id_inventario);
          document
            .getElementById("inventarioSection")
            .classList.remove("hidden");
        },
        error: function (error) {
          console.error("Error al obtener la imagen del producto:", error);
        },
      });
    },
    error: function (error) {
      console.error("Error al obtener la información del inventario:", error);
    },
  });
}