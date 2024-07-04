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
<div class="modal fade" id="gestionar_novedadModal" tabindex="-1" aria-labelledby="gestionar_novedadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="gestionar_novedadModalLabel"><i class="fas fa-edit"></i> Novedad</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex flex-row">
                    <div class="informacion_producto">
                        <h3 class="mb-3" style="text-decoration:underline;"><strong>Detalle Novedad</strong></h3>

                        <input type="hidden" id="numero_guia" name="numero_guia">
                        <input type="hidden" id="id_novedad" name="id_novedad">

                        <p class="texto_modal"><strong>ID:</strong> <span id="id_gestionarNov"></span></p>
                        <p class="texto_modal"><strong>Cliente:</strong> <span id="cliente_gestionarNov"></span></p>
                        <p class="texto_modal"><strong>Estado:</strong> <span id="estado_gestionarNov"></span></p>
                        <p class="texto_modal"><strong>Transportadora:</strong> <span id="transportadora_gestionarNov"></span></p>
                        <p class="texto_modal"><strong>Novedad:</strong> <span id="novedad_gestionarNov"></span></p>
                        <p class="texto_modal"><strong>Tracking:</strong> <a id="tracking_gestionarNov" target="_blank">Ver tracking</a></p>
                    </div>

                    <div id="seccion_servientrega" style="display: none;">
                        <div style="padding-bottom: 5px;">
                            <label for="observacion_nov">Observacion:</label>
                            <input type="text" class="form-control" id="observacion_nov">
                        </div>
                        <button type="button" class="btn btn-primary" onclick="enviar_serviNovedad()">Enviar</button>
                    </div>

                    <div id="seccion_laar" style="display: none;">
                        <div style="padding-bottom: 5px;">
                            <label for="observacion_nov">Observacion:</label>
                            <input type="text" class="form-control" id="observacion_nov">
                        </div>
                        <button type="button" class="btn btn-primary" onclick="enviar_serviNovedad()">Enviar</button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>