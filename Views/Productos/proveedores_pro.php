<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Productos/css/marketplacepro_style.php'; ?>


<div class="custom-container-fluid mt-4">
    <div style="padding-bottom: 20px; padding-top: 20px;">
        <div class="caja p-4 shadow-sm bg-white">

            <!-- Fila : Proveedores -->
            <div class="row mb-3">
                <div class="col-12">
                    <div class="slider-proveedores-container">
                        <h6>Proveedores</h6>
                        <div id="sliderProveedores" class="slider-proveedores"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Fila : Nombre + Favoritos -->
        <div class="row mb-3">
            <div class="col-12 col-md-4">
                <input
                    type="text"
                    class="form-control"
                    placeholder="Nombre de proveedor a encontrar.."
                    id="buscar_nombre" />
            </div>
        </div>
    </div>

    <div style="padding: 10px;">
        <div id="loading-indicator" style="display: none;">Cargando...</div>
        <button id="load-more" class="boton_mas" style="display: none;">Cargar más...</button>
    </div>
</div>


<script src="<?php echo SERVERURL ?>/Views/Productos/js/proveedores_pro.js"></script>
<?php require_once './Views/templates/footer.php'; ?>