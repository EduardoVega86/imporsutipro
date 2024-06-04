<?php require_once './Views/templates/header.php'; ?>

<style>
    .table-responsive {
    overflow-x: auto;
}

.table thead th {
    background-color: #171931;
    color: white;
}

.table th,
.table td {
    text-align: center;
    vertical-align: middle;
}

.centered {
    text-align: center !important;
    vertical-align: middle !important;
}

/* Ajustes para pantallas pequeñas */
@media (max-width: 768px) {
    .table th, .table td {
        display: block;
        width: 100%;
        text-align: left;
    }
    
    .table thead {
        display: none;
    }
    
    .table tr {
        margin-bottom: 1rem;
        border-bottom: 2px solid #ddd;
    }
    
    .table td {
        position: relative;
        padding-left: 50%;
    }
    
    .table td::before {
        content: attr(data-label);
        position: absolute;
        left: 0;
        width: 50%;
        padding-left: 15px;
        font-weight: bold;
    }
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
</style>
<div class="custom-container-fluid">
    <div class="container mt-5" style="max-width: 1900px;">
        <h2 class="text-center mb-4">Guias</h2>
        <!-- <div class="filtros_producos justify-content-between align-items-center mb-3">

        </div> -->
        <div class="table-responsive">
            <!-- <table class="table table-bordered table-striped table-hover"> -->
            <table id="datatable_guias" class="table table-striped">
                <!-- <caption>
                    DataTable.js Demo
                </caption> -->
                <thead>
                    <tr>
                        <th class="centered"># Orden</th>
                        <th class="centered">Detalle</th>
                        <th class="centered">Cliente</th>
                        <th class="centered">Localidad</th>
                        <th class="centered">Tienda</th>
                        <th class="centered">Transportadora</th>
                        <th class="centered">Estado</th>
                        <th class="centered">Impreso</th>
                        <th class="centered">Acciones</th>
                    </tr>
                </thead>
                <tbody id="tableBody_guias"></tbody>
            </table>
        </div>
    </div>
</div>
<script src="<?php echo SERVERURL?>/Views/Pedidos/js/guias.js"></script> 
<?php require_once './Views/templates/footer.php'; ?>