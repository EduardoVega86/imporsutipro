<?php require_once './Views/templates/header.php'; ?>

<style>
    .table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(0, 0, 0, .05);
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0, 0, 0, .075);
    }

    .table thead th {
        background-color: #007bff;
        color: white;
    }

    .table th,
    .table td {
        text-align: center;
        vertical-align: middle;
    }
</style>

<div class="custom-container-fluid">
    <div class="container mt-5">
        <h2 class="text-center mb-4">Productos</h2>
        <div class="d-flex justify-content-between align-items-center mb-3">
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
                <button class="btn btn-primary me-2"><i class="fas fa-list"></i> Atributos</button>
                <button class="btn btn-success"><i class="fas fa-plus"></i> Agregar</button>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>Juan Pérez</td>
                        <td>juan.perez@example.com</td>
                        <td>+123456789</td>
                        <td>
                            <button class="btn btn-primary btn-sm"><i class="fas fa-edit"></i> Editar</button>
                            <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Eliminar</button>
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>María López</td>
                        <td>maria.lopez@example.com</td>
                        <td>+987654321</td>
                        <td>
                            <button class="btn btn-primary btn-sm"><i class="fas fa-edit"></i> Editar</button>
                            <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Eliminar</button>
                        </td>
                    </tr>
                    <!-- Agrega más filas según sea necesario -->
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php require_once './Views/templates/footer.php'; ?>