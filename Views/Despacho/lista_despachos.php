<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Despacho/css/lista_despachos_style.php'; ?>

<div class="custom-container-fluid">
    <div class="container mt-5" style="max-width: 1600px;">
        <h2 class="text-center mb-4">Guias</h2>
        <div>
            <div class="d-flex flex-column w-100">
                <div class="form-group">
                    <label for="transportadora">Transportadora:</label>
                    <select class="form-select" id="transportadora">
                        <option value=""> Seleccione Transportadora</option>
                        <option value="1">Laar</option>
                        <option value="4">Speed</option>
                        <option value="2">Servientrega</option>
                        <option value="3">Gintracom</option>
                    </select>
                </div>
            </div>
            <button id="generarDespachoBtn" class="btn btn-success">Generar despacho</button>
        </div>
        <div class="table-responsive">
            <!-- <table class="table table-bordered table-striped table-hover"> -->
            <table id="datatable_lista_despachos" class="table table-striped">
                <!-- <caption>
                    DataTable.js Demo
                </caption> -->
                <thead>
                    <tr>
                        <th class="centered">ID despacho</th>
                        <th class="centered">ID usuario</th>
                        <th class="centered">ID plataforma</th>
                        <th class="centered">ID transportadora</th>
                        <th class="centered">ID bodega</th>
                        <th class="centered">Fecha y hora</th>
                    </tr>
                </thead>
                <tbody id="tableBody_lista_despachos"></tbody>
            </table>
        </div>
    </div>
</div>
<script src="<?php echo SERVERURL ?>/Views/Despacho/js/lista_despachos.js"></script>
<?php require_once './Views/templates/footer.php'; ?>