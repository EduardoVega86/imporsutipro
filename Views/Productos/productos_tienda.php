<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Productos/css/productos_tienda_style.php'; ?>


<!-- llamada de modales -->
<?php require_once './Views/Productos/Modales/editar_productoTienda.php'; ?>
<?php require_once './Views/Productos/Modales/imagen_productos_tienda.php'; ?>

<div class="custom-container-fluid">
    <div class="container mt-5" style="max-width: 1600px;">
        <h2 class="text-center mb-4">Productos de Tienda</h2>
        <!-- <div class="filtros_producos justify-content-between align-items-center mb-3">
            <div class="primerSeccion_filtros">
                <select id="categoria_filtro" class="form-select me-2">
                    <option selected value="">-- Seleccionar Categorías --</option>
                </select>

                <button class="btn btn-outline-secondary me-2"><i class="fas fa-search"></i></button>
                <div class="form-check align-self-center">
                    <input class="form-check-input" type="checkbox" id="habilitarDestacados">
                    <label class="form-check-label" for="habilitarDestacados">
                        Habilitar Destacados
                    </label>
                </div>
            </div>
            <div class="d-flex">
                <button class="btn btn-outline-secondary me-2" id="subidaMasiva_marketplace"><i class="fas fa-file-alt"></i> Subir Marketplace</button>
                <button class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#atributosModal"><i class="fas fa-list"></i> Atributos</button>
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#agregar_productoModal"><i class="fas fa-plus"></i> Agregar</button>
            </div>
        </div> -->
        <div class="table-responsive">
            <!-- <table class="table table-bordered table-striped table-hover"> -->
            <table id="datatable_productos" class="table table-striped">
                <thead>
                    <tr>
                        <th class="text-nowrap">Nombre</th>
                        <th class="text-nowrap"></th>
                        <th class="text-nowrap">Destacado</th>
                        <th class="text-nowrap">Landing</th>
                        <th class="text-nowrap">PVP</th>
                        <th class="text-nowrap">P. Ref</th>
                        <th class="text-nowrap">Acciones</th>
                    </tr>
                </thead>
                <tbody id="tableBody_productos"></tbody>
            </table>
        </div>
    </div>
</div>

<script src="<?php echo SERVERURL ?>/Views/Productos/js/productos_tienda.js"></script>

<?php require_once './Views/templates/footer.php'; ?>