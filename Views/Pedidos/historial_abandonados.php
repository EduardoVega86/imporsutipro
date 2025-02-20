<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Pedidos/css/historial_style.php'; ?>
<?php require_once './Views/Pedidos/Modales/informacion_plataforma.php'; ?>
<?php require_once './Views/Pedidos/Modales/agregar_detalle_noDeseaPedido.php'; ?>
<?php require_once './Views/Pedidos/Modales/agregar_detalle_observacion.php'; ?>

<!-- Agregar CDN de Boxicons -->
<link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">

<div class="custom-container-fluid">
    <div class="container mt-5" style="max-width: 1600px;">
        <h2 class="text-center mb-4">Historial de Abandonados</h2>

        <div class="d-flex mb-3 mt-3">
            <button id="btnTodos" class="btn btn-primary me-2 active">Todos</button>
            <button id="btnContactados" class="btn btn-secondary me-2">Contactados</button>
            <button id="btnNo_Contactados" class="btn btn-secondary">No Contactados</button>
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

<script src="<?php echo SERVERURL ?>/Views/Pedidos/js/historial_abandonados.js"></script>

<script>
    // Definir la URL de la API por defecto (Pedidos)
    let currentAPI = "";
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
            cargarCardsPedidos();
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
    // Definir la función para consumir la API
    function cargarCardsPedidos() {
        // URL de tu API (reemplazar con la URL real de tu backend)
        const apiUrl = SERVERURL + 'Pedidos/cargar_cards_pedidos';

        // Crear el objeto FormData
        const formData = new FormData();
        formData.append("fecha_inicio", fecha_inicio); // Parámetro de fecha de inicio
        formData.append("fecha_fin", fecha_fin); // Parámetro de fecha de fin

        // Realizar la solicitud AJAX
        $.ajax({
            url: apiUrl,
            method: 'POST', // Enviar la solicitud como POST
            data: formData, // Pasar el FormData
            processData: false, // Evitar que jQuery procese los datos
            contentType: false, // Evitar que jQuery configure el tipo de contenido
            dataType: "json", // Indicar que la respuesta es JSON
            success: function(data) {
                // Verificar si se recibieron los datos correctamente
                if (data) {
                    // Actualizar los valores en las tarjetas usando jQuery
                    $("#num_pedidos").text(data.total_pedidos || 0);
                    $("#valor_pedidos").text(
                        data.valor_pedidos ?
                        `$${parseFloat(data.valor_pedidos).toLocaleString('en-US', { minimumFractionDigits: 2 })}` :
                        '$0.00'
                    );
                    $("#num_guias").text(data.total_guias || 0);
                    $("#num_confirmaciones").text(
                        data.porcentaje_confirmacion ?
                        `${parseFloat(data.porcentaje_confirmacion).toFixed(2)}%` :
                        '0%'
                    );

                    $("#id_confirmacion").text("de " + data.mensaje || "");
                } else {
                    console.error('No se recibieron datos válidos de la API.');
                }
            },
            error: function(xhr, status, error) {
                // Manejar errores de la solicitud
                console.error('Error al consumir la API:', error);
            }
        });
    }

    // Ejemplo de uso: ejecutar la función cuando se cargue la página
    $(document).ready(function() {
        // Inicializar tooltips
        $('[data-toggle="tooltip"]').tooltip();

        // Llamar a la función con valores de ejemplo (reemplazar por los reales)
        cargarCardsPedidos(); // Llama a la API con las fechas inicial y final
    });
</script>

<?php require_once './Views/templates/footer.php'; ?>