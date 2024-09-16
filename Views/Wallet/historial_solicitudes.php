<?php require_once './Views/templates/header.php'; ?>

<h2>
    Historial de solicitudes
</h2>
<hr>

<div class="table-responsive">
    <table id="datatable_historial" class="table table-striped table-hover">
        <thead>
            <tr>
                <th>Id</th>
                <th>Fecha</th>
                <th>Tipo de Solicitud</th>
                <th>Estado</th>
                <th>Monto</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="tableBody_historial">

        </tbody>
    </table>
</div>
<script src="<?php echo SERVERURL ?>/Views/Wallet/js/historial_solicitudes.js"></script>

<?php require_once './Views/templates/footer.php'; ?>