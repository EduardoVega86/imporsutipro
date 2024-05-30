<?php require_once './Views/templates/landing/header.php'; ?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IMORSUIT Registration</title>
    <style>
        body {
            background-image: url('https://tiendas.imporsuitpro.com/imgs/login.png');
            background-size: cover;
            background-position: center;
            color: #fff;
            font-family: Arial, sans-serif;
        }

        .container {
            align-self: center;
            max-width: 600px;
            margin: 50px;
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

        .header-notice {
            background-color: #000;
            color: #fff;
            text-align: center;
            padding: 10px 0;
            font-size: 18px;
            margin-bottom: 20px;
        }

        .step {
            display: none;
            animation: fadeIn 0.5s forwards;
        }

        .step-active {
            display: block;
        }

        @media (max-width: 768px) {
            .menu_derecha {
                display: flex !important;
            }

            .menu_izquierda {
                display: none !important;
            }
        }
        .forgot-password {
            display: flex;
            align-items: center;
            color: #666;
            text-decoration: none;
        }
        .forgot-password i {
            margin-right: 5px;
        }
        .forgot-password:hover {
            color: #333;
        }
    </style>
</head>
<div class="d-flex flex-column" style="padding: 20px;">
    <div class="imagen_logo">
        <img src="https://tiendas.imporsuitpro.com/imgs/logo.png" alt="IMORSUIT" width="300px" height="100px">
    </div>
    <div class="container">
        <div class="header">
            <h1>Login</h1>
        </div>
        <form id="multiStepForm">
            <div class="form-group">
                <label for="usuario">Usuario</label>
                <input type="text" class="form-control" id="usuario" placeholder="usuario">
            </div>
            <div class="form-group">
                <label for="contrasena">Contraseña</label>
                <input type="contrasena" class="form-control" id="contrasena" placeholder="contrasena">
            </div>
            <button type="button" class="btn btn-primary w-100">Iniciar Sesión</button>
            <a href="#" class="forgot-password" style="justify-content: center;">
                <i class="fas fa-lock"></i> ¿Olvidaste tu contraseña?
            </a>
        </form>
    </div>
</div>


</body>

</html>

<?php require_once './Views/templates/landing/footer.php'; ?>