<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Productos/css/marketplace_style.php'; ?>

<div class="container mt-4">
    <div class="row">
        <!-- Imagen del producto -->
        <div class="col-md-5">
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

        <!-- Información del producto -->
        <div class="col-md-7">
            <p class="product-id-inventario">ID: <span id="producto-id-inventario"></span></p>
            <h5 class="product-title"><strong id="nombre_producto"></strong></h5>
            <p class="product-sku">SKU: <span id="codigo_producto"></span></p>

            <div class="product-pricing d-flex align-items-center">
                <div class="me-3 text-gray-777">
                    <small>Precio del proveedor</small>
                    <p class="fw-bold"><span id="precio_proveedor"></span></p>
                </div>
                <div>
                    <small class="text-muted">Precio sugerido</small>
                    <p class="fw-bold"><span id="precio_sugerido"></span></p>
                </div>
            </div>

            <p class="product-stock">
                <strong>Stock: </strong>
                <span id="stock" class="text-success"></span>
            </p>

            <!-- Botones de acción -->
            <div class="product-actions mt-3">
                <button class="btn btn-warning me-2">Enviar al cliente</button>
                <button class="btn btn-outline-secondary">Solicitar muestra</button>

                <!-- Botón de compartir -->
                <button class="btn btn-outline-primary d-flex align-items-center" id="btn_copiar_enlace" data-bs-toggle="tooltip" data-bs-placement="top" title="Copiar enlace del producto">
                    <i class="bx bsx-share-alt me-2"></i>
                </button>
            </div>

            <hr>

            <!-- Información del proveedor -->
            <div class="provider-info d-flex align-items-center">
                <div class="proveedor-logo-container" id="imagen_proveedor">
                    <img class="proveedor-logo" src="${imageSrc}" alt="Logo">
                </div>
                <div class="ms-3">
                    <h6 class="fw-bold" id="nombre_proveedor"></h6>
                    <p class="m-0">Contacto:</p>
                    <a id="telefono_proveedor_link" href="#" target="_blank"
                        class="d-flex align-items-center text-decoration-none">
                        <i class='bx bxl-whatsapp text-success bx-sm me-2'></i>
                        <span id="telefono_proveedor" class="fw-bold text-primary"></span>
                    </a>
                </div>
            </div>
            <!-- Descripción del producto -->
            <div class="product-description">
                <h5>Descripción del Producto</h5>
                <p id="descripcion"></p>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo SERVERURL; ?>/Views/Productos/js/products_page.js"></script>
<?php require_once './Views/templates/footer.php'; ?>