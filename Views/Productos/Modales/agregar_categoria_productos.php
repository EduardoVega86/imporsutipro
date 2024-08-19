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
</style>

<div class="modal fade" id="agregar_categoriaModal" tabindex="-1" aria-labelledby="agregar_categoriaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="agregar_categoriaModalLabel"><i class="fas fa-edit"></i> Nueva Categoría</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="agregar_categoriaForm">
                    <div class="form-group">
                        <label for="nombre_linea">Nombre:</label>
                        <input type="text" class="form-control" id="nombre_linea" name="nombre_linea" placeholder="Nombre">
                    </div>
                    <div class="form-group">
                        <label for="descripcion_linea">Descripción:</label>
                        <textarea class="form-control" id="descripcion_linea" name="descripcion_linea" rows="3" placeholder="Descripción"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="online">Online:</label>
                        <select class="form-control" id="online" name="online">
                            <option value="1">SI</option>
                            <option value="0">NO</option>
                        </select>
                    </div>
                    <!-- <div class="form-group">
                        <label for="tipo">Tipo:</label>
                        <select class="form-control" id="tipo" name="tipo">
                            <option value="1">PRINCIPAL</option>
                            <option value="0">SECUNDARIO</option>
                        </select>
                    </div> -->
                    <input type="hidden" id="tipo" name="tipo" value="1">
                    <!-- <div class="form-group">
                        <label for="padre">Categoria Principal:</label>
                        <select class="form-control" id="padre" name="padre">
                            <option value="0">-- Selecciona --</option>
                            
                        </select>
                    </div> -->
                    <input type="hidden" id="padre" name="padre" value="0">
                    <div class="form-group">
                        <label for="estado">Estado:</label>
                        <select class="form-control" id="estado" name="estado">
                            <option value="1">Activo</option>
                            <option value="0">Inactivo</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="orden">Orden en la que aparecerá la categoria:</label>
                        <input type="number" class="form-control" id="orden" name="orden" placeholder="orden" step="1" min="1">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="guardarCategoria">Guardar</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#guardarCategoria').click(function() {
            var formData = $('#agregar_categoriaForm').serialize();

            $.ajax({
                type: 'POST',
                url: '' + SERVERURL + 'productos/agregarCategoria',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    // Mostrar alerta de éxito
                    if (response.status == 500) {
                        Swal.fire({
                            icon: 'error',
                            title: response.title,
                            text: response.message
                        });
                    } else {
                        Swal.fire({
                            icon: 'success',
                            title: response.title,
                            text: response.message,
                            showConfirmButton: false,
                            timer: 2000
                        }).then(() => {
                            // Cerrar el modal
                            $('#agregar_categoriaModal').modal('hide');
                            // Recargar la DataTable
                            initDataTable();
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error en la solicitud AJAX:', error);
                    alert('Hubo un problema al agregar la categoría');
                }
            });
        });
    });
</script>