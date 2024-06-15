let dataTable;
let dataTableIsInitialized = false;

const dataTableOptions = {
  columnDefs: [
    { className: "centered", targets: [1, 2, 3, 4, 5, 6, 7, 8, 9] },
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

const initDataTable = async () => {
  if (dataTableIsInitialized) {
    dataTable.destroy();
  }

  await listGuias();

  dataTable = $("#datatable_guias").DataTable(dataTableOptions);

  dataTableIsInitialized = true;

  // Handle select all checkbox
  document.getElementById("selectAll").addEventListener("change", function () {
    const checkboxes = document.querySelectorAll(".selectCheckbox");
    checkboxes.forEach((checkbox) => (checkbox.checked = this.checked));
  });
};

const listGuias = async () => {
  try {
    const response = await fetch("" + SERVERURL + "productos/obtener_productos");
    const guias = await response.json();

    let content = ``;
    let cargar_imagen = "";
    guias.forEach((guia, index) => {
      if (!producto.image_path) {
        cargar_imagen = `<i class="bx bxs-camera-plus" onclick="agregar_imagenProducto(${producto.id_producto})"></i>`;
      } else {
        cargar_imagen = `<img src="${SERVERURL}${producto.image_path}" class="icon-button" onclick="agregar_imagenProducto(${producto.id_producto})" alt="Agregar imagen" width="50px">`;
      }

      content += `
      <tr>
      <td>${producto.id_producto}</td>
      <td>${cargar_imagen}</td>
      <td>${producto.codigo_producto}</td>
      <td>${producto.nombre_producto}</td>
      <td>${producto.saldo_stock}</td>
      <td>
          <button class="btn btn-sm btn-primary" onclick="seleccionar_cambiarInventario(${producto.id_producto})"><i class="fa-solid fa-pencil"></i>Editar</button>
      </td>
      </tr>`;
    });
    document.getElementById("tableBody_guias").innerHTML = content;
  } catch (ex) {
    alert(ex);
  }
};

window.addEventListener("load", async () => {
  await initDataTable();
});
