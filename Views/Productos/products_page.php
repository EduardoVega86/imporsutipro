<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Productos/css/marketplace_style.php'; ?>

<div class="container mt-4">
    <div class="row">
        <!-- Imagen del producto -->
        <div class="col-md-5">
            <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="" class="d-block w-100 product-image" alt="Imagen del producto" id="imagen_principal">
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

            <!-- Miniaturas -->
            <div class="carousel-thumbnails mt-2 d-flex">
                <img src="" class="img-thumbnail mx-1 active-thumbnail" alt="Thumbnail 1" id="imagen_principalPequena">
            </div>
        </div>

        <!-- Información del producto -->
        <div class="col-md-7">
            <h5 class="product-title"><strong id="nombre_producto"></strong></h5>
            <p class="product-sku">SKU: <span id="codigo_producto"></span></p>
            <span class="badge bg-primary" id="categoria_producto">Categoría</span>

            <p class="product-type">Tipo de producto: <strong>Simple</strong></p>

            <div class="product-pricing d-flex align-items-center">
                <div class="me-3">
                    <small class="text-muted">Precio del proveedor</small>
                    <p class="text-primary fw-bold">$<span id="precio_proveedor"></span></p>
                </div>
                <div>
                    <small class="text-muted">Precio sugerido</small>
                    <p class="text-success fw-bold">$<span id="precio_sugerido"></span></p>
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
            </div>

            <hr>

            <!-- Información del proveedor -->
            <div class="provider-info d-flex align-items-center">
                <div class="provider-avatar">
                    <img src="https://via.placeholder.com/50" class="rounded-circle" alt="Proveedor">
                </div>
                <div class="ms-3">
                    <h6 class="fw-bold" id="nombre_proveedor"></h6>
                    <p class="m-0"><strong>Contacto: </strong>
                        <a href="#" id="telefono_proveedor_link">
                            <span id="telefono_proveedor"></span>
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <hr>

    <!-- Descripción del producto -->
    <div class="product-description">
        <h5>Detalles</h5>
        <p id="descripcion"></p>
    </div>
</div>

<script src="<?php echo SERVERURL; ?>/Views/Productos/js/products_page.js"></script>
<?php require_once './Views/templates/footer.php'; ?>