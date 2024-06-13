<?php require_once './Views/templates/header.php'; ?>

<style>
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

    .table th,
    .table td {
        text-align: center;
        vertical-align: middle;
    }

    .centered {
        text-align: center !important;
        vertical-align: middle !important;
    }

    .badge_danger{
        background-color: red;
        color: white;
    }

    .badge_purple{
        background-color: #680EF2;
        color: white;
    }

    .badge_warning{
        background-color: #F2CC0E;
        color: white;
    }
    .badge_green{
        background-color: #32D814;
        color: white;
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
<div class="custom-container-fluid">
    <div class="container mt-5" style="max-width: 1600px;">
        <h2 class="text-center mb-4">Guias</h2>
        <!-- <div class="filtros_producos justify-content-between align-items-center mb-3">

        </div> -->
        <div class="table-responsive">
            <!-- <table class="table table-bordered table-striped table-hover"> -->
            <table id="datatable_guias" class="table table-striped">
                <!-- <caption>
                    DataTable.js Demo
                </caption> -->
                <thead>
                    <tr>
                        <th class="centered"># Orden</th>
                        <th class="centered">Detalle</th>
                        <th class="centered">Cliente</th>
                        <th class="centered">Provincia</th>
                        <th class="centered">Ciudad</th>
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
<script src="<?php echo SERVERURL?>/Views/Pedidos/js/guias.js"></script>
<?php require_once './Views/templates/footer.php'; ?>