<style>
    .modal-content {
        border-radius: 15px;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }

    .modal-header {
        background-color: <?php echo COLOR_FONDO; ?>;
        color: <?php echo COLOR_LETRAS; ?>;
        border-top-left-radius: 15px;
        border-top-right-radius: 15px;
    }

    .modal-header .btn-close {
        color: white;
    }

    .modal-body {
        padding: 20px;
    }

    .modal-footer {
        border-top: none;
        padding: 10px 20px;
    }

    .modal-footer .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
    }

    .modal-footer .btn-primary {
        background-color: #ffc107;
        border-color: #ffc107;
        color: white;
    }

    .texto_modal {
        font-size: 20px;
        margin-bottom: 5px;
    }

    .descripcion_producto {
        display: flex;
        flex-direction: row;
    }

    .informacion_producto {
        width: 50%;
        /* Aproximadamente la mitad del contenedor, similar a col-6 */
        margin-bottom: 1rem;
        /* Espaciado en la parte inferior, similar a mb-4 */
    }

    @media (max-width: 768px) {
        .descripcion_producto {
            flex-direction: column-reverse;
        }

        .informacion_producto {
            width: 100%;
        }
    }
</style>
<div class="modal fade" id="subir_imagen_speedModal" tabindex="-1" aria-labelledby="subir_imagen_speedModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="subir_imagen_speedModalLabel"><i class="fas fa-edit"></i> Novedad</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <!-- Configuración General -->
                <div class="card mb-4">
                    <div class="card-header">
                        Configuración General
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="nombreConfiguracion" class="form-label">Nombre Configuración</label>
                            <input type="text" class="form-control" id="nombreConfiguracion" placeholder="Ingrese el nombre de la configuración">
                        </div>
                    </div>
                </div>

                <!-- Configuración WhatsApp -->
                <div class="card">
                    <div class="card-header">
                        Configuración WhatsApp
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="text" class="form-control" id="telefono" placeholder="Ingrese el teléfono">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="idWhatsapp" class="form-label">ID WhatsApp</label>
                                <input type="text" class="form-control" id="idWhatsapp" placeholder="Ingrese el ID de WhatsApp">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="idBusinessAccount" class="form-label">ID WhatsApp Business Account</label>
                                <input type="text" class="form-control" id="idBusinessAccount" placeholder="Ingrese el ID de WhatsApp Business Account">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="tokenApi" class="form-label">Token WhatsApp API</label>
                            <input type="text" class="form-control" id="tokenApi" placeholder="Ingrese el token de la API de WhatsApp">
                        </div>

                        <div class="mb-3">
                            <label for="tokenWebhookUrl" class="form-label">Token Webhook URL</label>
                            <input type="text" class="form-control" id="tokenWebhookUrl" placeholder="Ingrese el token de Webhook URL" value="wh_clfgshur5">
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>