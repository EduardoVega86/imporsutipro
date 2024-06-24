<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Productos/css/marketplace_style.php'; ?>

<?php require_once './Views/Productos/Modales/descripcion_marketplace.php'; ?>
<?php require_once './Views/Productos/Modales/Seleccion_productoAtributo.php'; ?>

<div class="custom-container-fluid mt-4">
    <div class="caja p-4 shadow-sm bg-white">
        <div class="row mb-3">
            <div class="col-md-2 mb-3 mb-md-0">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="favoritosSwitch">
                    <label class="form-check-label" for="favoritosSwitch">Favoritos</label>
                </div>
            </div>
            <div class="col-md-2 mb-3 mb-md-0">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="privadosSwitch">
                    <label class="form-check-label" for="privadosSwitch">Privados</label>
                </div>
            </div>
            <div class="col-md-2 mb-3 mb-md-0">
                <select id="tipo_proveedor" class="form-select me-2">
                    <option selected value="">Tipo de proveedor</option>
                    <option value="1">Proveedor 1</option>
                    <option value="2">Proveedor 2</option>
                    <option value="3">Proveedor 3</option>
                </select>
            </div>
            <div class="col-md-3 mb-3 mb-md-0">
                <label for="priceRange" class="form-label">Rango de precio:</label>
                <input type="range" class="form-range" min="0" max="1000000" step="10000" id="priceRange">
            </div>
            <div class="col-md-2 mb-3 mb-md-0">
                <select id="categoria_filtroMarketplace" class="form-select me-2">
                    <option selected value="">Categorías</option>
                    <option value="1">Categoría 1</option>
                    <option value="2">Categoría 2</option>
                    <option value="3">Categoría 3</option>
                </select>
            </div>
            <div class="col-md-1">
                <button class="btn btn-outline-secondary w-100"><i class="fa fa-sliders-h"></i> Aplicar filtros</button>
            </div>
            <div class="col-md-1">
                <button class="btn btn-warning w-100"><i class="fa fa-search"></i></button>
            </div>
        </div>
    </div>

    <div id="card-container" class="card-container">
        <!-- Tarjetas de productos se insertarán aquí -->
    </div>
    <nav aria-label="Page navigation" style="padding-bottom: 10px;">
        <ul class="pagination justify-content-center" id="pagination">
            <!-- Botones de paginación se insertarán aquí -->
        </ul>
    </nav>
</div>


<script src="<?php echo SERVERURL ?>/Views/Productos/js/marketplace.js"></script>
<script src="<?php echo SERVERURL ?>/Views/Productos/js/tablaSeleccion_Producto.js"></script>
<?php require_once './Views/templates/footer.php'; ?>