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
    border: 1px solid #ddd; /* Añadir borde a celdas */
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

</style>
<!-- llamada de modales -->
<?php require_once './Views/Productos/Modales/atributos_index_productos.php'; ?>
<?php require_once './Views/Productos/Modales/agregar_index_productos.php'; ?>
<?php require_once './Views/Productos/Modales/editar_index_productos.php'; ?>
<?php require_once './Views/Productos/Modales/imagen_productos.php'; ?>
<?php require_once './Views/Productos/Modales/inventario_variable_index.php'; ?>

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
        <h2 class="text-center mb-4">Productos</h2>
        <div class="filtros_producos justify-content-between align-items-center mb-3">
            <div class="d-flex">
                <input type="text" class="form-control me-2" placeholder="Código o Nombre">
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
                <button class="btn btn-outline-secondary me-2"><i class="fas fa-file-alt"></i> Reporte</button>
                <button class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#atributosModal"><i class="fas fa-list"></i> Atributos</button>
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#agregar_productoModal"><i class="fas fa-plus"></i> Agregar</button>
            </div>
        </div>
        <div class="table-responsive">
            <!-- <table class="table table-bordered table-striped table-hover"> -->
            <table id="datatable_productos" class="table table-striped">
                <thead>
                    <tr>
                        <th class="text-nowrap">ID</th>
                        <th class="text-nowrap"></th>
                        <th class="text-nowrap">Código</th>
                        <th class="text-nowrap">Producto</th>
                        <th class="text-nowrap">Destacado</th>
                        <th class="text-nowrap">Existencia</th>
                        <th class="text-nowrap">Costo</th>
                        <th class="text-nowrap">P. Proveedor</th>
                        <th class="text-nowrap">PVP</th>
                        <th class="text-nowrap">Precio Referencial</th>
                        <th class="text-nowrap">Landing</th>
                        <th class="text-nowrap">Imagenes</th>
                        <th class="text-nowrap">Enviar a Marketplace</th>
                        <th class="text-nowrap">Atributos</th>
                        <th class="text-nowrap">Acciones</th>
                    </tr>
                </thead>
                <tbody id="tableBody_productos"></tbody>
            </table>
        </div>
    </div>
</div>
<script src="<?php echo SERVERURL ?>/Views/Productos/js/listado.js"></script>
<script src="<?php echo SERVERURL ?>/Views/Productos/js/productos.js"></script>
<script src="<?php echo SERVERURL ?>/Views/Productos/js/inventario_variable.js"></script>
<?php require_once './Views/templates/footer.php'; ?>