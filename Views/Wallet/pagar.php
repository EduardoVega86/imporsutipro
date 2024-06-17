<?php require_once './Views/templates/header.php'; ?>

<style>
    .table {
        border-collapse: collapse;
        width: 100%;
    }

    .table th,
    .table td {
        text-align: center;
        vertical-align: middle;
        border: 1px solid #ddd;
        /* Añadir borde a celdas */
    }

    .table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(0, 0, 0, .05);
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0, 0, 0, .075);
    }

    .table thead th {
        background-color: #171931;
        color: white;
    }

    .centered {
        text-align: center !important;
        vertical-align: middle !important;
    }
</style>

<style>
    /* reponsive de secciones */
    .left_right {
        display: flex;
        flex-direction: row;
    }

    .left {
        max-width: 37%;
    }

    .right {
        display: flex;
        flex-direction: row;
        max-width: 100%;
    }

    @media (max-width: 768px) {
        .left_right {
            flex-direction: column;
        }

        .left {
            max-width: 100%;
        }

        .right {
            flex-direction: column;
            max-width: 100%;
        }
    }

    /* diseño de iconos con botones */
    .icon-button {
        background-color: #007bff;
        /* Color de fondo azul */
        border: none;
        border-radius: 5px;
        color: white;
        /* Color del icono */
        padding: 10px 15px;
        font-size: 16px;
        cursor: pointer;
    }

    .icon-button i {
        margin-right: 5px;
    }

    .icon-button:hover {
        background-color: #0056b3;
        /* Color de fondo al pasar el ratón */
    }
</style>
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

        <div class="table-responsive">
            <!-- <table class="table table-bordered table-striped table-hover"> -->
            <table id="datatable_facturas" class="table table-striped">

                <thead>
                    <tr>
                        <th class="centered"><input type="checkbox" id="selectAll"></th>
                        <th class="centered">Factura</th>
                        <th class="centered">Detalle factura</th>
                        <th class="centered">Tienda</th>
                        <th class="centered">Venta total</th>
                        <th class="centered">Costo</th>
                        <th class="centered">Precio envio</th>
                        <th class="centered">Full Fillment</th>
                        <th class="centered">Monto a recibir</th>
                        <th class="centered">Monto cobrado</th>
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