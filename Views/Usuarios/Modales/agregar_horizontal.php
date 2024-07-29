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

<div class="modal fade" id="agregar_horizontalModal" tabindex="-1" aria-labelledby="agregar_horizontalModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="agregar_horizontalModalLabel"><i class="fas fa-edit"></i> Nuevo Flotante</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="agregar_horizontal_form" enctype="multipart/form-data">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="texto_flotante" class="form-label">Texto</label>
                            <textarea class="form-control" id="texto_flotante" rows="3" placeholder="Texto"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label for="visible_flotante" class="form-label">Visible</label>
                            <select class="form-select" id="visible_flotante">
                                <option value="1">Si</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="ubicacion_flotante" class="form-label">Ubicación</label>
                            <select class="form-select" id="ubicacion_flotante">
                                <option value="1">Arriba</option>
                                <option value="2">Abajo</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary" id="guardar_horizontal">Guardar</button>
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
            $('#agregar_horizontal_form')[0].reset();
        }

        // Evento para reiniciar el formulario cuando se cierre el modal
        $('#agregar_horizontalModal').on('hidden.bs.modal', function() {
            var button = document.getElementById('guardar_horizontal');
            button.disabled = false; // Desactivar el botón
            resetForm();
        });

        $('#agregar_horizontal_form').submit(function(event) {
            event.preventDefault(); // Evita que el formulario se envíe de la forma tradicional

            var button = document.getElementById('guardar_horizontal');
            button.disabled = true; // Desactivar el botón

            // Crea un objeto FormData
            var formData = new FormData();
            formData.append('texto', $('#texto_flotante').val());
            formData.append('estado', $('#visible_flotante').val());
            formData.append('posicion', $('#ubicacion_flotante').val());

            // Realiza la solicitud AJAX
            $.ajax({
                url: '' + SERVERURL + 'Usuarios/agregarhorizontal',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    response = JSON.parse(response);
                    // Mostrar alerta de éxito
                    if (response.status == 500) {
                        toastr.error(
                            "EL FLOTANTE NO SE AGREGRO CORRECTAMENTE",
                            "NOTIFICACIÓN", {
                                positionClass: "toast-bottom-center"
                            }
                        );
                    } else if (response.status == 200) {
                        toastr.success("FLOTANTE AGREGADO CORRECTAMENTE", "NOTIFICACIÓN", {
                            positionClass: "toast-bottom-center",
                        });

                        $('#agregar_horizontalModal').modal('hide');
                        resetForm();
                        initDataTablehorizontal();
                    }
                },
                error: function(error) {
                    alert('Hubo un error al agregar el flotante');
                    console.log(error);
                }
            });
        });
    });
</script>