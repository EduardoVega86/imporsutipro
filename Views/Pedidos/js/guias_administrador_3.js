let dataTable;
let dataTableIsInitialized = false;

// Opciones del DataTable (sin serverSide ni ajax)
const dataTableOptions = {
  // Definimos columnDefs para indicar estilos o configuraciones a columnas por índice
  // Ajusta los targets según la cantidad de columnas/td que tengas
  columnDefs: [
    // Por ejemplo, deshabilitamos el order en la primera columna (checkbox)
    { orderable: false, targets: 0 },
  ],
  order: [[2, "desc"]],    // Orden inicial
  pageLength: 25,
  lengthMenu: [10, 25, 50, 100],
  destroy: true,
  responsive: true,
  dom: '<"d-flex justify-content-between"lBf><t><"d-flex justify-content-between"ip>',
  buttons: [
    {
      extend: "excelHtml5",
      text: 'Excel <i class="fa-solid fa-file-excel"></i>',
      title: "Exportar a Excel",
      titleAttr: "Exportar a Excel",
      exportOptions: {
        // Ajusta qué columnas exportar
        columns: ":visible:not(:first-child)", 
      },
      filename: "guias_" + getFecha(),
      footer: true,
      className: "btn-excel",
    },
    {
      extend: "csvHtml5",
      text: 'CSV <i class="fa-solid fa-file-csv"></i>',
      title: "Exportar a CSV",
      titleAttr: "Exportar a CSV",
      exportOptions: {
        columns: ":visible:not(:first-child)",
      },
      filename: "guias_" + getFecha(),
      footer: true,
      className: "btn-csv",
    },
  ],
  language: {
    lengthMenu: "Mostrar _MENU_ registros por página",
    zeroRecords: "No se encontraron registros",
    info: "Mostrando de _START_ a _END_ de un total de _TOTAL_ registros",
    infoEmpty: "No se encontraron registros",
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

// Función para generar fecha en formato YYYY-MM-DD
function getFecha() {
  let fecha = new Date();
  let mes = fecha.getMonth() + 1;
  let dia = fecha.getDate();
  let anio = fecha.getFullYear();
  return `${anio}-${mes.toString().padStart(2, "0")}-${dia.toString().padStart(2, "0")}`;
}

// Inicializa DataTable una sola vez (o lo destruye y reinicia si ya existía)
const initDataTable = async () => {
  if (dataTableIsInitialized) {
    dataTable.destroy();
  }

  // Primero cargamos los datos y pintamos el <tbody> de la tabla manualmente
  await listGuias();

  // Luego iniciamos el DataTable tomando las filas ya pintadas
  dataTable = $("#datatable_guias").DataTable(dataTableOptions);

  dataTableIsInitialized = true;

  // Manejar el checkbox "Select All"
  const selectAllCheckbox = document.getElementById("selectAll");
  if (selectAllCheckbox) {
    selectAllCheckbox.addEventListener("change", function () {
      const checkboxes = document.querySelectorAll(".selectCheckbox");
      checkboxes.forEach((checkbox) => (checkbox.checked = this.checked));
    });
  }
};

// Recarga DataTable manteniendo paginación y pageLength
const reloadDataTable = async () => {
  const currentPage = dataTable.page();
  const currentLength = dataTable.page.len();

  dataTable.destroy();
  await listGuias(); // Volvemos a pintar el tbody manualmente
  dataTable = $("#datatable_guias").DataTable(dataTableOptions);

  // Restauramos la paginación y la longitud de página
  dataTable.page.len(currentLength).draw();
  dataTable.page(currentPage).draw(false);
  dataTableIsInitialized = true;

  // Reasignar evento de "Select All" tras reiniciar DataTable
  const selectAllCheckbox = document.getElementById("selectAll");
  if (selectAllCheckbox) {
    selectAllCheckbox.addEventListener("change", function () {
      const checkboxes = document.querySelectorAll(".selectCheckbox");
      checkboxes.forEach((checkbox) => (checkbox.checked = this.checked));
    });
  }
};

// Función que hace la petición al servidor, obtiene los datos y pinta el <tbody>
// Función que hace la petición al servidor, obtiene los datos y pinta el <tbody>
const listGuias = async () => {
  try {
    const formData = new FormData();
    let fechaInicio = $("#fecha_inicio").val() || "";
    let fechaFin = $("#fecha_fin").val() || "";
    formData.append("estado", $("#estado_q").val());
    formData.append("drogshipin", $("#tienda_q").val());
    formData.append("transportadora", $("#transporte").val());
    formData.append("impreso", $("#impresion").val());
    formData.append("despachos", $("#despachos").val());

    formData.append("fecha_inicio", fechaInicio);
    formData.append("fecha_fin", fechaFin);

    const response = await fetch(`${SERVERURL}pedidos/obtener_guiasAdministrador3`, {
      method: "POST",
      body: formData,
    });

    if (!response.ok) {
      throw new Error("Error en la respuesta del servidor");
    }

    const data = await response.json();
    const guias = data.data; // El array de objetos con la info de cada guía.

    console.log("Resultado JSON:", data);
    console.log("guias:", guias);

    // Verificamos si 'guias' es un array
    if (!Array.isArray(guias)) {
      throw new Error("El formato de los datos es incorrecto");
    }

    // Pintamos cada fila con 12 columnas en el mismo orden que el <thead>
    document.getElementById("tableBody_guias").innerHTML = guias
      .map((guia) => {
        // Preparamos algunos campos que queremos mostrar en columnas
        // (puedes cambiar la lógica según tu modelo de datos)
        
        // Destino (ciudad + provincia)
        const destino = guia.ciudad && guia.provinciaa
          ? `${guia.ciudad} - ${guia.provinciaa}`
          : guia.ciudad || "Sin ciudad";

        // Ícono de despachado
        let despachadoHTML = "";
        if (guia.estado_factura == 2) {
          // 2 = despachado
          despachadoHTML = `<i class='bx bx-check' style="color:#28E418; font-size: 30px;"></i>`;
        } else if (guia.estado_factura == 3) {
          // 3 = devuelto u otro estado, depende de tu lógica
          despachadoHTML = `<i class='bx bxs-truck' style="color:red; font-size: 30px;"></i>`;
        } else {
          // 1 = no despachado
          despachadoHTML = `<i class='bx bx-x' style="color:red; font-size: 30px;"></i>`;
        }

        // Ícono de impresiones
        const impresionesHTML = guia.impreso
          ? `<box-icon name='printer' color='#28E418'></box-icon>`
          : `<box-icon name='printer' color='red'></box-icon>`;

        // Arma las 12 <td> en el orden de tu <thead>
        return `
          <tr>
            <!-- 0: Checkbox -->
            <td>
              <input type="checkbox" class="selectCheckbox" data-id="${guia.id_factura || ''}">
            </td>

            <!-- 1: #Guía -->
            <td>${guia.numero_guia || "Sin número de guía"}</td>

            <!-- 2: Detalle (ej. "Ver detalle" + fecha) -->
            <td>
              <button class="btn btn-sm btn-outline-primary"
                      onclick="ver_detalle_cot('${guia.id_factura}')">
                Ver detalle
              </button><br>
              <small>${guia.fecha_factura || "Sin fecha"}</small>
            </td>

            <!-- 3: Cliente (nombre, dirección, teléfono) -->
            <td>
              <strong>${guia.nombre || "Sin nombre"}</strong><br>
              ${(guia.c_principal || "")} y ${(guia.c_secundaria || "")}<br>
              telf: ${guia.telefono || ""}
            </td>

            <!-- 4: Destino -->
            <td>${destino}</td>

            <!-- 5: Tienda -->
            <td>${guia.tienda || "Sin tienda"}</td>

            <!-- 6: Proveedor -->
            <td>${guia.nombre_proveedor || ""}</td>

            <!-- 7: Transportadora -->
            <td>${guia.transporte || "Sin transporte"}</td>

            <!-- 8: Estado (puedes usar tu propia lógica de validación) -->
            <td>
              <span class="badge ${guia.span_estado || "badge-default"}">
                ${guia.estado_guia || "Sin estado"}
              </span>
            </td>

            <!-- 9: Despachado -->
            <td>${despachadoHTML}</td>

            <!-- 10: Impreso -->
            <td>${impresionesHTML}</td>

            <!-- 11: Acciones (ej. anular, etc.) -->
            <td>
              <div class="dropdown">
                <button class="btn btn-sm btn-secondary dropdown-toggle" 
                        type="button" data-bs-toggle="dropdown">
                  <i class="fa-solid fa-gear"></i>
                </button>
                <ul class="dropdown-menu">
                  <li>
                    <span class="dropdown-item" style="cursor: pointer;"
                          onclick="anular_guiaServi('${guia.numero_guia}')">
                      Anular
                    </span>
                  </li>
                  <li>
                    <span class="dropdown-item" style="cursor: pointer;"
                          onclick='transito(${guia.id_factura})'>
                      Transito
                    </span>
                  </li>
                  <li>
                    <span class="dropdown-item" style="cursor: pointer;"
                          onclick='entregar(${guia.id_factura})'>
                      Entregado
                    </span>
                  </li>
                      <li><span class="dropdown-item" style="cursor: pointer;" onclick='devolucion(${
                        guia.id_factura
                      })' >Devolución</span></li>
                </ul>
              </div>
            </td>
          </tr>
        `;
      })
      .join("");
  } catch (error) {
    console.error("Error al obtener las guías:", error);
    alert("Hubo un problema al cargar las guías.");
  }
};


function transito(id_cabecera) {
  $.ajax({
    type: "POST",
    url: SERVERURL + "pedidos/transito/" + id_cabecera,
    dataType: "json",
    success: function (response) {
      if (response.status == 500) {
        toastr.error("ERROR AL REALIZAR EL CAMBIO", "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });
      } else if (response.status == 200) {
        toastr.success("CAMBIO REALIZADO CORRECTAMENTE", "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });
      }
    },
    error: function (xhr, status, error) {
      console.error("Error en la solicitud AJAX:", error);
      alert("Hubo un problema al realizar el cambio");
    },
  });
}

function entregar(id_cabecera) {
  $.ajax({
    type: "POST",
    url: SERVERURL + "pedidos/entregar/" + id_cabecera,
    dataType: "json",
    success: function (response) {
      if (response.status == 500) {
        toastr.error("ERROR AL REALIZAR EL CAMBIO", "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });
      } else if (response.status == 200) {
        toastr.success("CAMBIO REALIZADO CORRECTAMENTE", "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });
      }
    },
    error: function (xhr, status, error) {
      console.error("Error en la solicitud AJAX:", error);
      alert("Hubo un problema al realizar el cambio");
    },
  });
}

function devolucion(id_cabecera) {
  $.ajax({
    type: "POST",
    url: SERVERURL + "pedidos/devolucion/" + id_cabecera,
    dataType: "json",
    success: function (response) {
      if (response.status == 500) {
        toastr.error("ERROR AL REALIZAR EL CAMBIO", "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });
      } else if (response.status == 200) {
        toastr.success("CANBIO REALIZADO CORRECTAMENTE", "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });
      }
    },
    error: function (xhr, status, error) {
      console.error("Error en la solicitud AJAX:", error);
      alert("Hubo un problema al realizar el cambio");
    },
  });
}

// Event delegation for select change
document.addEventListener("change", async (event) => {
  if (event.target && event.target.classList.contains("select-estado-speed")) {
    const numeroGuia = event.target.getAttribute("data-numero-guia");
    const nuevoEstado = event.target.value;
    console.log(`Cambiando estado para la guía ${numeroGuia} a ${nuevoEstado}`);
    const formData = new FormData();
    formData.append("estado", nuevoEstado);

    if (nuevoEstado == 9) {
      $("#tipo_speed").val("recibir").change();
    }

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

        $("#gestionar_novedadSpeedModal").modal("show");
        reloadDataTable();
      }
    } catch (error) {
      console.error("Error al conectar con la API", error);
      alert("Error al conectar con la API");
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
      alert("Hubo un problema al anular la guia de Servientrega");
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
    estado_guia = "Novedad";
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
    estado_guia = "Novedad";
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
    estado_guia = "Entregado";
  } else if (estado == 8) {
    span_estado = "badge_danger";
    estado_guia = "Devuelto";
  } else if (estado == 9) {
    span_estado = "badge_danger";
    estado_guia = "Devuelto";
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
    estado_guia = "Devuelto";
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
      alert("Hubo un problema al anular gintracom");
    },
  });
}

//fin anular guia
//modal novedades
function gestionar_novedad(guia_novedad) {
  let transportadora = "";
  $.ajax({
    url: SERVERURL + "novedades/datos/" + guia_novedad,
    type: "GET",
    dataType: "json",
    success: function (response) {
      if (
        response.novedad[0].guia_novedad.includes("IMP") ||
        response.novedad[0].guia_novedad.includes("MKP")
      ) {
        transportadora = "LAAR";
        $("#seccion_laar").show();
        $("#seccion_servientrega").hide();
        $("#seccion_gintracom").hide();
      } else if (response.novedad[0].guia_novedad.includes("I")) {
        transportadora = "GINTRACOM";
        $("#seccion_laar").hide();
        $("#seccion_servientrega").hide();
        $("#seccion_gintracom").show();
      } else if (response.novedad[0].guia_novedad.includes("SPD")) {
        transportadora = "SPEED";
        $("#seccion_laar").hide();
        $("#seccion_servientrega").hide();
        $("#seccion_gintracom").hide();
      } else {
        transportadora = "SERVIENTREGA";
        $("#seccion_laar").hide();
        $("#seccion_servientrega").show();
        $("#seccion_gintracom").hide();
      }

      $("#id_gestionarNov").text(response.novedad[0].id_novedad);
      $("#cliente_gestionarNov").text(response.novedad[0].cliente_novedad);
      $("#estado_gestionarNov").text(response.novedad[0].estado_novedad);
      $("#transportadora_gestionarNov").text(transportadora);
      $("#novedad_gestionarNov").text(response.novedad[0].novedad);
      $("#tracking_gestionarNov").attr("href", response.novedad[0].tracking);

      $("#id_novedad").val(response.novedad[0].id_novedad);
      $("#numero_guia").val(response.novedad[0].guia_novedad);

      $("#gestionar_novedadModal").modal("show");
    },
    error: function (error) {
      console.error("Error al obtener la lista de bodegas:", error);
    },
  });
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

function enviar_gintraNovedad() {
  var button = document.getElementById("boton_gintra");
  button.disabled = true; // Desactivar el botón

  var guia = $("#numero_guia").val();
  var observacion = $("#Solucion_novedad").val();
  var id_novedad = $("#id_novedad").val();
  var tipo = $("#tipo_gintracom").val();
  var recaudo = "";
  var fecha = "";

  if (tipo == "recaudo") {
    recaudo = $("#Valor_recaudar").val();
  }
  if (tipo !== "rechazar") {
    fecha = $("#datepicker").val();
  }

  let formData = new FormData();
  formData.append("guia", guia);
  formData.append("observacion", observacion);
  formData.append("id_novedad", id_novedad);
  formData.append("tipo", tipo);
  formData.append("recaudo", recaudo);
  formData.append("fecha", fecha);

  $.ajax({
    url: SERVERURL + "novedades/solventarNovedadGintracom",
    type: "POST",
    data: formData,
    processData: false, // No procesar los datos
    contentType: false, // No establecer ningún tipo de contenido
    success: function (response) {
      response = JSON.parse(response);
      if (response.error === true) {
        toastr.error("" + response.message, "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });

        button.disabled = false;
      } else if (response.error === false) {
        toastr.success("" + response.message, "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });

        $("#gestionar_novedadModal").modal("hide");
        button.disabled = false;
        initDataTableNovedades();
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      alert(errorThrown);
      button.disabled = false;
    },
  });
}

function enviar_serviNovedad() {
  var button = document.getElementById("boton_servi");
  button.disabled = true; // Desactivar el botón

  var guia = $("#numero_guia").val();
  var observacion = $("#observacion_nov").val();
  var id_novedad = $("#id_novedad").val();

  let formData = new FormData();
  formData.append("guia", guia);
  formData.append("observacion", observacion);
  formData.append("id_novedad", id_novedad);

  $.ajax({
    url: SERVERURL + "novedades/solventarNovedadServientrega",
    type: "POST",
    data: formData,
    processData: false, // No procesar los datos
    contentType: false, // No establecer ningún tipo de contenido
    success: function (response) {
      response = JSON.parse(response);
      if (response.status == 500) {
        toastr.error("Novedad no enviada CORRECTAMENTE", "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });

        button.disabled = false;
      } else if (response.status == 200) {
        toastr.success("Novedad enviada CORRECTAMENTE", "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });

        $("#gestionar_novedadModal").modal("hide");
        button.disabled = false;
        initDataTableNovedades();
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      alert(errorThrown);
      button.disabled = false;
    },
  });
}

function enviar_laarNovedad() {
  var button = document.getElementById("boton_laar");
  button.disabled = true; // Desactivar el botón

  var guia = $("#numero_guia").val();
  var id_novedad = $("#id_novedad").val();
  var ciudad = $("#ciudad_novedadesServi").val();
  var nombre_novedadesServi = $("#nombre_novedadesServi").val();
  var callePrincipal_novedadesServi = $("#callePrincipal_novedadesServi").val();
  var calleSecundaria_novedadesServi = $(
    "#calleSecundaria_novedadesServi"
  ).val();
  var numeracion_novedadesServi = $("#numeracion_novedadesServi").val();
  var referencia_novedadesServi = $("#referencia_novedadesServi").val();
  var telefono_novedadesServi = $("#telefono_novedadesServi").val();
  var celular_novedadesServi = $("#celular_novedadesServi").val();
  var observacion_novedadesServi = $("#observacion_novedadesServi").val();
  var observacionA = $("#observacionA").val();

  let formData = new FormData();
  formData.append("guia", guia);
  formData.append("observacionA", observacionA);
  formData.append("id_novedad", id_novedad);
  formData.append("ciudad", ciudad);
  formData.append("nombre", nombre_novedadesServi);
  formData.append("callePrincipal", callePrincipal_novedadesServi);
  formData.append("calleSecundaria", calleSecundaria_novedadesServi);
  formData.append("numeracion", numeracion_novedadesServi);
  formData.append("referencia", referencia_novedadesServi);
  formData.append("telefono", telefono_novedadesServi);
  formData.append("celular", celular_novedadesServi);
  formData.append("observacion", observacion_novedadesServi);

  $.ajax({
    url: SERVERURL + "novedades/solventarNovedadLaar",
    type: "POST",
    data: formData,
    processData: false, // No procesar los datos
    contentType: false, // No establecer ningún tipo de contenido
    success: function (response) {
      response = JSON.parse(response);
      if (response.status == 500) {
        toastr.error("Novedad no enviada CORRECTAMENTE", "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });

        button.disabled = false;
      } else if (response.status == 200) {
        toastr.success("Novedad enviada CORRECTAMENTE", "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });

        $("#gestionar_novedadModal").modal("hide");
        button.disabled = false;
        initDataTableNovedades();
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      alert(errorThrown);
      button.disabled = false;
    },
  });
}
