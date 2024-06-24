<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Productos/css/marketplace_style.php'; ?>

<?php require_once './Views/Productos/Modales/descripcion_marketplace.php'; ?>
<?php require_once './Views/Productos/Modales/Seleccion_productoAtributo.php'; ?>

<div class="custom-container-fluid mt-4">
    <div style="padding-bottom: 20px; padding-top: 20px;">
        <div class="caja p-4 shadow-sm bg-white">
            <div class="row mb-3">
                <div class="col-md-2 mb-3 mb-md-0">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="favoritosSwitch">
                        <label class="form-check-label" for="favoritosSwitch">Favoritos</label>
                    </div>
                </div>
                <div class="col-md-4 mb-3 mb-md-0">
                    <input type="text" class="form-control" placeholder="Código o Nombre">
                </div>
                <div class="col-md-2 mb-3 mb-md-0">
                    <select id="tipo_proveedor" class="form-select me-2">
                        <option selected value="">Tipo de proveedor</option>
                        <option value="1">Proveedor 1</option>
                        <option value="2">Proveedor 2</option>
                        <option value="3">Proveedor 3</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3 mb-md-0">
                    <label for="priceRange" class="form-label">Rango de precio:</label>
                    <div id="price-range-slider"></div>
                    <div class="d-flex justify-content-between">
                        <input type="text" id="price-min" class="form-control me-2" readonly>
                        <input type="text" id="price-max" class="form-control" readonly>
                    </div>
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