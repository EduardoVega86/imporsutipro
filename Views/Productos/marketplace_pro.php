<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Productos/css/marketplacepro_style.php'; ?>

<?php require_once './Views/Productos/Modales/descripcion_marketplace.php'; ?>
<?php require_once './Views/Productos/Modales/Seleccion_productoAtributo.php'; ?>
<?php require_once './Views/Productos/Modales/tabla_idInventario.php'; ?>
<?php require_once './Views/Pedidos/Modales/informacion_plataforma.php'; ?>

<div class="custom-container-fluid mt-4">
    <div style="padding-bottom: 20px; padding-top: 20px;">
        <div class="caja p-4 shadow-sm bg-white">
            <!-- Fila 1: Categorías -->
            <div class="row mb-3">
                <div class="col-12">
                    <div class="slider-categorias-container">
                        <h5>Categorías</h5>
                        <div id="sliderCategorias" class="slider-categorias"></div>
                    </div>
                </div>
            </div>

            <!-- Fila 2: Proveedores -->
            <div class="row mb-3">
                <div class="col-12">
                    <div class="slider-proveedores-container">
                        <h5>Proveedores</h5>
                        <div id="sliderProveedores" class="slider-proveedores"></div>
                    </div>
                </div>
            </div>

            <!-- Fila 3: Nombre + Favoritos -->
            <div class="row mb-3">
                <div class="col-12 col-md-4">
                    <input
                        type="text"
                        class="form-control"
                        placeholder="Nombre"
                        id="buscar_nombre" />
                </div>
                <div class="col-12 col-md-4 d-flex align-items-center mt-2 mt-md-0">
                    <div class="form-check form-switch ms-md-4">
                        <input class="form-check-input" type="checkbox" id="favoritosSwitch" />
                        <label class="form-check-label" for="favoritosSwitch">Favoritos</label>
                    </div>
                </div>
            </div>

            <!-- Fila 4: Rango de precio + botón filtros -->
            <div class="row">
                <div class="col-12 col-md-3 mb-3">
                    <label for="priceRange" class="form-label">Rango de precio:</label>
                    <div id="price-range-slider"></div>
                    <div class="d-flex justify-content-between">
                        <input type="text" id="price-min" class="form-control me-2" readonly />
                        <input type="text" id="price-max" class="form-control" readonly />
                    </div>
                </div>
                <div class="col-12 col-md-2 d-flex align-items-end">
                    <button class="btn btn-outline-secondary w-100 mt-3 mt-md-0">
                        <i class="fa fa-sliders-h"></i> Aplicar filtros
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenedor de tarjetas de productos -->
    <div id="card-container" class="card-container">
        <!-- Tarjetas de productos se insertarán aquí -->
    </div>
    <div style="padding: 10px;">
        <div id="loading-indicator" style="display: none;">Cargando...</div>
        <button id="load-more" class="boton_mas" style="display: none;">Cargar más...</button>
    </div>
</div>


<script src="<?php echo SERVERURL ?>/Views/Productos/js/marketplace_pro.js"></script>
<script src="<?php echo SERVERURL ?>/Views/Productos/js/tablaSeleccion_Producto.js"></script>
<?php require_once './Views/templates/footer.php'; ?>