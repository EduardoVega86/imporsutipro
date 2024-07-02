<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Despacho/css/lista_despachos_style.php'; ?>

<div class="custom-container-fluid">
    <div class="container mt-5" style="max-width: 1600px;">
        <h2 class="text-center mb-4">Guias</h2>
        <div class="table-responsive">
            <!-- <table class="table table-bordered table-striped table-hover"> -->
            <table id="datatable_lista_despachos" class="table table-striped">
                <!-- <caption>
                    DataTable.js Demo
                </caption> -->
                <thead>
                    <tr>
                        <th class="centered"># Orden</th>
                        <th class="centered">Detalle</th>
                        <th class="centered">Cliente</th>
                        <th class="centered">Destino</th>
                        <th class="centered">Tienda</th>
                        <th class="centered">Transportadora</th>
                        <th class="centered">Estado</th>
                        <th class="centered">Impreso</th>
                        <th class="centered">Acciones</th>
                    </tr>
                </thead>
                <tbody id="tableBody_lista_despachos"></tbody>
            </table>
        </div>
    </div>
</div>
<script src="<?php echo SERVERURL ?>/Views/Despacho/js/lista_despachos.js"></script>
<?php require_once './Views/templates/footer.php'; ?>