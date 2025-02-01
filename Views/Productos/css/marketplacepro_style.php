<style>
    /* Contenedores principales del slider */
    .slider-proveedores-container {
        width: 100%;
        margin-bottom: 20px;
        overflow: hidden;
    }

    .slider-proveedores {
        display: flex;
        flex-wrap: nowrap;
        gap: 10px;
        overflow-x: auto;
        scrollbar-width: thin;
        -webkit-overflow-scrolling: touch; /* Suaviza el desplazamiento en dispositivos táctiles */
    }

    .slider-chip {
        min-width: 200px; /* Ajuste del tamaño mínimo para pantallas pequeñas */
        max-width: 300px; /* Limitar el tamaño máximo */
        flex: 0 0 auto; /* Mantener la alineación horizontal */
        text-align: center;
        white-space: normal; /* Permitir que el texto haga saltos de línea */
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 20px;
        background-color: #e0e0e0;
        transition: transform 0.3s ease, background-color 0.3s ease;
    }

    .slider-chip img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        margin: 0 auto;
    }

    .slider-chip:hover {
        background-color: #ccc;
        transform: scale(1.05);
    }

    .slider-chip .chip-title {
        font-size: 14px;
        font-weight: bold;
        margin-top: 5px;
    }

    .slider-chip .chip-count {
        font-size: 12px;
        margin-top: 3px;
    }

    /* Ajustes para dispositivos móviles */
    @media (max-width: 1024px) {
        .slider-proveedores {
            max-width: 100%;
            padding: 10px;
        }
        .slider-chip {
            min-width: 180px; /* Reducir el tamaño mínimo en pantallas medianas */
        }
    }

    @media (max-width: 768px) {
        .slider-proveedores {
            overflow-x: scroll; /* Habilitar scroll horizontal */
        }
        .slider-chip {
            min-width: 150px;
        }
        .slider-chip .chip-title {
            font-size: 12px;
        }
        .slider-chip .chip-count {
            font-size: 10px;
        }
        .slider-arrow-left,
        .slider-arrow-right {
            display: none; /* Ocultar flechas en pantallas pequeñas */
        }
    }

    @media (max-width: 480px) {
        .slider-chip {
            min-width: 120px; /* Tamaño más pequeño en pantallas muy pequeñas */
        }
        .slider-chip img {
            width: 50px; /* Ajuste de la imagen */
            height: 50px;
        }
    }

    /* Ajustes del scroll */
    .slider-proveedores::-webkit-scrollbar {
        height: 6px; /* Altura del scrollbar */
    }

    .slider-proveedores::-webkit-scrollbar-thumb {
        background-color: #ccc;
        border-radius: 3px;
    }

    /* Flechas */
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
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
    }

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
</style>
