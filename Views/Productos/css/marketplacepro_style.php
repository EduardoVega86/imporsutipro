
<style>
    /* cards */
    .card-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(275px, 1fr));
        gap: 10px;
    }

    .card-custom {
        position: relative;
        border-radius: 15px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s, box-shadow 0.3s;
        height: 520px;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        width: 100%;
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
        flex-shrink: 0;
    }

    .card-custom .card-body {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        overflow: hidden;
    }

    .card-custom .btn-description,
    .card-custom .btn-import {
        border-radius: 50px;
        padding: 10px 20px;
        margin: 5px auto;
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
    }

    .card-text {
        margin-bottom: 1px;
    }

    /* Estilos para el ID del producto */
    .card-id-container {
        position: absolute;
        top: 10px;
        left: 10px;
        background-color: rgba(255, 255, 255, 0.8);
        border-radius: 5px;
        padding: 5px 10px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5);
        cursor: pointer;
        z-index: 10;
    }

    .card-id {
        font-size: 14px;
        font-weight: bold;
        color: #333;
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
    .carousel-thumbnails {
        display: flex;
        justify-content: flex-start;
        align-items: center;
        gap: 10px;
        overflow-x: auto;
        padding: 10px;
        border-radius: 8px;
        background-color: #f9f9f9;
        scroll-behavior: smooth;
        white-space: nowrap;
        scrollbar-width: thin;
        -webkit-overflow-scrolling: touch;
    }

    .carousel-thumbnails img {
        height: 80px;
        width: 80px;
        object-fit: cover;
        cursor: pointer;
        transition: border 0.3s, transform 0.3s;
        border-radius: 8px;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
    }

    .carousel-thumbnails img:hover {
        border: 2px solid #007bff;
        transform: scale(1.05);
    }

    .carousel-thumbnails img.active-thumbnail {
        border: 3px solid #007bff;
        transform: scale(1.1);
    }

    .carousel-thumbnails img {
        min-width: 80px;
    }

    @media (max-width: 768px) {
        .carousel-thumbnails {
            justify-content: flex-start;
        }

        .carousel-thumbnails img {
            height: 70px;
            width: 70px;
        }
    }

    .descripcion_producto {
        display: flex;
        flex-direction: row;
    }

    .informacion_producto {
        width: 50%;
        margin-bottom: 1rem;
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

    /* Slide de rango de precios con noUiSlider */
    .noUi-target {
        background-color: #B2B2B2;
        height: 10px;
        border-radius: 5px;
    }

    .noUi-connect {
        background-color: <?php echo COLOR_FONDO; ?>;
    }

    .noUi-handle {
        outline: none;
        top: -5px;
        border: 1px solid #D3D3D3;
        background-color: white;
        border-radius: 50%;
        width: 19px !important;
        height: 19px !important;
        box-shadow: none;
        cursor: pointer;
        background-image: none !important;
    }

    .noUi-handle::after,
    .noUi-handle::before {
        content: none !important;
    }

    .noUi-tooltip {
        display: none;
    }

    /* boton favoritos */
    .btn-heart {
        position: absolute;
        top: 10px;
        right: 10px;
        background: transparent;
        border: none;
        color: grey;
        font-size: 1.5em;
        cursor: pointer;
        transition: transform 0.3s ease, color 0.3s ease;
    }

    .btn-heart.clicked {
        color: <?php echo COLOR_FAVORITO; ?>;
    }

    .btn-heart:hover {
        color: <?php echo COLOR_FAVORITO; ?>;
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
        color: #fff;
        padding: 10px 20px;
        border: none;
        cursor: pointer;
        transition: background-color 0.3s, transform 0.3s;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .boton_mas:hover {
        background-color: #0056b3;
        transform: translateY(-2px);
    }

    /* Contenedores principales de los sliders */
    .slider-proveedores-container {
        position: relative;
        width: 100%;
        margin-bottom: 20px;
        overflow: hidden;
    }

    .slider-arrow-left { left: 10px; }
    .slider-arrow-right { right: 10px; }
    
    @media (max-width: 768px) {
        .slider-arrow {
            width: 30px;
            height: 30px;
            font-size: 14px;
        }
    } {
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
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
    }

    .slider-arrow-left {
        left: 10px;
    }

    .slider-arrow-right {
        right: 10px;
    }

    .slider-arrow:hover {
        background-color: rgba(255, 255, 255, 1);
        transform: translateY(-50%) scale(1.05);
    }

    .slider-proveedores {
        display: flex;
        gap: 10px;
        overflow-x: auto;
        scroll-behavior: smooth;
        white-space: nowrap;
        padding-bottom: 10px;
    }

    .slider-chip {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background-color: #e0e0e0;
        color: #333;
        min-width: 200px;
        height: 80px;
        border: 1px solid #ccc;
        border-radius: 20px;
        cursor: pointer;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        text-align: center;
        padding: 10px;
        box-sizing: border-box;
        transition: background-color 0.3s ease, transform 0.3s ease;
        user-select: none;
    }

    .slider-chip:hover {
        background-color: #ccc;
        transform: scale(1.05);
    }

    .slider-chip.selected {
        background-color: rgb(91, 158, 230);
        color: white;
        transform: scale(1.05);
    }

    .slider-proveedores::-webkit-scrollbar {
        height: 6px;
    }

    .slider-proveedores::-webkit-scrollbar-thumb {
        background-color: #ccc;
        border-radius: 3px;
    }

    .icon-chip {
        width: 40px;
        height: 40px;
        margin-right: 10px;
        vertical-align: middle;
        border-radius: 40%;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2);
        object-fit: cover;
    }

    /* Responsive para móviles */
    @media (max-width: 768px) {
        .slider-chip {
            min-width: 150px;
            height: 70px;
        }

        .slider-arrow {
            width: 30px;
            height: 30px;
            font-size: 14px;
        }
    }
</style>