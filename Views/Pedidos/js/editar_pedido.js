let dataTableNuevoPedido;
let dataTableNuevoPedidoIsInitialized = false;
let eliminado = false;

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
    const response = await fetch("" + SERVERURL + "pedidos/buscarTmp");

    const data = await response.json();
    console.log(data);

    if (data.tmp[0].id_producto == 0 && eliminado == false) {
      // If the response is empty, return
      return;
    }
    const nuevosPedidos = data.tmp; // Extract the 'tmp' array from the response
    const nuevosPedidos_bodega = data.bodega;
    console.log(nuevosPedidos_bodega);
    let content = ``;
    let total = 0;
    let precio_costo = 0;
    nuevosPedidos.forEach((nuevoPedido, index) => {
      if (nuevosPedidos_bodega.length > 0 && nuevosPedidos_bodega[0]) {
        celular_bodega = nuevosPedidos_bodega[0].contacto;
        nombre_bodega = nuevosPedidos_bodega[0].nombre;
        ciudad_bodega = nuevosPedidos_bodega[0].localidad;
        provincia_bodega = nuevosPedidos_bodega[0].provincia;
        direccion_bodega = nuevosPedidos_bodega[0].direccion;
        referencia_bodega = nuevosPedidos_bodega[0].referencia;
        numeroCasa_bodega = nuevosPedidos_bodega[0].num_casa;
        id_propietario_bodega = nuevosPedidos_bodega[0].id;
      }
      id_producto_venta = nuevoPedido.id_producto;
      dropshipping = nuevoPedido.drogshipin;
      costo_producto = nuevoPedido.costo_producto;

      contiene += `${nuevoPedido.nombre_producto} X${nuevoPedido.cantidad_tmp} `;

      precio_costo = parseFloat(nuevoPedido.precio_tmp);

      // Verificar condición
      if (!validar_direccion()) {
        return; // Salir de la función si la validación falla
      }

      const precio = parseFloat(nuevoPedido.precio_tmp);
      const descuento = parseFloat(nuevoPedido.desc_tmp);
      const precioFinal = precio - precio * (descuento / 100);
      total += precioFinal;
      content += `
                <tr>
                    <td>${nuevoPedido.id_tmp}</td>
                    <td>${nuevoPedido.cantidad_tmp}</td>
                    <td>${nuevoPedido.nombre_producto}</td>
                    <td><input type="text" onblur='recalcular("${
                      nuevoPedido.id_tmp
                    }", "precio_nuevoPedido_${index}", "descuento_nuevoPedido_${index}")' id="precio_nuevoPedido_${index}" class="form-control prec" value="${precio}"></td>
                    <td><input type="text" onblur='recalcular("${
                      nuevoPedido.id_tmp
                    }", "precio_nuevoPedido_${index}", "descuento_nuevoPedido_${index}")' id="descuento_nuevoPedido_${index}" class="form-control desc" value="${descuento}"></td>
                    <td><span class='tota' id="precioFinal_nuevoPedido_${index}">${precioFinal.toFixed(
        2
      )}</span></td>
                    <td>
                        <button class="btn btn-sm btn-danger" onclick="eliminar_nuevoPedido(${
                          nuevoPedido.id_tmp
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

  $("#provincia,#ciudad").change(function () {
    var provincia = $("provincia").val();
    var ciudad = $("ciudad").val();
    var monto_total = $("monto_total").val();

    if (
      provincia !== "Selecciona una opción" &&
      ciudad !== "Selecciona una opción" &&
      monto_total != 0
    ) {
      let formData = new FormData();
      formData.append("ciudad", ciudad);
      formData.append("provincia", provincia);
      formData.append("monto_factura", monto_total);

      $.ajax({
        url: SERVERURL + "Calculadora/obtenerTarifas",
        type: "POST",
        data: formData,
        success: function (response) {
            console.log(response);
        },
        error: function (jqXHR, textStatus, errorThrown) {
          alert(errorThrown);
        },
      });
    }
  });
});

// Función para cargar provincias
function cargarProvincias() {
  $.ajax({
    url: "" + SERVERURL + "Ubicaciones/obtenerProvincias", // Reemplaza con la ruta correcta a tu controlador
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
  formData.append("costo_flete", 0);
  formData.append("costo_producto", costo_producto);
  formData.append("comentario", "Enviado por x");
  formData.append("id_transporte", 0);

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
