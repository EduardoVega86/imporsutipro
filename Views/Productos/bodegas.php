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
        <h2 class="text-center mb-4">Bodegas</h2>
        <!-- <div class="filtros_producos justify-content-between align-items-center mb-3">

        </div> -->
        <a href="https://new.imporsuitpro.com/Productos/agregar_bodegas" class="btn btn-success">
            <i class="fas fa-plus"></i> Agregar
        </a>
        <div class="table-responsive">
            <!-- <table class="table table-bordered table-striped table-hover"> -->
            <table id="datatable_bodegas" class="table table-striped">
                <!-- <caption>
                    DataTable.js Demo
                </caption> -->
                <thead>
                    <tr>
                        <th class="centered">ID</th>
                        <th class="centered">Nombre</th>
                        <th class="centered">Direccion</th>
                        <th class="centered">Ciudad</th>
                        <th class="centered">Responsable</th>
                        <th class="centered">Telefono</th>
                        <th class="centered">Acciones</th>
                    </tr>
                </thead>
                <tbody id="tableBody_bodegas"></tbody>
            </table>
        </div>
    </div>
</div>
<script src="<?php echo SERVERURL ?>/Views/Productos/js/bodegas.js"></script>
<?php require_once './Views/templates/footer.php'; ?>