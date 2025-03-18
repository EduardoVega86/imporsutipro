<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Usuarios/css/listado_style.php'; ?>
<?php require_once './Views/Pedidos/Modales/agregar_plantilla.php'; ?>
<?php require_once './Views/Pedidos/Modales/editar_plantilla.php'; ?>
<?php require_once './Views/Pedidos/Modales/configuraciones_chatcenter.php'; ?>


<div class="custom-container-fluid">
    <div class="container mt-5" style="max-width: 1600px;">
        <h2 class="text-center mb-4">Lista de Templates</h2>

        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#agregar_plantillaModal"><i class="fas fa-plus"></i> Agregar</button>
        <button class="btn btn-warning" onclick="abrir_modal_configuraciones()"><i class="fas fa-plus"></i> Configuraciones</button>
        
        <div class="table-responsive">
            <!-- <table class="table table-bordered table-striped table-hover"> -->
            <table id="datatable_obtener_usuarios_plataforma" class="table table-striped">
                <thead>
                    <tr>
                        <th class="centered">ID</th>
                        <th class="centered">Atajo</th>
                         <th class="centered">Mensaje</th>
                         <th class="centered">Principal</th>
                         <th class="centered">Acciones</th>
                     
                    </tr>
                </thead>
                <tbody id="tableBody_obtener_usuarios_plataforma"></tbody>
            </table>
        </div>
    </div>
</div>
<script src="<?php echo SERVERURL ?>/Views/Pedidos/js/plantillas_chat_center.js"></script>
<?php require_once './Views/templates/footer.php'; ?>