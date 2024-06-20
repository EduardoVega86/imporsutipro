<style>
    /* cards */
    .card-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(275px, 1fr));
        gap: 10px;
        /* Ajusta este valor para el espaciado deseado entre las tarjetas */
    }

    .card-custom {
        border-radius: 15px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s, box-shadow 0.3s;
        height: 520px;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        /* Para evitar que el contenido se salga */
        width: 100%;
        /* Hacer que la tarjeta ocupe todo el ancho de su contenedor */
    }

    .card-custom:hover {
        transform: translateY(-10px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.2);
    }

    .card-custom img {
        border-top-left-radius: 15px;
        border-top-right-radius: 15px;
        height: 200px;
        width: 100%;
        object-fit: cover;
        /* Mantener la proporción de la imagen */
        flex-shrink: 0;
        /* Evitar que la imagen se encoja */
    }

    .card-custom .card-body {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        overflow: hidden;
        /* Para evitar que el contenido se salga */
    }

    .card-custom .btn-description,
    .card-custom .btn-import {
        border-radius: 50px;
        padding: 10px 20px;
        margin: 5px auto;
        /* Centrar los botones */
        width: 80%;
    }

    .btn-description {
        background-color: #00aaff;
        color: white;
    }

    .btn-import {
        background-color: #ffc107;
        color: white;
        margin-bottom: 10px;
        /* Añade margen inferior para espacio adicional */
    }

    .card-text {
        margin-bottom: 10px;
    }

    @media (max-width: 768px) {
        .card-container {
            grid-template-columns: repeat(2, 1fr);
            /* Dos tarjetas por fila en pantallas pequeñas */
        }
    }



    /* modal */
    .modal-content {
        border-radius: 15px;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }

    .modal-header {
        background-color: <?php echo COLOR_FONDO; ?>;
        color: <?php echo COLOR_LETRAS; ?>;
        border-top-left-radius: 15px;
        border-top-right-radius: 15px;
    }

    .modal-header .btn-close {
        color: white;
    }

    .modal-body {
        padding: 20px;
    }

    .modal-footer {
        border-top: none;
        padding: 10px 20px;
    }

    .modal-footer .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
    }

    .modal-footer .btn-primary {
        background-color: #ffc107;
        border-color: #ffc107;
        color: white;
    }

    .texto_modal {
        font-size: 20px;
        margin-bottom: 5px;
    }

    /* carrusel */
    .fixed-size-img {
        height: 300px;
        /* Tamaño fijo para la imagen */
        object-fit: cover;
        /* La imagen se adapta al contenedor manteniendo su proporción */
    }

    .carousel-thumbnails img {
        height: 80px;
        width: 80px;
        object-fit: cover;
        cursor: pointer;
        transition: border 0.3s;
    }

    .carousel-thumbnails img:hover {
        border: 2px solid #007bff;
    }

    .descripcion_producto {
        display: flex;
        flex-direction: row;
    }

    .informacion_producto {
        width: 50%;
        /* Aproximadamente la mitad del contenedor, similar a col-6 */
        margin-bottom: 1rem;
        /* Espaciado en la parte inferior, similar a mb-4 */
    }

    @media (max-width: 768px) {
        .descripcion_producto {
            flex-direction: column-reverse;
        }

        .informacion_producto {
            width: 100%;
        }
    }

    /* paginacion */
    .pagination {
        margin-top: 20px;
    }

    .page-link {
        color: #007bff;
        cursor: pointer;
    }

    .page-link:hover {
        color: #0056b3;
        text-decoration: none;
        background-color: #e9ecef;
    }

    .page-item.disabled .page-link {
        color: #6c757d;
        pointer-events: none;
        background-color: #fff;
        border-color: #dee2e6;
    }

    .page-item.active .page-link {
        z-index: 1;
        color: #fff;
        background-color: #007bff;
        border-color: #007bff;
    }

    .page-item .page-link {
        position: relative;
        display: block;
        padding: 0.5rem 0.75rem;
        margin-left: -1px;
        line-height: 1.25;
        color: #007bff;
        background-color: #fff;
        border: 1px solid #dee2e6;
    }

    /* diseño de tabla */
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