<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Productos/css/importacion_masiva_style.php'; ?>

<div class="full-screen-container">
    <div class="custom-container-fluid mt-4">
        <h1>Importación Masiva</h1>
        <div class="download-button-container">
            <a href="https://tiendas.imporsuitpro.com/plantilla/plantilla_productos.xlsx" class="btn btn-success download-button">Descargar Plantilla</a>
        </div>
        <form id="uploadForm" enctype="multipart/form-data">

            <div class="form-group w-100 hidden-field" id="bodega-field">
                <label for="bodega">Bodega:</label>
                <select class="form-select" id="bodega_importacionMasiva">
                    <option value="0" selected>-- Selecciona Bodega --</option>
                </select>
            </div>
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

            var formData = new FormData();
            formData.append('archivo', $('#fileInput')[0].files[0]); // Añadir archivo al FormData
            formData.append('id_bodega', $('#bodega_importacionMasiva').val()); // Añadir ID de bodega al FormData

            $.ajax({
                url: '<?php echo SERVERURL; ?>Productos/importarExcel', // Ruta del controlador que manejará el archivo
                type: 'POST',
                data: formData,
                contentType: false, // Necesario para que jQuery no añada un tipo de contenido
                processData: false, // Necesario para que jQuery no convierta los datos a una cadena
                success: function(response) {
                    response = JSON.parse(response);
                    if (response.status == 500) {
                        toastr.error(
                            "LOS PRODUCTOS NO SE AGREGARON CORRECTAMENTE",
                            "NOTIFICACIÓN", {
                                positionClass: "toast-bottom-center"
                            }
                        );
                    } else if (response.status == 200) {
                        toastr.success("PRODUCTOS AGREGADOS CORRECTAMENTE", "NOTIFICACIÓN", {
                            positionClass: "toast-bottom-center",
                        });

                        $('#imagen_categoriaModal').modal('hide');
                        initDataTable();
                    }
                },
                error: function() {
                    alert('Error al subir el archivo');
                }
            });
        });
    });

    //cargar select de bodegas
    $(document).ready(function() {
        // Realiza la solicitud AJAX para obtener la lista de bodegas
        $.ajax({
            url: SERVERURL + "productos/listar_bodegas",
            type: "GET",
            dataType: "json",
            success: function(response) {
                // Asegúrate de que la respuesta es un array
                if (Array.isArray(response)) {
                    response.forEach(function(bodega) {
                        // Agrega una nueva opción al select por cada bodega
                        $("#bodega_importacionMasiva").append(new Option(bodega.nombre, bodega.id));
                    });
                } else {
                    console.log("La respuesta de la API no es un array:", response);
                }
            },
            error: function(error) {
                console.error("Error al obtener la lista de bodegas:", error);
            },
        });
    });
</script>

<?php require_once './Views/templates/footer.php'; ?>