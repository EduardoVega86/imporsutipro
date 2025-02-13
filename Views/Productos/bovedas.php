<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Productos/Modales/agregar_bovedas.php'; ?>
<?php require_once './Views/Productos/Modales/editar_bovedas.php'; ?>
<?php require_once './Views/Productos/css/bovedas_style.php'; ?>



<div class="custom-container-fluid">
    <div class="container mt-5" style="max-width: 1600px;">
        <button type="button"
            class="btn btn-success"
            data-bs-toggle="modal"
            data-bs-target="#modalAgregarBoveda">
            Agregar
        </button>

        <!-- Loader que se mostrará únicamente sobre el área de la tabla -->
        <div id="bovedasLoader" style="display: none;">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
        </div>

        <!-- Container -->
        <div class="table-responsive">
            <table id="datatable_bovedas" class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Categoría</th>
                        <th>Proveedor</th>
                        <th>Imagen</th>
                        <th>Plantilla de Ventas</th>
                        <th>Ejempo Landing</th>
                        <th>Duplicar Funnel</th>
                        <th>Videos</th>
                        <th>Fecha de Carga</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
                <tbody id="tableBody_bovedas">
                    <!-- Se cargan dinamicamente -->
                </tbody>

            </table>

        </div>
    </div>
</div>
<script src="<?php echo SERVERURL ?>/Views/Productos/js/boveda.js"></script>
<?php require_once './Views/templates/footer.php'; ?>