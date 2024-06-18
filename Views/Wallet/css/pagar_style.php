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
</style>

<style>
    /* reponsive de secciones */
    .left_right {
        display: flex;
        flex-direction: row;
    }

    .left {
        max-width: 37%;
    }

    .right {
        width: 100%;
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
            max-width: 100%;
        }
    }

    /* diseño de iconos con botones */
    .icon-button {
        background-color: #007bff;
        /* Color de fondo azul */
        border: none;
        border-radius: 5px;
        color: white;
        /* Color del icono */
        padding: 5px;
        font-size: 16px;
        cursor: pointer;
    }

    .icon-button i {
        margin-right: 5px;
    }

    .icon-button:hover {
        background-color: #0056b3;
        /* Color de fondo al pasar el ratón */
    }
</style>