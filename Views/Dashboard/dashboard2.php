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
                    <div class="icon-container" style="background: linear-gradient(to right, #ff7e5f, #feb47b);">
                        <i class="fa fa-dollar-sign"></i>
                    </div>
                    <h3>$ <span id="total_ventas"></span></h3>
                    <p>Total Ventas</p>
                </div>
                <div class="stat-box">
                    <div class="icon-container" style="background: linear-gradient(to right, #6a11cb, #2575fc);">
                        <i class="fa fa-shopping-cart"></i>
                    </div>
                    <h3><span id="total_pedidos"></span></h3>
                    <p>Total Pedidos</p>
                </div>
            </div>
            <div class="d-flex flex-row">
                <div class="stat-box">
                    <div class="icon-container" style="background: linear-gradient(to right, #ff512f, #dd2476);">
                        <i class="fa fa-truck"></i>
                    </div>
                    <h3><span id="total_guias"></span></h3>
                    <p>Total Guias</p>
                </div>
                <div class="stat-box">
                    <div class="icon-container" style="background: linear-gradient(to right, #43e97b, #38f9d7);">
                        <i class="fa fa-hand-holding-usd"></i>
                    </div>
                    <h3><span id="total_recaudo"></span></h3>
                    <p>Total Recaudo</p>
                </div>
            </div>
            <div class="d-flex flex-row">
                <div class="stat-box">
                    <div class="icon-container" style="background: linear-gradient(to right, #f7971e, #ffd200);">
                        <i class="fa fa-shipping-fast"></i>
                    </div>
                    <h3><span id="total_fletes"></span></h3>
                    <p>Total Fletes</p>
                </div>
                <div class="stat-box">
                    <div class="icon-container" style="background: linear-gradient(to right, #bdc3c7, #2c3e50);">
                        <i class="fa fa-undo"></i>
                    </div>
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
            <div class="d-flex flex-column" style="width: 33.33% !important;">
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
            
            <div class="d-flex flex-column" style="width: 33.33% !important;">
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
            <div class="content-box1 productos_entrega" style="width: 50%;">
                <h4>Productos entrega por cantidad</h4>
                <div id="productsEntregados-container"></div>
            </div>
            <!-- Fin Sección de productos_entrega por cantidad -->
            <!-- Sección de Ciudades_entrega con más despachos -->
            <div class="content-box1 ciudades_entrega" style="width: 50%;">
                <h4>Ciudades con mayores entregadas</h4>
                <div id="ciudadesEntregadas-container"></div>
            </div>
        </div>
    </div>

    <div class="tablas_estaditicas">
        <div class="content-container">
            <!-- Sección de productos_devolucion por cantidad -->
            <div class="content-box1 productos_devolucion" style="width: 50%;">
                <h4>Productos devolucion por cantidad</h4>
                <div id="productsDevolucion-container"></div>
            </div>

            <!-- Sección de Ciudades_devolucion con más despachos -->
            <div class="content-box1 ciudades_devolucion" style="width: 50%;">
                <h4>Ciudades con mayores devoluciones</h4>
                <div id="ciudadesDevolucion-container"></div>
            </div>
        </div>
    </div>

</div>

<script src="<?php echo SERVERURL ?>/Views/Dashboard/js/dashboard2.js"></script>

<?php require_once './Views/templates/footer.php'; ?>