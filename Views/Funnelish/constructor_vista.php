<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Funnelish/css/constructor_vista_style.php'; ?>

<div class="full-screen-container">
    <div class="custom-container-fluid mt-4" id="enlazar_funnel">
        <h1>Conexión a Funnelish</h1>

        <div class="img-container text-center aplicacion" id="trigger-container">
            <img src="<?php echo SERVERURL; ?>/public/img/logo_fuinnelish.png" alt="Shopify">
            <div class="name-tag"><span>Funnelish</span></div>
        </div>

        <div class="generacion_enlace" id="enlace-section" style="padding-top: 10px;">
            <label for="generador_enlace" class="form-label" style="color: white;">Enlace generado:</label>
            <div class="input-group">
                <input type="text" class="form-control" id="generador_enlace" disabled>
                <button class="btn btn-primary" type="button" onclick="generar_link()">Generar link</button>
            </div>
            <label for="infor" style="color: white;">Nota: Agregar este enlace en su webhook</label>
        </div>

        <div class="loading-animation" id="loading-below" style="display: none; margin-top: 10px;">
            <div class="spinner-border" role="status">
                <span class="sr-only">Cargando...</span>
            </div>
            <div>Cargando...</div>
        </div>
    </div>
    <div class="table-responsive" style="padding: 20px;">
        <!-- <table class="table table-bordered table-striped table-hover"> -->
        <table id="datatable_productos_shopify" width="100%" class="table table-striped">
            <thead>
                <tr>
                    <th class="text-nowrap">ID</th>
                    <th class="text-nowrap"></th>
                    <th class="text-nowrap">Nombre</th>

                </tr>
            </thead>
            <tbody id="tableBody_productos_shopify"></tbody>
        </table>
    </div>
</div>

<script src="<?php echo SERVERURL ?>/Views/Funnelish/js/constructor_vista.js"></script>
<?php require_once './Views/templates/footer.php'; ?>