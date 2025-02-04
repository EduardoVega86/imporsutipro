<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Pedidos/css/historial_style.php'; ?>
<?php require_once './Views/Pedidos/Modales/informacion_plataforma.php'; ?>
<?php require_once './Views/Pedidos/Modales/agregar_detalle_noDeseaPedido.php'; ?>

<!-- Agregar CDN de Boxicons -->
<link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">

<div class="custom-container-fluid">
    <div class="container mt-5" style="max-width: 1600px;">
        <h2 class="text-center mb-4">Historial de Pedidos</h2>

        

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
            
        </div>

        <!-- TABLA DE HISTORIAL DE PEDIDOS -->
        <div class="table-responsive">
            <table id="datatable_historialPedidos" class="table table-striped">
                <thead>
                    <tr>
                        <th class="centered"># Orden</th>
                        <th class="centered">Fecha</th>
                        <th class="centered">Canal de venta</th>
                        <th class="centered">Cliente</th>
                        <th class="centered">Destino</th>
                        <th class="centered">Contiene</th>
                        <th class="centered">Monto</th>
                        <th class="centered">Estado Pedido</th>
                        <th class="centered">Acciones</th>
                    </tr>
                </thead>
                <tbody id="tableBody_historialPedidos"></tbody>
            </table>
        </div>
    </div>
</div>

<script src="<?php echo SERVERURL ?>/Views/Pedidos/js/historial.js"></script>

<!-- ACTIVAR TOOLTIP PARA DESCRIPCIONES -->
<script>
    $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip();
    });

    // Simulación de datos (reemplazar con datos reales desde AJAX)
    document.getElementById('num_pedidos').innerText = 150;
    document.getElementById('valor_pedidos').innerText = "$45,000.00";
    document.getElementById('num_guias').innerText = 120;
    document.getElementById('num_confirmaciones').innerText = 135;
</script>

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
            initDataTableHistorial();
        });

        // Establece los valores iniciales en el input de fechas
        $('#daterange').val(haceUnaSemana.format('YYYY-MM-DD') + ' - ' + hoy.format('YYYY-MM-DD'));
    });

    $(document).ready(function() {
        // Inicializa la tabla cuando cambian los selectores
        /* $("#tienda_q,#estado_q,#transporte,#impresion,#despachos").change(function() {
            initDataTable();
        }); */
    });
</script>

<?php require_once './Views/templates/footer.php'; ?>