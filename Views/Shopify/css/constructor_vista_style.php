<style>
    .full-screen-container {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        background-color: #f0f0f0;
        padding: 30px;
        overflow: hidden;
    }

    .custom-container-fluid {
        background-color: <?php echo COLOR_FONDO; ?>;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        text-align: left;
        min-width: 60%;
        max-height: 80vh;
        overflow-y: auto;
    }

    .custom-container-fluid h1 {
        color: <?php echo COLOR_LETRAS; ?>;
        margin-bottom: 20px;
        text-align: center;
    }

    .datos_shopify {
        color: #333;
        font-family: Arial, sans-serif;
        line-height: 1.5;
    }

    .config-item {
        margin-bottom: 20px;
        padding: 15px;
        border: 1px solid #ddd;
        border-radius: 5px;
        background-color: #fff;
    }

    .config-item ul {
        padding-left: 20px;
        list-style: none;
        /* Eliminar viñetas predeterminadas */
        margin: 0;
    }

    .config-item li {
        margin-bottom: 8px;
        padding-left: 20px;
        border-left: 3px solid #ddd;
        position: relative;
        font-size: 14px;
        /* Ajustar el tamaño de la fuente */
    }

    .config-item li::before {
        content: '';
        position: absolute;
        left: -8px;
        top: 8px;
        height: 10px;
        width: 10px;
        border-radius: 50%;
        background-color: #007bff;
    }

    .config-item li>strong {
        font-weight: 600;
        color: #333;
        /* Color de clave más oscuro */
        margin-right: 5px;
    }

    .config-item li span {
        color: #555;
        /* Color de valor más claro */
    }

    .config-item .nested {
        margin-left: 20px;
        border-left: 2px dashed #ccc;
        padding-left: 10px;
    }

    @media (max-width: 768px) {
        .full-screen-container {
            flex-direction: column;
            height: 70vh;
        }
    }

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
</style>