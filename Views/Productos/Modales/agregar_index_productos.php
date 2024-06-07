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
    <div class="modal-dialog modal-xl">
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
                                        <input type="text" class="form-control" id="nombre">
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
                                            <select class="form-select" id="categoria">
                                                <option selected>-- Selecciona Categoría --</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Formato:</label>
                                            <div class="d-flex">
                                                <img src="formato1.png" alt="Formato 1" class="me-2">
                                                <img src="formato2.png" alt="Formato 2">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column w-100">
                                        <div class="form-group">
                                            <label for="formato-pagina">Formato Página Productos:</label>
                                            <select class="form-select" id="formato-pagina">
                                                <option selected>-- Selecciona --</option>
                                                <option value="1">Formato 1</option>
                                                <option value="2">Formato 2</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
                    <div class="tab-pane fade" id="precios-stock" role="tabpanel" aria-labelledby="precios-stock-tab">
                        <div class="d-flex flex-column">
                            <div class="d-flex flex-row gap-3">
                                <div class="form-group w-100">
                                    <label for="costo">Costo:</label>
                                    <input type="text" class="form-control" id="costo">
                                </div>
                                <div class="form-group w-100">
                                    <label for="utilidad">Utilidad %:</label>
                                    <input type="text" class="form-control" id="utilidad">
                                </div>
                            </div>
                            <div class="d-flex flex-row gap-3">
                                <div class="form-group w-100">
                                    <label for="precio-proveedor">Precio Proveedor:</label>
                                    <input type="text" class="form-control" id="precio-proveedor">
                                </div>
                                <div class="form-group w-100">
                                    <label for="precio-venta">Precio de Venta (Sugerido):</label>
                                    <input type="text" class="form-control" id="precio-venta">
                                </div>
                            </div>
                            <div class="d-flex flex-row gap-3">
                                <div class="form-group w-100">
                                    <label for="precio-referencial">¿Precio Referencial?</label>
                                    <input type="checkbox" class="form-check-input" id="precio-referencial">
                                    <input type="text" class="form-control mt-2" id="precio-referencial-valor" disabled>
                                </div>
                                <div class="form-group w-100">
                                    <label for="maneja-inventario">Maneja Inventario:</label>
                                    <select class="form-select" id="maneja-inventario">
                                        <option selected>-- Selecciona --</option>
                                        <option value="1">Sí</option>
                                        <option value="2">No</option>
                                    </select>
                                </div>
                                <div class="form-group w-100">
                                    <label for="producto-variable">Producto Variable:</label>
                                    <select class="form-select" id="producto-variable">
                                        <option selected>-- Selecciona --</option>
                                        <option value="1">Sí</option>
                                        <option value="2">No</option>
                                    </select>
                                </div>
                            </div>
                            <div class="d-flex flex-row gap-3">
                                <div class="form-group w-100">
                                    <label for="stock-inicial">Stock Inicial:</label>
                                    <input type="text" class="form-control" id="stock-inicial">
                                </div>
                                <div class="form-group w-100">
                                    <label for="stock-minimo">Stock Mínimo:</label>
                                    <input type="text" class="form-control" id="stock-minimo">
                                </div>
                                <div class="form-group w-100 hidden-field" id="bodega-field">
                                    <label for="bodega">Bodega:</label>
                                    <select class="form-select" id="bodega">
                                        <option selected>-- Selecciona Bodega --</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary">Guardar</button>
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


        function toggleBodegaField() {
            if (manejaInventarioSelect.value === '1' && productoVariableSelect.value === '2') { // 1 para "Sí" y 2 para "No"
                bodegaField.classList.remove('hidden-field');
            } else {
                bodegaField.classList.add('hidden-field');
            }
        }

        function togglePrecioReferencialInput() {
            precioReferencialInput.disabled = !precioReferencialCheckbox.checked;
        }

        productoVariableSelect.addEventListener('change', function() {
            toggleBodegaField();
        });

        manejaInventarioSelect.addEventListener('change', toggleBodegaField);
        precioReferencialCheckbox.addEventListener('change', togglePrecioReferencialInput);

        toggleBodegaField(); // Llama a la función al cargar la página para ajustar la visibilidad inicial
        togglePrecioReferencialInput(); // Llama a la función al cargar la página para ajustar la visibilidad inicial
    });

    //enviar datos a base de datos
    $(document).ready(function() {
        $('#agregar_producto_form').submit(function(event) {
            event.preventDefault(); // Evita que el formulario se envíe de la forma tradicional

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
            formData.append('image_path', ''); // Asumiendo que no hay imagen por ahora
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
                    alert('Producto agregado exitosamente');
                    console.log(response);
                },
                error: function(error) {
                    alert('Hubo un error al agregar el producto');
                    console.log(error);
                }
            });
        });
    });

    //cargar select de bodega 
    $(document).ready(function() {
        // Realiza la solicitud AJAX para obtener la lista de bodegas
        $.ajax({
            url: '' + SERVERURL + 'productos/listar_bodegas',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                // Asegúrate de que la respuesta es un array
                if (Array.isArray(response)) {
                    response.forEach(function(bodega) {
                        // Agrega una nueva opción al select por cada bodega
                        $('#bodega').append(new Option(bodega.nombre, bodega.id));
                    });
                } else {
                    console.log('La respuesta de la API no es un array:', response);
                }
            },
            error: function(error) {
                console.error('Error al obtener la lista de bodegas:', error);
            }
        });
    });

    //cargar select categoria
    $(document).ready(function() {
        // Realiza la solicitud AJAX para obtener la lista de categorias
        $.ajax({
            url: '' + SERVERURL + 'productos/cargar_categorias',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                // Asegúrate de que la respuesta es un array
                if (Array.isArray(response)) {
                    response.forEach(function(categoria) {
                        // Agrega una nueva opción al select por cada categoria
                        $('#categoria').append(new Option(categoria.nombre_linea, categoria.id_linea));
                    });
                } else {
                    console.log('La respuesta de la API no es un array:', response);
                }
            },
            error: function(error) {
                console.error('Error al obtener la lista de categorias:', error);
            }
        });
    });
</script>