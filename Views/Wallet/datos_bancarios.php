<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Wallet/css/datos_bancarios_style.php'; ?>


<div class="custom-container-fluid">
    <div class="container mt-5" style="max-width: 1600px;">
        <h2 class="text-center mb-4">Datos Bancarios</h2>
        <div class="left_right gap-2">
            <div class="left">
                <div class="accordion" id="acordion_datosBancarios">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                Datos Bancarios
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#acordion_datosBancarios">
                            <div class="accordion-body">
                                <form id="datos_bancario">
                                    <div class="mb-3">
                                        <label for="banco" class="form-label">Banco:</label>
                                        <select class="form-select" id="banco">
                                            <option value="0">-- Seleccione un banco --</option>
                                            <option value="Pichincha">Banco Pichincha</option>
                                            <option value="Guayaquil">Banco Guayaquil</option>
                                            <option value="Produbanco">Banco Produbanco</option>
                                            <option value="Bolivariano">Banco Bolivariano</option>
                                            <option value="Pacifico">Banco Pacifico</option>
                                            <option value="Solidario">Banco Solidario</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="tipo_cuenta" class="form-label">Tipo de cuenta:</label>
                                        <select class="form-select" id="tipo_cuenta">
                                            <option value="0">-- Seleccione un tipo de cuenta --</option>
                                            <option value="Ahorros">Ahorros</option>
                                            <option value="Corriente">Corriente</option>
                                        </select>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="numero_cuenta" class="form-label">Número de cuenta:</label>
                                        <input type="text" class="form-control" id="numero_cuenta" placeholder="Numero de cuenta">
                                    </div>
                                    <div class="mb-3">
                                        <label for="nombre_titular" class="form-label">Nombre del Titular:</label>
                                        <input type="text" class="form-control" id="nombre_titular" placeholder="Nombre del titular">
                                    </div>
                                    <div class="mb-3">
                                        <label for="cedula_titular" class="form-label">Cédula del Titular:</label>
                                        <input type="text" class="form-control" id="cedula_titular" placeholder="Cédula del titular">
                                    </div>
                                    <div class="mb-3">
                                        <label for="correo_titular" class="form-label">Correo del Titular:</label>
                                        <input type="email" class="form-control" id="correo_titular" placeholder="Correo del titular">
                                    </div>
                                    <div class="mb-3">
                                        <label for="telefono_titular" class="form-label">Teléfono del Titular:</label>
                                        <input type="text" class="form-control" id="telefono_titular" placeholder="Teléfono del titular">
                                    </div>
                                    <button type="submit" class="btn btn-success">Enviar datos</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingTwo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                Datos de Facturación
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#acordion_datosBancarios">
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
            <div class="line"></div>
            <div class="right">
                <div>
                    <h3 style="text-align: center; padding-top:5px;">Lista de datos Bancarios</h3>
                    <div class="table-responsive">
                        <table id="datatable_datos_bancarios" class="table table-striped">
                            <thead>
                                <tr>
                                    <th class="centered">ID</th>
                                    <th class="centered">Tipo de cuenta</th>
                                    <th class="centered">Banco</th>
                                    <th class="centered">Numero de cuenta</th>
                                    <th class="centered">Nombre</th>
                                    <th class="centered">Cedula</th>
                                    <th class="centered">Correo</th>
                                    <th class="centered">Telefono</th>
                                    <th class="centered">Acción</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody_datos_bancarios"></tbody>
                        </table>
                    </div>
                </div>
                <div>
                    <h3 style="text-align: center; padding-top:5px;">Lista de datos de Facturación</h3>
                    <div class="table-responsive">
                        <table id="datatable_datos_facturacion" class="table table-striped">
                            <thead>
                                <tr>
                                    <th class="centered">ID</th>
                                    <th class="centered">Razón Social</th>
                                    <th class="centered">RUC</th>
                                    <th class="centered">Dirección</th>
                                    <th class="centered">Correo</th>
                                    <th class="centered">Telefono</th>
                                    <th class="centered">Acción</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody_datos_facturacion"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo SERVERURL ?>/Views/Wallet/js/datos_bancarios.js"></script>
<?php require_once './Views/templates/footer.php'; ?>