<style>
    .form-group {
        margin-bottom: 15px;
    }

    /* .modal-header {
        background-color: #343a40;
        color: white;
    } */

    .hidden-tab {
        display: none !important;
    }

    .hidden-field {
        display: none;
    }
</style>

<div class="modal fade" id="agregar_profesionalModal" tabindex="-1" aria-labelledby="agregar_testimonioModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="agregar_testimonioModalLabel"><i class="fas fa-edit"></i> Nuevo Testimonio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="agregar_profesional_form" enctype="multipart/form-data">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="nombre_p" placeholder="Nombre">
                        </div>
                          <div class="col-md-6">
                            <label for="nombre" class="form-label">LinkedIn</label>
                            <input type="text" class="form-control" id="linkedin_p" placeholder="LinkeIn">
                        </div>
                       
                    </div>
                    
                       <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="facebook_p" placeholder="Facebook">
                        </div>
                          <div class="col-md-6">
                            <label for="nombre" class="form-label">LinkedIn</label>
                            <input type="text" class="form-control" id="instagram_p" placeholder="Instagram">
                        </div>
                       
                    </div>
                     <div class="row mb-3">
                     <div class="col-md-6">
                            <label for="testimonio" class="form-label">Descripcion</label>
                            <textarea class="form-control" id="testimonio" rows="3" placeholder="Descripcion"></textarea>
                        </div>
                         
                         </div>
                    
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="imagen_profesional" class="form-label">Imagen</label>
                            <input type="file" class="form-control" id="imagen_profesional" name="imagen_profesional" accept="image/*">
                            <img id="preview-imagen-profesional" src="#" alt="Vista previa de la imagen" style="display: none; margin-top: 10px; max-width: 100%;">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary" id="guardar_profesional">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Función para reiniciar el formulario
        function resetForm() {
            $('#agregar_profesional_form')[0].reset();
            $('#preview-imagen-profesional').attr('src', '#').hide();
        }

        // Evento para reiniciar el formulario cuando se cierre el modal
        $('#agregar_profesionalModal').on('hidden.bs.modal', function() {
            var button = document.getElementById('guardar_profesional');
            button.disabled = false; // Desactivar el botón
            resetForm();
        });

        // Vista previa de la imagen
        $('#imagen_profesional').change(function() {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#preview-imagen-profesional').attr('src', e.target.result).show();
            }
            reader.readAsDataURL(this.files[0]);
        });

        $('#agregar_profesional_form').submit(function(event) {
            event.preventDefault(); // Evita que el formulario se envíe de la forma tradicional

            var button = document.getElementById('guardar_profesional');
            button.disabled = true; // Desactivar el botón

            // Crea un objeto FormData
            var formData = new FormData();
            formData.append('nombre', $('#nombre_p').val());
            formData.append('descripcion', $('#descripcion_p').val());
            formData.append('linkedin', $('#linkedin_p').val());
            formData.append('facebook', $('#facebook_p').val());
            formData.append('instagram', $('#instagram_p').val());
            formData.append('imagen', $('#imagen_profesional')[0].files[0]);

            // Realiza la solicitud AJAX
            $.ajax({
                url: '' + SERVERURL + 'Usuarios/agregarProfesionales',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    response = JSON.parse(response);
                    // Mostrar alerta de éxito
                    if (response.status == 500) {
                        toastr.error(
                            "EL PRODUCTO NO SE AGREGRO CORRECTAMENTE",
                            "NOTIFICACIÓN", {
                                positionClass: "toast-bottom-center"
                            }
                        );
                    } else if (response.status == 200) {
                        toastr.success("PRODUCTO AGREGADO CORRECTAMENTE", "NOTIFICACIÓN", {
                            positionClass: "toast-bottom-center",
                        });

                        $('#agregar_profesionalModal').modal('hide');
                        resetForm();
                        initDataTableProfesionales();
                    }
                },
                error: function(error) {
                    alert('Hubo un error al agregar el producto');
                    console.log(error);
                }
            });
        });
    });
</script>