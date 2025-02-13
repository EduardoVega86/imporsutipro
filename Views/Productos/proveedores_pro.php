<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Productos/css/marketplacepro_style.php'; ?>

<div class="custom-container-fluid mt-4">
    <div class="container py-4">
        <div class="caja p-4 shadow-sm bg-white">
            <!-- Fila: Título y búsqueda -->
            <div class="row mb-3">
                <div class="col-12 text-center">
                    <!-- Título más grande con Bootstrap -->
                    <h1 class="display-4">Proveedores</h1>
                </div>
                <div class="col-12">
                    <!-- Grupo de input con icono de lupa -->
                    <div class="input-group mb-3">
                        <!-- Span con icono -->
                        <span class="input-group-text bg-white border-end-0" id="search-icon">
                            <i class="bi bi-search"></i>
                        </span>
                        <!-- Input con padding adecuado al icono -->
                        <input
                            type="text"
                            class="form-control border-start-0"
                            placeholder="Buscar proveedor..."
                            aria-label="Buscar proveedor..."
                            aria-describedby="search-icon"
                            id="buscar_proveedor">
                    </div>
                </div>
            </div>
            <!-- Fila: Grid de proveedores -->
            <div class="row">
                <div class="col-12">
                    <div id="sliderProveedores" class="proveedores-grid"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="container mt-3">
        <div id="loading-indicator" style="display: none;">Cargando...</div>
    </div>
</div>

<script src="<?php echo SERVERURL ?>/Views/Productos/js/proveedores_pro.js"></script>
<?php require_once './Views/templates/footer.php'; ?>