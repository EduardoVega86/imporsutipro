<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Usuarios/css/plataformas_style.php'; ?>

<div class="custom-container-fluid">
    <div class="container mt-5" style="max-width: 1600px;">
        <h2 class="text-center mb-4">Lista de Plataformas</h2>

        <div class="table-responsive">
            <!-- <table class="table table-bordered table-striped table-hover"> -->
            <table id="datatable_plataformas" class="table table-striped">
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
                <tbody id="tableBody_plataformas"></tbody>
            </table>
        </div>
    </div>
</div>
<script src="<?php echo SERVERURL ?>/Views/Usuarios/js/plataformas.js"></script>
<?php require_once './Views/templates/footer.php'; ?>