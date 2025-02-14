document.addEventListener("DOMContentLoaded", function () {
  // Event listener para el contenedor que activa la generación del enlace
  document
    .getElementById("trigger-container")
    .addEventListener("click", function () {
      document.getElementById("loading").style.display = "block";

      setTimeout(function () {
        document.getElementById("loading").style.display = "none";
        let url =
          "https://new.imporsuitpro.com/shopify/abandonado/" + ID_PLATAFORMA;

        $("#generador_enlace").val(url);
        document.getElementById("enlace-section").style.display = "block";
      }, 3000);
    });

  // Event listener para el botón de verificación
  document
    .getElementById("verify-button")
    .addEventListener("click", function () {
      document.getElementById("loading-below").style.display = "block";

      let intervalId = setInterval(function () {
        $.ajax({
          url: SERVERURL + "shopify/buscarEntradaAbandonado/" + ID_PLATAFORMA,
          method: "GET",
          dataType: "json",
          success: function (data) {
            if (data && data.id && data.confirmed) {
              document.getElementById("loading-below").style.display = "none";

              new bootstrap.Collapse(document.getElementById("collapseTwo"), {
                toggle: true,
              });
              clearInterval(intervalId);

              // Convertir el JSON en una cadena formateada y añadirlo al div
              document.getElementById("json-content").innerText =
                JSON.stringify(data, null, 2);

              // Mostrar el div json-informacion
              document.getElementById("json-informacion").style.display =
                "block";
            } else {
              document.getElementById("loading-below").innerHTML =
                '<div class="spinner-border" role="status"><span class="sr-only">Cargando...</span></div><div>No se pudo obtener información. Intentar nuevamente.</div>';
            }
          },
          error: function (error) {
            console.error("Error al llamar a la API:", error);
            document.getElementById("loading-below").innerHTML =
              '<div class="spinner-border" role="status"><span class="sr-only">Cargando...</span></div><div>Error al obtener información. Intentar nuevamente.</div>';
          },
        });
      }, 5000);
    });
});
