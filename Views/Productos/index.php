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
</style>
<!-- llamada de modales -->
<?php require_once './Views/Productos/Modales/atributos_index_productos.php'; ?>
<?php require_once './Views/Productos/Modales/agregar_index_productos.php'; ?>
<?php require_once './Views/Productos/Modales/editar_index_productos.php'; ?>

<style>
    .filtros_producos{
        display: flex;
        flex-direction: row;
    }
    @media (max-width: 768px) {
        .filtros_producos{
            flex-direction: column;
        }
    }
</style>
<div class="custom-container-fluid">
    <div class="container mt-5" style="max-width: 1900px;">
        <h2 class="text-center mb-4">Productos</h2>
        <div class="filtros_producos justify-content-between align-items-center mb-3">
            <div class="d-flex">
                <input type="text" class="form-control me-2" placeholder="Código o Nombre">
                <select class="form-select me-2">
                    <option selected>-- Seleccionar Categorias --</option>
                    <option value="1">Categoria 1</option>
                    <option value="2">Categoria 2</option>
                    <option value="3">Categoria 3</option>
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
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th class="text-nowrap">ID</th>
                        <th class="text-nowrap"></th>
                        <th class="text-nowrap">Código</th>
                        <th class="text-nowrap">Producto</th>
                        <th class="text-nowrap">Destacado</th>
                        <th class="text-nowrap">Existencia</th>
                        <th class="text-nowrap">Costo</th>
                        <th class="text-nowrap">Pxmayor</th>
                        <th class="text-nowrap">PVP</th>
                        <th class="text-nowrap">Precio Referencial</th>
                        <th class="text-nowrap">Landing</th>
                        <th class="text-nowrap">Imagenes</th>
                        <th class="text-nowrap">Enviar a Marketplace</th>
                        <th class="text-nowrap">Agregado</th>
                        <th class="text-nowrap">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>616</td>
                        <td>imagen Producto</td>
                        <td>10061</td>
                        <td>ACCESORIOS BRAZO IRONMAN</td>
                        <td>Si o No</td>
                        <td>8</td>
                        <td>$2.00</td>
                        <td>$2.00</td>
                        <td>$4.00</td>
                        <td>$4.00</td>
                        <td>icono</td>
                        <td>icono</td>
                        <td>icono</td>
                        <td>29/12/2023</td>
                        <td>
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#agregar_productoModal"><i class="fas fa-edit"></i> Editar</button>
                            <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Eliminar</button>
                        </td>
                    </tr>
                    <tr>
                        <td>616</td>
                        <td>imagen Producto</td>
                        <td>10061</td>
                        <td>ACCESORIOS BRAZO IRONMAN</td>
                        <td>Si o No</td>
                        <td>8</td>
                        <td>$2.00</td>
                        <td>$2.00</td>
                        <td>$4.00</td>
                        <td>$4.00</td>
                        <td>icono</td>
                        <td>icono</td>
                        <td>icono</td>
                        <td>29/12/2023</td>
                        <td>
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#agregar_productoModal"><i class="fas fa-edit"></i> Editar</button>
                            <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Eliminar</button>
                        </td>
                    </tr>
                    <!-- Agrega más filas según sea necesario -->
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="<?php echo SERVERURL ?>/Views/Productos/js/listado.js"></script>
<!-- <script src="<?php echo SERVERURL ?>/Views/Productos/js/inventario_variable.js"></script> -->
<?php require_once './Views/templates/footer.php'; ?>