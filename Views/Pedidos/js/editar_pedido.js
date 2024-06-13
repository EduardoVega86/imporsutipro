let dataTableNuevoPedido;
let dataTableNuevoPedidoIsInitialized = false;
let eliminado = false;
// Obtener el valor del id_factura desde la URL
const url = window.location.href;
const id_factura = url.split("/").pop();

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
var costo_producto = 0;

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
    nuevosPedidos.forEach((nuevoPedido, index) => {
      id_producto_venta = nuevoPedido.id_producto;
      dropshipping = nuevoPedido.drogshipin;
      costo_producto = nuevoPedido.costo_producto;

      contiene += `${nuevoPedido.nombre_producto} X${nuevoPedido.cantidad} `;

      precio_costo = parseFloat(nuevoPedido.pvp); // no estoy seguro si es precio_venta o pvp, preguntar despues

      // Verificar condición
      if (!validar_direccion()) {
        return; // Salir de la función si la validación falla
      }

      const precio = parseFloat(nuevoPedido.pvp); // no estoy seguro si es precio_venta o pvp, preguntar despues
      const descuento = parseFloat(nuevoPedido.desc_venta);
      const precioFinal = precio - precio * (descuento / 100);
      total += precioFinal;
      content += `
                <tr>
                <input type="hidden" id="id_productoBuscar_${index}" name="id_productoBuscar_${index}" value= "${nuevoPedido.id_producto}">
                <input type="hidden" id="sku_productoBuscar_${index}" name="sku_productoBuscar_${index}" value= "${nuevoPedido.sku}"></input>
                    <td>${nuevoPedido.id_producto}</td>
                    <td>${nuevoPedido.cantidad}</td>
                    <td>${nuevoPedido.nombre_producto}</td>
                    <td><input type="text" onblur='recalcular("${
                      nuevoPedido.id_producto
                    }", "precio_nuevoPedido_${index}", "descuento_nuevoPedido_${index}")' id="precio_nuevoPedido_${index}" class="form-control prec" value="${precio}"></td>
                    <td><input type="text" onblur='recalcular("${
                      nuevoPedido.id_producto
                    }", "precio_nuevoPedido_${index}", "descuento_nuevoPedido_${index}")' id="descuento_nuevoPedido_${index}" class="form-control desc" value="${descuento}"></td>
                    <td><span class='tota' id="precioFinal_nuevoPedido_${index}">${precioFinal.toFixed(
        2
      )}</span></td>
                    <td>
                        <button class="btn btn-sm btn-danger" onclick="eliminar_nuevoPedido(${
                          nuevoPedido.id_producto
                        })"><i class="fa-solid fa-trash-can"></i></button>
                    </td>
                </tr>`;
    });
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

function recalcular(id, idPrecio, idDescuento) {
  costo_producto = 0;
  const precio = parseFloat(document.getElementById(idPrecio).value);
  const descuento = parseFloat(document.getElementById(idDescuento).value);

  const ffrm = new FormData();
  ffrm.append("id", id);
  ffrm.append("precio", precio);
  ffrm.append("descuento", descuento);

  fetch("" + SERVERURL + "pedidos/actualizarTmp/" + id, {
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
      await initDataTableNuevoPedido();
    })
    .catch((error) => {
      console.error("Error:", error);
      alert("Hubo un problema al actualizar el producto");
    });
}

function validar_direccion() {
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
}

function eliminar_nuevoPedido(id) {
  eliminado = true;
  $.ajax({
    type: "POST",
    url: SERVERURL + "pedidos/eliminarTmp/" + id,
    success: function (response) {
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
      initDataTableNuevoPedido();
    },
    error: function (xhr, status, error) {
      console.error("Error en la solicitud AJAX:", error);
      alert("Hubo un problema al eliminar la categoría");
    },
  });
}

window.addEventListener("load", async () => {
  await initDataTableNuevoPedido();
  await initDataTableNuevosPedidos();
});

//cargar selelct ciudades y provincias
$(document).ready(function () {
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

  // Llamar a cargarCiudades cuando se seleccione una provincia
  $("#provincia").on("change", cargarCiudades);

  $(".transportadora").click(function () {
    var priceSpan = $(this).find(".price-tag span");
    var priceValue = priceSpan.text().trim();
    var selectedCompany = $(this).data("company");

    if (priceValue !== "--" && priceValue !== "") {
      $("#costo_flete").val(priceValue);
      $("#transportadora_selected").val(selectedCompany);

      // Remove 'selected' class from all transportadora elements
      $(".transportadora").removeClass("selected");

      // Add 'selected' class to the clicked transportadora
      $(this).addClass("selected");
    } else {
      toastr.error("ESTA TRANSPORTADORA NO TIENE COBERTURA", "NOTIFICACIÓN", {
        positionClass: "toast-bottom-center",
      });
    }
  });

  $("#provincia,#ciudad").change(function () {
    var provincia = $("#provincia").val();
    var ciudad = $("#ciudad").val();
    var recaudo = $("#recaudo").val();
    var monto_total = $("#monto_total").text().trim();

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

      $.ajax({
        url: SERVERURL + "Calculadora/obtenerTarifas",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
          response = JSON.parse(response);
          console.log("correcto el precio" + response.servientrega);
          $("#price_servientrega").text(response.servientrega);
          $("#price_gintracom").text(response.gintracom);
          $("#price_laar").text(response.laar);
        },
        error: function (jqXHR, textStatus, errorThrown) {
          alert(errorThrown);
        },
      });
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

  // cargar datos productos
  $.ajax({
    url: SERVERURL + "pedidos/datos_pedido/" + id_factura,
    type: "GET",
    dataType: "json",
    success: function (response) {
      console.log(response);
    },
    error: function (error) {
      console.error("Error al obtener la lista de bodegas:", error);
    },
  });
});

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
      .trigger("change.select2"); // Refrescar Select2 para mostrar el estado deshabilitado
  }
}

//agregar funcion pedido
function agregar_nuevoPedido() {
  // Evita que el formulario se envíe de la forma tradicional
  event.preventDefault();
  let transportadora_selected = $("#transportadora_selected").val();
  if (transportadora_selected == "servientrega") {
    transportadora_selected = 3;
  }
  if (transportadora_selected == "laar") {
    transportadora_selected = 1;
  }
  if (transportadora_selected == "speed") {
    transportadora_selected = 2;
  }
  if (transportadora_selected == "gintracom") {
    transportadora_selected = 4;
  }

  // Crea un objeto FormData
  var formData = new FormData();
  var montoTotal = document.getElementById("monto_total").innerText;
  formData.append("total_venta", montoTotal);
  formData.append("nombre", $("#nombre").val());
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
  formData.append("contiene", contiene);
  formData.append("costo_flete", $("#costo_flete").val());
  formData.append("costo_producto", costo_producto);
  formData.append("comentario", "Enviado por x");
  formData.append("id_transporte", transportadora_selected);

  // Realiza la solicitud AJAX
  $.ajax({
    url: "" + SERVERURL + "/pedidos/nuevo_pedido",
    type: "POST",
    data: formData,
    processData: false,
    contentType: false,
    success: function (response) {
      response = JSON.parse(response);
      if (response.status == 500) {
        Swal.fire({
          icon: "error",
          title: response.title,
          text: response.message,
        });
      } else if (response.status == 200) {
        Swal.fire({
          icon: "success",
          title: response.title,
          text: response.message,
          showConfirmButton: false,
          timer: 2000,
        }).then(() => {
          vaciarTmpPedidos();
          window.location.href = "" + SERVERURL + "Pedidos";
        });
      }
    },
    error: function (error) {
      alert("Hubo un error al agregar el producto");
      console.log(error);
    },
  });
}

function generar_guia() {
  //   alert()
  // Evita que el formulario se envíe de la forma tradicional
  event.preventDefault();
  let transportadora_selected = $("#transportadora_selected").val();
  if (transportadora_selected == "servientrega") {
    transportadora_selected = 3;
  }
  if (transportadora_selected == "laar") {
    transportadora_selected = 1;
  }
  if (transportadora_selected == "speed") {
    transportadora_selected = 2;
  }
  if (transportadora_selected == "gintracom") {
    transportadora_selected = 4;
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
  formData.append("contiene", contiene);
  formData.append("costo_flete", $("#costo_flete").val());
  formData.append("costo_producto", costo_producto);
  formData.append("comentario", "Enviado por x");
  formData.append("id_transporte", transportadora_selected);

  // Realiza la solicitud AJAX
  if (transportadora_selected == 1) {
    generar_guia = "generarlaar";
  } else {
  }

  // Mostrar alerta de carga antes de realizar la solicitud AJAX
  Swal.fire({
    title: "Cargando",
    text: "Creando nuevo pedido",
    allowOutsideClick: false,
    showConfirmButton: false,
    timer: 2000,
    willOpen: () => {
      Swal.showLoading();
    },
  });

  $.ajax({
    url: "" + SERVERURL + "/pedidos/nuevo_pedido",
    type: "POST",
    data: formData,
    processData: false,
    contentType: false,
    success: function (response) {
      response = JSON.parse(response);

      // Mostrar alerta de carga antes de realizar la solicitud AJAX
      Swal.fire({
        title: "Cargando",
        text: "Generando Guia del pedido",
        allowOutsideClick: false,
        showConfirmButton: false,
        timer: 2000,
        willOpen: () => {
          Swal.showLoading();
        },
      });

      if (response.status == 500) {
        Swal.fire({
          icon: "error",
          title: response.title,
          text: response.message,
        });
      } else if (response.status == 200) {
        formData.append("numero_factura", response.numero_factura);
        $.ajax({
          url: "" + SERVERURL + "/guias/" + generar_guia,
          type: "POST",
          data: formData,
          processData: false,
          contentType: false,
          success: function (response) {
            response = JSON.parse(response);

            if (response.status == 500) {
              Swal.fire({
                icon: "error",
                title: "Error al creat guia",
              });
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
            }
          },
          error: function (error) {
            alert("Hubo un error al agregar el producto");
            console.log(error);
          },
        });
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

//Generar guia
