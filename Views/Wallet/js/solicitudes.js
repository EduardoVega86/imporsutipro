let dataTableSolicitudes;
let dataTableSolicitudesIsInitialized = false;

$.fn.dataTable.ext.order['dom-checkbox'] = function (settings, col) {
  return this.api().column(col, { order: 'index' }).nodes().map(function (td, i) {
    return $('input[type="checkbox"]', td).prop('checked') ? 1 : 0;
  });
};

const dataTableSolicitudesOptions = {
  columnDefs: [
    { className: "centered", targets: [1, 2, 3, 4, 5] },
    { orderable: true, targets: 0, orderDataType: 'dom-checkbox' }, // Aplicar ordenación personalizada
  ],
  order: [[0, "asc"]], // Ordenar por la columna de checkboxes
  pageLength: 5,
  destroy: true,
  dom: '<"d-flex w-full justify-content-between"lBf><t><"d-flex justify-content-between"ip>',
  buttons: [
    {
      extend: "excelHtml5",
      text: 'Excel <i class="fa-solid fa-file-excel"></i>',
      title: "Panel de Control: Usuarios",
      titleAttr: "Exportar a Excel",
      exportOptions: {
        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9],
      },
      filename: "Productos" + "_" + getFecha(),
      footer: true,
      className: "btn-excel",
    },
    {
      extend: "csvHtml5",
      text: 'CSV <i class="fa-solid fa-file-csv"></i>',
      title: "Panel de Control: Productos",
      titleAttr: "Exportar a CSV",
      exportOptions: {
        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9],
      },
      filename: "Productos" + "_" + getFecha(),
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

const initDataTableSolicitudes = async () => {
  if (dataTableSolicitudesIsInitialized) {
    dataTableSolicitudes.destroy();
  }

  await listSolicitudes();

  dataTableSolicitudes = $("#datatable_solicitudes").DataTable(
    dataTableSolicitudesOptions
  );

  dataTableSolicitudesIsInitialized = true;
};

const listSolicitudes = async () => {
  try {
    const response = await fetch("" + SERVERURL + "wallet/obtenerSolicitudes");
    const solicitudes = await response.json();

    let content = ``;
    let checkboxState = "";
    solicitudes.forEach((solicitud, index) => {
      if (solicitud.visto == 1) {
        checkboxState = "checked disabled";
      } else {
        checkboxState = "";
      }
      content += `
                <tr>
                    <td><input type="checkbox" class="selectCheckbox" data-id="${solicitud.id_solicitud}" ${checkboxState} onclick="toggleSolicitud(${solicitud.id_solicitud}, this.checked)"></td>
                    <td>${solicitud.nombre}</td>
                    <td>${solicitud.correo}</td>
                    <td>${solicitud.cedula}</td>
                    <td>${solicitud.fecha}</td>
                    <td>${solicitud.telefono}</td>
                    <td>${solicitud.tipo_cuenta}</td>
                    <td>${solicitud.banco}</td>
                    <td>${solicitud.numero_cuenta}</td>
                    <td>${solicitud.cantidad}</td>
                    <td>
                        <button class="btn btn-sm btn-primary" onclick="Pagar(${solicitud.id_plataforma})"><i class="fa-solid fa-sack-dollar"></i>Pagar</button>
                        <button class="btn btn-sm btn-danger" onclick="eliminarSolicitud(${solicitud.id_solicitud})"><i class="fa-solid fa-trash-can"></i>Borrar</button>
                    </td>

                </tr>`;
    });
    document.getElementById("tableBody_solicitudes").innerHTML = content;
  } catch (ex) {
    alert(ex);
  }
};

// Función para manejar el evento click del checkbox
const toggleSolicitud = async (userId, isChecked) => {
  const proveedorValue = isChecked ? 1 : 0;
  const formData = new FormData();
  formData.append("id_solicitud", userId);

  try {
    const response = await fetch(`${SERVERURL}wallet/verificarPago`, {
      method: "POST",
      body: formData,
    });

    if (!response.ok) {
      throw new Error("Error al actualizar el solicitud");
    }

    const result = await response.json();
    initDataTableSolicitudes();
    initDataTableOtrasFormasPago();
  } catch (error) {
    console.error("Error:", error);
    alert("Hubo un error al actualizar el solicitud");
  }
};

function Pagar(id_plataforma) {
  window.location.href =
    "" + SERVERURL + "wallet/pagar?id_plataforma=" + id_plataforma;
}

let dataTableOtrasFormasPago;
let dataTableOtrasFormasPagoIsInitialized = false;

$.fn.dataTable.ext.order["dom-checkbox"] = function (settings, col) {
  return this.api()
    .column(col, { order: "index" })
    .nodes()
    .map(function (td, i) {
      return $('input[type="checkbox"]', td).prop("checked") ? 1 : 0;
    });
};

const dataTableOtrasFormasPagoOptions = {
  columnDefs: [
    { className: "centered", targets: [1, 2, 3, 4, 5] },
    { orderable: true, targets: 0, orderDataType: 'dom-checkbox' }, // Aplicar ordenación personalizada
  ],
  order: [[0, "asc"]], // Ordenar por la columna de checkboxes
  pageLength: 5,
  destroy: true,
  dom: '<"d-flex w-full justify-content-between"lBf><t><"d-flex justify-content-between"ip>',
  buttons: [
    {
      extend: "excelHtml5",
      text: 'Excel <i class="fa-solid fa-file-excel"></i>',
      title: "Panel de Control: Usuarios",
      titleAttr: "Exportar a Excel",
      exportOptions: {
        columns: [0, 1, 2, 3, 4, 5, 6],
      },
      filename: "Productos" + "_" + getFecha(),
      footer: true,
      className: "btn-excel",
    },
    {
      extend: "csvHtml5",
      text: 'CSV <i class="fa-solid fa-file-csv"></i>',
      title: "Panel de Control: Productos",
      titleAttr: "Exportar a CSV",
      exportOptions: {
        columns: [0, 1, 2, 3, 4, 5, 6],
      },
      filename: "Productos" + "_" + getFecha(),
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

const initDataTableOtrasFormasPago = async () => {
  if (dataTableOtrasFormasPagoIsInitialized) {
    dataTableOtrasFormasPago.destroy();
  }

  await listOtrasFormasPago();

  dataTableOtrasFormasPago = $("#datatable_otrasFormas_pago").DataTable(
    dataTableOtrasFormasPagoOptions
  );

  dataTableOtrasFormasPagoIsInitialized = true;
};

const listOtrasFormasPago = async () => {
  try {
    const response = await fetch(
      SERVERURL + "wallet/obtenerSolicitudes_otrasFormasPago"
    );
    const otrasFormasPago = await response.json();

    let content = ``;
    let checkboxState = "";

    for (const pago of otrasFormasPago) {
      if (pago.visto == 1) {
        checkboxState = "checked disabled";
      } else {
        checkboxState = "";
      }

      // Espera a que obtener_nombreTineda devuelva el nombre de la tienda
      let nombre_tienda = await obtener_nombreTineda(pago.id_plataforma);

      content += `
                <tr>
                    <td><input type="checkbox" class="selectCheckbox" data-id="${pago.id_solicitud}" ${checkboxState} onclick="toggleSolicitud(${pago.id_solicitud}, this.checked)"></td>
                    <td>${nombre_tienda}</td>
                    <td>${pago.fecha}</td>
                    <td>${pago.tipo}</td>
                    <td>${pago.red}</td>
                    <td>${pago.cuenta}</td>
                    <td>${pago.cantidad}</td>
                    <td>
                        <button class="btn btn-sm btn-primary" onclick="Pagar(${pago.id_plataforma})"><i class="fa-solid fa-sack-dollar"></i>Pagar</button>
                        <button class="btn btn-sm btn-danger" onclick="eliminarSolicitud(${pago.id_solicitud})"><i class="fa-solid fa-trash-can"></i>Borrar</button>
                    </td>
                </tr>`;
    }

    document.getElementById("tableBody_otrasFormas_pago").innerHTML = content;
  } catch (ex) {
    alert(ex);
  }
};

function obtener_nombreTineda(id_plataforma) {
  return new Promise((resolve, reject) => {
    let formData = new FormData();
    formData.append("id_plataforma", id_plataforma);

    $.ajax({
      url: SERVERURL + "Usuarios/obtener_infoTienda_privada",
      type: "POST",
      data: formData,
      processData: false,
      contentType: false,
      dataType: "json",
      success: function (response) {
        let nombre = response[0].nombre_tienda;
        resolve(nombre);
      },
      error: function (jqXHR, textStatus, errorThrown) {
        reject(errorThrown);
      },
    });
  });
}

function getFecha() {
  let fecha = new Date();
  let mes = fecha.getMonth() + 1;
  let dia = fecha.getDate();
  let anio = fecha.getFullYear();
  let fechaHoy = anio + "-" + mes + "-" + dia;
  return fechaHoy;
}
