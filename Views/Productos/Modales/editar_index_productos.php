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
</style>

<div class="modal fade" id="editar_productoModal" tabindex="-1" aria-labelledby="editar_productoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editar_productoModalLabel"><i class="fas fa-edit"></i> Editar Producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul class="nav nav-tabs" id="editarTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="editar-datos-basicos-tab" data-bs-toggle="tab" data-bs-target="#editar-datos-basicos" type="button" role="tab" aria-controls="editar-datos-basicos" aria-selected="true"><strong>Datos Básicos</strong></button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="editar-precios-stock-tab" data-bs-toggle="tab" data-bs-target="#editar-precios-stock" type="button" role="tab" aria-controls="editar-precios-stock" aria-selected="false"><strong>Precios y Stock</strong></button>
                    </li>
                </ul>
                <div class="tab-content" id="editarTabContent">
                    <div class="tab-pane fade show active" id="editar-datos-basicos" role="tabpanel" aria-labelledby="editar-datos-basicos-tab">
                        <form id="editar_producto_form">
                            <div class="d-flex flex-column">
                                <div class="d-flex flex-row gap-3">
                                    <input type="hidden" id="editar_id_producto" name="id_producto">
                                    <div class="form-group w-100">
                                        <label for="editar_codigo">Código:</label>
                                        <input type="text" class="form-control" id="editar_codigo">
                                    </div>
                                    <div class="form-group w-100">
                                        <label for="editar_nombre">Nombre:</label>
                                        <input type="text" class="form-control" id="editar_nombre">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="editar_descripcion">Descripción:</label>
                                    <textarea class="form-control" id="editar_descripcion"></textarea>
                                </div>
                                <div class="d-flex flex-row gap-3">
                                    <div class="d-flex flex-column w-100">
                                        <div class="form-group">
                                            <label for="editar_categoria">Categoría:</label>
                                            <select class="form-select" id="editar_categoria">
                                                <option selected>-- Selecciona Categoría --</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column w-100">
                                        <div class="form-group">
                                            <label for="editar_formato_pagina">Formato Página Productos:</label>
                                            <select onchange="formato_editar()" class="form-select" id="editar_formato_pagina">
                                                <option selected>-- Selecciona --</option>
                                                <option value="1">Formato 1</option>
                                                <option value="2">Formato 2</option>
                                            </select>
                                        </div>
                                    </div>
                                      <div style="display: none;" id="funnelish_editar" class="flex-column w-100">
                                        <div class="form-group">
                                            <label for="nombre">Enlace de Funnelish:</label>
                                            <input  type="text" class="form-control"  id="editar-enlace_funnelish" >                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                            <label>Formato:</label>
                                            <div class="d-flex">
                                                <img src="https://new.imporsuitpro.com/public/img/formato_pro.jpg" alt="Formato" class="me-2" width="350px;">
                                            </div>
                                        </div>
                            </div>
                    </div>
                    <div class="tab-pane fade" id="editar-precios-stock" role="tabpanel" aria-labelledby="editar-precios-stock-tab">
                        <div class="d-flex flex-column">
                            <div class="d-flex flex-row gap-3">
                                <div class="form-group w-100">
                                    <label for="editar_ultimo_costo">Ultimo Costo:</label>
                                    <input type="text" class="form-control" id="editar_ultimo_costo">
                                </div>
                                <div class="form-group w-100">
                                    <label for="editar_precio_proveedor">Precio Proveedor:</label>
                                    <input type="text" class="form-control" id="editar_precio_proveedor">
                                </div>
                            </div>
                            <div class="d-flex flex-row gap-3">
                                <div class="form-group w-100">
                                    <label for="editar_precio_venta">Precio de Venta (Sugerido):</label>
                                    <input type="text" class="form-control" id="editar_precio_venta">
                                </div>
                                <div class="form-group w-100">
                                    <label for="editar_precio_referencial">Precio Referencial</label>
                                    <input type="text" class="form-control mt-2" id="editar_precio_referencial">
                                </div>
                            </div>
                            <div class="d-flex flex-row gap-3">
                                <div class="form-group w-100">
                                    <label for="editar_maneja_inventario">Maneja Inventario:</label>
                                    <select class="form-select" id="editar_maneja_inventario">
                                        <option selected>-- Selecciona --</option>
                                        <option value="1">Sí</option>
                                        <option value="2">No</option>
                                    </select>
                                </div>
                                <div class="form-group w-100">
                                    <label for="editar_producto_variable">Producto Variable:</label>
                                    <select class="form-select" id="editar_producto_variable">
                                        <option selected>-- Selecciona --</option>
                                        <option value="1">Sí</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>
                                <!-- <div class="form-group w-100">
                                    <label for="editar_producto_privado">Producto privado:</label>
                                    <select class="form-select" id="editar_producto_privado">
                                        <option selected>-- Selecciona --</option>
                                        <option value="1">Sí</option>
                                        <option value="0">No</option>
                                    </select>
                                </div> -->
                            </div>
                            <div class="d-flex flex-row gap-3">
                                <div class="form-group w-100" id="bodega-field">
                                    <label for="editar_bodega">Bodega:</label>
                                    <select class="form-select" id="editar_bodega">
                                        <option selected>-- Selecciona Bodega --</option>
                                    </select>
                                </div>
                                <div class="form-group w-100">
                                    <label for="editar_stock_inicial">Stock Inicial:</label>
                                    <input type="text" class="form-control" id="editar_stock_inicial">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary">Actualizar</button>
            </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        // Enviar datos al editar producto
        $('#editar_producto_form').submit(function(event) {
            event.preventDefault(); // Evita que el formulario se envíe de la forma tradicional

            // Crea un objeto FormData
            var formData = new FormData();
            formData.append('id_producto', $('#editar_id_producto').val()); // Asegúrate de tener el ID del producto en un campo oculto
            formData.append('codigo_producto', $('#editar_codigo').val());
            formData.append('nombre_producto', $('#editar_nombre').val());
            formData.append('descripcion_producto', $('#editar_descripcion').val());
            formData.append('id_linea_producto', $('#editar_categoria').val());
            formData.append('inv_producto', $('#editar_maneja_inventario').val());
            formData.append('producto_variable', $('#editar_producto_variable').val());
            formData.append('costo_producto', $('#editar_ultimo_costo').val());
            formData.append('aplica_iva', 1); // Suponiendo que siempre aplica IVA
            formData.append('estado_producto', 1); // Suponiendo que el estado es activo
            formData.append('date_added', new Date().toISOString().split('T')[0]);
            formData.append('formato', $('#editar_formato_pagina').val());
            formData.append('drogshipin', 0); // Suponiendo que no es dropshipping
            formData.append('destacado', 0); // Suponiendo que no es destacado
            formData.append('producto_variable', $('#editar_producto_variable').val());
            formData.append('stock_inicial', $('#editar_stock_inicial').val());
            formData.append('bodega', $('#editar_bodega').val());
            formData.append('pcp', $('#editar_precio_proveedor').val());
            formData.append('pvp', $('#editar_precio_venta').val());
            formData.append('pref', $('#editar_precio_referencial').val());

            // Realiza la solicitud AJAX
            $.ajax({
                url: '' + SERVERURL + 'productos/editar_producto',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    // Mostrar alerta de éxito
                    response = JSON.parse(response);
                    if (response.status == 500) {
                        toastr.error(
                            "EL PRODUCTO NO SE EDITO CORRECTAMENTE",
                            "NOTIFICACIÓN", {
                                positionClass: "toast-bottom-center"
                            }
                        );
                    } else if (response.status == 200) {
                        toastr.success("PRODUCTO EDITADO CORRECTAMENTE", "NOTIFICACIÓN", {
                            positionClass: "toast-bottom-center",
                        });

                        $('#editar_productoModal').modal('hide');
                       // initDataTableProductos();
                       reloadDataTableProductos();
                    }
                },
                error: function(error) {
                    alert('Hubo un error al editar el producto');
                    console.log(error);
                }
            });
        });
    });
    
     function formato_editar() {
    let formatoSeleccionado = $("#editar_formato_pagina").val();
    //alert(formatoSeleccionado);
    if (formatoSeleccionado == '3') {
        $("#funnelish_editar").show();
    } else {
        $("#funnelish_editar").hide();
    }
}
</script>