// Obtener la URL actual
const urlActual = window.location.href;
// Crear un objeto URL
const url = new URL(urlActual);
// Obtener el valor del parámetro 'tienda'
const tienda = url.searchParams.get('tienda');

// Añadimos un evento que se ejecuta cuando el DOM ha sido completamente cargado
document.addEventListener('DOMContentLoaded', function() {
    cargarDashboard_wallet();
});

$(document).ready(function(){
    $('#regresar').click(function() {
        window.location.href = SERVERURL + 'wallet';
    });
});

function cargarDashboard_wallet(){
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
        $('#image_tienda').attr('src', SERVERURL+'public/img/profile_wallet.png');
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
let filtro_facturas="todas";
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

  dataTableFacturas = $("#datatable_facturas").DataTable(dataTableFacturasOptions);

  dataTableFacturasIsInitialized = true;
};

const listFacturas = async () => {
  try {
    const response = await fetch("" + SERVERURL + "wallet/obtenerDatos");
    const facturas = await response.json();

    let content = ``;

    facturas.forEach((factura, index) => {

      content += `
                <tr>
                    <td><input type="checkbox" class="selectCheckbox"></td>
                    <td>${factura.ventas}</td>
                    <td>${factura.utilidad}</td>
                    <td>${factura.count_visto_0}</td>
                    
                    <td>
                    <div class="dropdown">
                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-gear"></i>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <li><a class="dropdown-item" style="cursor: pointer;" href="${SERVERURL}wallet/pagar?tienda=${factura.tienda}"><i class='bx bx-wallet'></i>Pagar</a></li>
                    </ul>
                    </div>
                    </td>
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
