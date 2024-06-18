<?php require_once './Views/templates/landing/header.php'; ?>
<?php require_once './Views/templates/landing/css/recovery_style.php'; ?>

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