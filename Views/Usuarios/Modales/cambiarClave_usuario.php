<style>
    .modal-content {
        border-radius: 15px;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }

    .modal-header {
        background-color: <?php echo COLOR_FONDO; ?>;
        color: <?php echo COLOR_LETRAS; ?>;
        border-top-left-radius: 15px;
        border-top-right-radius: 15px;
    }

    .modal-header .btn-close {
        color: white;
    }

    .modal-body {
        padding: 20px;
    }

    .modal-footer {
        border-top: none;
        padding: 10px 20px;
    }

    .modal-footer .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
    }

    .modal-footer .btn-primary {
        background-color: #ffc107;
        border-color: #ffc107;
        color: white;
    }
</style>

<div class="modal fade" id="cambiarClave_usuarioModal" tabindex="-1" aria-labelledby="cambiarClave_usuarioModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cambiarClave_usuarioModalLabel"><i class="fas fa-edit"></i> Cambiar Contraseña</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="cambiarClave_usuario">
                    <input type="hidden" id="id_usuarioCambiar" name="id_usuarioCambiar">
                    <div class="form-group">
                        <label for="contrasena">Contraseña</label>
                        <input type="password" class="form-control" id="contrasena" name="contrasena" placeholder="Contraseña">
                    </div>
                    <div class="form-group">
                        <label for="repetir-contrasena">Repetir Contraseña</label>
                        <input type="password" class="form-control" id="repetir-contrasena" name="repetir-contrasena" placeholder="Repetir Contraseña">
                    </div>
                    <div id="password-error" style="color: red; display: none;">Las contraseñas no coinciden.</div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary" form="cambiarClave_usuario">Cambiar</button>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const passwordInput = document.getElementById("contrasena");
        const confirmPasswordInput = document.getElementById("repetir-contrasena");
        const passwordError = document.getElementById("password-error");

        function validatePasswords() {
            if (passwordInput.value !== confirmPasswordInput.value) {
                passwordError.style.display = "block";
            } else {
                passwordError.style.display = "none";
            }
        }

        passwordInput.addEventListener("input", validatePasswords);
        confirmPasswordInput.addEventListener("input", validatePasswords);
    });

    $("#cambiarClave_usuario").on("submit", function(event) {
        event.preventDefault(); // Evita el envío normal del formulario

        let password = $("#contrasena").val();
        let confirmPassword = $("#repetir-contrasena").val();
        let id_usuario = $("#id_usuarioCambiar").val();

        if (password !== confirmPassword) {
            $("#password-error").show();
            return;
        }

        let formData = new FormData();
        formData.append("contrasena", password);
        formData.append("id_usuario", id_usuario);

        $.ajax({
            url: SERVERURL + 'Usuarios/resetearContrasena', // Reemplaza esto con la URL de tu API
            method: 'POST',
            data: formData,
            processData: false, // No procesar los datos
            contentType: false, // No establecer ningún tipo de contenido
            success: function(response) {
                response = JSON.parse(response);
                if (response.status == 500) {
                    toastr.error(
                        "ERROR AL CAMBIAR CLAVE",
                        "NOTIFICACIÓN", {
                            positionClass: "toast-bottom-center"
                        }
                    );
                } else if (response.status == 200) {
                    toastr.success("CLAVE CAMBIADA CORRECTAMENTE", "NOTIFICACIÓN", {
                        positionClass: "toast-bottom-center",
                    });

                    $('#cambiarClave_usuarioModal').modal('hide');
                    initDataTableListaUsuarioMatriz();
                }
            },
            error: function(xhr, status, error) {
                console.log("Error:", error);
            }
        });
    });
</script>