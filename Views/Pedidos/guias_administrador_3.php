<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Pedidos/css/guias_administrador_style.php'; ?>


<?php require_once './Views/Pedidos/Modales/informacion_plataforma.php'; ?>
<?php require_once './Views/Pedidos/Modales/detalles_factura.php'; ?>
<?php require_once './Views/Pedidos/Modales/gestionar_novedad.php'; ?>


<div class="container mx-auto px-4">
    <h2 class="text-center text-2xl font-bold mb-6">Guias - Nueva Versión</h2>

    <!-- Filtros -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
        <div>
            <label for="daterange" class="block text-sm font-medium text-gray-700">Rango de Fechas</label>
            <input type="text" id="daterange" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm">
        </div>
        <div>
            <label for="impresion" class="block text-sm font-medium text-gray-700">Impresiones</label>
            <select id="impresion" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm">
                <option value="">Todas</option>
                <option value="1">Impresas</option>
                <option value="0">No Impresas</option>
            </select>
        </div>
        <div>
            <label for="despachos" class="block text-sm font-medium text-gray-700">Despachados</label>
            <select id="despachos" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm">
                <option value="">Todas</option>
                <option value="2">Despachados</option>
                <option value="1">No Despachados</option>
                <option value="3">Devueltos</option>
            </select>
        </div>
        <div>
            <label for="estado_q" class="block text-sm font-medium text-gray-700">Estado</label>
            <select id="estado_q" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm">
                <option value="">Seleccione Estado</option>
                <option value="generada">Generada/Por Recolectar</option>
                <option value="en_transito">En tránsito/Procesamiento/En ruta</option>
                <option value="entregada">Entregada</option>
                <option value="novedad">Novedad</option>
                <option value="devolucion">Devolución</option>
            </select>
        </div>
    </div>

    <!-- Tabla -->
    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-300 divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Número de Guía</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ciudad</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transporte</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Opciones</th>
                </tr>
            </thead>
            <tbody id="tableBody_guias" class="bg-white divide-y divide-gray-200">
                <!-- Contenido dinámico -->
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
    <div class="flex items-center justify-between mt-4">
        <button id="prevPage" class="px-4 py-2 bg-gray-500 text-white rounded">Anterior</button>
        <span id="currentPage" class="text-sm text-gray-600">Página: 1</span>
        <button id="nextPage" class="px-4 py-2 bg-blue-500 text-white rounded">Siguiente</button>
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
        $("#tienda_q,#estado_q,#transporte,#impresion,#despachos").change(function() {
            initDataTable();
        });
    });
</script>
<script src="<?php echo SERVERURL ?>/Views/Pedidos/js/guias_administrador_3.js"></script>
<?php require_once './Views/templates/footer.php'; ?>