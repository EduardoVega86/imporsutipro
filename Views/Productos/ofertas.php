<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Productos/css/ofertas_style.php'; ?>
<?php /* require_once './Views/Productos/Modales/agregar_oferta.php' */; ?>

<div class="custom-container-fluid">
    <div class="container mt-5" style="max-width: 1600px;">
        <h2 class="text-center mb-4">Lista de Usuarios</h2>

        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#agregar_usuarioModal"><i class="fas fa-plus"></i> Agregar</button>
        <div class="table-responsive">
            <!-- <table class="table table-bordered table-striped table-hover"> -->
            <table id="datatable_ofertas" class="table table-striped">
                <thead>
                    <tr>
                        <th class="centered">Nombre oferta</th>
                        <th class="centered">Precio oferta</th>
                        <th class="centered">Cantidad</th>
                        <th class="centered">fecha inicio</th>
                        <th class="centered">fecha fin</th>
                        <th class="centered">nombre producto</th>
                        <th class="centered">imagen</th>
                        <th class="centered">Acciones</th>
                    </tr>
                </thead>
                <tbody id="tableBody_ofertas"></tbody>
            </table>
        </div>
    </div>
</div>
<script src="<?php echo SERVERURL ?>/Views/Productos/js/ofertas.js"></script>
<?php require_once './Views/templates/footer.php'; ?>