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

<style>
    .primer_seccionFiltro {
        display: flex;
        flex-direction: row;
        gap: 20px;
    }

    .segunda_seccionFiltro {
        display: flex;
        flex-direction: row;
        gap: 10px;
    }

    .filtro_fecha {
        width: 100%;
        margin-top: 20px;
    }

    .filtro_impresar {
        width: 100%;
        padding-top: 8px;
    }

    .filtro_tienda {
        width: 100%;
        padding-top: 8px;
    }

    @media (max-width: 768px) {
        .primer_seccionFiltro {
            flex-direction: column;
        }

        .segunda_seccionFiltro {
            flex-direction: column;
        }

        .filtro_fecha {
            width: 100%;
        }

        .filtro_impresar {
            padding-top: 0;
        }

        .filtro_tienda {
            padding-top: 0;
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