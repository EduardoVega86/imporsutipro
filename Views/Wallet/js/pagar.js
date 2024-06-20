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

      pagos_global = response.pagos;
      initDataTablePagos();
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
let filtro_facturas = "todos";
let dataTableFacturas;
let dataTableFacturasIsInitialized = false;

const dataTableFacturasOptions = {
  columnDefs: [
    { className: "centered", targets: [1, 2, 3, 4, 5] },
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
    let cod = "";
    let estado_guia = "";
    let check = "";
    facturas.forEach((factura, index) => {
      let tienda_nombre = procesarPlataforma(factura.tienda);
      if (factura.cod == 1) {
        cod = "Recaudo";
      } else {
        cod = "Sin Recaudo";
      }
      check = "";
      if (factura.estado_guia == 7) {
        estado_guia = "Entregado";
        if (factura.monto_recibir == 0){
          check = "";
        }else{
          check = `<input type="checkbox" class="selectCheckbox" data-factura-id_cabecera="${factura.id_cabecera}" data-factura-valor="${factura.monto_recibir}">`;
        }
      } else if (factura.estado_guia == 9) {
        estado_guia = "Devuelto";
        if (factura.monto_recibir == 0){
          check = "";
        }else{
          check = `<input type="checkbox" class="selectCheckbox" data-factura-id_cabecera="${factura.id_cabecera}" data-factura-valor="${factura.monto_recibir}">`; 
        }
      } else {
        estado_guia = "No acreditable";
      }

      content += `
                <tr>
                    <td>${check}</td>
                    <td>
                    <div><span claas="text-nowrap">${
                      factura.numero_factura
                    }</span></div>
                    <div><span claas="text-nowrap">${factura.guia}</span></div>
                    <div><span class="w-100 text-nowrap" style="background-color:#7B57EC; color:white; padding:5px; border-radius:0.3rem;">${cod}</span></div>
                    </td>
                    <td>
                    <div>${factura.cliente}</div>
                    <div>${factura.fecha}</div>
                    </td>
                    <td>${estado_guia}</td>
                    <td>${tienda_nombre}</td>
                    <td>${factura.total_venta}</td>
                    <td>${factura.costo}</td>
                    <td>${factura.precio_envio}</td>
                    <td>${factura.full}</td>
                    <td>${factura.monto_recibir}</td>
                    <td>${factura.valor_pendiente}</td>
                    <td>${factura.peso}</td>
                    <td>
                    <div class="dropdown">
                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class='bx bxs-truck' ></i>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <li><a class="dropdown-item" style="cursor: pointer;" href="https://fenix.laarcourier.com/Tracking/Guiacompleta.aspx?guia=${
                          factura.guia
                        }">Traking</a></li>
                        <li><a class="dropdown-item" style="cursor: pointer;" href="https://api.laarcourier.com:9727/guias/pdfs/DescargarV2?guia=${
                          factura.guia
                        }">Ticket</a></li>
                    </ul>
                    </div>
                    </td>

                    <td><button class="icon-button" style="background-color: green; margin: 0;"><i class="fa-solid fa-pen-to-square" style="margin: 0;"></i></button></td>
                    <td><button class="icon-button" style="background-color: #FCBF00; margin: 0;"><i class="fa-solid fa-rotate-left" style="margin: 0;"></i></button></td>
                    <td></td>
                    <td></td>
                    <td><button class="icon-button" style="background-color: red; margin: 0;"><i class="fa-solid fa-trash" style="margin: 0;"></i></button></td>
                    
                </tr>`;
    });
    document.getElementById("tableBody_facturas").innerHTML = content;

    // Añadir evento de clic a los checkboxes
    document.querySelectorAll(".selectCheckbox").forEach((checkbox) => {
      checkbox.addEventListener("click", async (event) => {
        const target = event.target;
        if (!target.disabled) {
          target.disabled = true; // Bloquea el checkbox
          const id_cabecera = target.getAttribute("data-factura-id_cabecera");
          const valor = target.getAttribute("data-factura-valor");

          let formData = new FormData();
          formData.append("id_cabecera", id_cabecera);
          formData.append("valor", valor);

          $.ajax({
            url: SERVERURL + "wallet/abonarBilletera",
            type: "POST",
            data: formData,
            processData: false, // No procesar los datos
            contentType: false, // No establecer ningún tipo de contenido
            success: function (response) {
              response = JSON.parse(response);
              if (response.status == 500) {
                toastr.error(
                    "EL ABONADO NO SE AGREGRO CORRECTAMENTE",
                    "NOTIFICACIÓN", {
                        positionClass: "toast-bottom-center"
                    }
                );
            } else if (response.status == 200) {
                toastr.success("ABONADO AGREGADO CORRECTAMENTE", "NOTIFICACIÓN", {
                    positionClass: "toast-bottom-center",
                });

                initDataTableFacturas();
            }
            },
            error: function (jqXHR, textStatus, errorThrown) {
              alert(errorThrown);
            },
          });
        }
      });
    });
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
    const pagos = pagos_global;
    let content = ``;
    let tipo = "";
    console.log("pagos: " + pagos);
    pagos.forEach((pago, index) => {
      console.log("pago1" + pago.fecha);

      if (pago.recargo == 0) {
        tipo = "Pago de Billetera";
      } else {
        tipo = "Recargo de Billetera";
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

$(document).ready(function() {

  $('.filter-btn').on('click', function() {
    $('.filter-btn').removeClass('active');
    $(this).addClass('active');

    filtro_facturas = $(this).data('filter'); // Actualizar variable con el filtro seleccionado

    initDataTableFacturas()
  });
});

//TABLA DE HISTORIAL PAGOS
let dataTableHistorialPago;
let dataTableHistorialPagoIsInitialized = false;

const dataTableHistorialPagoOptions = {
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

const initDataTableHistorialPago = async () => {
  if (dataTableHistorialPagoIsInitialized) {
    dataTableHistorialPago.destroy();
  }

  await listHistorialPago();

  dataTableHistorialPago = $("#datatable_historial_pago").DataTable(dataTableHistorialPagoOptions);

  dataTableHistorialPagoIsInitialized = true;
};

const listHistorialPago = async () => {
  try {
    const response = await fetch("" + SERVERURL + "wallet/obtenerDatos");
    const historialPago = await response.json();

    let content = ``;

    historialPago.forEach((pago, index) => {

      content += `
                <tr>
                    <td><a class="dropdown-item link-like" href="${SERVERURL}wallet/pagar?tienda=${pago.tienda}">${pago.tienda}</a></td>
                    <td>${pago.ventas}</td>
                    <td>${pago.utilidad}</td>
                    <td>${pago.count_visto_0}</td>
                    <td>
                    <button id="downloadExcel" class="btn btn-success" onclick="descargarExcel_general('${pago.tienda}')">Descargar Excel general</button>
                    <button id="downloadExcel" class="btn btn-success" onclick="descargarExcel('${pago.tienda}')">Descargar Excel</button>
                    </td>
                    <td>
                    <div class="dropdown">
                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-gear"></i>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <li><a class="dropdown-item" style="cursor: pointer;" href="${SERVERURL}wallet/pagar?tienda=${pago.tienda}"><i class='bx bx-wallet'></i>Pagar</a></li>
                    </ul>
                    </div>
                    </td>
                </tr>`;
    });
    document.getElementById("tableBody_historial_pago").innerHTML = content;
  } catch (ex) {
    alert(ex);
  }
};

window.addEventListener("load", async () => {
  await initDataTableHistorialPago();
});
