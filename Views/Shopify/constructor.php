<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Shopify/css/constructor_style.php'; ?>

<div class="full-screen-container">
    <div class="custom-container-fluid mt-4">
        <h1>integraciones</h1>
        <div class="accordion" id="acordion_aplicacionShopify">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        Elija la aplicacion
                    </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#acordion_aplicacionShopify">
                    <div class="accordion-body" style="text-align: left;">
                        <div class="img-container text-center aplicacion" id="trigger-container">
                            <img src="<?php echo SERVERURL; ?>/public/img/logo_shopify.png" alt="Shopify">
                            <div class="name-tag"><span>Shopify</span></div>
                        </div>

                        <div class="loading-animation" id="loading">
                            <div class="spinner-border" role="status">
                                <span class="sr-only">Cargando...</span>
                            </div>
                            <div>Cargando...</div>
                        </div>

                        <div class="generacion_enlace" id="enlace-section" style="padding-top: 10px;">
                            <label for="generador_enlace" class="form-label">Enlace generado:</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="generador_enlace" disabled>
                                <button class="btn btn-primary" type="button" id="verify-button">Verificar</button>
                            </div>
                            <label for="infor">Nota: Agregar este enlace en su webhook</label>
                        </div>
                        
                        <div class="loading-animation" id="loading-below" style="display: none;">
                            <div class="spinner-border" role="status">
                                <span class="sr-only">Cargando...</span>
                            </div>
                            <div>Cargando...</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingTwo">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        Datos de Facturación
                    </button>
                </h2>
                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#acordion_aplicacionShopify">
                    <div class="accordion-body">
                        <form id="datos_facturacion">
                            <div class="mb-3">
                                <label for="razon_socialFactura" class="form-label">Razón Social:</label>
                                <input type="text" class="form-control" id="razon_socialFactura" placeholder="Razón Social">
                            </div>
                            <div class="mb-3">
                                <label for="ruc_factura" class="form-label">RUC:</label>
                                <input type="text" class="form-control" id="ruc_factura" placeholder="RUC">
                            </div>
                            <div class="mb-3">
                                <label for="direccion_factura" class="form-label">Dirección:</label>
                                <input type="text" class="form-control" id="direccion_factura" placeholder="Dirección">
                            </div>
                            <div class="mb-3">
                                <label for="correo_factura" class="form-label">Correo:</label>
                                <input type="email" class="form-control" id="correo_factura" placeholder="Correo">
                            </div>
                            <div class="mb-3">
                                <label for="telefono_factura" class="form-label">Teléfono:</label>
                                <input type="text" class="form-control" id="telefono_factura" placeholder="Teléfono">
                            </div>
                            <button type="submit" class="btn btn-success">Enviar datos</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo SERVERURL ?>/Views/Shopify/js/constructor.js"></script>
<?php require_once './Views/templates/footer.php'; ?>