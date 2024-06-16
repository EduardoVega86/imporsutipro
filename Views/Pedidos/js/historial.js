let dataTableHistorial;
let dataTableHistorialIsInitialized = false;

const dataTableHistorialOptions = {
  //scrollX: "2000px",
  /* lengthMenu: [5, 10, 15, 20, 100, 200, 500], */
  columnDefs: [
    { className: "centered", targets: [0, 1, 2, 3, 4, 5, 6, 7, 8] },
    /* { orderable: false, targets: [5, 6] }, */
    /* { searchable: false, targets: [1] } */
    //{ width: "50%", targets: [0] }
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

const initDataTableHistorial = async () => {
  if (dataTableHistorialIsInitialized) {
    dataTableHistorial.destroy();
  }

  await listHistorialPedidos();

  dataTableHistorial = $("#datatable_historialPedidos").DataTable(
    dataTableHistorialOptions
  );

  dataTableHistorialIsInitialized = true;
};

const listHistorialPedidos = async () => {
  try {
    const response = await fetch("" + SERVERURL + "pedidos/cargarPedidos");
    const historialPedidos = await response.json();

    let content = ``;
    let impresiones = "";
    historialPedidos.forEach((historialPedido, index) => {
      let transporte = historialPedido.id_transporte;
      console.log(transporte);
      let transporte_content = "";
      if (transporte == 3) {
        transporte_content =
          '<span text-nowrap style="background-color: #28C839; color: white; padding: 5px; border-radius: 0.3rem;">SERVIENTREGA</span>';
      } else if (transporte == 1) {
        transporte_content =
          '<span text-nowrap style="background-color: #E3BC1C; color: white; padding: 5px; border-radius: 0.3rem;">LAAR</span>';
      } else if (transporte == 2) {
        transporte_content =
          '<span text-nowrap style="background-color: red; color: white; padding: 5px; border-radius: 0.3rem;">SPEED</span>';
      } else if (transporte == 4) {
        transporte_content =
          '<span text-nowrap style="background-color: red; color: white; padding: 5px; border-radius: 0.3rem;">GINTRACOM</span>';
      } else if (transporte == 0) {
        transporte_content =
          '<span text-nowrap style="background-color: #E3BC1C; color: white; padding: 5px; border-radius: 0.3rem;">Guia no enviada</span>';
      }

      //impresiones
      if (historialPedido.impreso == 0) {
        impresiones = `<box-icon name='printer' color= "red"></box-icon>`;
      } else {
        impresiones = `<box-icon name='printer' color= "green"></box-icon>`;
      }

      //tomar solo la ciudad
      let ciudadCompleta = historialPedido.ciudad;
      let ciudadArray = ciudadCompleta.split("/");
      let ciudad = ciudadArray[0];

      let plataforma = procesarPlataforma(historialPedido.plataforma);

      content += `
                <tr>
                    <td>${historialPedido.numero_factura}</td>
                    <td>${historialPedido.fecha_factura}</td>
                    <td>
                        <div><strong>${historialPedido.nombre}</strong></div>
                        <div>${historialPedido.c_principal} y ${
        historialPedido.c_secundaria
      }</div>
                        <div>telf: ${historialPedido.telefono}</div>
                    </td>
                    <td>${historialPedido.provinciaa}-${ciudad}</td>
                    <td><span class="link-like" id="plataformaLink" onclick="abrirModal_infoTienda('${historialPedido.plataforma}')">${plataforma}</span></td>
                    <td>${transporte_content}</td>
                    <td>
                        <a class="w-100" href="https://wa.me/${formatPhoneNumber(
                          historialPedido.telefono
                        )}" style="font-size: 40px;" target="_blank"><box-icon type='logo' name='whatsapp-square' color="green"></box-icon></a>
                    </td>
                    <td>${impresiones}</td>
                    <td>
                        <button class="btn btn-sm btn-primary" onclick="boton_editarPedido(${
                          historialPedido.id_factura
                        })"><i class="fa-solid fa-pencil"></i></button>
                        <button class="btn btn-sm btn-danger"><i class="fa-solid fa-trash-can"></i></button>
                    </td>
                </tr>`;
    });
    document.getElementById("tableBody_historialPedidos").innerHTML = content;
  } catch (ex) {
    alert(ex);
  }
};

function abrirModal_infoTienda(tienda){
    let formData = new FormData();
    formData.append("tienda", tienda);
  
    $.ajax({
      url: SERVERURL + "pedidos/datosPlataformas",
      type: "POST", 
      data: formData,
      processData: false, // No procesar los datos
      contentType: false, // No establecer ningún tipo de contenido
      success: function (response) {
        $("#nombreTienda").val(response.nombre_tienda);
        $("#telefonoTienda").val(response.whatsapp);
        $("#correoTienda").val(response.email);
        $("#enlaceTienda").val(response.url_imporsuit);
  
        $('#infoTiendaModal').modal('show');
      },
      error: function (jqXHR, textStatus, errorThrown) {
        alert(errorThrown);
      },
    });
  }

function procesarPlataforma(url) {
    // Eliminar el "https://"
    let sinProtocolo = url.replace('https://', '');
  
    // Eliminar ".imporsuitpro.com"
    let baseNombre = sinProtocolo.replace('.imporsuitpro.com', '');
  
    // Convertir a mayúsculas
    let resultado = baseNombre.toUpperCase();
  
    return resultado;
  }

function boton_editarPedido(id) {
  window.location.href = "" + SERVERURL + "Pedidos/editar/" + id;
}

window.addEventListener("load", async () => {
  await initDataTableHistorial();
});

function formatPhoneNumber(number) {
  // Eliminar caracteres no numéricos excepto el signo +
  number = number.replace(/[^\d+]/g, "");

  // Verificar si el número ya tiene el código de país +593
  if (/^\+593/.test(number)) {
    // El número ya está correctamente formateado con +593
    return number;
  } else if (/^593/.test(number)) {
    // El número tiene 593 al inicio pero le falta el +
    return "+" + number;
  } else {
    // Si el número comienza con 0, quitarlo
    if (number.startsWith("0")) {
      number = number.substring(1);
    }
    // Agregar el código de país +593 al inicio del número
    number = "+593" + number;
  }

  return number;
}
