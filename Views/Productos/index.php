<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Productos/css/productos_style.php'; ?>


<!-- llamada de modales -->
<?php require_once './Views/Productos/Modales/atributos_index_productos.php'; ?>
<?php require_once './Views/Productos/Modales/agregar_index_productos.php'; ?>
<?php require_once './Views/Productos/Modales/editar_index_productos.php'; ?>
<?php require_once './Views/Productos/Modales/imagen_productos.php'; ?>
<?php require_once './Views/Productos/Modales/inventario_variable_index.php'; ?>
<?php require_once './Views/Productos/Modales/Seleccion_productoAtributo.php'; ?>
<?php require_once './Views/Productos/Modales/tabla_idInventario.php'; ?>
<?php require_once './Views/Productos/Modales/landing_block_editor.php'; ?>


<div class="custom-container-fluid">
    <div class="container mt-5" style="max-width: 1600px;">
        <h2 class="text-center mb-4">Productos</h2>
        <div class="filtros_producos justify-content-between align-items-center mb-3">
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
                <button id="agregar_producto" class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#agregar_productoModal"><i class="fas fa-plus"></i> Agregar</button>
                <button class="btn btn-light btn-outline-custom me-2" onclick="window.location.href='<?php echo SERVERURL; ?>Productos/gestion_privados'">
                    <i class="fas fa-arrow-right"></i> Gestionar productos privados
                </button>
            </div>
        </div>
        <div class="table-responsive">
            <!-- <table class="table table-bordered table-striped table-hover"> -->
            <table id="datatable_productos" class="table table-striped">
                <thead>
                    <tr>
                        <th class="centered"><input type="checkbox" id="selectAll"></th>
                        <th class="text-nowrap">ID</th>
                        <th class="text-nowrap">Imagenes</th>
                        <th class="text-nowrap">Código</th>
                        <th class="text-nowrap">Producto</th>
                        <th class="text-nowrap">Destacado</th>
                        <th class="text-nowrap">Existencia</th>
                        <th class="text-nowrap">Costo</th>
                        <th class="text-nowrap">P. Proveedor</th>
                        <th class="text-nowrap">PVP</th>
                        <th class="text-wrap">Precio Referencial</th>
                        <th class="text-nowrap">Landing</th>
                        <th class="text-wrap">Marketplace</th>
                        <th class="text-wrap">Enviar a cliente</th>
                        <th class="text-nowrap">Atributos</th>
                        <th class="text-nowrap">Enviar a Tienda</th>
                        <th>Agregar Privado</th> <!-- Añadir columna para el checkbox de agregar_privado -->
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
<script src="<?php echo SERVERURL ?>/Views/Productos/js/tablaSeleccion_Producto.js"></script>
<?php require_once './Views/templates/footer.php'; ?>