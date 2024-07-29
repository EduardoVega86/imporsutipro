<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Integraciones/css/integraciones_style.php'; ?>

<?php require_once './Views/Integraciones/Modales/conectar_facebook.php'; ?>
<?php require_once './Views/Integraciones/Modales/conectar_tiktok.php'; ?>

<div class="custom-container-fluid mt-4">
    <div class="row justify-content-center">
        <!-- Facebook -->
        <div class="col-md-4">
            <div class="card-custom">
                <img src="https://cdn.worldvectorlogo.com/logos/x-logo.svg" alt="Facebook Logo">
                <h5 class="card-title">Facebook</h5>
                <p class="card-text">Manten actualizadas tus campañas con nuestra api.</p>
                <p class="status disconnected">Desconectado</p>
                <button class="btn btn-primary" onclick="abrirmodal_facebook()">Conectar</button>
            </div>
        </div>

        <!-- TikTok -->
        <div class="col-md-4">
            <div class="card-custom">
                <img src="https://cdn.worldvectorlogo.com/logos/tiktok-icon-1.svg" alt="TikTok Logo">
                <h5 class="card-title">TikTok</h5>
                <p class="card-text">Manten actualizadas tus campañas con nuestra api.</p>
                <p class="status connected">Conectado</p>
                <button class="btn btn-primary" onclick="abrirmodal_tiktok()">Conectar</button>
            </div>
        </div>

        <!-- Clarity -->
        <div class="col-md-4">
            <!-- <div class="card-custom">
                <img src="https://cdn.worldvectorlogo.com/logos/microsoft-clarity.svg" alt="Clarity Logo">
                <h5 class="card-title">Clarity</h5>
                <p class="card-text">Manten actualizadas tus campañas con nuestra api.</p>
                <p class="status connected">Conectado</p>
                <button class="btn btn-primary">Conectar</button>
            </div> -->
        </div>
    </div>
</div>

<script src="<?php echo SERVERURL ?>/Views/Integraciones/js/integraciones.js"></script>
<?php require_once './Views/templates/footer.php'; ?>