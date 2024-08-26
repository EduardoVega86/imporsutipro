// Obtener la URL actual
const urlActual = window.location.href;
// Crear un objeto URL
const url = new URL(urlActual);
// Obtener el valor del parámetro 'tienda'
const tienda = url.searchParams.get("id_plataforma");

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
      $("#tienda_span").text(response.plataforma_url);

      $("#totalVentas_wallet").text(response.ventas);
      $("#utilidadGenerada_wallet").text(response.utilidad);
      $("#descuentoDevolucion_wallet").text(response.devoluciones);
      $("#retirosAcreditados_wallet").text(response.abonos_registrados);
      $("#saldoBilletera_wallet").text(response.saldo);

      if (!response.verificar) {
        Swal.fire({
          icon: "error",
          title: "Wallet descuadrada, por favor contactar a Sistemas",
          showConfirmButton: false,
          timer: 2000,
        });
      }
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
  dom: '<"d-flex w-full justify-content-between"lBf><t><"d-flex justify-content-between"ip>',
  buttons: [
    {
      extend: "excelHtml5",
      text: 'Excel <i class="fa-solid fa-file-excel"></i>',
      title: "Panel de Control: Usuarios",
      titleAttr: "Exportar a Excel",
      exportOptions: {
        columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11],
      },
      filename: "facturas" + "_" + getFecha(),
      footer: true,
      className: "btn-excel",
    },
    {
      extend: "csvHtml5",
      text: 'CSV <i class="fa-solid fa-file-csv"></i>',
      title: "Panel de Control: facturas",
      titleAttr: "Exportar a CSV",
      exportOptions: {
        columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11],
      },
      filename: "facturas" + "_" + getFecha(),
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
    let check = "";
    let url_tracking = "";
    let url_descargar = "";
    let acreditable = "";
    facturas.forEach((factura, index) => {
      let tienda_nombre = procesarPlataforma(factura.tienda);
      if (factura.cod == 1) {
        cod = "Recaudo";
      } else {
        cod = "Sin Recaudo";
      }
      check = "";
      if (factura.estado_guia == 7) {
        if (factura.valor_pendiente == 0) {
          check = "";
        } else {
          if (filtro_facturas == "pendientes") {
            if (factura.visto == 1) {
              check = "";
            } else {
              check = `<input type="checkbox" class="selectCheckbox" data-factura-id_cabecera="${factura.id_cabecera}" data-factura-valor="${factura.monto_recibir}">`;
            }
          } else {
            check = "";
          }
        }
        acreditable = "acreditable";
      } else if (factura.estado_guia == 9) {
        if (factura.valor_pendiente >= 0) {
          check = "";
        } else {
          if (filtro_facturas == "pendientes") {
            if (factura.visto == 1) {
              check = "";
            } else {
              check = `<input type="checkbox" class="selectCheckbox" data-factura-id_cabecera="${factura.id_cabecera}" data-factura-valor="${factura.monto_recibir}">`;
            }
          } else {
            check = "";
          }
        }
        acreditable = "acreditable";
      } else {
        acreditable = "No acreditable";
      }

      if (factura.guia.includes("IMP") || factura.guia.includes("MKP")) {
        url_tracking = `https://fenixoper.laarcourier.com/Tracking/Guiacompleta.aspx?guia=${factura.guia}`;
        url_descargar = `https://api.laarcourier.com:9727/guias/pdfs/DescargarV2?guia=${factura.guia}`;
        estado = validar_estadoLaar(factura.estado_guia);
      } else if (factura.guia.includes("I")) {
        url_tracking = `https://ec.gintracom.site/web/site/tracking`;
        url_descargar = `https://guias.imporsuitpro.com/Gintracom/label/${factura.guia}`;
        estado = validar_estadoGintracom(factura.estado_guia);
      } else if (factura.guia.includes("SPD")) {
        url_tracking = ``;
        url_descargar = `https://guias.imporsuitpro.com/Speed/descargar/${factura.guia}`;
        estado = validar_estadoSpeed(factura.estado_guia);
      } else {
        url_tracking = `https://servientrega-ecuador.appsiscore.com/app/app-cliente/cons_publica.php?guia=${factura.guia}&Request=Buscar+`;
        url_descargar = `https://guias.imporsuitpro.com/Servientrega/guia/${factura.guia}`;
        estado = validar_estadoServi(factura.estado_guia);
      }

      var span_estado = estado.span_estado;
      var estado_guia = estado.estado_guia;
      
      var nombre_proveedor="";
      if(!factura.proveedor){
        nombre_proveedor = "---";
      } else {
        nombre_proveedor = procesarPlataforma(factura.proveedor);
      }
      
      if (filtro_facturas == "pendientes" && factura.visto == 1) {
        content += ``;
      } else {
        content += `
      <tr>
          <td>${check}</td>
          <td>
          <div><span claas="text-nowrap">${factura.numero_factura}</span></div>
          <div><span class="w-100 text-nowrap" style="background-color:#7B57EC; color:white; padding:5px; border-radius:0.3rem;">${cod}</span></div>
          </td>
          <td>
          <div>${factura.cliente}</div>
          <div>${factura.fecha}</div>
          <div><button onclick="ver_detalle_cot('${factura.numero_factura}')" class="btn btn-sm btn-outline-primary"> Ver detalle</button></div>
          </td>
          <td>${factura.guia}</td>
          <td>
          <div><span class="w-100 text-nowrap ${span_estado}">${estado_guia}</span></div>
          <div>${acreditable}</div>
          </td>
          <td>${tienda_nombre}</td>
          <td>${nombre_proveedor}</td>
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
              <li><a class="dropdown-item" style="cursor: pointer;" href="${url_tracking}" target="_blank">Tracking</a></li>
              <li><a class="dropdown-item" style="cursor: pointer;" href="${url_descargar}">Ticket</a></li>
          </ul>
          </div>
          </td>
          <td><button class="icon-button" style="background-color: green; margin: 0;" onclick="abrirModal_editarCabecera(${factura.id_cabecera})"><i class="fa-solid fa-pen-to-square" style="margin: 0;"></i></button></td>
          <td>
          <div class="dropdown">
          <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false" style="background-color: #000000 !important; border-color: #000000 !important;">
          <i class='bx bxs-cog'></i>
          </button>
          <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
              <li><a class="dropdown-item" style="cursor: pointer;" onclick="devolucion(${factura.id_cabecera})">Devolucion</a></li>
              <li><a class="dropdown-item" style="cursor: pointer;" onclick="entregar(${factura.id_cabecera})">Entregar</a></li>
          </ul>
          </div>
          </td>
          <td>${factura.trayecto}</td>
          <td></td>
          <td><button class="icon-button" style="background-color: red; margin: 0;" onclick="eliminar_wallet(${factura.id_cabecera})"><i class="fa-solid fa-trash" style="margin: 0;"></i></button></td>
          
      </tr>`;
      }
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
                cargarDashboard_wallet();
                initDataTableHistorialPago();
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
    url: SERVERURL + "wallet/obtenerCabecera/" + id_cabecera,
    type: "GET",
    dataType: "json",
    success: function (response) {
      $("#total_ventasEditar_Wallet").val(response[0].total_venta);
      $("#costoEditar_Wallet").val(response[0].costo);
      $("#precio_envioEditar_Wallet").val(response[0].precio_envio);
      $("#fulfilmentEditar_Wallet").val(response[0].full);
      $("#id_cabeceraEditarWallet").val(id_cabecera);

      initDataTableFacturas();
      $("#editar_walletModal").modal("show");
    },
    error: function (error) {
      console.error("Error al obtener la informacion:", error);
    },
  });
}

function devolucion(id_cabecera) {
  $.ajax({
    type: "POST",
    url: SERVERURL + "wallet/devolucion/" + id_cabecera,
    dataType: "json",
    success: function (response) {
      if (response.status == 500) {
        toastr.error("LA IMAGEN NO SE AGREGRO CORRECTAMENTE", "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });
      } else if (response.status == 200) {
        toastr.success("IMAGEN AGREGADA CORRECTAMENTE", "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });

        initDataTableFacturas();
      }
    },
    error: function (xhr, status, error) {
      console.error("Error en la solicitud AJAX:", error);
      alert("Hubo un problema al obtener la información de la categoría");
    },
  });
}

function entregar(id_cabecera) {
  $.ajax({
    type: "POST",
    url: SERVERURL + "wallet/entregar/" + id_cabecera,
    dataType: "json",
    success: function (response) {
      if (response.status == 500) {
        toastr.error("LA IMAGEN NO SE AGREGRO CORRECTAMENTE", "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });
      } else if (response.status == 200) {
        toastr.success("IMAGEN AGREGADA CORRECTAMENTE", "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });

        initDataTableFacturas();
      }
    },
    error: function (xhr, status, error) {
      console.error("Error en la solicitud AJAX:", error);
      alert("Hubo un problema al obtener la información de la categoría");
    },
  });
}

function eliminar_wallet(id_cabecera) {
  $.ajax({
    url: SERVERURL + "wallet/eliminar/" + id_cabecera,
    type: "POST",
    dataType: "json",
    success: function (response) {
      if (response.status == 500) {
        toastr.error("NO SE ELIMINO CORRECTAMENTE", "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });
      } else if (response.status == 200) {
        toastr.success("SE ELIMINO CORRECTAMENTE", "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });

        initDataTableFacturas();
      }
    },
    error: function (error) {
      console.error("Error al obtener la lista de bodegas:", error);
    },
  });
}

function ver_detalle_cot(numero_factura) {
  let formData = new FormData();
  formData.append("numero_factura", numero_factura);
  if (numero_factura.includes("-F")) {
    $.ajax({
      url: SERVERURL + "wallet/buscarTienda",
      type: "POST",
      data: formData,
      processData: false, // No procesar los datos
      contentType: false, // No establecer ningún tipo de contenido
      success: function (response) {
        response = JSON.parse(response);

        // Mostrar los detalles principales de la primera factura
        $("#ordePara_detalleFac").text(response[0].nombre);
        $("#direccion_detalleFac").text(
          `${response[0].c_principal},${response[0].c_secundaria}`
        );
        $("#telefono_detalleFac").text(response[0].telefono);
        $("#numOrden_detalleFac").text(response[0].numero_factura);
        $("#fecha_detalleFac").text(response[0].fecha_factura);
        $("#companiaEnvio_detalleFac").text(response[0].transporte);
        if (response[0].cod == 1) {
          $("#tipoEnvio_detalleFac").html(
            "Con Recaudo <br><strong>Tiendas:</strong> " + response[0].url
          );
        } else {
          $("#tipoEnvio_detalleFac").html(
            "Sin Recaudo <br><strong>Tiendas:</strong> " + response[0].url
          );
        }

        // Verificar si la respuesta tiene elementos y llenar la tabla
        if (response.length > 0) {
          let tableBody = $("#tabla_body");
          tableBody.empty(); // Limpiar cualquier contenido previo

          let total = 0; // Variable para calcular el total

          response.forEach(function (detalle) {
            let subtotal = detalle.cantidad * detalle.precio_venta;
            total += subtotal;

            let rowHtml = `
              <tr>
                <td>${detalle.nombre_producto}</td>
                <td>${detalle.cantidad}</td>
                <td>${detalle.precio_venta}</td>
                <td>${subtotal.toFixed(2)}</td>
              </tr>
            `;
            tableBody.append(rowHtml);
          });

          // Agregar la fila del total
          let totalRowHtml = `
            <tr class="custom-total-row">
              <td colspan="3" class="text-right">Total</td>
              <td>${total.toFixed(2)}</td>
            </tr>
          `;
          tableBody.append(totalRowHtml);
        }

        $("#detalles_facturaModal").modal("show");
      },
      error: function (error) {
        console.error("Error al obtener la lista de bodegas:", error);
      },
    });
  } else {
    $.ajax({
      url: SERVERURL + "Pedidos/obtenerDetalleWallet",
      type: "POST",
      data: formData,
      processData: false, // No procesar los datos
      contentType: false, // No establecer ningún tipo de contenido
      success: function (response) {
        response = JSON.parse(response);

        // Mostrar los detalles principales de la primera factura
        $("#ordePara_detalleFac").text(response[0].nombre);
        $("#direccion_detalleFac").text(
          `${response[0].c_principal},${response[0].c_secundaria}`
        );
        $("#telefono_detalleFac").text(response[0].telefono);
        $("#numOrden_detalleFac").text(response[0].numero_factura);
        $("#fecha_detalleFac").text(response[0].fecha_factura);
        $("#companiaEnvio_detalleFac").text(response[0].transporte);
        if (response[0].cod == 1) {
          $("#tipoEnvio_detalleFac").text("Con Recaudo");
        } else {
          $("#tipoEnvio_detalleFac").text("Sin Recaudo");
        }

        // Verificar si la respuesta tiene elementos y llenar la tabla
        if (response.length > 0) {
          let tableBody = $("#tabla_body");
          tableBody.empty(); // Limpiar cualquier contenido previo

          let total = 0; // Variable para calcular el total

          response.forEach(function (detalle) {
            let subtotal = detalle.cantidad * detalle.precio_venta;
            total += subtotal;

            let rowHtml = `
            <tr>
              <td>${detalle.nombre_producto}</td>
              <td>${detalle.cantidad}</td>
              <td>${detalle.precio_venta}</td>
              <td>${subtotal.toFixed(2)}</td>
            </tr>
          `;
            tableBody.append(rowHtml);
          });

          // Agregar la fila del total
          let totalRowHtml = `
          <tr class="custom-total-row">
            <td colspan="3" class="text-right">Total</td>
            <td>${total.toFixed(2)}</td>
          </tr>
        `;
          tableBody.append(totalRowHtml);
        }

        $("#detalles_facturaModal").modal("show");
      },
      error: function (error) {
        console.error("Error al obtener la lista de bodegas:", error);
      },
    });
  }
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
  order: [[1, "desc"]], // Ordenar por la primera columna (fecha) en orden descendente
  pageLength: 10,
  destroy: true,
  responsive: true,
  dom: '<"d-flex w-full justify-content-between"lBf><t><"d-flex justify-content-between"ip>',
  buttons: [
    {
      extend: "excelHtml5",
      text: 'Excel <i class="fa-solid fa-file-excel"></i>',
      title: "Panel de Control: Usuarios",
      titleAttr: "Exportar a Excel",
      exportOptions: {
        columns: [0, 1, 2, 3, 4],
      },
      filename: "pagos" + "_" + getFecha(),
      footer: true,
      className: "btn-excel",
    },
    {
      extend: "csvHtml5",
      text: 'CSV <i class="fa-solid fa-file-csv"></i>',
      title: "Panel de Control: pagos",
      titleAttr: "Exportar a CSV",
      exportOptions: {
        columns: [0, 1, 2, 3, 4],
      },
      filename: "pagos" + "_" + getFecha(),
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
                    <td>
                    <a href="${SERVERURL}${pago.imagen}" class="icon-link" target="_blank">
                    <i class="fas fa-receipt"></i>
                    </a>
                    </td>
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
  order: [[0, "desc"]], // Ordenar por la primera columna (fecha) en orden descendente
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
      filename: "acreditacion" + "_" + getFecha(),
      footer: true,
      className: "btn-excel",
    },
    {
      extend: "csvHtml5",
      text: 'CSV <i class="fa-solid fa-file-csv"></i>',
      title: "Panel de Control: acreditacion",
      titleAttr: "Exportar a CSV",
      exportOptions: {
        columns: [0, 1, 2, 3, 4, 5],
      },
      filename: "acreditacion" + "_" + getFecha(),
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

    // Verifica si la respuesta no es OK
    if (!response.ok) {
      return;
    }

    console.log("sin json: " + response);
    // Analiza la respuesta como JSON
    const historialPago = await response.json();

    console.log("json: " + historialPago);

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
    console.error("Error al obtener historialPago:", ex);
    alert("Error al obtener historialPago: " + ex.message);

    // Registra el texto de la respuesta para depuración
    try {
      const errorText = await ex.response.text();
      console.error("Texto de respuesta:", errorText);
    } catch (innerEx) {
      console.error("No se pudo analizar el texto de la respuesta:", innerEx);
    }
  }
};

window.addEventListener("load", async () => {
  await initDataTableHistorialPago();
});

function abrirModal_realizarPago() {
  $("#id_plataforma").val(tienda);
  $("#realizar_pagoModal").modal("show");
}

/* validar estado */
function validar_estadoLaar(estado) {
  var span_estado = "";
  var estado_guia = "";
  if (estado == 1) {
    span_estado = "badge_purple";
    estado_guia = "Generado";
  } else if (estado == 2) {
    span_estado = "badge_purple";
    estado_guia = "Por recolectar";
  } else if (estado == 3) {
    span_estado = "badge_purple";
    estado_guia = "Por recolectar";
  } else if (estado == 4) {
    span_estado = "badge_purple";
    estado_guia = "Por recolectar";
  } else if (estado == 5) {
    span_estado = "badge_warning";
    estado_guia = "En transito";
  } else if (estado == 6) {
    span_estado = "badge_warning";
    estado_guia = "Zona de entrega";
  } else if (estado == 7) {
    span_estado = "badge_green";
    estado_guia = "Entregado";
  } else if (estado == 8) {
    span_estado = "badge_danger";
    estado_guia = "Anulado";
  } else if (estado == 11) {
    span_estado = "badge_warning";
    estado_guia = "En transito";
  } else if (estado == 12) {
    span_estado = "badge_warning";
    estado_guia = "En transito";
  } else if (estado == 14) {
    span_estado = "badge_danger";
    estado_guia = "Con novedad";
  } else if (estado == 9) {
    span_estado = "badge_danger";
    estado_guia = "Devuelto";
  }

  return {
    span_estado: span_estado,
    estado_guia: estado_guia,
  };
}

function validar_estadoServi(estado) {
  var span_estado = "";
  var estado_guia = "";
  if (estado == 101) {
    span_estado = "badge_danger";
    estado_guia = "Anulado";
  } else if (estado == 100 || estado == 102 || estado == 103) {
    span_estado = "badge_purple";
    estado_guia = "Generado";
  } else if (estado == 200 || estado == 201 || estado == 202) {
    span_estado = "badge_purple";
    estado_guia = "Recolectado";
  } else if (estado >= 300 && estado <= 317) {
    span_estado = "badge_warning";
    estado_guia = "Procesamiento";
  } else if ((estado >= 400 && estado <= 403) || estado == 7) {
    span_estado = "badge_green";
    estado_guia = "Entregado";
  } else if (estado >= 318 && estado <= 351) {
    span_estado = "badge_danger";
    estado_guia = "Con novedad";
  } else if ((estado >= 500 && estado <= 502) || estado == 9) {
    span_estado = "badge_danger";
    estado_guia = "Devuelto";
  }

  return {
    span_estado: span_estado,
    estado_guia: estado_guia,
  };
}

function validar_estadoGintracom(estado) {
  var span_estado = "";
  var estado_guia = "";

  if (estado == 1) {
    span_estado = "badge_generado";
    estado_guia = "Generada";
  } else if (estado == 2) {
    span_estado = "badge_warning";
    estado_guia = "Picking";
  } else if (estado == 3) {
    span_estado = "badge_warning";
    estado_guia = "Packing";
  } else if (estado == 4) {
    span_estado = "badge_warning";
    estado_guia = "En tránsito";
  } else if (estado == 5) {
    span_estado = "badge_warning";
    estado_guia = "En reparto";
  } else if (estado == 6) {
    span_estado = "badge_purple";
    estado_guia = "Novedad";
  } else if (estado == 7) {
    span_estado = "badge_green";
    estado_guia = "Entregada";
  } else if (estado == 8) {
    span_estado = "badge_danger";
    estado_guia = "Devolucion";
  } else if (estado == 9) {
    span_estado = "badge_danger";
    estado_guia = "Devolución Entregada a Origen";
  } else if (estado == 10) {
    span_estado = "badge_danger";
    estado_guia = "Cancelada por transportadora";
  } else if (estado == 11) {
    span_estado = "badge_danger";
    estado_guia = "Indemnización";
  } else if (estado == 12) {
    span_estado = "badge_danger";
    estado_guia = "Anulada";
  } else if (estado == 13) {
    span_estado = "badge_danger";
    estado_guia = "Devolucion en tránsito";
  }

  return {
    span_estado: span_estado,
    estado_guia: estado_guia,
  };
}

function validar_estadoSpeed(estado) {
  var span_estado = "";
  var estado_guia = "";
  if (estado == 2) {
    span_estado = "badge_purple";
    estado_guia = "generado";
  } else if (estado == 3) {
    span_estado = "badge_warning";
    estado_guia = "En transito";
  } else if (estado == 7) {
    span_estado = "badge_green";
    estado_guia = "Entregado";
  } else if (estado == 9) {
    span_estado = "badge_danger";
    estado_guia = "Devuelto";
  } else if (estado == 1) {
    span_estado = "badge_purple";
    estado_guia = "Nuevo";
  }

  return {
    span_estado: span_estado,
    estado_guia: estado_guia,
  };
}
/* Fin validar estado */
