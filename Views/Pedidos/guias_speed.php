<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Pedidos/css/guias_speed_style.php'; ?>


<?php require_once './Views/Pedidos/Modales/informacion_plataforma.php'; ?>
<?php require_once './Views/Pedidos/Modales/detalles_factura.php'; ?>
<?php require_once './Views/Pedidos/Modales/gestionar_novedad.php'; ?>
<?php require_once './Views/Pedidos/Modales/gestionar_novedadSpeed.php'; ?>
<?php require_once './Views/Pedidos/Modales/subir_recibo_speed.php'; ?>
<?php require_once './Views/Pedidos/Modales/subir_direccion_speed.php'; ?>

<div class="custom-container-fluid">
    <div class="container mt-5" style="max-width: 1600px;">
        <h2 class="text-center mb-4">Guias Speed</h2>
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
                            <option value=""> Seleccione Estado </option>
                            <option value="(estado_guia_sistema in (100,102,103) and id_transporte=2)
                            OR (estado_guia_sistema in (1,2) and id_transporte=1)
                            or (estado_guia_sistema in (1,2,3) and id_transporte=3)
                            or (estado_guia_sistema in (2) and id_transporte=4)"> Generada/ Por Recolectar </option>
                            <option value="(estado_guia_sistema BETWEEN 200 AND 202 and id_transporte=2)
                            OR (estado_guia_sistema in (5,11,12,6) and id_transporte=1)
                            OR (estado_guia_sistema in (5,4) and id_transporte=3)
                            OR (estado_guia_sistema in (3) and id_transporte=4)"> En Transito </option>
                            <option value="(estado_guia_sistema BETWEEN 400 AND 403   and id_transporte=2)
                            OR  (estado_guia_sistema in (7)  and id_transporte=1)
                            OR  (estado_guia_sistema in (7)  and id_transporte=3)"> Entregada </option>
                            <option value="(estado_guia_sistema BETWEEN 320 AND 351 and id_transporte=2)
                            OR  (estado_guia_sistema in (14) and id_transporte=1)
                            OR  (estado_guia_sistema in (6) and id_transporte=3)"> Novedad </option>
                            <option value="(estado_guia_sistema BETWEEN 500 AND 502 and id_transporte=2)
                            OR (estado_guia_sistema in (9) and id_transporte=2)
                            OR (estado_guia_sistema in (9) and id_transporte=4)
                            OR (estado_guia_sistema in (8,9,13) and id_transporte=3)"> Devolucion </option>
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
                <div style="width: 100%;">
                    <label for="inputPassword3" class="col-sm-2 col-form-label">Recibo</label>
                    <div>
                        <select name="recibo" id="recibo" class="form-control">
                            <option value=""> Seleccione Transportadora</option>
                            <option value="1">Con recibo</option>
                            <option value="0">Sin recibo</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div style="padding-top: 20px;">


            <button id="imprimir_guias" class="btn btn-success">Generar Impresion</button>
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
                        <th class="centered"># Orden</th>
                        <th class="centered">Detalle</th>
                        <th class="centered">Cliente</th>
                        <th class="centered">Destino</th>
                        <th class="centered">Tienda</th>
                        <th class="centered">Proveedor</th>
                        <th class="centered">Motorizado</th>
                        <th class="centered">Transportadora</th>
                        <th class="centered">Ruta Google</th>
                        <th class="centered">Estado</th>
                        <th class="centered">Despachado</th>
                        <th class="centered">Impreso</th>
                        <th class="centered">Recibo</th>
                        <th class="centered">Acciones</th>
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

        // Evento que se dispara cuando se aplica un nuevo rango de fechas
        $('#daterange').on('apply.daterangepicker', function(ev, picker) {
            // Actualiza el valor del input con el rango de fechas seleccionado
            $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));

            // Actualizar las variables con las nuevas fechas seleccionadas
            fecha_inicio = picker.startDate.format('YYYY-MM-DD') + ' 00:00:00';
            fecha_fin = picker.endDate.format('YYYY-MM-DD') + ' 23:59:59';
            initDataTable();
        });

        // Establece los valores iniciales en el input de fechas
        $('#daterange').val(haceUnaSemana.format('YYYY-MM-DD') + ' - ' + hoy.format('YYYY-MM-DD'));
    });

    $(document).ready(function() {
        // Inicializa la tabla cuando cambian los selectores
        $("#tienda_q,#estado_q,#transporte,#impresion,#despachos,#recibo").change(function() {
            initDataTable();
        });
    });
</script>
<script src="<?php echo SERVERURL ?>/Views/Pedidos/js/guias_speed.js"></script>
<?php require_once './Views/templates/footer.php'; ?>