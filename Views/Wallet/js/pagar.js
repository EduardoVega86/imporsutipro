// Obtener la URL actual
const urlActual = window.location.href;
// Crear un objeto URL
const url = new URL(urlActual);
// Obtener el valor del parámetro 'tienda'
const tienda = url.searchParams.get("tienda");

// Añadimos un evento que se ejecuta cuando el DOM ha sido completamente cargado
document.addEventListener("DOMContentLoaded", function () {
  cargarDashboard_wallet();
});

$(document).ready(function () {
  $("#regresar").click(function () {
    window.location.href = SERVERURL + "wallet";
  });
});

function cargarDashboard_wallet() {
  let formData = new FormData();
  formData.append("tienda", tienda);

  $.ajax({
    url: SERVERURL + "wallet/obtenerDetalles",
    type: "POST",
    data: formData,
    processData: false, // No procesar los datos
    contentType: false, // No establecer ningún tipo de contenido
    success: function (response) {
      response = JSON.parse(response);
      $("#image_tienda").attr(
        "src",
        SERVERURL + "public/img/profile_wallet.png"
      );
      $("#tienda_span").text(tienda);

      $("#totalVentas_wallet").text(response.ventas);
      $("#utilidadGenerada_wallet").text(response.utilidad);
      $("#descuentoDevolucion_wallet").text(response.devoluciones);
      $("#retirosAcreditados_wallet").text(response.abonos_registrados);
      $("#saldoBilletera_wallet").text(response.saldo);
    },
    error: function (jqXHR, textStatus, errorThrown) {
      alert(errorThrown);
    },
  });
}

// TABLAS FACTURAS
let filtro_facturas = "todas";
let dataTableFacturas;
let dataTableFacturasIsInitialized = false;

const dataTableFacturasOptions = {
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

const initDataTableFacturas = async () => {
  if (dataTableFacturasIsInitialized) {
    dataTableFacturas.destroy();
  }

  await listFacturas();

  dataTableFacturas = $("#datatable_facturas").DataTable(
    dataTableFacturasOptions
  );

  dataTableFacturasIsInitialized = true;
};

const listFacturas = async () => {
  try {
    const formData = new FormData();
    formData.append("tienda", tienda);
    formData.append("filtro", filtro_facturas);

    const response = await fetch(`${SERVERURL}wallet/obtenerFacturas`, {
      method: "POST",
      body: formData,
    });
    const facturas = await response.json();

    let content = ``;

    facturas.forEach((factura, index) => {
      content += `
                <tr>
                    <td><input type="checkbox" class="selectCheckbox"></td>
                    <td>
                    #Factura:${factura.numero_factura}
                    Guia:${factura.guia}
                    </td>
                    <td>
                    ${factura.cliente}
                    ${factura.fecha}
                    </td>
                    <td>${factura.tienda}</td>
                    <td>${factura.total_venta}</td>
                    <td>${factura.costo}</td>
                    <td>${factura.precio_envio}</td>
                    <td>${factura.full}</td>
                    <td>${factura.monto_recibir}</td>
                    <td>${factura.valor_cobrado}</td>
                    <td>${factura.valor_pendiente}</td>
                    <td>${factura.peso}</td>
                    <td>
                    <div class="dropdown">
                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class='bx bxs-truck' ></i>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <li><a class="dropdown-item" style="cursor: pointer;" href="https://fenix.laarcourier.com/Tracking/Guiacompleta.aspx?guia=${factura.guia}">Traking</a></li>
                        <li><a class="dropdown-item" style="cursor: pointer;" href="https://api.laarcourier.com:9727/guias/pdfs/DescargarV2?guia=${factura.guia}">Ticket</a></li>
                    </ul>
                    </div>
                    </td>

                    <td><button class="icon-button"><i class="fas fa-eye"></i></button></td>
                    <td><button class="icon-button" style="background-color: grenn;"><i class="fa-solid fa-file-pen"></i></button></td>
                    <td><button class="icon-button" style="background-color: #FCBF00;"><i class="fa-solid fa-rotate-left"></i></button></td>
                    <td></td>
                    <td></td>
                    <td><button class="icon-button" style="background-color: red;"><i class="fa-solid fa-trash"></i></button></td>
                    
                </tr>`;
    });
    document.getElementById("tableBody_facturas").innerHTML = content;
  } catch (ex) {
    alert(ex);
  }
};

window.addEventListener("load", async () => {
  await initDataTableFacturas();
});
