<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Usuarios/css/listado_style.php'; ?>

<div class="custom-container-fluid">
    <div class="container mt-5" style="max-width: 1600px;">
        <h2 class="text-center mb-4">Lista de Usuarios</h2>

        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#agregar_productoModal"><i class="fas fa-plus"></i> Agregar</button>
        <div class="table-responsive">
            <!-- <table class="table table-bordered table-striped table-hover"> -->
            <table id="datatable_obtener_usuarios_plataforma" class="table table-striped">
                <thead>
                    <tr>
                        <th class="centered">ID</th>
                        <th class="centered">Nombres</th>
                        <th class="centered">Usuario</th>
                        <th class="centered">Email</th>
                        <th class="centered">Telefono</th>
                        <th class="centered">Tienda</th>
                        <th class="centered">Agregado</th>
                        <th class="centered">Acciones</th>
                    </tr>
                </thead>
                <tbody id="tableBody_obtener_usuarios_plataforma"></tbody>
            </table>
        </div>
    </div>
</div>
<script src="<?php echo SERVERURL ?>/Views/Usuarios/js/listado.js"></script>
<?php require_once './Views/templates/footer.php'; ?>