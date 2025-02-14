document.addEventListener("DOMContentLoaded", function () {
    // Obtener el ID del producto desde la URL
    const urlParams = new URLSearchParams(window.location.search);
    const id = urlParams.get("id");

    if (!id) {
        alert("ID de producto no encontrado.");
        return;
    }

    // Hacer la solicitud AJAX para obtener los detalles del producto
    fetch(SERVERURL + "marketplace/obtener_producto/" + id)
        .then(response => response.json())
        .then(data => {
            if (data.length > 0) {
                const producto = data[0];

                // Rellenar los datos en la página

                document.getElementById("imagen_proveedor").textContent = producto.image;
                document.getElementById("producto-id-inventario").textContent = producto.id_inventario;
                document.getElementById("codigo_producto").textContent = producto.codigo_producto;
                document.getElementById("nombre_producto").textContent = producto.nombre_producto;
                document.getElementById("precio_proveedor").textContent = "$" + producto.pcp;
                document.getElementById("precio_sugerido").textContent = "$" + producto.pvp;
                document.getElementById("stock").textContent = producto.saldo_stock;
                document.getElementById("nombre_proveedor").textContent = producto.contacto;
                document.getElementById("telefono_proveedor").textContent = producto.whatsapp;
                document.getElementById("link_whatsapp").href = "https://wa.me/" + producto.whatsapp;
                document.getElementById("descripcion").textContent = producto.descripcion_producto;

                // Cargar la imagen principal
                let imagenUrl = obtenerURLImagen(producto.image_path, SERVERURL);
                document.getElementById("imagen_principal").src = imagenUrl;
                document.getElementById("imagen_principalPequena").src = imagenUrl;

                // Obtener imágenes adicionales
                let formData = new FormData();
                formData.append("id_producto", id);

                fetch(SERVERURL + "Productos/listar_imagenAdicional_productos", {
                    method: "POST",
                    body: formData
                })
                .then(response => response.json())
                .then(imagenes => {
                    if (imagenes.length > 0) {
                        const carouselInner = document.querySelector(".carousel-inner");
                        const thumbnails = document.querySelector(".carousel-thumbnails");

                        imagenes.forEach((imgData, index) => {
                            let imgURL = obtenerURLImagen(imgData.url, SERVERURL);
                            let activeClass = index === 0 ? "active" : "";

                            carouselInner.innerHTML += `
                                <div class="carousel-item ${activeClass}">
                                    <img src="${imgURL}" class="d-block w-100 fixed-size-img" alt="Product Image ${index + 2}">
                                </div>
                            `;

                            thumbnails.innerHTML += `
                                <img src="${imgURL}" class="img-thumbnail mx-1" alt="Thumbnail ${index + 2}" data-bs-target="#productCarousel" data-bs-slide-to="${index + 1}">
                            `;
                        });
                    }
                })
                .catch(error => console.error("Error al obtener imágenes adicionales:", error));

            } else {
                alert("Producto no encontrado.");
            }
        })
        .catch(error => console.error("Error al obtener el producto:", error));
});

// Función para obtener la URL de la imagen
function obtenerURLImagen(imagePath, serverURL) {
    if (imagePath.startsWith("http")) {
        return imagePath;
    }
    return serverURL + imagePath;
}
