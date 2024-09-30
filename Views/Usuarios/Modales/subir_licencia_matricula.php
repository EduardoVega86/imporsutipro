<style>
    .modal-content {
        border-radius: 15px;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }

    .modal-header {
        background-color: <?php echo COLOR_FONDO; ?>;
        color: <?php echo COLOR_LETRAS; ?>;
        border-top-left-radius: 15px;
        border-top-right-radius: 15px;
    }

    .modal-header .btn-close {
        color: white;
    }

    .modal-body {
        padding: 20px;
    }

    .modal-footer {
        border-top: none;
        padding: 10px 20px;
    }

    .modal-footer .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
    }

    .modal-footer .btn-primary {
        background-color: #ffc107;
        border-color: #ffc107;
        color: white;
    }

    .image-preview-container img {
        width: 100px;
        margin-right: 10px;
        margin-bottom: 10px;
    }
</style>

<div class="modal fade" id="imagen_licencia_matriculaModal" tabindex="-1" aria-labelledby="imagen_licencia_matriculaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imagen_licencia_matriculaModalLabel"><i class="fas fa-edit"></i> Matricula y Licencia</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="id_usuario_matricula_licencia" name="id_usuario_matricula_licencia">
                <div>
                    <div class="form-group mt-3">
                        <label for="imagen_licencia">licencia</label>
                        <input type="file" class="form-control-file" id="imagen_licencia" accept="image/*" name="imagen">
                    </div>
                    <img id="imagePreviewLicencia" class="image-preview mt-2" src="" alt="Preview" width="200px">
                </div>

                <div>
                    <div class="form-group mt-3">
                        <label for="imagen_matricula">Matricula</label>
                        <input type="file" class="form-control-file" id="imagen_matricula" accept="image/*" name="imagen">
                    </div>
                    <img id="imagePreviewmatricula" class="image-preview mt-2" src="" alt="Preview" width="200px">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="guardarImagenesBtn">Guardar</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Función para mostrar la previsualización de la imagen
        function previewImage(input, previewElementId) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#' + previewElementId).attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]); // Leer la imagen como URL
            }
        }

        // Escuchar el cambio de los inputs de archivos para previsualizar las imágenes
        $('#imagen_licencia').change(function() {
            previewImage(this, 'imagePreviewLicencia'); // Mostrar previsualización de la licencia
        });

        $('#imagen_matricula').change(function() {
            previewImage(this, 'imagePreviewmatricula'); // Mostrar previsualización de la matrícula
        });

        // Capturamos el evento del botón "Guardar"
        $('#guardarImagenesBtn').click(function() {
            // Obtenemos los archivos seleccionados
            var licencia = $('#imagen_licencia')[0].files[0];
            var matricula = $('#imagen_matricula')[0].files[0];

            // Verificamos que ambos archivos hayan sido seleccionados
            if (!licencia || !matricula) {
                alert('Por favor, selecciona ambos archivos (licencia y matrícula).');
                return;
            }

            // Creamos un objeto FormData para enviar los archivos
            var formData = new FormData();
            formData.append('licencia', licencia);
            formData.append('matricula', matricula);
            formData.append('id_usuario', $('#id_usuario_matricula_licencia').val());

            // Realizamos la petición AJAX
            $.ajax({
                url: SERVERURL + 'speed/subirMatriculaLicencia',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                dataType: "json",
                success: function(response) {
                    if (response.status == 500) {
                        toastr.error(
                            "LA IMAGEN NO SE AGREGRO CORRECTAMENTE",
                            "NOTIFICACIÓN", {
                                positionClass: "toast-bottom-center"
                            }
                        );
                    } else if (response.status == 200) {
                        toastr.success("IMAGEN AGREGADA CORRECTAMENTE", "NOTIFICACIÓN", {
                            positionClass: "toast-bottom-center",
                        });
                        $('#imagen_licencia_matriculaModal').modal('hide');
                    }
                },
                error: function(xhr, status, error) {
                    // Manejo del error
                    console.error(xhr.responseText);
                    alert('Ha ocurrido un error al guardar las imágenes.');
                }
            });
        });
    });
</script>