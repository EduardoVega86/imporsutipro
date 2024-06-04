<?php require_once './Views/templates/landing/header.php'; ?>
<style>
    body {
        background-color: #171931;
        color: #fff;
        background-size: cover;
        background-position: center;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        margin: 0;
        font-family: Arial, sans-serif;
    }

    .container {
        align-self: center;
        max-width: 600px;
        margin: 20px;
        background-color: #fff;
        color: #000;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .header {
        text-align: center;
        margin-bottom: 30px;
    }

    .header img {
        max-width: 150px;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-control {
        height: 45px;
        font-size: 16px;
    }

    .btn-primary {
        background-color: #11143b;
        border: none;
    }

    .btn-primary:hover {
        background-color: #0a0b29;
    }

    .imagen_logo {
        text-align: center;
    }

    .forgot-password {
        display: flex;
        align-items: center;
        color: #666;
        text-decoration: none;
        justify-content: center;
        margin-top: 15px;
    }

    .forgot-password i {
        margin-right: 5px;
    }

    .forgot-password:hover {
        color: #333;
    }

    /* Estilo base del enlace */
    .animated-link {
        display: flex !important;
        align-items: center;
        justify-content: center;
        margin-top: 15px;
        font-size: 1rem;
        color: #007bff;
        text-decoration: none;
        transition: all 0.3s ease-in-out;
        display: inline-block;
        /* Para que la transformación funcione correctamente */
    }

    /* Estilo cuando el mouse está sobre el enlace */
    .animated-link:hover {
        font-size: 1.2rem;
        color: #0056b3;
        transform: scale(1.1);
        /* Aumenta ligeramente el tamaño */
    }

    /* Estilo para centrar el texto "o" */
    .center-text {
        text-align: center;
        margin: 5px 0;
        /* Añadir margen para separarlo de los enlaces */
    }
</style>

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
            <div class="form-group">
                <label for="contrasena">Contraseña</label>
                <input type="password" class="form-control" id="contrasena" name="contrasena" placeholder="Contraseña">
            </div>
            <button type="submit" class="btn btn-primary w-100">Iniciar Sesión</button>
            <a href="https://new.imporsuitpro.com/Home/recovery" class="forgot-password">
                <i class="fas fa-lock"></i> ¿Olvidaste tu contraseña?
            </a>
            
            <div class="center-text">o</div>
            
            <a href="https://new.imporsuitpro.com/registro" class="animated-link">
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
                        window.location.href = 'https://new.imporsuitpro.com/dashboard';
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
</script>
<?php require_once './Views/templates/landing/footer.php'; ?>