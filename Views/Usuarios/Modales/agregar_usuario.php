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
                            <label for="nombres" class="form-label">Nombres:</label>
                            <input type="text" class="form-control" id="nombres" placeholder="Nombres">
                        </div>
                        <div class="col">
                            <label for="apellidos" class="form-label">Apellidos:</label>
                            <input type="text" class="form-control" id="apellidos" placeholder="Apellidos">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label for="usuario" class="form-label">Usuario:</label>
                            <input type="text" class="form-control" id="usuario" placeholder="Usuario">
                        </div>
                        <div class="col">
                            <label for="grupoPermisos" class="form-label">Grupo de permisos:</label>
                            <select class="form-select" id="grupoPermisos">
                                <option selected>Super Administrador</option>
                                <option>Cliente</option>
                                <option>Ventas</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label for="email" class="form-label">Email:</label>
                            <input type="email" class="form-control" id="email" placeholder="Email">
                        </div>
                        <div class="col">
                            <label for="sucursal" class="form-label">Sucursal:</label>
                            <select class="form-select" id="sucursal">
                                <option selected>-- Selecciona --</option>
                                <option>Sucursal 1</option>
                            </select>
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
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary" id="agregar_usuario">Actualizar</button>
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

            var button = document.getElementById('agregar_usuario');
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

                        $('#agregar_usuarioModal').modal('hide');
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