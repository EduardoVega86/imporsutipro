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
    .primer_seccionFiltro {
        display: flex;
        flex-direction: row;
    }
    .segunda_seccionFiltro {
        display: flex;
        flex-direction: row;
        gap : 10px;
    }
    .filtro_fecha{
        width: 40%; 
        margin-top: 20px;
    }

    @media (max-width: 768px) {
        .primer_seccionFiltro {
            flex-direction: column;
        }
        .segunda_seccionFiltro {
            flex-direction: column;
        }
        .filtro_fecha{
            width: 100%;
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

    .btn_novedades {
        background-color: #1337EC;
        border-color: #1337EC;
        color: white;
    }

    .btn_novedades:hover {
        background-color: #102BB4;
        border-color: #102BB4;
        color: white;
    }
</style>