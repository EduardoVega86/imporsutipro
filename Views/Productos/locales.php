<?php require_once './Views/templates/header.php'; ?>

<style>
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0,0,0,.05);
        }
        .table-hover tbody tr:hover {
            background-color: rgba(0,0,0,.075);
        }
        .table thead th {
            background-color: #007bff;
            color: white;
        }
        .table th, .table td {
            text-align: center;
            vertical-align: middle;
        }
    </style>

<div class="custom-container-fluid">
    <div class="container mt-5">
        <h2 class="text-center mb-4">Tabla de Ejemplo</h2>
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