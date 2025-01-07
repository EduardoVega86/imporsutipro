<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Swagger UI</title>
    <!-- Incluimos los estilos de swagger-ui-dist desde un CDN -->
    <link rel="stylesheet" href="https://unpkg.com/swagger-ui-dist@4/swagger-ui.css" />
</head>

<body>
    <div id="swagger-ui"></div>

    <!-- Incluimos los scripts de swagger-ui-dist -->
    <script src="https://unpkg.com/swagger-ui-dist@4/swagger-ui-bundle.js"></script>
    <script src="https://unpkg.com/swagger-ui-dist@4/swagger-ui-standalone-preset.js"></script>

    <script>
        // Aquí indicamos la URL de tu JSON (el endpoint que creaste)
        window.onload = function() {
            window.ui = SwaggerUIBundle({
                url: '/swagger/docs', // <-- Ruta a tu JSON (método docs())
                dom_id: '#swagger-ui',
                presets: [
                    SwaggerUIBundle.presets.apis,
                    SwaggerUIStandalonePreset
                ],
                layout: "BaseLayout",
            });
        };
    </script>
</body>

</html>