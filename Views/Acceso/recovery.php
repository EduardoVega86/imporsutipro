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
                <label for="repetir-contrasena">Repetir Contraseña</label>
                <input type="password" class="form-control" id="repetir-contrasena" name="repetir-contrasena" placeholder="Repetir Contraseña">
            </div>
            <button type="button" class="btn btn-primary w-100" id="sendEmailButton"><i class="fa-solid fa-key"></i> Cambiar contraseña</button>
        </div>
        <div id="token_valido">
            <div class="d-flex flex-column">
                <i class='bx bx-sad' style="font-size: 60px;"></i>
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
            url: SERVERURL + 'acceso/validarToken',
            type: 'POST',
            data: formData,
            processData: false, // No procesar los datos
            contentType: false, // No establecer ningún tipo de contenido
            success: function(response) {
                // Maneja la respuesta de la API aquí
                alert('Correo enviado exitosamente');
            },
            error: function(error) {
                // Maneja el error aquí
                alert('Hubo un error al enviar el correo');
            }
        });
    });
</script>

<?php require_once './Views/templates/landing/footer.php'; ?>