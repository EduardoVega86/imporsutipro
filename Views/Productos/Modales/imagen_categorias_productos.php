<style>
    .modal-content {
        border-radius: 15px;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }

    .modal-header {
        background-color: #171931;
        color: white;
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
</style>

<div class="modal fade" id="imagen_categoriaModal" tabindex="-1" aria-labelledby="imagen_categoriaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imagen_categoriaModalLabel"><i class="fas fa-edit"></i> Nueva Categoría</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="imageForm" enctype="multipart/form-data">
                    <input type="hidden" id="id_imagenCategoria" name="id_linea">
                    <div class="form-group">
                        <label for="imageInput">Imagen</label>
                        <input type="file" class="form-control-file" id="imageInput" accept="image/*" name="imagen">
                    </div>
                    <img id="imagePreview" class="image-preview" src="" alt="Preview">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#imageForm').submit(function(event) {
            event.preventDefault(); // Evita el envío del formulario por defecto

            var formData = new FormData(this); // Crea un objeto FormData a partir del formulario

            $.ajax({
                url: SERVERURL + 'Productos/guardar_imagen_categorias', // Cambia esta ruta por la ruta correcta a tu controlador
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    alert('Imagen guardada correctamente');
                    $('#imagen_categoriaModal').modal('hide');
                    // Puedes añadir lógica adicional aquí para actualizar la vista
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error al guardar la imagen: ' + textStatus);
                }
            });
        });
    });
</script>