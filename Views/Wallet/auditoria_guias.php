<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Wallet/css/auditoria_guias_style.php'; ?>


<div class="custom-container-fluid">
    <div class="container mt-5" style="max-width: 1600px;">
        <h2 class="text-center mb-4">Auditoria</h2>
<div class="d-flex flex-column justify-content-between">
            

            <div class="segunda_seccionFiltro">
                
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
        </div>
        <div class="table-responsive">
            <!-- <table class="table table-bordered table-striped table-hover"> -->
            <div class="filter-container">
                <button class="filter-btn active" data-filter="0">Pendientes</button>
                <button class="filter-btn" data-filter="1">Validados</button>
            </div>
            <table id="datatable_auditoria" class="table table-striped">

                <thead>
                    <tr>
                        <th class="centered">Factura</th>
                        <th class="centered">Numero Guia</th>
                        <th class="centered">COD</th>
                        <th class="centered">Monto Factura</th>
                        <th class="centered">Costo flete</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="tableBody_auditoria"></tbody>
            </table>
        </div>
    </div>
</div>

<script src="<?php echo SERVERURL ?>/Views/Wallet/js/solicitudes.js"></script>
<?php require_once './Views/templates/footer.php'; ?>