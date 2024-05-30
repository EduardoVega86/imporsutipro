<?php require_once './Views/templates/landing/header.php'; ?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IMORSUIT Registration</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background-image: url('https://tiendas.imporsuitpro.com/imgs/login.png');
            background-size: cover;
            background-position: center;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            color: #fff;
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
    </style>
</head>

<body>
    <div class="container">
        <div class="imagen_logo">
            <img src="https://tiendas.imporsuitpro.com/imgs/logo.png" alt="IMORSUIT" width="300px" height="100px">
        </div>
        <div class="header">
            <h1>Login</h1>
        </div>
        <form id="multiStepForm">
            <div class="form-group">
                <label for="usuario">Usuario</label>
                <input type="text" class="form-control" id="usuario" placeholder="Usuario">
            </div>
            <div class="form-group">
                <label for="contrasena">Contraseña</label>
                <input type="password" class="form-control" id="contrasena" placeholder="Contraseña">
            </div>
            <button type="button" class="btn btn-primary w-100">Iniciar Sesión</button>
            <a href="#" class="forgot-password">
                <i class="fas fa-lock"></i> ¿Olvidaste tu contraseña?
            </a>
        </form>
    </div>
</body>

</html>

<?php require_once './Views/templates/landing/footer.php'; ?>