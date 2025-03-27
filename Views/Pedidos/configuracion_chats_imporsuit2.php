<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Pedidos/css/configuracion_chats_imporsuit_style.php'; ?>
<?php require_once './Views/Pedidos/Modales/agregar_configuracion_automatizador.php'; ?>
<?php require_once './Views/Pedidos/Modales/modal_conectar_whatsapp.php'; ?>
<?php require_once './Views/Pedidos/Modales/agregar_automatizador.php'; ?>

<div class="custom-container-fluid">
    <div class="container mt-5" style="max-width: 1600px;">
        <h2 class="text-center mb-4">Lista de Configuraciones</h2>

        <button
            class="btn btn-success"
            data-bs-toggle="modal"
            data-bs-target="#agregar_configuracion_automatizadorModal"
            style="display:none;"
            id="boton_agregar_configuracion">
            <i class="fas fa-plus"></i> Agregar
        </button>

        <!-- Botón para el flujo de Embedded Signup -->
        <button
            class="btn btn-primary mb-3"
            id="btnConectarWhatsApp">
            <i class="fab fa-whatsapp"></i> Conectar WhatsApp
        </button>

        <div class="table-responsive">
            <table id="datatable_configuracion_automatizador" class="table table-striped">
                <thead>
                    <tr>
                        <th class="centered">ID</th>
                        <th class="centered">Nombre configuración</th>
                        <th class="centered">Teléfono</th>
                        <th class="centered">webhook_url</th>
                        <th class="centered">Acciones</th>
                    </tr>
                </thead>
                <tbody id="tableBody_configuracion_automatizador"></tbody>
            </table>
        </div>
    </div>
</div>

<!-- Cargar solo el SDK oficial -->
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js"></script>

<script>
    window.fbAsyncInit = function() {
        FB.init({
            appId: '1211546113231811',
            autoLogAppEvents: true,
            xfbml: true,
            version: 'v22.0'
        });

        // Esperar hasta que EmbeddedSignup esté disponible
        const waitForEmbed = setInterval(() => {
            if (typeof FB.EmbeddedSignup !== 'undefined') {
                clearInterval(waitForEmbed);

                document.getElementById('btnConectarWhatsApp').addEventListener('click', () => {
                    FB.EmbeddedSignup.start({
                        config_id: '2295613834169297',
                        onEvent: (event) => {
                            console.log("Evento recibido:", event);
                            if (event.type === 'WA_EMBEDDED_SIGNUP' && event.payload) {
                                const {
                                    waba_id,
                                    phone_number_id,
                                    long_lived_token
                                } = event.payload;

                                fetch("<?php echo SERVERURL; ?>Pedidos/onboarding?waba_id=" + waba_id +
                                        "&phone_number_id=" + phone_number_id +
                                        "&access_token=" + long_lived_token)
                                    .then(resp => resp.text())
                                    .then(serverResponse => {
                                        console.log("Respuesta del backend onboarding:", serverResponse);
                                    })
                                    .catch(error => console.error("Error en onboarding:", error));
                            }
                        }
                    });
                });
            }
        }, 300);
    };
</script>

<script src="<?php echo SERVERURL; ?>/Views/Pedidos/js/configuracion_chats_imporsuit2.js"></script>
<?php require_once './Views/templates/footer.php'; ?>