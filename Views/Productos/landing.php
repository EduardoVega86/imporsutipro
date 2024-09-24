<?php require_once './Views/templates/header.php'; ?>
<!-- Incluimos los estilos y scripts necesarios -->
<link href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote.min.css" rel="stylesheet">

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote.min.js"></script>

<!-- Estilos personalizados -->
<style>
    /* Limitar el ancho del contenedor del editor */
    .editor-container {
        max-width: 800px;
        margin: 0 auto;
    }

    /* Asegurar que el editor tome el 100% del ancho disponible */
    .note-editor {
        width: 100%;
    }

    /* Limitar el contenido editable a un ancho máximo y evitar el desbordamiento */
    .note-editable {
        max-width: 800px;
        word-wrap: break-word;
        white-space: normal;
        overflow-wrap: break-word;
        overflow-x: hidden;
        /* Evitar el desbordamiento horizontal */
    }

    /* Asegurar que las imágenes no desborden el editor */
    .note-editable img {
        max-width: 100%;
        height: auto;
    }

    /* Justificar párrafos y asegurar ajuste de línea */
    .note-editable p {
        text-align: justify;
        word-break: break-word;
        white-space: normal;
    }
</style>

<?php
if ($data == 0) {
?>
    <div class="container">
        <!-- no existe el producto -->
        <div class="row">
            <div class="col-12">
                <h1 class="text-center">No existe el producto</h1>
            </div>
        </div>
    </div>
<?php
} else {
?>
    <div class="container py-5">
        <div class="row">
            <div class="col-12">
                <h3 class="text-center">Landing</h3>
                <div class="editor-container">
                    <textarea id="summernote"></textarea>
                    <button class="accept-btn btn btn-primary" id="accept-btn">Aceptar</button>
                    <div class="html-output" id="html-output"></div>
                </div>
            </div>
        </div>
    <?php
}
    ?>
    <script>
        $(document).ready(function() {
            // Reemplaza SERVERURL por la URL de tu servidor
            const SERVERURL = 'https://new.imporsuitpro.com';

            // Obtener el ID del producto desde la URL
            const id_producto = location.href.split("/").pop();

            $.ajax({
                url: SERVERURL + '/productos/existeLanding/' + id_producto,
                method: 'GET',
                success: function(response) {
                    if (response == 1) {
                        $.ajax({
                            url: 'https://imagenes.imporsuitpro.com/obtenerLanding',
                            data: {
                                id_producto: id_producto
                            },
                            method: 'POST',
                            success: function(response) {
                                inicializarSummernote(response);
                            }
                        });
                    } else {
                        inicializarSummernote();
                    }
                }
            });

            function inicializarSummernote(contenido = '') {
                $('#summernote').summernote({
                    height: 300,
                    toolbar: [
                        ['style', ['style']],
                        ['font', ['bold', 'italic', 'underline', 'clear']],
                        ['fontname', ['fontname']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph', 'height']],
                        ['table', ['table']],
                        ['insert', ['link', 'picture', 'video']],
                        ['view', ['fullscreen', 'codeview', 'help']],
                        ['misc', ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull']]
                    ],
                    callbacks: {
                        onImageUpload: function(files) {
                            const formData = new FormData();
                            formData.append('file', files[0]);

                            $.ajax({
                                url: 'https://imagenes.imporsuitpro.com/subir',
                                method: 'POST',
                                data: formData,
                                contentType: false,
                                processData: false,
                                success: function(url) {
                                    $('#summernote').summernote('insertImage', "https://imagenes.imporsuitpro.com/" + url);
                                }
                            });
                        }
                    }
                });

                if (contenido !== '') {
                    // Si hay contenido, lo decodificamos y lo insertamos en el editor
                    let jsonResponse = JSON.parse(response);
                    let decodedHTML = $('<textarea/>').html(jsonResponse.data).text();
                    $('#summernote').summernote('code', decodedHTML);
                }
            }

            $('#accept-btn').click(function() {
                const editorContent = $('#summernote').summernote('code');

                const fullHtmlContent = `<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Generated HTML</title>
<style>
    body {
        max-width: 800px;
        margin: 0 auto;
        text-align: justify;
        word-wrap: break-word;
        overflow-wrap: break-word;
        word-break: break-word;
    }
    img {
        max-width: 100%;
        height: auto;
    }
    p {
        text-align: justify;
        word-break: break-word;
        white-space: normal;
    }
</style>
</head>
<body>
${editorContent}
</body>
</html>`;

                const formData = new FormData();
                formData.append('id_producto', id_producto);

                const existeLanding = $.ajax({
                    url: SERVERURL + '/productos/existeLanding/' + id_producto,
                    method: 'GET',
                    async: false
                }).responseText;

                if (existeLanding == 0) {
                    const blob = new Blob([fullHtmlContent], {
                        type: 'text/html'
                    });
                    const fileName = "landing_" + Math.floor(Math.random() * 100000000000) + '.html';
                    formData.append('file', blob, fileName);

                    $.ajax({
                        url: 'https://imagenes.imporsuitpro.com/landing',
                        method: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            manejarRespuesta(response);
                        },
                        error: function(xhr, status, error) {
                            console.error('Error al enviar el archivo:', error);
                        }
                    });
                } else {
                    formData.append('html', fullHtmlContent);

                    $.ajax({
                        url: 'https://imagenes.imporsuitpro.com/editarLanding',
                        method: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        dataType: 'json',
                        success: function(response) {
                            manejarRespuesta(response);
                        },
                        error: function(xhr, status, error) {
                            console.error('Error al enviar el archivo:', error);
                        }
                    });
                }
                $('#html-output').text(fullHtmlContent);
            });

            function manejarRespuesta(response) {
                response = typeof response === 'string' ? JSON.parse(response) : response;
                if (response.status === 200) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Landing guardado',
                        text: 'El archivo se ha guardado correctamente',
                        showConfirmButton: false,
                        timer: 1500
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error al guardar el landing',
                        text: 'Ocurrió un error al guardar el archivo',
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            }
        });
    </script>

    <?php require_once './Views/templates/footer.php'; ?>