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
                                <a href="https://www.youtube.com/watch?v=t0hK4MM1Cp8" target="_blank" class="btn btn-warning">
                                    <img src="<?php echo SERVERURL; ?>/public/noticias/slider1.jpeg" class="d-block w-100" alt="...">
                                </a>
                            </div>
                            <div class="carousel-item">
                                <a href="https://wa.link/ykg131" target="_blank" class="btn btn-warning">
                                    <img src="<?php echo SERVERURL; ?>/public/noticias/slider2.jpeg" class="d-block w-100" alt="...">
                                </a>
                            </div>
                            <div class="carousel-item">
                                <a href="https://www.youtube.com/watch?v=VEar2a4T_cU" target="_blank" class="btn btn-warning">
                                    <img src="<?php echo SERVERURL; ?>/public/noticias/slider3.jpeg" class="d-block w-100" alt="...">
                                </a>
                            </div>
                            <div class="carousel-item">
                                <a href="https://new.imporsuitpro.com/Productos/marketplace" target="_blank" class="btn btn-warning">
                                    <img src="<?php echo SERVERURL; ?>/public/noticias/slider4.jpeg" class="d-block w-100" alt="...">
                                </a>
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
                        <div class="card-body align-items-center megafono">
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
                            <a href="https://danielbonilla522-9.funnels.mastertools.com/#primeros-pasos" target="_blank" class="btn btn-warning">Ver Ahora</a>
                        </div>
                    </div>
                </div>
                <div class="d-flex flex-column gap-2 mb-3">
                    <div class="card">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div class="align-items-center seccion_agendar">
                                <img src="<?php echo SERVERURL; ?>/public/noticias/FULFILLMENT.png" alt="FULFILLMENT" class="me-2 icono_agendar">
                                <span>FULFILLMENT</span>
                            </div>
                            <a href="https://wa.link/ykg131" target="_blank" class="btn schedule-btn">Agendarme</a>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div class="align-items-center seccion_agendar">
                                <img src="<?php echo SERVERURL; ?>/public/noticias/como_vender.png" alt="como_vender" class="me-2 icono_agendar">
                                <span>COMO EMPEZAR A VENDER</span>
                            </div>
                            <a href="https://youtu.be/Ycv7pgXflgk?si=pcQObKeA4lrkbOQV" target="_blank" class="btn schedule-btn">Agendarme</a>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div class="align-items-center seccion_agendar">
                                <img src="<?php echo SERVERURL; ?>/public/noticias/ser_proveedor.png" alt="ser_proveedor" class="me-2 icono_agendar">
                                <span>QUIERO SER PROVEEDOR</span>
                            </div>
                            <a href="https://wa.link/xumf9z" target="_blank" class="btn schedule-btn">Agendarme</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script src="<?php echo SERVERURL ?>/Views/Dashboard/js/home.js"></script>

<?php require_once './Views/templates/footer.php'; ?>