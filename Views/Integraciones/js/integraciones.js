function abrirmodal_facebook() {
  let formData = new FormData();
  formData.append("tipo", 1); // Añadir el SKU al FormData

  $.ajax({
    url: SERVERURL + "tienda/obtenerPixel",
    type: "POST", // Cambiar a POST para enviar FormData
    data: formData,
    processData: false, // No procesar los datos
    contentType: false, // No establecer ningún tipo de contenido
    success: function (response) {
      $("#script_facebook").val(response[0].pixel);
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
      $("#script_tiktok").val(response[0].pixel);
      $("#conectar_tiktokModal").modal("show");
    },
    error: function (jqXHR, textStatus, errorThrown) {
      alert(errorThrown);
    },
  });
}
