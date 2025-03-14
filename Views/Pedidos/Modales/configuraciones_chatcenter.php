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

<div class="modal fade" id="configuraciones_chatcenterModal" tabindex="-1" aria-labelledby="agregar_testimonioModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="agregar_testimonioModalLabel"><i class="fas fa-edit"></i> Configuraciones generar guias</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="configuraciones_chatcenter_form" enctype="multipart/form-data">
                    <div class="row mb-3">
                        <div class="d-flex flex-column">
                            <label for="template_whatsapp" class="form-label">Plantilla de respuesta Laar:</label>
                            <select id="select_templates" style="width: 100%;">
                                <option value="">Cargando...</option>
                            </select>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
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
            $('#configuraciones_chatcenter_form')[0].reset();

        }

        // Evento para reiniciar el formulario cuando se cierre el modal
        $('#configuraciones_chatcenterModal').on('hidden.bs.modal', function() {
            resetForm();
        });

        // Vista previa de la imagen


        $('#configuraciones_chatcenter_form').submit(function(event) {
            event.preventDefault(); // Evita el envío tradicional

            var formData = new FormData();
            formData.append('id_template_whatsapp', $('#select_templates').val() || "");

            $.ajax({
                url: SERVERURL + 'Usuarios/editar_configuracion',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    response = JSON.parse(response);

                    if (response.status == 500) {
                        toastr.error("Error al editar la configuración", "NOTIFICACIÓN", {
                            positionClass: "toast-bottom-center"
                        });
                    } else if (response.status == 200) {
                        toastr.success("Configuración editada correctamente", "NOTIFICACIÓN", {
                            positionClass: "toast-bottom-center",
                        });

                        $('#configuraciones_chatcenterModal').modal('hide');
                        resetForm();
                    }
                },
                error: function(error) {
                    alert('Hubo un error al editar la configuración');
                    console.log(error);
                }
            });
        });
    });
</script>