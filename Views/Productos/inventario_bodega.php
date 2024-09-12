<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Productos/css/inventario_bodega_style.php'; ?>


<div class="custom-container-fluid">
    <div class="container mt-5" style="max-width: 1600px;">
        <h2 class="text-center mb-4">Ajuste de Inventario</h2>
        <!-- <div class="filtros_producos justify-content-between align-items-center mb-3"></div>
        </div> -->
        <div class="left_right gap-2">
            <div class="flecha" onclick="redireccionar()">
                <i class="fas fa-arrow-left"></i>
            </div>
            <div class="table-responsive left">
                <!-- <table class="table table-bordered table-striped table-hover"> -->
                <table id="datatable_inventario" class="table table-striped">
                    <thead>
                        <tr>
                            <th class="centered">ID</th>
                            <th class="centered">Codigo</th>
                            <th class="centered"></th>
                            <th class="centered">Producto</th>
                            <th class="centered">Variedad</th>
                            <th class="centered">Existencia</th>
                            <th class="centered">Ajustar</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody_inventario"></tbody>
                </table>
            </div>
            <div class="right gap-2 hidden" id="inventarioSection">
                <div class="card" style="height: 550px; padding:10px">
                    <input type="hidden" id="id_inventarioStock" name="id_inventarioStock">
                    <input type="hidden" id="skuStock" name="skuStock">
                    <input type="hidden" id="id_productoStock" name="id_productoStock">
                    <input type="hidden" id="id_bodegaStock" name="id_bodegaStock">

                    <img src="tu-imagen.png" alt="Producto" id="image_stock">
                    <h6 style="padding-top: 5px;"><strong><span id="nombreeProducto_stock"></span></strong></h6>
                    <div class="stock">Existencia: <span id="existencia_stock"></span></div>
                    <hr>
                    <label for="cantidad:">Cantidad:</label>
                    <input type="text" class="form-control" id="cantidadStock" placeholder="Ingresar cantidad">
                    <label for="referencia:">Referencia:</label>
                    <input type="text" class="form-control" id="referencistock" placeholder="Ingresar referencia">
                    <button class="btn btn-add" onclick="agregar_stock()">Agregar Stock</button>
                    <button class="btn btn-delete" onclick="eliminar_stock()">Eliminar Stock</button>
                </div>
                <div class="table-responsive">
                    <!-- <table class="table table-bordered table-striped table-hover"> -->
                    <table id="datatable_stockIndividual" class="table table-striped">
                        <!-- <caption>
                    DataTable.js Demo
                </caption> -->
                        <thead>
                            <tr>
                                <th class="centered">Fecha</th>
                                <th class="centered">Descripcion</th>
                                <th class="centered">Referencia</th>
                                <th class="centered">Tipo</th>
                                <th class="centered">Total</th>
                                 <th class="centered">Saldo</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody_stockIndividual"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo SERVERURL ?>/Views/Productos/js/inventario_bodega.js"></script>
<?php require_once './Views/templates/footer.php'; ?>