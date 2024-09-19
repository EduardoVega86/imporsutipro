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
<div class="modal fade" id="gestionar_novedadSpeedModal" tabindex="-1" aria-labelledby="gestionar_novedadSpeedModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="gestionar_novedadSpeedModalLabel"><i class="fas fa-edit"></i> Novedad</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex flex-row">
                    <!-- <div class="informacion_producto">
                        <h3 class="mb-3" style="text-decoration:underline;"><strong>Detalle Novedad</strong></h3>

                        <p class="texto_modal"><strong>ID:</strong> <span id="id_gestionarNov"></span></p>
                        <p class="texto_modal"><strong>Cliente:</strong> <span id="cliente_gestionarNov"></span></p>
                        <p class="texto_modal"><strong>Estado:</strong> <span id="estado_gestionarNov"></span></p>
                        <p class="texto_modal"><strong>Transportadora:</strong> <span id="transportadora_gestionarNov"></span></p>
                        <p class="texto_modal"><strong>Novedad:</strong> <span id="novedad_gestionarNov"></span></p>
                        <p class="texto_modal"><strong>Tracking:</strong> <a id="tracking_gestionarNov" target="_blank">Ver tracking</a></p>
                    </div> -->

                    <input type="hidden" id="numeroGuia_novedad_speed" name="numeroGuia_novedad_speed">
                    <input type="hidden" id="nuevoEstado_novedad_speed" name="nuevoEstado_novedad_speed">
                    <input type="hidden" id="idFactura_novedad_speed" name="idFactura_novedad_speed">

                    <div id="seccion_speed">
                        <div class="form-group w-100" style="padding-bottom: 5px;">
                            <label for="tipo_speed">Tipo:</label>
                            <select class="form-select" id="tipo_speed">
                                <option selected value="">-- Selecciona --</option>
                                <option value="recibir">Cliente no desea recibir</option>
                                <option value="direccion">Direccion erronea</option>
                                <option value="telefono">Numero de telefono incorrecto</option>
                                <option value="nocontesta">No contesta</option>
                                <option value="nolugar">No se encuentra en lugar</option>
                                <option value="peligro">Zona peligrosa</option>
                                <option value="rechazar">Reagendar entrega</option>
                            </select>
                        </div>
                        <div style="padding-bottom: 5px;">
                            <label for="observacion_nov_speed">Observacion:</label>
                            <input type="text" class="form-control" id="observacion_nov_speed">
                        </div>
                        <button type="button" class="btn btn-primary" id="boton_speed" onclick="enviar_speedNovedad()">Enviar</button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>