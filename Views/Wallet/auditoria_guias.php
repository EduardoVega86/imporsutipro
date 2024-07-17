<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Wallet/css/auditoria_guias_style.php'; ?>


<div class="custom-container-fluid">
    <div class="container mt-5" style="max-width: 1600px;">
        <h2 class="text-center mb-4">Auditoria</h2>

        <div class="table-responsive">
            <!-- <table class="table table-bordered table-striped table-hover"> -->
            <div class="filter-container">
                <button class="filter-btn active" data-filter="pendientes">Pendientes</button>
                <button class="filter-btn" data-filter="abonadas">Abonadas</button>
                <button class="filter-btn" data-filter="devoluciones">Devoluciones</button>
                <button class="filter-btn" data-filter="todos">Todas</button>
            </div>
            <table id="datatable_facturas" class="table table-striped">

                <thead>
                    <tr>
                        <th></th>
                        <th class="centered">Factura</th>
                        <th class="centered">Detalle factura</th>
                        <th class="centered">Estado Guia</th>
                        <th class="centered">Tienda</th>
                        <th class="centered">Venta total</th>
                        <th class="centered">Costo</th>
                        <th class="centered">Precio envio</th>
                        <th class="centered">Fulfillment</th>
                        <th class="centered">Monto a recibir</th>
                        <th class="centered">Monto pendiente</th>
                        <th class="centered">Peso</th>
                        <th class="centered">Guia</th>
                        <th class="centered">Editar</th>
                        <th class="centered">Devolucion</th>
                        <th class="centered">Tipo envio</th>
                        <th class="centered">Ganancia</th>
                        <th class="centered">Eliminar</th>
                    </tr>
                </thead>
                <tbody id="tableBody_facturas"></tbody>
            </table>
        </div>
    </div>
</div>

<script src="<?php echo SERVERURL ?>/Views/Wallet/js/solicitudes.js"></script>
<?php require_once './Views/templates/footer.php'; ?>