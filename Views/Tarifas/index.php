<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Tarifas/css/tarifas_style.php'; ?>
<div class="custom-container-fluid">

    <div class="container mt-5" style="max-width: 1600px;">
        <div class="left_right gap-2">


            <div class="left gap-2">

                <h3 style="text-align: center; padding-top:5px;">Tarifas</h3>
                <div class="table-responsive">
                    <table id="datatable_pagos" class="table table-striped">

                        <thead>
                            <tr>
                                <th class="centered">Nombre</th>
                                <th class="centered">Descripción</th>
                                <th class="centered">Tarifa</th>

                            </tr>
                        </thead>
                        <tbody id="tableBody_pagos"></tbody>
                    </table>
                </div>

            </div>


            <div class="right gap-2">

                <h3 style="text-align: center; padding-top:5px;">Historial de pagos</h3>
                <div class="table-responsive">
                    <table id="datatable_pagos" class="table table-striped">

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
                        <tbody id="tableBody_pagos"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

<?php require_once './Views/templates/footer.php'; ?>