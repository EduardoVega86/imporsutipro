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

<div class="modal fade" id="editar_testimonioModal" tabindex="-1" aria-labelledby="editar_testimonioModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editar_testimonioModalLabel"><i class="fas fa-edit"></i> Editar template</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editar_testimonio_form" enctype="multipart/form-data">
                    <input type="hidden" id="id_template_Editar" name="id_template_Editar">

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="atajo_Editar" class="form-label">Atajo</label>
                            <input type="text" class="form-control" id="atajo_Editar" placeholder="Nombre">
                        </div>
                        <div class="col-md-12">
                            <label for="testimonio_testimonioEditar" class="form-label">Texto del Template</label>
                            <textarea class="form-control" id="texto_Editar" rows="5" placeholder="Texto del testimonio"></textarea>
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary" id="actualizar_testimonio">Actualizar</button>
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
            $('#editar_testimonio_form')[0].reset();
            $('#preview-imagen-testimonioEditar').attr('src', '#').hide();
        }

        // Evento para reiniciar el formulario cuando se cierre el modal
        $('#editar_testimonioModal').on('hidden.bs.modal', function() {
            var button = document.getElementById('actualizar_testimonio');
            button.disabled = false; // Desactivar el botón
            resetForm();
        });

        // Vista previa de la imagen
       

        $('#editar_testimonio_form').submit(function(event) {
            event.preventDefault(); // Evita que el formulario se envíe de la forma tradicional

            var button = document.getElementById('actualizar_testimonio');
            button.disabled = true; // Desactivar el botón

            // Crea un objeto FormData
            var formData = new FormData();
            formData.append('id_plantilla', $('#id_template_Editar').val());
            formData.append('atajo', $('#atajo_Editar').val());
            formData.append('texto', $('#texto_Editar').val());
      
            // Realiza la solicitud AJAX
            $.ajax({
                url: SERVERURL + 'Usuarios/editarplantilla',
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

                        $('#editar_testimonioModal').modal('hide');
                        resetForm();
                        initDataTableObtenerUsuariosPlataforma();
                    }
                },
                error: function(error) {
                    alert('Hubo un error al editar el producto');
                    console.log(error);
                }
            });
        });

    });
</script>