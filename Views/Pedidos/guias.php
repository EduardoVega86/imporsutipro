<?php require_once './Views/templates/header.php'; ?>

<style>
    .table {
        border-collapse: collapse;
        width: 100%;
    }

    .table th,
    .table td {
        text-align: center;
        vertical-align: middle;
        border: 1px solid #ddd;
        /* Añadir borde a celdas */
    }

    .table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(0, 0, 0, .05);
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0, 0, 0, .075);
    }

    .table thead th {
        background-color: #171931;
        color: white;
    }

    .centered {
        text-align: center !important;
        vertical-align: middle !important;
    }

    /* Diseños de estados guias */
    .badge_danger {
        background-color: red;
        color: white;
        padding: 4px;
        border-radius: 0.3rem;
    }

    .badge_purple {
        background-color: #804BD1;
        color: white;
        padding: 4px;
        border-radius: 0.3rem;
    }

    .badge_warning {
        background-color: #F2CC0E;
        color: white;
        padding: 4px;
        border-radius: 0.3rem;
    }

    .badge_green {
        background-color: #59D343;
        color: white;
        padding: 4px;
        border-radius: 0.3rem;
    }
</style>

<style>
    .filtros_producos {
        display: flex;
        flex-direction: row;
    }

    @media (max-width: 768px) {
        .filtros_producos {
            flex-direction: column;
        }
    }
</style>
<?php require_once './Views/Pedidos/Modales/informacion_plataforma.php'; ?>
<div class="custom-container-fluid">
    <div class="container mt-5" style="max-width: 1600px;">
        <h2 class="text-center mb-4">Guias</h2>
        <!-- <div class="filtros_producos justify-content-between align-items-center mb-3">

        </div> -->
        <div class="d-flex flex-column justify-content-between">
            <div class="d-flex flex-row " style="width: 100%;">
                <div class="d-flex flex-row align-items-end" style="width: 40%;">
                    <div class="flex-fill" style="margin: 0; padding-left: 0;">
                        <h6>Seleccione fecha de inicio:</h6>
                        <div class="input-group date" id="datepickerInicio">
                            <input type="text" class="form-control" name="fechaInicio">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="flex-fill" style="padding-left: 15px; ">
                        <h6>Seleccione fecha de fin:</h6>
                        <div class="input-group date" id="datepickerFin">
                            <input type="text" class="form-control" name="fechaFin">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                            </div>
                        </div>
                    </div>
                    <div style=" padding-top: 10px; padding-left: 10px;">
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-info waves-effect waves-light">
                                Buscar <span class="fa fa-search"></span></button>
                        </span>
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
                                <option value="0">Selecciona una Tienda</option>

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
                            <option value="0"> Seleccione Estado </option>
                        </select>
                    </div>
                </div>
                <div style="width: 100%;">
                    <label style="padding-left: 20px;" for="inputPassword3" class="col-sm-2 col-form-label">Transportadora</label>
                    <div style="padding-left: 20px;">
                        <select name="transporte" id="transporte" class="form-control">
                            <option value="0"> Seleccione Transportadora</option>
                            <option value="LAAR">Laar</option>
                            <option value="IMPORFAST">Speed</option>
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
<script src="<?php echo SERVERURL ?>/Views/Pedidos/js/guias.js"></script>
<?php require_once './Views/templates/footer.php'; ?>