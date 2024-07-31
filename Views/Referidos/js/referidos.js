// Definir las constantes globales
let cabeceras_principal = [];
let referidos_principal = [];

$(document).ready(function () {
  $.ajax({
    url: SERVERURL + "referidos/getReferidos",
    type: "GET",
    dataType: "json",
    success: function (response) {
      cabeceras_principal = response.cabeceras;
      referidos_principal = response.referidos;

      $("#cantidad_referidos").text(response.cantidad);
      $("#ganancia_historico_referidos").text(response.ganancias);
      $("#ganancias_referidos").text(response.saldo);
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

// TABLA REFERIDOS
let dataTableReferidos;
let dataTableReferidosIsInitialized = false;

const dataTableReferidosOptions = {
  columnDefs: [
    { className: "centered", targets: [0, 1, 2, 3, 4] },
    { orderable: false, targets: 0 }, //ocultar para columna 0 el ordenar columna
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

const initDataTableReferidos = async () => {
  if (dataTableReferidosIsInitialized) {
    dataTableReferidos.destroy();
  }

  await listReferidos();

  dataTableReferidos = $("#datatable_referidos").DataTable(
    dataTableReferidosOptions
  );

  dataTableReferidosIsInitialized = true;
};

const listReferidos = async () => {
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

window.addEventListener("load", async () => {
  await initDataTableReferidos();
});

//TABLA GUIAS REFERIDOS
let dataTableGuiasReferidos;
let dataTableGuiasReferidosIsInitialized = false;

const dataTableGuiasReferidosOptions = {
  columnDefs: [
    { className: "centered", targets: [0, 1, 2, 3] },
    { orderable: false, targets: 0 }, //ocultar para columna 0 el ordenar columna
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

const initDataTableGuiasReferidos = async () => {
  if (dataTableGuiasReferidosIsInitialized) {
    dataTableGuiasReferidos.destroy();
  }

  await listGuiasReferidos();

  dataTableGuiasReferidos = $("#datatable_guias_referidos").DataTable(
    dataTableGuiasReferidosOptions
  );

  dataTableGuiasReferidosIsInitialized = true;
};

const listGuiasReferidos = async () => {
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

window.addEventListener("load", async () => {
  await initDataTableGuiasReferidos();
});
