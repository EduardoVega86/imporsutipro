<?php require_once './Views/templates/landing/header.php'; ?>
<?php require_once './Views/templates/landing/css/login_style.php'; ?>

<div class="d-flex flex-column" style="width: 700px;">
    <div class="imagen_logo">
        <img src="https://tiendas.imporsuitpro.com/imgs/logo.png" alt="IMORSUIT" width="300px" height="100px">
    </div>
    <div class="container">
        <div class="header">
            <h1>Login</h1>
        </div>
        <form id="login">
            <div class="form-group">
                <label for="correo">Correo</label>
                <input type="text" class="form-control" id="correo" name="correo" placeholder="Correo">
            </div>
            <div class="form-group password-toggle">
                <label for="contrasena">Contraseña</label>
                <input type="password" class="form-control" id="contrasena" name="contrasena" placeholder="Contraseña">
                <span class="password-toggle-icon" id="togglePassword" onclick="togglePasswordVisibility()">
                🙈
                </span>
            </div>
            <button type="submit" class="btn btn-primary w-100">Iniciar Sesión</button>
            <a href="<?php echo SERVERURL ?>Home/recovery" class="forgot-password">
                <i class="fas fa-lock"></i> ¿Olvidaste tu contraseña?
            </a>

            <div class="center-text">o</div>

            <a href="<?php echo SERVERURL ?>registro" class="animated-link">
                Regístrate ahora
            </a>
        </form>
    </div>
</div>

<script>
    document.getElementById("login").addEventListener("submit", function(event) {
        event.preventDefault();

        const formData = new FormData(this);
        const data = {};
        formData.forEach((value, key) => {
            data[key] = value;
        });

        const url = '<?php echo SERVERURL; ?>Acceso/login'; // Asegúrate de definir SERVERURL en tu backend PHP

        fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                console.log('Success:', data);
                // Mostrar alerta de éxito
                if (data.status == 401) {
                    Swal.fire({
                        icon: 'error',
                        title: data.title,
                        text: data.message
                    });
                } else {
                    Swal.fire({
                        icon: 'success',
                        title: data.title,
                        text: data.message,
                        showConfirmButton: false,
                        timer: 2000
                    }).then(() => {
                        window.location.href = '<?php echo SERVERURL ?>dashboard';
                    });
                }
            })
            .catch((error) => {
                console.error('Error:', error);
                // Mostrar alerta de error
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Hubo un problema con el inicio de sesión.',
                    showConfirmButton: false,
                    timer: 2000
                });
            });
    });

    //funcion ara dejar ver o no contraseña
    function togglePasswordVisibility() {
        const passwordField = document.getElementById('contrasena');
        const toggleIcon = document.getElementById('togglePassword').firstElementChild;
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            toggleIcon.textContent = '🙈'; // Ícono con la raya
        } else {
            passwordField.type = 'password';
            passwordField.type = 'password';
                toggleIcon.textContent = '👁️'; // Ícono sin la raya
        }
    }
</script>
<?php require_once './Views/templates/landing/footer.php'; ?>