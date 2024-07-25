<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Usuarios/css/listamatriz_style.php'; ?>

<?php require_once './Views/Usuarios/Modales/cambiarClave_usuario.php'; ?>
<div class="custom-container-fluid">
    <div class="container mt-5" style="max-width: 1600px;">
        <h2 class="text-center mb-4">Lista de Usuarios Matriz</h2>

        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#agregar_productoModal"><i class="fas fa-plus"></i> Agregar</button>
        <div class="table-responsive">
            <!-- <table class="table table-bordered table-striped table-hover"> -->
            <table id="datatable_lista_usuarioMatriz" class="table table-striped">
                <thead>
                    <tr>
                        <th class="centered">ID</th>
                        <th class="centered">Nombre Tienda</th>
                        <th class="centered">Contacto</th>
                        <th class="centered">Telefono</th>
                        <th class="centered">URL</th>
                        <th class="centered">Correo</th>
                        <th class="centered">Ocultar</th>
                    </tr>
                </thead>
                <tbody id="tableBody_lista_usuarioMatriz"></tbody>
            </table>
        </div>
    </div>
</div>
<script src="<?php echo SERVERURL ?>/Views/Usuarios/js/listamatriz.js"></script>
<?php require_once './Views/templates/footer.php'; ?>