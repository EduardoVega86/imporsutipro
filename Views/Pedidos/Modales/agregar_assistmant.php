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
<div class="modal fade" id="agregar_assistmantModal" tabindex="-1" aria-labelledby="agregar_assistmantModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="agregar_assistmantModalLabel"><i class="fas fa-edit"></i> Configuración</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <!-- Configuración WhatsApp -->
                <div class="card">
                    <div class="card-header">
                        Configuración Asistente
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="nombre_bot" class="form-label">Nombre del bot:</label>
                            <input type="text" class="form-control" id="nombre_bot" placeholder="Ingrese el nombre del bot">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="assistant_id" class="form-label">ID del asistente:</label>
                                <input type="text" class="form-control" id="assistant_id" placeholder="Ingrese el ID del asistente">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="api_key" class="form-label">Api Key:</label>
                                <input type="text" class="form-control" id="api_key" placeholder="Ingrese el Api Key">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="promt" class="form-label">Promt (Opcional):</label>
                            <textarea id="promt" rows="5" cols="50" style="resize: none;"></textarea>
                        </div>
                    </div>
                </div>

                <div style="padding: 10px;">
                    <button type="button" class="btn btn-primary" onclick="agregar_assistmant()">Agregar asistente</button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
    function agregar_assistmant() {
        var nombre_bot = $('#nombre_bot').val();
        var assistant_id = $('#assistant_id').val();
        var api_key = $('#api_key').val();
        var promt = $('#promt').val();

        let formData = new FormData();
        formData.append("nombre_bot", nombre_bot);
        formData.append("assistant_id", assistant_id);
        formData.append("api_key", api_key);
        formData.append("promt", promt);

        $.ajax({
            url: SERVERURL + "Pedidos/agregar_assistmant",
            type: "POST",
            data: formData,
            processData: false, // No procesar los datos
            contentType: false, // No establecer ningún tipo de contenido
            dataType: "json",
            success: function(response) {
                if (response.status == 500) {
                    toastr.error(
                        "LA CONFIGURACION NO SE AGREGRO CORRECTAMENTE",
                        "NOTIFICACIÓN", {
                            positionClass: "toast-bottom-center"
                        }
                    );
                } else if (response.status == 200) {
                    toastr.success("CONFIGURACION AGREGADA CORRECTAMENTE", "NOTIFICACIÓN", {
                        positionClass: "toast-bottom-center",
                    });
                    $('#agregar_assistmantModal').modal('hide');
                    initDataTableConfiguracionAutomatizador();
                }

            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert(errorThrown);
            },
        });

    }
</script>