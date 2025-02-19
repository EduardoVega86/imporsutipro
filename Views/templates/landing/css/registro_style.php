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
        background-color: <?php echo COLOR_BOTON_LOGIN; ?>;
        border: none;
    }

    .btn-primary:hover {
        background-color: <?php echo COLOR_HOVER_LOGIN; ?>;
    }

    .imagen_logo {
        text-align: center;
    }

    .header-notice {
        background-color: <?php echo COLOR_BOTON_LOGIN; ?>;
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

    /* whatsapp flotante */
    .whatsapp-float {
        position: fixed;
        bottom: 20px;
        right: 20px;
        background-color: #25D366;
        color: white;
        border-radius: 50%;
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        z-index: 1000;
        cursor: pointer;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        text-decoration: none;
        /* Elimina la raya */
    }

    .whatsapp-float:hover {
        transform: scale(1.1);
        box-shadow: 0px 6px 12px rgba(0, 0, 0, 0.3);
    }

    .password-toggle {
        position: relative;
    }

    .password-toggle-icon {
        position: absolute;
        top: 65%;
        right: 10px;
        transform: translateY(-50%);
        cursor: pointer;
    }
</style>