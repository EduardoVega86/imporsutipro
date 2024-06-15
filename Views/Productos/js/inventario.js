let dataTableInventario;
let dataTableInventarioIsInitialized = false;

const dataTableInventarioOptions = {
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
      "" + SERVERURL + "productos/obtener_productos"
    );
    const inventarios = await response.json();

    let content = ``;
    let cargarImagen = "";
    inventarios.forEach((inventario, index) => {
      if (!inventario.image_path) {
        cargarImagen = `<i class="bx bxs-camera-plus"></i>`;
      } else {
        cargarImagen = `<img src="${SERVERURL}${inventario.image_path}" class="icon-button" alt="Agregar imagen" width="50px">`;
      }

      content += `
      <tr>
      <td>${inventario.id_producto}</td>
      <td>${cargarImagen}</td>
      <td>${inventario.codigo_producto}</td>
      <td>${inventario.nombre_producto}</td>
      <td>${inventario.saldo_stock}</td>
      <td>
          <button class="btn btn-sm btn-primary" onclick="seleccionar_cambiarInventario(${inventario.id_inventario})"><i class="fa-solid fa-pencil"></i>Ajustar</button>
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
    { className: "centered", targets: [0, 1, 2, 3] },
    { orderable: false, targets: 0 }, //ocultar para columna 0 el ordenar columna
  ],
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

    const response = await fetch(`${SERVERURL}inventarios/obtenerInventario`, {
      method: "POST",
      body: formData,
    });
    const stockIndividuals = await response.json();

    let content = ``;
    stockIndividuals.forEach((stockIndividual, index) => {
      let cargarImagen = "";
      if (!stockIndividual.image_path) {
        cargarImagen = `<i class="bx bxs-camera-plus"></i>`;
      } else {
        cargarImagen = `<img src="${SERVERURL}${stockIndividual.image_path}" class="icon-button" alt="Agregar imagen" width="50px">`;
      }

      content += `
        <tr>
        <td>${stockIndividual.id_producto}</td>
        <td>${cargarImagen}</td>
        <td>${stockIndividual.codigo_producto}</td>
        <td>${stockIndividual.nombre_producto}</td>
        <td>${stockIndividual.saldo_stock}</td>
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

  $.ajax({
    url: SERVERURL + "inventarios/obtenerInventario",
    type: "POST",
    data: formData,
    processData: false, // No procesar los datos
    contentType: false, // No establecer ningún tipo de contenido
    dataType: "json", // Asegúrate de recibir datos JSON
    success: function (response) {
      console.log("informacion de inventario: " + JSON.stringify(response));
      console.log(response[0].saldo_stock);
      $("#existencia_stock").val(response[0].saldo_stock);
      var id_producto = response[0].id_producto;

      // ajax para consultar imagen de producto
      $.ajax({
        url: SERVERURL + "productos/obtener_producto/" + id_producto,
        type: "GET",
        dataType: "json",
        success: function (response2) {
          const data = response2[0];
          document.getElementById("image_stock").src =
            SERVERURL + data.image_path;

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
