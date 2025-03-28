<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Productos/css/bodegas_style.php'; ?>


<div class="custom-container-fluid">
    <div class="container mt-5" style="max-width: 1600px;">
        <h2 class="text-center mb-4">Bodegas</h2>
        <!-- <div class="filtros_producos justify-content-between align-items-center mb-3">

        </div> -->
        <a href="<?php echo SERVERURL ?>Productos/agregar_bodegas" class="btn btn-success">
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
<?php loadViewScripts("Productos", "listar_bodega"); ?>
<?php require_once './Views/templates/footer.php'; ?>