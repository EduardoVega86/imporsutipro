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

    .link-like {
            color: blue;
            text-decoration: underline;
            cursor: pointer;
        }
        .link-like:hover {
            color: darkblue;
        }
</style>
<?php require_once './Views/Pedidos/Modales/informacion_plataforma.php'; ?>
<div class="custom-container-fluid">
    <div class="container mt-5" style="max-width: 1600px;">
        <h2 class="text-center mb-4">Guias</h2>

        <div style="padding-top: 20px;">
            <button id="imprimir_guias" class="btn btn-success">Generar Impresion</button>
        </div>

        <div class="table-responsive">
            <!-- <table class="table table-bordered table-striped table-hover"> -->
            <table id="datatable_guias" class="table table-striped">
                <!-- <caption>
                    DataTable.js Demo
                </caption> -->
                <thead>
                    <tr>
                        <th class="centered">Nivel administrador</th>
                        <th class="centered">Agregado</th>
                        <th class="centered">Usuarios</th>
                        <th class="centered">Acciones</th>
                    </tr>
                </thead>
                <tbody id="tableBody_guias"></tbody>
            </table>
        </div>
    </div>
</div>
<script src="<?php echo SERVERURL ?>/Views/Pedidos/js/guias.js"></script>
<?php require_once './Views/templates/footer.php'; ?>