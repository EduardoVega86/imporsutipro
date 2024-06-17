// Obtener la URL actual
const urlActual = window.location.href;
// Crear un objeto URL
const url = new URL(urlActual);
// Obtener el valor del parámetro 'tienda'
const tienda = url.searchParams.get("tienda");

var pagos_global;

// Añadimos un evento que se ejecuta cuando el DOM ha sido completamente cargado
document.addEventListener("DOMContentLoaded", function () {
  cargarDashboard_wallet();
});

$(document).ready(function () {
  $("#regresar").click(function () {
    window.location.href = SERVERURL + "wallet";
  });
});

async function cargarDashboard_wallet() {
    let formData = new FormData();
    formData.append("tienda", tienda);
  
    try {
      let response = await fetch(SERVERURL + "wallet/obtenerDetalles", {
        method: "POST",
        body: formData
      });
  
      if (!response.ok) {
        throw new Error('Network response was not ok ' + response.statusText);
      }
  
      let data = await response.json();
  
      pagos_global = data.pagos;
      console.log("primero global: "+pagos_global);
      $("#image_tienda").attr(
        "src",
        SERVERURL + "public/img/profile_wallet.png"
      );
      $("#tienda_span").text(tienda);
  
      $("#totalVentas_wallet").text(data.ventas);
      $("#utilidadGenerada_wallet").text(data.utilidad);
      $("#descuentoDevolucion_wallet").text(data.devoluciones);
      $("#retirosAcreditados_wallet").text(data.abonos_registrados);
      $("#saldoBilletera_wallet").text(data.saldo);
  
    } catch (error) {
      alert('Error: ' + error.message);
    }
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
    estado_guia = "";
    facturas.forEach((factura, index) => {
      let tienda_nombre = procesarPlataforma(factura.tienda);
      if (factura.cod == 1) {
        estado_guia = "Recaudo";
      } else {
        estado_guia = "Sin Recaudo";
      }

      content += `
                <tr>
                    <td><input type="checkbox" class="selectCheckbox"></td>
                    <td>
                    <span claas="text-nowrap">${factura.numero_factura}</span> 
                    <span claas="text-nowrap">${factura.guia}</span> 
                    <span class="w-100 text-nowrap" style="background-color:#7B57EC; color:white; padding:5px; border-radius:0.3rem;">${estado_guia}</span>      
                    </td>
                    <td>
                    ${factura.cliente}
                    ${factura.fecha}
                    </td>
                    <td>${tienda_nombre}</td>
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

                    <td><button class="icon-button" style="background-color: green;"><i class="fa-solid fa-pen-to-square"></i></button></td>
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

function procesarPlataforma(url) {
  // Eliminar el "https://"
  let sinProtocolo = url.replace("https://", "");

  // Eliminar ".imporsuitpro.com"
  let baseNombre = sinProtocolo.replace(".imporsuitpro.com", "");

  // Convertir a mayúsculas
  let resultado = baseNombre.toUpperCase();

  return resultado;
}

window.addEventListener("load", async () => {
  await initDataTableFacturas();
});


//TABLA DE PAGOS
let dataTablePagos;
let dataTablePagosIsInitialized = false;

const dataTablePagosOptions = {
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

const initDataTablePagos = async () => {
  if (dataTablePagosIsInitialized) {
    dataTablePagos.destroy();
  }

  await listPagos();

  dataTablePagos = $("#datatable_pagos").DataTable(dataTablePagosOptions);

  dataTablePagosIsInitialized = true;
};

const listPagos = async () => {
  try {
    const pagos =  pagos_global;
    let content = ``;
    let tipo ="";
    console.log(pagos)
    pagos.forEach((pago, index) => {
        console.log("pago1"+pago.fecha);

        if (pago.recargo == 0){
            tipo= "Pago de Billetera";
        }else{
            tipo= "Recargo de Billetera";
        }
      content += `
                <tr>
                    <td>${pago.numero_documento}</td>
                    <td>${pago.fecha}</td>
                    <td>${tipo}</td>
                    <td>${pago.valor}</td>
                    <td>${pago.forma_pago}</td>
                    <td></td>
                </tr>`;
    });
    document.getElementById("tableBody_pagos").innerHTML = content;
  } catch (ex) {
    alert(ex);
  }
};

window.addEventListener("load", async () => {
  await initDataTablePagos();
});
