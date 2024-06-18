<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Despacho/css/despacho_style.php'; ?>

<div class="full-screen-container">
    <div class="custom-container-fluid mt-4" style="margin-right: 20px;">
        <h1>Importacion Masiva</h1>
        <form id="uploadForm" enctype="multipart/form-data">
            <label for="fileUpload">Seleccione un archivo:</label>
            <input type="file" name="archivo" id="fileUpload">
            <button type="submit">Enviar Archivo</button>
        </form>

        <button id="despachoBtn" class="btn btn-success">Despacho</button>
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