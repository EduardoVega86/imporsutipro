document
  .getElementById("trigger-container")
  .addEventListener("click", function () {
    // Mostrar la animación de carga de boton shopify
    document.getElementById("loading").style.display = "block";

    // Esperar 5 segundos y luego mostrar la sección de enlace generado
    setTimeout(function () {
      document.getElementById("loading").style.display = "none";
      $.ajax({
        url: SERVERURL + "shopify/generarEnlace",
        type: "GET",
        dataType: "json",
        success: function (response) {
          $("#generador_enlace").val(response.url_imporsuit);
          document.getElementById("enlace-section").style.display = "block";
        },
        error: function (error) {
          console.error("Error al obtener la lista de bodegas:", error);
        },
      });
    }, 3000);
    // final del cargar shopify

    //inicio cargar de boton verificar
    document
      .getElementById("verify-button")
      .addEventListener("click", function () {
        // Mostrar la animación de carga debajo del input
        document.getElementById("loading-below").style.display = "block";

        // Iniciar el bucle de verificación
        let intervalId = setInterval(function () {
          $.ajax({
            url: SERVERURL + "shopify/ultimoJson",
            method: "GET",
            dataType: "json",
            success: function (data) {
              if (data && data.id && data.confirmed) {
                // Ocultar la animación de carga debajo del input
                document.getElementById("loading-below").style.display = "none";

                // Llenar los selects con las claves del JSON
                fillSelectsWithKeys(data);

                // Abrir el siguiente acordeón
                var collapseTwo = new bootstrap.Collapse(
                  document.getElementById("collapseTwo"),
                  {
                    toggle: true,
                  }
                );

                // Terminar el intervalo
                clearInterval(intervalId);
              } else {
                // La condición no se cumple, mantener la animación de carga o mostrar un mensaje de error
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

    function fillSelectsWithKeys(data) {
      clearSelects();

      for (let key in data) {
        if (data.hasOwnProperty(key)) {
          addOptionToSelect("#select-nombres", key);
        }
      }

      // Inicializar select2 en todos los selects
      $(".form-select").select2();
    }

    function clearSelects() {
      $("#select-nombres")
        .empty()
        .append('<option value="" selected>-- Seleccione --</option>');
    }

    function addOptionToSelect(selectId, key) {
      const select = document.querySelector(selectId);
      const option = document.createElement("option");
      option.value = key;
      option.text = key;
      select.appendChild(option);
    }
    // final cargar de boton veritifcar
  });
