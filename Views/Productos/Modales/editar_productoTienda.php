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
<div class="modal fade" id="editar_productoTiendaModal" tabindex="-1" aria-labelledby="editar_productoTiendaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editar_productoTiendaModalLabel"><i class="fas fa-edit"></i> Editar Producto tienda</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editar_productoTiendaForm">
                    <input type="hidden" id="editar_id_producto" name="editar_id_producto">
                    <div class="form-group">
                        <label for="editar_nombre_productoTienda">Nombre:</label>
                        <input type="text" class="form-control" id="editar_nombre_productoTienda" name="nombre_productoTienda" placeholder="Nombre">
                    </div>

                    <div class="form-group">
                        <label for="editar_pvpTienda">PVP:</label>
                        <input type="text" class="form-control" id="editar_pvpTienda" name="pvpTienda" placeholder="Nombre">
                    </div>

                    <div class="form-group">
                        <label for="editar_prefTienda">Precio Referencial:</label>
                        <input type="text" class="form-control" id="editar_prefTienda" name="prefTienda" placeholder="Nombre">
                    </div>

                    <div class="form-group">
                        <label for="editar_categoria">Categoría:</label>
                        <select class="form-control" id="editar_categoria" name="editar_categoria">
                            <option value="0">-- Selecciona --</option>

                        </select>
                    </div>
                    <div class="form-group w-100">
    <label for="precio-referencial">Funnelish</label>
    <input type="checkbox" class="form-check-input" id="precio-referencial">
    <input type="hidden" id="precio-referencial-estado" value="0">
    <input type="text" class="form-control mt-2" id="funnelish" disabled>
</div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="actualizarproductoTienda">Actualizar</button>
            </div>
        </div>
    </div>
</div>
<script>
      document.getElementById('precio-referencial').addEventListener('change', function() {
        const estadoCheckbox = document.getElementById('precio-referencial-estado');
        const inputValor = document.getElementById('funnelish');

        // Cambia el valor del campo oculto según el estado del checkbox
        if (this.checked) {
            estadoCheckbox.value = '1';  // Si está marcado, establece a '1'
            inputValor.disabled = false; // Habilita el input
        } else {
            estadoCheckbox.value = '0';  // Si no está marcado, establece a '0'
            inputValor.disabled = true;  // Deshabilita el input
        }
    });
    
   
  
    
    $(document).ready(function() {
        $('#actualizarproductoTienda').click(function() {
            var editar_id_producto = $('#editar_id_producto').val();
            var editar_nombre_productoTienda = $('#editar_nombre_productoTienda').val();
            var editar_pvpTienda = $('#editar_pvpTienda').val();
            var editar_prefTienda = $('#editar_prefTienda').val();
            var editar_categoria = $('#editar_categoria').val();
            var editar_categoria = $('#editar_categoria').val();
            
            let formData = new FormData();
            formData.append("id_producto_tienda", editar_id_producto);
            formData.append("nombre", editar_nombre_productoTienda);
            formData.append("pvp_tienda", editar_pvpTienda);
            formData.append("id_categoria", editar_categoria);
            formData.append("pref", editar_prefTienda);

            $.ajax({
                type: 'POST',
                url: '' + SERVERURL + 'productos/editarProductoTienda',
                data: formData,
                processData: false, // No procesar los datos
                contentType: false, // No establecer ningún tipo de contenido
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
                            $('#editar_productoTiendaModal').modal('hide');
                            // Recargar la DataTable
                            initDataTableProductos();
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