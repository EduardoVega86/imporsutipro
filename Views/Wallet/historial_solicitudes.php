<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Wallet/css/historial_solicitudes_style.php'; ?>

<main class="container">

    <h2 class="text-center pt-2">
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
                    <th>Cuenta</th>
                    <th>Usuario</th>
                    <th>Monto</th>
                </tr>
            </thead>
            <tbody id="tableBody_historial">

            </tbody>
        </table>
    </div>
</main>
<script src="<?php echo SERVERURL ?>/Views/Wallet/js/historial_solicitudes.js"></script>

<?php require_once './Views/templates/footer.php'; ?>