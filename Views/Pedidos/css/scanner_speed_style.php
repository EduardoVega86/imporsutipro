<style>
    #scanner-container {
        max-width: 600px;
        margin: 0 auto;
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    #scanner {
        width: 100%;
        /* Ocupa el ancho completo del contenedor */
        height: 250px;
        /* Altura fija para que el escáner funcione bien */
        border: 2px solid #007bff;
        border-radius: 5px;
        background-color: #f8f9fa;
        box-sizing: border-box;
        overflow: hidden;
    }

    #scanner video {
        width: 50% !important;
        /* Ocupa el 100% del contenedor */
        height: auto;
        /* Mantén la proporción del video */
        max-width: 640px;
        /* Controla el tamaño máximo */
        max-height: 480px;
    }

    #scanner canvas {
        width: 100%;
        /* Ocupa el 100% del contenedor */
        height: auto;
        /* Mantiene la proporción del canvas */
        max-width: 640px;
        /* Tamaño máximo controlado */
        max-height: 480px;
        /* Ajusta la altura máxima */
    }

    #result {
        font-size: 1.2em;
        margin-top: 20px;
        font-weight: bold;
        color: #333;
    }

    button {
        width: 45%;
    }

    /* Media Query para ajustar en móviles */
    @media (max-width: 576px) {
        #scanner {
            height: 200px;
            /* Reducir la altura para dispositivos móviles */
        }

        button {
            width: 100%;
            /* Botones al 100% en pantallas pequeñas */
            margin-bottom: 10px;
        }
    }
</style>