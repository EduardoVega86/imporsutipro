<?php require_once './Views/templates/header.php'; ?>

<div class="container">
    <div class="row">
        <div class="col-12">
            <h1 class="text-center">Wallet</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <table id="walletDatatable" class="table table-striped">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Fecha</th>
                        <th>Usuario</th>
                        <th>Lugar</th>
                        <th>Acción</th>
                        <th>Respuesta del servidor</th>
                    </tr>
                </thead>
                <tbody id="walletDatas">

                </tbody>
            </table>
        </div>
    </div>
    <script src="<?php echo SERVERURL; ?>/Views/Auditoria/js/auditoria.js"></script>
<?php require_once './Views/templates/footer.php'; ?>