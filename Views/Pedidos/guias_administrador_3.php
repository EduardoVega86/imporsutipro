<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Pedidos/css/guias_administrador_style.php'; ?>


<?php require_once './Views/Pedidos/Modales/informacion_plataforma.php'; ?>
<?php require_once './Views/Pedidos/Modales/detalles_factura.php'; ?>
<?php require_once './Views/Pedidos/Modales/gestionar_novedad.php'; ?>
<?php require_once './Views/Pedidos/Modales/gestionar_novedadSpeed.php'; ?>
<div class="custom-container-fluid">
    <div class="container mt-5" style="max-width: 1600px;">
        <h2 class="text-center mb-4">Guias</h2>
        <div class="d-flex flex-column justify-content-between">
            <div class="primer_seccionFiltro" style="width: 100%;">
                <div class="d-flex flex-row align-items-end filtro_fecha">
                    <div class="flex-fill">
                        <h6>Seleccione el rango de fechas:</h6>
                        <div class="input-group">
                            <input type="text" class="form-control" id="daterange">
                            <span class="input-group-text"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                        </div>
                    </div>
                </div>
                <div class="flex-fill filtro_impresar">
                    <div class=" d-flex flex-column justify-content-start">
                        <label for="inputPassword3" class="col-sm-2 col-form-label">Impresiones</label>
                        <div>
                            <select name="impresion" class="form-control" id="impresion">
                                <option value=""> Todas</option>
                                <option value="1"> Impresas </option>
                                <option value="0"> No impresas </option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="flex-fill filtro_impresar">
                    <div class=" d-flex flex-column justify-content-start">
                        <label for="despachado" class="col-sm-2 col-form-label">Despachados</label>
                        <div>
                            <select name="despachos" class="form-control" id="despachos">
                                <option value=""> Todas</option>
                                <option value="2"> Despachados </option>
                                <option value="1"> No Despachados </option>
                                <option value="3"> Devueltos </option>
                                <option value="4"> No Devueltos </option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="flex-fill filtro_tienda" style="width: 100%; padding-top: 8px; ">
                    <div style="width: 100%;">
                        <label for="tienda_q" class="col-form-label">Proveedor / Dropshipper</label>
                        <select id="tienda_q" class="form-control">
                            <option value="">Selecciona un Proveedor o Dropshipper</option>
                            <option value="1">Dropshipper</option>
                            <option value="0">Local</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="segunda_seccionFiltro">
                <div style="width: 100%;">
                    <label for="inputPassword3" class="col-sm-2 col-form-label">Estado</label>
                    <div>
                        <select name="estado_q" class="form-control" id="estado_q">
                            <option value="">Seleccione Estado</option>
                            <option value="generada">Generada/ Por Recolectar</option>
                            <option value="en_transito">En transito / Procesamiento / En ruta</option>
                            <option value="entregada">Entregada</option>
                            <option value="novedad">Novedad</option>
                            <option value="devolucion">Devolución</option>
                        </select>
                    </div>
                </div>
                <div style="width: 100%;">
                    <label for="inputPassword3" class="col-sm-2 col-form-label">Transportadora</label>
                    <div>
                        <select name="transporte" id="transporte" class="form-control">
                            <option value=""> Seleccione Transportadora</option>
                            <option value="LAAR">Laar</option>
                            <option value="SPEED">Speed</option>
                            <option value="SERVIENTREGA">Servientrega</option>
                            <option value="GINTRACOM">Gintracom</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div style="padding-top: 20px;">
            <button id="btnAplicarFiltros" class="btn btn-primary">Aplicar Filtros</button>
            <button id="imprimir_guias" class="btn btn-success">Generar Impresion</button>
        </div>

        <div class="table-container" style="position: relative;">
            <!-- Loader que se mostrará únicamente sobre el área de la tabla -->
            <div id="tableLoader" style="display: none;">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table id="datatable_guias" class="table table-striped">
                <thead>
                    <tr>
                        <th class="centered"><input type="checkbox" id="selectAll"></th>
                        <th class="centered"># Guia</th>
                        <th class="centered">Detalle</th>
                        <th class="centered">Cliente</th>
                        <th class="centered">Destino</th>
                        <th class="centered">Entidades</th>
                        <th class="centered">Transportadora</th>
                        <th class="centered">Estado</th>
                        <th class="centered">Despachado</th>
                        <th class="centered">Impreso</th>
                        <th class="centered">Venta total</th>
                        <th class="centered">Costo producto</th>
                        <th class="centered">Costo flete</th>
                        <th class="centered">Fulfillment</th>
                        <th class="centered">Monto a recibir</th>
                        <th class="centered">Recaudo</th>
                        <th class="centered">Acciones</th>
                    </tr>
                </thead>
                <tbody id="tableBody_guias"></tbody>
            </table>
        </div>

        <script src="<?php echo SERVERURL ?>/Views/Pedidos/js/guias_administrador_3.js"></script>
        <?php require_once './Views/templates/footer.php'; ?>