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
<div class="modal fade" id="subir_imagen_speedModal" tabindex="-1" aria-labelledby="subir_imagen_speedModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="subir_imagen_speedModalLabel"><i class="fas fa-edit"></i> Novedad</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="numeroGuia_subir_reporte" name="numeroGuia_subir_reporte">
                <input type="hidden" id="nuevoEstado_subir_reporte" name="nuevoEstado_subir_reporte">
                <input type="hidden" id="idFactura_subir_reporte" name="idFactura_subir_reporte">

                <form id="uploadForm" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="fileUpload" class="form-label">Subir Recibo</label>
                        <input type="file" class="form-control" id="fileUpload" name="recibo" accept="image/*" required>
                    </div>
                    <div id="uploadStatus" class="texto_modal"></div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Usamos jQuery para manejar el evento de subida de archivo
    $(document).ready(function() {
        $('#fileUpload').on('change', async function(event) {
            event.preventDefault(); // Prevenir el comportamiento por defecto del formulario

            var numeroGuia_subir_reporte = $('#numeroGuia_subir_reporte').val();
            var nuevoEstado_subir_reporte = $('#nuevoEstado_subir_reporte').val();
            var idFactura_subir_reporte = $('#idFactura_subir_reporte').val();

            let formData = new FormData();
            formData.append("recibo", $('#fileUpload')[0].files[0]); // Correcto para archivos
            formData.append("id_factura", idFactura_subir_reporte);

            $.ajax({
                url: SERVERURL + 'speed/guardarRecibo',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                dataType: "json",
                success: async function(response) {
                    if (response.status == 500) {
                        toastr.error(
                            "LA IMAGEN NO SE AGREGÓ CORRECTAMENTE",
                            "NOTIFICACIÓN", {
                                positionClass: "toast-bottom-center"
                            }
                        );
                    } else if (response.status == 200) {
                        toastr.success("IMAGEN AGREGADA CORRECTAMENTE", "NOTIFICACIÓN", {
                            positionClass: "toast-bottom-center",
                        });

                        // Cambiar estado de la guía
                        const formData = new FormData();
                        formData.append("estado", nuevoEstado_subir_reporte);

                        try {
                            const response = await fetch(
                                `https://guias.imporsuitpro.com/Speed/estado/${numeroGuia_subir_reporte}`, {
                                    method: "POST",
                                    body: formData,
                                }
                            );
                            const result = await response.json();
                            if (result.status == 200) {
                                toastr.success("ESTADO ACTUALIZADO CORRECTAMENTE", "NOTIFICACIÓN", {
                                    positionClass: "toast-bottom-center",
                                });

                                $("#subir_imagen_speedModal").modal("hide"); // Cerrar modal correctamente
                                reloadDataTable(); // Recargar tabla si tienes alguna
                            } else {
                                toastr.error("Error al actualizar el estado", "NOTIFICACIÓN", {
                                    positionClass: "toast-bottom-center",
                                });
                            }
                        } catch (error) {
                            console.error("Error al conectar con la API", error);
                            alert("Error al conectar con la API");
                        }
                    }
                },
                error: function() {
                    $('#uploadStatus').html('<p>Error al subir el recibo.</p>'); // Mostramos un mensaje de error si falla
                }
            });
        });
    });
</script>