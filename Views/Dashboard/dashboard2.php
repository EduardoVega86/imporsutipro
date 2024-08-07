<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Dashboard/css/dashboard2_style.php'; ?>

<div class="custom-container-fluid">

    <div class="banner_estadisticas">
        <div class="stats-container">
            <div class="flex-fill" style="padding: 10px;">
                <h6>Seleccione el rango de fechas:</h6>
                <div class="input-group">
                    <input type="text" class="form-control" id="daterange">
                    <span class="input-group-text"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                </div>
            </div>
            <div class="d-flex flex-row">
                <div class="stat-box">
                    <h3>$ <span id="total_ventas"></span></h3>
                    <p>Total Ventas</p>
                </div>
                <div class="stat-box">
                    <h3><span id="total_pedidos"></span></h3>
                    <p>Total Pedidos</p>
                </div>
            </div>
            <div class="d-flex flex-row">
                <div class="stat-box">
                    <h3><span id="total_guias"></span></h3>
                    <p>Total Guias</p>
                </div>
                <div class="stat-box">
                    <h3><span id="total_recaudo"></span></h3>
                    <p>Total Recaudo</p>
                </div>
            </div>
            <div class="d-flex flex-row">
                <div class="stat-box">
                    <h3><span id="total_fletes"></span></h3>
                    <p>Total Fletes</p>
                </div>
                <div class="stat-box">
                    <h3><span id="devoluciones"></span></h3>
                    <p>Devoluciones</p>
                </div>
            </div>
        </div>

        <div class="slider-container">
            <img src="<?php echo BANNER_INICIO; ?>" alt="Slider">
        </div>
    </div>
    <div class="tablas_estaditicas">
        <div class="content-container">
            <div class="content-box">
                <h4>Ventas del Último Mes</h4>
                <canvas id="salesChart"></canvas>
            </div>
            <!-- tabla ultimos pedidos -->
            <div class="content-box">
                <h4>Últimos Pedidos</h4>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No. Pedido</th>
                                <th>Fecha</th>
                                <th>Monto</th>
                            </tr>
                        </thead>
                        <tbody id="facturas-body">
                            <!-- Aquí se cargarán los datos dinámicamente -->
                        </tbody>
                    </table>
                </div>
                <!-- <button class="btn btn-primary">Ver todas las Ventas</button> -->
            </div>
            <!-- fin de tabla ultimos pedidos -->
            <div class="content-box" id="pie-chart-container" style="text-align: -webkit-center;">
                <h4>Distribución de estados de guías de envío</h4>
                <canvas id="pastelChart"></canvas>
            </div>
        </div>
    </div>

    <div class="tablas_estaditicas">
        <div class="content-container">
            <div class="d-flex flex-column">
                <div class="d-flex flex-row">
                    <div class="stat-box">
                        <h3>$ <span id="total_ventas"></span></h3>
                        <p>Ticket Promedio</p>
                    </div>
                    <div class="stat-box">
                        <h3>$ <span id="total_ventas"></span></h3>
                        <p>Flete Promedio</p>
                    </div>
                </div>
                <!-- card -->
                <div class="content-box">
                    <h4>Productos por cantidad</h4>
                    <div class="product">
                        <div class="product-info">
                            <img src="path-to-icon" alt="icon" class="product-icon">
                            <span>Otros</span>
                            <span class="quantity">0 (0.00%)</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress" style="width: 0%;"></div>
                        </div>
                    </div>
                    <div class="product">
                        <div class="product-info">
                            <img src="path-to-icon" alt="icon" class="product-icon">
                            <span>Otros</span>
                            <span class="quantity">0 (0.00%)</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress" style="width: 0%;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script src="<?php echo SERVERURL ?>/Views/Dashboard/js/dashboard2.js"></script>

<?php require_once './Views/templates/footer.php'; ?>