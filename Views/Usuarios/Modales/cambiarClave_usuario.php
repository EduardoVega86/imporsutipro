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

        const password = $("#contrasena").val();
        const confirmPassword = $("#repetir-contrasena").val();

        if (password !== confirmPassword) {
            $("#password-error").show();
            return;
        }

        $.ajax({
            url: 'URL_DE_TU_API', // Reemplaza esto con la URL de tu API
            method: 'POST',
            data: {
                contrasena: password,
                // Puedes agregar más datos aquí si es necesario
            },
            success: function(response) {
                // Maneja la respuesta de éxito aquí
                console.log("Éxito:", response);
                // Puedes agregar código para mostrar un mensaje de éxito o cerrar el modal
            },
            error: function(xhr, status, error) {
                // Maneja el error aquí
                console.log("Error:", error);
                // Puedes agregar código para mostrar un mensaje de error al usuario
            }
        });
    });
</script>