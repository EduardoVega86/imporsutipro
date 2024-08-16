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
                            <p class="texto_modal"><strong>Nombre:</strong> <span id="nombre_proveedor"></span></p>
                            <p class="texto_modal"><a href="https://wa.me/{telefono_proveedor}"><span id="telefono_proveedor"></span></a></p>
                        </div>
                        <div class="informacion_producto">
                            <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
                                <div class="carousel-inner">
                                    <!-- Imagen principal -->
                                    <div class="carousel-item active">
                                        <img src="" class="d-block w-100 fixed-size-img" alt="Product Image 1" id="imagen_principal">
                                    </div>
                                </div>
                                <!-- Controles del carrusel -->
                                <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>
                            </div>

                            <!-- Contenedor de miniaturas -->
                            <div class="carousel-thumbnails mt-2">
                                <!-- Miniatura principal -->
                                <img src="" class="img-thumbnail mx-1 active-thumbnail" alt="Thumbnail 1" data-bs-target="#productCarousel" data-bs-slide-to="0" id="imagen_principalPequena">
                            </div>
                        </div>

                    </div>
                    <hr>
                    <h3 class="mb-3" style="text-decoration:underline;"><strong>Descripción</strong></h3>
                    <p><span id="descripcion"></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>