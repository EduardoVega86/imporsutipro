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

<div class="modal fade" id="editar_usuarioModal" tabindex="-1" aria-labelledby="editar_usuarioModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editar_usuarioModalLabel"><i class="fas fa-edit"></i> Editar de Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editar_usuario_form" enctype="multipart/form-data">
                    <input type="hidden" id="id_usuario_editar" name="id_usuario_editar">
                    <div class="row mb-3">
                        <div class="col">
                            <label for="nombre_editar" class="form-label">Nombre:</label>
                            <input type="text" class="form-control" id="nombre_editar" placeholder="Nombre">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label for="contrasena_editar" class="form-label">Contraseña:</label>
                            <input type="password" class="form-control" id="contrasena_editar" placeholder="Contraseña">
                        </div>
                        <div class="col">
                            <label for="repiteContrasena_editar" class="form-label">Repite contraseña:</label>
                            <input type="password" class="form-control" id="repiteContrasena_editar" placeholder="Repite contraseña">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label for="grupoPermisos_editar" class="form-label">Grupo de permisos:</label>
                            <select class="form-select" id="grupoPermisos_editar">
                                <option value="1" selected>Administrador</option>
                                <option value="5">Ventas</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary" id="editar_usuario">editar</button>
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
            $('#editar_usuario_form')[0].reset();
        }

        // Evento para reiniciar el formulario cuando se cierre el modal
        $('#editar_usuarioModal').on('hidden.bs.modal', function() {
            var button = document.getElementById('editar_usuario');
            button.disabled = false; // Desactivar el botón
            resetForm();
        });

        $('#editar_usuario_form').submit(function(event) {
            event.preventDefault(); // Evita que el formulario se envíe de la forma tradicional

            var contrasena = $('#contrasena_editar').val();
            var repiteContrasena = $('#repiteContrasena_editar').val();

            // Validar que las contraseñas sean iguales
            if (contrasena !== repiteContrasena) {
                toastr.warning("Las contraseñas no coinciden", "ADVERTENCIA", {
                    positionClass: "toast-bottom-center",
                });
                return; // Detener el envío del formulario si no coinciden
            }

            var button = document.getElementById('editar_usuario');
            button.disabled = true; // Desactivar el botón

            // Crea un objeto FormData
            var formData = new FormData();
            formData.append('id_usuario', $('#id_usuario_editar').val());
            formData.append('nombre', $('#nombre_editar').val());
            formData.append('contrasena', contrasena);
            formData.append('cargo', $('#grupoPermisos_editar').val());

            // Realiza la solicitud AJAX
            $.ajax({
                url: SERVERURL + 'Usuarios/editar_usuario',
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

                        $('#editar_usuarioModal').modal('hide');
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