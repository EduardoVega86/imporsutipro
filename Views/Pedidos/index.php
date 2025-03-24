<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Pedidos/css/historial_style.php'; ?>
<?php require_once './Views/Pedidos/Modales/informacion_plataforma.php'; ?>
<?php require_once './Views/Pedidos/Modales/agregar_detalle_noDeseaPedido.php'; ?>
<?php require_once './Views/Pedidos/Modales/agregar_detalle_observacion.php'; ?>

<!-- Agregar CDN de Boxicons -->
<link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">

<div class="custom-container-fluid">
    <div class="container mt-5" style="max-width: 1600px;">

        <!-- 🔹 SECCIÓN DE CARDS INFORMATIVAS 🔹 -->
        <div class="row mb-4">
            <!-- Card 1: Número de pedidos -->
            <div class="col-md-3">
                <div class="card shadow-sm p-3 text-center" style="background: white; border-left: 5px solid #007bff;">
                    <h5 class="text-primary">
                        <i class="bx bx-box" style="font-size: 24px;"></i> Número de Pedidos
                        <i class="bx bx-help-circle text-muted" data-toggle="tooltip"
                            title="Cantidad total de pedidos registrados incluida las guias ya generadas"></i>
                    </h5>
                    <h3 class="font-weight-bold" id="num_pedidos">0</h3>
                </div>
            </div>

            <!-- Card 2: Guías Generadas -->
            <div class="col-md-3">
                <div class="card shadow-sm p-3 text-center" style="background: white; border-left: 5px solid #ffc107;">
                    <h5 class="text-warning">
                        <i class="bx bx-package" style="font-size: 24px;"></i> Guías Generadas
                        <i class="bx bx-help-circle text-muted" data-toggle="tooltip"
                            title="Cantidad de guías que han sido generadas"></i>
                    </h5>
                    <h3 class="font-weight-bold" id="num_guias">0</h3>
                </div>
            </div>

            <!-- Card 3: Valor de Pedidos -->
            <div class="col-md-3">
                <div class="card shadow-sm p-3 text-center" style="background: white; border-left: 5px solid #28a745;">
                    <h5 class="text-success">
                        <i class="bx bx-money" style="font-size: 24px;"></i> Valor de Pedidos
                        <i class="bx bx-help-circle text-muted" data-toggle="tooltip"
                            title="Monto total de los pedidos en el sistema"></i>
                    </h5>
                    <h3 class="font-weight-bold" id="valor_pedidos">$0.00</h3>
                </div>
            </div>

            <!-- Card 4: Confirmación -->
            <div class="col-md-3">
                <div class="card shadow-sm p-3 text-center" style="background: white; border-left: 5px solid #dc3545;">
                    <h5 class="text-danger">
                        <i class="bx bx-check-shield" style="font-size: 24px;"></i> Confirmacion <span id="id_confirmacion"></span>
                        <i class="bx bx-help-circle text-muted" data-toggle="tooltip"
                            title="Procentaje de guias o pedidos confirmados"></i>
                    </h5>
                    <h3 class="font-weight-bold" id="num_confirmaciones">0</h3>
                </div>
            </div>
        </div>

        <!-- 🔹 FILTROS (rango de fechas, estado) 🔹 -->
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
                    <label for="estado_pedido" class="col-sm-2 col-form-label">Estado</label>
                    <div>
                        <select name="estado_pedido" class="form-control" id="estado_pedido">
                            <option value=""> Todas</option>
                            <option value="1"> Pendiente </option>
                            <option value="2"> Gestionado </option>
                            <option value="3"> No desea </option>
                            <option value="4"> 1ra llamada </option>
                            <option value="5"> 2da llamada </option>
                            <option value="6"> Observación </option>
                            <option value="7"> Anulado </option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- 🔹 BOTÓN FILTRAR, SPINNER Y BÚSQUEDA 🔹 -->
        <div style="padding-top: 20px;">
            <div class="d-flex align-items-center" style="gap: 20px;">
                <!-- Botón de Aplicar Filtros -->
                <button id="btnAplicarFiltros" class="btn btn-primary">
                    Aplicar Filtros
                </button>

                <!-- Spinner (visible mientras se cargan los datos de la tabla) -->
                <div id="tableLoader" style="display: none;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                </div>

                <!-- Input de búsqueda (visible para TODAS las plataformas) -->
                <div class="input-group" style="max-width: 320px;">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" class="form-control" id="buscar_pedido"
                        placeholder="Buscar por #Orden o Cliente...">
                </div>
            </div>
        </div>

        <!-- 🔹 TABLA DE HISTORIAL DE PEDIDOS 🔹 -->
        <div class="table-responsive mt-4">
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

<!-- Tu JS principal para historial -->
<script>
    // Obtenemos el ID de la plataforma desde PHP
    const ID_PLATAFORMA = <?php echo (int)$_SESSION['id_plataforma']; ?>;

    // Definimos la URL de la API por defecto, según la plataforma
    // Si es 3280 => usamos "pedidos/cargarPedidos_imporsuit"
    // Caso contrario => usamos "pedidos/cargarTodosLosPedidos"
    let currentAPI = (ID_PLATAFORMA === 3280) ?
        "pedidos/cargarPedidos_imporsuit" :
        "pedidos/cargarTodosLosPedidos";

    let fecha_inicio = "";
    let fecha_fin = "";

    // Calcula la fecha de inicio (14 días atrás) y la fecha de fin (hoy)
    let hoy = moment();
    let haceDosSemanas = moment().subtract(13, 'days'); // Rango de 14 días

    // Asignar las fechas a las variables al cargar la página
    fecha_inicio = haceDosSemanas.format('YYYY-MM-DD') + ' 00:00:00';
    fecha_fin = hoy.format('YYYY-MM-DD') + ' 23:59:59';

    $(function() {
        // Inicializa el DateRangePicker
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
                monthNames: [
                    'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                    'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
                ],
                firstDay: 1
            },
            autoUpdateInput: true
        });

        // Evento que se dispara al aplicar un nuevo rango de fechas
        $('#daterange').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(
                picker.startDate.format('YYYY-MM-DD') + ' - ' +
                picker.endDate.format('YYYY-MM-DD')
            );
            fecha_inicio = picker.startDate.format('YYYY-MM-DD') + ' 00:00:00';
            fecha_fin = picker.endDate.format('YYYY-MM-DD') + ' 23:59:59';

            // Actualizamos la info de la tabla y las cards
            cargarCardsPedidos();
            initDataTableHistorial();
        });

        // Establece los valores iniciales en el input de fechas
        $('#daterange').val(
            haceDosSemanas.format('YYYY-MM-DD') + ' - ' + hoy.format('YYYY-MM-DD')
        );
    });
</script>

<!-- Carga tu lógica de historial (historial.js) -->
<script src="<?php echo SERVERURL ?>/Views/Pedidos/js/historial.js"></script>

<script>
    // Función para cargar las tarjetas superiores (cards)
    function cargarCardsPedidos() {
        const apiUrl = SERVERURL + 'Pedidos/cargar_cards_pedidos';
        const formData = new FormData();
        formData.append("fecha_inicio", fecha_inicio);
        formData.append("fecha_fin", fecha_fin);
        formData.append("estado_pedido", $("#estado_pedido").val());

        $.ajax({
            url: apiUrl,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json",
            success: function(data) {
                if (data) {
                    $("#num_pedidos").text(data.total_pedidos || 0);
                    $("#valor_pedidos").text(
                        data.valor_pedidos ?
                        `$${parseFloat(data.valor_pedidos).toLocaleString('en-US', 
                            { minimumFractionDigits: 2 })}` :
                        '$0.00'
                    );
                    $("#num_guias").text(data.total_guias || 0);
                    $("#num_confirmaciones").text(
                        data.porcentaje_confirmacion ?
                        `${parseFloat(data.porcentaje_confirmacion).toFixed(2)}%` :
                        '0%'
                    );
                    // Mensaje adicional
                    $("#id_confirmacion").text("de " + data.mensaje || "");
                } else {
                    console.error('No se recibieron datos válidos de la API.');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error al consumir la API:', error);
            }
        });
    }

    // Llamar a la función de cards al cargar la página
    $(document).ready(function() {
        // Inicializar tooltips (si usas Bootstrap)
        $('[data-toggle="tooltip"]').tooltip();
        // Cargar el resumen en cards
        cargarCardsPedidos();
    });
</script>

<?php require_once './Views/templates/footer.php'; ?>