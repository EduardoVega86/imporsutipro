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

    .boton_eliminar_etiqueta {
        background-color: transparent;
        border: hidden;
        color: #afaea9;
    }

    .boton_eliminar_etiqueta:hover {
        color: black;
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
<div class="modal fade" id="agregar_numero_clienteModal" tabindex="-1" aria-labelledby="agregar_numero_clienteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="agregar_numero_clienteModalLabel"><i class="fas fa-edit"></i> Asignar motivo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card mb-4">
                    <div class="card-header">
                        Agregar motivo
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <input type="hidden" id="id_factura_ingresar_motivo" name="id_factura_ingresar_motivo">

                            <label for="motivo_noDesea" class="form-label">Motivo:</label>
                            <input type="text" class="form-control" id="motivo_noDesea" placeholder="Ingrese el motivo">

                            <div id="seccion_informacion_numero" style="display: none;">
                                <button type="button" class="btn btn-primary" onclick="agregar_detalle_noDesea()">Agregar</button>
                            </div>
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

<script>
    function agregar_detalle_noDesea() {
        var id_factura_ingresar_motivo = $('#id_factura_ingresar_motivo').val();
        var motivo_noDesea = $('#motivo_noDesea').val();

        let formData = new FormData();
        formData.append("id_factura_ingresar_motivo", id_factura_ingresar_motivo);
        formData.append("motivo_noDesea", motivo_noDesea);

        $.ajax({
            url: SERVERURL + "Pedidos/agregar_detalle_noDesea",
            type: "POST",
            data: formData,
            processData: false, // No procesar los datos
            contentType: false, // No establecer ningún tipo de contenido
            dataType: "json",
            success: function(response) {
                if (result.status == 200) {
                    toastr.success("MOTIVO INGRESADO CORRECTAMENTE", "NOTIFICACIÓN", {
                        positionClass: "toast-bottom-center",
                    });

                    initDataTableHistorial();
                    $("#ingresar_nodDesea_pedido").modal("hide");
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert(errorThrown);
            },
        });

    }
</script>