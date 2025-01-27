<?php require_once './Views/templates/landing/header.php'; ?>
<?php require_once './Views/Acceso/css/recoveryAcceso_style.php'; ?>

<div class="d-flex flex-column" style="width: 700px;">
    <div class="imagen_logo">
        <img src="<?php echo LOGIN_IMAGE; ?>" alt="IMORSUIT" width="300px" height="150px">
    </div>
    <div class="container">
        <div class="header">
            <h1><?php echo MARCA; ?></h1>
        </div>
        <div class="hidden" id="cambiar_contrasena">
            <div class="form-group">
                <label for="contrasena">Contraseña</label>
                <input type="password" class="form-control" id="contrasena" name="contrasena" placeholder="Contraseña">
            </div>
            <div class="form-group">
                <label for="repetir_contrasena">Repetir Contraseña</label>
                <input type="password" class="form-control" id="repetir_contrasena" name="repetir_contrasena" placeholder="Repetir Contraseña">
            </div>
            <div id="password-error" style="color: red; display: none;">Las contraseñas no coinciden.</div>
            <button type="button" class="btn btn-primary w-100" id="btnCambiar_contrasena"><i class="fa-solid fa-key"></i> Cambiar contraseña</button>
        </div>
        <div id="token_valido" class="hidden" style="text-align-last: center;">
            <div class="d-flex flex-column">
                <i class="fa-solid fa-face-frown" style="font-size: 60px;"></i>
                <h1>TOKEN NO VALIDO</h1>
            </div>
            <a href="<?php echo SERVERURL ?>login" class="forgot-password">
                <i class="fa-solid fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        // Obtener la URL actual de la página
        var url_1 = window.location.href;

        // Extraer el token de la URL
        var token = url_1.substring(url_1.lastIndexOf('/') + 1);

        let formData = new FormData();
        formData.append("token", token);

        $.ajax({
            url: '<?php echo SERVERURL; ?>acceso/validarToken',
            type: 'POST',
            data: formData,
            processData: false, // No procesar los datos
            contentType: false, // No establecer ningún tipo de contenido
            dataType: "json",
            success: function(response) {
                if (response === 'true') {
                    $('#cambiar_contrasena').removeClass('hidden');
                    $('#token_valido').addClass('hidden');
                } else {
                    $('#cambiar_contrasena').addClass('hidden');
                    $('#token_valido').removeClass('hidden');
                }
            },
            error: function(error) {
                // Maneja el error aquí
                alert('Hubo un error al validar el token');
            }
        });

        // Validar las contraseñas en tiempo real
        $('#contrasena, #repetir_contrasena').on('input', function() {
            var contrasena = $('#contrasena').val();
            var repetirContrasena = $('#repetir_contrasena').val();

            if (contrasena !== repetirContrasena) {
                $('#password-error').show();
            } else {
                $('#password-error').hide();
            }
        });

        $('#btnCambiar_contrasena').click(function() {
            var contrasena = $('#contrasena').val();
            var repetirContrasena = $('#repetir_contrasena').val();

            if (contrasena !== repetirContrasena) {
                Swal.fire({
                    icon: 'error',
                    title: "Error",
                    text: "Las contraseñas no coinciden"
                });
                return;
            }

            let formData = new FormData();
            formData.append("contrasena", contrasena);
            formData.append("token", token);

            $.ajax({
                url: '<?php echo SERVERURL; ?>acceso/cambiarContrasena',
                type: 'POST',
                data: formData,
                processData: false, // No procesar los datos
                contentType: false, // No establecer ningún tipo de contenido
                success: function(response) {
                    response = JSON.parse(response);
                    if (response.status == 500) {
                        Swal.fire({
                            icon: 'error',
                            title: response.title,
                            text: response.message
                        });
                    } else if (response.status == 200) {

                        Swal.fire({
                            icon: 'success',
                            title: response.title,
                            text: response.message,
                            showConfirmButton: false,
                            timer: 2000
                        }).then(() => {
                            window.location.href = '' + SERVERURL + 'login';
                        });
                    }
                },
                error: function(error) {
                    // Maneja el error aquí
                    alert('Hubo un error al cambiar la contraseña');
                }
            });
        });
    });
</script>

<?php require_once './Views/templates/landing/footer.php'; ?>