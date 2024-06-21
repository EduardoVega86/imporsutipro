<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Productos/css/importacion_masiva_style.php'; ?>

<div class="full-screen-container">
    <div class="custom-container-fluid mt-4">
        <h1>Importación Masiva</h1>
        <div class="accordion" id="acordion_aplicacionShopify">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                        Elija la aplicacion
                    </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#acordion_aplicacionShopify">
                    <div class="accordion-body">
                        <div class="img-container text-center">
                            <img src="<?php echo SERVERURL; ?>/public/img/SERVIENTREGA.jpg" alt="Shopify">
                            <div><span>Shopify</span></div>
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

<?php require_once './Views/templates/footer.php'; ?>