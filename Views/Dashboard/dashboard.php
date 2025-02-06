<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Dashboard/css/dashboard_style.php'; ?>

<div class="custom-container-fluid">
    <?php if ($_SESSION['cargo'] != 5) { ?>
        <div class="banner_estadisticas">
            <div class="stats-container">
                <div class="flex-fill" style="padding: 10px;">
                    <h6>Seleccione el rango de fechas:</h6>
                    <div class="input-group">
                        <input type="text" class="form-control" id="daterange">
                        <span class="input-group-text"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                    </div>
                </div>

                <!-- Card 1: Total Vendido -->
                <div class="col-md-3">
                    <div class="card shadow-sm p-3 text-center" style="background: white; border-left: 5px solid #007bff;">
                        <h5 class="text-primary">
                            <i class="bx bx-dollar-circle" style="font-size: 24px;"></i> Total Vendido
                        </h5>
                        <h3 class="font-weight-bold">$ <span id="total_ventas">0.00</span></h3>
                    </div>
                </div>

                <!-- Card 2: Total Pedidos -->
                <div class="col-md-3">
                    <div class="card shadow-sm p-3 text-center" style="background: white; border-left: 5px solid #28a745;">
                        <h5 class="text-success">
                            <i class="bx bx-cart" style="font-size: 24px;"></i> Total Pedidos
                        </h5>
                        <h3 class="font-weight-bold"><span id="total_pedidos">0</span></h3>
                    </div>
                </div>

                <!-- Card 3: Total Guías Generadas -->
                <div class="col-md-3">
                    <div class="card shadow-sm p-3 text-center" style="background: white; border-left: 5px solid #ffc107;">
                        <h5 class="text-warning">
                            <i class="bx bx-truck" style="font-size: 24px;"></i> Total Guías
                        </h5>
                        <h3 class="font-weight-bold"><span id="total_guias">0</span></h3>
                    </div>
                </div>

                <!-- Card 4: Total Recaudo -->
                <div class="col-md-3">
                    <div class="card shadow-sm p-3 text-center" style="background: white; border-left: 5px solid #dc3545;">
                        <h5 class="text-danger">
                            <i class="bx bx-wallet" style="font-size: 24px;"></i> Total Recaudo
                        </h5>
                        <h3 class="font-weight-bold">$ <span id="total_recaudo">0.00</span></h3>
                    </div>
                </div>
            </div>

            <!-- Card 5: Total Fletes -->
            <div class="col-md-3">
                <div class="card shadow-sm p-3 text-center" style="background: white; border-left: 5px solid #f7971e;">
                    <h5 class="text-warning">
                        <i class="bx bx-send" style="font-size: 24px;"></i> Total Fletes
                    </h5>
                    <h3 class="font-weight-bold">$ <span id="total_fletes">0.00</span></h3>
                </div>
            </div>

            <!-- Card 6: Devoluciones -->
            <div class="col-md-3">
                <div class="card shadow-sm p-3 text-center" style="background: white; border-left: 5px solid #bdc3c7;">
                    <h5 class="text-secondary">
                        <i class="bx bx-undo" style="font-size: 24px;"></i> Devoluciones
                    </h5>
                    <h3 class="font-weight-bold"><span id="devoluciones">0</span></h3>
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
        <div class="d-flex flex-column seccion_dashboard1">
            <div class="d-flex flex-row">
                <div class="stat-box">
                    <div class="icon-container" style="background: linear-gradient(to right, #7a00d7, #26113a);">
                        <i class="fa-solid fa-ticket"></i>
                    </div>
                    <h3>$ <span id="ticket_promedio"></span></h3>
                    <p>Ticket Promedio</p>
                </div>
                <div class="stat-box">
                    <div class="icon-container" style="background: linear-gradient(to right, #0dcaf0, #1966b4);">
                        <i class="fa-solid fa-truck-front"></i>
                    </div>
                    <h3>$ <span id="flete_promedio"></span></h3>
                    <p>Flete Promedio</p>
                </div>
            </div>
            <!-- Sección de Productos por cantidad -->
            <div class="content-box1 productos" style="height: 100%;">
                <h4>Productos por cantidad</h4>
                <div id="products-container"></div>
            </div>
            <!-- Fin Sección de Productos por cantidad -->
        </div>

        <div class="d-flex flex-column seccion_dashboard1">
            <div class="d-flex flex-row">
                <div class="stat-box">
                    <div class="icon-container" style="background: linear-gradient(to right, #c61f1f, #611313);">
                        <i class="fa-solid fa-truck-arrow-right"></i>
                    </div>
                    <h3>$ <span id="devolucion_promedio"></span></h3>
                    <p>Devolucion Promedio</p>
                </div>
                <div class="stat-box">
                    <div class="icon-container" style="background: linear-gradient(to right, #0ccf4b, #1d692f);">
                        <i class="fa-solid fa-business-time"></i>
                    </div>
                    <h3> <span id="tiempo_promedio_entrega">30 Horas</span></h3>
                    <p>Tiempo Promedio Entrega</p>
                </div>
            </div>
            <!-- Sección de Ciudades con más despachos -->
            <div class="content-box1 ciudades" style="height: 100%;">
                <h4>Ciudades con más despachos</h4>
                <div id="ciudades-container"></div>
            </div>
        </div>
    </div>
</div>
<div class="tablas_estaditicas">
    <div class="content-container">
        <!-- Sección de productos_entrega por cantidad -->
        <div class="content-box1 productos_entrega seccion_dashboard1">
            <h4>Productos entregados por cantidad</h4>
            <div id="productsEntregados-container"></div>
        </div>
        <!-- Fin Sección de productos_entrega por cantidad -->
        <!-- Sección de Ciudades_entrega con más despachos -->
        <div class="content-box1 ciudades_entrega seccion_dashboard1">
            <h4>Ciudades con mayores entregadas</h4>
            <div id="ciudadesEntregadas-container"></div>
        </div>
    </div>
</div>

<div class="tablas_estaditicas">
    <div class="content-container">
        <!-- Sección de productos_devolucion por cantidad -->
        <div class="content-box1 productos_devolucion seccion_dashboard1">
            <h4>Productos devueltos por cantidad</h4>
            <div id="productsDevolucion-container"></div>
        </div>

        <!-- Sección de Ciudades_devolucion con más despachos -->
        <div class="content-box1 ciudades_devolucion seccion_dashboard1">
            <h4>Ciudades con mayores devoluciones</h4>
            <div id="ciudadesDevolucion-container"></div>
        </div>
    </div>
</div>
<?php } ?>

</div>

<script src="<?php echo SERVERURL ?>/Views/Dashboard/js/dashboard.js"></script>

<?php require_once './Views/templates/footer.php'; ?>