<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Pedidos/css/guias_style.php'; ?>


<?php require_once './Views/Pedidos/Modales/informacion_plataforma.php'; ?>
<?php require_once './Views/Pedidos/Modales/novedades.php'; ?>
<div class="custom-container-fluid">
    <div class="container mt-5" style="max-width: 1600px;">
        <h2 class="text-center mb-4">Guias</h2>
        <!-- <div class="filtros_producos justify-content-between align-items-center mb-3">

        </div> -->
        <div class="d-flex flex-column justify-content-between">
            <div class="d-flex flex-row " style="width: 100%;">
                <div class="d-flex flex-row align-items-end" style="width: 40%; margin-top: 20px;">
                    <div class="flex-fill">
                        <h6>Seleccione el rango de fechas:</h6>
                        <div class="input-group">
                            <input type="text" class="form-control" id="daterange">
                            <span class="input-group-text"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                        </div>
                    </div>
                </div>
                <div class="flex-fill" style=" padding-left: 20px; width:35%">
                    <div class=" d-flex flex-row justify-content-start">
                        <input class="input-change" type="checkbox" role="switch" id="facturas_impresas">
                        <label class="form-check-label" for="flexSwitchCheckChecked" style="padding-left: 10px;">Facturas Impresas</label>
                    </div>
                </div>
                <div class="flex-fill">

                </div>
            </div>

            <div class="d-flex flex-row">
                <div class="d-flex flex-column" style="width: 100%;">
                    <div class="d-flex flex-row justify-content-start">
                        <div style="width: 100%;">
                            <label for="tienda_q" class="col-form-label">Tienda</label>
                            <select onchange="buscar(this.value)" id="tienda_q" class="form-control">
                                <option value="">Selecciona una Tienda</option>

                            </select>
                        </div>
                    </div>
                    <div style="width: 100%;">


                    </div>
                </div>
                <div style="width: 100%;">
                    <label style="padding-left: 20px;" for="inputPassword3" class="col-sm-2 col-form-label">Estado</label>
                    <div style="padding-left: 20px;">
                        <select onchange="buscar_estado(this.value)" name="estado_q" class="form-control" id="estado_q">
                            <option value=""> Seleccione Estado </option>
                            <option value="1"> Anulado </option>
                            <option value="2"> Por Recolectar </option>
                            <option value="5"> En Transito </option>
                            <option value="7"> Entregado </option>
                            <option value="14"> Con Novedad </option>
                            <option value="9"> Devuelto </option>
                        </select>
                    </div>
                </div>
                <div style="width: 100%;">
                    <label style="padding-left: 20px;" for="inputPassword3" class="col-sm-2 col-form-label">Transportadora</label>
                    <div style="padding-left: 20px;">
                        <select name="transporte" id="transporte" class="form-control">
                            <option value=""> Seleccione Transportadora</option>
                            <option value="1">Laar</option>
                            <option value="2">Speed</option>
                            <option value="3">Servientrega</option>
                            <option value="4">Gintracom</option>
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
                }
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
        $("#estado_q,#transporte").change(function() {
            initDataTable();
        });
    });
</script>
<script src="<?php echo SERVERURL ?>/Views/Pedidos/js/guias.js"></script>
<?php require_once './Views/templates/footer.php'; ?>