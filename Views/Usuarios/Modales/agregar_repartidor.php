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

<div class="modal fade" id="agregar_repartidorModal" tabindex="-1" aria-labelledby="agregar_repartidorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="agregar_repartidorModalLabel"><i class="fas fa-edit"></i> Registro de Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="agregar_repartidor_form" enctype="multipart/form-data">
                    <div class="row mb-3">
                        <div class="col">
                            <label for="nombre_repartidor" class="form-label">Nombre:</label>
                            <input type="text" class="form-control" id="nombre_repartidor" placeholder="Nombre">
                        </div>
                        <div class="col">
                            <label for="celular_repartidor" class="form-label">Celular:</label>
                            <input type="text" class="form-control" id="celular_repartidor" placeholder="celular">
                        </div>
                        <div class="col">
                            <label for="usuario_repartidor" class="form-label">Usuario:</label>
                            <input type="text" class="form-control" id="usuario_repartidor" placeholder="usuario">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label for="contrasena_repartidor" class="form-label">Contraseña:</label>
                            <input type="password" class="form-control" id="contrasena_repartidor" placeholder="Contraseña">
                        </div>
                        <div class="col">
                            <label for="repiteContrasena_repartidor" class="form-label">Repite contraseña:</label>
                            <input type="password" class="form-control" id="repiteContrasena_repartidor" placeholder="Repite contraseña">
                        </div>
                    </div>
                    <div class="row mb-3">
                    </div>
                    <div class="row mb-3">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary" id="agregar_repartidor">Agregar</button>
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
            $('#agregar_repartidor_form')[0].reset();
        }

        // Evento para reiniciar el formulario cuando se cierre el modal
        $('#agregar_repartidorModal').on('hidden.bs.modal', function() {
            var button = document.getElementById('agregar_repartidor');
            button.disabled = false; // Desactivar el botón
            resetForm();
        });

        $('#agregar_repartidor_form').submit(function(event) {
            event.preventDefault(); // Evita que el formulario se envíe de la forma tradicional

            var contrasena = $('#contrasena_repartidor').val();
            var repiteContrasena = $('#repiteContrasena_repartidor').val();

            // Validar que las contraseñas sean iguales
            if (contrasena !== repiteContrasena) {
                toastr.warning("Las contraseñas no coinciden", "ADVERTENCIA", {
                    positionClass: "toast-bottom-center",
                });
                return; // Detener el envío del formulario si no coinciden
            }

            var button = document.getElementById('agregar_repartidor');
            button.disabled = true; // Desactivar el botón

            // Crea un objeto FormData
            var formData = new FormData();
            formData.append('nombre', $('#nombre_repartidor').val());
            formData.append('celular', $('#celular_repartidor').val());
            formData.append('usuario', $('#usuario_repartidor').val());
            formData.append('contrasena', contrasena);

            // Realiza la solicitud AJAX
            $.ajax({
                url: SERVERURL + 'speed/guardarMotorizado',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    response = JSON.parse(response);
                    // Mostrar alerta de éxito
                    if (response.status == 500) {
                        toastr.error(
                            "EL USUARIO NO SE AGREGRO CORRECTAMENTE",
                            "NOTIFICACIÓN", {
                                positionClass: "toast-bottom-center"
                            }
                        );
                    } else if (response.status == 200) {
                        toastr.success("USUARIO AGREGADO CORRECTAMENTE", "NOTIFICACIÓN", {
                            positionClass: "toast-bottom-center",
                        });

                        $('#agregar_repartidorModal').modal('hide');
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