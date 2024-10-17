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

<div class="modal fade" id="agregar_testimonioModal" tabindex="-1" aria-labelledby="agregar_testimonioModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="agregar_testimonioModalLabel"><i class="fas fa-edit"></i> Nuevo Template</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="agregar_plantilla_form" enctype="multipart/form-data">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="atajo" class="form-label">Atajo</label>
                            <input type="text" class="form-control" id="atajo" placeholder="Atajo">
                        </div>
                        <div class="col-md-12">
                            <label for="plantilla" class="form-label">Texto del template</label>
                            <textarea class="form-control" id="plantilla" rows="5" placeholder="Texto del Template"></textarea>
                        </div>
                    </div>
                 
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary" id="guardar_testimonio">Guardar</button>
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
            $('#agregar_testimonio_form')[0].reset();
            $('#preview-imagen-testimonio').attr('src', '#').hide();
        }

        // Evento para reiniciar el formulario cuando se cierre el modal
        $('#agregar_plantillaModal').on('hidden.bs.modal', function() {
            var button = document.getElementById('guardar_testimonio');
            button.disabled = false; // Desactivar el botón
            resetForm();
        });

        // Vista previa de la imagen
 

        $('#agregar_plantilla_form').submit(function(event) {
            event.preventDefault(); // Evita que el formulario se envíe de la forma tradicional

            var button = document.getElementById('guardar_testimonio');
            button.disabled = true; // Desactivar el botón

            // Crea un objeto FormData
            var formData = new FormData();
            formData.append('atajo', $('#atajo').val());
            formData.append('plantilla', $('#plantilla').val());
         

            // Realiza la solicitud AJAX
            $.ajax({
                url: '' + SERVERURL + 'Usuarios/agregarPlantilla',
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

                        $('#agregar_testimonioModal').modal('hide');
                        resetForm();
                        initDataTableObtenerUsuariosPlataforma();
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