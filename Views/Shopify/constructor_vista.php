<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Shopify/css/constructor_vista_style.php'; ?>

<div class="full-screen-container">
    <div class="custom-container-fluid mt-4">
        <h1>Datos Shopify</h1>
        <div class="datos_shopify" style="color:white;">
            <!-- Los datos JSON se insertan aquí -->
        </div>
    </div>
    <div class="table-responsive">
        <!-- <table class="table table-bordered table-striped table-hover"> -->
        <table id="datatable_productos_shopify" width="100%" class="table table-striped">
            <thead>
                <tr>
                    <th class="text-nowrap">ID</th>
                    <th class="text-nowrap"></th>
                    <th class="text-nowrap">Nombre</th>
                    <th class="text-nowrap">Precio</th>
                    <th class="text-nowrap">Acciones</th>
                </tr>
            </thead>
            <tbody id="tableBody_productos_shopify"></tbody>
        </table>
    </div>
</div>

<script src="<?php echo SERVERURL ?>/Views/Shopify/js/constructor_vista.js"></script>
<?php require_once './Views/templates/footer.php'; ?>