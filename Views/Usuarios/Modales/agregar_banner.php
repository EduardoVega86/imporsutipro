<style>
    .form-group {
        margin-bottom: 15px;
    }

    /* .modal-header {
        background-color: #343a40;
        color: white;
    } */

    .hidden-tab {
        display: none !important;
    }

    .hidden-field {
        display: none;
    }
</style>

<div class="modal fade" id="agregar_bannerModal" tabindex="-1" aria-labelledby="agregar_bannerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="agregar_bannerModalLabel"><i class="fas fa-edit"></i> Nuevo Banner</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <form id="agregar_banner_form">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="titulo" class="form-label">Título</label>
                        <input type="text" class="form-control" id="titulo" placeholder="Título">
                    </div>
                    <div class="col-md-6">
                        <label for="textoSlider" class="form-label">Texto del slider</label>
                        <textarea class="form-control" id="textoSlider" rows="3" placeholder="Texto del slider"></textarea>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="boton" class="form-label">Botón</label>
                        <input type="text" class="form-control" id="boton" placeholder="Texto del botón">
                    </div>
                    <div class="col-md-6">
                        <label for="enlaceBoton" class="form-label">Enlace Botón</label>
                        <input type="text" class="form-control" id="enlaceBoton" placeholder="URL del botón">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="alineacion" class="form-label">Alineación</label>
                        <select class="form-select" id="alineacion">
                            <option value="izquierda">Izquierda</option>
                            <option value="centro">Centro</option>
                            <option value="derecha">Derecha</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary" id="guardar_banner">Guardar</button>
            </div>
            </form>
        </div>
    </div>
</div>

<script>

    //enviar datos a base de datos
    $(document).ready(function() {
        // Función para reiniciar el formulario
        function resetForm() {
            $('#agregar_producto_form')[0].reset();
            $('#bodega-field').addClass('hidden-field');
            $('#precio-referencial-valor').prop('disabled', true);
        }

        // Evento para reiniciar el formulario cuando se cierre el modal
        $('#agregar_productoModal').on('hidden.bs.modal', function() {
            var button = document.getElementById('guardar_producto');
            button.disabled = false; // Desactivar el botón
        });

        $('#agregar_producto_form').submit(function(event) {
            event.preventDefault(); // Evita que el formulario se envíe de la forma tradicional

            var button = document.getElementById('guardar_producto');
            button.disabled = true; // Desactivar el botón

            // Crea un objeto FormData
            var formData = new FormData();
            formData.append('codigo_producto', $('#codigo').val());
            formData.append('nombre_producto', $('#nombre').val());
            formData.append('descripcion_producto', $('#descripcion').val());
            formData.append('id_linea_producto', $('#categoria').val());
            formData.append('inv_producto', $('#maneja-inventario').val());
            formData.append('producto_variable', $('#producto-variable').val());
            formData.append('costo_producto', $('#costo').val());
            formData.append('aplica_iva', 1); // Suponiendo que siempre aplica IVA
            formData.append('estado_producto', 1); // Suponiendo que el estado es activo
            formData.append('date_added', new Date().toISOString().split('T')[0]);
            formData.append('formato', $('#formato-pagina').val());
            formData.append('drogshipin', 0); // Suponiendo que no es dropshipping
            formData.append('destacado', 0); // Suponiendo que no es destacado
            formData.append('stock_inicial', $('#stock-inicial').val());
            formData.append('bodega', $('#bodega').val());
            formData.append('pcp', $('#precio-proveedor').val());
            formData.append('pvp', $('#precio-venta').val());
            formData.append('pref', $('#precio-referencial-valor').val());

            // Realiza la solicitud AJAX
            $.ajax({
                url: '' + SERVERURL + 'productos/agregar_producto',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    response = JSON.parse(response);
                    // Mostrar alerta de éxito
                    if (response.status == 500) {
                        toastr.error(
                            "EL PRODUCTO NO SE AGREGRO CORRECTAMENTE",
                            "NOTIFICACIÓN", {
                                positionClass: "toast-bottom-center"
                            }
                        );
                    } else if (response.status == 200) {
                        toastr.success("PRODUCTO AGREGADO CORRECTAMENTE", "NOTIFICACIÓN", {
                            positionClass: "toast-bottom-center",
                        });

                        $('#agregar_productoModal').modal('hide');
                        resetForm();
                        initDataTableProductos();
                    }
                },
                error: function(error) {
                    alert('Hubo un error al agregar el producto');
                    console.log(error);
                }
            });
        });
    });
</script>