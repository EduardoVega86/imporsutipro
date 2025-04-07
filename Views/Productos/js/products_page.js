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
                const producto = data[0]; // Definir 'producto' correctamente

                // Obtener el número de teléfono del proveedor
                let telefono = producto.whatsapp ? producto.whatsapp.replace(/\D/g, '') : "";

                // Si el número comienza con 0, lo ajustamos para Ecuador (+593)
                if (telefono.startsWith("0")) {
                    telefono = "+593" + telefono.substring(1);
                } else if (!telefono.startsWith("+")) {
                    telefono = "+593" + telefono; // Si falta el código de país, lo agregamos
                }

                // Rellenar los datos en la página
                document.getElementById("imagen_proveedor").innerHTML = `<img src="${SERVERURL + producto.image}" class="proveedor-logo" alt="Logo del proveedor">`;
                document.getElementById("producto-id-inventario").textContent = producto.id_inventario;
                document.getElementById("codigo_producto").textContent = producto.codigo_producto;
                document.getElementById("nombre_producto").textContent = producto.nombre_producto;
                document.getElementById("precio_proveedor").textContent = "$" + producto.pcp;
                document.getElementById("precio_sugerido").textContent = "$" + producto.pvp;
                document.getElementById("stock").textContent = producto.saldo_stock;
                document.getElementById("nombre_proveedor").textContent = producto.contacto;

                // Actualizar el enlace de WhatsApp con el número corregido
                document.getElementById("telefono_proveedor").textContent = telefono;
                document.getElementById("telefono_proveedor_link").href = `https://wa.me/${telefono}`;

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
                const id_producto = producto.id_producto;
                const sku         = producto.codigo_producto;
                const pvp         = producto.pvp;
                const id_inventario = producto.id_inventario;
                  // Capturas el botón y le asignas el click
            
                const btnEnviar = document.getElementById("btn_enviar_cliente");
                btnEnviar.addEventListener("click", function() {
                    // Llamas a la función de arriba
                    enviar_cliente(id_producto, sku, pvp, id_inventario);
                });
                // Botón "Solicitar muestra"
                const btnMuestra = document.getElementById("btn_solicitar_muestra");
                btnMuestra.addEventListener("click", function() {
                  solicitar_muestra(id_producto, sku, pvp, id_inventario);
                });

            } else {
                alert("Producto no encontrado.");
            }
        })
        .catch(error => console.error("Error al obtener el producto:", error));

    /************************************************
     * Botón de compartir - Copiar enlace al portapapeles
     ************************************************/
    const btnCopiarEnlace = document.getElementById("btn_copiar_enlace");

    if (btnCopiarEnlace) {
        btnCopiarEnlace.addEventListener("click", function () {
            // Obtener la URL actual
            const urlProducto = window.location.href;

            // Copiar al portapapeles
            navigator.clipboard.writeText(urlProducto)
                .then(() => {
                    // Cambiar el tooltip temporalmente para indicar que se copió
                    btnCopiarEnlace.setAttribute("title", "Enlace copiado!");
                    var tooltip = new bootstrap.Tooltip(btnCopiarEnlace);
                    tooltip.show();

                    // Restaurar el tooltip original después de 2 segundos
                    setTimeout(() => {
                        btnCopiarEnlace.setAttribute("title", "Copiar enlace del producto");
                        tooltip.dispose(); // Eliminar el tooltip para que se pueda volver a mostrar
                    }, 2000);
                })
                .catch(err => console.error("Error al copiar enlace:", err));
        });

        // Inicializar el tooltip de Bootstrap
        new bootstrap.Tooltip(btnCopiarEnlace);
    }
});

// Función para obtener la URL de la imagen
function obtenerURLImagen(imagePath, serverURL) {
    if (imagePath.startsWith("http")) {
        return imagePath;
    }
    return serverURL + imagePath;
}

// Función para enviar al cliente (la misma que usas en marketplace.js)
function enviar_cliente(id, sku, pvp, id_inventario) {
    const formData = new FormData();
    formData.append("cantidad", 1);
    formData.append("precio", pvp);
    formData.append("id_producto", id);
    formData.append("sku", sku);
    formData.append("id_inventario", id_inventario);
  
    $.ajax({
      type: "POST",
      url: SERVERURL + "marketplace/agregarTmp",
      data: formData,
      processData: false,
      contentType: false,
      success: function (response2) {
        response2 = JSON.parse(response2);
  
        if (response2.status == 500) {
          Swal.fire({
            icon: "error",
            title: response2.title,
            text: response2.message,
          });
        } else if (response2.status == 200) {
          // Redirecciona a la pantalla de creación de guía
          window.location.href = SERVERURL + "Pedidos/nuevo?id_producto=" + id + "&sku=" + sku;
        }
      },
      error: function (xhr, status, error) {
        console.error("Error en la solicitud AJAX:", error);
        alert("Hubo un problema al agregar el producto temporalmente");
      },
    });
}

// Función para solicitar muestra
function solicitar_muestra(id, sku, pcp,id_inventario) {
    const formData = new FormData();
    formData.append("cantidad", 1);
    formData.append("precio", pcp); // Precio en 0 porque es muestra
    formData.append("id_producto", id);
    formData.append("sku", sku);
    formData.append("id_inventario", id_inventario);
    formData.append("muestra", 1); // Indicamos que es una muestra

    $.ajax({
        type: "POST",
        url: SERVERURL + "marketplace/agregarTmpMuestra",
        data: formData,
        processData: false,
        contentType: false,
        success: function (response2) {
            response2 = JSON.parse(response2);

            if (response2.status == 500) {
                Swal.fire({
                    icon: "error",
                    title: response2.title,
                    text: response2.message,
                });
            } else if (response2.status == 200) {
                // Redireccionamos a la pantalla de pedido con la muestra seleccionada
                window.location.href = SERVERURL + "Pedidos/nuevo?id_producto=" + id + "&sku=" + sku + "&muestra=1";
            }
        },
        error: function (xhr, status, error) {
            console.error("Error en la solicitud AJAX:", error);
            alert("Hubo un problema al agregar la muestra");
        },
    });
}
  
  