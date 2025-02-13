<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Productos/css/marketplacepro_style.php'; ?>

<?php require_once './Views/Productos/Modales/descripcion_marketplace.php'; ?>
<?php require_once './Views/Productos/Modales/Seleccion_productoAtributo.php'; ?>
<?php require_once './Views/Productos/Modales/tabla_idInventario.php'; ?>
<?php require_once './Views/Pedidos/Modales/informacion_plataforma.php'; ?>

<div class="custom-container-fluid mt-4">
    <div style="padding-bottom: 20px; padding-top: 20px;">
        <div class="caja p-4 shadow-sm bg-white">
            <div class="row mb-3">
                <div class="d-flex">
                    <div class="slider-proveedores-container">
                        <div class="d-flex align-items-center">
                            <h6 class="me-2 mb-0">Proveedores</h6>
                            <button id="toggleSearch" class="btn btn-sm btn-success me-2">+</button>
                            <input type="text" class="form-control" placeholder="Buscar proveedor..." id="buscar_proveedor" style="display: none; width: 200px;" />
                        </div>
                        <div id="sliderProveedores" class="slider-proveedores mt-3"></div>
                        <div class="slider-arrow slider-arrow-right" id="sliderProveedoresRight">
                            <i class="fas fa-chevron-right"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="card-container" class="card-container">
        <!-- Tarjetas de productos -->
    </div>
    <div style="padding: 10px;">
        <div class="contenedor cargando" style="display: flex; justify-content: center;">
            <div id="loading-indicator" style="display: none;">Cargando...</div>
        </div>
        <div class="contenedor-boton-mas" style="display: flex; justify-content: center; margin: 20px 0;">
            <button id="load-more" class="boton_mas">Cargar Más</button>
        </div>
    </div>
</div>

<script src="<?php echo SERVERURL ?>/Views/Productos/js/marketplace_pro.js"></script>
<script src="<?php echo SERVERURL ?>/Views/Productos/js/tablaSeleccion_Producto.js"></script>
<?php require_once './Views/templates/footer.php'; ?>