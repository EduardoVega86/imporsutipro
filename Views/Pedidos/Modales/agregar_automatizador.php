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
<div class="modal fade" id="agregar_automatizadorModal" tabindex="-1" aria-labelledby="agregar_automatizadorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="agregar_automatizadorModalLabel"><i class="fas fa-edit"></i> Automatizador</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="card mb-4">
                    <div class="card-header">
                       Datos Automatizador
                    </div>
                    <div class="card-body">
                        <input type="hidden" id="id_configuracion" name="id_configuracion">
                        <div class="mb-3">
                            <label for="nombreAutomatizador" class="form-label">Nombre Automatizador</label>
                            <input type="text" class="form-control" id="nombreAutomatizador" placeholder="Ingrese el nombre de la configuración">
                        </div>
                    </div>
                </div>

                <div style="padding: 10px;">
                    <button type="button" class="btn btn-primary" onclick="agregar_automatizador()">Agregar automatizador</button>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
    function agregar_automatizador() {
        var id_configuracion = $('#id_configuracion').val();
        var nombreAutomatizador = $('#nombreAutomatizador').val();

        let formData = new FormData();
        formData.append("nombre_automatizador", nombreAutomatizador);
        formData.append("id_configuracion", id_configuracion);
        
        $.ajax({
            url: SERVERURL + "Pedidos/agregar_automatizador",
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
                    $('#agregar_configuracion_automatizadorModal').modal('hide');
                    initDataTableConfiguracionAutomatizador();
                }

            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert(errorThrown);
            },
        });

    }
</script>