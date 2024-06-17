<?php require_once './Views/templates/header.php'; ?>

<style>
    .table {
        border-collapse: collapse;
        width: 100%;
    }

    .table th,
    .table td {
        text-align: center;
        vertical-align: middle;
        border: 1px solid #ddd;
        /* Añadir borde a celdas */
    }

    .table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(0, 0, 0, .05);
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0, 0, 0, .075);
    }

    .table thead th {
        background-color: #171931;
        color: white;
    }

    .centered {
        text-align: center !important;
        vertical-align: middle !important;
    }

    /* Diseños de estados guias */
    .badge_danger {
        background-color: red;
        color: white;
        padding: 4px;
        border-radius: 0.3rem;
    }

    .badge_purple {
        background-color: #804BD1;
        color: white;
        padding: 4px;
        border-radius: 0.3rem;
    }

    .badge_warning {
        background-color: #F2CC0E;
        color: white;
        padding: 4px;
        border-radius: 0.3rem;
    }

    .badge_green {
        background-color: #59D343;
        color: white;
        padding: 4px;
        border-radius: 0.3rem;
    }
</style>

<style>
    .filtros_producos {
        display: flex;
        flex-direction: row;
    }

    @media (max-width: 768px) {
        .filtros_producos {
            flex-direction: column;
        }
    }

    /* Diseño de Cards */
    .card {
        border: 1px solid #ddd;
        border-radius: 8px;
        width: 200px;
        padding: 15px;
        text-align: center;
        background-color: #fff;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .card img {
        width: 100%;
        height: auto;
        border-radius: 8px;
    }

    .card .stock {
        color: red;
        font-weight: bold;
        margin: 10px 0;
        background-color: #f8d7da;
        padding: 5px;
        border-radius: 4px;
    }

    .card .btn {
        display: block;
        width: 100%;
        padding: 10px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        margin: 5px 0;
    }

    .card .btn-add {
        background-color: #28a745;
        color: white;
    }

    .card .btn-delete {
        background-color: #dc3545;
        color: white;
    }

    .vertical-line {
        border-left: 2px solid black;
        height: 100px;
        /* Ajusta la altura según tus necesidades */
        position: absolute;
        left: 50%;
        /* Ajusta la posición horizontal según tus necesidades */
    }

    .hidden {
        display: none !important;
    }

    /* reponsive de secciones */
    .left_right {
        display: flex;
        flex-direction: row;
    }

    .left {
        max-width: 37%;
    }

    .right {
        display: flex;
        flex-direction: row;
        max-width: 63%;
    }

    @media (max-width: 768px) {
        .left_right {
            flex-direction: column;
        }

        .left {
            max-width: 100%;
        }

        .right {
            flex-direction: column;
            max-width: 100%;
        }
    }

    .flecha {
        font-size: 30px;
        cursor: pointer;
        display: inline-block;
        padding: 10px;
        transition: transform 0.2s;
        text-decoration: none;
        color: black;
    }

    .flecha:hover {
        transform: scale(1.02);
    }
</style>
<div class="custom-container-fluid">
    <div class="container mt-5" style="max-width: 1600px;">
        <h2 class="text-center mb-4">Ajusto de Inventario</h2>
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