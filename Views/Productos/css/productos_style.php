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
        background-color: <?php echo COLOR_FONDO; ?>;
        color: <?php echo COLOR_LETRAS; ?>;
    }

    .centered {
        text-align: center !important;
        vertical-align: middle !important;
    }

    .btn-excel{
        background-color: #198754;
        border: #198754;
    }

    .btn-csv{
        background-color: #198754;
        border: #198754;
    }
</style>
<style>
    .filtros_producos {
        display: flex;
        flex-direction: row;
    }

    .primerSeccion_filtros {
        display: flex;
        flex-direction: row;
    }

    @media (max-width: 768px) {
        .filtros_producos {
            flex-direction: column;
        }

        .primerSeccion_filtros {
            flex-direction: column;
            gap: 5px;
        }
    }
</style>