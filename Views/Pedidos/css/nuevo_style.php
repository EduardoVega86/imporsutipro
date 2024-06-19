<style>
    .section-title {
        background-color: #f8f9fa;
        padding: 10px;
        border-radius: 5px;
        text-align: center;
        margin-bottom: 20px;
    }

    .form-section {
        background-color: #fff;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
    }

    .btn-custom {
        width: 100%;
    }

    .left-column,
    .right-column {
        padding: 20px;
    }

    .img-container {
        flex: 0 0 48%;
        max-width: 48%;
        margin-bottom: 16px;
        /* Ajuste de margen inferior */
    }

    @media (max-width: 768px) {
        .img-container {
            flex: 0 0 100%;
            max-width: 100%;
        }
    }

    /* animacion y diseño de transportadoras y precio transportador */
    .transportadora {
        position: relative;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-radius: 10px;
        overflow: hidden;
        padding: 10px;
        background-color: #f8f9fa;
        display: inline-block;
    }

    .transportadora img {
        max-width: 100px;
        transition: filter 0.3s ease;
        filter: grayscale(100%);
    }

    .transportadora:hover {
        transform: scale(1.1);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .transportadora:hover img {
        filter: grayscale(0%);
    }

    .price-tag {
        position: absolute;
        bottom: 10px;
        left: 50%;
        transform: translate(-50%, 0);
        background-color: rgba(0, 0, 0, 0.7);
        color: white;
        padding: 3px 6px;
        /* Ajustar tamaño del fondo */
        border-radius: 3px;
        /* Ajustar borde redondeado */
        font-size: 12px;
        /* Ajustar tamaño de la fuente */
        transition: transform 0.3s ease, background-color 0.3s ease;
    }

    .transportadora:hover .price-tag {
        transform: translate(-50%, 0) scale(1.2);
        background-color: rgba(0, 0, 0, 0.9);
    }

    .transportadora.selected {
        border: 2px solid #007bff;
        box-shadow: 0 0 10px rgba(0, 123, 255, 0.5);
    }

    .transportadora.selected img {
        filter: grayscale(0%);
    }

    .transportadora.selected .price-tag {
        background-color: rgba(0, 123, 255, 0.9);
        transform: translate(-50%, 0) scale(1.2);
    }

    /* final de diseño de transportadoras */

    /* tabla */
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

    .table th,
    .table td {
        text-align: center;
        vertical-align: middle;
    }

    .centered {
        text-align: center !important;
        vertical-align: middle !important;
    }
</style>