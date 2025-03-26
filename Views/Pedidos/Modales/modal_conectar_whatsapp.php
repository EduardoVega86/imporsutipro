<div class="modal fade" id="modalConectarWhatsApp" tabindex="-1" aria-labelledby="modalConectarWhatsAppLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="modalConectarWhatsAppLabel">Conectar número de WhatsApp</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <iframe
                    src="https://www.facebook.com/business/wa/onboarding?app_id=1790001771377467&redirect_uri=<?php echo SERVERURL; ?>controladores/WhatsappController.php?m=onboarding"
                    width="100%"
                    height="700px"
                    style="border:none; overflow:hidden;"
                    allowfullscreen>
                </iframe>
            </div>
        </div>
    </div>
</div>