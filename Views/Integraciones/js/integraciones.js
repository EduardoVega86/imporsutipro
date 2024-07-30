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
      if (response == 0){
        $("#id_estado_facebook").val(0);
      }else{
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
        if (response == 0){
            $("#id_estado_tiktok").val(0);
          }else{
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
