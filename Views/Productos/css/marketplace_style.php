<style>
    /* cards */
    .card-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(275px, 1fr));
        gap: 10px;
        /* Ajusta este valor para el espaciado deseado entre las tarjetas */
    }

    .card-custom {
        position: relative;
        /* Añadir esto para posicionar el span absolutamente */
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
        /* Center the buttons */
        width: 80%;
    }

    /* Estilos para el ID del producto */
    .card-id-container {
        position: absolute;
        top: 10px;
        left: 10px;
        background-color: rgba(255, 255, 255, 0.8);
        /* Fondo blanco semi-transparente */
        border-radius: 5px;
        padding: 5px 10px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5);
        cursor: pointer;
        z-index: 10;
        /* Asegura que el span esté por encima de la imagen */
    }

    .card-id {
        font-size: 14px;
        font-weight: bold;
        color: #333;
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
        margin-bottom: 1px;
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
    /* Imagen principal del carrusel */
    .fixed-size-img {
        height: 300px;
        width: 100%;
        object-fit: cover;
        border-radius: 8px;
    }

    /* Wrapper para las miniaturas con flechas */
    .carousel-thumbnails-wrapper {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        margin-top: 20px;
        position: relative;
        padding: 0 15px;
    }

    /* Contenedor de miniaturas */
    .carousel-thumbnails {
        display: flex;
        align-items: center;
        gap: 10px;
        overflow: hidden;
        padding: 10px;
        border-radius: 8px;
        background-color: #f9f9f9;
        width: 80%;
        /* Controla el tamaño de la zona visible de las miniaturas */
        scroll-behavior: smooth;
    }

    /* Miniaturas */
    .carousel-thumbnails img {
        height: 80px;
        width: 80px;
        object-fit: cover;
        cursor: pointer;
        transition: transform 0.3s;
        border-radius: 4px;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
    }

    /* Efecto hover en miniaturas */
    .carousel-thumbnails img:hover {
        transform: scale(1.1);
    }

    /* Botones de control de miniaturas */
    .thumbnails-control-prev,
    .thumbnails-control-next {
        background-color: #007bff;
        color: white;
        border: none;
        font-size: 24px;
        padding: 12px;
        cursor: pointer;
        border-radius: 50%;
        box-shadow: 0px 6px 12px rgba(0, 0, 0, 0.2);
        transition: background-color 0.3s ease, transform 0.3s ease;
    }

    .thumbnails-control-prev:hover,
    .thumbnails-control-next:hover {
        background-color: #0056b3;
        transform: scale(1.1);
    }

    .thumbnails-control-prev {
        margin-right: 10px;
    }

    .thumbnails-control-next {
        margin-left: 10px;
    }

    /* Mejoras para el movimiento fluido en dispositivos móviles */
    .carousel-thumbnails-wrapper {
        touch-action: pan-x;
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

    /* CSS Filtros */
    .caja {
        background-color: #ffffff;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .form-check-input:checked {
        background-color: #ff6f61;
        border-color: #ff6f61;
    }

    .form-check-label {
        color: #495057;
    }

    .form-select,
    .form-range,
    .btn-outline-secondary,
    .btn-warning {
        border-radius: 5px;
    }

    .btn-outline-secondary {
        color: #6c757d;
        border-color: #6c757d;
    }

    .btn-outline-secondary:hover {
        background-color: #6c757d;
        color: white;
    }

    .btn-warning {
        background-color: #ff6f61;
        border-color: #ff6f61;
    }

    .btn-warning:hover {
        background-color: #e85b50;
        border-color: #e85b50;
    }

    .caja_filtros {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .primer_seccionFiltro {
        display: flex;
        flex-direction: row;
        gap: 20px;
    }

    .boton_favoritos {
        margin-left: auto;
    }

    @media (max-width: 768px) {
        .primer_seccionFiltro {
            flex-direction: column;
        }

        .boton_favoritos {
            margin-left: initial;
        }
    }

    /* Slide de rango de precions con noUiSlider */
    /* Base del Slider */
    .noUi-target {
        background-color: #B2B2B2;
        height: 10px;
        border-radius: 5px;
    }

    /* Conexión entre las manijas */
    .noUi-connect {
        background-color: <?php echo COLOR_FONDO; ?>;
        /* Tu color de elección para la barra activa */
    }

    /* Manijas del Slider */
    .noUi-handle {
        outline: none;
        top: -5px;
        /* Ajusta esta propiedad para cambiar la posición vertical de la manija */
        border: 1px solid #D3D3D3;
        /* Borde de la manija */
        background-color: white;
        border-radius: 50%;
        width: 19px !important;
        /* Ancho de la manija */
        height: 19px !important;
        /* Altura de la manija */
        box-shadow: none;
        cursor: pointer;
        background-image: none !important;
    }

    .noUi-handle::after,
    .noUi-handle::before {
        content: none !important;
        /* Elimina el contenido de los pseudo-elementos */
    }

    /* Tooltips (los que muestran los valores encima de las manijas) */
    .noUi-tooltip {
        display: none;
        /* Oculta el tooltip por defecto de noUiSlider */
    }

    /* CSS filtros */

    /* boton favoritos */
    .btn-heart {
        position: absolute;
        top: 10px;
        right: 10px;
        background: transparent;
        border: none;
        color: grey;
        /* Color apagado */
        font-size: 1.5em;
        cursor: pointer;
        transition: transform 0.3s ease, color 0.3s ease;
    }

    .btn-heart.clicked {
        color: <?php echo COLOR_FAVORITO; ?>;
        /* Color encendido */
    }

    .btn-heart:hover {
        color: <?php echo COLOR_FAVORITO; ?>;
        /* Cambia este color al que desees */
    }

    .btn-heart:focus {
        outline: none;
    }

    .btn-heart .fas.fa-heart {
        transition: transform 0.3s ease, color 0.3s ease;
    }

    .btn-heart.clicked .fas.fa-heart {
        transform: scale(1.3);
        color: <?php echo COLOR_FAVORITO; ?>;
    }

    /* fin boton favoritos */

    /* boton añadir a tienda */
    .image-container {
        position: relative;
    }

    .add-to-store-button {
        position: absolute;
        bottom: 10px;
        right: 10px;
        background-color: green;
        color: white;
        border: none;
        border-radius: 0.3rem;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .add-to-store-button.added {
        background-color: green;
        color: white;
    }

    .add-to-store-button .plus-icon {
        font-size: 20px;
    }

    .add-to-store-button .add-to-store-text {
        display: none;
        margin-right: 10px;
        white-space: nowrap;
        transition: opacity 0.3s ease;
    }

    .add-to-store-button:hover .add-to-store-text {
        display: block;
        opacity: 1;
    }

    .add-to-store-button:hover {
        width: auto;
        padding: 5px 10px;
        border-radius: 0.3rem;
        background-color: green;
        color: white;
    }

    /* Fin de boton añadir tienda */

    .boton_mas {
        font-size: 15px;
        border-radius: 0.3rem;
        background-color: #007bff;
        /* Color de fondo */
        color: #fff;
        /* Color de texto */
        padding: 10px 20px;
        /* Espaciado interno */
        border: none;
        /* Sin borde */
        cursor: pointer;
        /* Cursor de mano */
        transition: background-color 0.3s, transform 0.3s;
        /* Transiciones suaves */
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        /* Sombra */
        display: flex;
        /* Centrado del texto */
        align-items: center;
        /* Centrado del texto */
        justify-content: center;
        /* Centrado del texto */
    }

    .boton_mas:hover {
        background-color: #0056b3;
        /* Color de fondo al pasar el cursor */
        transform: translateY(-2px);
        /* Efecto de elevación */
    }
</style>