<style>
    .full-screen-container {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        background-color: #f0f0f0;
        padding-left: 30px;
        overflow: hidden;
        /* Para evitar el desbordamiento */
    }

    .custom-container-fluid {
        background-color: <?php echo COLOR_FONDO; ?>;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        text-align: center;
        min-width: 50%;
        max-height: 80vh;
        /* Altura máxima */
        overflow-y: auto;
        /* Scroll vertical si el contenido es mayor que la altura */
    }

    .custom-container-fluid h1 {
        color: <?php echo COLOR_LETRAS; ?>;
        margin-bottom: 20px;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        color: <?php echo COLOR_LETRAS; ?>;
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


    @media (max-width: 768px) {
        .full-screen-container {
            flex-direction: column;
            gap: 10px;
            height: 60vh;
        }
    }

    .form-group label {
        color: black;
    }

    /* animacion y diseño de aplicacion y name de aplicacion */
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
        /* Ajustar tamaño del fondo */
        border-radius: 3px;
        /* Ajustar borde redondeado */
        font-size: 12px;
        /* Ajustar tamaño de la fuente */
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

    /* final de diseño de aplicacion */

    .generacion_enlace {
        display: none;
    }

    .spinner-border {
        width: 3rem;
        height: 3rem;
    }

    #json-informacion {
        display: none;
    }

    .json_informacion {
        max-height: 600px;
        /* Puedes ajustar esta altura según tus necesidades */
        overflow-y: auto;
        /* Añadir scroll vertical */
        border: 1px solid #ced4da;
        /* Borde ligero */
        padding: 10px;
        background-color: #f8f9fa;
        /* Fondo claro */
        border-radius: 5px;
    }

    #json-content {
        white-space: pre-wrap;
        /* Mantener los saltos de línea y espacios */
        font-family: monospace;
        /* Fuente monoespaciada para el JSON */
        color: #333;
        /* Color de texto */
    }

    @media (max-width: 768px) {
        .json_informacion {
            min-height: 400px;
            width: 80%;
        }
    }

    .img-container {
    cursor: pointer; /* Cambiar a puntero para indicar clic */
    display: inline-block;
    padding: 15px;
    border-radius: 8px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    background-color: #f8f9fa; /* Fondo claro */
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.img-container:hover {
    transform: scale(1.05); /* Escalar ligeramente */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Sombra más grande */
}

.img-container img {
    width: 100px;
    transition: transform 0.3s ease;
}

.img-container:hover img {
    transform: scale(1.1); /* Agrandar imagen ligeramente */
}

.generacion_enlace {
    display: none; /* Ocultar inicialmente */
    margin-top: 20px;
    transition: opacity 0.5s ease; /* Añadir transición para mostrar */
}

.loading-animation {
    display: none; /* Ocultar inicialmente */
    text-align: center;
    margin-top: 20px;
}

.spinner-border {
    width: 3rem;
    height: 3rem;
}

</style>