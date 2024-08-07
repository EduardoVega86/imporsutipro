<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Productos/css/marketplace_style.php'; ?>

<?php require_once './Views/Productos/Modales/descripcion_marketplace.php'; ?>
<?php require_once './Views/Productos/Modales/Seleccion_productoAtributo.php'; ?>
<?php require_once './Views/Productos/Modales/tabla_idInventario.php'; ?>
<?php require_once './Views/Pedidos/Modales/informacion_plataforma.php'; ?>

<div class="custom-container-fluid mt-4">
    <div style="padding-bottom: 20px; padding-top: 20px;">
        <div class="caja p-4 shadow-sm bg-white">
            <div class="caja_filtros">
                <div class="primer_seccionFiltro">
                    <div class="col-md-4 mb-3 mb-md-0">
                        <input type="text" class="form-control" placeholder="Nombre" id="buscar_nombre">
                    </div>
                    <div class="col-md-2 mb-3 mb-md-0">
                        <select id="proveedor_filtroMarketplace" class="form-select me-2">
                            <option selected value="">Seleccione un proveedor</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3 mb-md-0">
                        <select id="categoria_filtroMarketplace" class="form-select me-2">
                            <option selected value="">Seleccione una categoría</option>
                        </select>
                    </div>
                    <div class="boton_favoritos">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="favoritosSwitch">
                            <label class="form-check-label" for="favoritosSwitch">Favoritos</label>
                        </div>
                    </div>
                </div>
                <div class="primer_seccionFiltro">
                    <div class="col-md-2 mb-3 mb-md-0">
                        <label for="priceRange" class="form-label">Rango de precio:</label>
                        <div id="price-range-slider"></div>
                        <div class="d-flex justify-content-between">
                            <input type="text" id="price-min" class="form-control me-2" readonly>
                            <input type="text" id="price-max" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="col-md-1" style="align-content: center;">
                        <button class="btn btn-outline-secondary w-100"><i class="fa fa-sliders-h"></i> Aplicar filtros</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="card-container" class="card-container">
        <!-- Tarjetas de productos se insertarán aquí -->
    </div>
    
</div>


<script src="<?php echo SERVERURL ?>/Views/Productos/js/marketplace_privado.js"></script>
<script src="<?php echo SERVERURL ?>/Views/Productos/js/tablaSeleccion_Producto.js"></script>
<?php require_once './Views/templates/footer.php'; ?>