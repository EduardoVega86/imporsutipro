let dataTableListaDespachos;
let dataTableListaDespachosIsInitialized = false;

const dataTableListaDespachosOptions = {
  columnDefs: [
    { className: "centered", targets: [0, 1, 2, 3, 4, 5] },
    { orderable: false, targets: 0 }, //ocultar para columna 0 el ordenar columna
  ],
  order: [[5, "desc"]], // Ordenar por la primera columna (fecha) en orden descendente
  pageLength: 10,
  destroy: true,
  dom: '<"d-flex w-full justify-content-between"lBf><t><"d-flex justify-content-between"ip>',
  buttons: [
    {
      extend: "excelHtml5",
      text: 'Excel <i class="fa-solid fa-file-excel"></i>',
      title: "Panel de Control: Usuarios",
      titleAttr: "Exportar a Excel",
      exportOptions: {
        columns: [0, 1, 2, 3, 4, 5],
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
        columns: [0, 1, 2, 3, 4, 5],
      },
      filename: "Productos" + "_" + getFecha(),
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

function getFecha() {
  let fecha = new Date();
  let mes = fecha.getMonth() + 1;
  let dia = fecha.getDate();
  let anio = fecha.getFullYear();
  let fechaHoy = anio + "-" + mes + "-" + dia;
  return fechaHoy;
}

const initDataTableListaDespachos = async () => {
  if (dataTableListaDespachosIsInitialized) {
    dataTableListaDespachos.destroy();
  }

  await listListaDespachos();

  dataTableListaDespachos = $("#datatable_lista_despachos").DataTable(
    dataTableListaDespachosOptions
  );

  dataTableListaDespachosIsInitialized = true;
};

const listListaDespachos = async () => {
  try {
    const response = await fetch("" + SERVERURL + "despacho/listarDespachos");
    const listaDespachos = await response.json();

    let content = ``;

    listaDespachos.forEach((despacho, index) => {
      content += `
                  <tr>
                      <td>${despacho.id_relacion_despacho}</td>
                      <td>${despacho.id_usuario}</td>
                      <td>${despacho.id_plataforma}</td>
                      <td>${despacho.id_transportadora}</td>
                      <td>${despacho.id_bodega}</td>
                      <td>${despacho.fecha_hora}</td>
                      <td><a href="${despacho.url_documento}" download><i class='bx bxs-file-pdf' style="color:red;"></i></a></td>
                  </tr>`;
    });
    document.getElementById("tableBody_lista_despachos").innerHTML = content;
  } catch (ex) {
    alert(ex);
  }
};

document
  .getElementById("generarDespachoBtn")
  .addEventListener("click", function () {
    const transportadoraSelect = document.getElementById("transportadora");
    const bodegaSelect = document.getElementById("select_bodega");
    const transportadoraValue = transportadoraSelect.value;
    const bodegaValue = bodegaSelect.value;

    if (transportadoraValue !== "") {
      if (bodegaValue !== "") {
        const url =
          SERVERURL +
          `despacho/despacho?transportadora=${transportadoraValue}&bodega=${bodegaValue}`;
        window.location.href = url;
      } else {
        toastr.error("Por favor selecciona una bodega.", "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });
      }
    } else {
      toastr.error("Por favor selecciona una transportadora.", "NOTIFICACIÓN", {
        positionClass: "toast-bottom-center",
      });
    }
  });

window.addEventListener("load", async () => {
  await initDataTableListaDespachos();
});

function descargar_pdf(id) {
  console.log(id);
}

//cargar select de bodegas
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
          $("#select_bodega").append(new Option(bodega.nombre, bodega.id));
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
