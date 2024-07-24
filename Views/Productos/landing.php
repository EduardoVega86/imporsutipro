<?php require_once './Views/templates/header.php'; ?>

<?php require_once './Views/Productos/css/landing.php'; ?>
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
                    <button class="accept-btn" id="accept-btn">Aceptar</button>
                    <div class="html-output" id="html-output"></div>
                </div>
            </div>
        </div>
    <?php
}
    ?>
    <script>
        $(document).ready(function() {
            $('#summernote').summernote({
                height: 300,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['fontname', ['fontname']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['fullscreen', 'codeview', 'help']]
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
                                $('#summernote').summernote('editor.insertImage', "https://imagenes.imporsuitpro.com/" + url);
                            }
                        });
                    }
                }
            });

            $('#accept-btn').click(function() {
                //existe landing?
                const existeLanding = $.ajax({
                    url: 'https://imagenes.imporsuitpro.com/productos/existeLanding/' + <?php echo $data['id_producto'] ?>,
                    method: 'GET',
                    async: false
                }).responseText;
                console.log(existeLanding);

                /* const editorContent = $('#summernote').summernote('code');

                const fullHtmlContent = `<!DOCTYPE html>
                <html lang="es">
                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>Generated HTML</title>
                </head>
                <body>
                ${editorContent}
                </body>
                </html>`;

                const blob = new Blob([fullHtmlContent], {
                    type: 'text/html'
                });
                const fileName = "landing_" + Math.floor(Math.random() * 100000000000) + '.html';

                const formData = new FormData();
                formData.append('file', blob, fileName);

                $.ajax({
                    url: 'https://imagenes.imporsuitpro.com/landing', // Cambia esta URL al script PHP que manejará la subida del archivo
                    method: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        console.log('Archivo enviado:', fileName);
                        response = JSON.parse(response);
                        if (response.status === 200) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Landing guardado',
                                text: 'El archivo se ha guardado correctamente',
                                showConfirmButton: false,
                                timer: 1500
                            })
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error al guardar el landing',
                                text: 'Ocurrió un error al guardar el archivo',
                                showConfirmButton: false,
                                timer: 1500
                            })
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error al enviar el archivo:', error);
                    }
                });

                $('#html-output').text(fullHtmlContent); */
            });
        });
    </script>
    <?php require_once './Views/templates/footer.php'; ?>