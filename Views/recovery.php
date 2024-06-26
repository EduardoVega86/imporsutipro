<?php require_once './Views/templates/landing/header.php'; ?>
<?php require_once './Views/templates/landing/css/recovery_style.php'; ?>

<div class="d-flex flex-column" style="width: 700px;">
    <div class="imagen_logo">
        <img src="<?php echo LOGIN_IMAGE; ?>" alt="IMORSUIT" width="300px" height="150px">
    </div>
    <div class="container">
        <div class="header">
            <h1><?php echo MARCA; ?></h1>
        </div>
        <form id="multiStepForm">
            <div class="form-group">
                <label for="email">Restablecer contraseña</label>
                <input type="text" class="form-control" id="email" placeholder="Email">
            </div>
            <button type="button" class="btn btn-primary w-100" id="sendEmailButton"> <box-icon name='envelope' color='#ffff' type='solid'></box-icon> Enviar correo</button>
            <a href="<?php echo SERVERURL ?>login" class="forgot-password">
                <i class="fa-solid fa-arrow-left"></i> Volver
            </a>
        </form>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#sendEmailButton').click(function() {
            var email = $('#email').val();

            let formData = new FormData();
            formData.append("correo", email);
            $.ajax({
                url: SERVERURL + 'acceso/recuperar_contrasena',
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
    });
</script>

<?php require_once './Views/templates/landing/footer.php'; ?>