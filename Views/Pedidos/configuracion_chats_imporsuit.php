<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Pedidos/css/configuracion_chats_impursuit_style.php'; ?>
<?php require_once './Views/Pedidos/Modales/agregar_configuracion_automatizador.php'; ?>
<?php require_once './Views/Pedidos/Modales/agregar_automatizador.php'; ?>


<div class="custom-container-fluid">
    <div class="container mt-5" style="max-width: 1600px;">
        <h2 class="text-center mb-4">Lista de Configuraciones</h2>

        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#agregar_configuracion_automatizadorModal" style="display:none;" id="boton_agregar_configuracion"><i class="fas fa-plus"></i> Agregar</button>
        <div class="table-responsive">
            <!-- <table class="table table-bordered table-striped table-hover"> -->
            <table id="datatable_configuracion_automatizador" class="table table-striped">
                <thead>
                    <tr>
                        <th class="centered">ID</th>
                        <th class="centered">Nombre configuración</th>
                        <th class="centered">Telefono</th>
                        <th class="centered">webhook_url</th>
                        <th class="centered">token</th>
                        <th class="centered">Acciones</th>
                    </tr>
                </thead>
                <tbody id="tableBody_configuracion_automatizador"></tbody>
            </table>
        </div>
    </div>
</div>
<script src="<?php echo SERVERURL ?>/Views/Pedidos/js/configuracion_chats_impursuit.js"></script>
<?php require_once './Views/templates/footer.php'; ?>