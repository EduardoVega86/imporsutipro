<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Pedidos/css/inicio_automatizador_style.php'; ?>

<div class="custom-container-fluid">
    <div class="container mt-5" style="max-width: 1600px;">
        <h2 class="text-center mb-4">Automatizador</h2>

        <!-- Video de YouTube -->
        <div class="text-center">
            <iframe width="560" height="315"
                src="https://www.youtube.com/embed/TU_LINK_DE_VIDEO"
                title="YouTube video player"
                frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen>
            </iframe>
        </div>

        <!-- Botón para agendar citas -->
        <div class="text-center mt-4">
            <a href="https://api.leadconnectorhq.com/widget/booking/IDNQKni1wvk9WuJseH9R"
                class="btn btn-primary btn-lg"
                target="_blank">
                Agendar Citas
            </a>
        </div>

        <!-- Lista con diseño -->
        <div class="mt-5">
            <ul class="list-group text-center" style="font-size: 1.2rem; font-weight: bold; list-style: none; padding: 0;">
                <li class="list-group-item" style="border: none; padding: 10px 0;">
                    <span style="color: #5A2D82; font-size: 1.5rem;">&#9733;</span> Número Nuevo
                </li>
                <li class="list-group-item" style="border: none; padding: 10px 0;">
                    <span style="color: #5A2D82; font-size: 1.5rem;">&#9733;</span> Bussines Manager
                </li>
                <li class="list-group-item" style="border: none; padding: 10px 0;">
                    <span style="color: #5A2D82; font-size: 1.5rem;">&#9733;</span> Método de Pago
                </li>
            </ul>
        </div>
    </div>
</div>

<script src="<?php echo SERVERURL ?>/Views/Pedidos/js/inicio_automatizador.js"></script>
<?php require_once './Views/templates/footer.php'; ?>