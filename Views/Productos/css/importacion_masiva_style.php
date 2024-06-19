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
    }

    .custom-container-fluid h1 {
        color: white;
        margin-bottom: 20px;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        color: white;
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
        color: white;
        /* Cambiar el color de la etiqueta a blanco */
    }

    .form-control-file {
        color: white;
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
</style>