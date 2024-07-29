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

<div class="modal fade" id="editar_horizontalModal" tabindex="-1" aria-labelledby="editar_horizontalModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editar_horizontalModalLabel"><i class="fas fa-edit"></i> Editar Horizontal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editar_horizontal_form" enctype="multipart/form-data">
                    <input type="hidden" id="id_horizontal" name="id_horizontal">

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="texto_flotanteEditar" class="form-label">Texto</label>
                            <textarea class="form-control" id="texto_flotanteEditar" rows="3" placeholder="Texto"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label for="visible_flotanteEditar" class="form-label">Visible</label>
                            <select class="form-select" id="visible_flotanteEditar">
                                <option value="1">Si</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="posicion_flotanteEditar" class="form-label">Posición</label>
                            <select class="form-select" id="posicion_flotanteEditar">
                                <option value="1">Arriba</option>
                                <option value="2">Abajo</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary" id="actualizar_horizontal">Actualizar</button>
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
            $('#editar_horizontal_form')[0].reset();
        }

        // Evento para reiniciar el formulario cuando se cierre el modal
        $('#editar_horizontalModal').on('hidden.bs.modal', function() {
            var button = document.getElementById('actualizar_horizontal');
            button.disabled = false; // Desactivar el botón
            resetForm();
        });


        $('#editar_horizontal_form').submit(function(event) {
            event.preventDefault(); // Evita que el formulario se envíe de la forma tradicional

            var button = document.getElementById('actualizar_horizontal');
            button.disabled = true; // Desactivar el botón

            // Crea un objeto FormData
            var formData = new FormData();
            formData.append('id_horizontal', $('#id_horizontal').val());
            formData.append('texto', $('#texto_flotanteEditar').val());
            formData.append('estado', $('#visible_flotanteEditar').val());
            formData.append('posicion', $('#posicion_flotanteEditar').val());

            // Realiza la solicitud AJAX
            $.ajax({
                url: SERVERURL + 'Usuarios/editarHorizontal',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    response = JSON.parse(response);
                    // Mostrar alerta de éxito
                    if (response.status == 500) {
                        toastr.error(
                            "EL HORIZONTAL NO SE AGREGRO CORRECTAMENTE",
                            "NOTIFICACIÓN", {
                                positionClass: "toast-bottom-center"
                            }
                        );
                    } else if (response.status == 200) {
                        toastr.success("HORIZONTAL AGREGADO CORRECTAMENTE", "NOTIFICACIÓN", {
                            positionClass: "toast-bottom-center",
                        });

                        $('#editar_horizontalModal').modal('hide');
                        resetForm();
                        initDataTableHorizonal();
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