<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Productos/css/marketplacepro_style.php'; ?>

<?php require_once './Views/Productos/Modales/descripcion_marketplace.php'; ?>
<?php require_once './Views/Productos/Modales/Seleccion_productoAtributo.php'; ?>
<?php require_once './Views/Productos/Modales/tabla_idInventario.php'; ?>
<?php require_once './Views/Pedidos/Modales/informacion_plataforma.php'; ?>

<div class="custom-container-fluid mt-4" style="text-align: -webkit-center;">
    <div style="padding-bottom: 20px; padding-top: 20px;">
        <div class="caja p-4 shadow-sm bg-white">
            <div class="caja_filtros">

                <!-- Fila 1: Categorías y Proveedores -->
                <div class="primer_seccionFiltro">
                    <div class="slider-categorias-container">
                        <h5>Categorías</h5>
                        <div id="sliderCategorias" class="slider-categorias">
                            <!-- Chips de categorías -->
                        </div>
                    </div>
                    <div class="slider-proveedores-container">
                        <h5>Proveedores</h5>
                        <div id="sliderProveedores" class="slider-proveedores">
                            <!-- Chips de proveedores -->
                        </div>
                    </div>
                </div>

                <!-- Fila 2: Nombre + Favoritos -->
                <div class="primer_seccionFiltro">
                    <div class="col-md-4 mb-3">
                        <input
                            type="text"
                            class="form-control"
                            placeholder="Nombre"
                            id="buscar_nombre" />
                    </div>
                    <div class="boton_favoritos">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="favoritosSwitch" />
                            <label class="form-check-label" for="favoritosSwitch">Favoritos</label>
                        </div>
                    </div>
                </div>

                <!-- Fila 3: Rango de precio + botón -->
                <div class="primer_seccionFiltro">
                    <div class="col-md-2 mb-3">
                        <label for="priceRange" class="form-label">Rango de precio:</label>
                        <div id="price-range-slider"></div>
                        <div class="d-flex justify-content-between">
                            <input
                                type="text"
                                id="price-min"
                                class="form-control me-2"
                                readonly />
                            <input
                                type="text"
                                id="price-max"
                                class="form-control"
                                readonly />
                        </div>
                    </div>
                    <div class="col-md-1" style="align-content: center;">
                        <button class="btn btn-outline-secondary w-100">
                            <i class="fa fa-sliders-h"></i> Aplicar filtros
                        </button>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <div id="card-container" class="card-container">
        <!-- Tarjetas de productos se insertarán aquí -->
    </div>
    <div style="padding: 10px;">
        <div id="loading-indicator" style="display: none;">Cargando...</div>
        <button id="load-more" class="boton_mas" style="display: none;">Cargar más...</button>
    </div>

</div>


<script src="<?php echo SERVERURL ?>/Views/Productos/js/marketplace.js"></script>
<script src="<?php echo SERVERURL ?>/Views/Productos/js/tablaSeleccion_Producto.js"></script>
<?php require_once './Views/templates/footer.php'; ?>