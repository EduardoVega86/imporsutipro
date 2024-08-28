let dataTable;
let dataTableIsInitialized = false;

const dataTableOptions = {
  processing: true, // Mostrar indicador de procesamiento
  serverSide: true, // Habilitar la paginación del lado del servidor
  ajax: {
    url: `${SERVERURL}pedidos/obtener_guiasAdministrador2`,
    type: "POST",
    data: function (d) {
      d.fecha_inicio = fecha_inicio;
      d.fecha_fin = fecha_fin;
      d.estado = $("#estado_q").val();
      d.transportadora = $("#transporte").val();
      d.impreso = $("#impresion").val();
    },
  },
  columns: [
    {
      data: "numero_factura",
      render: function (data, type, row) {
        const drogshipin = row.drogshipin == 0 ? "Local" : "Drogshipin";
        return `<div>${data}</div><div>${drogshipin}</div>`;
      },
    },
    {
      data: "fecha_factura",
      render: function (data, type, row) {
        return `<div><button onclick="ver_detalle_cot('${row.id_factura}')" class="btn btn-sm btn-outline-primary">Ver detalle</button></div><div>${data}</div>`;
      },
    },
    {
      data: "nombre",
      render: function (data, type, row) {
        return `<div><strong>${data}</strong></div><div>${row.c_principal} y ${row.c_secundaria}</div><div>telf: ${row.telefono}</div>`;
      },
    },
    {
      data: "ciudad",
      render: function (data, type, row) {
        let ciudadArray = data.split("/");
        let ciudad = ciudadArray[0];
        return `${row.provinciaa}-${ciudad}`;
      },
    },
    { data: "tienda" },
    { data: "nombre_proveedor" },
    {
      data: "id_transporte",
      render: function (data, type, row) {
        return renderTransporte(row);
      },
    },
    {
      data: "estado_guia_sistema",
      render: function (data, type, row) {
        return renderEstado(row.id_transporte, data);
      },
    },
    {
      data: "estado_factura",
      render: function (data) {
        return data == 2
          ? `<i class='bx bx-check' style="color:#28E418; font-size: 30px;"></i>`
          : `<i class='bx bx-x' style="color:red; font-size: 30px;"></i>`;
      },
    },
    {
      data: "impreso",
      render: function (data) {
        return data == 0
          ? `<box-icon name='printer' color= "red"></box-icon>`
          : `<box-icon name='printer' color= "#28E418"></box-icon>`;
      },
    },
    {
      data: null,
      render: function (data, type, row) {
        return renderAcciones(row);
      },
    },
  ],
  order: [[1, "desc"]], // Ordenar por la columna de fecha
  pageLength: 25,
  lengthMenu: [25, 50, 100, 200],
  destroy: true,
  responsive: true,
  dom: '<"d-flex w-full justify-content-between"lBf><t><"d-flex justify-content-between"ip>',
  buttons: [
    {
      extend: "excelHtml5",
      text: 'Excel <i class="fa-solid fa-file-excel"></i>',
      exportOptions: {
        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8],
      },
      filename: "guias" + "_" + getFecha(),
      footer: true,
      className: "btn-excel",
    },
    {
      extend: "csvHtml5",
      text: 'CSV <i class="fa-solid fa-file-csv"></i>',
      exportOptions: {
        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8],
      },
      filename: "guias" + "_" + getFecha(),
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

const initDataTable = async () => {
  if (dataTableIsInitialized) {
    dataTable.destroy();
  }
  dataTable = $("#datatable_guias").DataTable(dataTableOptions);
  dataTableIsInitialized = true;

  // Handle select all checkbox
  document.getElementById("selectAll").addEventListener("change", function () {
    const checkboxes = document.querySelectorAll(".selectCheckbox");
    checkboxes.forEach((checkbox) => (checkbox.checked = this.checked));
  });
};

// Funciones para renderizar las columnas personalizadas
function renderTransporte(row) {
  let transporte_content = "";
  let ruta_descarga = "";
  let ruta_traking = "";
  let funcion_anular = "";

  switch (row.id_transporte) {
    case 2:
      transporte_content =
        '<span style="background-color: #28C839; color: white; padding: 5px; border-radius: 0.3rem;">SERVIENTREGA</span>';
      ruta_descarga = `<a class="w-100" href="https://guias.imporsuitpro.com/Servientrega/guia/${row.numero_guia}" target="_blank">${row.numero_guia}</a>`;
      ruta_traking = `https://www.servientrega.com.ec/Tracking/?guia=${row.numero_guia}&tipo=GUIA`;
      funcion_anular = `anular_guiaServi('${row.numero_guia}')`;
      break;
    case 1:
      transporte_content =
        '<span style="background-color: #E3BC1C; color: white; padding: 5px; border-radius: 0.3rem;">LAAR</span>';
      ruta_descarga = `<a class="w-100" href="https://api.laarcourier.com:9727/guias/pdfs/DescargarV2?guia=${row.numero_guia}" target="_blank">${row.numero_guia}</a>`;
      ruta_traking = `https://fenixoper.laarcourier.com/Tracking/Guiacompleta.aspx?guia=${row.numero_guia}`;
      funcion_anular = `anular_guiaLaar('${row.numero_guia}')`;
      break;
    case 4:
      transporte_content =
        '<span style="background-color: red; color: white; padding: 5px; border-radius: 0.3rem;">MerkaLogistic</span>';
      if (row.numero_guia.includes("MKL")) {
        ruta_descarga = `<a class="w-100" href="https://guias.imporsuitpro.com/Speed/descargar/${row.numero_guia}" target="_blank">${row.numero_guia}</a>`;
      }
      break;
    case 3:
      transporte_content =
        '<span style="background-color: red; color: white; padding: 5px; border-radius: 0.3rem;">GINTRACOM</span>';
      ruta_descarga = `<a class="w-100" href="https://guias.imporsuitpro.com/Gintracom/label/${row.numero_guia}" target="_blank">${row.numero_guia}</a>`;
      ruta_traking = `https://ec.gintracom.site/web/site/tracking`;
      funcion_anular = `anular_guiaGintracom('${row.numero_guia}')`;
      break;
    default:
      transporte_content =
        '<span style="background-color: #E3BC1C; color: white; padding: 5px; border-radius: 0.3rem;">Guia no enviada</span>';
      break;
  }

  return `
    <div style="text-align: center;">
      ${transporte_content}
      <div>${ruta_descarga}</div>
      ${
        ruta_traking
          ? `<div><a href="${ruta_traking}" target="_blank"><img src="https://new.imporsuitpro.com/public/img/tracking.png" width="40px"></a></div>`
          : ""
      }
    </div>
  `;
}

function renderEstado(id_transporte, estado) {
  let span_estado = "";
  let estado_guia = "";

  // Aquí deberás implementar la lógica que depende del id_transporte y estado
  // Por ejemplo:
  if (id_transporte === 2 && estado === 100) {
    span_estado = "badge_purple";
    estado_guia = "Generado";
  }
  // Agrega aquí los demás casos...

  return `<span class="w-100 text-nowrap ${span_estado}">${estado_guia}</span>`;
}

function renderAcciones(row) {
  let funcion_anular = ""; // Asigna la función de anular según el transporte
  return `
    <div class="dropdown">
      <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
        <i class="fa-solid fa-gear"></i>
      </button>
      <ul class="dropdown-menu">
        <li><span class="dropdown-item" style="cursor: pointer;" onclick="${funcion_anular}">Anular</span></li>
        <li><span class="dropdown-item" style="cursor: pointer;">Información</span></li>
      </ul>
    </div>
  `;
}

// Iniciar DataTable al cargar la página
window.addEventListener("load", async () => {
  await initDataTable();
});
