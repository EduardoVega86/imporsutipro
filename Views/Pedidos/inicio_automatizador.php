<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Pedidos/css/inicio_automatizador_style.php'; ?>

<div class="custom-container-fluid">
    <div class="container mt-5" style="max-width: 1600px;">
        <!-- Encabezado principal -->
        <div class="text-center mb-5">
            <h2 style="color: #5A2D82; font-size: 3rem; font-weight: bold; text-shadow: 2px 2px 5px rgba(0,0,0,0.2);">
                🚀 Automatizador
            </h2>
            <p style="font-size: 1.2rem; color: #555; max-width: 800px; margin: 0 auto;">
                Simplifica tus tareas y lleva tu negocio al siguiente nivel. Sigue las instrucciones para empezar.
            </p>
        </div>

        <!-- Contenedor dividido en dos columnas -->
        <div class="row align-items-center justify-content-between">
            <!-- Columna izquierda: Video -->
            <div class="col-md-6" style="padding-top: 3%;">
                <div style="border: 5px solid #5A2D82; border-radius: 15px; overflow: hidden; box-shadow: 0px 4px 8px rgba(0,0,0,0.1);">
                    <iframe width="100%" height="400"
                        src="https://www.youtube.com/embed/l8hHWPsDHVw?si=t-9KBRjFb4YFC0aR"
                        title="YouTube video player"
                        frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen>
                    </iframe>
                </div>
            </div>

            <!-- Separador vertical -->
            <div class="col-md-auto d-none d-md-block">
                <div style="border-left: 2px solid #ddd; height: 450px; margin: auto;"></div>
            </div>

            <!-- Columna derecha: Calendario -->
            <div class="col-md-5">
                <div class="text-center">
                    <h2 style="color: #5A2D82; font-size: 2rem; font-weight: bold;">Agendar Cita</h2>
                    <iframe
                        src="https://api.leadconnectorhq.com/widget/booking/IDNQKni1wvk9WuJseH9R"
                        width="100%"
                        height="400"
                        style="border: none; box-shadow: 0px 4px 8px rgba(0,0,0,0.1); border-radius: 10px;">
                    </iframe>
                </div>
            </div>
        </div>


        <!-- Tarjetas informativas -->
        <div class="row mt-5 justify-content-center">
            <div class="col-md-4 mb-4">
                <div style="background-color: #F8F9FA; border: 1px solid #E0E0E0; border-radius: 10px; padding: 20px; text-align: center; box-shadow: 0px 4px 8px rgba(0,0,0,0.1);">
                    <span style="font-size: 3rem; color: #5A2D82;">&#9733;</span>
                    <h4 style="color: #333; margin-top: 10px;">Número Nuevo</h4>
                    <p style="color: #666; font-size: 1rem;">Ten a la mano el número que utilizarás para el proceso.</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div style="background-color: #F8F9FA; border: 1px solid #E0E0E0; border-radius: 10px; padding: 20px; text-align: center; box-shadow: 0px 4px 8px rgba(0,0,0,0.1);">
                    <span style="font-size: 3rem; color: #5A2D82;">&#9733;</span>
                    <h4 style="color: #333; margin-top: 10px;">Business Manager</h4>
                    <p style="color: #666; font-size: 1rem;">Acceso a tu administrador de negocios configurado.</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div style="background-color: #F8F9FA; border: 1px solid #E0E0E0; border-radius: 10px; padding: 20px; text-align: center; box-shadow: 0px 4px 8px rgba(0,0,0,0.1);">
                    <span style="font-size: 3rem; color: #5A2D82;">&#9733;</span>
                    <h4 style="color: #333; margin-top: 10px;">Método de Pago</h4>
                    <p style="color: #666; font-size: 1rem;">Verifica que tu método de pago esté configurado correctamente.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo SERVERURL ?>/Views/Pedidos/js/inicio_automatizador.js"></script>
<?php require_once './Views/templates/footer.php'; ?>