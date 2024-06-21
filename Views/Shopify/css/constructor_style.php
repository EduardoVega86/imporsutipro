<style>
    .full-screen-container {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        background-color: #f0f0f0;
        padding-left: 30px;
    }

    .custom-container-fluid {
        background-color: <?php echo COLOR_FONDO; ?>;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        text-align: center;
        min-width: 50%;
    }

    .custom-container-fluid h1 {
        color: <?php echo COLOR_LETRAS;?>;
        margin-bottom: 20px;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        color: <?php echo COLOR_LETRAS;?>;
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

    .btn {
        background-color: #198754;
        color: black;
        border: none;
        padding: 10px 20px;
        border-radius: 4px;
        cursor: pointer;
    }

    @media (max-width: 768px) {
        .full-screen-container {
            flex-direction: column;
            gap: 2px;
            height: 60vh;
        }
    }

    .form-group label {
        color: <?php echo COLOR_LETRAS;?>;
        /* Cambiar el color de la etiqueta a blanco */
    }

    .form-control-file {
        color: <?php echo COLOR_LETRAS;?>;
        /* Cambiar el color del texto del input file a blanco */
    }

    .download-button-container {
        text-align: left;
        /* Alinea el botón a la izquierda */
    }

    .download-button {
        font-size: 0.9rem;
        /* Reduce el tamaño de la fuente */
        padding: 0.5rem 1rem;
        /* Ajusta el relleno del botón */
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
</style>