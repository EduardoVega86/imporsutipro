<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Pedidos/css/historial_style.php'; ?>
<?php require_once './Views/Pedidos/Modales/informacion_plataforma.php'; ?>
<?php require_once './Views/Pedidos/Modales/agregar_detalle_noDeseaPedido.php'; ?>

<!-- Agregar CDN de Boxicons -->
<link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">

<div class="custom-container-fluid">
    <div class="container mt-5" style="max-width: 1600px;">
        <h2 class="text-center mb-4">Historial de Pedidos</h2>

        <!-- 🔹 SECCIÓN DE CARDS INFORMATIVAS 🔹 -->
        <div class="row mb-4">
            <!-- Card 1: Número de pedidos -->
            <div class="col-md-3">
                <div class="card shadow-sm p-3 text-center" style="background: white; border-left: 5px solid #007bff;">
                    <h5 class="text-primary">
                        <i class="bx bx-box" style="font-size: 24px;"></i> Número de Pedidos
                        <i class="bx bx-help-circle text-muted" data-toggle="tooltip" title="Cantidad total de pedidos registrados"></i>
                    </h5>
                    <h3 class="font-weight-bold" id="num_pedidos">0</h3>
                </div>
            </div>

            <!-- Card 2: Valor de pedidos -->
            <div class="col-md-3">
                <div class="card shadow-sm p-3 text-center" style="background: white; border-left: 5px solid #28a745;">
                    <h5 class="text-success">
                        <i class="bx bx-money" style="font-size: 24px;"></i> Valor de Pedidos
                        <i class="bx bx-help-circle text-muted" data-toggle="tooltip" title="Monto total de los pedidos en el sistema"></i>
                    </h5>
                    <h3 class="font-weight-bold" id="valor_pedidos">$0.00</h3>
                </div>
            </div>

            <!-- Card 3: Número de guías confirmadas -->
            <div class="col-md-3">
                <div class="card shadow-sm p-3 text-center" style="background: white; border-left: 5px solid #ffc107;">
                    <h5 class="text-warning">
                        <i class="bx bx-package" style="font-size: 24px;"></i> Guías Confirmadas
                        <i class="bx bx-help-circle text-muted" data-toggle="tooltip" title="Cantidad de guías que han sido confirmadas"></i>
                    </h5>
                    <h3 class="font-weight-bold" id="num_guias">0</h3>
                </div>
            </div>

            <!-- Card 4: Número de confirmaciones -->
            <div class="col-md-3">
                <div class="card shadow-sm p-3 text-center" style="background: white; border-left: 5px solid #dc3545;">
                    <h5 class="text-danger">
                        <i class="bx bx-check-shield" style="font-size: 24px;"></i> Confirmaciones
                        <i class="bx bx-help-circle text-muted" data-toggle="tooltip" title="Número total de pedidos confirmados"></i>
                    </h5>
                    <h3 class="font-weight-bold" id="num_confirmaciones">0</h3>
                </div>
            </div>
        </div>

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

<script>
    let fecha_inicio = "";
    let fecha_fin = "";

    // Calcula la fecha de inicio (hace 14 días) y la fecha de fin (hoy)
    let hoy = moment();
    let haceDosSemanas = moment().subtract(13, 'days'); // Rango de 14 días

    // Asignar las fechas a las variables al cargar la página
    fecha_inicio = haceDosSemanas.format('YYYY-MM-DD') + ' 00:00:00';
    fecha_fin = hoy.format('YYYY-MM-DD') + ' 23:59:59';

    $(function() {
        $('#daterange').daterangepicker({
            opens: 'right',
            startDate: haceDosSemanas, // Fecha de inicio predefinida
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
        $('#daterange').val(haceDosSemanas.format('YYYY-MM-DD') + ' - ' + hoy.format('YYYY-MM-DD'));
    });


    $(document).ready(function() {
        // Inicializa la tabla cuando cambian los selectores
        /* $("#tienda_q,#estado_q,#transporte,#impresion,#despachos").change(function() {
            initDataTable();
        }); */
    });
</script>

<script>
    $(document).ready(function () {
        // Inicializar tooltips
        $('[data-toggle="tooltip"]').tooltip();

        // URL de tu API (reemplazar con la URL real de tu backend)
        const apiUrl = SERVERURL+'Pedidos/cargar_cards_pedidos';

        // Crear el objeto FormData
        const formData = new FormData();
        formData.append("fecha_inicio", fecha_inicio); // fecha_inicio ya está definida globalmente
        formData.append("fecha_fin", fecha_fin); // fecha_fin ya está definida globalmente

        // Realizar la solicitud AJAX
        $.ajax({
            url: apiUrl,
            method: 'POST', // Enviar la solicitud como POST
            data: formData, // Pasar el FormData
            processData: false, // Evitar que jQuery procese los datos
            contentType: false, // Evitar que jQuery configure el tipo de contenido
            success: function (response) {
                // Verificar si la respuesta es exitosa
                if (response) {
                    // Actualizar los valores en los elementos HTML
                    document.getElementById('num_pedidos').innerText = response.total_pedidos || 0;
                    document.getElementById('valor_pedidos').innerText = response.valor_pedidos
                        ? `$${parseFloat(response.valor_pedidos).toLocaleString('en-US', { minimumFractionDigits: 2 })}`
                        : '$0.00';
                    document.getElementById('num_guias').innerText = response.total_guias || 0;
                    document.getElementById('num_confirmaciones').innerText = response.porcentaje_confirmacion
                        ? `${response.porcentaje_confirmacion}%`
                        : '0%';
                } else {
                    console.error('No se recibieron datos válidos de la API.');
                }
            },
            error: function (xhr, status, error) {
                // Manejar errores de la solicitud
                console.error('Error al consumir la API:', error);
            }
        });
    });
</script>

<?php require_once './Views/templates/footer.php'; ?>