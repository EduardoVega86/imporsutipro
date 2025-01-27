<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Pedidos/css/inicio_automatizador_style.php'; ?>

<div class="custom-container-fluid">
    <div class="container mt-5" style="max-width: 1600px;">
        <h2 class="text-center mb-4" style="color: #5A2D82; font-size: 2.5rem; font-weight: bold;">Automatizador</h2>

        <!-- Video de YouTube -->
        <div class="text-center">
            <iframe width="560" height="315"
                src="https://www.youtube.com/embed/TU_LINK_DE_VIDEO"
                title="YouTube video player"
                frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen
                style="border: 3px solid #5A2D82; border-radius: 10px;">
            </iframe>
        </div>

        <!-- Botón para agendar citas -->
        <div class="text-center mt-4">
            <a href="https://youtu.be/Hl3tuoSVuuw?si=JnHydcfeypp6r4d-"
                class="btn btn-primary btn-lg"
                target="_blank"
                style="background-color: #5A2D82; border-color: #5A2D82; font-size: 1.5rem; font-weight: bold; padding: 10px 20px; border-radius: 10px;">
                Agendar Citas
            </a>
        </div>

        <!-- Lista con diseño -->
        <div class="mt-5">
            <ul class="list-group text-center" style="font-size: 1.2rem; font-weight: bold; list-style: none; padding: 0;">
                <label for="" style="font-size: 1.5rem; color: #333; font-weight: bold; margin-bottom: 10px; display: inline-block;">
                    Recuerda tener listos los siguientes datos antes de entrar en la reunión:
                </label>
                <li class="list-group-item" style="border: none; padding: 10px 0; background-color: #F9F9F9;">
                    <span style="color: #5A2D82; font-size: 1.5rem;">&#9733;</span> Número Nuevo
                </li>
                <li class="list-group-item" style="border: none; padding: 10px 0; background-color: #F9F9F9;">
                    <span style="color: #5A2D82; font-size: 1.5rem;">&#9733;</span> Bussines Manager
                </li>
                <li class="list-group-item" style="border: none; padding: 10px 0; background-color: #F9F9F9;">
                    <span style="color: #5A2D82; font-size: 1.5rem;">&#9733;</span> Método de Pago
                </li>
            </ul>
        </div>
    </div>
</div>

<script src="<?php echo SERVERURL ?>/Views/Pedidos/js/inicio_automatizador.js"></script>
<?php require_once './Views/templates/footer.php'; ?>