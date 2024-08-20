<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Wallet/css/solicitudes_referidos_style.php'; ?>


<?php require_once './Views/Pedidos/Modales/informacion_plataforma.php'; ?>

<div class="custom-container-fluid">
    <div class="container mt-5" style="max-width: 1600px;">
        <h2 class="text-center mb-4">Solicitudes Referidos Datos Bancarios</h2>
        <button class="btn btn-light btn-outline-custom me-2" onclick="window.location.href='<?php echo SERVERURL; ?>wallet/solicitudes'">
            <i class="fas fa-arrow-left"></i> Volver
        </button>
        <div class="table-responsive">
            <!-- <table class="table table-bordered table-striped table-hover"> -->
            <table id="datatable_solicitudes" class="table table-striped">
                <!-- <caption>
                    DataTable.js Demo
                </caption> -->
                <thead>
                    <tr>
                        <th class="centered"></th>
                        <th class="centered">ID</th>
                        <th class="centered">Nombre tienda</th>
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

        <h2 class="text-center mb-4">Solicitudes Referidos otras formas de Pago</h2>

        <div class="table-responsive">
            <!-- <table class="table table-bordered table-striped table-hover"> -->
            <table id="datatable_otrasFormas_pago" class="table table-striped">
                <!-- <caption>
                    DataTable.js Demo
                </caption> -->
                <thead>
                    <tr>
                        <th class="centered"></th>
                        <th class="centered">ID</th>
                        <th class="centered">Nombre tienda</th>
                        <th class="centered">Fecha solicitud</th>
                        <th class="centered">Tipo</th>
                        <th class="centered">Red</th>
                        <th class="centered">Numero de cuenta</th>
                        <th class="centered">Cantidad</th>
                        <th class="centered">Acciones</th>
                    </tr>
                </thead>
                <tbody id="tableBody_otrasFormas_pago"></tbody>
            </table>
        </div>
    </div>
</div>

<script src="<?php echo SERVERURL ?>/Views/Wallet/js/solicitudes_referidos.js"></script>
<script>
    window.addEventListener("load", async () => {
        await initDataTableSolicitudes();
        await initDataTableOtrasFormasPago();
    });
</script>
<?php require_once './Views/templates/footer.php'; ?>