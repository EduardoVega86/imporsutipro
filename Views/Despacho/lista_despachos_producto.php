<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Despacho/css/lista_despachos_style.php'; ?>

<div class="custom-container-fluid">
    <div class="container mt-5" style="max-width: 1600px;">
        <h2 class="text-center mb-4">Lista Despacho Productos</h2>
        <div>
            <div class="selects">
            
                <div class="form-group" id="bodega-field">
                    <label for="bodega">Bodega:</label>
                    <select class="form-select" id="select_bodega">
                        <option value="0" selected> Selecciona Bodega</option>
                    </select>
                </div>
            </div>
            <button id="generarDespachoBtn" class="btn btn-success">Generar Salida de Mercaderia</button>
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
                        <th class="centered">Usuario</th>
                        <th class="centered">Bodega</th>
                        <th class="centered">Fecha y hora</th>
                        <th class="centered">Ver PDF</th>
                    </tr>
                </thead>
                <tbody id="tableBody_lista_despachos"></tbody>
            </table>
        </div>
    </div>
</div>
<script src="<?php echo SERVERURL ?>/Views/Despacho/js/lista_despachos_producto.js"></script>
<?php require_once './Views/templates/footer.php'; ?>