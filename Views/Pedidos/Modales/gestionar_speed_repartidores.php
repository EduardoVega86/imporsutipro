<style>
    /* El resto del estilo queda igual */
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
        margin-bottom: 1rem;
    }

    @media (max-width: 768px) {
        .descripcion_producto {
            flex-direction: column-reverse;
        }

        .informacion_producto {
            width: 100%;
        }
    }

    #previsualizacion {
        max-width: 100%;
        height: auto;
        margin-top: 10px;
    }
</style>

<div class="modal fade" id="gestionar_speed_repartidoresModal" tabindex="-1" aria-labelledby="gestionar_speed_repartidoresModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="gestionar_speed_repartidoresModalLabel"><i class="fas fa-edit"></i> Novedad</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex flex-row">
                    <input type="hidden" id="numeroGuia_novedad_speed" name="numeroGuia_novedad_speed">
                    <input type="hidden" id="nuevoEstado_novedad_speed" name="nuevoEstado_novedad_speed">
                    <input type="hidden" id="idFactura_novedad_speed" name="idFactura_novedad_speed">

                    <!-- Sección de foto con opción para tomar foto -->
                    <div id="seccion_foto" style="display: block;">
                        <label for="input_foto">Tomar Foto:</label>
                        <input type="file" id="input_foto" accept="image/*;capture=camera" onchange="previsualizarFoto(event)">
                        <img id="previsualizacion" alt="Previsualización de la imagen">
                        <button type="button" class="btn btn-primary" id="boton_speed" onclick="enviar_foto()">Enviar</button>
                    </div>

                    <div id="seccion_speed" style="display: none;">
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
                    </div>

                    <button type="button" class="btn btn-primary" id="boton_speed" onclick="enviar_speedNovedad()">Enviar</button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Función para previsualizar la imagen seleccionada
    function previsualizarFoto(event) {
        const input = event.target;
        const file = input.files[0];
        const reader = new FileReader();

        reader.onload = function(e) {
            const previsualizacion = document.getElementById('previsualizacion');
            previsualizacion.src = e.target.result;
        };

        if (file) {
            reader.readAsDataURL(file);
        }
    }

    // Función para enviar la foto a la API
    function enviar_foto() {
        const inputFoto = document.getElementById('input_foto');
        const file = inputFoto.files[0];

        if (!file) {
            alert('Por favor, toma o selecciona una foto.');
            return;
        }

        const formData = new FormData();
        formData.append('foto', file);

        fetch('URL_DE_TU_API', {
                method: 'POST',
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                console.log('Éxito:', data);
                alert('Foto enviada exitosamente.');
            })
            .catch((error) => {
                console.error('Error:', error);
                alert('Error al enviar la foto.');
            });
    }
</script>