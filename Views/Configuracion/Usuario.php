<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Configuracion/css/usuario_style.php'; ?>

<?php require_once './Views/Pedidos/Modales/informacion_plataforma.php'; ?>
<div class="custom-container-fluid">
    <div class="container mt-5" style="max-width: 1600px;">
        <h2 class="text-center mb-4">Guias</h2>

        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#agregar_productoModal"><i class="fas fa-plus"></i> Agregar</button>
        <div class="table-responsive">
            <!-- <table class="table table-bordered table-striped table-hover"> -->
            <table id="datatable_guias" class="table table-striped">
                <thead>
                    <tr>
                        <th class="centered">ID</th>
                        <th class="centered">Nombres</th>
                        <th class="centered">Usuario</th>
                        <th class="centered">Email</th>
                        <th class="centered">Agregado</th>
                        <th class="centered">Acciones</th>
                    </tr>
                </thead>
                <tbody id="tableBody_guias"></tbody>
            </table>
        </div>
    </div>
</div>
<script src="<?php echo SERVERURL ?>/Views/Configuracion/js/usuario.js"></script>
<?php require_once './Views/templates/footer.php'; ?>