<?php require_once './Views/templates/header.php'; ?>
<link rel="stylesheet" type="text/css" href="<?php echo SERVERURL ?>/Views/Productos/css/marketplace.css">
<?php require_once './Views/Productos/Modales/descripcion_marketplace.php'; ?>

<div class="custom-container-fluid mt-4">
    <div class="row mb-3">
        <div class="col-md-4 mb-3 mb-md-0">
            <input type="text" class="form-control" placeholder="Código o Nombre">
        </div>
        <div class="col-md-4 mb-3 mb-md-0">
            <select class="form-control">
                <option>-- Selecciona Línea --</option>
                <option>Línea 1</option>
                <option>Línea 2</option>
                <option>Línea 3</option>
            </select>
        </div>
        <div class="col-md-3 mb-3 mb-md-0">
            <select class="form-control">
                <option>Selecciona una opción</option>
                <option>Opción 1</option>
                <option>Opción 2</option>
                <option>Opción 3</option>
            </select>
        </div>
        <div class="col-md-1">
            <button class="btn btn-warning w-100"><i class="fa fa-search"></i></button>
        </div>
    </div>
    <div id="card-container" class="card-container">
        <!-- Tarjetas de productos se insertarán aquí -->
    </div>
    <nav aria-label="Page navigation" style="padding-bottom: 10px;">
        <ul class="pagination justify-content-center" id="pagination">
            <!-- Botones de paginación se insertarán aquí -->
        </ul>
    </nav>
</div>


<script src="<?php echo SERVERURL ?>/Views/Productos/js/marketplace.js"></script>
<?php require_once './Views/templates/footer.php'; ?>