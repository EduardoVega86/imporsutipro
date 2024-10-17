<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Usuarios/css/listado_style.php'; ?>
<?php require_once './Views/Pedidos/Modales/agregar_plantilla.php'; ?>
<?php require_once './Views/Usuarios/Modales/editar_usuario.php'; ?>


<div class="custom-container-fluid">
    <div class="container mt-5" style="max-width: 1600px;">
        <h2 class="text-center mb-4">Lista de Usuarios</h2>

        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#agregar_usuarioModal"><i class="fas fa-plus"></i> Agregar</button>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#agregar_repartidorModal"><i class="fa-solid fa-motorcycle"></i> Agregar repartidor</button>
        <div class="table-responsive">
            <!-- <table class="table table-bordered table-striped table-hover"> -->
            <table id="datatable_obtener_usuarios_plataforma" class="table table-striped">
                <thead>
                    <tr>
                        <th class="centered">ID</th>
                        <th class="centered">Atajo</th>
                         <th class="centered">Mensaje</th>
                     
                    </tr>
                </thead>
                <tbody id="tableBody_obtener_usuarios_plataforma"></tbody>
            </table>
        </div>
    </div>
</div>
<script src="<?php echo SERVERURL ?>/Views/Pedidos/js/plantillas_chat_center.js"></script>
<?php require_once './Views/templates/footer.php'; ?>