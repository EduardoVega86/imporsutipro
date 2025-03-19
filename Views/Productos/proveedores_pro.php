<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Productos/css/marketplacepro_style.php'; ?>

<div class="custom-container-fluid mt-4">
    <div style="padding-bottom: 20px; padding-top: 20px;">
        <div class="caja p-4 shadow-sm bg-white">
            <!-- Fila : Proveedores -->
            <div class="row mb-3">
                <div class="col-12">
                    <div class="slider-proveedores-container">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" class="form-control" id="buscar_proveedor" placeholder="Buscar Proveedor...">
                        <div id="sliderProveedores" class="proveedores-grid"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div style="padding: 10px;">
        <div id="loading-indicator" style="display: none;">Cargando...</div>
    </div>
</div>

<script src="<?php echo SERVERURL ?>/Views/Productos/js/proveedores_pro.js"></script>
<?php require_once './Views/templates/footer.php'; ?>