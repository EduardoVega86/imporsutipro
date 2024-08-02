<style>
    .full-screen-container {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        background-color: #f0f0f0;
        padding-left: 30px;
        overflow: hidden;
    }

    .custom-container-fluid {
        background-color: <?php echo COLOR_FONDO; ?>;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        text-align: left;
        /* Cambiado a izquierda para mejor lectura */
        min-width: 50%;
        max-height: 80vh;
        overflow-y: auto;
    }

    .custom-container-fluid h1 {
        color: <?php echo COLOR_LETRAS; ?>;
        margin-bottom: 20px;
        text-align: center;
        /* Alineado al centro para títulos */
    }

    .datos_shopify {
        color: #333;
        /* Texto más oscuro para mejor contraste */
        font-family: Arial, sans-serif;
        /* Fuente legible */
    }

    .config-item {
        margin-bottom: 20px;
        padding: 15px;
        border: 1px solid #ddd;
        border-radius: 5px;
        background-color: #fff;
        /* Fondo blanco para destacar el texto */
    }

    .config-item ul {
        padding-left: 20px;
        /* Sangría para listas anidadas */
    }

    .config-item li {
        margin-bottom: 5px;
        padding-left: 10px;
        border-left: 2px solid #eee;
        /* Línea vertical para indicar anidación */
        position: relative;
    }

    .config-item li::before {
        content: '';
        position: absolute;
        left: -2px;
        top: 10px;
        height: 10px;
        width: 10px;
        border-radius: 50%;
        background-color: #007bff;
        /* Puntos de color para subniveles */
    }

    .config-item strong {
        color: #007bff;
        /* Azul para claves */
        font-weight: bold;
    }

    .config-item p {
        margin-bottom: 10px;
    }

    @media (max-width: 768px) {
        .full-screen-container {
            flex-direction: column;
            gap: 10px;
            height: 60vh;
        }

        .json_informacion {
            min-height: 400px;
            width: 80%;
        }
    }

    /* Estilos adicionales para manejar información JSON */
    .json_informacion {
        max-height: 600px;
        overflow-y: auto;
        border: 1px solid #ced4da;
        padding: 10px;
        background-color: #f8f9fa;
        border-radius: 5px;
    }

    #json-content {
        white-space: pre-wrap;
        font-family: monospace;
        color: #333;
    }

    .generacion_enlace {
        display: none;
    }

    .loading-animation {
        display: none;
        text-align: center;
        margin-top: 20px;
    }

    .spinner-border {
        width: 3rem;
        height: 3rem;
    }

    .img-container {
        cursor: pointer;
    }

    #json-informacion {
        display: none;
    }

    /* Estilos para aplicaciones */
    .aplicacion {
        position: relative;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-radius: 10px;
        overflow: hidden;
        padding: 10px;
        background-color: #f8f9fa;
        display: inline-block;
    }

    .aplicacion img {
        max-width: 100px;
        transition: filter 0.3s ease;
        filter: grayscale(100%);
    }

    .aplicacion:hover {
        transform: scale(1.1);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .aplicacion:hover img {
        filter: grayscale(0%);
    }

    .name-tag {
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

    .aplicacion:hover .name-tag {
        transform: translate(-50%, 0) scale(1.2);
        background-color: rgba(0, 0, 0, 0.9);
    }

    .aplicacion.selected {
        border: 2px solid #007bff;
        box-shadow: 0 0 10px rgba(0, 123, 255, 0.5);
    }

    .aplicacion.selected img {
        filter: grayscale(0%);
    }

    .aplicacion.selected .name-tag {
        background-color: rgba(0, 123, 255, 0.9);
        transform: translate(-50%, 0) scale(1.2);
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        color: black;
        /* Cambiado a negro */
        margin-bottom: 5px;
    }

    .form-group input {
        width: 100%;
        padding: 10px;
        border: 1px solid #ced4da;
        border-radius: 4px;
    }

    .form-group input::placeholder {
        color: #6c757d;
    }
</style>