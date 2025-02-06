$(document).ready(function () {
  console.log("Entramos");
  initTable();

  // Deshabilitar el botón "Guardar" al inicio
  $("#passwordForm button[type='submit']").prop("disabled", true);

  // Evento para detectar escritura en el input de contraseña
  $("#password").on("input", function () {
    let passwordValue = $(this).val().trim();
    if (passwordValue.length > 0) {
      $("#generatePassword").prop("disabled", true); // Bloquea "Generar por defecto"
      $("#passwordForm button[type='submit']").prop("disabled", false); // Desbloquea "Guardar"
    } else {
      $("#generatePassword").prop("disabled", false); // Desbloquea "Generar por defecto"
      $("#passwordForm button[type='submit']").prop("disabled", true); // Bloquea "Guardar"
    }
  });

  // Evento cuando se abre el modal para resetear botones
  $("#passwordModal").on("show.bs.modal", function () {
    $("#password").val(""); // Limpiar campo contraseña
    $("#generatePassword").prop("disabled", false); // Habilitar "Generar por defecto"
    $("#passwordForm button[type='submit']").prop("disabled", true); // Bloquear "Guardar"
  });
});

const TOAST = Swal.mixin({
  toast: true,
  position: "top-end",
  showConfirmButton: false,
  timer: 3000,
  timerProgressBar: true,
  didOpen: (toast) => {
    toast.addEventListener("mouseenter", Swal.stopTimer);
    toast.addEventListener("mouseleave", Swal.resumeTimer);
  },
});

function editarUsuario(id_usuario) {
  $("#passwordModal").modal("show");
  $("#userId").val(id_usuario);
}

$("#passwordForm").submit(function (e) {
  e.preventDefault();
  let userId = $("#userId").val();
  let password = $("#password").val();
  let formData = new FormData();
  formData.append("id_usuario", userId);
  formData.append("password", password);
  $.ajax({
    url: `${SERVERURL}/usuarios/normal_password`,
    type: "POST",
    data: formData,
    processData: false,
    contentType: false,
    dataType: "json",
    success: function (response) {
      if (response.status == 200) {
        $("#passwordModal").modal("hide");
        $("#password").val("");
        TOAST.fire({
          icon: "success",
          title: "Contraseña actualizada correctamente",
        });
      } else {
        alert("Error al actualizar la contraseña");
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      alert(errorThrown);
    },
  });
});

$("#generatePassword").click(function () {
  let userId = $("#userId").val();
  let formData = new FormData();
  formData.append("id_usuario", userId);
  $.ajax({
    url: `${SERVERURL}/usuarios/default_password`,
    type: "POST",
    data: formData,
    processData: false,
    contentType: false,
    dataType: "json",
    success: function (response) {
      if (response.status == 200) {
        $("#passwordModal").modal("hide");
        TOAST.fire({
          icon: "success",
          title: "Contraseña generada correctamente",
        });
      } else {
        alert("Error al generar la contraseña");
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      alert(errorThrown);
    },
  });
});
// inicializa la tabla
function initTable() {
  $("#lista_usuarios").DataTable({
    processing: true,
    serverSide: false,
    ajax: {
      url: `${SERVERURL}/usuarios/passwords_list`,
      type: "POST",
    },
    columns: [
      { data: "id_users" },
      { data: "nombre_users" },
      { data: "email_users" },
      { data: "whatsapp" },
      { data: "nombre_tienda" },
      {
        data: "date_added",
        render: function (data, type, row) {
          if (!data) return "";
          let [year, month, day] = data.split(" ")[0].split("-");
          return `${day}/${month}/${year}`; // Convierte YYYY-MM-DD a DD/MM/YYYY
        },
      },
      { data: "acciones" },
    ],
    columnDefs: [
      { width: "5%", targets: 0 }, // ID
      { width: "15%", targets: 1 }, // Nombre
      { width: "20%", targets: 2 }, // Email
      { width: "10%", targets: 3 }, // WhatsApp
      { width: "10%", targets: 4 }, // Tienda
      { width: "10%", targets: 5 }, // Fecha
      { width: "10%", targets: 6 }, // Acciones
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
    pageLength: 10,
    destroy: true,
  });
}
