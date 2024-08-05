<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Productos/css/gestion_privados_style.php'; ?>

<div class="custom-container-fluid">
    <div class="container mt-5" style="max-width: 1600px;">
        <h2 class="text-center mb-4">Ajuste de Inventario</h2>
        <!-- <div class="filtros_producos justify-content-between align-items-center mb-3"></div>
        </div> -->
        <div class="left_right gap-2">
            <div class="table-responsive left">
                <!-- <table class="table table-bordered table-striped table-hover"> -->
                <table id="datatable_inventario" class="table table-striped" style="min-width: 100%;">
                    <thead>
                        <tr>
                            <th class="centered">ID</th>
                            <th class="centered"></th>
                            <th class="centered">Codigo</th>
                            <th class="centered">Producto</th>
                            <th class="centered">Ajustar</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody_inventario"></tbody>
                </table>
            </div>
            <div class="right gap-2 hidden" id="inventarioSection">
                <div class="card" style="height: auto; padding: 10px;">
                    <div class="form-group" id="tiendas-field">
                        <input type="hidden" id="id_producto_privado" name="id_producto_privado">
                        <label for="tiendas">Tiendas:</label>
                        <select class="form-select" id="select_tiendas">
                            <option value="0" selected>Selecciona tiendas</option>
                        </select>
                    </div>

                    <!-- Contenedor de Información de la Tienda -->
                    <div id="informacion_tienda" class="card mt-3 p-3" style="display: none; max-width: 100%; border: 1px solid #ddd; border-radius: 5px;">
                        <img src="tu-imagen.png" alt="Producto" id="image_tienda" class="img-fluid rounded" style="max-height: 200px; margin-bottom: 15px;">

                        <h6 class="text-center mb-3"><strong><span id="nombre_tienda"></span></strong></h6>

                        <hr class="mb-3">

                        <div class="d-flex flex-column gap-2">
                            <label for="url"><strong>URL: </strong><span id="url"></span></label>
                            <label for="telefono"><strong>Teléfono: </strong><span id="telefono"></span></label>
                            <label for="correo"><strong>Correo: </strong><span id="correo"></span></label>
                        </div>

                        <button class="btn btn-primary btn-block mt-3" onclick="agregar_tienda()">Agregar tienda</button>
                    </div>
                </div>
                <div class="table-responsive">
                    <!-- <table class="table table-bordered table-striped table-hover"> -->
                    <table id="datatable_stockIndividual" class="table table-striped" style="min-width: 100%;">
                        <!-- <caption>
                    DataTable.js Demo
                </caption> -->
                        <thead>
                            <tr>
                                <th class="centered">Nombre tienda</th>
                                <th class="centered">Correo</th>
                                <th class="centered">Telefono</th>
                                <th class="centered">URL</th>
                                <th class="centered">Accción</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody_stockIndividual"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo SERVERURL ?>/Views/Productos/js/gestion_privados.js"></script>
<?php require_once './Views/templates/footer.php'; ?>