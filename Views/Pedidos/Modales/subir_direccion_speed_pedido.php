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

<div class="modal fade" id="subir_direccion_speedModal" tabindex="-1" aria-labelledby="subir_direccion_speedModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="subir_direccion_speedModalLabel"><i class="fas fa-edit"></i> Ubicación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex flex-column">
                    <div id="seccion_transito">
                        <label for="url_direccion_google">Link ubicacion google:</label>
                        <input type="text" class="form-control" id="url_direccion_google">
                    </div>

                    <button type="button" class="btn btn-primary" id="boton_speed" onclick="guardar_direccion_speed()">Guardar</button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
    function guardar_direccion_speed() {

        var url_direccion_google = $('#url_direccion_google').val();

        $("#url_google_speed_pedido").val(url_direccion_google);

        $('#subir_direccion_speedModal').modal('hide');
    }
</script>