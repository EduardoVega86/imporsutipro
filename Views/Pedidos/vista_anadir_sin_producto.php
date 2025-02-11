<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Pedidos/css/anadir_sin_producto_style.php'; ?>

<div class="custom-container-fluid">
    <div class="container mt-5">
        <h2 class="text-center mb-4">Pedidos sin Productos</h2>

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