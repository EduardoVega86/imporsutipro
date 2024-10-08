<style>
    .stat-box {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        padding: 20px;
        margin: 10px;
        text-align: center;
        flex: 1 1 calc(25% - 40px);
        width: 50%;
    }

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

    /* dashboard auditoria */
    .dashboard_auditoria {
        display: flex;
        flex-direction: row;
    }

    @media (max-width: 768px) {
        .dashboard_auditoria {
            flex-direction: column;
            align-items: center;
        }

        .stat-box {
            width: 100%;
        }
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

    .filter-container {
        margin-bottom: 10px;
    }

    .filter-btn {
        background-color: #007bff;
        color: white;
        border: none;
        padding: 10px 20px;
        margin-right: 5px;
        cursor: pointer;
        border-radius: 5px;
        transition: background-color 0.3s;
    }

    .filter-btn:hover {
        background-color: #0056b3;
    }

    .filter-btn.active {
        background-color: #0056b3;
        font-weight: bold;
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

    /* boton si y no  */
    .btn-cod-si {
        background-color: green;
        color: white;
        border: none;
        padding: 5px 10px;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .btn-cod-si:hover {
        background-color: darkgreen;
    }

    .btn-cod-no {
        background-color: red;
        color: white;
        border: none;
        padding: 5px 10px;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .btn-cod-no:hover {
        background-color: darkred;
    }

    /* boton de descargar plantilla */
    .download-button-container {
        text-align: left;
        /* Alinea el botón a la izquierda */
    }

    .download-button {
        font-size: 0.9rem;
        /* Reduce el tamaño de la fuente */
        padding: 0.5rem 1rem;
        /* Ajusta el relleno del botón */
    }
</style>