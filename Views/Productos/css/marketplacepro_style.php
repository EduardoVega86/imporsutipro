<style>
    /* ==================== GENERAL ==================== */
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

    /* ==================== MODAL ==================== */
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

    .modal-body {
        padding: 20px;
    }

    .modal-footer {
        border-top: none;
        padding: 10px 20px;
    }

    .modal-footer .btn-primary {
        background-color: #ffc107;
        border-color: #ffc107;
        color: white;
    }

    /* ==================== CARRUSEL ==================== */
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

    /* ==================== FILTROS ==================== */
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

    .btn-outline-secondary {
        color: #6c757d;
        border-color: #6c757d;
    }

    .btn-warning {
        background-color: #ff6f61;
        border-color: #ff6f61;
    }

    .caja_filtros {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    @media (max-width: 768px) {
        .caja_filtros {
            flex-direction: column;
        }
    }

    /* ==================== SLIDER PRECIO ==================== */
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
        cursor: pointer;
    }

    /* ==================== BOTONES ==================== */
    .btn-heart {
        position: absolute;
        top: 10px;
        right: 10px;
        background: transparent;
        border: none;
        color: grey;
        font-size: 1.5em;
        cursor: pointer;
    }

    .btn-heart.clicked {
        color: <?php echo COLOR_FAVORITO; ?>;
    }

    /* ==================== PROVEEDORES ==================== */
    .slider-proveedores-container {
        width: 100%;
        margin-bottom: 20px;
    }

    .slider-proveedores-container h5 {
        margin-bottom: 10px;
    }

    .slider-proveedores {
        display: flex;
        gap: 15px;
        overflow-x: auto;
        max-width: 1620px;
        padding: 10px;
        box-sizing: border-box;
        border-radius: 8px;
        background-color: #f9f9f9;
        scroll-behavior: smooth;
        white-space: nowrap;
    }

    .proveedor-card {
        display: flex;
        align-items: center;
        background: white;
        padding: 15px;
        border-radius: 12px;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        min-width: 250px;
        max-width: 280px;
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        cursor: pointer;
    }

    .proveedor-card:hover {
        transform: scale(1.05);
        box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.15);
    }

    .proveedor-content {
        display: flex;
        align-items: center;
        gap: 15px;
        width: 100%;
    }

    .proveedor-img {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #ddd;
    }

    .proveedor-info {
        display: flex;
        flex-direction: column;
        width: 100%;
    }

    .proveedor-nombre {
        font-weight: bold;
        font-size: 15px;
        color: #333;
    }

    .proveedor-productos {
        font-size: 13px;
        color: #666;
    }

    .slider-proveedores::-webkit-scrollbar {
        height: 6px;
    }

    .slider-proveedores::-webkit-scrollbar-thumb {
        background-color: #ccc;
        border-radius: 3px;
    }
</style>