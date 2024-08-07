<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Tarifas/css/tarifas_style.php'; ?>
<div class="custom-container-fluid">

    <div class="container mt-5" style="max-width: 1600px;">
        <div class="left_right gap-2">


            <div class="left gap-2">
                <button class="btn btn-success" onclick="crearTarifa()">
                    <i class="fas fa-plus"></i>Crear Tarifa
                </button>
                <h3 style="text-align: center; padding-top:5px;">Bitacora de Tarifas</h3>
                <div class="table-responsive">
                    <table id="datatable_tarifas" class="table table-striped">

                        <thead>
                            <tr>
                                <th class="centered">Nombre</th>
                                <th class="centered">Descripción</th>
                                <th class="centered">Tarifa</th>
                                <th class="centered">Opciones</th>

                            </tr>
                        </thead>
                        <tbody id="tableBody_tarifas"></tbody>
                    </table>
                </div>

            </div>


            <div class="right gap-2">

                <h3 style="text-align: center; padding-top:5px;">Historial de tarifas</h3>
                <div class="table-responsive">
                    <table id="datatable_tarifas" class="table table-striped">

                        <thead>
                            <tr>
                                <th class="centered">Numero documento</th>
                                <th class="centered">Fecha</th>
                                <th class="centered">Tipo</th>
                                <th class="centered">Valor</th>
                                <th class="centered">Forma de pago</th>
                                <th class="centered">Recibo</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody_tarifas"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
<script src="<?php echo SERVERURL ?>/Views/Tarifas/js/tarifas.js"></script>

<?php require_once './Views/templates/footer.php'; ?>