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
            <div class="card-custom">
                <img src="<?php echo SERVERURL; ?>/public/img/facebook.png" alt="Facebook Logo">
                <h5 class="card-title">Shopify</h5>
                <p class="card-text">Realiza la conexion de tu cuenta de shopify con nuestro sistema Imporsuit.</p>
                <!-- <input type="hidden" id="conectado_facebook" name="conectado_facebook">
                <div id="conectarFacebook" style="display: none;">
                    <p class="status connected">Conectado</p>
                </div>
                <div id="desconectarFacebook" style="display: none;">
                    <p class="status disconnected">Desconectado</p>
                </div> -->

                <button class="btn btn-primary" onclick="redireccionar_shopify()">Conectar</button>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <!-- Funnelish -->
        <div class="col-md-4">
            <div class="card-custom">
                <img src="<?php echo SERVERURL; ?>/public/img/facebook.png" alt="Facebook Logo">
                <h5 class="card-title">Funnelish</h5>
                <p class="card-text">Realiza la conexion de tu cuenta de funnelish con nuestro sistema Imporsuit.</p>
                <!-- <input type="hidden" id="conectado_facebook" name="conectado_facebook">
                <div id="conectarFacebook" style="display: none;">
                    <p class="status connected">Conectado</p>
                </div>
                <div id="desconectarFacebook" style="display: none;">
                    <p class="status disconnected">Desconectado</p>
                </div> -->

                <button class="btn btn-primary" onclick="redireccionar_funnelish()">Conectar</button>
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