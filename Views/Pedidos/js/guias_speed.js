let dataTable;
let dataTableIsInitialized = false;

const dataTableOptions = {
  columnDefs: [
    { className: "centered", targets: [1, 2, 3, 4, 5, 6, 7, 8, 9] },
    { orderable: false, targets: 0 }, //ocultar para columna 0 el ordenar columna
  ],
  order: [[2, "desc"]], // Ordenar por la primera columna (fecha) en orden descendente
  pageLength: 25,
  lengthMenu: [25, 50, 100, 200],
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
        columns: [1, 2, 3, 4, 5, 6, 7, 8],
      },
      filename: "guias" + "_" + getFecha(),
      footer: true,
      className: "btn-excel",
    },
    {
      extend: "csvHtml5",
      text: 'CSV <i class="fa-solid fa-file-csv"></i>',
      title: "Panel de Control: guias",
      titleAttr: "Exportar a CSV",
      exportOptions: {
        columns: [1, 2, 3, 4, 5, 6, 7, 8],
      },
      filename: "guias" + "_" + getFecha(),
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

const initDataTable = async () => {
  if (dataTableIsInitialized) {
    dataTable.destroy();
  }

  await listGuias();

  dataTable = $("#datatable_guias").DataTable(dataTableOptions);

  dataTableIsInitialized = true;

  // Handle select all checkbox
  document.getElementById("selectAll").addEventListener("change", function () {
    const checkboxes = document.querySelectorAll(".selectCheckbox");
    checkboxes.forEach((checkbox) => (checkbox.checked = this.checked));
  });
};

// Nueva función para recargar el DataTable manteniendo la paginación y el pageLength
const reloadDataTable = async () => {
  const currentPage = dataTable.page();
  const currentLength = dataTable.page.len();
  dataTable.destroy();
  await listGuias();
  dataTable = $("#datatable_guias").DataTable(dataTableOptions);
  dataTable.page.len(currentLength).draw();
  dataTable.page(currentPage).draw(false);
  dataTableIsInitialized = true;
  document.getElementById("selectAll").addEventListener("change", function () {
    const checkboxes = document.querySelectorAll(".selectCheckbox");
    checkboxes.forEach((checkbox) => (checkbox.checked = this.checked));
  });
};

const listGuias = async () => {
  try {
    const formData = new FormData();
    formData.append("fecha_inicio", fecha_inicio);
    formData.append("fecha_fin", fecha_fin);
    formData.append("estado", $("#estado_q").val());
    formData.append("drogshipin", $("#tienda_q").val());
    formData.append("impreso", $("#impresion").val());
    formData.append("despachos", $("#despachos").val());
    formData.append("recibo", $("#recibo").val());

    const response = await fetch(`${SERVERURL}pedidos/obtener_guiasSpeed`, {
      method: "POST",
      body: formData,
    });
    const guias = await response.json();

    let content = ``;
    let impresiones = "";
    let novedad = "";
    guias.forEach((guia, index) => {
      let transporte = guia.id_transporte;
      let transporte_content = "";
      let ruta_descarga = "";
      let ruta_traking = "";
      let funcion_anular = "";
      let select_speed = "";
      let drogshipin = "";
      let despachado = "";
      if (transporte == 2) {
        transporte_content =
          '<span style="background-color: #28C839; color: white; padding: 5px; border-radius: 0.3rem;">SERVIENTREGA</span>';
        ruta_descarga = `<a class="w-100" href="https://guias.imporsuitpro.com/Servientrega/guia/${guia.numero_guia}" target="_blank">${guia.numero_guia}</a>`;
        ruta_traking = `https://www.servientrega.com.ec/Tracking/?guia=${guia.numero_guia}&tipo=GUIA`;
        funcion_anular = `anular_guiaServi('${guia.numero_guia}')`;
        estado = validar_estadoServi(guia.estado_guia_sistema);
      } else if (transporte == 1) {
        transporte_content =
          '<span style="background-color: #E3BC1C; color: white; padding: 5px; border-radius: 0.3rem;">LAAR</span>';

        ruta_descarga = `<a class="w-100" href="https://api.laarcourier.com:9727/guias/pdfs/DescargarV2?guia=${guia.numero_guia}" target="_blank">${guia.numero_guia}</a>`;

        ruta_traking = `https://fenixoper.laarcourier.com/Tracking/Guiacompleta.aspx?guia=${guia.numero_guia}`;
        funcion_anular = `anular_guiaLaar('${guia.numero_guia}')`;
        estado = validar_estadoLaar(guia.estado_guia_sistema);
      } else if (transporte == 4) {
        if (guia.numero_guia.includes("MKL")) {
          transporte_content =
            '<span style="background-color: red; color: white; padding: 5px; border-radius: 0.3rem;">MerkaLogistic</span>';
        } else if (guia.numero_guia.includes("SPD")) {
          transporte_content =
            '<span style="background-color: red; color: white; padding: 5px; border-radius: 0.3rem;">SPEED</span>';
        }
        ruta_descarga = `<a class="w-100" href="https://guias.imporsuitpro.com/Speed/descargar/${guia.numero_guia}" target="_blank">${guia.numero_guia}</a>`;
        ruta_traking = ``;
        funcion_anular = `anular_guiaSpeed('${guia.numero_guia}')`;
        estado = validar_estadoSpeed(guia.estado_guia_sistema);
        select_speed = `
                    <select class="form-select select-estado-speed" style="max-width: 130px;" data-numero-guia="${
                      guia.numero_guia
                    }" data-id-factura="${guia.id_factura}" data-numero-factura="${guia.numero_factura}">
                        <option value="0" ${
                          guia.estado_guia_sistema == 0 ? "selected" : ""
                        }>-- Selecciona estado --</option>
                        <option value="2" ${
                          guia.estado_guia_sistema == 2 ? "selected" : ""
                        }>Generado</option>
                        <option value="3" ${
                          guia.estado_guia_sistema == 3 ? "selected" : ""
                        }>Transito</option>
                        <option value="14" ${
                          guia.estado_guia_sistema == 14 ? "selected" : ""
                        }>Novedad</option>
                        <option value="7" ${
                          guia.estado_guia_sistema == 7 ? "selected" : ""
                        }>Entregado</option>
                        <option value="9" ${
                          guia.estado_guia_sistema == 9 ? "selected" : ""
                        }>Devuelto</option>
                    </select>`;
      } else if (transporte == 3) {
        transporte_content =
          '<span style="background-color: red; color: white; padding: 5px; border-radius: 0.3rem;">GINTRACOM</span>';
        ruta_descarga = `<a class="w-100" href="https://guias.imporsuitpro.com/Gintracom/label/${guia.numero_guia}" target="_blank">${guia.numero_guia}</a>`;
        ruta_traking = `https://ec.gintracom.site/web/site/tracking`;
        funcion_anular = `anular_guiaGintracom('${guia.numero_guia}')`;
        estado = validar_estadoGintracom(guia.estado_guia_sistema);
      } else {
        transporte_content =
          '<span style="background-color: #E3BC1C; color: white; padding: 5px; border-radius: 0.3rem;">Guia no enviada</span>';
      }

      var span_estado = estado.span_estado;
      var estado_guia = estado.estado_guia;

      if (guia.drogshipin == 0) {
        drogshipin = "Local";
      } else if (drogshipin == 1) {
        drogshipin = "Drogshipin";
      }

      // Definir la variable ciudad antes de los bloques if-else
      let ciudad = "Ciudad no especificada";

      // Verificar si la ciudad es válida antes de usar split
      let ciudadCompleta = guia.ciudad;

      if (ciudadCompleta) {
        let ciudadArray = ciudadCompleta.split("/");
        ciudad = ciudadArray[0];
      } else {
        console.log("La ciudad no está definida o está vacía");
      }

      novedad = "";
      if (guia.estado_guia_sistema == 14 && transporte == 1) {
        novedad = `<button id="downloadExcel" class="btn btn_novedades" onclick="gestionar_novedad('${guia.numero_guia}')">Gestionar novedad</button>`;
      } else if (guia.estado_guia_sistema == 6 && transporte == 3) {
        novedad = `<button id="downloadExcel" class="btn btn_novedades" onclick="gestionar_novedad('${guia.numero_guia}')">Gestionar novedad</button>`;
      } /* else if (guia.estado_guia_sistema == 14 && transporte == 4) {
        novedad = `<button id="downloadExcel" class="btn btn_novedades" onclick="gestionar_novedad('${guia.numero_guia}')">Gestionar novedad</button>`;
      } */
      if (
        guia.estado_guia_sistema >= 318 &&
        guia.estado_guia_sistema <= 351 &&
        transporte == 2
      ) {
        novedad = `<button id="downloadExcel" class="btn btn_novedades" onclick="gestionar_novedad('${guia.numero_guia}')">Gestionar novedad</button>`;
      }

      let plataforma = procesarPlataforma(guia.plataforma);
      if (guia.impreso == 0) {
        impresiones = `<box-icon name='printer' color= "red"></box-icon>`;
      } else {
        impresiones = `<box-icon name='printer' color= "#28E418"></box-icon>`;
      }

      despachado = "";
      if (guia.estado_factura == 2) {
        despachado = `<i class='bx bx-check' style="color:#28E418; font-size: 30px;"></i>`;
      } else if (guia.estado_factura == 1) {
        despachado = `<i class='bx bx-x' style="color:red; font-size: 30px;"></i>`;
      } else if (guia.estado_factura == 3) {
        despachado = `<i class='bx bxs-truck' style="color:red; font-size: 30px;"></i>`;
      }

      let recibo = "";
      if (guia.recibo != null) {
        recibo = `
        <a href="${SERVERURL}${guia.recibo}" class="icon-link" target="_blank">
          <i class="fas fa-receipt"></i>
        </a>`;
      }

      let repartidor = "";

      if (guia.id_motorizado) {
        repartidor = `<button onclick="abrir_modal_dellate_repartidor('${guia.id_motorizado}')" class="btn btn-sm btn-outline-primary"> Detalle repartidor</button>`;
      } else {
        repartidor = "No hay repartidor asignado";
      }

      content += `
                <tr>
                    <td><input type="checkbox" class="selectCheckbox" data-id="${
                      guia.id_factura
                    }"></td>
                    <td>
                    <div>
                    ${guia.numero_factura}
                    </div>
                    <div>
                    ${drogshipin}
                    </div>
                    </td>
                    <td>
                    <div><button onclick="ver_detalle_cot('${
                      guia.id_factura
                    }')" class="btn btn-sm btn-outline-primary"> Ver detalle</button></div>
                    <div>${guia.fecha_factura}</td></div>
                    <td>
                        <div><strong>${guia.nombre}</strong></div>
                        <div>${guia.c_principal} y ${guia.c_secundaria}</div>
                        <div>telf: ${guia.telefono}</div>
                    </td>
                    <td>${guia.provinciaa}-${ciudad}</td>
                    <td><span class="link-like" id="plataformaLink">${
                      guia.tienda
                    }</span></td>
                    <td><span class="link-like" id="plataformaLink">${
                      guia.nombre_proveedor
                    }</span></td>
                    <td>${repartidor}</td>
                    <td>${transporte_content}</td>
                    <td>
                    <button onclick="abrir_modal_subirURL('${
                      guia.numero_guia
                    }','${
        guia.googlemaps
      }')" class="btn btn-sm btn-outline-primary"> Subir ubicacion</button>
                    </td>
                    <td>
                     <div style="text-align: center;">
                     <div>
                      <span class="w-100 text-nowrap ${span_estado}">${estado_guia}</span>
                     </div>
                     <div>
                     ${ruta_descarga}
                     </div>
                     <div style="position: relative; display: inline-block;">
                      <a href="${ruta_traking}" target="_blank" style="vertical-align: middle;">
                        <img src="https://new.imporsuitpro.com/public/img/tracking.png" width="40px" id="buscar_traking" alt="buscar_traking">
                      </a>
                      <a href="https://wa.me/${formatPhoneNumber(
                        guia.telefono
                      )}" target="_blank" style="font-size: 45px; vertical-align: middle; margin-left: 10px;" target="_blank">
                      <i class='bx bxl-whatsapp-square' style="color: green;"></i>
                      </a>
                     </div>
                     <div style="text-align: -webkit-center;">
                     ${select_speed}
                     </div>
                     <div>
                     ${novedad}
                     </div>
                     </div>
                    </td>
                    <td>${despachado}</td>
                    <td>${impresiones}</td>
                    <td>${recibo}</td>
                    <td>
                    <div class="dropdown">
                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-gear"></i>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <li><span class="dropdown-item" style="cursor: pointer;" onclick="${funcion_anular}">Anular</span></li>
                        <li><span class="dropdown-item" style="cursor: pointer;">Información</span></li>
                    </ul>
                </div>
                    </td>
                </tr>`;
    });
    document.getElementById("tableBody_guias").innerHTML = content;
  } catch (ex) {
    alert(ex);
  }
};

// Event delegation for select change
document.addEventListener("change", async (event) => {
  if (event.target && event.target.classList.contains("select-estado-speed")) {
    const numeroGuia = event.target.getAttribute("data-numero-guia");
    const nuevoEstado = event.target.value;

    const idFactura = event.target.getAttribute("data-id-factura");
    const numeroFactura = event.target.getAttribute("data-numero-factura");
    

    if (nuevoEstado == 7) {
      $("#numeroGuia_subir_reporte").val(numeroGuia);
      $("#nuevoEstado_subir_reporte").val(nuevoEstado);
      $("#idFactura_subir_reporte").val(idFactura);

      $("#subir_imagen_speedModal").modal("show");
      reloadDataTable();
    } else if (nuevoEstado == 14) {
      const formData = new FormData();
      formData.append("estado", nuevoEstado);
      formData.append("numeroGuia", numeroGuia);
      formData.append("numeroFactura", numeroFactura);

      try {
        const response = await fetch(
          SERVERURL+`Speed/cambiar_estado_novedad`,
          {
            method: "POST",
            body: formData,
          }
        );
        const result = await response.json();
        if (result.status == 200) {
          toastr.success("ESTADO ACTUALIZADO CORRECTAMENTE", "NOTIFICACIÓN", {
            positionClass: "toast-bottom-center",
          });

          reloadDataTable();
        }
      } catch (error) {
        console.error("Error al conectar con la API", error);
        alert("Error al conectar con la API");
      }
    } else if (nuevoEstado == 9) {
      const formData = new FormData();
      formData.append("estado", nuevoEstado);
      formData.append("numeroGuia", numeroGuia);
      formData.append("numeroFactura", numeroFactura);

      try {
        const response = await fetch(
          SERVERURL+`Speed/cambiar_estado_devolucion`,
          {
            method: "POST",
            body: formData,
          }
        );
        const result = await response.json();
        if (result.status == 200) {
          toastr.success("ESTADO ACTUALIZADO CORRECTAMENTE", "NOTIFICACIÓN", {
            positionClass: "toast-bottom-center",
          });

          reloadDataTable();
        }
      } catch (error) {
        console.error("Error al conectar con la API", error);
        alert("Error al conectar con la API");
      }
    } else {
      const formData = new FormData();
      formData.append("estado", nuevoEstado);

      try {
        const response = await fetch(
          `https://guias.imporsuitpro.com/Speed/estado/${numeroGuia}`,
          {
            method: "POST",
            body: formData,
          }
        );
        const result = await response.json();
        if (result.status == 200) {
          toastr.success("ESTADO ACTUALIZADO CORRECTAMENTE", "NOTIFICACIÓN", {
            positionClass: "toast-bottom-center",
          });

          reloadDataTable();
        }
      } catch (error) {
        console.error("Error al conectar con la API", error);
        alert("Error al conectar con la API");
      }
    }
  }
});

function anular_guiaSpeed(numero_guia) {
  $.ajax({
    type: "GET",
    url: "https://guias.imporsuitpro.com/Speed/anular/" + numero_guia,
    dataType: "json",
    success: function (response) {
      if (response.status == 500) {
        toastr.error("LA GUIA NO SE ANULO CORRECTAMENTE", "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });
      } else if (response.status == 200) {
        toastr.success("GUIA ANULADA CORRECTAMENTE", "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });

        reloadDataTable();
      }
    },
    error: function (xhr, status, error) {
      alert("Hubo un problema al anular la guia de speed");
    },
  });
}

function abrirModal_infoTienda(tienda) {
  let formData = new FormData();
  formData.append("tienda", tienda);

  $.ajax({
    url: SERVERURL + "pedidos/datosPlataformas",
    type: "POST",
    data: formData,
    processData: false, // No procesar los datos
    contentType: false, // No establecer ningún tipo de contenido
    success: function (response) {
      response = JSON.parse(response);
      $("#nombreTienda").val(response[0].nombre_tienda);
      $("#telefonoTienda").val(response[0].whatsapp);
      $("#correoTienda").val(response[0].email);
      $("#enlaceTienda").val(response[0].url_imporsuit);

      $("#infoTiendaModal").modal("show");
    },
    error: function (jqXHR, textStatus, errorThrown) {
      alert(errorThrown);
    },
  });
}

function abrir_modal_subirURL(numero_guia, url_google) {
  $("#numero_guia_url").val(numero_guia);
  $("#url_direccion_google").val(url_google);

  $("#subir_direccion_speedModal").modal("show");
}

function abrir_modal_dellate_repartidor(id_motorizado) {
  let formData = new FormData();
  formData.append("id_usuario", id_motorizado); // Añadir el SKU al FormData
  $.ajax({
    url: SERVERURL + "speed/perfil",
    type: "POST",
    data: formData,
    processData: false, // No procesar los datos
    contentType: false, // No establecer ningún tipo de contenido
    dataType: "json",
    success: function (response) {
      $("#nombre_motorizado_detalle").text(response.data.nombre_motorizado);
      $("#numero_motorizado_detalle").text(response.data.numero_motorizado);

      if (response.data.placa_motorizado) {
        $("#imagen_placa_detalle")
          .attr("src", SERVERURL + imagen)
          .show();
      } else {
        $("#imagen_placa_detalle")
          .attr("src", SERVERURL + "public/img/broken-image.png")
          .show();
      }

      $("#detalles_repartidorModal").modal("show");
    },
    error: function (jqXHR, textStatus, errorThrown) {
      alert(errorThrown);
    },
  });
}

function ver_detalle_cot(id_factura) {
  let formData = new FormData();
  formData.append("id_factura", id_factura);

  $.ajax({
    url: SERVERURL + "Pedidos/obtenerDetalle",
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
          let descuentoTotal = subtotal * (detalle.desc_venta / 100);
          let precioFinal = subtotal - descuentoTotal;
          total += precioFinal;

          let rowHtml = `
            <tr>
              <td>${detalle.nombre_producto}</td>
              <td>${detalle.cantidad}</td>
              <td>${detalle.precio_venta}</td>
              <td>${detalle.desc_venta}</td>
              <td>${precioFinal.toFixed(2)}</td>
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

function procesarPlataforma(url) {
  if (url == null || url == "") {
    let respuesta_error = "La tienda ya no existe";
    return respuesta_error;
  }
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
    estado_guia = "Recolectado";
  } else if (estado == 4) {
    span_estado = "badge_purple";
    estado_guia = "En bodega";
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
  } else if (estado >= 400 && estado <= 403) {
    span_estado = "badge_green";
    estado_guia = "Entregado";
  } else if (estado >= 318 && estado <= 351) {
    span_estado = "badge_danger";
    estado_guia = "Con novedad";
  } else if (estado >= 500 && estado <= 502) {
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
    span_estado = "badge_purple";
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
  } else if (estado == 14) {
    span_estado = "badge_purple";
    estado_guia = "Novedad";
  }

  return {
    span_estado: span_estado,
    estado_guia: estado_guia,
  };
}

// Function to handle the click event for sending selected items
document.getElementById("imprimir_guias").addEventListener("click", () => {
  const selectedGuias = [];
  const checkboxes = document.querySelectorAll(".selectCheckbox:checked");

  checkboxes.forEach((checkbox) => {
    selectedGuias.push(checkbox.getAttribute("data-id"));
  });

  // Convert the selected items to JSON and log it to the console
  const selectedGuiasJson = JSON.stringify(selectedGuias);
  console.log(selectedGuiasJson);

  let formData = new FormData();
  formData.append("facturas", selectedGuiasJson);

  $.ajax({
    type: "POST",
    url: SERVERURL + "/Manifiestos/generar", // Asegúrate de que SERVERURL esté definida
    data: formData,
    processData: false, // Necesario para FormData
    contentType: false, // Necesario para FormData
    dataType: "json",
    beforeSend: function () {
      // Mostrar alerta de carga antes de realizar la solicitud AJAX
      Swal.fire({
        title: "Cargando",
        text: "Creando lista de productos",
        allowOutsideClick: false,
        showConfirmButton: false,
        willOpen: () => {
          Swal.showLoading();
        },
      });
    },
    success: function (response) {
      if (response.status == 200) {
        const link = document.createElement("a");
        link.href = response.download;
        link.download = ""; // Puedes poner un nombre de archivo aquí si lo deseas
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);

        // Cerrar el Swal después de hacer clic en el enlace
        reloadDataTable();
        Swal.close();
      }
    },
    error: function (xhr, status, error) {
      console.error("Error en la solicitud AJAX:", error);
      alert("Hubo un problema al imprimir manifiesto");
    },
  });
});

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

//anular guia
function anular_guiaLaar(numero_guia) {
  let formData = new FormData();
  formData.append("guia", numero_guia);

  $.ajax({
    url: SERVERURL + "guias/anularGuia",
    type: "POST",
    data: formData,
    processData: false, // No procesar los datos
    contentType: false, // No establecer ningún tipo de contenido
    success: function (response) {
      response = JSON.parse(response);
      if (response.status == 500) {
        toastr.error("LA GUIA NO SE ANULO CORRECTAMENTE", "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });
      } else if (response.status == 200) {
        toastr.success("GUIA ANULADA CORRECTAMENTE", "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });

        $("#imagen_categoriaModal").modal("hide");
        reloadDataTable();
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      alert(errorThrown);
    },
  });
}

function anular_guiaServi(numero_guia) {
  $.ajax({
    type: "GET",
    url: "https://guias.imporsuitpro.com/Servientrega/Anular/" + numero_guia,
    dataType: "json",
    success: function (response) {},
    error: function (xhr, status, error) {
      /* alert("Hubo un problema al anular la guia de Servientrega"); */
    },
  });

  $.ajax({
    type: "GET",
    /* url: "https://guias.imporsuitpro.com/Servientrega/Anular/" + numero_guia, */
    url: SERVERURL + "Guias/anularServi_temporal/" + numero_guia,
    dataType: "json",
    success: function (response) {
      if (response.status == 500) {
        toastr.error("LA GUIA NO SE ANULO CORRECTAMENTE", "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });
      } else if (response.status == 200) {
        toastr.success("GUIA ANULADA CORRECTAMENTE", "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });

        reloadDataTable();
      }
    },
    error: function (xhr, status, error) {
      alert("Hubo un problema al anular la guia de Servientrega");
    },
  });
}

function anular_guiaGintracom(numero_guia) {
  $.ajax({
    type: "POST",
    url: "https://guias.imporsuitpro.com/Gintracom/anular/" + numero_guia,
    dataType: "json",
    success: function (response) {
      if (response == "1") {
        toastr.success("GUIA ANULADA CORRECTAMENTE", "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });

        $("#imagen_categoriaModal").modal("hide");
        reloadDataTable();
      } else {
        toastr.error("LA GUIA NO SE ANULO CORRECTAMENTE", "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });
      }
    },
    error: function (xhr, status, error) {
      console.error("Error en la solicitud AJAX:", error);
      alert("Hubo un problema al anular guia de gintracom");
    },
  });
}

//fin anular guia
//modal novedades
function gestionar_novedad() {
  window.location.href = SERVERURL + "Pedidos/novedades_2";
}

$(document).ready(function () {
  $("#tipo_gintracom").change(function () {
    var tipo = $("#tipo_gintracom").val();
    if (tipo == "recaudo") {
      $("#valor_recaudoGintra").show();
      $("#fecha_gintra").show();
    } else if (tipo == "rechazar") {
      $("#valor_recaudoGintra").hide();
      $("#fecha_gintra").hide();
    } else {
      $("#valor_recaudoGintra").hide();
      $("#fecha_gintra").show();
    }
  });
});
