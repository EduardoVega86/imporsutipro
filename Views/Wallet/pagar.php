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
        max-width: 63%;
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
</style>
<div class="custom-container-fluid">
    <div class="container mt-5" style="max-width: 1600px;">
        <h2 class="text-center mb-4">Pagar Wallet</h2>

        <div class="left_right gap-2">
            <div class="table-responsive left">
            <div class="card text-center">
            <div class="card-body">
                <img src="" id="image_tienda" class="rounded-circle mb-3" alt="Profile Picture">
                <h5 class="card-title"><a href="#" id="tienda_url"><span id="tienda_span"></span></a></h5>
                <button class="btn btn-primary mb-3">Regresar</button>
                
                <div class="row text-start">
                    <div class="col-12 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-cart-fill fs-1 text-primary"></i>
                                        <div class="ms-3">
                                            <p class="mb-0">MONTO DE VENTA</p>
                                            <h3 class="text-primary">$<span id="montoVenta_wallet"></span></h3>
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
                                            <p class="mb-0">GANANCIA DE VENTAS</p>
                                            <h3 class="text-success">$<span id="gananciaVentas_wallet"></span></h3>
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
                                            <p class="mb-0">DESCUENTO DEVOLUCIONES</p>
                                            <h3 class="text-danger">$<span id="descuentoDevoluciones_wallet"></span></h3>
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
                                            <p class="mb-0">UTILIDAD GENERADA</p>
                                            <h3 class="text-primary">$<span id="utilidadGenerada_wallet"></span></h3>
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
                                            <p class="mb-0">TOTAL RETIROS</p>
                                            <h3 class="text-success">$<span id="totalRetiros_wallet"></span></h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-bank fs-1 text-primary"></i>
                                        <div class="ms-3">
                                            <p class="mb-0">SALDO EN WALLET</p>
                                            <h3 class="text-primary">$<span id="saldo_wallet"></span></h3>
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

            <div class="right gap-2 hidden" id="inventarioSection">
                
            </div>
        </div>
    </div>
</div>
<script src="<?php echo SERVERURL ?>/Views/Wallet/js/pagar.js"></script>
<?php require_once './Views/templates/footer.php'; ?>