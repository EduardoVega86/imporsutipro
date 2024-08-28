if (CARGO == 10){
    $.ajax({
        url: "https://guias.imporsuitpro.com/Servientrega/validarGuias",
        type: "GET",
        dataType: "json",
        success: function (response) {
          
        },
        error: function (error) {
          console.error("Error al obtener la lista de bodegas:", error);
        },
      });
    
}