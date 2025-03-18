<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Dashboard/css/dashboard_style.php'; ?>

<!-- Agregar CDN de Boxicons -->
<link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">

<div class="custom-container-fluid">
    <?php if ($_SESSION['cargo'] != 5) { ?>
        <div class="banner_estadisticas">
            <div class="container mt-4">
                <div class="row mb-4">
                    <div class="flex-fill" style="padding: 10px;">
                        <div class="input-group">
                            <h6 class="h6">Seleccione el rango de fechas:</h6>
                            <input type="text" class="form-control" id="daterange">
                            <span class="input-group-text"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                        </div>
                    </div>
                    <!-- Card: Total Vendido -->
                    <div class="col-md-4">
                        <div class="card shadow-sm p-3 text-center" style="background: white; border-left: 5px solid #28a745;">
                            <h5 class="text-success">
                                <i class="bx bx-money"></i> Valor Total Pedidos
                                <i class="bx bx-help-circle text-muted" data-toggle="tooltip" title="Valor total de pedidos"></i>
                            </h5>
                            <h3 class="font-weight-bold" id="total_ventas"></h3>
                        </div>
                    </div>
                    <!-- Card: Total Guias Generadas -->
                    <div class="col-md-4">
                        <div class="card shadow-sm p-3 text-center" style="background: white; border-left: 5px solid #ffc107;">
                            <h5 class="text-warning">
                                <i class="bx bx-package"></i> Guías Generadas
                                <i class="bx bx-help-circle text-muted" data-toggle="tooltip" title="Cantidad total de guías generadas"></i>
                            </h5>
                            <h3 class="font-weight-bold" id="total_guias">0</h3>
                        </div>
                    </div>
                    <!-- Card: Total Recaudo -->
                    <div class="col-md-4">
                        <div class="card shadow-sm p-3 text-center" style="background: white; border-left: 5px solid #17a2b8;">
                            <h5 class="text-info">
                                <i class="bx bx-wallet"></i> Total Utilidad
                                <i class="bx bx-help-circle text-muted" data-toggle="tooltip" title="Monto total recaudado"></i>
                            </h5>
                            <h3 class="font-weight-bold" id="total_recaudo"></h3>
                        </div>
                    </div>
                    <!-- Card: Pedidos Entregados -->
                    <div class="col-md-4">
                        <div class="card shadow-sm p-3 text-center" style="background: white; border-left: 5px solid #ffc107;">
                            <h5 class="text-warning">
                                <i class="bx bx-package"></i> Pedidos Entregados
                                <i class="bx bx-help-circle text-muted" data-toggle="tooltip" title="Cantidad total de pedidos entregados"></i>
                            </h5>
                            <h3 class="font-weight-bold" id="total_entregado">0</h3>
                        </div>
                    </div>
                    <!-- Card: Total Pedidos -->
                    <!-- <div class="col-md-4">
                        <div class="card shadow-sm p-3 text-center" style="background: white; border-left: 5px solid #007bff;">
                            <h5 class="text-primary">
                                <i class="bx bx-money"></i> Total de Pedidos
                                <i class="bx bx-help-circle text-muted" data-toggle="tooltip" title="Cantidad total de pedidos"></i>
                            </h5>
                            <h3 class="font-weight-bold" id="total_pedidos">0</h3>
                        </div>
                    </div> -->
                </div>
                <!-- <div class="row mb-4"> -->
                <!-- Card: Total Recaudo -->
                <!-- <div class="col-md-4">
                        <div class="card shadow-sm p-3 text-center" style="background: white; border-left: 5px solid #17a2b8;">
                            <h5 class="text-info">
                                <i class="bx bx-wallet"></i> Total Utilidad
                                <i class="bx bx-help-circle text-muted" data-toggle="tooltip" title="Monto total recaudado"></i>
                            </h5>
                            <h3 class="font-weight-bold" id="total_recaudo"></h3>
                        </div>
                    </div> -->
                <!-- Card: Total Fletes -->
                <!-- <div class="col-md-4">
                        <div class="card shadow-sm p-3 text-center" style="background: white; border-left: 5px solid #fd7e14;">
                            <h5 style="color: #fd7e14;">
                                <i class="bx bx-dollar"></i> Total Fletes
                                <i class="bx bx-help-circle text-muted" data-toggle="tooltip" title="Cantidad total de fletes"></i>
                            </h5>
                            <h3 class="font-weight-bold" id="total_fletes">0</h3>
                        </div>
                    </div> -->
                <!-- Card: Devoluciones -->
                <!-- <div class="col-md-4">
                        <div class="card shadow-sm p-3 text-center" style="background: white; border-left: 5px solid #dc3545;">
                            <h5 class="text-danger">
                                <i class="bx bx-dollar"></i> Devoluciones
                                <i class="bx bx-help-circle text-muted" data-toggle="tooltip" title="Cantidad total de devoluciones"></i>
                            </h5>
                            <h3 class="font-weight-bold" id="devoluciones">0</h3>
                        </div>
                    </div> -->
                <!-- </div> -->
            </div>
        </div>
        <div class="tablas_estaditicas">
            <div class="content-container">
                <div class="content-box">
                    <h3 class="fs-5 fw-bold">Ventas del último Mes</h3>
                    <canvas id="salesChart"></canvas>
                </div>
                <!-- div adicional para cerrar lo que usamos -->
            </div>
            <!-- tabla ultimos pedidos -->
            <!-- <div class="content-box">
                    <h3 class="fs-5 fw-bold">Últimos pedidos</h3>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>No. Pedido</th>
                                    <th>Fecha</th>
                                    <th>Monto</th>
                                </tr>
                            </thead>
                            <tbody id="facturas-body"> -->
            <!-- Aquí se cargarán los datos dinámicamente -->
            <!-- </tbody>
                        </table>
                    </div> -->
            <!-- <button class="btn btn-primary">Ver todas las Ventas</button> -->
            <!-- </div> -->
            <!-- fin de tabla ultimos pedidos -->
            <!-- <div class="content-box" id="pie-chart-container" style="text-align: -webkit-center;">
                    <h3 class="fs-5 fw-bold">Distribución de estados en guías de envío</h3>
                    <canvas id="distributionChart" width="400" height="200"></canvas>
                </div> -->
            <!-- </div>
        </div> -->

            <!-- <div class="tablas_estaditicas">
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
                    Sección de Productos por cantidad
                    <div class="content-box1 productos" style="height: 100%;">
                        <h4>Productos por cantidad</h4>
                        <div id="products-container"></div>
                    </div> -->
            <!-- Fin Sección de Productos por cantidad -->
            <!-- </div>

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
                    </div> -->
            <!-- Sección de Ciudades con más despachos -->
            <!-- <div class="content-box1 ciudades" style="height: 100%;">
                        <h4>Ciudades con más despachos</h4>
                        <div id="ciudades-container"></div>
                    </div>
                </div>
            </div>
        </div> -->
            <!-- <div class="tablas_estaditicas">
            <div class="content-container"> -->
            <!-- Sección de productos_entrega por cantidad -->
            <!-- <div class="content-box1 productos_entrega seccion_dashboard1">
                    <h4>Productos entregados por cantidad</h4>
                    <div id="productsEntregados-container"></div>
                </div> -->
            <!-- Fin Sección de productos_entrega por cantidad -->
            <!-- Sección de Ciudades_entrega con más despachos -->
            <!-- <div class="content-box1 ciudades_entrega seccion_dashboard1">
                    <h4>Ciudades con mayores entregadas</h4>
                    <div id="ciudadesEntregadas-container"></div>
                </div>
            </div>
        </div> -->

            <!-- <div class="tablas_estaditicas">
            <div class="content-container"> -->
            <!-- Sección de productos_devolucion por cantidad -->
            <!-- <div class="content-box1 productos_devolucion seccion_dashboard1">
                    <h4>Productos devueltos por cantidad</h4>
                    <div id="productsDevolucion-container"></div>
                </div> -->

            <!-- Sección de Ciudades_devolucion con más despachos -->
            <!-- <div class="content-box1 ciudades_devolucion seccion_dashboard1">
                    <h4>Ciudades con mayores devoluciones</h4>
                    <div id="ciudadesDevolucion-container"></div>
                </div>
            </div>
        </div> -->
        <?php } ?>

        </div>

        <script src="<?php echo SERVERURL ?>/Views/Dashboard/js/dashboard2.js"></script>

        <?php require_once './Views/templates/footer.php'; ?>