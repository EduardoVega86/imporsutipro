<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Pedidos/css/novedades_style.php'; ?>

<?php require_once './Views/Pedidos/Modales/gestionar_novedad.php'; ?>
<div class="custom-container-fluid">
    <div class="container mt-5" style="max-width: 1600px;">
        <h2 class="text-center mb-4">Novedades</h2>
        <!-- <div class="d-flex flex-column justify-content-between">
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
                <div class="flex-fill filtro_tienda" style="width: 100%; padding-top: 8px; ">
                    <div style="width: 100%;">
                        <label for="tienda_q" class="col-form-label">Proveedor / Dropshipper</label>
                        <select id="tienda_q" class="form-control">
                            <option value="">Selecciona un Proveedor o Dropshipper</option>

                        </select>
                    </div>
                </div>
            </div>

            <div class="segunda_seccionFiltro">
                <div style="width: 100%;">
                    <label for="inputPassword3" class="col-sm-2 col-form-label">Estado</label>
                    <div>
                        <select name="estado_q" class="form-control" id="estado_q">
                            <option value=""> Seleccione Estado </option>
                            <option value="8"> Anulado </option>
                            <option value="2"> Por Recolectar </option>
                            <option value="5"> En Transito </option>
                            <option value="7"> Entregado </option>
                            <option value="14"> Con Novedad </option>
                            <option value="9"> Devuelto </option>
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


            <button id="imprimir_guias" class="btn btn-success">Generar Impresion</button>
        </div> -->

        <div class="table-responsive">
            <!-- <table class="table table-bordered table-striped table-hover"> -->
            <table id="datatable_novedades" class="table table-striped">
                <!-- <caption>
                    DataTable.js Demo
                </caption> -->
                <thead>
                    <tr>
                        <th class="centered">Orden</th>
                        <th class="centered"># de Guia</th>
                        <th class="centered">Fecha</th>
                        <th class="centered">Transportadora</th>
                        <th class="centered">Cliente</th>
                        <th class="centered">Novedad</th>
                        <th class="centered">Medida tomada</th>
                        <th class="centered">Estado</th>
                        <th class="centered">Solución</th>
                        <th class="centered">Tracking</th>
                    </tr>
                </thead>
                <tbody id="tableBody_novedades"></tbody>
            </table>
        </div>

        <h2 class="text-center mb-4">Novedades Gestionadas</h2>
        <div class="table-responsive">
            <!-- <table class="table table-bordered table-striped table-hover"> -->
            <table id="datatable_novedades_gestionadas" class="table table-striped">
                <!-- <caption>
                    DataTable.js Demo
                </caption> -->
                <thead>
                    <tr>
                        <th class="centered">Orden</th>
                        <th class="centered"># de Guia</th>
                        <th class="centered">Fecha</th>
                        <th class="centered">Transportadora</th>
                        <th class="centered">Cliente</th>
                        <th class="centered">Novedad</th>
                        <th class="centered">Medida tomada</th>
                        <th class="centered">Estado</th>
                        <th class="centered">Solución</th>
                        <th class="centered">Tracking</th>
                    </tr>
                </thead>
                <tbody id="tableBody_novedades_gestionadas"></tbody>
            </table>
        </div>
    </div>
</div>

<script src="<?php echo SERVERURL ?>/Views/Pedidos/js/novedades.js"></script>
<?php require_once './Views/templates/footer.php'; ?>