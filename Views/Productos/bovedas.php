<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Productos/Modales/agregar_bovedas.php'; ?>



<div class="custom-container-fluid">
    <div class="container mt-5" style="max-width: 1600px;">
        <button type="button"
            class="btn btn-primary mb-3"
            data-bs-toggle="modal"
            data-bs-target="#modalAgregarBoveda">
            Agregar Nuevo
        </button>

        <!-- Container -->
        <div class="table-responsive">
            <table id="datatable_bovedas" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Categoría</th>
                        <th>Proveedor</th>
                        <th>Ejempo Landing</th>
                        <th>Duplicar Funnel</th>
                        <th>Videos</th>
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