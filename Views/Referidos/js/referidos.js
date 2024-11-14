// Definir las constantes globales
let cabeceras_principal = [];
let referidos_principal = [];

function getFecha() {
  let fecha = new Date();
  let mes = fecha.getMonth() + 1;
  let dia = fecha.getDate();
  let anio = fecha.getFullYear();
  let fechaHoy = anio + "-" + mes + "-" + dia;
  return fechaHoy;
}
$(document).ready(function () {
  $.ajax({
    url: SERVERURL + "referidos/getReferidos",
    type: "GET",
    dataType: "json",
    success: function (response) {
      cabeceras_principal = response.cabeceras;
      referidos_principal = response.referidos;

      $("#cantidad_referidos").text(parseFloat(response.cantidad).toFixed(2));
      $("#ganancia_historico_referidos").text(
        parseFloat(response.ganancias).toFixed(2)
      );
      $("#ganancias_referidos").text(parseFloat(response.saldo).toFixed(2));

      if (response.saldo == "0") {
        $("#boton_solicitar_pago").hide();
      } else {
        $("#boton_solicitar_pago").show();
      }

      $("#image_tienda").attr(
        "src",
        SERVERURL + "public/img/profile_wallet.png"
      );

      // Inicializar las tablas después de cargar los datos
      initDataTableReferidos();
      initDataTableGuiasReferidos();
    },
    error: function (error) {
      console.error("Error al obtener la lista de bodegas:", error);
    },
  });
});

function generar_referido() {
  $.ajax({
    url: SERVERURL + "referidos/crearReferido",
    type: "GET",
    dataType: "json",
    success: function (response) {
      $("#link_referido").show();
    },
    error: function (error) {
      console.error("Error al obtener la lista de bodegas:", error);
    },
  });
}

$(document).ready(function () {
  $.ajax({
    url: SERVERURL + "wallet/obtenerCuentas",
    type: "GET",
    dataType: "json",
    success: function (response) {
      console.log(response);
      // Asegúrate de que la respuesta es un array
      if (Array.isArray(response)) {
        response.forEach(function (cuenta) {
          $("#cuenta").append(
            new Option(
              `${cuenta.banco}- ${cuenta.numero_cuenta} -${cuenta.tipo_cuenta}`,
              cuenta.id_cuenta
            )
          );
        });
      } else {
        console.log("La respuesta de la API no es un array:", response);
      }
    },
    error: function (error) {
      console.error("Error al obtener la lista de cuentas:", error);
    },
  });

  $.ajax({
    url: SERVERURL + "wallet/obtenerOtroPago",
    type: "GET",
    dataType: "json",
    success: function (response) {
      console.log(response);
      // Asegúrate de que la respuesta es un array
      if (Array.isArray(response)) {
        response.forEach(function (cuenta) {
          $("#formadePago").append(
            new Option(`${cuenta.tipo}- ${cuenta.cuenta}`, cuenta.id_pago)
          );
        });
      } else {
        console.log("La respuesta de la API no es un array:", response);
      }
    },
    error: function (error) {
      console.error("Error al obtener la lista de cuentas:", error);
    },
  });

  $.ajax({
    url: SERVERURL + "referidos/crearBilletera",
    type: "GET",
    dataType: "json",
    success: function (response) {},
    error: function (error) {
      console.error("Error al obtener la lista de bodegas:", error);
    },
  });
});

// TABLA REFERIDOS
let dataTableReferidos;
let dataTableReferidosIsInitialized = false;

const dataTableReferidosOptions = {
  columnDefs: [
    { className: "centered", targets: [0, 1, 2, 3, 4] },
    { orderable: false, targets: 0 }, //ocultar para columna 0 el ordenar columna
  ],
  dom: '<"d-flex w-full justify-content-between"lBf><t><"d-flex justify-content-between"ip>',

  buttons: [
    {
      extend: "excelHtml5",
      text: 'Excel <i class="fa-solid fa-file-excel"></i>',
      title: "Panel de Control: Usuarios",
      titleAttr: "Exportar a Excel",

      filename: "Referidos" + "_" + getFecha(),
      footer: true,
      className: "btn-excel",
    },
    {
      extend: "csvHtml5",
      text: 'CSV <i class="fa-solid fa-file-csv"></i>',
      title: "Panel de Control: Referidos",
      titleAttr: "Exportar a CSV",
      exportOptions: {
        columns: [1, 2, 3, 4, 5],
      },
      filename: "Referidos" + "_" + getFecha(),
      footer: true,
      className: "btn-csv",
    },
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

const initDataTableReferidos = () => {
  if (dataTableReferidosIsInitialized) {
    dataTableReferidos.destroy();
  }

  listReferidos();

  dataTableReferidos = $("#datatable_referidos").DataTable(
    dataTableReferidosOptions
  );

  dataTableReferidosIsInitialized = true;
};

const listReferidos = () => {
  try {
    let content = ``;

    referidos_principal.forEach((referido, index) => {
      content += `
                <tr>
                    <td>${referido.id_plataforma}</td>
                    <td>${referido.nombre_tienda}</td>
                    <td>${referido.url_imporsuit}</td>
                    <td>${referido.whatsapp}</td>      
                    <td>${referido.fecha_ingreso}</td>      
                </tr>`;
    });
    document.getElementById("tableBody_referidos").innerHTML = content;
  } catch (ex) {
    alert(ex);
  }
};

// TABLA GUIAS REFERIDOS
let dataTableGuiasReferidos;
let dataTableGuiasReferidosIsInitialized = false;

const dataTableGuiasReferidosOptions = {
  columnDefs: [
    { className: "centered", targets: [0, 1, 2, 3] },
    { orderable: false, targets: 0 }, //ocultar para columna 0 el ordenar columna
  ],
  dom: '<"d-flex w-full justify-content-between"lBf><t><"d-flex justify-content-between"ip>',

  buttons: [
    {
      extend: "excelHtml5",
      text: 'Excel <i class="fa-solid fa-file-excel"></i>',
      title: "Panel de Control: Usuarios",
      titleAttr: "Exportar a Excel",

      filename: "Referidos" + "_" + getFecha(),
      footer: true,
      className: "btn-excel",
    },
    {
      extend: "csvHtml5",
      text: 'CSV <i class="fa-solid fa-file-csv"></i>',
      title: "Panel de Control: Referidos",
      titleAttr: "Exportar a CSV",
      exportOptions: {
        columns: [1, 2, 3, 4],
      },
      filename: "Referidos" + "_" + getFecha(),
      footer: true,
      className: "btn-csv",
    },
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

const initDataTableGuiasReferidos = () => {
  if (dataTableGuiasReferidosIsInitialized) {
    dataTableGuiasReferidos.destroy();
  }

  listGuiasReferidos();

  dataTableGuiasReferidos = $("#datatable_guias_referidos").DataTable(
    dataTableGuiasReferidosOptions
  );

  dataTableGuiasReferidosIsInitialized = true;
};

const listGuiasReferidos = () => {
  try {
    let content = ``;

    cabeceras_principal.forEach((guia, index) => {
      content += `
                <tr>
                    <td>${guia.id_referido}</td>
                    <td>${guia.guia}</td>
                    <td>${guia.monto}</td>
                    <td>${guia.fecha}</td>
                </tr>`;
    });
    document.getElementById("tableBody_guias_referidos").innerHTML = content;
  } catch (ex) {
    alert(ex);
  }
};

// Asegurarse de que las tablas se inicializan solo después de cargar los datos
$(window).on("load", async () => {
  // Los datos se cargarán y las tablas se inicializarán automáticamente después de recibir la respuesta en el $.ajax
});
