<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Wallet/css/auditoria_guias_style.php'; ?>


<div class="custom-container-fluid">
    <div class="container mt-5" style="max-width: 1600px;">
        <h2 class="text-center mb-4">Auditoria</h2>

        <div class="table-responsive">
            <!-- <table class="table table-bordered table-striped table-hover"> -->
            <table id="datatable_solicitudes" class="table table-striped">
                <!-- <caption>
                    DataTable.js Demo
                </caption> -->
                <thead>
                    <tr>
                        <th class="centered"></th>
                        <th class="centered">Nombre</th>
                        <th class="centered">Correo</th>
                        <th class="centered">Cedula</th>
                        <th class="centered">Fecha solicitud</th>
                        <th class="centered">Telefono</th>
                        <th class="centered">Tipo de cuenta</th>
                        <th class="centered">Banco</th>
                        <th class="centered">Numero de cuenta</th>
                        <th class="centered">Cantidad</th>
                        <th class="centered">Acciones</th>
                    </tr>
                </thead>
                <tbody id="tableBody_solicitudes"></tbody>
            </table>
        </div>
    </div>
</div>

<script src="<?php echo SERVERURL ?>/Views/Wallet/js/auditoria_guias.js"></script>
<?php require_once './Views/templates/footer.php'; ?>