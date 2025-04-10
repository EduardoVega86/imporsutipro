<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Wallet/css/billetera_style.php'; ?>

<?php require_once './Views/Wallet/Modales/solicitar_pago.php'; ?>
<?php require_once './Views/Wallet/Modales/cargar_comprobante.php'; ?>

<div class="custom-container-fluid">
    <div class="container mt-5" style="max-width: 1600px;">

        <div class="left_right gap-2">

            <div class="table-responsive left">
                <div class="card text-center">
                    <div class="card-body">
                        <img src="" id="image_tienda" width="100px" class="rounded-circle mb-3" alt="Profile Picture">
                        <h5 class="card-title"><a href="#" id="tienda_url"><span id="tienda_span"></span></a></h5>
                        <button type="button" class="btn btn-outline-primary mb-3" data-bs-toggle="modal" data-bs-target="#SoliciModal" onclick="enviarCodigo()">
                            <!-- <button type="button" class="btn btn-outline-primary mb-3" data-bs-toggle="modal" data-bs-target="#solicitar_pagoModal"> -->
                            Solicitar Pago
                        </button>

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
                                                    <p class="mb-0">HISTORICO DE GANANCIAS</p>
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
                        <div id="solicitud_realizada" style="display: none;">
                            <h4 class="alert alert-success">Tiene una solicitud pendiente con un saldo de $<span id="valor_solicitud"></span></h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="right gap-2">

                <h3 style="text-align: center; padding-top:5px;">Historial de Pagos</h3>
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
        <hr style="border: none; border-top: 2px solid #000;">
        <div class="table-responsive">
            <!-- <table class="table table-bordered table-striped table-hover"> -->
            <div class="filter-container">
                <button class="filter-btn" data-filter="abonadas">Guías Pagadas</button>

            </div>

            <div class="segunda_seccionFiltro">
                <div style="width: 100%;">
                    <label for="inputPassword3" class="col-sm-2 col-form-label">Estado</label>
                    <div>
                        <select name="estado_q" class="form-control" id="estado_q">
                            <option value="">Seleccione Estado</option>
                            <option value="generada">Generada/ Por Recolectar</option>
                            <option value="en_transito">En transito / Procesamiento / En ruta</option>
                            <option value="entregada">Entregada</option>
                            <option value="novedad">Novedad</option>
                            <option value="devolucion">Devolución</option>
                        </select>
                    </div>
                </div>
                <div style="width: 100%;">
                    <label for="inputPassword3" class="col-sm-2 col-form-label">Transportadora</label>
                    <div>
                        <select name="transporte" id="transporte" class="form-control">
                            <option value=""> Seleccione Transportadora</option>
                            <option value="LAAR">Laar</option>
                            <option value="SPEED">Speed</option>
                            <option value="SERVIENTREGA">Servientrega</option>
                            <option value="GINTRACOM">Gintracom</option>
                        </select>
                    </div>
                </div>
            </div>

            <table id="datatable_facturas" class="table table-striped">

                <thead>
                    <tr>
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
                        <th class="centered">Guia</th>
                    </tr>
                </thead>
                <tbody id="tableBody_facturas"></tbody>
            </table>
        </div>
    </div>
</div>
<script>
    const tienda = "<?php echo ENLACE; ?>";
</script>
<script src="<?php echo SERVERURL ?>/Views/Wallet/js/billetera.js"></script>
<?php require_once './Views/templates/footer.php'; ?>