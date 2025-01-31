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
    /* Contenedor de miniaturas */
    .carousel-thumbnails {
        display: flex;
        justify-content: flex-start;
        /* Alineamos las miniaturas al principio */
        align-items: center;
        gap: 10px;
        overflow-x: auto;
        padding: 10px;
        border-radius: 8px;
        background-color: #f9f9f9;
        scroll-behavior: smooth;
        white-space: nowrap;
        /* Las miniaturas se mantendrán en una sola línea */
        scrollbar-width: thin;
        /* Scroll más delgado */
        -webkit-overflow-scrolling: touch;
        /* Suavizar el desplazamiento en dispositivos móviles */
    }

    /* Miniaturas */
    .carousel-thumbnails img {
        height: 80px;
        width: 80px;
        object-fit: cover;
        cursor: pointer;
        transition: border 0.3s, transform 0.3s;
        border-radius: 8px;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
    }

    /* Efecto hover en miniaturas */
    .carousel-thumbnails img:hover {
        border: 2px solid #007bff;
        transform: scale(1.05);
    }

    /* Miniatura activa */
    .carousel-thumbnails img.active-thumbnail {
        border: 3px solid #007bff;
        transform: scale(1.1);
    }

    /* Evitar que las miniaturas se corten en dispositivos pequeños */
    .carousel-thumbnails img {
        min-width: 80px;
        /* Garantizamos un ancho mínimo para que no se corten */
    }

    /* Medios de comunicación para dispositivos móviles */
    @media (max-width: 768px) {
        .carousel-thumbnails {
            justify-content: flex-start;
            /* Asegura que las miniaturas no desaparezcan en móviles */
        }

        .carousel-thumbnails img {
            height: 70px;
            /* Ajuste de tamaño para pantallas más pequeñas */
            width: 70px;
        }
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

        display: flex;
        flex-direction: column;
        /* Asegura que los elementos internos se apilen */
        justify-content: flex-start;
        /* Evita que se expanda más de lo necesario */
        width: 100%;
        /* Evita que crezca fuera del viewport */
        max-width: 1400px;
        /* Limita su expansión */
        margin: 0 auto;
        /* Centra el contenedor */
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
        z-index: 10;
        transition: all 0.3s ease;
    }

    .add-to-funnel-button {
        position: absolute;
        bottom: 10px;
        right: 50px;
        background-color: #007bff;
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

    .add-to-funnel-button.added {
        background-color: #007bff;
        color: white;
    }

    .add-to-store-button .plus-icon {
        font-size: 20px;
    }

    .add-to-funnel-button .plus-icon {
        font-size: 20px;
    }

    .add-to-store-button .add-to-store-text {
        display: none;
        margin-right: 10px;
        white-space: nowrap;
        transition: opacity 0.3s ease;
    }

    .add-to-funnel-button .add-to-funnel-text {
        display: none;
        margin-right: 10px;
        white-space: nowrap;
        transition: opacity 0.3s ease;
    }

    .add-to-store-button:hover .add-to-store-text {
        display: block;
        opacity: 1;
    }

    .add-to-funnel-button:hover .add-to-funnel-text {
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

    .add-to-funnel-button:hover {
        width: auto;
        padding: 5px 10px;
        border-radius: 0.3rem;
        background-color: #007bff;
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

    /* Contenedores principales de los sliders */
    .slider-proveedores-container {
        width: 100%;
        max-width: 100%;
        /* Para que no se expanda demasiado */
        margin-bottom: 10px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        align-items: center;
        /* Asegura que los elementos internos estén centrados */
    }


    .slider-arrow {
        cursor: pointer;
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background-color: rgba(255, 255, 255, 0.8);
        border-radius: 50%;
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        z-index: 2;
        /* para que quede por encima de los chips */
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
    }

    /* Flechas */

    .slider-arrow-left {
        left: 17px;
    }

    .slider-arrow-right {
        right: -12px;
        top: 78px;
    }

    .slider-arrow:hover {
        background-color: rgba(255, 255, 255, 1);
        transform: translateY(-50%) scale(1.05);
    }

    /* Encabezado */
    .slider-proveedores-container h5 {
        margin-bottom: 10px;
    }

    .slider-proveedores {
        display: flex;
        gap: 10px;
        overflow-x: auto;
        max-width: 95%;
        /* Evita que se expanda más de lo necesario */
        padding: 10px 0;
        justify-content: flex-start;
        box-sizing: border-box;
        white-space: nowrap;
        /* Evita que los elementos se vayan a otra línea */
    }


    /* Estilo para cada 'chip' o botón */
    .slider-chip {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background-color: #e0e0e0;
        color: #333;
        /* Ancho fijo */
        min-width: 283px;
        /* Alto fijo */
        height: 105px;
        /* Borde que solicitas */
        border: 1px solid #ccc;
        border-radius: 20px;
        cursor: pointer;
        /* Ocultará contenido que se desborde */
        overflow: hidden;
        /* Opcional: recorta texto muy largo con “...” */
        white-space: nowrap;
        text-overflow: ellipsis;
        text-align: center;
        /* Ajusta si necesitas más o menos espacio interno */
        padding: 10px;
        /* Asegura que padding no rompa el ancho/alto */
        box-sizing: border-box;
        transition: background-color 0.3s ease, transform 0.3s ease;
        /* Evita la selección del texto al clicar */
        user-select: none;
    }


    /* Mantener el título en la parte superior */
    .chip-title {
        font-size: 14px;
        font-weight: bold;
        margin-top: 5px;
    }

    /* Poner la cantidad de productos en la parte inferior */
    .chip-count {
        font-size: 12px;
        margin-top: 3px;
    }

    .chip-categories {
        font-size: 12px;
        color: #666;
        margin-top: 3px;
    }

    .chip-content {
        display: flex;
        align-items: center;
        /* Alinea verticalmente */
        gap: 10px;
        /* Espacio entre imagen y texto */
    }

    .chip-text {
        display: flex;
        flex-direction: column;
        /* Hace que el nombre y cantidad estén en columnas */
        align-items: flex-start;
        /* Alinea a la izquierda */
    }

    .slider-chip:hover {
        background-color: #ccc;
        transform: scale(1.05);
    }

    /* Ajuste de los iconos dentro de los chips */
    .slider-chip i {
        margin-right: 8px;
        /* Espacio entre el ícono y el texto */
        font-size: 18px;
        /* Tamaño del ícono */
        /* color: #007bff; */
    }

    /* Para marcar un chip seleccionado */
    .slider-chip.selected {
        background-color: rgb(91, 158, 230);
        color: white;
        transform: scale(1.05);
    }

    .slider-proveedores::-webkit-scrollbar {
        height: 6px;
        /* Altura de la barra de scroll */
    }

    .slider-proveedores::-webkit-scrollbar-thumb {
        background-color: #ccc;
        border-radius: 3px;
    }

    /* Estilo para las imágenes dentro de los chips */
    .icon-chip {
        width: 40px;
        /* Tamaño ajustable */
        height: 40px;
        margin-right: 10px;
        vertical-align: middle;
        border-radius: 40%;
        /* Hace la imagen redonda */
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2);
        /* Efecto de sombra */
        object-fit: cover;
        /* Ajuste correcto de imagen */
    }

    /*Estilos de vista proveedores_pro    */
    .proveedores-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 20px;
        padding: 20px;
    }

    .proveedor-card {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        padding: 15px;
        text-align: center;
        transition: transform 0.3s ease;
    }

    .proveedor-card:hover {
        transform: translateY(-5px);
    }

    .proveedor-logo-container {
        width: 100px;
        height: 100px;
        margin: 0 auto 10px;
        border-radius: 50%;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f0f0f0;
    }

    .proveedor-logo {
        width: 40px;
        /* Tamaño ajustable */
        height: 40px;
        margin-right: 10px;
        vertical-align: middle;
        border-radius: 40%;
        /* Hace la imagen redonda */
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2);
        /* Efecto de sombra */
        object-fit: cover;
        /* Ajuste correcto de imagen */
    }
</style>