<style>
    .table {
        border-collapse: collapse;
        width: 100%;
        max-width: 100%;
        /* Se asegura de no superar el 100% del contenedor */
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

    /* Contenedor para el scroll horizontal si la tabla excede el ancho */
    .table-responsive {
        width: 100%;
        max-width: 100%;
        overflow-x: auto;
    }

    #bovedasLoader {
        position: absolute;
        top: -18px;
        width: 54%;
        height: 100%;
        background: rgba(255, 255, 255, 0.8);
        /* Fondo semitransparente */
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        /* Asegura que esté por encima de la tabla */
    }
</style>