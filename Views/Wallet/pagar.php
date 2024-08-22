<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Wallet/css/pagar_style.php'; ?>

<?php require_once './Views/Wallet/Modales/editar_wallet.php'; ?>
<?php require_once './Views/Pedidos/Modales/detalles_factura.php'; ?>
<?php require_once './Views/Wallet/Modales/realizar_pagoModal.php'; ?>
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
        <hr style="border: none; border-top: 2px solid #000;">
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
                        <th class="centered">Numero Guia</th>
                        <th class="centered">Estado Guia</th>
                        <th class="centered">Tienda</th>
                        <th class="centered">Proveedor</th>
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
<script src="<?php echo SERVERURL ?>/Views/Wallet/js/pagar.js"></script>
<?php require_once './Views/templates/footer.php'; ?>