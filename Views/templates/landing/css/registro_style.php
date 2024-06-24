<style>
    body {
        background-color: <?php echo COLOR_FONDO; ?>;
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
        background-color: <?php echo COLOR_BOTON_LOGIN;?>;
        border: none;
    }

    .btn-primary:hover {
        background-color: <?php echo COLOR_HOVER_LOGIN;?>;
    }

    .imagen_logo {
        text-align: center;
    }

    .header-notice {
        background-color: <?php echo COLOR_BOTON_LOGIN;?>;
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

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }
</style>