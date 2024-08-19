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

<div class="modal fade" id="agregar_usuarioModal" tabindex="-1" aria-labelledby="agregar_usuarioModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="agregar_usuarioModalLabel"><i class="fas fa-edit"></i> Registro de Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="agregar_usuario_form" enctype="multipart/form-data">
                    <div class="row mb-3">
                        <div class="col">
                            <label for="nombre" class="form-label">Nombre:</label>
                            <input type="text" class="form-control" id="nombre" placeholder="Nombre">
                        </div>
                        <div class="col">
                            <label for="email" class="form-label">Email:</label>
                            <input type="email" class="form-control" id="email" placeholder="Email">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label for="contrasena" class="form-label">Contraseña:</label>
                            <input type="password" class="form-control" id="contrasena" placeholder="Contraseña">
                        </div>
                        <div class="col">
                            <label for="repiteContrasena" class="form-label">Repite contraseña:</label>
                            <input type="password" class="form-control" id="repiteContrasena" placeholder="Repite contraseña">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label for="grupoPermisos" class="form-label">Grupo de permisos:</label>
                            <select class="form-select" id="grupoPermisos">
                                <option value="1" selected>Administrador</option>
                                <option value="5">Ventas</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary" id="agregar_usuario">Agregar</button>
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
            $('#agregar_usuario_form')[0].reset();
        }

        // Evento para reiniciar el formulario cuando se cierre el modal
        $('#agregar_usuarioModal').on('hidden.bs.modal', function() {
            var button = document.getElementById('agregar_usuario');
            button.disabled = false; // Desactivar el botón
            resetForm();
        });

        $('#agregar_usuario_form').submit(function(event) {
            event.preventDefault(); // Evita que el formulario se envíe de la forma tradicional

            var contrasena = $('#contrasena').val();
            var repiteContrasena = $('#repiteContrasena').val();

            // Validar que las contraseñas sean iguales
            if (contrasena !== repiteContrasena) {
                toastr.warning("Las contraseñas no coinciden", "ADVERTENCIA", {
                    positionClass: "toast-bottom-center",
                });
                return; // Detener el envío del formulario si no coinciden
            }

            var button = document.getElementById('agregar_usuario');
            button.disabled = true; // Desactivar el botón

            // Crea un objeto FormData
            var formData = new FormData();
            formData.append('nombre', $('#nombre').val());
            formData.append('correo', $('#email').val());
            formData.append('contrasena', contrasena);
            formData.append('cargo', $('#grupoPermisos').val());

            // Realiza la solicitud AJAX
            $.ajax({
                url: SERVERURL + 'Usuarios/agregar_usuario',
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

                        $('#agregar_usuarioModal').modal('hide');
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