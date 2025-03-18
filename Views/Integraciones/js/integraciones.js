function abrirmodal_facebook() {
  let formData = new FormData();
  formData.append("tipo", 1); // Añadir el SKU al FormData

  $.ajax({
    url: SERVERURL + "tienda/obtenerPixel",
    type: "POST", // Cambiar a POST para enviar FormData
    data: formData,
    processData: false, // No procesar los datos
    contentType: false, // No establecer ningún tipo de contenido
    dataType: "json",
    success: function (response) {
      if (response == 0) {
        $("#id_estado_facebook").val(0);
      } else {
        $("#id_estado_facebook").val(1);
        console.log(response.pixel);
        console.log(response[0].pixel);
        $("#script_facebook").val(response[0].pixel);
      }
      $("#conectar_facebookModal").modal("show");
    },
    error: function (jqXHR, textStatus, errorThrown) {
      alert(errorThrown);
    },
  });
}

function abrirmodal_tiktok() {
  let formData = new FormData();
  formData.append("tipo", 2); // Añadir el SKU al FormData

  $.ajax({
    url: SERVERURL + "tienda/obtenerPixel",
    type: "POST", // Cambiar a POST para enviar FormData
    data: formData,
    processData: false, // No procesar los datos
    contentType: false, // No establecer ningún tipo de contenido
    success: function (response) {
      if (response == 0) {
        $("#id_estado_tiktok").val(0);
      } else {
        $("#id_estado_tiktok").val(1);

        $("#script_tiktok").val(response[0].pixel);
      }
      $("#conectar_tiktokModal").modal("show");
    },
    error: function (jqXHR, textStatus, errorThrown) {
      alert(errorThrown);
    },
  });
}

function estado_facebook() {
  return new Promise((resolve, reject) => {
    let formData = new FormData();
    formData.append("tipo", 1); // Añadir el SKU al FormData

    $.ajax({
      url: SERVERURL + "tienda/obtenerPixel",
      type: "POST", // Cambiar a POST para enviar FormData
      data: formData,
      processData: false, // No procesar los datos
      contentType: false, // No establecer ningún tipo de contenido
      dataType: "json",
      success: function (response) {
        if (response == 0) {
          $("#conectado_facebook").val(0);
        } else {
          $("#conectado_facebook").val(1);
        }
        resolve();
      },
      error: function (jqXHR, textStatus, errorThrown) {
        alert(errorThrown);
        reject(errorThrown);
      },
    });
  });
}

function estado_tiktok() {
  return new Promise((resolve, reject) => {
    let formData = new FormData();
    formData.append("tipo", 2); // Añadir el SKU al FormData

    $.ajax({
      url: SERVERURL + "tienda/obtenerPixel",
      type: "POST", // Cambiar a POST para enviar FormData
      data: formData,
      processData: false, // No procesar los datos
      contentType: false, // No establecer ningún tipo de contenido
      success: function (response) {
        if (response == 0) {
          $("#conectado_tiktok").val(0);
        } else {
          $("#conectado_tiktok").val(1);
        }
        resolve();
      },
      error: function (jqXHR, textStatus, errorThrown) {
        alert(errorThrown);
        reject(errorThrown);
      },
    });
  });
}

$(document).ready(function () {
  Promise.all([estado_facebook(), estado_tiktok()])
    .then(() => {
      var estado_conectado_facebook = $("#conectado_facebook").val();
      var estado_conectado_tiktok = $("#conectado_tiktok").val();

      //facebook
      console.log("estado: " + estado_conectado_facebook);
      if (estado_conectado_facebook == 0) {
        $("#desconectarFacebook").show();
        $("#conectarFacebook").hide();
      } else {
        $("#conectarFacebook").show();
        $("#desconectarFacebook").hide();
      }
      //tiktok
      if (estado_conectado_tiktok == 0) {
        $("#desconectarTiktok").show();
        $("#conectarTiktok").hide();
      } else {
        $("#conectarTiktok").show();
        $("#desconectarTiktok").hide();
      }
    })
    .catch((error) => {
      console.error("Error al obtener el estado de las integraciones:", error);
    });
});
