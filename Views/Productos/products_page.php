<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Productos/css/marketplace_style.php'; ?>

<div class="container">
    <h1>Detalles del Producto</h1>
    <div class="descripcion_producto">
        <div class="informacion_producto">
            <h3 style="text-decoration:underline;"><strong>Información</strong></h3>
            <p><strong>Código del Producto:</strong> <span id="codigo_producto"></span></p>
            <p><strong>Nombre Producto:</strong> <span id="nombre_producto"></span></p>
            <p><strong>Precio Proveedor:</strong> <span id="precio_proveedor"></span></p>
            <p><strong>Precio Sugerido:</strong> <span id="precio_sugerido"></span></p>
            <p><strong>Stock:</strong> <span id="stock" class="text-success"></span></p>
            <br>
            <h3 style="text-decoration:underline;"><strong>Proveedor</strong></h3>
            <p><strong>Nombre:</strong> <span id="nombre_proveedor"></span></p>
            <p><a href="https://wa.me/{telefono_proveedor}" id="link_whatsapp"><span id="telefono_proveedor"></span></a></p>
        </div>

        <div class="informacion_producto">
            <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="" class="d-block w-100 fixed-size-img" alt="Product Image 1" id="imagen_principal">
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

            <div class="carousel-thumbnails mt-2">
                <img src="" class="img-thumbnail mx-1 active-thumbnail" alt="Thumbnail 1" id="imagen_principalPequena">
            </div>
        </div>
    </div>

    <hr>
    <h3><strong>Descripción</strong></h3>
    <p><span id="descripcion"></span></p>
</div>

<script src="<?php echo SERVERURL; ?>/Views/Productos/js/products_page.js"></script>
<?php require_once './Views/templates/footer.php'; ?>