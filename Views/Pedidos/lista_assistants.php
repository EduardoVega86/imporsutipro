<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Pedidos/css/lista_assistants_style.php'; ?>
<?php require_once './Views/Pedidos/Modales/agregar_assistmant.php'; ?>


<div class="custom-container-fluid">
    <div class="container mt-5" style="max-width: 1600px;">
        <h2 class="text-center mb-4">Lista de Configuraciones</h2>

        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#agregar_assistmantModal"><i class="fas fa-plus"></i> Agregar</button>
        <div class="table-responsive">
            <!-- <table class="table table-bordered table-striped table-hover"> -->
            <table id="datatable_configuracion_automatizador" class="table table-striped">
                <thead>
                    <tr>
                        <th class="centered">Nombre bot</th>
                        <th class="centered">assistant_id</th>
                        <th class="centered">api_key</th>
                        <th class="centered">activo </th>
                        <th class="centered">Acciones</th>
                    </tr>
                </thead>
                <tbody id="tableBody_configuracion_automatizador"></tbody>
            </table>
        </div>
    </div>
</div>
<script src="<?php echo SERVERURL ?>/Views/Pedidos/js/lista_assistants.js"></script>
<?php require_once './Views/templates/footer.php'; ?>