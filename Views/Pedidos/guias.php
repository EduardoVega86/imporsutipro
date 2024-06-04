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
<!-- llamada de modales -->
<?php require_once './Views/Productos/Modales/atributos_index_productos.php'; ?>
<?php require_once './Views/Productos/Modales/agregar_index_productos.php'; ?>
<?php require_once './Views/Productos/Modales/editar_index_productos.php'; ?>

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
    <?php print_r($data);?>
    <div class="container mt-5" style="max-width: 1900px;">
        <h2 class="text-center mb-4">Productos</h2>
        <div class="filtros_producos justify-content-between align-items-center mb-3">

        </div>
        <div class="table-responsive">
            <!-- <table class="table table-bordered table-striped table-hover"> -->
            <table id="datatable_users" class="table table-striped">
                <!-- <caption>
                    DataTable.js Demo
                </caption> -->
                <thead>
                    <tr>
                        <th class="centered">#</th>
                        <th class="centered">Name</th>
                        <th class="centered">Email</th>
                        <th class="centered">City</th>
                        <th class="centered">Company</th>
                        <th class="centered">Status</th>
                        <th class="centered">Options</th>
                    </tr>
                </thead>
                <tbody id="tableBody_users"></tbody>
            </table>
        </div>
    </div>
</div>
<?php require_once './Views/templates/footer.php'; ?>