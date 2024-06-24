<style>
    body {
        background-color: <?php echo COLOR_FONDO; ?>;
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
        background-color: <?php echo COLOR_BOTON_LOGIN;?>;
        border: none;
    }

    .btn-primary:hover {
        background-color: <?php echo COLOR_HOVER_LOGIN;?>;
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