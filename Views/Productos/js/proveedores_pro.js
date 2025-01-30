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
                          <div class="proveedor-logo">
                              <img src="${proveedor.logo || SERVERURL + 'public/img/icons/proveedor.png'}" alt="Logo">
                          </div>
                          <div class="proveedor-info">
                              <h6>${proveedor.nombre_tienda.toUpperCase()}</h6>
                              <p>${proveedor.cantidad_productos} productos</p>
                              <p>${proveedor.categorias ? proveedor.categorias.split(",").slice(0, 3).join(", ") : 'Sin categorías'}</p>
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
