let dataTable;
let dataTableIsInitialized = false;

const dataTableOptions = {
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

const initDataTable = async () => {
  if (dataTableIsInitialized) {
    dataTable.destroy();
  }

  await listGuias();

  dataTable = $("#datatable_guias").DataTable(dataTableOptions);

  dataTableIsInitialized = true;
};

const listGuias = async () => {
  try {
    const response = await fetch("" + SERVERURL + "pedidos/obtener_guias");
    const guias = await response.json();

    let content = ``;
    let impresiones = "";
    guias.forEach((guia, index) => {
      let transporte = guia.id_transporte;
      console.log(transporte);
      let transporte_content = "";
      if (transporte == 3) {
        transporte_content =
          '<span style="background-color: #28C839; color: white; padding: 5px; border-radius: 0.3rem;">SERVIENTREGA</span>';
      } else if (transporte == 1) {
        transporte_content =
          '<span style="background-color: #E3BC1C; color: white; padding: 5px; border-radius: 0.3rem;">LAAR</span>';
      } else if (transporte == 2) {
        transporte_content =
          '<span style="background-color: red; color: white; padding: 5px; border-radius: 0.3rem;">SPEED</span>';
      } else if (transporte == 4) {
        transporte_content =
          '<span style="background-color: red; color: white; padding: 5px; border-radius: 0.3rem;">GINTRACOM</span>';
      } else {
        transporte_content =
          '<span style="background-color: #E3BC1C; color: white; padding: 5px; border-radius: 0.3rem;">Guia no enviada</span>';
      }

      estado = validar_estado(guia.estado_guia_sistema);
      var span_estado = estado.span_estado; // Obtiene el valor de span_estado
      var estado_guia = estado.estado_guia; // Obtiene el valor de estado_guia

      //impresiones
      if (guia.impreso == 0) {
        impresiones = `<box-icon name='printer' color= "red"></box-icon>`;
      } else {
        impresiones = `<box-icon name='printer' color= "green"></box-icon>`;
      }
      content += `
                <tr>
                    <td>${guia.numero_factura}</td>
                    <td>${guia.fecha_factura}</td>
                    <td>
                        <div><strong>${guia.nombre}</strong></div>
                        <div>${guia.c_principal} y ${guia.c_secundaria}</div>
                        <div>telf: ${guia.telefono}</div>
                    </td>
                    <td>${guia.provincia}</td>
                    <td>${guia.ciudad}</td>
                    <td>${guia.tienda}</td>
                    <td>${transporte_content}</td>
                    <td>
                        <span class="w-100 ${span_estado}">${estado_guia}</span>
                        <a class="w-100" href="https://api.laarcourier.com:9727/guias/pdfs/DescargarV2?guia=${guia.numero_guia}" target="_blank">${guia.numero_guia}</a>
                        <a href="https://fenix.laarcourier.com/Tracking/Guiacompleta.aspx?guia=${guia.numero_guia}"></a><img src="https://new.imporsuitpro.com/public/img/tracking.png" class="profile-pic" id="buscar_traking" alt="buscar_traking"></a>
                        <a class="w-100" href="https://wa.me/${formatPhoneNumber(
                          guia.telefono
                        )}" style="font-size: 40px;" target="_blank"><box-icon type='logo' name='whatsapp-square' color="green"></box-icon></a>
                    </td>
                    <td>${impresiones}</td>
                    <td>
                        <button class="btn btn-sm btn-primary"><i class="fa-solid fa-pencil"></i></button>
                        <button class="btn btn-sm btn-danger"><i class="fa-solid fa-trash-can"></i></button>
                    </td>
                </tr>`;
    });
    document.getElementById("tableBody_guias").innerHTML = content;
  } catch (ex) {
    alert(ex);
  }
};

function validar_estado(estado) {
  var span_estado = "";
  var estado_guia = "";
  if (estado == 1) {
    span_estado = "badge-danger";
    estado_guia = "Anulado";
  } else if (estado == 2) {
    span_estado = "badge-purple";
    estado_guia = "Por recolectar";
  } else if (estado == 3) {
    span_estado = "badge-purple";
    estado_guia = "Por recolectar";
  } else if (estado == 4) {
    span_estado = "badge-purple";
    estado_guia = "Por recolectar";
  } else if (estado == 5) {
    span_estado = "badge-warning";
    estado_guia = "En transito";
  } else if (estado == 6) {
    span_estado = "badge-purple";
    estado_guia = "Por recolectar";
  } else if (estado == 7) {
    span_estado = "badge-green";
    estado_guia = "Entregado";
  } else if (estado == 8) {
    span_estado = "badge-danger";
    estado_guia = "Anulado";
  } else if (estado == 11) {
    span_estado = "badge-warning";
    estado_guia = "En transito";
  } else if (estado == 12) {
    span_estado = "badge-warning";
    estado_guia = "En transito";
  } else if (estado == 14) {
    span_estado = "badge-danger";
    estado_guia = "Con novedad";
  } else if (estado == 9) {
    span_estado = "badge-danger";
    estado_guia = "Devuelto";
  }

  return {
    span_estado: span_estado,
    estado_guia: estado_guia,
  };
}

window.addEventListener("load", async () => {
  await initDataTable();
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
