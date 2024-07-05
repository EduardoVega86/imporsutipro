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
let filtro_facturas = "pendientes";
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
    let url_tracking = "";
    let url_descargar = "";
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
        if (factura.valor_pendiente == 0) {
          check = "";
        } else {
          check = `<input type="checkbox" class="selectCheckbox" data-factura-id_cabecera="${factura.id_cabecera}" data-factura-valor="${factura.monto_recibir}">`;
        }
      } else if (factura.estado_guia == 9) {
        estado_guia = "Devuelto";
        if (factura.valor_pendiente == 0) {
          check = "";
        } else {
          check = `<input type="checkbox" class="selectCheckbox" data-factura-id_cabecera="${factura.id_cabecera}" data-factura-valor="${factura.monto_recibir}">`;
        }
      } else {
        estado_guia = "No acreditable";
      }

      if (factura.guia.includes("IMP")) {
        url_tracking = `https://fenix.laarcourier.com/Tracking/Guiacompleta.aspx?guia=${factura.guia}`;
        url_descargar = `https://api.laarcourier.com:9727/guias/pdfs/DescargarV2?guia=${factura.guia}`;
      } else if (factura.guia.includes("I")) {
        url_tracking = `https://ec.gintracom.site/web/site/tracking`;
        url_descargar = `https://guias.imporsuitpro.com/Gintracom/label/${factura.guia}`;
      } else if (factura.guia.includes("SPD")) {
        url_tracking = ``;
        url_descargar = `https://guias.imporsuitpro.com/Speed/descargar/${factura.guia}`;
      } else {
        url_tracking = `https://www.servientrega.com.ec/Tracking/?guia=${factura.guia}&tipo=GUIA`;
        url_descargar = `https://guias.imporsuitpro.com/Servientrega/guia/${factura.guia}`;
      }

      content += `
                <tr>
                    <td>${check}</td>
                    <td>
                    <div><span claas="text-nowrap">${factura.numero_factura}</span></div>
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
                        <li><a class="dropdown-item" style="cursor: pointer;" href="${url_tracking}" target="_blank">Traking</a></li>
                        <li><a class="dropdown-item" style="cursor: pointer;" href="${url_descargar}">Ticket</a></li>
                    </ul>
                    </div>
                    </td>
                    <td><button class="icon-button" style="background-color: green; margin: 0;" onclick="abrirModal_editarCabecera(${factura.id_cabecera})"><i class="fa-solid fa-pen-to-square" style="margin: 0;"></i></button></td>
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
                  "NOTIFICACIÓN",
                  {
                    positionClass: "toast-bottom-center",
                  }
                );
              } else if (response.status == 200) {
                toastr.success(
                  "ABONADO AGREGADO CORRECTAMENTE",
                  "NOTIFICACIÓN",
                  {
                    positionClass: "toast-bottom-center",
                  }
                );

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

function abrirModal_editarCabecera(id_cabecera) {
  $.ajax({
    url: SERVERURL + "wallet/obtenerCabecera/"+id_cabecera,
    type: "GET",
    dataType: "json",
    success: function (response) {
      $("#editar_walletModal").modal("show");
    },
    error: function (error) {
      console.error("Error al obtener la lista de bodegas:", error);
    },
  });
}

function procesarPlataforma(url) {
  // Eliminar el "https://"
  let sinProtocolo = url.replace("https://", "");

  // Encontrar la posición del primer punto
  let primerPunto = sinProtocolo.indexOf(".");

  // Obtener la subcadena desde el inicio hasta el primer punto
  let baseNombre = sinProtocolo.substring(0, primerPunto);

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

$(document).ready(function () {
  $(".filter-btn").on("click", function () {
    $(".filter-btn").removeClass("active");
    $(this).addClass("active");

    filtro_facturas = $(this).data("filter"); // Actualizar variable con el filtro seleccionado

    initDataTableFacturas();
  });

  $.ajax({
    url: SERVERURL + "wallet/obtenerCuentas",
    type: "GET",
    dataType: "json",
    success: function (response) {
      console.log(response);
      // Asegúrate de que la respuesta es un array
      if (Array.isArray(response)) {
        response.forEach(function (bodega) {
          // Agrega una nueva opción al select por cada bodega
          $("#bodega_inventarioVariable").append(
            new Option(bodega.nombre, bodega.id)
          );
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

//TABLA DE HISTORIAL PAGOS
let dataTableHistorialPago;
let dataTableHistorialPagoIsInitialized = false;

const dataTableHistorialPagoOptions = {
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

const initDataTableHistorialPago = async () => {
  if (dataTableHistorialPagoIsInitialized) {
    dataTableHistorialPago.destroy();
  }

  await listHistorialPago();

  dataTableHistorialPago = $("#datatable_historial_pago").DataTable(
    dataTableHistorialPagoOptions
  );

  dataTableHistorialPagoIsInitialized = true;
};

const listHistorialPago = async () => {
  try {
    const formData = new FormData();
    formData.append("tienda", tienda);

    const response = await fetch(`${SERVERURL}wallet/obtenerHistorial`, {
      method: "POST",
      body: formData,
    });
    const historialPago = await response.json();

    let content = ``;

    historialPago.forEach((pago, index) => {
      content += `
                <tr>
                    <td>${pago.id_historial}</td>
                    <td>${pago.tipo}</td>
                    <td>${pago.motivo}</td>
                    <td>${pago.monto}</td>
                    <td>${pago.nombre}</td>
                    <td>${pago.fecha}</td>
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
