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
    dataTableNuevoPedido.destroy();  // Destruir la DataTable existente
  }

  await listNuevoPedido();  // Esperar a que los datos sean listados

  dataTableNuevoPedido = $("#datatable_nuevoPedido").DataTable(dataTableNuevoPedidoOptions);  // Inicializar la DataTable

  dataTableNuevoPedidoIsInitialized = true;  // Marcar que la DataTable ha sido inicializada
};

const listNuevoPedido = async () => {
  try {
    const response = await fetch(SERVERURL + "pedidos/buscarTmp");
    const data = await response.json();
    console.log(data);

    if (data.tmp.length === 0) {
      // Si no hay datos, resetear la tabla
      document.getElementById("monto_total").innerHTML = "0.00";
      document.getElementById("tableBody_nuevoPedido").innerHTML = '<tr><td colspan="7">No data available in table</td></tr>';
      return;
    }

    const nuevosPedidos = data.tmp;
    const nuevosPedidos_bodega = data.bodega;
    console.log(nuevosPedidos_bodega);

    let content = ``;
    let total = 0;

    nuevosPedidos.forEach((nuevoPedido, index) => {
      const precio = parseFloat(nuevoPedido.precio_tmp);
      const descuento = parseFloat(nuevoPedido.desc_tmp);
      const precioFinal = precio - precio * (descuento / 100);
      total += precioFinal;

      content += `
        <tr>
            <td>${nuevoPedido.id_tmp}</td>
            <td>${nuevoPedido.cantidad_tmp}</td>
            <td>${nuevoPedido.nombre_producto}</td>
            <td><input type="text" onblur='recalcular("${nuevoPedido.id_tmp}", "precio_nuevoPedido_${index}", "descuento_nuevoPedido_${index}")' id="precio_nuevoPedido_${index}" class="form-control prec" value="${precio}"></td>
            <td><input type="text" onblur='recalcular("${nuevoPedido.id_tmp}", "precio_nuevoPedido_${index}", "descuento_nuevoPedido_${index}")' id="descuento_nuevoPedido_${index}" class="form-control desc" value="${descuento}"></td>
            <td><span class='tota' id="precioFinal_nuevoPedido_${index}">${precioFinal.toFixed(2)}</span></td>
            <td>
                <button class="btn btn-sm btn-danger" onclick="eliminar_nuevoPedido(${nuevoPedido.id_tmp})"><i class="fa-solid fa-trash-can"></i></button>
            </td>
        </tr>`;
    });

    document.getElementById("monto_total").innerHTML = total.toFixed(2);
    document.getElementById("tableBody_nuevoPedido").innerHTML = content;

    if (eliminado) {
      eliminado = false;
      document.getElementById("monto_total").innerHTML = "0.00";
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

  fetch(SERVERURL + "pedidos/actualizarTmp/" + id, {
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
        toastr.error("EL PRODUCTO NO SE ACTUALIZADO CORRECTAMENTE", "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });
      }
      await initDataTableNuevoPedido();
    })
    .catch((error) => {
      console.error("Error:", error);
      alert("Hubo un problema al actualizar el producto");
    });
}

function validar_direccion() {
  const urlParams = new URLSearchParams(window.location.search);
  const idProducto = urlParams.get("id_producto");
  const sku = urlParams.get("sku");

  if (idProducto && sku) {
    if (
      ciudad_bodega == null ||
      provincia_bodega == null ||
      direccion_bodega == null
    ) {
      const guardarPedidoBtn = document.getElementById("guardarPedidoBtn");
      const generarGuiaBtn = document.getElementById("generarGuiaBtn");

      guardarPedidoBtn.disabled = true;
      generarGuiaBtn.disabled = true;

      toastr.error(
        "Esta bodega no contiene datos de dirección y no puede generar guias",
        "NOTIFICACIÓN",
        {
          positionClass: "toast-bottom-center",
        }
      );

      return false;
    }
  }
  return true;
}

function eliminar_nuevoPedido(id) {
  eliminado = true;
  $.ajax({
    type: "POST",
    url: SERVERURL + "pedidos/eliminarTmp/" + id,
    success: function (response) {
      if (response.status == 500) {
        toastr.error("EL PRODUCTO NO SE ELIMINADO CORRECTAMENTE", "NOTIFICACIÓN", { positionClass: "toast-bottom-center" });
      } else if (response.status == 200) {
        toastr.success("PRODUCTO ELIMINADO CORRECTAMENTE", "NOTIFICACIÓN", { positionClass: "toast-bottom-center" });
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

$(document).ready(function () {
  $('#provincia').select2({
    placeholder: 'Selecciona una opción',
    allowClear: true
  });
  
  $('#ciudad').select2({
    placeholder: 'Selecciona una opción',
    allowClear: true
  });

  cargarProvincias(); 
  
  $("#provincia").on("change", cargarCiudades);
});

function cargarProvincias() {
  $.ajax({
    url: SERVERURL + "Ubicaciones/obtenerProvincias",
    method: "GET",
    success: function (response) {
      let provincias = JSON.parse(response);
      let provinciaSelect = $("#provincia");
      provinciaSelect.empty();
      provinciaSelect.append('<option value="">Provincia *</option>');

      provincias.forEach(function (provincia) {
        provinciaSelect.append(
          `<option value="${provincia.codigo_provincia}">${provincia.provincia}</option>`
        );
      });

      provinciaSelect.trigger('change.select2');
    },
    error: function (error) {
      console.log("Error al cargar provincias:", error);
    },
  });
}

function cargarCiudades() {
  let provinciaId = $("#provincia").val();
  if (provinciaId) {
    $.ajax({
      url: SERVERURL + "Ubicaciones/obtenerCiudades/" + provinciaId,
      method: "GET",
      success: function (response) {
        let ciudades = JSON.parse(response);
        console.log("Ciudades recibidas:", ciudades);
        let ciudadSelect = $("#ciudad");
        ciudadSelect.empty();
        ciudadSelect.append('<option value="">Ciudad *</option>');

        ciudades.forEach(function (ciudad) {
          ciudadSelect.append(
            `<option value="${ciudad.id_cotizacion}">${ciudad.ciudad}</option>`
          );
        });

        ciudadSelect.trigger('change.select2');

        ciudadSelect.prop("disabled", false);
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
      .trigger('change.select2');
  }
}

function agregar_nuevoPedido(event) {
  event.preventDefault();

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
  formData.append("celular", $("#telefono").val());
  formData.append("id_producto_venta", id_producto_venta);
  formData.append("dropshipping", dropshipping);
  formData.append("importado", 0);
  formData.append("id_propietario", id_propietario_bodega);
  formData.append("identificacionO", 0);
  formData.append("celularO", celular_bodega);
  formData.append("nombreO", nombre_bodega);
  formData.append("ciudadO", ciudad_bodega);
  formData.append("provinciaO", provincia_bodega);
  formData.append("direccionO", direccion_bodega);
  formData.append("referenciaO", referencia_bodega);
  formData.append("numeroCasaO", numeroCasa_bodega);
  formData.append("valor_seguro", 0);
  formData.append("no_piezas", 1);
  formData.append("contiene", contiene);
  formData.append("costo_flete", 0);
  formData.append("costo_producto", costo_producto);
  formData.append("comentario", "Enviado por x");
  formData.append("id_transporte", 0);

  $.ajax({
    url: SERVERURL + "/pedidos/nuevo_pedido",
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
          window.location.href = SERVERURL + "Pedidos";
        });
      }
    },
    error: function (error) {
      alert("Hubo un error al agregar el producto");
      console.log(error);
    },
  });
}

const vaciarTmpPedidos = async () => {
  try {
    const response = await fetch(SERVERURL + "marketplace/vaciarTmp");
    if (!response.ok) {
      throw new Error("Error al vaciar los pedidos temporales");
    }
    const data = await response.json();
    console.log("Respuesta de vaciarTmp:", data);
  } catch (error) {
    console.error("Error al hacer la solicitud:", error);
  }
};
