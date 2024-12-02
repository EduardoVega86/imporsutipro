//TABLA DE DATOS BANCARIOS
let dataTableDatosBancarios;
let dataTableDatosBancariosIsInitialized = false;

const dataTableDatosBancariosOptions = {
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

const initDataTableDatosBancarios = async () => {
  if (dataTableDatosBancariosIsInitialized) {
    dataTableDatosBancarios.destroy();
  }

  await listDatosBancarios();

  dataTableDatosBancarios = $("#datatable_datos_bancarios").DataTable(
    dataTableDatosBancariosOptions
  );

  dataTableDatosBancariosIsInitialized = true;
};

const listDatosBancarios = async () => {
  try {
    const response = await fetch(
      "" + SERVERURL + "wallet/obtenerDatosFacturacion"
    );
    const datosBancarios = await response.json();

    const datos_bancarios = datosBancarios.datos_bancarios;

    let content = ``;

    datos_bancarios.forEach((dato, index) => {
      content += `
                <tr>
                    <td>${dato.id_cuenta}</td>
                    <td>${dato.tipo_cuenta}</td>
                    <td>${dato.banco}</td>
                    <td>${dato.numero_cuenta}</td>
                    <td>${dato.nombre}</td>
                    <td>${dato.cedula}</td>
                    <td>${dato.correo}</td>
                    <td>${dato.telefono}</td>
                    <td><button class="icon-button" style="background-color: red; margin: 0;" onclick="eliminar_datoBancario(${dato.id_cuenta})"><i class="fa-solid fa-trash" style="margin: 0;"></i></button></td>
                </tr>`;
    });
    document.getElementById("tableBody_datos_bancarios").innerHTML = content;
  } catch (ex) {
    alert(ex);
  }
};

window.addEventListener("load", async () => {
  await initDataTableDatosBancarios();
});

//TABLA DE DATOS DE FACTURACION
let dataTableDatosFacturacion;
let dataTableDatosFacturacionIsInitialized = false;

const dataTableDatosFacturacionOptions = {
  columnDefs: [
    { className: "centered", targets: [0, 1, 2, 3, 4, 5] },
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

const initDataTableDatosFacturacion = async () => {
  if (dataTableDatosFacturacionIsInitialized) {
    dataTableDatosFacturacion.destroy();
  }

  await listDatosFacturacion();

  dataTableDatosFacturacion = $("#datatable_datos_facturacion").DataTable(
    dataTableDatosFacturacionOptions
  );

  dataTableDatosFacturacionIsInitialized = true;
};

const listDatosFacturacion = async () => {
  try {
    const response = await fetch(
      "" + SERVERURL + "wallet/obtenerDatosFacturacion"
    );
    const datosFacturacion = await response.json();
    const datos_facturacion = datosFacturacion.datos_facturacion;

    let content = ``;

    datos_facturacion.forEach((dato, index) => {
      content += `
                <tr>
                    <td>${dato.id_facturacion}</td>
                    <td>${dato.razon_social}</td>
                    <td>${dato.ruc}</td>
                    <td>${dato.direccion}</td>
                    <td>${dato.correo}</td>
                    <td>${dato.telefono}</td>
                    <td><button class="icon-button" style="background-color: red; margin: 0;" onclick="eliminar_datofacturacion(${dato.id_facturacion})"><i class="fa-solid fa-trash" style="margin: 0;"></i></button></td>
                </tr>`;
    });
    document.getElementById("tableBody_datos_facturacion").innerHTML = content;
  } catch (ex) {
    alert(ex);
  }
};

window.addEventListener("load", async () => {
  await initDataTableDatosFacturacion();
});

let dataTableObtenerOtroPago;
let dataTableObtenerOtroPagoIsInitialized = false;

const dataTableObtenerOtroPagoOptions = {
  columnDefs: [
    { className: "centered", targets: [1, 2, 3] },
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

const initDataTableObtenerOtroPago = async () => {
  if (dataTableObtenerOtroPagoIsInitialized) {
    dataTableObtenerOtroPago.destroy();
  }

  await listObtenerOtroPago();

  dataTableObtenerOtroPago = $("#datatable_obtenerOtroPago").DataTable(
    dataTableObtenerOtroPagoOptions
  );

  dataTableObtenerOtroPagoIsInitialized = true;
};

const listObtenerOtroPago = async () => {
  try {
    const response = await fetch("" + SERVERURL + "wallet/obtenerOtroPago");
    const obtenerOtroPago = await response.json();

    let content = ``;

    obtenerOtroPago.forEach((pago, index) => {
      content += `
                <tr>
                    <td>${pago.id_pago}</td>
                    <td>${pago.cuenta}</td>
                    <td>${pago.tipo}</td>
                    <td>${pago.red}</td>
                    <td>
                        <button class="btn btn-sm btn-danger" onclick="eliminarForma_pago(${pago.id_pago})"><i class="fa-solid fa-trash-can"></i>Borrar</button>
                    </td>
                </tr>`;
    });
    document.getElementById("tableBody_obtenerOtroPago").innerHTML = content;
  } catch (ex) {
    alert(ex);
  }
};

window.addEventListener("load", async () => {
  await initDataTableObtenerOtroPago();
});

function eliminarForma_pago(id) {
  let formData = new FormData();
  formData.append("id", id);
  $.ajax({
    type: "POST",
    url: SERVERURL + "wallet/eliminarOtroPago",
    data: formData,
    processData: false, // No procesar los datos
    contentType: false, // No establecer ningún tipo de contenido
    dataType: "json",
    success: function (response) {
      // Mostrar alerta de éxito
      if (response.status == 500) {
        Swal.fire({
          icon: "error",
          title: response.title,
          text: response.message,
        });
      } else if (response.status == 500) {
        Swal.fire({
          icon: "success",
          title: response.title,
          text: response.message,
          showConfirmButton: false,
          timer: 2000,
        }).then(() => {
          // Recargar la DataTable
          initDataTableObtenerOtroPago();
        });
      }
    },
    error: function (xhr, status, error) {
      console.error("Error en la solicitud AJAX:", error);
      alert("Hubo un problema al eliminar la forma de pago");
    },
  });
}

//FUNCIONES PARA ENVIO DE INFORMACION
$(document).ready(function () {
  //enviar datos bancarios
  $("#datos_bancario").on("submit", function (event) {
    event.preventDefault();

    var formData = {
      banco: $("#banco").val(),
      tipo_cuenta: $("#tipo_cuenta").val(),
      numero_cuenta: $("#numero_cuenta").val(),
      nombre: $("#nombre_titular").val(),
      cedula: $("#cedula_titular").val(),
      correo: $("#correo_titular").val(),
      telefono: $("#telefono_titular").val(),
    };

    $.ajax({
      url: SERVERURL + "wallet/guardarDatosBancarios",
      type: "POST",
      data: formData,
      dataType: "json",
      success: function (response) {
        if (response.status == 400) {
          if (response.code == "23000") {
            toastr.error(
              "ESTE NUMERO DE CUENTA YA HA SIDO REGISTRADO",
              "NOTIFICACIÓN",
              {
                positionClass: "toast-bottom-center",
              }
            );
          } else {
            toastr.error(
              "DATOS BANCARIOS NO SE AGREGARON CORRECTAMENTE",
              "NOTIFICACIÓN",
              {
                positionClass: "toast-bottom-center",
              }
            );
          }
        } else if (response.status == 200) {
          toastr.success(
            "DATOS BANCARIOS SE AGREGARON CORRECTAMENTE",
            "NOTIFICACIÓN",
            {
              positionClass: "toast-bottom-center",
            }
          );

          initDataTableDatosBancarios();
        }
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.error(textStatus, errorThrown);
        alert("Error al guardar los datos");
      },
    });
  });

  //datos facturacion
  $("#datos_facturacion").on("submit", function (event) {
    event.preventDefault();

    var formData = {
      ruc: $("#ruc_factura").val(),
      razon_social: $("#razon_socialFactura").val(),
      direccion: $("#direccion_factura").val(),
      correo: $("#correo_factura").val(),
      telefono: $("#telefono_factura").val(),
    };

    $.ajax({
      url: SERVERURL + "wallet/guardarDatosFacturacion",
      type: "POST",
      data: formData,
      dataType: "json",
      success: function (response) {
        if (response.status == 500) {
          toastr.error(
            "DATOS DE FACTURACION NO SE AGREGARON CORRECTAMENTE",
            "NOTIFICACIÓN",
            {
              positionClass: "toast-bottom-center",
            }
          );
        } else if (response.status == 200) {
          toastr.success(
            "DATOS DE FACTURACION SE AGREGARON CORRECTAMENTE",
            "NOTIFICACIÓN",
            {
              positionClass: "toast-bottom-center",
            }
          );

          initDataTableDatosFacturacion();
        }
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.error(textStatus, errorThrown);
        alert("Error al guardar los datos");
      },
    });
  });

  $("#forma_pago").change(function () {
    var select_formaPago = $("#forma_pago").val();

    if (select_formaPago == "PAYONEER") {
      $("#select_red").hide();
    } else {
      $("#select_red").show();
    }
  });
});

//enviar forma de pago
$("#info_formaPago").on("submit", function (event) {
  event.preventDefault();

  var formData = {
    tipo: $("#forma_pago").val(),
    cuenta: $("#cuenta_formaPago").val(),
    red: $("#red_transferencia").val(),
  };

  $.ajax({
    url: SERVERURL + "wallet/agregarOtroPago",
    type: "POST",
    data: formData,
    dataType: "json",
    success: function (response) {
      if (response.status == 400) {
        toastr.error(
          "DATOS BANCARIOS NO SE AGREGARON CORRECTAMENTE",
          "NOTIFICACIÓN",
          {
            positionClass: "toast-bottom-center",
          }
        );
      } else if (response.status == 200) {
        toastr.success(
          "DATOS BANCARIOS SE AGREGARON CORRECTAMENTE",
          "NOTIFICACIÓN",
          {
            positionClass: "toast-bottom-center",
          }
        );

        initDataTableObtenerOtroPago();
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.error(textStatus, errorThrown);
      alert("Error al guardar los datos");
    },
  });
});

//eliminar dato bancario
function eliminar_datoBancario(id_cuenta) {
  let formData = new FormData();
  formData.append("id_cuenta", id_cuenta);

  $.ajax({
    url: SERVERURL + "wallet/eliminarDatoBancario",
    type: "POST",
    data: formData,
    processData: false, // No procesar los datos
    contentType: false, // No establecer ningún tipo de contenido
    success: function (response) {
      response = JSON.parse(response);
      if (response.status == 200) {
        toastr.success("SE ELIMINO CORRECTAMENTE", "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });

        initDataTableDatosBancarios();
      } else {
        toastr.error("NO SE ELIMINO CORRECTAMENTE", "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      alert(errorThrown);
    },
  });
}

//eliminar datos factracion
function eliminar_datofacturacion(id_facturacion) {
  let formData = new FormData();
  formData.append("id_facturacion", id_facturacion);

  $.ajax({
    url: SERVERURL + "wallet/eliminarDatoFacturacion",
    type: "POST",
    data: formData,
    processData: false, // No procesar los datos
    contentType: false, // No establecer ningún tipo de contenido
    success: function (response) {
      response = JSON.parse(response);
      if (response.status == 200) {
        toastr.success("SE ELIMINO CORRECTAMENTE", "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });

        initDataTableDatosFacturacion();
      } else {
        toastr.error("NO SE ELIMINO CORRECTAMENTE", "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      alert(errorThrown);
    },
  });
}

$(document).ready(function () {
  $("#banco").select2();
});
