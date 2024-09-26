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
                <h5 class="modal-title" id="subir_direccion_speedModalLabel"><i class="fas fa-edit"></i> Novedad</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex flex-column">
                    <input type="hidden" id="numero_guia_url" name="numero_guia_url">

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
        var numero_guia_url = $('#numero_guia_url').val();
        var url_direccion_google = $('#url_direccion_google').val();

        let formData = new FormData();
        formData.append("guia", numero_guia_url);
        formData.append("url", url_direccion_google);

        $.ajax({
            url: "https://guias.imporsuitpro.com/Speed/asignarUrl",
            type: "POST",
            data: formData,
            processData: false, // No procesar los datos
            contentType: false, // No establecer ningún tipo de contenido
            dataType: "json",
            success: function(response) {
                if (response.status == 500) {
                    toastr.error(
                        "NO SE GUARDO CORRECTAMENTE",
                        "NOTIFICACIÓN", {
                            positionClass: "toast-bottom-center"
                        }
                    );
                } else if (response.status == 200) {
                    toastr.success("GUARDADO CORRECTAMENTE", "NOTIFICACIÓN", {
                        positionClass: "toast-bottom-center",
                    });
                    $('#subir_direccion_speedModal').modal('hide');
                }

            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert(errorThrown);
            },
        });

    }
</script>