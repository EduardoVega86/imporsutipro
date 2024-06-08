<div class="modal fade" id="descripcion_productModal" tabindex="-1" aria-labelledby="descripcion_productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="descripcion_productModalLabel">Descripción del Producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="descripcion_producto">
                        <div class="informacion_producto">
                            <h3 class="mb-3" style="text-decoration:underline;"><strong>Información</strong></h3>
                            <p class="texto_modal"><strong>Código del Producto:</strong> <span id="codigo_producto"></span></p>
                            <p class="texto_modal"><strong>Nombre Producto:</strong> <span id="nombre_producto"></span></p>
                            <p class="texto_modal"><strong>Precio Proveedor:</strong> <span id="precio_proveedor"></span></p>
                            <p class="texto_modal"><strong>Precio Sugerido:</strong> <span id="precio_sugerido"></span></p>
                            <p class="texto_modal"><strong>Stock:</strong> <span id="stock" class="text-success"></span></p>
                            <br>
                            <h3 class="mb-3" style="text-decoration:underline;"><strong>Proveedor</strong></h3>
                            <p class="texto_modal"><strong>Nombre:</strong> <span id="nombre_proveedor" class="text-success"></span></p>
                            <p class="texto_modal"><a href="https://wa.me/{telefono_proveedor}"><span id="telefono_proveedor"></span></a></p>
                        </div>
                        <div class="informacion_producto">
                            <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
                                <div class="carousel-inner">
                                    <div class="carousel-item active">
                                        <img src="https://img.freepik.com/foto-gratis/colores-arremolinados-interactuan-danza-fluida-sobre-lienzo-que-muestra-tonos-vibrantes-patrones-dinamicos-que-capturan-caos-belleza-arte-abstracto_157027-2892.jpg" class="d-block w-100 fixed-size-img" alt="Product Image 1">
                                    </div>
                                    <div class="carousel-item">
                                        <img src="https://significado.com/wp-content/uploads/Imagen-Animada.jpg" class="d-block w-100 fixed-size-img" alt="Product Image 2">
                                    </div>
                                    <div class="carousel-item">
                                        <img src="https://marketing4ecommerce.net/wp-content/uploads/2024/02/imagen-generada-con-nightcafe-e1708680739301.jpg" class="d-block w-100 fixed-size-img" alt="Product Image 3">
                                    </div>
                                </div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>
                            </div>
                            <div class="carousel-thumbnails mt-2 d-flex justify-content-center">
                                <img src="https://img.freepik.com/foto-gratis/colores-arremolinados-interactuan-danza-fluida-sobre-lienzo-que-muestra-tonos-vibrantes-patrones-dinamicos-que-capturan-caos-belleza-arte-abstracto_157027-2892.jpg" class="img-thumbnail mx-1" alt="Thumbnail 1" data-bs-target="#productCarousel" data-bs-slide-to="0">
                                <img src="https://significado.com/wp-content/uploads/Imagen-Animada.jpg" class="img-thumbnail mx-1" alt="Thumbnail 2" data-bs-target="#productCarousel" data-bs-slide-to="1">
                                <img src="https://marketing4ecommerce.net/wp-content/uploads/2024/02/imagen-generada-con-nightcafe-e1708680739301.jpg" class="img-thumbnail mx-1" alt="Thumbnail 3" data-bs-target="#productCarousel" data-bs-slide-to="2">
                            </div>
                        </div>
                    </div>
                    <hr>
                    <h3 class="mb-3" style="text-decoration:underline;"><strong>Descripción</strong></h3>
                    <p><span id="descripcion" class="text-success"></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>