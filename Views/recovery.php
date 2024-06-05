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
        height: 100vh;
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
</style>

<div class="d-flex flex-column" style="width: 700px;">
    <div class="imagen_logo">
        <img src="https://tiendas.imporsuitpro.com/imgs/logo.png" alt="IMORSUIT" width="300px" height="100px">
    </div>
    <div class="container">
        <div class="header">
            <h1>IMPORSUIT</h1>
        </div>
        <form id="multiStepForm">
            <div class="form-group">
                <label for="email">Restablecer contraseña</label>
                <input type="text" class="form-control" id="email" placeholder="Email">
            </div>
            <button type="button" class="btn btn-primary w-100"> <box-icon name='envelope' color='#ffff' type='solid'></box-icon> Enviar correo</button>
            <a href="<?php echo SERVERURL ?>login" class="forgot-password">
                <i class="fa-solid fa-arrow-left"></i> Volver
            </a>
        </form>
    </div>
</div>


<?php require_once './Views/templates/landing/footer.php'; ?>