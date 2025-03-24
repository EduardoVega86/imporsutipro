<?php require_once './Views/templates/header.php'; ?>
<!-- CSS personalizado para tu dashboard -->
<link rel="stylesheet" href="<?php echo SERVERURL ?>/Views/Dashboard/css/dashboard2_style.css">

<div class="container-fluid px-4 py-4">
    <!-- FILTRO DE FECHAS -->
    <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
        <div class="d-flex align-items-center">
            <!-- <label class="me-2 mb-0 fw-bold" for="daterange">Seleccione el rango de fechas:</label> -->
            <div class="input-group">
                <input type="text" class="form-control" id="daterange" style="min-width: 200px;">
                <span class="input-group-text"><i class="fa fa-calendar"></i></span>
            </div>
        </div>
    </div>

    <!-- FILA DE CARDS: 5 Tarjetas ocupando todo el ancho en pantallas grandes -->
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-5 g-3 mb-4">

        <!-- Card: Valor Total -->
        <div class="col">
            <div class="card h-100 shadow border-start border-4 border-success">
                <div class="card-body text-center">
                    <div class="mb-2">
                        <i class="bx bx-money fs-1 text-success"></i>
                    </div>
                    <h6 class="text-success">
                        Valor Total
                        <i class="bx bx-help-circle text-muted" data-bs-toggle="tooltip" title="Valor total de pedidos"></i>
                    </h6>
                    <h3 class="fw-bold mb-0" id="total_ventas">$0.00</h3>
                </div>
            </div>
        </div>

        <!-- Card: Guías Generadas -->
        <div class="col">
            <div class="card h-100 shadow border-start border-4 border-warning">
                <div class="card-body text-center">
                    <div class="mb-2">
                        <i class="bx bx-package fs-1 text-warning"></i>
                    </div>
                    <h6 class="text-warning">
                        Guías Generadas
                        <i class="bx bx-help-circle text-muted" data-bs-toggle="tooltip" title="Cantidad total de guías generadas"></i>
                    </h6>
                    <h3 class="fw-bold mb-0" id="total_guias">0</h3>
                </div>
            </div>
        </div>

        <!-- Card: Productos Vendidos -->
        <div class="col">
            <div class="card h-100 shadow border-start border-4 border-primary">
                <div class="card-body text-center">
                    <div class="mb-2">
                        <i class="bx bx-cube fs-1 text-primary"></i>
                    </div>
                    <h6 class="text-primary">
                        Productos Vendidos
                        <i class="bx bx-help-circle text-muted" data-bs-toggle="tooltip" title="Cantidad total de productos vendidos"></i>
                    </h6>
                    <h3 class="fw-bold mb-0" id="total_productos">0</h3>
                </div>
            </div>
        </div>

        <!-- Card: Guías Entregadas -->
        <div class="col">
            <div class="card h-100 shadow border-start border-4 border-success">
                <div class="card-body text-center">
                    <div class="mb-2">
                        <i class="bx bx-check-square fs-1 text-success"></i>
                    </div>
                    <h6 class="text-success">
                        Guías Entregadas
                        <i class="bx bx-help-circle text-muted" data-bs-toggle="tooltip" title="Cantidad total de pedidos entregados"></i>
                    </h6>
                    <h3 class="fw-bold mb-0" id="total_entregado">0</h3>
                </div>
            </div>
        </div>

        <!-- Card: Utilidad Total -->
        <div class="col">
            <div class="card h-100 shadow border-start border-4 border-info">
                <div class="card-body text-center">
                    <div class="mb-2">
                        <i class="bx bx-wallet fs-1 text-info"></i>
                    </div>
                    <h6 class="text-info">
                        Utilidad Total
                        <i class="bx bx-help-circle text-muted" data-bs-toggle="tooltip" title="Monto total recaudado"></i>
                    </h6>
                    <h3 class="fw-bold mb-0" id="total_recaudo">0</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- GRÁFICO DE RENDIMIENTO -->
    <div class="row g-3 mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body">
                    <h5 class="card-title">Rendimiento</h5>
                    <canvas id="salesChart" height="80"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <!-- Card: Top 5 Productos Vendidos -->
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card shadow h-100">
                <div class="card-body">
                    <h5 class="card-title">Top Productos Vendidos</h5>
                    <!-- Usamos una lista o tabla para inyectar con JS -->
                    <ul class="list-group list-group-flush" id="topProductsList"></ul>
                </div>
            </div>
        </div>

        <!-- Card: Top 5 Categorías -->
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card shadow h-100">
                <div class="card-body">
                    <h5 class="card-title">Top Categorías</h5>
                    <ul class="list-group list-group-flush" id="topCategoriesList"></ul>
                </div>
            </div>
        </div>

        <!-- Card: Top 5 Ciudades -->
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card shadow h-100">
                <div class="card-body">
                    <h5 class="card-title">Top Ciudades</h5>
                    <ul class="list-group list-group-flush" id="topCitiesList"></ul>
                </div>
            </div>
        </div>
    </div>


    <!-- Footer -->
    <footer class="text-center mt-4 py-3">
        <hr class="mb-3">
        <p class="mb-0 text-muted" style="font-size: 0.9rem;">
            2025 © Imporsuit
        </p>
    </footer>
</div>

<!-- JS para tu dashboard -->
<script src="<?php echo SERVERURL ?>/Views/Dashboard/js/dashboard2.js"></script>

<?php require_once './Views/templates/footer.php'; ?>