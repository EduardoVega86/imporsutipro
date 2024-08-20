<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Pedidos/css/anuladas_administrador_style.php'; ?>


<?php require_once './Views/Pedidos/Modales/informacion_plataforma.php'; ?>
<?php require_once './Views/Pedidos/Modales/gestionar_novedad.php'; ?>
<div class="custom-container-fluid">
    <div class="container mt-5" style="max-width: 1600px;">
        <h2 class="text-center mb-4">Guias Anuladas</h2>
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
                        <th class="centered">Transportadora</th>
                        <th class="centered">Estado</th>
                        <th class="centered">Impreso</th>
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
    $(function() {
        $('#daterange').daterangepicker({
            opens: 'right',
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
            autoUpdateInput: false
        });

        // Evento que se dispara cuando se aplica un nuevo rango de fechas
        $('#daterange').on('apply.daterangepicker', function(ev, picker) {
            // Actualiza el valor del input con el rango de fechas seleccionado
            $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));

            fecha_inicio = picker.startDate.format('YYYY-MM-DD') + ' 00:00:00';
            fecha_fin = picker.endDate.format('YYYY-MM-DD') + ' 23:59:59';
            initDataTable();
        });
    });

    $(document).ready(function() {
        $("#estado_q,#transporte,#impresion").change(function() {
            initDataTable();
        });
    });
</script>
<script src="<?php echo SERVERURL ?>/Views/Pedidos/js/anuladas_administrador.js"></script>
<?php require_once './Views/templates/footer.php'; ?>