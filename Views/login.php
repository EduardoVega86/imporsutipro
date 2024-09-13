<?php require_once './Views/templates/landing/header.php'; ?>
<?php require_once './Views/templates/landing/css/login_style.php'; ?>

<div class="d-flex flex-column" style="width: 700px;">
    <div class="imagen_logo">
        <img src="<?php echo LOGIN_IMAGE; ?>" alt="IMORSUIT" width="300px" height="150px">
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
                    <i class="fa-solid fa-eye"></i>
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
                // Manejo de la respuesta del fetch de login
                console.log('Success:', data);
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
                        window.location.href = '<?php echo SERVERURL; ?>' + data.ultimo_punto.url;
                    });
                }
            })
            .catch((error) => {
                console.error('Error:', error);
                // Mostrar alerta de error en caso de fallo en el fetch
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
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        } else {
            passwordField.type = 'password';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        }
    }
</script>
<?php require_once './Views/templates/landing/footer.php'; ?>