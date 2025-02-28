<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Pedidos/css/historial_style.php'; ?>
<?php require_once './Views/Pedidos/Modales/informacion_plataforma.php'; ?>
<?php require_once './Views/Pedidos/Modales/agregar_detalle_noDeseaPedido.php'; ?>
<?php require_once './Views/Pedidos/Modales/agregar_detalle_observacion.php'; ?>

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
                        <i class="bx bx-help-circle text-muted" data-toggle="tooltip" title="Cantidad total de pedidos registrados incluida las guías ya generadas"></i>
                    </h5>
                    <h3 class="font-weight-bold" id="num_pedidos">0</h3>
                </div>
            </div>

            <!-- Card 2: Guías Generadas -->
            <div class="col-md-3">
                <div class="card shadow-sm p-3 text-center" style="background: white; border-left: 5px solid #ffc107;">
                    <h5 class="text-warning">
                        <i class="bx bx-package" style="font-size: 24px;"></i> Guías Generadas
                        <i class="bx bx-help-circle text-muted" data-toggle="tooltip" title="Cantidad de guías que han sido generadas"></i>
                    </h5>
                    <h3 class="font-weight-bold" id="num_guias">0</h3>
                </div>
            </div>

            <!-- Card 3: Valor de Pedidos -->
            <div class="col-md-3">
                <div class="card shadow-sm p-3 text-center" style="background: white; border-left: 5px solid #28a745;">
                    <h5 class="text-success">
                        <i class="bx bx-money" style="font-size: 24px;"></i> Valor de Pedidos
                        <i class="bx bx-help-circle text-muted" data-toggle="tooltip" title="Monto total de los pedidos en el sistema"></i>
                    </h5>
                    <h3 class="font-weight-bold" id="valor_pedidos">$0.00</h3>
                </div>
            </div>

            <!-- Card 4: Confirmación -->
            <div class="col-md-3">
                <div class="card shadow-sm p-3 text-center" style="background: white; border-left: 5px solid #dc3545;">
                    <h5 class="text-danger">
                        <i class="bx bx-check-shield" style="font-size: 24px;"></i> Confirmación <span id="id_confirmacion"></span>
                        <i class="bx bx-help-circle text-muted" data-toggle="tooltip" title="Porcentaje de guías o pedidos confirmados"></i>
                    </h5>
                    <h3 class="font-weight-bold" id="num_confirmaciones">0</h3>
                </div>
            </div>
        </div>

        <!-- Filtros -->
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
                <div class="d-flex flex-column justify-content-start">
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
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div style="padding-top: 20px;">
            <button id="btnAplicarFiltros" class="btn btn-primary">Aplicar Filtros</button>
        </div>

        <!-- Loader de la tabla -->
        <div class="table-container" style="position: relative;">
            <div id="tableLoader" style="display: none;">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
            </div>
        </div>

        <!-- Botones para cambiar API -->
        <div class="d-flex mb-3 mt-3">
            <button id="btnPedidos" class="btn btn-primary me-2 active">Pedidos</button>
            <button id="btnAnulados" class="btn btn-secondary me-2">Anulados</button>
            <button id="btnNo_vinculados" class="btn btn-secondary">No Vinculados</button>
        </div>

        <!-- Tabla de Historial de Pedidos -->
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
    // Definir la URL de la API por defecto (Pedidos)
    let currentAPI = "pedidos/cargarPedidos_imporsuit";
    let fecha_inicio = "";
    let fecha_fin = "";

    // Calcula la fecha de inicio (hace 14 días) y la fecha de fin (hoy)
    let hoy = moment();
    let haceDosSemanas = moment().subtract(13, 'days');

    // Asignar las fechas a las variables al cargar la página
    fecha_inicio = haceDosSemanas.format('YYYY-MM-DD') + ' 00:00:00';
    fecha_fin = hoy.format('YYYY-MM-DD') + ' 23:59:59';

    $(function() {
        $('#daterange').daterangepicker({
            opens: 'right',
            startDate: haceDosSemanas,
            endDate: hoy,
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
            autoUpdateInput: true
        });

        $('#daterange').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
            fecha_inicio = picker.startDate.format('YYYY-MM-DD') + ' 00:00:00';
            fecha_fin = picker.endDate.format('YYYY-MM-DD') + ' 23:59:59';

            cargarCardsPedidos();
            initDataTableHistorial();
        });

        $('#daterange').val(haceDosSemanas.format('YYYY-MM-DD') + ' - ' + hoy.format('YYYY-MM-DD'));
    });

    // Eventos para cambiar el endpoint de la API según el botón seleccionado
    document.getElementById("btnPedidos").addEventListener("click", () => {
        currentAPI = "pedidos/cargarPedidos_imporsuit";
        cambiarBotonActivo("btnPedidos");
        initDataTableHistorial();
    });

    document.getElementById("btnAnulados").addEventListener("click", () => {
        currentAPI = "pedidos/cargarPedidosAnulados";
        cambiarBotonActivo("btnAnulados");
        initDataTableHistorial();
    });

    document.getElementById("btnNo_vinculados").addEventListener("click", () => {
        currentAPI = "pedidos/cargar_pedidos_sin_producto";
        cambiarBotonActivo("btnNo_vinculados");
        initDataTableHistorial();
    });

    // Función para consumir la API y listar los pedidos en la tabla
    const listHistorialPedidos = async () => {
        try {
            const formData = new FormData();
            formData.append("fecha_inicio", fecha_inicio);
            formData.append("fecha_fin", fecha_fin);
            formData.append("estado_pedido", $("#estado_pedido").val());

            const response = await fetch(`${SERVERURL}${currentAPI}`, {
                method: "POST",
                body: formData,
            });

            const historialPedidos = await response.json();
            let content = ``;
            historialPedidos.forEach((historialPedido) => {
                content += `
                    <tr>
                        <td>${historialPedido.numero_factura}</td>
                        <td>${historialPedido.fecha_factura}</td>
                        <td>${historialPedido.canal_venta || ''}</td>
                        <td>${historialPedido.nombre}</td>
                        <td>${historialPedido.provinciaa} - ${historialPedido.ciudad}</td>
                        <td>${historialPedido.contiene}</td>
                        <td>$ ${historialPedido.monto_factura}</td>
                        <td>${historialPedido.estado_pedido}</td>
                        <td>
                            <button class="btn btn-sm btn-primary" onclick="boton_editarPedido(${historialPedido.id_factura})"><i class="fa-solid fa-pencil"></i></button>
                            <button class="btn btn-sm btn-danger" onclick="boton_anularPedido(${historialPedido.id_factura})"><i class="fa-solid fa-trash-can"></i></button>
                        </td>
                    </tr>
                `;
            });
            document.getElementById("tableBody_historialPedidos").innerHTML = content;
        } catch (ex) {
            alert(ex);
        }
    };

    // Inicialización de DataTable para la tabla de historial
    let dataTableHistorial;
    let dataTableHistorialIsInitialized = false;

    const dataTableHistorialOptions = {
        columnDefs: [{
            className: "centered",
            targets: [0, 1, 2, 3, 4, 5, 6]
        }, ],
        order: [
            [1, "desc"]
        ],
        pageLength: 10,
        destroy: true,
        responsive: true,
        language: {
            lengthMenu: "Mostrar _MENU_ registros por página",
            zeroRecords: "Ningún usuario encontrado",
            info: "Mostrando de _START_ a _END_ de un total de _TOTAL_ registros",
            infoEmpty: "Ningún usuario encontrado",
            infoFiltered: "(filtrados desde _MAX_ registros totales)",
            search: "Buscar:",
            loadingRecords: "Cargando...",
            paginate: {
                first: "Primero",
                last: "Último",
                next: "Siguiente",
                previous: "Anterior",
            },
        },
    };

    const initDataTableHistorial = async () => {
        showTableLoader();
        try {
            if (dataTableHistorialIsInitialized) {
                dataTableHistorial.destroy();
            }

            await listHistorialPedidos();

            dataTableHistorial = $("#datatable_historialPedidos").DataTable(dataTableHistorialOptions);
            dataTableHistorialIsInitialized = true;
        } catch (error) {
            console.error("Error al cargar la tabla:", error);
        } finally {
            hideTableLoader();
        }
    };

    // Funciones para mostrar y ocultar el loader de la tabla
    function showTableLoader() {
        $("#tableLoader")
            .html('<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Cargando...</span></div>')
            .css("display", "flex");
    }

    function hideTableLoader() {
        $("#tableLoader").css("display", "none");
    }

    // Función para cambiar la clase activa de los botones
    const cambiarBotonActivo = (botonID) => {
        document.querySelectorAll(".d-flex button").forEach((btn) => {
            btn.classList.remove("active", "btn-primary");
            btn.classList.add("btn-secondary");
        });
        const botonActivo = document.getElementById(botonID);
        botonActivo.classList.remove("btn-secondary");
        botonActivo.classList.add("btn-primary", "active");
    };

    $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip();
        cargarCardsPedidos();
        initDataTableHistorial();
    });
</script>

<script>
    // Función para cargar las cards de pedidos
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
                console.error('Error al consumir la API:', error);
            }
        });
    }
</script>

<?php require_once './Views/templates/footer.php'; ?>