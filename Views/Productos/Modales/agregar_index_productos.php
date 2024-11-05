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

<div class="modal fade" id="agregar_productoModal" tabindex="-1" aria-labelledby="agregar_productoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="agregar_productoModalLabel"><i class="fas fa-edit"></i> Nuevo Producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="datos-basicos-tab" data-bs-toggle="tab" data-bs-target="#datos-basicos" type="button" role="tab" aria-controls="datos-basicos" aria-selected="true"><strong>Datos Básicos</strong></button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="precios-stock-tab" data-bs-toggle="tab" data-bs-target="#precios-stock" type="button" role="tab" aria-controls="precios-stock" aria-selected="false"><strong>Precios y Stock</strong></button>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="datos-basicos" role="tabpanel" aria-labelledby="datos-basicos-tab">
                        <form id="agregar_producto_form">
                            <div class="d-flex flex-column">
                                <div class="d-flex flex-row gap-3">
                                    <div class="form-group w-100">
                                        <label for="codigo">Código:</label>
                                        <input type="text" class="form-control" id="codigo" value="10088">
                                    </div>
                                    <div class="form-group w-100">
                                        <label for="nombre">Nombre:</label>
                                        <input type="text" class="form-control" id="nombre" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="descripcion">Descripción:</label>
                                    <textarea class="form-control" id="descripcion"></textarea>
                                </div>
                                <div class="d-flex flex-row gap-3">
                                    <div class="d-flex flex-column w-100">
                                        <div class="form-group">
                                            <label for="categoria">Categoría:</label>
                                            <select class="form-select" id="categoria" required>
                                                <option selected value="">-- Selecciona Categoría --</option>
                                            </select>
                                        </div>
                                    </div>
                                   
                                    
                                     <div class="d-flex flex-column w-100">
                                        <div class="form-group">
                                            <label for="formato-pagina">Formato Página Productos:</label>
                                            <select onchange="formato()" class="form-select" id="formato-pagina" required>
                                                <option selected value="">-- Selecciona --</option>
                                                <option value="1">Formato 1</option>
                                                <option value="2">Formato 2</option>
                                                <option value="3">Funnelish</option>
                                            </select>
                                        </div>
                                    </div>
                                     <div style="display: none;" id="funnelish" class="flex-column w-100">
                                        <div class="form-group">
                                            <label for="nombre">Enlace de Funnelish:</label>
                                            <input  type="text" class="form-control"  id="enlace_funnelish" >                                        </div>
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
                    <div class="tab-pane fade" id="precios-stock" role="tabpanel" aria-labelledby="precios-stock-tab">
                        <div class="d-flex flex-column">
                            <div class="d-flex flex-row gap-3">
                                <div class="form-group w-100">
                                    <label for="precio-venta">Precio de Venta (Sugerido):</label>
                                    <input type="text" class="form-control" id="precio-venta" required>
                                </div>
                                <div class="form-group w-100">
                                    <label for="precio-proveedor">Precio Proveedor:</label>
                                    <input type="text" class="form-control" id="precio-proveedor" required>
                                </div>
                            </div>
                         
                            <div class="d-flex flex-row gap-3">
                                <div class="form-group w-100">
                                    <label for="maneja-inventario">Maneja Inventario:</label>
                                    <select class="form-select" id="maneja-inventario" required>
                                        <option selected value="">-- Selecciona --</option>
                                        <option value="1">Sí</option>
                                        <option value="0">No</option>
                                        <option value="3">Es Servicio</option>
                                    </select>
                                </div>
                                <div class="form-group w-100">
                                    <label for="producto-variable">Producto Variable:</label>
                                    <select class="form-select" id="producto-variable" required>
                                        <option selected value="">-- Selecciona --</option>
                                        <option value="1">Sí</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>
                                <!-- <div class="form-group w-100">
                                    <label for="producto-privado">Producto privado:</label>
                                    <select class="form-select" id="producto-privado">
                                        <option selected>-- Selecciona --</option>
                                        <option value="1">Sí</option>
                                        <option value="0">No</option>
                                    </select>
                                </div> -->
                            </div>
                            <div class="d-flex flex-row gap-3">
                                <div class="form-group w-100">
                                    <label for="stock-inicial">Stock Inicial:</label>
                                    <input type="text" class="form-control" id="stock-inicial" required>
                                </div>
                                <div class="form-group w-100" id="bodega-field">
                                    <label for="bodega">Bodega:</label>
                                    <select class="form-select" id="bodega">
                                        <option value="0" selected>-- Selecciona Bodega --</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary" id="guardar_producto">Guardar</button>
            </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const productoVariableSelect = document.getElementById('producto-variable');
        const manejaInventarioSelect = document.getElementById('maneja-inventario');
        const bodegaField = document.getElementById('bodega-field');
        const precioReferencialCheckbox = document.getElementById('precio-referencial');
        const precioReferencialInput = document.getElementById('precio-referencial-valor');


        /* function toggleBodegaField() {
            if (manejaInventarioSelect.value === '1' && productoVariableSelect.value === '0') { // 1 para "Sí" y 2 para "No"
                bodegaField.classList.remove('hidden-field');
            } else {
                bodegaField.classList.add('hidden-field');
            }
        } */

        function togglePrecioReferencialInput() {
            precioReferencialInput.disabled = !precioReferencialCheckbox.checked;
        }

        /* productoVariableSelect.addEventListener('change', function() {
            toggleBodegaField();
        }); */

        /* manejaInventarioSelect.addEventListener('change', toggleBodegaField); */
        precioReferencialCheckbox.addEventListener('change', togglePrecioReferencialInput);

        /* toggleBodegaField(); */
        togglePrecioReferencialInput(); // Llama a la función al cargar la página para ajustar la visibilidad inicial
    });

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

            var maneja_inventario = $('#maneja-inventario').val();
            var producto_variable = $('#producto-variable').val();
            var categoria = $('#categoria').val();
            var bodega = $('#bodega').val();

            if (maneja_inventario == "") {
                toastr.error(
                    "Falta llenar Manjero de Inventario",
                    "NOTIFICACIÓN", {
                        positionClass: "toast-bottom-center"
                    }
                );
                return
            }

            if (producto_variable == "") {
                toastr.error(
                    "Falta llenar Producto Variable",
                    "NOTIFICACIÓN", {
                        positionClass: "toast-bottom-center"
                    }
                );
                return
            }

            if (categoria == "") {
                toastr.error(
                    "Falta llenar la categoria",
                    "NOTIFICACIÓN", {
                        positionClass: "toast-bottom-center"
                    }
                );
                return
            }

            if (bodega == "0") {
                toastr.error(
                    "Falta llenar la bodega",
                    "NOTIFICACIÓN", {
                        positionClass: "toast-bottom-center"
                    }
                );
                return
            }


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
            formData.append('enlace_funnelish', $('#enlace_funnelish').val());

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
    
    function formato() {
    let formatoSeleccionado = $("#formato-pagina").val();
    //alert(formatoSeleccionado);
    if (formatoSeleccionado == '3') {
        $("#funnelish").show();
    } else {
        $("#funnelish").hide();
    }
}
</script>