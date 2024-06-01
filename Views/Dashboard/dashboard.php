<?php require_once './Views/templates/header.php'; ?>
<style>
    body {
        background-color: #f8f9fa;
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        height: 100vh;
        overflow-y: auto;
    }

    .container-fluid {
        width: 100%;
        padding: 20px;
    }

    .header {
        text-align: center;
        margin: 20px 0;
    }

    .stats-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-around;
        margin-bottom: 20px;
    }

    .stat-box {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        padding: 20px;
        margin: 10px;
        text-align: center;
        flex: 1 1 calc(25% - 40px);
        max-width: calc(25% - 40px);
    }

    .stat-box h3 {
        margin-top: 10px;
        font-size: 24px;
    }

    .slider-container {
        width: 100%;
        margin-bottom: 20px;
    }

    .slider-container img {
        width: 100%;
        border-radius: 10px;
    }

    .content-container {
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
    }

    .content-box {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        padding: 20px;
        margin: 10px;
        flex: 1 1 calc(50% - 40px);
        max-width: calc(50% - 40px);
    }

    .table-responsive {
        max-height: 200px;
        overflow-y: auto;
    }

    .table thead th {
        background: #343a40;
        color: #fff;
    }
</style>
</head>

<body>
    <div class="container-fluid">
        <div class="header">
            <h1>IMPOR SUIT Dashboard</h1>
        </div>

        <div class="stats-container">
            <div class="stat-box">
                <h3>5.00</h3>
                <p>Total Ventas</p>
            </div>
            <div class="stat-box">
                <h3>$ 299.98</h3>
                <p>Total Pedidos</p>
            </div>
            <div class="stat-box">
                <h3>2.00</h3>
                <p>Total Guias</p>
            </div>
            <div class="stat-box">
                <h3>0.00</h3>
                <p>Total Recaudo</p>
            </div>
            <div class="stat-box">
                <h3>0.00</h3>
                <p>Total Fletes</p>
            </div>
            <div class="stat-box">
                <h3>0.00</h3>
                <p>Devoluciones</p>
            </div>
        </div>

        <div class="slider-container">
            <img src="https://tiendas.imporsuitpro.com/imgs/logo.png" alt="Slider">
        </div>

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
                <ul class="list-group">
                    <li class="list-group-item">Inicio</li>
                    <li class="list-group-item">Plancha Pelo</li>
                    <li class="list-group-item">Catálogo</li>
                    <li class="list-group-item">Productos</li>
                    <!-- Añadir más elementos de visita según sea necesario -->
                </ul>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
    </script>
    <?php require_once './Views/templates/footer.php'; ?>