<style>
    .card-custom {
        border: 1px solid #dee2e6;
        border-radius: 10px;
        padding: 20px;
        margin: 10px;
        text-align: center;
    }

    .card-custom img {
        height: 50px;
        margin-bottom: 10px;
    }

    .status {
        font-weight: bold;
        margin-bottom: 10px;
    }

    .connected {
        color: green;
    }

    .disconnected {
        color: red;
    }

    /* Estilos para los iconos */
    .icon-btn {
        display: inline-block;
        font-size: 22px;
        color: #333;
        background: #f8f9fa;
        border-radius: 50%;
        padding: 12px;
        text-decoration: none;
        transition: transform 0.3s ease, color 0.3s ease;
        position: relative;
    }

    /* Cambio de color al pasar el mouse */
    .icon-btn:hover {
        transform: translateY(-5px);
        /* Mueve el icono hacia arriba */
        color: #007bff;
        /* Azul Bootstrap */
    }

    /* Tooltip */
    .icon-btn::after {
        content: attr(data-tooltip);
        position: absolute;
        bottom: -30px;
        left: 50%;
        transform: translateX(-50%);
        background: #333;
        color: white;
        padding: 6px 10px;
        font-size: 12px;
        border-radius: 5px;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s ease, transform 0.3s ease;
        white-space: nowrap;
    }

    /* Mostrar el tooltip cuando pasa el mouse */
    .icon-btn:hover::after {
        opacity: 1;
        visibility: visible;
        transform: translate(-50%, -10px);
    }
</style>