<?php require_once './Views/templates/header.php'; ?>
<?php // Recuperar los parámetros de la URL
$id_producto = isset($_GET['id_producto']) ? $_GET['id_producto'] : null;
$sku = isset($_GET['sku']) ? $_GET['sku'] : null;
?>

<?php require_once './Views/Pedidos/css/nuevo_style.php'; ?>

<?php require_once './Views/Pedidos/Modales/agregar_productos_pedido.php'; ?>
<div class="custom-container-fluid mt-4">
    <div class="row">
        <div class="col">
            <h2 class="section-title">Generar Guías</h2>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 left-column d-flex flex-column">
            <div class="form-section">
                <form>
                    <div class="row g-3 align-items-center mb-3">
                        <div class="col-auto">
                            <label for="cantidad" class="col-form-label">Cant:</label>
                        </div>
                        <div class="col-auto">
                            <input type="number" id="cantidad" class="form-control" value="1" min="1">
                        </div>
                        <div class="col-auto">
                            <label for="codigo" class="col-form-label">Código:</label>
                        </div>
                        <div class="col-auto">
                            <input type="text" id="codigo" class="form-control">
                        </div>
                        <div class="col-auto">
                            <button type="button" class="btn btn-primary" onclick="buscar_productos_nuevoPedido()">Buscar</button>
                        </div>

                    </div>
                </form>
            </div>
            <div class="table-responsive">
                <!-- <table class="table table-bordered table-striped table-hover"> -->
                <table id="datatable_nuevoPedido" class="table table-striped">
                    <!-- <caption>
                    DataTable.js Demo
                </caption> -->
                    <thead>
                        <tr>
                            <th class="centered">Cod</th>
                            <th class="centered">Cant.</th>
                            <th class="centered">Descripción</th>
                            <th class="centered">Precio</th>
                            <th class="centered">Desc %</th>
                            <th class="centered">Total</th>
                            <th class="centered">Acción</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody_nuevoPedido"></tbody>
                </table>
                <div class="text-end">
                    <span>Total: $<span id="monto_total" class="monto">

                        </span> </span>

                </div>
            </div>
            <div style="padding-top: 40px;">
                <h3 class="mb-3" style="text-decoration:underline;"><strong>Información de la venta</strong></h3>
                <p class="texto_infoVenta"><strong>Monto de Venta:</strong> <span id="montoVenta_infoVenta"></span></p>
                <p class="texto_infoVenta"><strong>Costo:</strong> <span id="costo_infoVenta"></span></p>
                <p class="texto_infoVenta"><strong>Precio de envio:</strong> <span id="precioEnvio_infoVenta"></span></p>
                <p class="texto_infoVenta"><strong>Total:</strong> <span id="total_infoVenta"></span></p>
                <div class="alert alert-warning" role="alert" style="display: none;" id="alerta_valoresContra">
                    <strong>Atención:</strong> No puede generar esta guia porque el costo de la misma tiene valores en contra.
                </div>
            </div>
        </div>

        <div class="col-md-6 right-column">
            <div class="form-section mb-4">
                <h5>Datos Destinatario</h5>
                <form id="datos_destinatario">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="nombre" class="form-label">Nombre y Apellido</label>
                            <input type="text" class="form-control" id="nombre" placeholder="Nombre y Apellido">
                        </div>
                        <div class="col-md-6">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="text" class="form-control" id="telefono" placeholder="Teléfono" oninput="this.value = this.value.replace(/[^0-9+]/g, '')" onblur="validar_devoluciones(this.value)">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="provincia" class="form-label">Provincia</label>
                            <select id="provincia" class="form-select">
                                <option selected>Selecciona una opción</option>
                                <!-- Agregar opciones aquí -->
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="ciudad" class="form-label">Ciudad</label>
                            <select id="ciudad" class="form-select">
                                <option selected>Selecciona una opción</option>
                                <!-- Agregar opciones aquí -->
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="calle_principal" class="form-label">Calle Principal</label>
                            <input type="text" class="form-control" id="calle_principal" placeholder="Calle Principal">
                        </div>
                        <div class="col-md-6">
                            <label for="calle_secundaria" class="form-label">Calle Secundaria</label>
                            <input type="text" class="form-control" id="calle_secundaria" placeholder="Calle Secundaria">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <!-- <div class="col-md-6">
                            <label for="numero_casa" class="form-label">Número de Casa</label>
                            <input type="text" class="form-control" id="numero_casa" placeholder="Número de Casa">
                        </div> -->
                        <div class="mb-3">
                            <label for="referencia" class="form-label">Referencia</label>
                            <input type="text" class="form-control" id="referencia" placeholder="Referencia">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="observaciones" class="form-label">Observaciones para la entrega</label>
                        <input type="text" class="form-control" id="observacion" placeholder="Referencias Adicionales (Opcional)">
                    </div>
                    <div class="alert alert-warning" role="alert" id="alerta_devoluciones" style="display: none;">
                        Este cliente registra 1 o más devoluciones en nuestro sistema.
                    </div>
                </form>
            </div>

            <div class="form-section">
                <h5>Generar Guías</h5>
                <div class="d-flex justify-content-around mb-4 flex-wrap gap-4">
                    <div class="img-container text-center transportadora" data-company="servientrega">
                        <img src="<?php echo SERVERURL; ?>/public/img/SERVIENTREGA.jpg" alt="Servientrega">
                        <div class="price-tag" data-price-id="price_servientrega">$<span id="price_servientrega">--</span></div>
                    </div>
                    <div class="img-container text-center transportadora" data-company="laar">
                        <img src="<?php echo SERVERURL; ?>/public/img/LAAR.jpg" alt="Laborcourier">
                        <div class="price-tag" data-price-id="price_laar">$<span id="price_laar">--</span></div>
                    </div>
                    <div class="img-container text-center transportadora" data-company="speed">
                        <img src="<?php echo TRANSPORTADORA_IMAGEN ?>" alt="Speed">
                        <?php if (MATRIZ == 2) { ?>
                            <div class="price-tag" data-price-id="price_speed">$<span id="price_speed">Mantenimiento</span></div>
                        <?php } else { ?>
                            <div class="price-tag" data-price-id="price_speed">$<span id="price_speed">--</span></div>
                        <?php } ?>
                    </div>
                    <div class="img-container text-center transportadora" data-company="gintracom">
                        <img src="<?php echo SERVERURL; ?>/public/img/GINTRACOM.jpg" alt="Gintracom">
                        <div class="price-tag" data-price-id="price_gintracom">$<span id="price_gintracom">--</span></div>
                    </div>
                    <input type="hidden" id="costo_flete" name="costo_flete">
                    <input type="hidden" id="transportadora_selected" name="transportadora_selected">

                    <!-- inputs con informacion servientrega -->
                    <input type="hidden" id="flete" name="flete">
                    <input type="hidden" id="seguro" name="seguro">
                    <input type="hidden" id="comision" name="comision">
                    <input type="hidden" id="otros" name="otros">
                    <input type="hidden" id="impuestos" name="impuestos">
                </div>
                <form>
                    <div class="mb-3">
                        <label for="recaudo" class="form-label">Recaudo</label>
                        <select id="recaudo" class="form-select">
                            <option value="1" selected>Con Recaudo</option>
                            <option value="2">Sin Recaudo</option>
                        </select>
                    </div>
                    <!--  <div class="mb-3">
                        <label class="form-check-label" for="extras">extra</label>
                        <select class="form-control">
                            <option>Selecciona un Extras</option>
                            <option value="1">Autogestion</option>
                            <option value="2">Call center</option>
                        </select>
                    </div> -->
                </form>
                <div class="d-flex justify-content-between">
                    <button type="button" id="guardarPedidoBtn" class="btn btn-success btn-custom" onclick="handleButtonClick('guardarPedidoBtn', agregar_nuevoPedido)">Guardar Pedido</button>
                    <button type="button" id="generarGuiaBtn" class="btn btn-danger btn-custom" onclick="handleButtonClick('generarGuiaBtn', generar_guia)">Generar Guía</button>
                </div>

            </div>
        </div>
    </div>
</div>

<script src="<?php echo SERVERURL ?>/Views/Pedidos/js/nuevo.js"></script>
<script src="<?php echo SERVERURL ?>/Views/Pedidos/js/agregar_productos_pedido.js"></script>
<?php require_once './Views/templates/footer.php'; ?>