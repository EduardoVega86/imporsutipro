<style>
    body {
        background-color: <?php echo COLOR_FONDO; ?>;
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