let formData_filtro;

document.addEventListener("DOMContentLoaded", function () {
  formData_filtro = new FormData();

  const initialProveedoresPerPage = 24;
  const additionalProductsPerPage = 24;
  let currentPage = 1;
  let proveedores = [];
  let displayedProveedores = new Set();


  // Filtro por texto (nombre)
  $("#buscar_nombre").on(
    "input",
    debounce(function () {
      var q = $("#buscar_nombre").val();
      formData_filtro.set("nombre", q);
      clearAndFetchProducts();
    }, 300)
  );

  // Botón “Cargar más”
  loadMoreButton.addEventListener("click", () => {
    if (!isLoading) {
      isLoading = true;
      loadingIndicator.style.display = "block";
      currentPage++;
      fetchProducts(false);
    }
  });
  
  // Cargar proveedores
  $.ajax({
    url: SERVERURL + "marketplace/obtenerProveedoresConProductosCategorias",
    type: "GET",
    dataType: "json",
    success: function (response) {
      console.log("Respuesta de obtener proveedores con productos:", response);
      if (Array.isArray(response)) {
        const sliderProveedores = document.getElementById("sliderProveedores");
        sliderProveedores.innerHTML = ""; // Limpia antes de insertar
  
        response.forEach(proveedor => {
          const chipProv = document.createElement("div");
          chipProv.classList.add("slider-chip");
          chipProv.dataset.provId = proveedor.id_plataforma;
        
          // Ruta de la imagen en el servidor
          const iconUrl = SERVERURL + "public/img/icons/proveedor.png";
        
          // Convertir string de categorías a un array limpio
          const categoriasArray = proveedor.categorias
            ? proveedor.categorias.split(",").map(cat => cat.trim()) // Separar por comas y quitar espacios
            : [];
        
          // Mostrar solo las primeras 3 categorías
          const categoriasMostradas = categoriasArray.length > 0
            ? categoriasArray.slice(0, 3).join(", ") // Tomar solo 3 y unir con comas
            : "Sin categorías";
        
          chipProv.innerHTML = `
            <div class="chip-content">
              <img src="${iconUrl}" class="icon-chip"> 
              <div class="chip-text">
                <span class="chip-title">${proveedor.nombre_tienda.toUpperCase()}</span>
                <span class="chip-count">${proveedor.cantidad_productos} productos</span>
                <span class="chip-categories">${categoriasMostradas}</span>
              </div>
            </div>
          `;
          // Toggle logic
          chipProv.addEventListener("click", function (e) {
            const clickedProvChip = e.currentTarget;
            if (clickedProvChip.classList.contains("selected")) {
              clickedProvChip.classList.remove("selected");
              formData_filtro.set("plataforma", "");
            } else {
              document
                .querySelectorAll("#sliderProveedores .slider-chip")
                .forEach((el) => el.classList.remove("selected"));
              clickedProvChip.classList.add("selected");
              formData_filtro.set("plataforma", clickedProvChip.dataset.provId);
            }
            clearAndFetchProducts();
          });
  
          sliderProveedores.appendChild(chipProv);
        });
      } else {
        console.log("La respuesta de la API no es un array:", response);
      }
    },
    error: function (error) {
      console.error("Error al obtener la lista de proveedores:", error);
    },
   }
  )
 }
);