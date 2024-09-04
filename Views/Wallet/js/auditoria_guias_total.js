function datos_auditoriaPrincial(estado, transportadora) {
  let formData = new FormData();
  formData.append("estado", estado);
  formData.append("transportadora", transportadora);
  $.ajax({
    url: SERVERURL + "Wallet/obtenerTotalGuiasAuditoria",
    type: "POST",
    data: formData,
    processData: false, // No procesar los datos
    contentType: false, // No establecer ningún tipo de contenido
    dataType: "json",
    success: function (response) {
      $("#total_venta").text(
        parseFloat(response[0].total_cabecera_cuenta_pagar).toFixed(2)
      );

      /* $("#valor_recaudo").text(parseFloat(response[0].diferencia).toFixed(2)); */

      $("#por_pagar").text(parseFloat(response[0].diferencia).toFixed(2));

      $("#valor_pagado").text(parseFloat(response[0].total_pagos).toFixed(2));
    },
    error: function (jqXHR, textStatus, errorThrown) {
      alert(errorThrown);
    },
  });
}

/* $(document).ready(function () {
  var filtro_facturas_principal = $(this).data("filter"); // Actualizar variable con el filtro seleccionado
  var id_transportadora_principal = $("#transporte").val();
  datos_auditoriaPrincial(
    filtro_facturas_principal,
    id_transportadora_principal
  );

  $(".filter-btn").on("click", function () {
    $(".filter-btn").removeClass("active");
    $(this).addClass("active");

    var filtro_facturas = $(this).data("filter"); // Actualizar variable con el filtro seleccionado
    var id_transportadora = $("#transporte").val();
    initDataTableAuditoria(filtro_facturas, id_transportadora);
    datos_auditoriaPrincial(filtro_facturas, id_transportadora);
  });

  // Añadir event listener al select para el evento change
  $("#transporte").on("change", function () {
    var id_transportadora = $(this).val();
    var filtro_facturas = $(".filter-btn.active").data("filter"); // Obtener el filtro activo
    initDataTableAuditoria(filtro_facturas, id_transportadora);
    datos_auditoriaPrincial(filtro_facturas, id_transportadora);
  });
}); */

let dataTableAuditoria;
let dataTableAuditoriaIsInitialized = false;

const dataTableAuditoriaOptions = {
  columnDefs: [
    { className: "centered", targets: [0, 1, 2, 3, 4] },
    { orderable: false, targets: 0 }, //ocultar para columna 0 el ordenar columna
    { width: "100px", targets: 2 },
  ],
  pageLength: 10,
  destroy: true,
  responsive: true,
  autoWidth: true,
  bAutoWidth: true,
  dom: '<"d-flex w-full justify-content-between"lBf><t><"d-flex justify-content-between"ip>',
  buttons: [
    {
      extend: "excelHtml5",
      text: 'Excel <i class="fa-solid fa-file-excel"></i>',
      title: "Panel de Control: Usuarios",
      titleAttr: "Exportar a Excel",
      exportOptions: {
        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11],
      },
      filename: "auditoria" + "_" + getFecha(),
      footer: true,
      className: "btn-excel",
    },
    {
      extend: "csvHtml5",
      text: 'CSV <i class="fa-solid fa-file-csv"></i>',
      title: "Panel de Control: auditoria",
      titleAttr: "Exportar a CSV",
      exportOptions: {
        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11],
      },
      filename: "auditoria" + "_" + getFecha(),
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

const initDataTableAuditoria = async (estado, id_transporte) => {
  if (dataTableAuditoriaIsInitialized) {
    dataTableAuditoria.destroy();
  }

  await listAuditoria(estado, id_transporte);

  dataTableAuditoria = $("#datatable_auditoria").DataTable(
    dataTableAuditoriaOptions
  );

  dataTableAuditoriaIsInitialized = true;
};

const listAuditoria = async (estado, id_transporte) => {
  try {
    const formData = new FormData();
    formData.append("estado", estado);
    formData.append("transportadora", id_transporte);

    const response = await fetch(SERVERURL + "wallet/obtenerGuiasAuditoria", {
      method: "POST",
      body: formData,
    });

    const auditoria = await response.json();

    let content = ``;

    let total = 0;

    let total_valor_flete = 0;
    let total_costo_flete = 0;
    let valor_costo_flete = 0;
    //let devuelto = 0;
    let valor_cod_flete = 0;

    let total_recaudado = 0;

    let mostrar = 0;

    auditoria.forEach((item, index) => {
      let transporte = item.id_transporte;
      let transporte_content = "";
      let estado = "";
      let url_tracking = "";
      let url_descargar = "";
      let motivo = "";

      let valor_recaudado = parseFloat(item.valor);
      if (!isNaN(valor_recaudado)) {
        total_recaudado += valor_recaudado;
      } else {
        console.warn(
          "Valor de sdfsdfsd no es un número: ",
          item.valor_recaudado
        );
      }

      let utilidad = parseFloat(item.utilidad);
      if (!isNaN(utilidad)) {
        total += utilidad;
      } else {
        console.warn("Valor de utilidad no es un número: ", item.utilidad);
      }

      let costo_flete = parseFloat(item.costo_flete);

      if (!isNaN(costo_flete)) {
        total_costo_flete += costo_flete;
      } else {
        console.warn(
          "Valor de utilidad no es un número: ",
          item.total_costo_flete
        );
      }

      let valor_cod = parseFloat(item.valor_cod);

      let costo = parseFloat(item.costo);
      if (!isNaN(costo)) {
        valor_costo_flete += costo;
      } else {
        console.warn(
          "Valor de utilidad no es un número: ",
          item.valor_costo_flete
        );
      }

      if (transporte == 2) {
        transporte_content =
          '<span style="background-color: #28C839; color: white; padding: 5px; border-radius: 0.3rem;">SERVIENTREGA</span>';
        estado = validar_estadoServi(item.estado_guia_sistema);
      } else if (transporte == 1) {
        transporte_content =
          '<span style="background-color: #E3BC1C; color: white; padding: 5px; border-radius: 0.3rem;">LAAR</span>';
        estado = validar_estadoLaar(item.estado_guia_sistema);
      } else if (transporte == 4) {
        if (item.numero_guia.includes("MKL")) {
          transporte_content =
            '<span style="background-color: red; color: white; padding: 5px; border-radius: 0.3rem;">MerkaLogistic</span>';
        } else if (item.numero_guia.includes("SPD")) {
          transporte_content =
            '<span style="background-color: red; color: white; padding: 5px; border-radius: 0.3rem;">SPEED</span>';
        }
        estado = validar_estadoSpeed(item.estado_guia_sistema);
      } else if (transporte == 3) {
        transporte_content =
          '<span style="background-color: red; color: white; padding: 5px; border-radius: 0.3rem;">GINTRACOM</span>';
        estado = validar_estadoGintracom(item.estado_guia_sistema);
      } else {
        transporte_content =
          '<span style="background-color: #E3BC1C; color: white; padding: 5px; border-radius: 0.3rem;">Guia no enviada</span>';
      }

      var span_estado = estado.span_estado;
      var estado_guia = estado.estado_guia;
      var devuelto = estado.devuelto;

      var background = 'style="background-color: #E3BC1C;';

      const codBtn = item.cod
        ? `<button class="btn-cod-si">SI</button>`
        : `<button class="btn-cod-no">NO</button>`;

      // Determinar si el checkbox debe estar marcado
      let check = item.valida_transportadora == 1 ? "checked" : "";

      if (
        item.numero_guia.includes("IMP") ||
        item.numero_guia.includes("MKP")
      ) {
        url_tracking = `https://fenixoper.laarcourier.com/Tracking/Guiacompleta.aspx?guia=${item.numero_guia}`;
        url_descargar = `https://api.laarcourier.com:9727/guias/pdfs/DescargarV2?guia=${item.numero_guia}`;
      } else if (item.numero_guia.includes("I")) {
        url_tracking = `https://ec.gintracom.site/web/site/tracking`;
        url_descargar = `https://guias.imporsuitpro.com/Gintracom/label/${item.numero_guia}`;
      } else if (item.numero_guia.includes("SPD")) {
        url_tracking = ``;
        url_descargar = `https://guias.imporsuitpro.com/Speed/descargar/${item.numero_guia}`;
      } else {
        url_tracking = `https://servientrega-ecuador.appsiscore.com/app/app-cliente/cons_publica.php?guia=${item.numero_guia}&Request=Buscar+`;
        url_descargar = `https://guias.imporsuitpro.com/Servientrega/guia/${item.numero_guia}`;
      }
      var background = "";
      if (
        item.monto_recibir != item.monto_total_historial ||
        parseFloat(item.costo_flete * 100) >
          parseFloat(item.envio_wallet * 100) ||
        (item.drogshipin == 1 && item.id_plataforma == item.id_propietario) ||
        (item.drogshipin == 0 && item.id_plataforma != item.id_propietario) ||
        (item.drogshipin == 1 && item.costo_wallet <= 0) ||
        (item.numero_guia.includes("MKP") &&
          item.id_transporte == 1 &&
          item.costo_flete != 5.99) ||
        (devuelto == 1 && parseFloat(item.monto_recibir).toFixed(2) != parseFloat(item.envio_wallet).toFixed(2) * -1)
      ) {
        if (item.monto_recibir != item.monto_total_historial) {
          motivo = motivo + " DIFERENCIA MONTOS";
        }
        if (item.costo_flete > item.envio_wallet) {
          motivo =
            motivo +
            "-" +
            item.costo_flete +
            "-" +
            item.envio_wallet +
            " WALLET ENVIO MENOR";
        }
        if (item.drogshipin == 1 && item.id_plataforma == item.id_propietario) {
          motivo = motivo + " DROP IGUAL A PROPIETARIO";
        }
        if (item.drogshipin == 0 && item.id_plataforma != item.id_propietario) {
          motivo = motivo + " PROPIO DIFERENTE PROPIETARIO";
        }
        if (
          item.numero_guia.includes("MKP") &&
          item.id_transporte == 1 &&
          item.costo_flete != 5.99
        ) {
          motivo = motivo + " MKP DIFERENTE A 5.99";
        }
        if (devuelto == 1 && (parseFloat(item.monto_recibir).toFixed(2) != parseFloat(item.envio_wallet).toFixed(2)) * -1) {
          motivo =parseFloat(item.monto_recibir).toFixed(2) + "*" + parseFloat(item.envio_wallet).toFixed(2)* -1 + motivo + " VALOR DEVUELTO";
        }
        if (item.drogshipin == 1 && item.costo_wallet <= 0) {
          motivo =
            motivo +  "DROP COSTO 0";
        }
        mostrar = 1;
        background = 'style="background-color: red;"';
      } else {
        mostrar = 0;
        background = "";
      }
      if (mostrar == 0) {
        content += `
              <tr>
                  <td >${item.numero_factura}</td>
                  <td>${item.numero_guia}</td>
          <td><span class="w-100 ${span_estado}">${estado_guia}</span></td>
          <td>${item.drogshipin}</td>
                 <td>${item.costo_wallet}</td>
           <td>${transporte_content}</td>
                  <td>${codBtn}</td>
                  <td>${item.monto_factura}</td>
             <td>${item.envio_wallet}</td>
                  <td>${item.costo_flete}</td>
                  <td>${item.precio}</td>
                  <td>${item.costo}</td>
                 <td>${item.valor_cod}</td>
           <td>${item.utilidad}</td>
           
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
           </td>
           <td ${background}>${parseFloat(item.monto_recibir).toFixed(2)}</td>
            <td ${background}>${parseFloat(item.monto_total_historial).toFixed(
          2
        )}</td>
          <td ${background}>${item.valor}</td>
           <td ${background}>${item.comision}</td>
                  
             <td>${motivo}</td>
              </tr>`;
      }
    });

    // Formatear el total con dos decimales
    let totalConDosDecimales = total.toFixed(2);
    // Formatear el total con dos decimales
    let valorfletes = total_costo_flete.toFixed(2);

    let valor_costo_flete2 = valor_costo_flete.toFixed(2);

    total_recaudado = total_recaudado.toFixed(2);

    // Asignar el valor al span en el DOM
    $("#total_utilidad").text(valor_costo_flete2);

    $("#valor_recaudo").text(total_recaudado);
    $("#total_utilidad").text(totalConDosDecimales);
    $("#valor_fletes").text(valorfletes);

    $("#costo_flete").text(valor_costo_flete2);

    document.getElementById("tableBody_auditoria").innerHTML = content;

    // Añadir event listeners a los checkboxes
    const checkboxes = document.querySelectorAll(".selectCheckbox");
    checkboxes.forEach((checkbox) => {
      checkbox.addEventListener("click", async (event) => {
        const facturaId = event.target.getAttribute("data-id");
        const isChecked = event.target.checked ? 1 : 0; // Convertir a 1 o 0
        await handleCheckboxClick(facturaId, isChecked);
      });
    });
  } catch (ex) {
    alert(ex);
  }
};

const handleCheckboxClick = async (facturaId, isChecked) => {
  try {
    const formData = new FormData();
    formData.append("numero_guia", facturaId);
    formData.append("estado", isChecked);

    const response = await fetch(SERVERURL + "Wallet/habilitarAuditoria", {
      method: "POST",
      body: formData,
    });

    const result = await response.json();
    console.log(result); // Manejar la respuesta de la API
  } catch (error) {
    console.error("Error:", error);
  }
};

function validar_estadoLaar(estado) {
  var span_estado = "";
  var estado_guia = "";
  var devuelto = 0;
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
    devuelto = 1;
  }

  return {
    span_estado: span_estado,
    estado_guia: estado_guia,
    devuelto: devuelto,
  };
}

function validar_estadoServi(estado) {
  var span_estado = "";
  var estado_guia = "";
  var devuelto = 0;
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
  } else if (estado >= 400 && estado <= 403) {
    span_estado = "badge_green";
    estado_guia = "Entregado";
  } else if (estado >= 318 && estado <= 351) {
    span_estado = "badge_danger";
    estado_guia = "Con novedad";
  } else if (estado >= 500 && estado <= 502) {
    span_estado = "badge_danger";
    estado_guia = "Devuelto";
    devuelto = 1;
  }

  return {
    span_estado: span_estado,
    estado_guia: estado_guia,
    devuelto: devuelto,
  };
}

function validar_estadoGintracom(estado) {
  var span_estado = "";
  var estado_guia = "";
  var devuelto = 0;

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
    devuelto = 1;
  } else if (estado == 9) {
    span_estado = "badge_danger";
    estado_guia = "Devolución Entregada a Origen";
    devuelto = 1;
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
    devuelto = 1;
  }

  return {
    span_estado: span_estado,
    estado_guia: estado_guia,
    devuelto: devuelto,
  };
}

function validar_estadoSpeed(estado) {
  var span_estado = "";
  var estado_guia = "";
  var devuelto = 0;
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
    devuelto = 1;
  }

  return {
    span_estado: span_estado,
    estado_guia: estado_guia,
    devuelto: devuelto,
  };
}
