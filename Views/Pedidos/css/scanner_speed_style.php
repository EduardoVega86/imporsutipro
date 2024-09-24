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
        /* Se ajusta al 100% del contenedor padre */
        max-width: 300px;
        /* Tamaño máximo para asegurar que no sea demasiado grande */
        height: 300px;
        /* Altura fija para que el escáner funcione bien */
        border: 2px solid #007bff;
        /* Cambiar el borde a azul */
        border-radius: 5px;
        background-color: #f8f9fa;
        /* Fondo claro */
        box-sizing: border-box;
        /* Incluir el borde en el tamaño total */
        overflow: hidden;
        /* Asegura que el contenido no se salga */
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

    @media (max-width: 768px) {

        #scanner {
            max-width: 100%;
            /* En pantallas pequeñas, el scanner ocupará el 100% del ancho del contenedor */
            height: 200px;
            /* Reducir la altura para dispositivos móviles */
        }

        button {
            width: 100%;
            margin-bottom: 10px;
        }
    }
</style>