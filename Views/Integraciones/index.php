<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Integraciones/css/integraciones_style.php'; ?>

<?php require_once './Views/Integraciones/Modales/conectar_facebook.php'; ?>
<?php require_once './Views/Integraciones/Modales/conectar_tiktok.php'; ?>

<div class="custom-container-fluid mt-4">
    <div class="row justify-content-center">
        <!-- Facebook -->
        <div class="col-md-4">
            <div class="card-custom">
                <img src="<?php echo SERVERURL; ?>/public/img/facebook.png" alt="Facebook Logo">
                <h5 class="card-title">Facebook</h5>
                <p class="card-text">Manten actualizadas tus campañas con nuestra api.</p>
                <input type="hidden" id="conectado_facebook" name="conectado_facebook">
                <div id="conectarFacebook" style="display: none;">
                    <p class="status connected">Conectado</p>
                </div>
                <div id="desconectarFacebook" style="display: none;">
                    <p class="status disconnected">Desconectado</p>
                </div>

                <button class="btn btn-primary" onclick="abrirmodal_facebook()">Conectar</button>
            </div>
        </div>

        <!-- TikTok -->
        <div class="col-md-4">
            <div class="card-custom">
                <img src="<?php echo SERVERURL; ?>/public/img/tiktok.png" alt="TikTok Logo">
                <h5 class="card-title">TikTok</h5>
                <p class="card-text">Manten actualizadas tus campañas con nuestra api.</p>
                <input type="hidden" id="conectado_tiktok" name="conectado_tiktok">

                <div id="conectarTiktok" style="display: none;">
                    <p class="status connected">Conectado</p>
                </div>
                <div id="desconectarTiktok" style="display: none;">
                    <p class="status disconnected">Desconectado</p>
                </div>

                <button class="btn btn-primary" onclick="abrirmodal_tiktok()">Conectar</button>
            </div>
        </div>

        <!-- Shopify -->
        <div class="col-md-4">
            <div class="card-custom text-center p-4 shadow-lg" style="border-radius: 12px;">
                <img src="<?php echo SERVERURL; ?>public/img/logo_shopify.png" alt="Shopify Logo"
                    class="img-fluid mb-3" style="max-width: 80px;">
                <h5 class="card-title fw-bold">Shopify</h5>
                <p class="card-text" style="font-size: 14px; color: #666;">Realiza la conexión de tu cuenta de Shopify con nuestro sistema Imporsuit.</p>

                <div class="d-flex justify-content-center gap-3 mt-3">
                    <a href="<?php echo SERVERURL; ?>shopify/constructor" class="icon-btn" data-tooltip="Conectar">
                        <i class="fas fa-plug"></i>
                    </a>
                    <a href="<?php echo SERVERURL; ?>shopify/constructor_vista" class="icon-btn" data-tooltip="Productos conectados">
                        <i class="fas fa-box"></i>
                    </a>
                    <a href="<?php echo SERVERURL; ?>shopify/constructor_abandonados" class="icon-btn" data-tooltip="Conectar abandonados">
                        <i class="fas fa-shopping-cart"></i>
                    </a>
                    <a href="<?php echo SERVERURL; ?>Pedidos/historial_abandonados" class="icon-btn" data-tooltip="Historial abandonados">
                        <i class="fas fa-history"></i>
                    </a>
                </div>
            </div>
        </div>

    </div>
    <div class="row justify-content-center">
        <!-- Funnelish -->
        <div class="col-md-4">
            <div class="card-custom">
                <img src="<?php echo SERVERURL; ?>public/img/logo_fuinnelish.png" alt="Funnelish Logo">
                <h5 class="card-title">Funnelish</h5>
                <p class="card-text">Realiza la conexion de tu cuenta de funnelish con nuestro sistema Imporsuit.</p>
                <!-- <input type="hidden" id="conectado_facebook" name="conectado_facebook">
                <div id="conectarFacebook" style="display: none;">
                    <p class="status connected">Conectado</p>
                </div>
                <div id="desconectarFacebook" style="display: none;">
                    <p class="status disconnected">Desconectado</p>
                </div> -->

                <button class="btn btn-primary" onclick="window.location.href=SERVERURL+'Productos/marketplace'">Markerplace</button>
                <button class="btn btn-primary" onclick="window.location.href=SERVERURL+'funnelish/constructor_vista'">Productos conectados</button>
            </div>
        </div>

        <!-- Vacio -->
        <div class="col-md-4">
            <!-- <div class="card-custom">
                <img src="<?php echo SERVERURL; ?>/public/img/tiktok.png" alt="TikTok Logo">
                <h5 class="card-title">TikTok</h5>
                <p class="card-text">Manten actualizadas tus campañas con nuestra api.</p>
                <input type="hidden" id="conectado_tiktok" name="conectado_tiktok">

                <div id="conectarTiktok" style="display: none;">
                    <p class="status connected">Conectado</p>
                </div>
                <div id="desconectarTiktok" style="display: none;">
                    <p class="status disconnected">Desconectado</p>
                </div>

                <button class="btn btn-primary" onclick="abrirmodal_tiktok()">Conectar</button>
            </div> -->
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

<script src="<?php echo SERVERURL ?>Views/Integraciones/js/integraciones.js"></script>
<?php require_once './Views/templates/footer.php'; ?>