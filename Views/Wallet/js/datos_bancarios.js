$(document).ready(function () {
  $("#datos_bancario").on("submit", function (event) {
    event.preventDefault();

    var formData = {
      banco: $("#banco").val(),
      tipo_cuenta: $("#tipo_cuenta").val(),
      numero_cuenta: $("#numero_cuenta").val(),
      nombre: $("#nombre_titular").val(),
      cedula: $("#cedula_titular").val(),
      correo: $("#correo_titular").val(),
      telefono: $("#telefono_titular").val(),
    };

    $.ajax({
      url: SERVERURL + "wallet/guardarDatosBancarios",
      type: "POST",
      data: formData,
      dataType: "json",
      success: function (response) {
        if (response.status == 500) {
          toastr.error(
            "DATOS BANCARIOS NO SE AGREGARON CORRECTAMENTE",
            "NOTIFICACIÓN",
            {
              positionClass: "toast-bottom-center",
            }
          );
        } else if (response.status == 200) {
          toastr.success("DATOS BANCARIOS SE AGREGARON CORRECTAMENTE", "NOTIFICACIÓN", {
            positionClass: "toast-bottom-center",
          });

          $("#imagen_categoriaModal").modal("hide");
          initDataTable();
        }
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.error(textStatus, errorThrown);
        alert("Error al guardar los datos");
      },
    });
  });
});
