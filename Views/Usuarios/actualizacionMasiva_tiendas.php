<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Usuarios/css/actualizacionMasiva_tiendas_style.php'; ?>

<div class="full-screen-container">
    <div class="custom-container-fluid mt-4" style="margin-right: 20px;">
        <h1>Despacho de guías</h1>
        <div class="form-group">
            <label for="numeroGuia">Número de Guía</label>
            <input type="text" id="numeroGuia" placeholder="Coloca el cursor aquí antes de">
        </div>
        <button id="despachoBtn" class="btn btn-success">Despacho</button>
    </div>
    <div class="guides-list-container mt-4" style="margin-right: auto; margin-left: 30px;">
        <h2>Guías Ingresadas</h2>
        <ul id="guidesList" class="list-group"></ul>
        <div style="padding-top:10px;">
            <button id="generarImpresionBtn" class="btn btn-success">Generar Impresion</button>
        </div>
    </div>
</div>

<?php require_once './Views/templates/footer.php'; ?>