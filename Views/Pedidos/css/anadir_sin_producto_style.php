<style>
    /* Contenedor principal con flexbox */
    .content-wrapper {
        display: flex;
        flex-wrap: wrap;
        /* Permite que los elementos se apilen en pantallas pequeñas */
        justify-content: center;
        gap: 20px;
        max-width: 1600px;
        margin: auto;
        padding: 20px;
    }

    /* Diseño de la tabla */
    .table-container {
        flex: 1;
        background: #ffffff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        overflow-x: auto;
        /* Para scroll horizontal si la tabla es muy grande */
    }

    /* Tabla responsive */
    .table-container table {
        width: 100%;
        min-width: 600px;
        /* Asegura que la tabla tenga un mínimo de ancho */
    }

    .table thead {
        background: #007bff;
        color: white;
    }

    .table tbody tr:hover {
        background: #f1f1f1;
    }

    /* Línea vertical divisoria */
    .divider {
        width: 3px;
        background: #007bff;
        height: auto;
        border-radius: 5px;
    }

    /* Diseño de los labels de la factura */
    .info-container {
        flex: 1;
        background: #ffffff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    /* Etiquetas de información */
    .info-label {
        font-weight: bold;
        color: #007bff;
    }

    .info-value {
        font-size: 18px;
        color: #333;
        padding-left: 10px;
    }

    /* Botones responsivos */
    .d-flex button {
        width: 100%;
        /* Ocupa todo el ancho en móviles */
        margin-bottom: 5px;
        /* Espaciado entre botones */
    }

    /* Responsive */
    @media (max-width: 768px) {
        .content-wrapper {
            flex-direction: column;
            /* Apila los elementos */
        }

        .divider {
            display: none;
            /* Oculta la línea divisoria en móviles */
        }

        .table-container,
        .info-container {
            width: 100%;
            /* Ocupa todo el ancho en móviles */
            padding: 15px;
        }

        .table-container table {
            min-width: 100%;
            /* Ajusta la tabla al 100% en móviles */
        }

        .table thead {
            display: none;
            /* Oculta los encabezados en móviles */
        }

        .table tbody,
        .table tr,
        .table td {
            display: block;
            width: 100%;
        }

        .table tbody tr {
            margin-bottom: 10px;
            border: 1px solid #ddd;
            padding: 10px;
        }

        .table td {
            text-align: right;
            position: relative;
            padding-left: 50%;
        }

        .table td::before {
            content: attr(data-label);
            position: absolute;
            left: 10px;
            font-weight: bold;
            color: #007bff;
        }
    }
</style>