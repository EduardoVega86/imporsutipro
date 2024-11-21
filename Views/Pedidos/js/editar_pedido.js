let dataTableNuevoPedido;
let dataTableNuevoPedidoIsInitialized = false;
let eliminado = false;
let generar_guiaTransportadora;
// Obtener el valor del id_factura desde la URL
const url = window.location.href;
const id_factura = url.split("/").pop();
let numero_factura;

const dataTableNuevoPedidoOptions = {
  paging: false,
  searching: false,
  info: false,
  lengthChange: false,
  destroy: true,
  autoWidth: false,
  columnDefs: [{ className: "centered", targets: [0, 1, 2, 3, 4, 5, 6] }],
  /* dom: '<"flex justify-between items-center mb-4"lBf<"text-center mt-4">r>t<"flex justify-between items-center"ip>', */
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

const initDataTableNuevoPedido = async () => {
  if (dataTableNuevoPedidoIsInitialized) {
    dataTableNuevoPedido.destroy();
  }

  await listNuevoPedido();

  dataTableNuevoPedido = $("#datatable_nuevoPedido").DataTable(
    dataTableNuevoPedidoOptions
  );

  dataTableNuevoPedidoIsInitialized = true;
};

var celular_bodega = "";
var nombre_bodega = "";
var ciudad_bodega = "";
var provincia_bodega = "";
var direccion_bodega = "";
var referencia_bodega = "";
var numeroCasa_bodega = "";
var id_propietario_bodega = "";
var id_producto_venta = "";
var dropshipping = "";
var contiene = "";
var contieneGintracom = "";
var costo_producto = 0;

var costo_general = 0;

const listNuevoPedido = async () => {
  try {
    const response = await fetch(
      "" + SERVERURL + "pedidos/datos_pedido/" + id_factura
    );

    const data = await response.json();
    nuevosPedidos = data;

    let content = ``;
    let total = 0;
    let precio_costo = 0;
    costo_producto = 0;
    let variedad = "";

    costo_general = 0;

    let id_combo = 0;
    let combo = 0;

    nuevosPedidos.forEach((nuevoPedido, index) => {
      $("#id_producto_temporal").val(nuevoPedido.id_producto);

      numero_factura = nuevoPedido.numero_factura;
      id_producto_venta = nuevoPedido.id_producto;
      dropshipping = nuevoPedido.drogshipin;
      costo_producto =
        costo_producto +
        parseFloat(nuevoPedido.pcp) * parseFloat(nuevoPedido.cantidad);

      variedad = "";
      if (nuevoPedido.variedad != null) {
        variedad = `${nuevoPedido.variedad}`;
      }
      contiene += ` ${nuevoPedido.cantidad} x ${nuevoPedido.nombre_producto} ${variedad}`;
      contieneGintracom += ` ${nuevoPedido.nombre_producto} ${variedad} X${nuevoPedido.cantidad} `;

      precio_costo = parseFloat(nuevoPedido.precio_venta);

      // Verificar condición
      /* if (!validar_direccion()) {
        return; // Salir de la función si la validación falla
      } */

      costo_general = costo_general + nuevoPedido.pcp * nuevoPedido.cantidad;

      let color_combo = "";
      let disable_combo = "";
      let boton_eliminar = "";

      if (nuevoPedido.combo == 1) {
        color_combo = 'style = "color: blue"';
        disable_combo = "disabled";

        combo = nuevoPedido.combo;
        id_combo = nuevoPedido.id_combo;

        boton_eliminar = "";
      } else {
        boton_eliminar = `<button class="btn btn-sm btn-danger" onclick="eliminar_nuevoPedido(${nuevoPedido.id_detalle})"><i class="fa-solid fa-trash-can"></i></button>`;
      }

      const precio = parseFloat(nuevoPedido.precio_venta);
      const descuento = parseFloat(nuevoPedido.desc_venta);
      const cantidad = parseFloat(nuevoPedido.cantidad);
      const subtotal = precio * cantidad;
      const descuentoTotal = subtotal * (descuento / 100);
      const precioFinal = subtotal - descuentoTotal;
      total += precioFinal;
      content += `
                <tr>
                <input type="hidden" id="id_productoBuscar_${index}" name="id_productoBuscar_${index}" value= "${
        nuevoPedido.id_producto
      }">
                <input type="hidden" id="sku_productoBuscar_${index}" name="sku_productoBuscar_${index}" value= "${
        nuevoPedido.sku
      }"></input>
                    <td ${color_combo}>${nuevoPedido.id_producto}</td>
                    <td><input ${disable_combo} type="text" onblur='recalcular("${
        nuevoPedido.id_producto
      }", "${nuevoPedido.id_detalle}"
                    , "precio_nuevoPedido_${index}", "descuento_nuevoPedido_${index}"
                    , "cantidad_nuevoPedido_${index}")' id="cantidad_nuevoPedido_${index}" 
        class="form-control prec" 
        value="${nuevoPedido.cantidad}">
        </td>
                    <td>${nuevoPedido.nombre_producto} ${variedad}</td>
                    <td><input ${disable_combo} type="text" onblur='recalcular("${
        nuevoPedido.id_producto
      }", "${nuevoPedido.id_detalle}"
                    , "precio_nuevoPedido_${index}", "descuento_nuevoPedido_${index}"
                    , "cantidad_nuevoPedido_${index}")' id="precio_nuevoPedido_${index}" class="form-control prec" value="${precio}"></td>
                    <td><input ${disable_combo} type="text" onblur='recalcular("${
        nuevoPedido.id_producto
      }", "${nuevoPedido.id_detalle}"
                    , "precio_nuevoPedido_${index}", "descuento_nuevoPedido_${index}"
                    , "cantidad_nuevoPedido_${index}")' id="descuento_nuevoPedido_${index}" class="form-control desc" value="${descuento}"></td>
                    <td><span class='tota' id="precioFinal_nuevoPedido_${index}">${precioFinal.toFixed(
        2
      )}</span></td>
                    <td>
                        ${boton_eliminar}
                    </td>
                </tr>`;
    });

    /* Seccion combo */
    if (combo == 1) {
      let formData = new FormData();
      formData.append("id", id_combo);

      $.ajax({
        url: SERVERURL + "Productos/obtener_combo_id",
        type: "POST",
        data: formData,
        processData: false, // No procesar los datos
        contentType: false, // No establecer ningún tipo de contenido
        dataType: "json",
        success: function (response) {
          $("#nombre_combo").text(response[0].nombre);
          $("#alerta_nombreCombo").show();
        },
        error: function (jqXHR, textStatus, errorThrown) {
          alert(errorThrown);
        },
      });
    }
    /* Fin Seccion combo */

    document.getElementById("monto_total").innerHTML = total.toFixed(2);
    document.getElementById("tableBody_nuevoPedido").innerHTML = content;
    if (eliminado == true) {
      eliminado = false;
      document.getElementById("monto_total").innerHTML = 0;
      document.getElementById("tableBody_nuevoPedido").innerHTML = "";
    }
  } catch (ex) {
    alert(ex);
  }
};

function recalcular(id_producto, id, idPrecio, idDescuento, idCantidad) {
  var button2 = document.getElementById("generarGuiaBtn");
  button2.disabled = true;

  const precio = parseFloat(document.getElementById(idPrecio).value);
  const descuento = parseFloat(document.getElementById(idDescuento).value);
  const cantidad = parseFloat(document.getElementById(idCantidad).value);
  contiene = "";
  contieneGintracom = "";
  const ffrm = new FormData();
  ffrm.append("id", id);
  ffrm.append("precio", precio);
  ffrm.append("descuento", descuento);
  ffrm.append("cantidad", cantidad);

  fetch("" + SERVERURL + "pedidos/actualizarDetalle/" + id, {
    method: "POST",
    body: ffrm,
  })
    .then((response) => response.json())
    .then(async (data) => {
      if (data.status == 200) {
        toastr.success("PRODUCTO ACTUALIZADO CORRECTAMENTE", "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });
      } else {
        toastr.error(
          "EL PRODUCTO NO SE ACTUALIZADO CORRECTAMENTE",
          "NOTIFICACIÓN",
          {
            positionClass: "toast-bottom-center",
          }
        );
      }
      // Retraso de 1 segundo antes de ejecutar initDataTableNuevoPedido
      await new Promise((resolve) => setTimeout(resolve, 1000));
      await initDataTableNuevoPedido();

      /* calcularGuiaDirecta */

      var priceSpan = $(this).find(".price-tag span");
      var priceValue = $("#costo_flete").val();

      var monto_total_general = $("#monto_total").text().trim();

      let formData = new FormData();
      formData.append("id_producto", id_producto);
      formData.append("total", monto_total_general);
      formData.append("tarifa", priceValue);
      formData.append("costo", costo_general);

      $.ajax({
        url: SERVERURL + "calculadora/calcularGuiaDirecta",
        type: "POST", // Cambiar a POST para enviar FormData
        data: formData,
        processData: false, // No procesar los datos
        contentType: false, // No establecer ningún tipo de contenido
        dataType: "json",
        success: function (response) {
          $("#montoVenta_infoVenta").text(response.total);
          $("#costo_infoVenta").text(response.costo);
          $("#precioEnvio_infoVenta").text(response.tarifa);
          $("#fulfillment_infoVenta").text(response.full);
          $("#total_infoVenta").text(response.resultante);

          calcularTarifas();

          if (response.resultante > 0) {
            if (response.generar == false) {
              button2.disabled = true;
              $("#alerta_valoresContra").show();
            } else {
              button2.disabled = false;
              $("#alerta_valoresContra").hide();
            }
          }
        },
        error: function (jqXHR, textStatus, errorThrown) {
          alert(errorThrown);
        },
      });
      /* Fin calcularGuiaDirecta */
    })
    .catch((error) => {
      console.error("Error:", error);
      alert("Hubo un problema al actualizar el producto");
    });
}

/* function validar_direccion() {
  // Obtener los parámetros de la URL
  const urlParams = new URLSearchParams(window.location.search);
  const idProducto = urlParams.get("id_producto");
  const sku = urlParams.get("sku");

  // Solo realizar la validación si los parámetros están presentes
  if (idProducto && sku) {
    if (
      ciudad_bodega == null ||
      provincia_bodega == null ||
      direccion_bodega == null
    ) {
      // Bloquear los botones
      const guardarPedidoBtn = document.getElementById("guardarPedidoBtn");
      const generarGuiaBtn = document.getElementById("generarGuiaBtn");

      guardarPedidoBtn.disabled = true;
      generarGuiaBtn.disabled = true;

      // Crear el tooltip
      toastr.error(
        "Esta bodega no contiene datos de dirección y no puede generar guias",
        "NOTIFICACIÓN",
        {
          positionClass: "toast-bottom-center",
        }
      );

      return false; // Retorna falso para indicar que la validación falló
    }
  }
  return true; // Retorna verdadero si la validación pasa o los parámetros no están presentes
} */

async function eliminar_nuevoPedido(id) {
  let eliminado = true;
  try {
    const response = await $.ajax({
      type: "POST",
      url: SERVERURL + "pedidos/eliminarDescripcion/" + id,
    });

    // Mostrar alerta de éxito
    if (response.status == 500) {
      toastr.error(
        "EL PRODUCTO NO SE ELIMINADO CORRECTAMENTE",
        "NOTIFICACIÓN",
        { positionClass: "toast-bottom-center" }
      );
    } else if (response.status == 200) {
      toastr.success("PRODUCTO ELIMINADO CORRECTAMENTE", "NOTIFICACIÓN", {
        positionClass: "toast-bottom-center",
      });
    }

    // Recargar la DataTable
    await initDataTableNuevoPedido();
  } catch (error) {
    console.error("Error en la solicitud AJAX:", error);
    alert("Hubo un problema al eliminar la categoría");
  }
}

window.addEventListener("load", async () => {
  await initDataTableNuevoPedido();
  await initDataTableNuevosPedidos();
});

$(document).ready(function () {
  /* Verificacion de saldo en contra */
  $.ajax({
    url: SERVERURL + "calculadora/saldo",
    type: "GET",
    dataType: "json",
    success: function (response) {
      var saldo = parseFloat(response).toFixed(2);

      var button2 = document.getElementById("generarGuiaBtn");

      if (
        saldo < -10 &&
        ID_PLATAFORMA != 1238 &&
        ID_PLATAFORMA != 1226 &&
        ID_PLATAFORMA != 1246
      ) {
        button2.disabled = true;
        Swal.fire({
          icon: "error",
          title: "No puede realizar guias",
          text: "No puede realizar guias porque tiene registrado un saldo negativo.",
          showConfirmButton: false,
          timer: 2000,
        }).then(() => {
          window.location.href = "" + SERVERURL + "dashboard";
        });
      }
    },
    error: function (error) {
      console.error("Error al obtener la lista de bodegas:", error);
    },
  });
  /* Fin verificacion de saldo en contra */

  // Inicializar Select2 en los selects
  $("#provincia").select2({
    placeholder: "Selecciona una opción",
    allowClear: true,
  });

  $("#ciudad").select2({
    placeholder: "Selecciona una opción",
    allowClear: true,
  });

  cargarProvincias(); // Llamar a cargarProvincias cuando la página esté lista

  let isInitialLoad = true;

  // Llamar a cargarCiudades cuando se seleccione una provincia
  $("#provincia").on("change", function () {
    if (!isInitialLoad) {
      cargarCiudades();
    }
  });

  // Consumir datos y poner en inputs para editar
  $.ajax({
    url: SERVERURL + "pedidos/verPedido/" + id_factura,
    type: "GET",
    dataType: "json",
    success: function (response) {
      celular_bodega = response[0].telefonoO;
      nombre_bodega = response[0].nombreO;
      ciudad_bodega = response[0].ciudadO;
      provincia_bodega = response[0].provinciaO;
      direccion_bodega = response[0].direccionO;
      referencia_bodega = response[0].referenciaO;
      numeroCasa_bodega = response[0].numeroCasaO;
      id_propietario_bodega = response[0].id_propietario;

      $("#nombre").val(response[0].nombre);
      $("#telefono").val(response[0].telefono);
      $("#calle_principal").val(response[0].c_principal);
      $("#calle_secundaria").val(response[0].c_secundaria);
      $("#referencia").val(response[0].referencia);
      $("#observacion").val(response[0].observacion);

      // Primero cargar provincias y luego asignar la provincia seleccionada
      $.ajax({
        url: SERVERURL + "Ubicaciones/obtenerProvincias",
        type: "GET",
        success: function (responseProvincias) {
          let provincias = JSON.parse(responseProvincias);
          let provinciaSelect = $("#provincia");
          provinciaSelect.empty();
          provinciaSelect.append('<option value="">Provincia *</option>'); // Añadir opción por defecto

          provincias.forEach(function (provincia) {
            provinciaSelect.append(
              `<option value="${provincia.codigo_provincia}">${provincia.provincia}</option>`
            );
          });

          // Asignar valor de la provincia y notificar a Select2
          provinciaSelect.val(response[0].provincia).trigger("change.select2");

          // Después de asignar la provincia, cargar ciudades y asignar la ciudad seleccionada
          $.ajax({
            url:
              SERVERURL +
              "Ubicaciones/obtenerCiudades/" +
              response[0].provincia,
            type: "GET",
            success: function (responseCiudades) {
              let ciudades = JSON.parse(responseCiudades);
              let ciudadSelect = $("#ciudad");
              ciudadSelect.empty();
              ciudadSelect.append('<option value="">Ciudad *</option>'); // Añadir opción por defecto

              ciudades.forEach(function (ciudad) {
                ciudadSelect.append(
                  `<option value="${ciudad.id_cotizacion}">${ciudad.ciudad}</option>`
                );
              });

              // Asignar valor de la ciudad y notificar a Select2
              ciudadSelect
                .val(response[0].ciudad_cot)
                .trigger("change.select2");

              // Asegurarse de que la ciudad se muestre correctamente
              setTimeout(() => {
                ciudadSelect
                  .val(response[0].ciudad_cot)
                  .trigger("change.select2");
              }, 100);

              // Llamar manualmente la función de cambio después de asignar los valores
              setTimeout(() => {
                $("#provincia, #ciudad").trigger("change");
                isInitialLoad = false; // Finalizar la carga inicial
              }, 200);
            },
            error: function (error) {
              console.error("Error al cargar las ciudades:", error);
            },
          });
        },
        error: function (error) {
          console.error("Error al cargar las provincias:", error);
        },
      });
    },
    error: function (error) {
      console.error("Error al obtener el pedido:", error);
    },
  });

  $(".transportadora").click(function () {
    var priceSpan = $(this).find(".price-tag span");
    var priceValue = priceSpan.text().trim();
    var selectedCompany = $(this).data("company");

    if (
      priceValue !== "--" &&
      priceValue !== "" &&
      priceValue !== "0" &&
      priceValue !== "Proximamente" &&
      priceValue !== "Mantenimiento"
    ) {
      var button2 = document.getElementById("generarGuiaBtn");
      button2.disabled = true;

      $("#costo_flete").val(priceValue);
      $("#transportadora_selected").val(selectedCompany);

      // Remove 'selected' class from all transportadora elements
      $(".transportadora").removeClass("selected");

      // Add 'selected' class to the clicked transportadora
      $(this).addClass("selected");

      const idProducto_calcular = $("#id_producto_temporal").val();

      var monto_total_general = $("#monto_total").text().trim();

      let formData = new FormData();
      formData.append("id_producto", idProducto_calcular);
      formData.append("total", monto_total_general);
      formData.append("tarifa", priceValue);
      formData.append("costo", costo_general);

      $.ajax({
        url: SERVERURL + "calculadora/calcularGuiaDirecta",
        type: "POST", // Cambiar a POST para enviar FormData
        data: formData,
        processData: false, // No procesar los datos
        contentType: false, // No establecer ningún tipo de contenido
        dataType: "json",
        success: function (response) {
          $("#montoVenta_infoVenta").text(response.total);
          $("#costo_infoVenta").text(response.costo);
          $("#precioEnvio_infoVenta").text(response.tarifa);
          $("#fulfillment_infoVenta").text(response.full);
          $("#total_infoVenta").text(response.resultante);

          if (response.resultante > 0) {
            if (response.generar == false) {
              button2.disabled = true;
              $("#alerta_valoresContra").show();
            } else {
              button2.disabled = false;
              $("#alerta_valoresContra").hide();
            }
          }
        },
        error: function (jqXHR, textStatus, errorThrown) {
          alert(errorThrown);
        },
      });
    } else {
      toastr.error("ESTA TRANSPORTADORA NO TIENE COBERTURA", "NOTIFICACIÓN", {
        positionClass: "toast-bottom-center",
      });
    }
  });

  $("#provincia,#ciudad,#recaudo").change(function () {
    calcularTarifas();
  });

  // cargar datos productos
  $.ajax({
    url: SERVERURL + "pedidos/datos_pedido/" + id_factura,
    type: "GET",
    dataType: "json",
    success: function (response) {
      /* console.log(response); */
    },
    error: function (error) {
      console.error("Error al obtener la lista de bodegas:", error);
    },
  });
});

function calcularTarifas() {
  var provincia = $("#provincia").val();
  var ciudad = $("#ciudad").val();
  var monto_total = $("#monto_total").text().trim();
  var recaudo = $("#recaudo").val();

  if (
    provincia !== "Selecciona una opción" &&
    ciudad !== "Selecciona una opción" &&
    monto_total !== "" &&
    monto_total !== "0"
  ) {
    let formData = new FormData();
    formData.append("ciudad", ciudad);
    formData.append("provincia", provincia);
    formData.append("recaudo", recaudo);
    formData.append("monto_factura", monto_total);
    formData.append("id_plataforma", ID_PLATAFORMA);

    $.ajax({
      url: SERVERURL + "Calculadora/obtenerTarifas",
      type: "POST",
      data: formData,
      processData: false,
      contentType: false,
      success: function (response) {
        response = JSON.parse(response);

        $("#price_servientrega").text(response.servientrega);
        $("#price_gintracom").text(response.gintracom);

        $("#price_speed").text(response.speed);

        $("#price_laar").text(response.laar);

        /* calculador servi */
        let formData_ServiTarifa = new FormData();
        formData_ServiTarifa.append("ciudadO", ciudad_bodega);
        formData_ServiTarifa.append("monto_factura", monto_total);
        formData_ServiTarifa.append("ciudadD", ciudad);
        formData_ServiTarifa.append("provinciaD", provincia);

        $.ajax({
          url: SERVERURL + "calculadora/calcularServi",
          type: "POST",
          data: formData_ServiTarifa,
          processData: false, // No procesar los datos
          contentType: false, // No establecer ningún tipo de contenido
          success: function (response_serviTarifa) {
            response_serviTarifa = JSON.parse(response_serviTarifa);
            $("#flete").val(response_serviTarifa.flete);
            $("#seguro").val(response_serviTarifa.seguro);
            $("#comision").val(response_serviTarifa.comision);
            $("#otros").val(response_serviTarifa.otros);
            $("#impuestos").val(response_serviTarifa.impuestos);
          },
          error: function (jqXHR, textStatus, errorThrown) {
            alert(errorThrown);
          },
        });
        /* fin calculador servi */
      },
      error: function (jqXHR, textStatus, errorThrown) {
        alert(errorThrown);
      },
    });
  }
}

// Función para cargar provincias
function cargarProvincias() {
  $.ajax({
    url: SERVERURL + "Ubicaciones/obtenerProvincias", // Reemplaza con la ruta correcta a tu controlador
    method: "GET",
    success: function (response) {
      let provincias = JSON.parse(response);
      let provinciaSelect = $("#provincia");
      provinciaSelect.empty();
      provinciaSelect.append('<option value="">Provincia *</option>'); // Añadir opción por defecto

      provincias.forEach(function (provincia) {
        provinciaSelect.append(
          `<option value="${provincia.codigo_provincia}">${provincia.provincia}</option>`
        );
      });

      // Refrescar Select2 para que muestre las nuevas opciones
      provinciaSelect.trigger("change.select2");
    },
    error: function (error) {
      console.log("Error al cargar provincias:", error);
    },
  });
}
// Función para cargar ciudades según la provincia seleccionada
function cargarCiudades() {
  let provinciaId = $("#provincia").val();
  if (provinciaId) {
    $.ajax({
      url: SERVERURL + "Ubicaciones/obtenerCiudades/" + provinciaId, // Reemplaza con la ruta correcta a tu controlador
      method: "GET",
      success: function (response) {
        let ciudades = JSON.parse(response);
        console.log("Ciudades recibidas:", ciudades); // Verificar los datos en la consola del navegador
        let ciudadSelect = $("#ciudad");
        ciudadSelect.empty();
        ciudadSelect.append('<option value="">Ciudad *</option>'); // Añadir opción por defecto

        ciudades.forEach(function (ciudad) {
          ciudadSelect.append(
            `<option value="${ciudad.id_cotizacion}">${ciudad.ciudad}</option>`
          );
        });

        // Refrescar Select2 para que muestre las nuevas opciones
        ciudadSelect.trigger("change.select2");

        ciudadSelect.prop("disabled", false); // Habilitar el select de ciudades
      },
      error: function (error) {
        console.log("Error al cargar ciudades:", error);
      },
    });
  } else {
    $("#ciudad")
      .empty()
      .append('<option value="">Ciudad *</option>')
      .prop("disabled", true)
      .trigger("change.select2");
  }
}

//Generar guia

function generar_guia() {
  var button = document.getElementById("generarGuiaBtn");
  button.disabled = true; // Desactivar el botón

  //   alert()
  // Evita que el formulario se envíe de la forma tradicional
  event.preventDefault();
  let transportadora_selected = $("#transportadora_selected").val();
  if (transportadora_selected == "servientrega") {
    transportadora_selected = 2;
  }
  if (transportadora_selected == "laar") {
    transportadora_selected = 1;
  }
  if (transportadora_selected == "speed") {
    transportadora_selected = 4;
  }
  if (transportadora_selected == "gintracom") {
    transportadora_selected = 3;
  }

  // Crea un objeto FormData
  var formData = new FormData();
  var montoTotal = document.getElementById("monto_total").innerText;
  formData.append("total_venta", montoTotal);
  formData.append("nombre", $("#nombre").val());
  formData.append("recaudo", $("#recaudo").val());
  formData.append("telefono", $("#telefono").val());
  formData.append("calle_principal", $("#calle_principal").val());
  formData.append("calle_secundaria", $("#calle_secundaria").val());
  formData.append("referencia", $("#referencia").val());
  formData.append("ciudad", $("#ciudad").val());
  formData.append("provincia", $("#provincia").val());
  formData.append("identificacion", 0);
  formData.append("observacion", $("#observacion").val());
  formData.append("transporte", 0);
  formData.append("celular", $("#telefono").val()); // Asegúrate de obtener el valor correcto
  formData.append("id_producto_venta", id_producto_venta);
  formData.append("dropshipping", dropshipping);
  formData.append("importado", 0);
  formData.append("id_propietario", id_propietario_bodega);
  formData.append("identificacionO", 0);
  formData.append("celularO", celular_bodega);
  formData.append("nombreO", nombre_bodega); // Corregir nombre de variable
  formData.append("ciudadO", ciudad_bodega);
  formData.append("provinciaO", provincia_bodega);
  formData.append("direccionO", direccion_bodega);
  formData.append("referenciaO", referencia_bodega); // Corregir nombre de variable
  formData.append("numeroCasaO", numeroCasa_bodega);
  formData.append("valor_seguro", 0); // Corregir nombre de variable
  formData.append("no_piezas", 1);
  if (transportadora_selected == 3) {
    formData.append("contiene", contieneGintracom);
  } else {
    formData.append("contiene", contiene);
  }
  formData.append("costo_flete", $("#costo_flete").val());
  formData.append("costo_producto", costo_producto);
  formData.append("comentario", "Enviado por x");
  formData.append("id_transporte", transportadora_selected);

  // Realiza la solicitud AJAX
  if (transportadora_selected == 1) {
    generar_guiaTransportadora = "generarLaar";
  } else if (transportadora_selected == 2) {
    generar_guiaTransportadora = "generarServientrega";
  } else if (transportadora_selected == 3) {
    generar_guiaTransportadora = "generarGintracom";
  } else if (transportadora_selected == 4) {
    generar_guiaTransportadora = "generarSpeed";
  }

  // Mostrar alerta de carga antes de realizar la solicitud AJAX
  Swal.fire({
    title: "Cargando",
    text: "Creando Guia",
    allowOutsideClick: false,
    showConfirmButton: false,
    timer: 2000,
    willOpen: () => {
      Swal.showLoading();
    },
  });

  formData.append("numero_factura", numero_factura);

  if (transportadora_selected == 2) {
    formData.append("flete", $("#flete").val());
    formData.append("seguro", $("#seguro").val());
    formData.append("comision", $("#comision").val());
    formData.append("otros", $("#otros").val());
    formData.append("impuestos", $("#impuestos").val());
  }

  $.ajax({
    url: "" + SERVERURL + "/guias/" + generar_guiaTransportadora,
    type: "POST",
    data: formData,
    processData: false,
    contentType: false,
    success: function (response) {
      response = JSON.parse(response);

      if (response.status == 500) {
        Swal.fire({
          icon: "error",
          title:
            "Error al crear la guia, no se encuentra la ciudad o provincia de destino",
        });
        var button2 = document.getElementById("generarGuiaBtn");
        button2.disabled = false; // Desactivar el botón
      } else if (response.status == 200) {
        Swal.fire({
          icon: "success",
          title: "Creacion de guia Completada",
          showConfirmButton: false,
          timer: 2000,
        }).then(() => {
          vaciarTmpPedidos();
          window.location.href = "" + SERVERURL + "Pedidos/guias";
        });
      } else if (response.status == 501) {
        Swal.fire({
          icon: "warning",
          title: response.message,
        });
        var button2 = document.getElementById("generarGuiaBtn");
        button2.disabled = false; // Desactivar el botón
      }
    },
    error: function (error) {
      alert("Hubo un error al agregar el producto");
      console.log(error);
    },
  });
}
// Función para vaciar temporalmente los pedidos
const vaciarTmpPedidos = async () => {
  try {
    const response = await fetch("" + SERVERURL + "marketplace/vaciarTmp");
    if (!response.ok) {
      throw new Error("Error al vaciar los pedidos temporales");
    }
    const data = await response.json();
    console.log("Respuesta de vaciarTmp:", data);
  } catch (error) {
    console.error("Error al hacer la solicitud:", error);
  }
};

function validar_devoluciones(telefono) {
  $.ajax({
    url: SERVERURL + "Pedidos/validaDevolucion/" + telefono,
    type: "GET",
    dataType: "json",
    success: function (response) {
      if (response.length > 0) {
        $("#alerta_devoluciones").show();
      } else {
        $("#alerta_devoluciones").hide();
      }
    },
    error: function (error) {
      console.error("Error con la api de Validar devolucion:", error);
    },
  });
}

// Desactivar el botón al iniciar la página
document.addEventListener("DOMContentLoaded", function () {
  var button2 = document.getElementById("generarGuiaBtn");
  button2.disabled = true;
});
