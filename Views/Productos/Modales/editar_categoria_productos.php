<style>
    .modal-content {
        border-radius: 15px;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }

    .modal-header {
        background-color: #171931;
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

<!-- Modal -->
<div class="modal fade" id="editar_categoriaModal" tabindex="-1" aria-labelledby="editar_categoriaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editar_categoriaModalLabel"><i class="fas fa-edit"></i> Editar Categoría</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editar_categoriaForm">
                    <input type="hidden" id="editar_id_linea" name="id_linea">
                    <div class="form-group">
                        <label for="editar_nombre_linea">Nombre:</label>
                        <input type="text" class="form-control" id="editar_nombre_linea" name="nombre_linea" placeholder="Nombre">
                    </div>
                    <div class="form-group">
                        <label for="editar_descripcion_linea">Descripción:</label>
                        <textarea class="form-control" id="editar_descripcion_linea" name="descripcion_linea" rows="3" placeholder="Descripción"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="editar_online">Online:</label>
                        <select class="form-control" id="editar_online" name="online">
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
                    <input type="hidden" id="editar_tipo" name="tipo" value="1">
                    <!-- <div class="form-group">
                        <label for="padre">Categoria Principal:</label>
                        <select class="form-control" id="padre" name="padre">
                            <option value="0">-- Selecciona --</option>
                            
                        </select>
                    </div> -->
                    <input type="hidden" id="editar_padre" name="padre" value="0">
                    <div class="form-group">
                        <label for="editar_estado">Estado:</label>
                        <select class="form-control" id="editar_estado" name="estado">
                            <option value="1">Activo</option>
                            <option value="0">Inactivo</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="orden_editar">Orden en la que aparecerá la categoria:</label>
                        <input type="number" class="form-control" id="orden_editar" name="orden_editar" placeholder="orden" step="1" min="1">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="actualizarCategoria">Guardar</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#actualizarCategoria').click(function() {
            var id_linea = $('#editar_id_linea').val();
            var nombre_linea = $('#editar_nombre_linea').val();
            var descripcion_linea = $('#editar_descripcion_linea').val();
            var online = $('#editar_online').val();
            var tipo = $('#editar_tipo').val();
            var padre = $('#editar_padre').val();
            var estado = $('#editar_estado').val();
            var orden = $('#orden_editar').val();
            var imagen = ''; // Asigna el valor apropiado para imagen si es necesario
            var date_added = new Date().toISOString().slice(0, 19).replace('T', ' ');

            var formData = {
                id: id_linea,
                nombre_linea: nombre_linea,
                descripcion_linea: descripcion_linea,
                online: online,
                tipo: tipo,
                padre: padre,
                estado: estado,
                orden: orden,
                imagen: imagen,
                date_added: date_added
            };

            $.ajax({
                type: 'POST',
                url: '' + SERVERURL + 'productos/editarCategoria',
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
                            $('#editar_categoriaModal').modal('hide');
                            // Recargar la DataTable
                            initDataTable();
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error en la solicitud AJAX:', error);
                    alert('Hubo un problema al editar la categoría');
                }
            });
        });
    });
</script>