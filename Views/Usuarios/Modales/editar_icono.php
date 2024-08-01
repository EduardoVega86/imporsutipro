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

<div class="modal fade" id="editar_iconoModal" tabindex="-1" aria-labelledby="editar_iconoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editar_iconoModalLabel"><i class="fas fa-edit"></i> Nuevo Flotante</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editar_icono_form" enctype="multipart/form-data">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="texto_icono" class="form-label">Texto Icono:</label>
                            <input type="text" class="form-control" id="texto_icono" rows="3" placeholder="Texto del icono"></input>
                        </div>
                        <div class="col-md-6">
                            <label for="subTexto_icono" class="form-label">Sub-texto Icono</label>
                            <textarea class="form-control" id="subTexto_icono" rows="3" placeholder="Escriba el sub-texto"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label for="enale_icono" class="form-label">Enlace Icono:</label>
                            <input type="text" class="form-control" id="enale_icono" rows="3" placeholder="URL"></input>
                        </div>
                        <div class="col-md-6">
                            <label for="icono" class="form-label">Icono:</label>
                            <select class="form-select" id="icono">
                                <option selected value="">-- Selecciona Icono --</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary" id="editar_icono">Actualizar</button>
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
            $('#editar_icono_form')[0].reset();
        }

        // Evento para reiniciar el formulario cuando se cierre el modal
        $('#editar_iconoModal').on('hidden.bs.modal', function() {
            var button = document.getElementById('editar_icono');
            button.disabled = false; // Desactivar el botón
            resetForm();
        });

        $('#editar_icono_form').submit(function(event) {
            event.preventDefault(); // Evita que el formulario se envíe de la forma tradicional

            var button = document.getElementById('editar_icono');
            button.disabled = true; // Desactivar el botón

            // Crea un objeto FormData
            var formData = new FormData();
            formData.append('texto', $('#texto_flotante').val());
            formData.append('estado', $('#visible_flotante').val());
            formData.append('posicion', $('#posicion_flotante').val());

            // Realiza la solicitud AJAX
            $.ajax({
                url: '' + SERVERURL + 'Usuarios/editaricono',
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

                        $('#editar_iconoModal').modal('hide');
                        resetForm();
                        initDataTableHorizonal();
                    }
                },
                error: function(error) {
                    alert('Hubo un error al editar el flotante');
                    console.log(error);
                }
            });
        });
    });
</script>