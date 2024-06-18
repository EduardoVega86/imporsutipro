<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Productos/css/importacion_masiva_style.php'; ?>

<div class="full-screen-container">
    <div class="custom-container-fluid mt-4" style="margin-right: 20px;">
        <h1>Importación Masiva</h1>
        <form id="uploadForm" enctype="multipart/form-data">
            <div class="form-group">
                <label for="fileInput">Seleccionar archivo:</label>
                <input type="file" class="form-control-file" id="fileInput" name="file" required>
            </div>
            <button type="submit" class="btn btn-success">Enviar</button>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#uploadForm').on('submit', function(e) {
            e.preventDefault(); // Prevenir el envío normal del formulario

            var formData = new FormData(this); // Usar FormData para manejar archivos

            $.ajax({
                url: '<?php echo SERVERURL; ?>Productos/importarExcel', // Ruta del controlador que manejará el archivo
                type: 'POST',
                data: formData,
                contentType: false, // Necesario para que jQuery no añada un tipo de contenido
                processData: false, // Necesario para que jQuery no convierta los datos a una cadena
                success: function(response) {
                    alert('Archivo subido correctamente');
                    console.log(response); // Mostrar respuesta del servidor en consola
                },
                error: function() {
                    alert('Error al subir el archivo');
                }
            });
        });
    });
</script>

<?php require_once './Views/templates/footer.php'; ?>
