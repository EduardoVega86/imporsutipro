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
                        <button type="button" class="btn btn-primary" id="boton_servi" onclick="enviar_serviNovedad()">Enviar</button>
                    </div>

                    <div id="seccion_laar" style="display: none;">
                        <div style="padding-bottom: 5px;">
                            <label for="ciudad_novedadesServi">Ciudad:</label>
                            <input type="text" class="form-control" id="ciudad_novedadesServi">
                            <br>
                            <label for="nombre_novedadesServi">Nombre:</label>
                            <input type="text" class="form-control" id="nombre_novedadesServi">
                            <br>
                            <label for="callePrincipal_novedadesServi">Calle Principal:</label>
                            <input type="text" class="form-control" id="callePrincipal_novedadesServi">
                            <br>
                            <label for="calleSecundaria_novedadesServi">Calle Secundaria:</label>
                            <input type="text" class="form-control" id="calleSecundaria_novedadesServi">
                            <br>
                            <label for="numeracion_novedadesServi">Numeracion:</label>
                            <input type="text" class="form-control" id="numeracion_novedadesServi">
                            <br>
                            <label for="referencia_novedadesServi">Referencia:</label>
                            <input type="text" class="form-control" id="referencia_novedadesServi">
                            <br>
                            <label for="telefono_novedadesServi">Telefono:</label>
                            <input type="text" class="form-control" id="telefono_novedadesServi">
                            <br>
                            <label for="celular_novedadesServi">Celular:</label>
                            <input type="text" class="form-control" id="celular_novedadesServi">
                            <br>
                            <label for="observacion_novedadesServi">Observacion:</label>
                            <input type="text" class="form-control" id="observacion_novedadesServi">
                            <br>
                            <label for="observacionA">Solucion a la Novedad:</label>
                            <input type="text" class="form-control" id="observacionA">

                        </div>
                        <button type="button" class="btn btn-primary" id="boton_laar" onclick="enviar_laarNovedad()">Enviar</button>
                    </div>

                    <div id="seccion_gintracom" style="display: none;">
                        <div class="form-group w-100" style="padding-bottom: 5px;">
                            <label for="tipo_gintracom">Tipo:</label>
                            <select class="form-select" id="tipo_gintracom">
                                <option selected value="">-- Selecciona --</option>
                                <option value="ofrecer">Volver a ofrecer al cliente</option>
                                <option value="rechazar">Efectuar devolucion</option>
                                <option value="recaudo">Ajustar recaudo</option>
                            </select>
                        </div>
                        <div style="padding-bottom: 10px;">
                            <label for="Solucion_novedad">Solucion a novedad:</label>
                            <input type="text" class="form-control" id="Solucion_novedad" maxlength="50">
                        </div>
                        <div style="padding-bottom: 5px;" id="fecha_gintra">
                            <label for="fecha">Fecha para gestionar novedad:</label>
                            <input type="text" id="datepicker">
                        </div>
                        <div class="alert alert-warning" role="alert">
                            <strong>Atención:</strong> Gintracom no recibe novedades los días domingo.
                        </div>
                        <div style="padding-bottom: 5px; display: none;" id="valor_recaudoGintra">
                            <label for="Valor_recaudar">Valor a recaudar:</label>
                            <input type="text" class="form-control" id="Valor_recaudar">
                        </div>
                        <button type="button" class="btn btn-primary" id="boton_gintra" onclick="enviar_gintraNovedad()">Enviar</button>
                    </div>

                    <div id="seccion_speed" style="display: none;">
                        <div class="form-group w-100" style="padding-bottom: 5px;">
                            <label for="tipo_speed">Tipo:</label>
                            <select class="form-select" id="tipo_speed">
                                <option selected value="">-- Selecciona --</option>
                                <option value="ofrecer">Volver a ofrecer al cliente</option>
                                <option value="rechazar">Efectuar devolucion</option>
                                <option value="recaudo">Ajustar recaudo</option>
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
<script>
    $(function() {
        $('#datepicker').daterangepicker({
            singleDatePicker: true,
            minDate: moment().add(1, 'days'), // Deshabilita días anteriores y el día actual
            locale: {
                format: 'YYYY-MM-DD' // Establece el formato de fecha
            },
            isInvalidDate: function(date) {
                // Deshabilitar fines de semana
                var day = date.day();
                return (day === 0);
            }
        });
    });
</script>