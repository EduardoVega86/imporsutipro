<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documentación de la API</title>
    <!-- Incluir el CSS de Swagger -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/swagger-ui/4.15.5/swagger-ui.css" />
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }
    </style>
</head>

<body>
    <div id="swagger-ui"></div>
    <!-- Incluir el script de Swagger -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/swagger-ui/4.15.5/swagger-ui-bundle.js"></script>
    <script>
        window.onload = () => {
            SwaggerUIBundle({
                url: "<?php echo SERVERURL; ?>api/docs", // URL donde generaste el JSON
                dom_id: '#swagger-ui',
            });
        };
    </script>
</body>

</html>