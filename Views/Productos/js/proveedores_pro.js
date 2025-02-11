document.addEventListener("DOMContentLoaded", function () {
  let formData_filtro = new FormData();
  formData_filtro.append("nombre", "");

  const proveedoresContainer = document.getElementById("sliderProveedores");
  const loadingIndicator = document.getElementById("loading-indicator");
  const loadMoreButton = document.getElementById("load-more");
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
              console.log("Respuesta de obtener proveedores con productos:", response);
              if (Array.isArray(response)) {
                  proveedoresContainer.innerHTML = "";
                  response.forEach(proveedor => {
                      const proveedorCard = document.createElement("div");
                      proveedorCard.classList.add("proveedor-card");
                      proveedorCard.innerHTML = `
                          <div class="proveedor-logo-container">
                              <img src="${proveedor.logo || SERVERURL + 'public/img/icons/proveedor.png'}" alt="Logo">
                          </div>
                          <div class="chip-text">
                              <span class="chip-title">${proveedor.nombre_tienda.toUpperCase()}</span>
                              <span class="chip-count">${proveedor.cantidad_productos} productos</span>
                              <span class="chip-categories">${proveedor.categorias ? proveedor.categorias.split(",").slice(0, 8).join(", ") : 'Sin categorías'}</span>
                          </div>
                      `;
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
          }
      });
  }

  document.getElementById("buscar_nombre").addEventListener("input", function () {
      formData_filtro.set("nombre", this.value);
      fetchProveedores();
  });

  fetchProveedores();
});
