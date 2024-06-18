<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Wallet/css/detalle_style.php'; ?>


<div class="custom-container-fluid">
    <div class="container mt-5" style="max-width: 1600px;">
        <h2 class="text-center mb-4">Dellate</h2>

        <div class="table-responsive">
            <!-- <table class="table table-bordered table-striped table-hover"> -->
            <table id="datatable_detalleWallet" class="table table-striped">

                <thead>
                    <tr>
                        <th class="centered">Tienda</th>
                        <th class="centered">Total Venta</th>
                        <th class="centered">Total Utilidad</th>
                        <th class="centered">Guías Pendientes</th>
                        <th class="centered">Excel</th>
                        <th class="centered">Acciones</th>
                    </tr>
                </thead>
                <tbody id="tableBody_detalleWallet"></tbody>
            </table>
        </div>
    </div>
</div>
<script src="<?php echo SERVERURL ?>/Views/Wallet/js/detalle.js"></script>
<?php require_once './Views/templates/footer.php'; ?>