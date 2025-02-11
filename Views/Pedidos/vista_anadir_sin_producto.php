<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Pedidos/css/anadir_sin_producto_style.php'; ?>

<div class="custom-container-fluid">
    <div class="container mt-5">
        <h2 class="text-center mb-4">Pedidos sin Productos</h2>

        <div class="d-flex justify-content-center mb-3">
            <button id="btnPropios" class="btn btn-primary me-2 active">Propios</button>
            <button id="btnBodegas" class="btn btn-secondary me-2">Bodegas</button>
            <button id="btnPrivados" class="btn btn-secondary">Privados</button>
        </div>
        <div id="bodegaContainer" class="mt-3" style="display: none;">
            <label for="selectBodega">Seleccionar Bodega:</label>
            <select id="selectBodega" class="form-control select2">
                <option value="0">Seleccione una bodega</option>
            </select>
        </div>

        <!-- Contenedor de tabla e información -->
        <div class="content-wrapper">

            <!-- Tabla con DataTable -->
            <div class="table-container">
                <table id="datatable_pedidos_sin_producto" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>SKU</th>
                            <th>Nombre</th>
                            <th>Precio Proveedor</th>
                            <th>Previo Venta</th>
                            <th>Imange</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody_pedidos_sin_producto">
                        <!-- Aquí se insertarán los datos dinámicamente desde JavaScript -->
                    </tbody>
                </table>
                <div class="text-center mt-3">
                    <button id="btnAgregarProductos" class="btn btn-success">Agregar Productos</button>
                </div>
            </div>

            <!-- Línea vertical separadora -->
            <div class="divider"></div>

            <!-- Información de la factura -->
            <div class="info-container">
                <h4 class="text-center mb-3">Detalles de la Factura</h4>
                <div class="row">
                    <div class="col-md-6">
                        <label class="info-label">Número de Factura:</label>
                    </div>
                    <div class="col-md-6">
                        <span class="info-value">#FCT-12345</span>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-6">
                        <label class="info-label">Cliente:</label>
                    </div>
                    <div class="col-md-6">
                        <span class="info-value">Juan Pérez</span>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-6">
                        <label class="info-label">Fecha:</label>
                    </div>
                    <div class="col-md-6">
                        <span class="info-value">07/02/2024</span>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-6">
                        <label class="info-label">Total:</label>
                    </div>
                    <div class="col-md-6">
                        <span class="info-value">$250.00</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo SERVERURL ?>/Views/Pedidos/js/vista_anadir_sin_producto.js"></script>
<?php require_once './Views/templates/footer.php'; ?>