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
            id="btnConectarWhatsApp"
            onclick="launchWhatsAppSignup()">
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

<!-- Carga del SDK de Facebook (necesario para Embedded Signup) -->
<script
    async
    defer
    crossorigin="anonymous"
    src="https://connect.facebook.net/en_US/sdk.js">
</script>

<script>
    // 1. Inicializar el SDK de Facebook
    window.fbAsyncInit = function() {
        FB.init({
            appId: '1211546113231811', // Reemplazar con tu App ID real de Facebook
            autoLogAppEvents: true,
            xfbml: true,
            version: 'v22.0' // O la versión de Graph API que uses
        });
    };

    // 2. Escuchar mensajes desde el iFrame (Embedded Signup usa un iFrame interno)
    window.addEventListener('message', (event) => {
        console.log("MENSAJE RECIBIDO DE FB:", event);
        // Solo atendemos mensajes que provienen de Facebook
        if (
            event.origin !== "https://www.facebook.com" &&
            event.origin !== "https://web.facebook.com"
        ) return;

        try {
            console.log("Contenido bruto del mensaje:", event.data); // <--- Y ESTO
            const data = JSON.parse(event.data);
            console.log("Mensaje parseado:", data);
            // Verifica si es el evento del “WhatsApp Embedded Signup”
            if (data.type === 'WA_EMBEDDED_SIGNUP') {
                console.log('Mensaje Embedded Signup:', data);

                // En algunos casos, data puede traer: data.payload.waba_id, data.payload.phone_number_id, data.payload.long_lived_token, etc.
                // Valida y envía al backend para guardar
                if (data.payload) {
                    const wabaId = data.payload.waba_id;
                    const phoneNumberId = data.payload.phone_number_id;
                    const accessToken = data.payload.long_lived_token;
                    // 🔍 LOGEA LOS DATOS ANTES DE LLAMAR AL BACK
                    console.log({
                        wabaId,
                        phoneNumberId,
                        accessToken
                    });

                    // Hacemos un fetch al método "onboarding" que tienes en tu Controller
                    // para almacenar la info (waba_id, phone_number_id, access_token):
                    fetch("<?php echo SERVERURL; ?>Pedidos/onboarding?waba_id=" + wabaId +
                            "&phone_number_id=" + phoneNumberId +
                            "&access_token=" + accessToken)
                        .then(resp => resp.text())
                        .then(serverResponse => {
                            console.log("Respuesta del backend onboarding:", serverResponse);
                            // Podrías recargar la página o redirigir a otra ruta si quieres
                            // location.reload();
                        })
                        .catch(error => console.error("Error en fetch onboarding:", error));
                }
            }
        } catch (err) {
            console.warn('Mensaje no-JSON o error parseando:', event.data);
        }
    });

    // 3. (Opcional) Callback de FB.login (si devuelven un “code”)
    //    Puedes usarlo o no, según lo requiera tu flujo.
    const fbLoginCallback = (response) => {
        if (response.authResponse) {
            console.log('fbLoginCallback con authResponse:', response.authResponse);
            // Por si Meta retornara un code, lo capturas:
            // let code = response.authResponse.code;
            // ...
        } else {
            console.log('fbLoginCallback sin authResponse:', response);
        }
    };

    // 4. Función que lanza el flujo de WhatsApp Embedded Signup
    function launchWhatsAppSignup() {
        FB.EmbeddedSignup.start({
            // Reemplaza con tu config ID real
            config_id: '2295613834169297',
            onEvent: (event) => {
                console.log('Evento recibido de EmbeddedSignup:', event);

                if (event.type === 'WA_EMBEDDED_SIGNUP') {
                    const {
                        waba_id,
                        phone_number_id,
                        long_lived_token
                    } = event.payload;

                    console.log("Datos recibidos:", {
                        waba_id,
                        phone_number_id,
                        long_lived_token
                    });

                    // Ahora sí llamamos a tu backend
                    fetch("<?php echo SERVERURL; ?>Pedidos/onboarding?waba_id=" + waba_id +
                            "&phone_number_id=" + phone_number_id +
                            "&access_token=" + long_lived_token)
                        .then(resp => resp.text())
                        .then(serverResponse => {
                            console.log("Respuesta del backend onboarding:", serverResponse);
                        })
                        .catch(error => console.error("Error en fetch onboarding:", error));
                }
            },
        });
    }
</script>

<!-- Tu archivo JS donde tienes el DataTable, etc. -->
<script src="<?php echo SERVERURL; ?>/Views/Pedidos/js/configuracion_chats_imporsuit2.js"></script>

<?php require_once './Views/templates/footer.php'; ?>