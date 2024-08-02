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
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        line-height: 1.6;
    }

    .config-item {
        margin-bottom: 20px;
        padding: 15px;
        border: 1px solid #ddd;
        border-radius: 5px;
        background-color: #fff;
        transition: box-shadow 0.3s ease;
    }

    .config-item:hover {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .config-item ul {
        padding-left: 20px;
        list-style: none;
        margin: 0;
        border-left: 3px solid #ddd;
        /* Línea para jerarquía */
    }

    .config-item li {
        margin-bottom: 15px;
        /* Aumentamos el margen inferior */
        position: relative;
        font-size: 14px;
        padding-left: 25px;
        /* Aumentamos el padding para dar espacio al círculo */
        display: flex;
        align-items: center;
    }

    .config-item li::before {
        content: '';
        position: absolute;
        left: 0px;
        /* Ajusta la posición del círculo */
        top: 50%;
        transform: translateY(-50%);
        height: 10px;
        width: 10px;
        border-radius: 50%;
        background-color: #007bff;
        /* Fondo azul del círculo */
        margin-right: 10px;
        /* Espaciado entre el círculo y el texto */
        transition: background-color 0.3s ease;
    }

    .config-item li:hover::before {
        background-color: #0056b3;
        /* Color más oscuro al hacer hover */
    }

    .config-item li>strong {
        font-weight: 600;
        color: #222;
        margin-right: 5px;
    }

    .config-item li span {
        color: #555;
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