<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Dashboard/css/dashboard_style.php'; ?>

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
                        <tbody>
                            <tr>
                                <td>COT-005757</td>
                                <td>01-06-2024</td>
                                <td>$44.99</td>
                            </tr>
                            <tr>
                                <td>COT-005756</td>
                                <td>01-06-2024</td>
                                <td>$45.00</td>
                            </tr>
                            <tr>
                                <td>COT-005755</td>
                                <td>01-06-2024</td>
                                <td>$49.99</td>
                            </tr>
                            <tr>
                                <td>COT-005754</td>
                                <td>01-06-2024</td>
                                <td>$80.00</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <button class="btn btn-primary">Ver todas las Ventas</button>
            </div>
            <div class="content-box">
                <h4>Ventas del Último Mes</h4>
                <canvas id="salesChart"></canvas>
            </div>
            <div class="content-box">
                <h4>Visitas</h4>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Pagina</th>
                            <th>Visitas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Catalogo</td>
                            <td>20000</td>
                        </tr>
                        <tr>
                            <td>Productos</td>
                            <td>20000</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<script src="<?php echo SERVERURL ?>/Views/Dashboard/js/dashboard.js"></script>
<script>
    var ctx = document.getElementById('salesChart').getContext('2d');
    var salesChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['1 May', '2 May', '3 May', '4 May', '5 May', '6 May', '7 May', '8 May', '9 May', '10 May', '11 May', '12 May', '13 May', '14 May', '15 May', '16 May', '17 May', '18 May', '19 May', '20 May', '21 May', '22 May', '23 May', '24 May', '25 May', '26 May', '27 May', '28 May', '29 May', '30 May', '31 May'],
            datasets: [{
                label: 'Ventas este mes',
                data: [0, 1000, 500, 1200, 1800, 2300, 300, 600, 800, 500, 700, 2000, 100, 0, 300, 500, 400, 300, 200, 1000, 200, 300, 500, 600, 300, 500, 700, 800, 600, 1000, 0],
                backgroundColor: 'rgba(0, 123, 255, 0.5)',
                borderColor: 'rgba(0, 123, 255, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
<?php require_once './Views/templates/footer.php'; ?>