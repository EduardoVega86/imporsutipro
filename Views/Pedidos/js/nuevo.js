let dataTableNuevoPedido;
let dataTableNuevoPedidoIsInitialized = false;

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
var nombre = "";
var ciudad_bodega = "";
var provincia_bodega = "";
var direccion_bodega = "";
var referencia_bodega = "";
var numerCasa_Bodega = "";

const listNuevoPedido = async () => {
  try {
    const response = await fetch("" + SERVERURL + "pedidos/buscarTmp");
    const data = await response.json();
    const nuevosPedidos = data.tmp; // Extract the 'tmp' array from the response
    const nuevosPedidos_bodega = data.bodega;

    let content = ``;
    let total = 0;
    nuevosPedidos.forEach((nuevoPedido, index) => {
      celular_bodega = nuevosPedidos_bodega.contacto;
      nombre = nuevosPedidos_bodega.nombre;
      ciudad_bodega = nuevosPedidos_bodega.localidad;
      provincia_bodega = nuevosPedidos_bodega.provincia;
      direccion_bodega = nuevosPedidos_bodega.direccion;
      referencia_bodega = nuevosPedidos_bodega.referencia;
      numerCasa_Bodega = nuevosPedidos_bodega.num_casa;

      const precio = parseFloat(nuevoPedido.precio_tmp);
      const descuento = parseFloat(nuevoPedido.desc_tmp);
      const precioFinal = precio - precio * (descuento / 100);
        total += precioFinal;
      content += `
                <tr>
                    <td>${nuevoPedido.id_tmp}</td>
                    <td>${nuevoPedido.cantidad_tmp}</td>
                    <td>${nuevoPedido.nombre_producto}</td>
                    <td><input type="text" oncblur='recalcular()' id="precio_nuevoPedido_${index}" class="form-control prec" value="${precio}"></td>
                    <td><input type="text" oncblur='recalcular()' id="descuento_nuevoPedido_${index}" class="form-control desc" value="${descuento}"></td>
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
  } catch (ex) {
    alert(ex);
  }
};

function eliminar_nuevoPedido(id) {
  $.ajax({
    type: "POST",
    url: SERVERURL + "pedidos/eliminarTmp/" + id,
    dataType: "json", // Asegurarse de que la respuesta se trata como JSON
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
  cargarProvincias(); // Llamar a cargarProvincias cuando la página esté lista

  // Llamar a cargarCiudades cuando se seleccione una provincia
  $("#provincia").on("change", cargarCiudades);
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
      .prop("disabled", true);
  }
}

//agregar funcion pedido

function agregar_nuevoPedido(){
    $('#agregar_producto_form').submit(function(event) {
        event.preventDefault(); // Evita que el formulario se envíe de la forma tradicional

        // Crea un objeto FormData
        var formData = new FormData();
        formData.append('codigo_producto', $('#codigo').val());
        formData.append('nombre_producto', $('#nombre').val());
        formData.append('descripcion_producto', $('#descripcion').val());
        formData.append('id_linea_producto', $('#categoria').val());
        formData.append('inv_producto', $('#maneja-inventario').val());
        formData.append('producto_variable', $('#producto-variable').val());
        formData.append('costo_producto', $('#costo').val());
        formData.append('aplica_iva', 1); // Suponiendo que siempre aplica IVA
        formData.append('estado_producto', 1); // Suponiendo que el estado es activo
        formData.append('date_added', new Date().toISOString().split('T')[0]);
        formData.append('image_path', ''); // Asumiendo que no hay imagen por ahora
        formData.append('formato', $('#formato-pagina').val());
        formData.append('drogshipin', 0); // Suponiendo que no es dropshipping
        formData.append('destacado', 0); // Suponiendo que no es destacado
        formData.append('stock_inicial', $('#stock-inicial').val());
        formData.append('bodega', $('#bodega').val());
        formData.append('pcp', $('#precio-proveedor').val());
        formData.append('pvp', $('#precio-venta').val());
        formData.append('pref', $('#precio-referencial-valor').val());

        // Realiza la solicitud AJAX
        $.ajax({
            url: '' + SERVERURL + 'productos/agregar_producto',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                alert('Producto agregado exitosamente');
                console.log(response);
            },
            error: function(error) {
                alert('Hubo un error al agregar el producto');
                console.log(error);
            }
        });
    });
}