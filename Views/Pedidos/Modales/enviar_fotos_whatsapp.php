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

    .imagen-container {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
    }

    .imagen-container img {
        max-width: 100px;
        margin-right: 15px;
        border-radius: 5px;
    }

    .imagen-container input {
        flex: 1;
        border: 1px solid #ccc;
        border-radius: 5px;
        padding: 5px;
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

<!-- Modal para enviar fotos a WhatsApp -->
<div class="modal fade" id="enviar_fotos_whatsappModal" tabindex="-1" aria-labelledby="enviar_fotos_whatsappModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="enviar_fotos_whatsappModalLabel"><i class="fas fa-edit"></i> Seleccionar imágenes para enviar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <button id="seleccionar_imagenes" class="btn btn-primary">Seleccionar Imágenes</button>
                <input id="foto-input" type="file" accept="image/*" multiple style="display:none;">

                <div id="galeria-imagenes" class="mt-3">
                    <!-- Aquí se mostrarán las imágenes seleccionadas con sus captions -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" id="enviar-imagenes" class="btn btn-primary">Enviar Imágenes</button>
            </div>
        </div>
    </div>
</div>