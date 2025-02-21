<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Pedidos/css/guias_style.php'; ?>


<?php require_once './Views/Pedidos/Modales/informacion_plataforma.php'; ?>
<?php require_once './Views/Pedidos/Modales/detalles_factura.php'; ?>
<?php require_once './Views/Pedidos/Modales/gestionar_novedad.php'; ?>

<div class="custom-container-fluid">
    <div class="container mt-5" style="max-width: 1600px;">
        <h2 class="text-center mb-4">Guias</h2>

        <!-- 🔹 SECCIÓN DE CARDS INFORMATIVAS 🔹 -->
        <div class="row mb-4">
            <!-- Card 1: Número de guias -->
            <div class="col-md-3">
                <div class="card shadow-sm p-3 text-center" style="background: white; border-left: 5px solid #007bff;">
                    <h5 class="text-primary">
                        <i class="bx bx-box" style="font-size: 24px;"></i> Número de Guías
                        <i class="bx bx-help-circle text-muted" data-toggle="tooltip" title="Cantidad total de pedidos registrados incluida las guias ya generadas"></i>
                    </h5>
                    <h3 class="font-weight-bold" id="num_pedidos">0</h3>
                </div>
            </div>

            <!-- Card 2: Guias Por Recolectar/Generadas -->
            <div class="col-md-3">
                <div class="card shadow-sm p-3 text-center" style="background: white; border-left: 5px solid #ffc107;">
                    <h5 class="text-warning">
                        <i class="bx bx-package" style="font-size: 24px;"></i> Guías por Recolectar
                        <i class="bx bx-help-circle text-muted" data-toggle="tooltip" title="Cantidad de guías que han sido generadas"></i>
                    </h5>
                    <h3 class="font-weight-bold" id="num_generadas">0</h3>
                </div>
            </div>

            <!-- Card 3: Guías en tránsito -->
            <div class="col-md-3">
                <div class="card shadow-sm p-3 text-center" style="background: white; border-left: 5px solid #28a745;">
                    <h5 class="text-success">
                        <i class="bx bx-money" style="font-size: 24px;"></i> Guías en tránsito
                        <i class="bx bx-help-circle text-muted" data-toggle="tooltip" title="Monto total de los pedidos en el sistema"></i>
                    </h5>
                    <h3 class="font-weight-bold" id="num_transito">$0.00</h3>
                </div>
            </div>

            <!-- Card 4: Guías zona de entrega -->
            <div class="col-md-3">
                <div class="card shadow-sm p-3 text-center" style="background: white; border-left: 5px solid #dc3545;">
                    <h5 class="text-danger">
                        <i class="bx bx-check-shield" style="font-size: 24px;"></i> Guías en zona de entrega <span id="id_confirmacion"></span>
                        <i class="bx bx-help-circle text-muted" data-toggle="tooltip" title="Procentaje de guias o pedidos confirmados"></i>
                    </h5>
                    <h3 class="font-weight-bold" id="num_entregadas">0</h3>
                </div>
            </div>
            <!-- Card 5: Guías en estado novedad -->
            <div class="col-md-3">
                <div class="card shadow-sm p-3 text-center" style="background: white; border-left: 5px solid #dc3545;">
                    <h5 class="text-danger">
                        <i class="bx bx-check-shield" style="font-size: 24px;"></i> Guías en novedad <span id="id_confirmacion"></span>
                        <i class="bx bx-help-circle text-muted" data-toggle="tooltip" title="Procentaje de guias o pedidos confirmados"></i>
                    </h5>
                    <h3 class="font-weight-bold" id="num_novedad">0</h3>
                </div>
            </div>
            <!-- Card 5: Guías en estado devolucion -->
            <div class="col-md-3">
                <div class="card shadow-sm p-3 text-center" style="background: white; border-left: 5px solid #dc3545;">
                    <h5 class="text-danger">
                        <i class="bx bx-check-shield" style="font-size: 24px;"></i> Guías en devolución <span id="id_confirmacion"></span>
                        <i class="bx bx-help-circle text-muted" data-toggle="tooltip" title="Procentaje de guias o pedidos confirmados"></i>
                    </h5>
                    <h3 class="font-weight-bold" id="num_devolucion">0</h3>
                </div>
            </div>
        </div>
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
                                <option value="4"> No Devueltos</option>
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
            <!-- <table class="table table-bordered table-striped table-hover"> -->
            <table id="datatable_guias" class="table table-striped">
                <!-- <caption>
                    DataTable.js Demo
                </caption> -->
                <thead>
                    <tr>
                        <th class="centered"><input type="checkbox" id="selectAll"></th>
                        <th class="centered"># Guia</th>
                        <th class="centered">Detalle</th>
                        <th class="centered">Cliente</th>
                        <th class="centered">Destino</th>
                        <!-- <th class="centered">Tienda</th> -->
                        <th class="centered">Transportadora</th>
                        <th class="centered">Estado</th>
                        <th class="centered">Despachado</th>
                        <th class="centered">Impreso</th>
                        <th class="centered">Acciones</th>
                        <th class="centered">Contiene</th>
                        <th class="centered">Monto</th>
                        <th class="centered">Costo</th>
                    </tr>
                </thead>
                <tbody id="tableBody_guias"></tbody>
            </table>
        </div>
    </div>
</div>
<script>
    let fecha_inicio = "";
    let fecha_fin = "";

    // Calcula la fecha de inicio (hace 7 días) y la fecha de fin (hoy)
    let hoy = moment();
    let haceUnaSemana = moment().subtract(6, 'days'); // Rango de 7 días

    // Asignar las fechas a las variables al cargar la página
    fecha_inicio = haceUnaSemana.format('YYYY-MM-DD') + ' 00:00:00';
    fecha_fin = hoy.format('YYYY-MM-DD') + ' 23:59:59';

    $(function() {
        $('#daterange').daterangepicker({
            opens: 'right',
            startDate: haceUnaSemana, // Fecha de inicio predefinida
            endDate: hoy, // Fecha de fin predefinida
            locale: {
                format: 'YYYY-MM-DD',
                separator: ' - ',
                applyLabel: 'Aplicar',
                cancelLabel: 'Cancelar',
                fromLabel: 'Desde',
                toLabel: 'Hasta',
                customRangeLabel: 'Custom',
                weekLabel: 'S',
                daysOfWeek: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
                monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                firstDay: 1
            },
            autoUpdateInput: true // Actualiza el input automáticamente
        });

        // NO recargamos la tabla directamente al aplicar el rango, lo haremos con el botón "Aplicar Filtros".
        $('#daterange').on('apply.daterangepicker', function(ev, picker) {
            fecha_inicio = picker.startDate.format('YYYY-MM-DD') + ' 00:00:00';
            fecha_fin = picker.endDate.format('YYYY-MM-DD') + ' 23:59:59';

            //Recargamos la tabla inmediatamente usando el nuevo rango de fechas
            initDataTable();
        });

        // Seteamos en el input la fecha inicial y final
        $('#daterange').val(
            haceUnaSemana.format('YYYY-MM-DD') + ' - ' + hoy.format('YYYY-MM-DD')
        );
    });
</script>
<script src="<?php echo SERVERURL ?>/Views/Pedidos/js/guias.js"></script>
<?php require_once './Views/templates/footer.php'; ?>