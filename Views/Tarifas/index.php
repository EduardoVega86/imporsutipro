<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Tarifas/css/tarifas_style.php'; ?>
<div class="custom-container-fluid">

    <div class="container mt-5" style="max-width: 1600px;">
        <div class="left_right gap-2">
            <div class="table-responsive left">
                <div class="card text-center">
                    <div class="card-body">
                        <img src="" id="image_tienda" width="100px" class="rounded-circle mb-3" alt="Profile Picture">
                        <h5 class="card-title"><a href="#" id="tienda_url"><span id="tienda_span"></span></a></h5>
                        <button class="btn btn-primary mb-3" id="regresar"><i class="fa-solid fa-arrow-left"></i> Regresar</button>
                        <button class="btn btn-primary mb-3" data-bs-toggle="modal" onclick="abrirModal_realizarPago()"></i> Pagar</button>

                        <div class="row text-start">
                            <div class="col-12 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-cart-fill fs-1 text-primary"></i>
                                                <div class="ms-3">
                                                    <p class="mb-0">TOTAL EN VENTAS</p>
                                                    <h3 class="text-primary">$<span id="totalVentas_wallet"></span></h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-currency-dollar fs-1 text-success"></i>
                                                <div class="ms-3">
                                                    <p class="mb-0">HISTORICO DE GANACIAS</p>
                                                    <h3 class="text-success">$<span id="utilidadGenerada_wallet"></span></h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-cash-coin fs-1 text-danger"></i>
                                                <div class="ms-3">
                                                    <p class="mb-0">DESCUENTO DEVOLUCIÓN</p>
                                                    <h3 class="text-danger">$<span id="descuentoDevolucion_wallet"></span></h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-clipboard-check fs-1 text-primary"></i>
                                                <div class="ms-3">
                                                    <p class="mb-0">RETIROS ACREDITADOS</p>
                                                    <h3 class="text-primary">$<span id="retirosAcreditados_wallet"></span></h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-wallet2 fs-1 text-success"></i>
                                                <div class="ms-3">
                                                    <p class="mb-0">SALDO EN BILLETERA</p>
                                                    <h3 class="text-success">$<span id="saldoBilletera_wallet"></span></h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
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
                <h3 style="text-align: center; padding-top:5px;">Historial de acreditación</h3>
                <div class="table-responsive">
                    <table id="datatable_historial_pago" class="table table-striped">

                        <thead>
                            <tr>
                                <th class="centered">#</th>
                                <th class="centered">Tipo</th>
                                <th class="centered">Motivo</th>
                                <th class="centered">Monto</th>
                                <th class="centered">Responsable</th>
                                <th class="centered">Fecha</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody_historial_pago"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

<?php require_once './Views/templates/footer.php'; ?>