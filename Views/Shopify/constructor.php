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
                        Seleccione
                    </button>
                </h2>
                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#acordion_aplicacionShopify">
                    <div class="accordion-body">
                        <div class="form-group w-100 hidden-field" id="bodega-field">
                            <label for="nombre">Nombre:</label>
                            <select class="form-select" id="nombre">
                                <option value="" selected>-- Seleccione Nombre --</option>
                            </select>
                        </div>
                        <div class="form-group w-100 hidden-field" id="bodega-field">
                            <label for="apellido">Apellido:</label>
                            <select class="form-select" id="apellido">
                                <option value="" selected>-- Seleccione Apellido --</option>
                            </select>
                        </div>
                        <div class="form-group w-100 hidden-field" id="bodega-field">
                            <label for="principal">Calle Principal:</label>
                            <select class="form-select" id="principal">
                                <option value="" selected>-- Seleccione Calle Principal --</option>
                            </select>
                        </div>
                        <div class="form-group w-100 hidden-field" id="bodega-field">
                            <label for="secundario">Calle Secundaria:</label>
                            <select class="form-select" id="secundario">
                                <option value="" selected>-- Seleccione Calle Secundaria --</option>
                            </select>
                        </div>
                        <div class="form-group w-100 hidden-field" id="bodega-field">
                            <label for="provincia">Provincia:</label>
                            <select class="form-select" id="provincia">
                                <option value="" selected>-- Seleccione Provincia --</option>
                            </select>
                        </div>
                        <div class="form-group w-100 hidden-field" id="bodega-field">
                            <label for="ciudad">Ciudad:</label>
                            <select class="form-select" id="ciudad">
                                <option value="" selected>-- Seleccione Ciudad --</option>
                            </select>
                        </div>
                        <div class="form-group w-100 hidden-field" id="bodega-field">
                            <label for="codigo_postal">Codigo postal:</label>
                            <select class="form-select" id="codigo_postal">
                                <option value="" selected>-- Seleccione Codigo postal --</option>
                            </select>
                        </div>
                        <div class="form-group w-100 hidden-field" id="bodega-field">
                            <label for="pais">País:</label>
                            <select class="form-select" id="pais">
                                <option value="" selected>-- Seleccione País --</option>
                            </select>
                        </div>
                        <div class="form-group w-100 hidden-field" id="bodega-field">
                            <label for="telefono">Teléfono:</label>
                            <select class="form-select" id="telefono">
                                <option value="" selected>-- Seleccione Teléfono --</option>
                            </select>
                        </div>
                        <div class="form-group w-100 hidden-field" id="bodega-field">
                            <label for="email">Email:</label>
                            <select class="form-select" id="email">
                                <option value="" selected>-- Seleccione Email --</option>
                            </select>
                        </div>
                        <div class="form-group w-100 hidden-field" id="bodega-field">
                            <label for="total">Total:</label>
                            <select class="form-select" id="total">
                                <option value="" selected>-- Seleccione Total --</option>
                            </select>
                        </div>
                        <div class="form-group w-100 hidden-field" id="bodega-field">
                            <label for="descuento">Descuento:</label>
                            <select class="form-select" id="descuento">
                                <option value="" selected>-- Seleccione Descuento --</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo SERVERURL ?>/Views/Shopify/js/constructor.js"></script>
<?php require_once './Views/templates/footer.php'; ?>