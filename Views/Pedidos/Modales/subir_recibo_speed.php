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
        $('#fileUpload').on('change', function() {
            let formData = new FormData($('#uploadForm')[0]); // Capturamos el formulario

            $.ajax({
                url: 'subir_recibo.php', // Archivo PHP que manejará la subida
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    $('#uploadStatus').html('<p>' + response + '</p>'); // Mostramos la respuesta del servidor
                },
                error: function() {
                    $('#uploadStatus').html('<p>Error al subir el recibo.</p>'); // Mostramos un mensaje de error si falla
                }
            });
        });
    });
</script>