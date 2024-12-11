<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Wallet/css/detalle_style.php'; ?>


<div class="custom-container-fluid">
    <div class="container mt-5" style="max-width: 1600px;">
        <h2 class="text-center mb-4">Detalle</h2>

        <div class="table-responsive">
            <!-- <table class="table table-bordered table-striped table-hover"> -->
            <table id="datatable_detalleWallet" class="table table-striped">
                <!-- Modal para Reporte -->
                <div class="modal fade" id="modalReporte" tabindex="-1" aria-labelledby="modalReporteLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">

                            <div class="modal-header">
                                <h5 class="modal-title" id="modalReporteLabel">Generar Reporte</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                            </div>

                            <div class="modal-body">
                                <form id="formReporte">
                                    <input type="hidden" id="id_plataforma_hidden" name="id_plataforma">

                                    <div class="mb-3">
                                        <label for="anio_select" class="form-label">Año:</label>
                                        <select class="form-select" id="anio_select">
                                            <option selected value="2024">2024</option>
                                            <option value="2025">2025</option>
                                            <option value="2026">2026</option>
                                            <option value="2027">2027</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="mes_select" class="form-label">Mes:</label>
                                        <select class="form-select" id="mes_select">
                                            <option value="1">Enero</option>
                                            <option value="2">Febrero</option>
                                            <option value="3">Marzo</option>
                                            <option value="4">Abril</option>
                                            <option value="5">Mayo</option>
                                            <option value="6">Junio</option>
                                            <option value="7">Julio</option>
                                            <option value="8">Agosto</option>
                                            <option value="9">Septiembre</option>
                                            <option value="10">Octubre</option>
                                            <option value="11">Noviembre</option>
                                            <option value="12">Diciembre</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="tipo_reporte" class="form-label">¿Desea reporte por día?</label>
                                        <input type="checkbox" id="tipo_reporte" class="form-check-input">
                                    </div>

                                    <!-- Contenedor para el campo de día -->
                                    <div class="mb-3 hidden" id="dia_container">
                                        <label for="dia_select" class="form-label">Día:</label>
                                        <select class="form-select" id="dia_select"></select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="tipo_select" class="form-label">¿Desea rango de fechas?</label>
                                        <input type="checkbox" id="tipo_select" class="form-check-input">
                                    </div>

                                    <!-- Contenedor para el campo de rango -->
                                    <div class="mb-3 hidden" id="rango_container">
                                        <label for="rango_select" class="form-label">Rango:</label>
                                        <input type="number" id="rango_select" class="form-control" placeholder="Ej: 10">
                                    </div>

                                </form>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                <button type="button" class="btn btn-primary" id="btnGenerarReporte">Generar Reporte</button>
                            </div>

                        </div>
                    </div>
                </div>


                <thead>
                    <tr>
                        <th class="centered">Tienda</th>
                        <th class="centered">Total Venta</th>
                        <th class="centered">Total Utilidad</th>
                        <th class="centered">Guías Pendientes</th>
                        <th class="centered">Excel</th>
                        <th class="centered">Acciones</th>
                    </tr>
                </thead>
                <tbody id="tableBody_detalleWallet"></tbody>
            </table>
        </div>
    </div>
</div>
<script src="<?php echo SERVERURL ?>/Views/Wallet/js/detalle.js"></script>
<?php require_once './Views/templates/footer.php'; ?>