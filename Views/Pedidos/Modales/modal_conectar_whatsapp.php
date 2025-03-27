<!-- Modal para conectar a WhatsApp -->
<div class="modal fade" id="modalConectarWhatsApp" tabindex="-1" aria-labelledby="modalConectarWhatsAppLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="modalConectarWhatsAppLabel">Conectar número de WhatsApp</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body text-center">
                <p>
                    Para iniciar el proceso de configuración de tu número de WhatsApp,
                    da clic en el siguiente botón. Se abrirá la ventana de Onboarding
                    de Facebook en otra pestaña.
                </p>
                <button class="btn btn-primary" onclick="abrirOnboardingWhatsApp()">
                    Abrir Onboarding de WhatsApp
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // por ejemplo: https://desarrollo.imporsuitpro.com/
    function abrirOnboardingWhatsApp() {
        // Ruta que Facebook usará para retornar waba_id, phone_number_id y access_token
        var redirectUrl = '<?php echo SERVERURL; ?>pedidos/onboarding?m=onboarding';

        // Arma la URL del Onboarding de WhatsApp con los parámetros adecuados
        var url = 'https://www.facebook.com/business/wa/onboarding' +
            '?app_id=1211546113231811' +
            '&redirect_uri=' + encodeURIComponent(redirectUrl);

        // Abre la ventana de Onboarding en una nueva pestaña
        window.open(url, '_blank');
    }
</script>