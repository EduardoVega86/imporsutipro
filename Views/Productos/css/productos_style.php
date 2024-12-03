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

    .botones_principales {
        display: flex;
    }

    @media (max-width: 768px) {
        .filtros_producos {
            flex-direction: column;
        }

        .primerSeccion_filtros {
            flex-direction: column;
            gap: 5px;
        }

        .botones_principales {
            flex-direction: column;
            gap: 5px;
        }
    }

    .btn-outline-custom {
        border: 2px solid #4B0082;
        /* Borde púrpura */
        color: #4B0082;
        /* Texto púrpura */
        background-color: transparent;
        /* Fondo transparente */
        border-radius: 5px;
        /* Esquinas ligeramente redondeadas */
        transition: background-color 0.3s ease, color 0.3s ease, transform 0.3s ease;
        /* Transiciones para hover */
    }

    .btn-outline-custom:hover {
        background-color: #4B0082;
        /* Fondo púrpura al hacer hover */
        color: #ffffff;
        /* Texto blanco al hacer hover */
        transform: scale(1.05);
        /* Aumento de tamaño al hacer hover */
    }

    .btn-outline-custom i {
        margin-right: 8px;
        /* Espacio entre el icono y el texto */
    }
</style>