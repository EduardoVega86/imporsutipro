<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Productos/css/marketplacepro_style.php'; ?>

<?php require_once './Views/Productos/Modales/descripcion_marketplace.php'; ?>
<?php require_once './Views/Productos/Modales/Seleccion_productoAtributo.php'; ?>
<?php require_once './Views/Productos/Modales/tabla_idInventario.php'; ?>
<?php require_once './Views/Pedidos/Modales/informacion_plataforma.php'; ?>

<div class="custom-container-fluid">
    <div style="padding-bottom: 20px; padding-top: 20px;">
        <div class="caja p-4 shadow-sm bg-white">

            <!-- Fila : Proveedores -->
            <div class="row mb-3">
                <div class="d-flex">
                    <div class="slider-proveedores-container">
                        <div class="d-flex align-items-center">
                            <h6 class="me-2 mb-0">Proveedores</h6>
                            <button id="toggleSearch" class="btn btn-sm btn-primary me-2">
                                <i class="fas fa-search"></i>
                            </button>
                            <!-- Input oculto inicialmente -->
                            <input
                                type="text"
                                class="form-control"
                                placeholder="Buscar proveedor..."
                                id="buscar_proveedor"
                                style="display: none; width: 200px;" />
                        </div>
                        <!-- Contenido deslizable -->
                        <div id="sliderProveedores" class="slider-proveedores mt-3">
                            <!-- Aquí se cargarán dinámicamente los proveedores -->
                        </div>

                        <!-- Flecha derecha -->
                        <div class="slider-arrow slider-arrow-right" id="sliderProveedoresRight">
                            <i class="fas fa-chevron-right"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Fila : Nombre + Favoritos + Vendidos -->
            <div class="row mb-3">
                <div class="col-12 col-md-4">
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-search"></i> <!-- Icono de lupa -->
                        </span>
                        <input
                            type="text"
                            class="form-control"
                            placeholder="Buscar producto..."
                            id="buscar_nombre" />
                    </div>
                </div>
                <div class="col-12 col-md-4 d-flex align-items-center mt-2 mt-md-0">
                    <div class="form-check form-switch ms-md-4">
                        <input class="form-check-input" type="checkbox" id="favoritosSwitch" />
                        <label class="form-check-label" for="favoritosSwitch">Favoritos</label>
                    </div>

                    <!-- Switch de vendidos -->
                    <div class="form-check form-switch ms-md-4">
                        <input class="form-check-input" type="checkbox" id="vendidosSwitch" />
                        <label class="form-check-label" for="vendidosSwitch">Vendidos</label>
                    </div>

                    <!-- Switch de Privados -->
                    <div class="form-check form-switch ms-md-4">
                        <input class="form-check-input" type="checkbox" id="privadosSwitch" />
                        <label class="form-check-label" for="privadosSwitch">Privados</label>
                    </div>
                </div>
                <div class="col-md-2 mb-3 mb-md-0">
                    <select id="categoria_filtroMarketplace" class="form-select me-2">
                        <option selected value="">Seleccione una categoría</option>
                    </select>
                </div>
            </div>


            <!-- Fila : Rango de precio + botón filtros -->
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
        <div class="contenedor cargando" style="display: flex; justify-content: center;">
            <div id="loading-indicator" style="display: none;">Cargando...</div>
        </div>
        <div class="contenedor-boton-mas" style="display: flex; justify-content: center; margin: 20px 0;">
            <button id="load-more" class="boton_mas">Cargar Más</button>
        </div>
        <div id="no-more-products" style="display: none; text-align: center; padding: 10px; color: gray;">
            No hay más productos disponibles.
        </div>
    </div>
</div>

<script src="<?php echo SERVERURL ?>/Views/Productos/js/marketplace_pro.js"></script>
<script src="<?php echo SERVERURL ?>/Views/Productos/js/tablaSeleccion_Producto.js"></script>
<?php require_once './Views/templates/footer.php'; ?>