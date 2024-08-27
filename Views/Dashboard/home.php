<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Dashboard/css/home_style.php'; ?>

<div class="custom-container-fluid">

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-4">
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
            <div class="col-md-8">
                <div class="mb-3">
                    <div class="card p-3">
                        <div class="card-body d-flex align-items-center">
                            <div>
                                <img src="icono_megafono.png" alt="Icono" class="me-3">
                            </div>
                            <div>
                                <h6 class="card-title mb-0">Tu satisfacción es muy importante para nosotros...</h6>
                                <p class="card-text">Recientemente estuvimos generando actualizaciones para mejorar tu
                                    experiencia en la Plataforma. ¡Dropi, tu lugar seguro!</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex flex-row">
                    <div class="mb-3">
                        <div class="card module-card">
                            <img src="<?php echo SERVERURL; ?>/public/noticias/imagen_youtube.jpeg" class="card-img-top" alt="Módulo de Garantías">
                            <div class="card-body">
                                <h5 class="card-title">Conoce nuestro módulo Mis Garantías</h5>
                                <p class="card-text">Gestiona las garantías de tus productos a través del Módulo de
                                    Garantías. Descubre cómo utilizarlo en nuestro tutorial.</p>
                                <a href="#" class="btn btn-warning">Ver Ahora</a>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex flex-column gap-2">
                        <div class="card">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <span>Dropshipper</span>
                                <a href="#" class="btn schedule-btn">Agendarme</a>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <span>Proveedor</span>
                                <a href="#" class="btn schedule-btn">Agendarme</a>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <span>Emprendedor</span>
                                <a href="#" class="btn schedule-btn">Agendarme</a>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <span>Logística</span>
                                <a href="#" class="btn schedule-btn">Agendarme</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- <script src="<?php echo SERVERURL ?>/Views/Dashboard/js/home.js"></script> -->

<?php require_once './Views/templates/footer.php'; ?>