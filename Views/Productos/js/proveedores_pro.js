document.addEventListener("DOMContentLoaded", function () {
    let formData_filtro = new FormData();
    formData_filtro.append("nombre", "");
  
    const proveedoresContainer = document.getElementById("sliderProveedores");
    const loadingIndicator = document.getElementById("loading-indicator");
    let isLoading = false;
  
    function fetchProveedores() {
      if (isLoading) return;
      isLoading = true;
      loadingIndicator.style.display = "block";
  
      $.ajax({
        url: SERVERURL + "marketplace/obtenerProveedoresConProductosCategorias",
        type: "GET",
        dataType: "json",
        success: function (response) {
          proveedoresContainer.innerHTML = "";
          if (Array.isArray(response)) {
            response.forEach((proveedor) => {
              const proveedorCard = document.createElement("div");
              proveedorCard.classList.add("proveedor-card");

              //Construimos la ruta si existe, de lo contrario usamos el ícono por defecto
              const imageSrc = proveedor.image
               ? SERVERURL + proveedor.image
               : SERVERURL + "public/img/icons/proveedor.png"

              proveedorCard.innerHTML = `
                <div class="proveedor-logo-container">
                  <img class="proveedor-logo" src="${imageSrc}" alt="Logo">
                </div>
                <div class="chip-text">
                  <!-- IMPORTANTE: la clase .chip-title es la que usaremos para filtrar -->
                  <span class="chip-title">${proveedor.nombre_tienda.toUpperCase()}</span>
                  <span class="chip-count">${proveedor.cantidad_productos} productos</span>
                  <span class="chip-categories">${
                    proveedor.categorias
                      ? proveedor.categorias.split(",").slice(0, 8).join(", ")
                      : "Sin categorías"
                  }</span>
                </div>
              `;

            // *** LÓGICA DE CLICK PARA REDIRECCIONAR A marketplace_pro CON EL FILTRO ***
              proveedorCard.addEventListener("click", function () {
                // Redireccionamos pasando ?plataforma=[ID_PROVEEDOR]
                window.location.href = SERVERURL + "Productos/marketplace_pro?plataforma=" + proveedor.id_plataforma;
              });

              proveedoresContainer.appendChild(proveedorCard);
            });
          } else {
            console.log("La respuesta de la API no es un array:", response);
          }
        },
        error: function (error) {
          console.error("Error al obtener la lista de proveedores:", error);
        },
        complete: function () {
          isLoading = false;
          loadingIndicator.style.display = "none";
        },
      });
    }
  
    // 1) Llamada inicial
    fetchProveedores();
  
    // 2) Lógica para buscar en el input (CAMBIA aquí si tu input se llama #buscar_proveedor)
    $("#buscar_proveedor").on("input", function () {
      const searchValue = $(this).val().toLowerCase().trim();
  
      // Recorremos las tarjetas .proveedor-card
      $(".proveedor-card").each(function () {
        const providerName = $(this).find(".chip-title").text().toLowerCase();
        // Si coincide con el texto buscado, mostramos la tarjeta; si no, la ocultamos
        if (providerName.includes(searchValue)) {
          $(this).show();
        } else {
          $(this).hide();
        }
      });
    });
  });
  