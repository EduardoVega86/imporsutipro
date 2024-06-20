<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Wallet/css/pagar_style.php'; ?>


<div class="custom-container-fluid">
    <div class="container mt-5" style="max-width: 1600px;">
        <h2 class="text-center mb-4">Pagar Wallet</h2>

        <div class="left_right gap-2">
            <div class="table-responsive left">
                <div class="card text-center">
                    <div class="card-body">
                        <img src="" id="image_tienda" width="100px" class="rounded-circle mb-3" alt="Profile Picture">
                        <h5 class="card-title"><a href="#" id="tienda_url"><span id="tienda_span"></span></a></h5>
                        <button class="btn btn-primary mb-3" id="regresar">Regresar</button>

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
                                                    <p class="mb-0">UTILIDAD GENERADA</p>
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
                <h3 style="text-align: center; padding-top:5px;">Tabla de Pagos</h3>
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
                <h3 style="text-align: center; padding-top:5px;">Tabla Historial de Pagos</h3>
                <div class="table-responsive">
                    <table id="datatable_historial_pago" class="table table-striped">

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
                        <tbody id="tableBody_historial_pago"></tbody>
                    </table>
                </div>
            </div>
        </div>
        <hr style="border: none; border-top: 2px solid #000;">
        <div class="table-responsive">
            <!-- <table class="table table-bordered table-striped table-hover"> -->
            <div class="filter-container">
                <button class="filter-btn active" data-filter="todos">Todas</button>
                <button class="filter-btn" data-filter="pendientes">Pendientes</button>
                <button class="filter-btn" data-filter="abonadas">Abonadas</button>
                <button class="filter-btn" data-filter="devoluciones">Devoluciones</button>
            </div>
            <table id="datatable_facturas" class="table table-striped">

                <thead>
                    <tr>
                        <th></th>
                        <th class="centered">Factura</th>
                        <th class="centered">Detalle factura</th>
                        <th class="centered">Estado Guia</th>
                        <th class="centered">Tienda</th>
                        <th class="centered">Venta total</th>
                        <th class="centered">Costo</th>
                        <th class="centered">Precio envio</th>
                        <th class="centered">Full Fillment</th>
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