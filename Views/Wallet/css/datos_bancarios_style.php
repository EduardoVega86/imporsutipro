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

    /* reponsive de secciones */
    .left_right {
        display: flex;
        flex-direction: row;
    }

    .left {
        display: flex;
        flex-direction: column;
        max-width: 30%;
        min-width: 30%;
    }

    .right {
        display: flex;
        flex-direction: column;
        max-width: 60%;
        min-width: 60%;
    }

    .line {
        width: 1px;
        background-color: #000;
        margin-left: 50px;
        margin-right: 50px;
    }



    @media (max-width: 768px) {
        .left_right {
            flex-direction: column;
        }

        .left {
            max-width: 100%;
            min-width: 100%;
        }

        .right {

            max-width: 100%;
            min-width: 60%;
        }

        .line {
            width: 100%;
            height: 1px;
            margin: 0;
        }
    }
</style>