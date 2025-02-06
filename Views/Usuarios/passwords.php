<?php require_once './Views/templates/header.php'; ?>
<!-- Modal para cambiar contraseña -->
<div class="modal fade" id="passwordModal" tabindex="-1" aria-labelledby="passwordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="passwordModalLabel">Cambiar Contraseña</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="passwordForm">
                    <!-- Input oculto para almacenar el ID del usuario -->
                    <input type="hidden" id="userId" name="userId">

                    <!-- Campo para la nueva contraseña -->
                    <div class="mb-3">
                        <label for="password" class="form-label">Nueva Contraseña</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password" name="password" required minlength="4">
                            <button type="button" class="btn btn-secondary" id="generatePassword">Generar por defecto</button>
                        </div>
                    </div>

                    <!-- Botones de acción -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="">
    <div class="container mt-5" style="max-width: 1600px;">
        <h2 class="text-center mb-4">Listado General de Usuarios</h2>
        <div class="table-responsive">
            <!-- <table class="table table-bordered table-striped table-hover"> -->
            <table id="lista_usuarios" class="table table-striped table-bordered table-hover table-responsive">
                <thead>
                    <tr>
                        <th class="centered">ID</th>
                        <th class="centered">Nombres</th>
                        <th class="centered">Email</th>
                        <th class="centered">Telefono</th>
                        <th class="centered">Tienda</th>
                        <th class="centered">Fecha de Ingreso </th>
                        <th class="centered">Acciones</th>
                    </tr>
                </thead>
                <tbody id="data_lista_usuarios"></tbody>
            </table>
        </div>
    </div>
</div>
<script src="<?php echo SERVERURL ?>/Views/Usuarios/js/passwords.js"></script>
<?php require_once './Views/templates/footer.php'; ?>