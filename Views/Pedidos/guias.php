<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Pedidos/css/guias_style.php'; ?>


<?php require_once './Views/Pedidos/Modales/informacion_plataforma.php'; ?>
<?php require_once './Views/Pedidos/Modales/detalles_factura.php'; ?>
<?php require_once './Views/Pedidos/Modales/gestionar_novedad.php'; ?>

<div class="custom-container-fluid">
    <div class="container mt-5" style="max-width: 1600px;">
        <h2 class="text-center mb-4">Guias</h2>

        <!-- 🔹 SECCIÓN DE CARDS INFORMATIVAS 🔹 -->
        <div class="row mb-4 text-center custom-cards">
            <!-- Card 1: Número de guias -->
            <div class="col-6 col-md-2">
                <div class="card shadow-sm p-2" style="border-left: 5px solid #007bff;">
                    <h6 class="text-primary">
                        <i class="bx bx-box" style="font-size: 20px;"></i> # de guías
                        <i class="bx bx-help-circle text-muted" data-toggle="tooltip"
                            title="Cantidad total de pedidos registrados incluida las guias ya generadas">
                        </i>
                    </h6>
                    <h4 class="font-weight-bold" id="num_pedidos">0</h4>
                </div>
            </div>

            <!-- Card 2: Guías por Recolectar/Generadas -->
            <div class="col-6 col-md-2">
                <div class="card shadow-sm p-2" style="border-left: 5px solid #ffc107;">
                    <h6 class="text-warning">
                        <i class="bx bx-package" style="font-size: 20px;"></i> Por recolectar
                        <i class="bx bx-help-circle text-muted" data-toggle="tooltip"
                            title="Cantidad de guías que han sido generadas">
                        </i>
                    </h6>
                    <h4 class="font-weight-bold" id="num_generadas">0</h4>
                    <!-- Barra de progreso -->
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-warning" role="progressbar" style="width: 0%;" id="progress_generadas"></div>
                    </div>
                    <!-- Porcentaje numérico -->
                    <small class="text-muted" id="percent_generadas">0%</small>
                </div>
            </div>

            <!-- Card 3: Guías en tránsito -->
            <div class="col-6 col-md-2">
                <div class="card shadow-sm p-2" style="border-left: 5px solid #28a745;">
                    <h6 class="text-success">
                        <i class="bx bx-run" style="font-size: 20px;"></i> En tránsito
                        <i class="bx bx-help-circle text-muted" data-toggle="tooltip"
                            title="Cantidad de guías que están en ruta o procesamiento">
                        </i>
                    </h6>
                    <h4 class="font-weight-bold" id="num_transito">0</h4>
                    <!-- Barra de progreso -->
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 0%;" id="progress_transito"></div>
                    </div>
                    <!-- Porcentaje numérico -->
                    <small class="text-muted" id="percent_transito">0%</small>
                </div>
            </div>

            <!-- Card 4: Guías en zona de entrega -->
            <div class="col-6 col-md-2">
                <div class="card shadow-sm p-2" style="border-left: 5px solid #17a2b8;">
                    <h6 class="text-info">
                        <i class="bx bx-map-pin" style="font-size: 20px;"></i> Zona de entrega
                        <i class="bx bx-help-circle text-muted" data-toggle="tooltip"
                            title="Guías que se encuentran cerca del lugar de entrega ">
                        </i>
                    </h6>
                    <h4 class="font-weight-bold" id="num_zona_entrega">0</h4>
                    <!-- Barra de progreso -->
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-info" role="progressbar" style="width: 0%;" id="progress_zonaentrega"></div>
                    </div>
                    <!-- Porcentaje numérico -->
                    <small class="text-muted" id="percent_zonaentrega">0%</small>
                </div>
            </div>

            <!-- Card 5: Guías en entregadas -->
            <div class="col-6 col-md-2">
                <div class="card shadow-sm p-2" style="border-left: 5px solid #28a745;">
                    <h6 class="text-success">
                        <i class="bx bx-check-circle" style="font-size: 20px;"></i> Entregadas
                        <i class="bx bx-help-circle text-muted" data-toggle="tooltip"
                            title="Guías que ya fueron entregadas">
                        </i>
                    </h6>
                    <h4 class="font-weight-bold" id="num_entregadas">0</h4>
                    <!-- Barra de progreso -->
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 0%;" id="progress_entrega"></div>
                    </div>
                    <!-- Porcentaje numérico -->
                    <small class="text-muted" id="percent_entrega">0%</small>
                </div>
            </div>

            <!-- Card 6: Guías en novedad -->
            <div class="col-6 col-md-2">
                <div class="card shadow-sm p-2" style="border-left: 5px solid #fd7e14;">
                    <h6 style="color: #fd7e14;">
                        <i class="bx bx-error" style="font-size: 20px; color: #fd7e14;"></i> Novedad <!-- Ícono en naranja -->
                        <i class="bx bx-help-circle text-muted" data-toggle="tooltip"
                            title="Guías que presentan alguna incidencia o novedad">
                        </i>
                    </h6>
                    <h4 class="font-weight-bold" id="num_novedad">0</h4>
                    <!-- Barra de progreso -->
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar" role="progressbar" style="width: 0%; background-color: #fd7e14;" id="progress_novedad"></div> <!-- Barra en naranja -->
                    </div>
                    <!-- Porcentaje numérico -->
                    <small class="text-muted" id="percent_novedad">0%</small>
                </div>
            </div>



            <!-- Card 7: Guías en devolución -->
            <div class="col-6 col-md-2">
                <div class="card shadow-sm p-2" style="border-left: 5px solid #dc3545;">
                    <h6 class="text-danger">
                        <i class="bx bx-undo" style="font-size: 20px;"></i> Devolución
                        <i class="bx bx-help-circle text-muted" data-toggle="tooltip"
                            title="Guías que han sido devueltas o están en proceso de devolución">
                        </i>
                    </h6>
                    <h4 class="font-weight-bold" id="num_devolucion">0</h4>
                    <!-- Barra de progreso -->
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-danger" role="progressbar" style="width: 0%;" id="progress_devolucion"></div>
                    </div>
                    <!-- Porcentaje numérico -->
                    <small class="text-muted" id="percent_devolucion">0%</small>
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
                            <option value="zona_entrega">Zona de entrega</option>
                            <option value="entregada">Entregadas</option>
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

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

        <div style="padding-top: 20px;">
            <button id="btnAplicarFiltros" class="btn btn-primary">Aplicar Filtros</button>
            <button id="imprimir_guias" class="btn btn-success">Generar Impresion</button>
        </div>
        <div class="dropdown">
            <button class="btn btn-primary dropdown-toggle" type="button" id="btnObtenerReporte" data-bs-toggle="dropdown" aria-expanded="false">
                Obtener reporte
            </button>
            <ul class="dropdown-menu" aria-labelledby="btnObtenerReporte">
                <li><a class="dropdown-item" href="#" id="downloadExcelOption">Excel</a></li>
                <li><a class="dropdown-item" href="#" id="downloadCsvOption">CSV</a></li>
            </ul>
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