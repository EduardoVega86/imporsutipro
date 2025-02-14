<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Shopify/css/constructor_style.php'; ?>

<div class="full-screen-container">
    <div class="custom-container-fluid mt-4">
        <h1>Configurar Carritos Abandonado en Shopify</h1>
        <div class="accordion" id="acordion_aplicacionShopify">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        Elija la aplicación
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

                        <div class="loading-animation" id="loading-below" style="display: none; margin-top: 10px;">
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
                        Seleccione
                    </button>
                </h2>
                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#acordion_aplicacionShopify">
                    <div class="accordion-body" style="text-align: left;">
                        <div class="form-group w-100 hidden-field">
                            <label for="select-nombre">Nombre:</label>
                            <select class="form-select" id="select-nombre">
                                <option value="" selected>-- Seleccione Nombre --</option>
                            </select>
                        </div>
                        <div class="form-group w-100 hidden-field">
                            <label for="select-apellido">Apellido:</label>
                            <select class="form-select" id="select-apellido">
                                <option value="" selected>-- Seleccione Apellido --</option>
                            </select>
                        </div>
                        <div class="form-group w-100 hidden-field">
                            <label for="select-principal">Calle Principal:</label>
                            <select class="form-select" id="select-principal">
                                <option value="" selected>-- Seleccione Calle Principal --</option>
                            </select>
                        </div>
                        <div class="form-group w-100 hidden-field">
                            <label for="select-secundario">Calle Secundaria:</label>
                            <select class="form-select" id="select-secundario">
                                <option value="" selected>-- Seleccione Calle Secundaria --</option>
                            </select>
                        </div>
                        <div class="form-group w-100 hidden-field">
                            <label for="select-provincia">Provincia:</label>
                            <select class="form-select" id="select-provincia">
                                <option value="" selected>-- Seleccione Provincia --</option>
                            </select>
                        </div>
                        <div class="form-group w-100 hidden-field">
                            <label for="select-ciudad">Ciudad:</label>
                            <select class="form-select" id="select-ciudad">
                                <option value="" selected>-- Seleccione Ciudad --</option>
                            </select>
                        </div>
                        <div class="form-group w-100 hidden-field">
                            <label for="select-codigo_postal">Codigo postal:</label>
                            <select class="form-select" id="select-codigo_postal">
                                <option value="" selected>-- Seleccione Codigo postal --</option>
                            </select>
                        </div>
                        <div class="form-group w-100 hidden-field">
                            <label for="select-pais">País:</label>
                            <select class="form-select" id="select-pais">
                                <option value="" selected>-- Seleccione País --</option>
                            </select>
                        </div>
                        <div class="form-group w-100 hidden-field">
                            <label for="select-telefono">Teléfono:</label>
                            <select class="form-select" id="select-telefono">
                                <option value="" selected>-- Seleccione Teléfono --</option>
                            </select>
                        </div>
                        <div class="form-group w-100 hidden-field">
                            <label for="select-email">Email:</label>
                            <select class="form-select" id="select-email">
                                <option value="" selected>-- Seleccione Email --</option>
                            </select>
                        </div>
                        <div class="form-group w-100 hidden-field">
                            <label for="select-total">Total:</label>
                            <select class="form-select" id="select-total">
                                <option value="" selected>-- Seleccione Total --</option>
                            </select>
                        </div>
                        <div class="form-group w-100 hidden-field">
                            <label for="select-descuento">Descuento:</label>
                            <select class="form-select" id="select-descuento">
                                <option value="" selected>-- Seleccione Descuento --</option>
                            </select>
                        </div>
                        <div class="form-group w-100 hidden-field">
                            <label for="select-referencia">Referencia:</label>
                            <select class="form-select" id="select-referencia">
                                <option value="" selected>-- Seleccione Descuento --</option>
                            </select>
                        </div>
                        <button id="send-button" class="btn btn-primary">Enviar datos</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="json_informacion" id="json-informacion">
        <h1>Json de Informacion</h1>
        <div id="json-content"></div>
    </div>

</div>

<script src="<?php echo SERVERURL ?>/Views/Shopify/js/constructor.js"></script>
<?php require_once './Views/templates/footer.php'; ?>