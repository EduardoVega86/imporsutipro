<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Pedidos/css/novedades_2_style.php'; ?>

<?php require_once './Views/Pedidos/Modales/gestionar_novedad.php'; ?>
<?php require_once './Views/Pedidos/Modales/vista_detalle_novedad.php'; ?>

<div class="custom-container-fluid">
    <div class="container mt-5" style="max-width: 1600px;">
        <h2 class="text-center mb-4">Novedades</h2>
        <div class="table-responsive">
            <!-- <table class="table table-bordered table-striped table-hover"> -->
            <table id="datatable_novedades" class="table table-striped">
                <!-- <caption>
                    DataTable.js Demo
                </caption> -->
                <thead>
                    <tr>
                        <th class="centered">Orden</th>
                        <th class="centered"># de Guia</th>
                        <th class="centered">Fecha</th>
                        <th class="centered">Transportadora</th>
                        <th class="centered">Cliente</th>
                        <th class="centered">Novedad</th>
                        <th class="centered">Codigo novedad</th>
                        <th class="centered">Solución</th>
                        <th class="centered">Tracking</th>
                    </tr>
                </thead>
                <tbody id="tableBody_novedades"></tbody>
            </table>
        </div>
    </div>
</div>

<script src="<?php echo SERVERURL ?>/Views/Pedidos/js/novedades_2.js"></script>
<?php require_once './Views/templates/footer.php'; ?>