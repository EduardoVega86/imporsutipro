<?php require_once './Views/templates/header.php'; ?>
<!-- CSS personalizado para tu dashboard (ver más abajo) -->
<link rel="stylesheet" href="<?php echo SERVERURL ?>/Views/Dashboard/css/dashboard2_style.css">

<div class="container-fluid px-4 py-4">
    <!-- FILTRO DE FECHAS -->
    <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
        <div class="d-flex align-items-center">
            <label class="me-2 mb-0 fw-bold" for="daterange">Seleccione el rango de fechas:</label>
            <div class="input-group">
                <input type="text" class="form-control" id="daterange" style="min-width: 200px;">
                <span class="input-group-text"><i class="fa fa-calendar"></i></span>
            </div>
        </div>
    </div>

    <!-- FILA DE CARDS -->
    <div class="row g-3 mb-4">
        <!-- Card: Valor Total -->
        <div class="col-6 col-md-2">
            <div class="card h-100 shadow border-start border-4 border-success">
                <div class="card-body text-center">
                    <h6 class="text-success">
                        <i class="bx bx-money"></i> Valor Total
                        <i class="bx bx-help-circle text-muted"
                            data-toggle="tooltip" title="Valor total de pedidos"></i>
                    </h6>
                    <h3 class="fw-bold mb-0" id="total_ventas">$0.00</h3>
                </div>
            </div>
        </div>
        <!-- Card: Guías Generadas -->
        <div class="col-6 col-md-2">
            <div class="card h-100 shadow border-start border-4 border-warning">
                <div class="card-body text-center">
                    <h6 class="text-warning">
                        <i class="bx bx-package"></i> Guías Generadas
                        <i class="bx bx-help-circle text-muted"
                            data-toggle="tooltip" title="Cantidad total de guías generadas"></i>
                    </h6>
                    <h3 class="fw-bold mb-0" id="total_guias">0</h3>
                </div>
            </div>
        </div>
        <!-- Card: Productos Vendidos -->
        <div class="col-6 col-md-2">
            <div class="card h-100 shadow border-start border-4 border-primary">
                <div class="card-body text-center">
                    <h6 class="text-primary">
                        <i class="bx bx-package"></i> Productos Vendidos
                        <i class="bx bx-help-circle text-muted"
                            data-toggle="tooltip" title="Cantidad total de productos vendidos"></i>
                    </h6>
                    <h3 class="fw-bold mb-0" id="total_productos">0</h3>
                </div>
            </div>
        </div>
        <!-- Card: Guías Entregadas -->
        <div class="col-6 col-md-2">
            <div class="card h-100 shadow border-start border-4 border-success">
                <div class="card-body text-center">
                    <h6 class="text-success">
                        <i class="bx bx-package"></i> Guías Entregadas
                        <i class="bx bx-help-circle text-muted"
                            data-toggle="tooltip" title="Cantidad total de pedidos entregados"></i>
                    </h6>
                    <h3 class="fw-bold mb-0" id="total_entregado">0</h3>
                </div>
            </div>
        </div>
        <!-- Card: Utilidad Total -->
        <div class="col-6 col-md-2">
            <div class="card h-100 shadow border-start border-4 border-info">
                <div class="card-body text-center">
                    <h6 class="text-info">
                        <i class="bx bx-wallet"></i> Utilidad Total
                        <i class="bx bx-help-circle text-muted"
                            data-toggle="tooltip" title="Monto total recaudado"></i>
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

    <!-- (Opcional) Sección para OTROS datos, tablas o gráficos adicionales -->
    <!--
  <div class="row g-3">
    <div class="col-12 col-lg-6">
      <div class="card shadow">
        <div class="card-body">
          <h5 class="card-title">Distribución de Estados</h5>
          <canvas id="distributionChart" height="100"></canvas>
        </div>
      </div>
    </div>
    
    <div class="col-12 col-lg-6">
      <div class="card shadow">
        <div class="card-body">
          <h5 class="card-title">Últimos Pedidos</h5>
          <div class="table-responsive" style="max-height: 250px;">
            <table class="table table-sm table-striped">
              <thead>
                <tr>
                  <th>No. Pedido</th>
                  <th>Fecha</th>
                  <th>Monto</th>
                </tr>
              </thead>
              <tbody id="facturas-body">
                <-- Se llena vía JS -->
    </tbody>
    </table>
</div>
</div>
</div>
</div>
</div>

</div>
<footer class="text-center mt-4 py-3">
    <hr class="mb-3">
    <p class="mb-0 text-muted" style="font-size: 0.9rem;">
        2025 © Imporsuit
    </p>
</footer>

<!-- Tu JS actual del dashboard -->
<script src="<?php echo SERVERURL ?>/Views/Dashboard/js/dashboard2.js"></script>
<?php require_once './Views/templates/footer.php'; ?>