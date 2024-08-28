<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Dashboard/css/home_style.php'; ?>

<div class="custom-container-fluid">

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-4 mt-3">
                <div class="slider-container">
                    <div id="carouselExampleFade" class="carousel slide carousel-fade" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="<?php echo SERVERURL; ?>/public/noticias/slider1.png" class="d-block w-100" alt="...">
                            </div>
                            <div class="carousel-item">
                                <img src="<?php echo SERVERURL; ?>/public/noticias/slider2.png" class="d-block w-100" alt="...">
                            </div>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleFade"
                            data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleFade"
                            data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-md-8 mt-3">
                <div class="mb-3">
                    <div class="card p-3">
                        <div class="card-body d-flex align-items-center">
                            <div class="icon-circle d-flex align-items-center justify-content-center me-3">
                                <img src="<?php echo SERVERURL; ?>/public/noticias/icono_megafono.png" alt="Icono" class="img-fluid" style="margin: 10%;">
                            </div>
                            <div>
                                <h6 class="card-title mb-0">¡PRUEBALA AHORA!</h6>
                                <p class="card-text">Revisa ahora nuestra renovada plataforma con herramientas especializadas
                                    para Ecommerce y gestion de logisticas e inventario, ademas de nuestra nueva plantilla de distribución
                                    de conenido.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                    <div class="mb-3">
                        <div class="card module-card">
                            <img src="<?php echo SERVERURL; ?>/public/noticias/imagen_youtube.jpeg" class="card-img-top" alt="Módulo de Garantías">
                            <div class="card-body">
                                <a href="#" class="btn btn-warning">Ver Ahora</a>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex flex-column gap-2 mb-3">
                        <div class="card">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <img src="<?php echo SERVERURL; ?>/public/noticias/FULLFILLMENT.png" class="card-img-top" alt="FULLFILLMENT" style="width: 10%;">
                                <span>FULLFILLMENT</span>
                                <a href="#" class="btn schedule-btn">Agendarme</a>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <img src="<?php echo SERVERURL; ?>/public/noticias/como_vender.png" class="card-img-top" alt="como_vender" style="width: 10%;">
                                <span>COMO EMPEZAR A VENDER</span>
                                <a href="#" class="btn schedule-btn">Agendarme</a>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <img src="<?php echo SERVERURL; ?>/public/noticias/ser_proveedor.png" class="card-img-top" alt="ser_proveedor" style="width: 10%;">
                                <span>QUIERO SER PROVEEDOR</span>
                                <a href="#" class="btn schedule-btn">Agendarme</a>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>

</div>

<!-- <script src="<?php echo SERVERURL ?>/Views/Dashboard/js/home.js"></script> -->

<?php require_once './Views/templates/footer.php'; ?>