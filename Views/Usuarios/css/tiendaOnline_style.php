<style>
    .accordion-button {
        background-color: #171931 !important;
        color: white !important;
    }

    .card {
        border-radius: 15px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .card-header,
    .card-body {
        border-radius: 15px;
    }

    .form-group label {
        font-weight: bold;
    }

    .custom-checkbox .custom-control-input:checked~.custom-control-label::before {
        background-color: #0d6efd;
    }

    .pagination {
        justify-content: center;
    }

    /* tabla */
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

    /* fin tabla */

    .boton-flotante {
        position: fixed;
        bottom: 20px;
        right: 20px;
        padding: 15px 25px;
        background-color: #E64747;
        color: white;
        border: none;
        border-radius: 50px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        cursor: pointer;
        font-size: 16px;
        display: none;
        /* Oculto por defecto */
        opacity: 0;
        transform: translateY(20px);
        transition: all 0.3s ease;
        z-index: 3;
    }

    .boton-flotante.mostrar {
        display: inline-block;
        animation: aparecer 0.5s forwards;
    }

    .vertical-hr {
        width: 1px;
        background-color: black;
        border: none;
        margin: 0;
    }

    @keyframes aparecer {
        0% {
            opacity: 0;
            transform: translateY(20px);
        }

        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .loader {
        border: 16px solid #f3f3f3;
        border-radius: 50%;
        border-top: 16px solid #3498db;
        width: 60px;
        height: 60px;
        animation: spin 2s linear infinite;
        margin: 20px auto;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    .aviso-banner {
        width: 40%;
    }

    .aviso-promocion {
        width: 40%;
    }

    @media (max-width: 768px) {
        .aviso-banner {
            width: 100%;
        }

        .aviso-promocion {
            width: 100%;
        }
    }

    /* seccion plantillas */
    .plantilla {
        position: relative;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-radius: 10px;
        overflow: hidden;
        padding: 10px;
        background-color: #f8f9fa;
        display: inline-block;
    }

    .plantilla img {
        max-width: 100px;
        transition: filter 0.3s ease;
        filter: grayscale(100%);
    }

    .plantilla:hover {
        transform: scale(1.1);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .plantilla:hover img {
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
        border-radius: 3px;
        font-size: 12px;
        transition: transform 0.3s ease, background-color 0.3s ease;
    }

    .plantilla:hover .price-tag {
        transform: translate(-50%, 0) scale(1.2);
        background-color: rgba(0, 0, 0, 0.9);
    }

    .plantilla.selected {
        border: 2px solid #007bff;
        box-shadow: 0 0 10px rgba(0, 123, 255, 0.5);
    }

    .plantilla.selected img {
        filter: grayscale(0%);
    }

    .plantilla.selected .price-tag {
        background-color: rgba(0, 123, 255, 0.9);
        transform: translate(-50%, 0) scale(1.2);
    }

    .oferta {
        display: flex;
        flex-direction: row;
    }

    .oferta1_color {
        display: flex;
        flex-direction: row;
    }

    .oferta2_color {
        display: flex;
        flex-direction: row;
    }

    #imagen_oferta1 {
        width: 30%;
    }

    #imagen_oferta2 {
        width: 30%;
    }

    .promocion {
        display: flex;
        flex-direction: row;
    }

    #imagen_promocion {
        width: 30%;
    }

    @media (max-width: 768px) {
        .oferta {
            flex-direction: column;
        }

        .oferta1_color {
            display: block;
        }

        .oferta2_color {
            display: block;
        }

        #imagen_oferta1 {
            width: 100%;
        }

        #imagen_oferta2 {
            width: 100%;
        }

        .promocion {
            flex-direction: column;
        }

        #imagen_promocion {
            width: 100%;
        }
    }

    /* seccion plnatillas */
</style>