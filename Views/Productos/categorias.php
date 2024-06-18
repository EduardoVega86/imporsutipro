<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Productos/css/categorias_style.php'; ?>


<?php require_once './Views/Productos/Modales/agregar_categoria_productos.php'; ?>
<?php require_once './Views/Productos/Modales/editar_categoria_productos.php'; ?>
<?php require_once './Views/Productos/Modales/imagen_categorias_productos.php'; ?>

<div class="custom-container-fluid">
    <div class="container mt-5" style="max-width: 1600px;">
        <h2 class="text-center mb-4">Categorias</h2>
        <!-- <div class="filtros_producos justify-content-between align-items-center mb-3">

        </div> -->
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#agregar_categoriaModal"><i class="fas fa-plus"></i> Agregar</button>
        <div class="table-responsive">
            <!-- <table class="table table-bordered table-striped table-hover"> -->
            <table id="datatable_categorias" class="table table-striped">
                <!-- <caption>
                    DataTable.js Demo
                </caption> -->
                <thead>
                    <tr>
                        <th class="centered">Nombre</th>
                        <th class="centered">Imagen</th>
                        <th class="centered">Online</th>
                        <th class="centered">Descripción</th>
                        <th class="centered">Tipo</th>
                        <!-- <th class="centered">Padre</th> -->
                        <th class="centered">Estado</th>
                        <th class="centered">Acciones</th>
                    </tr>
                </thead>
                <tbody id="tableBody_categorias"></tbody>
            </table>
        </div>
    </div>
</div>
<script src="<?php echo SERVERURL ?>/Views/Productos/js/categorias.js"></script>
<?php require_once './Views/templates/footer.php'; ?>