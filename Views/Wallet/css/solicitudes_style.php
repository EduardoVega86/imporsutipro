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

    .btn-excel,
    .btn-csv {
        background-color: #198754;
        /* Color verde para Excel */
        color: white;
        padding: 5px 10px;
        /* Ajusta el padding para reducir el tamaño */
        margin: 0;
        /* Elimina el margen */
        border: none;
        /* Elimina el borde */
    }

    .btn-group {
        align-content: center;
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

    .badge_generado {
        background-color: #54DD10;
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

    .btn-outline-custom {
        border: 2px solid #4B0082; /* Borde púrpura */
        color: #4B0082; /* Texto púrpura */
        background-color: transparent; /* Fondo transparente */
        border-radius: 5px; /* Esquinas ligeramente redondeadas */
        transition: background-color 0.3s ease, color 0.3s ease, transform 0.3s ease; /* Transiciones para hover */
    }

    .btn-outline-custom:hover {
        background-color: #4B0082; /* Fondo púrpura al hacer hover */
        color: #ffffff; /* Texto blanco al hacer hover */
        transform: scale(1.05); /* Aumento de tamaño al hacer hover */
    }

    .btn-outline-custom i {
        margin-right: 8px; /* Espacio entre el icono y el texto */
    }
</style>