<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario Producto</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .form-group {
            margin-bottom: 15px;
        }

        .hidden-tab {
            display: none !important;
        }

        .hidden-field {
            display: none;
        }
    </style>
</head>

<body>
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
                                                    <option selected>-- Selecciona Categoría --</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="d-flex flex-column w-100">
                                            <div class="form-group">
                                                <label for="formato-pagina">Formato Página Productos:</label>
                                                <select class="form-select" id="formato-pagina" required>
                                                    <option selected>-- Selecciona --</option>
                                                    <option value="1">Formato 1</option>
                                                    <option value="2">Formato 2</option>
                                                </select>
                                            </div>
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
                                        <label for="costo">Costo:</label>
                                        <input type="text" class="form-control" id="costo" required>
                                    </div>
                                    <div class="form-group w-100">
                                        <label for="precio-proveedor">Precio Proveedor:</label>
                                        <input type="text" class="form-control" id="precio-proveedor" required>
                                    </div>
                                </div>
                                <div class="d-flex flex-row gap-3">
                                    <div class="form-group w-100">
                                        <label for="precio-venta">Precio de Venta (Sugerido):</label>
                                        <input type="text" class="form-control" id="precio-venta" required>
                                    </div>
                                    <div class="form-group w-100">
                                        <label for="precio-referencial">¿Precio Referencial?</label>
                                        <input type="checkbox" class="form-check-input" id="precio-referencial">
                                        <input type="text" class="form-control mt-2" id="precio-referencial-valor" disabled>
                                    </div>
                                </div>
                                <div class="d-flex flex-row gap-3">
                                    <div class="form-group w-100">
                                        <label for="maneja-inventario">Maneja Inventario:</label>
                                        <select class="form-select" id="maneja-inventario" required>
                                            <option selected>-- Selecciona --</option>
                                            <option value="1">Sí</option>
                                            <option value="0">No</option>
                                        </select>
                                    </div>
                                    <div class="form-group w-100">
                                        <label for="producto-variable">Producto Variable:</label>
                                        <select class="form-select" id="producto-variable" required>
                                            <option selected>-- Selecciona --</option>
                                            <option value="1">Sí</option>
                                            <option value="0">No</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="d-flex flex-row gap-3">
                                    <div class="form-group w-100">
                                        <label for="stock-inicial">Stock Inicial:</label>
                                        <input type="text" class="form-control" id="stock-inicial" required>
                                    </div>
                                    <div class="form-group w-100 hidden-field" id="bodega-field">
                                        <label for="bodega">Bodega:</label>
                                        <select class="form-select" id="bodega" required>
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

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.6.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const productoVariableSelect = document.getElementById('producto-variable');
            const manejaInventarioSelect = document.getElementById('maneja-inventario');
            const bodegaField = document.getElementById('bodega-field');
            const precioReferencialCheckbox = document.getElementById('precio-referencial');
            const precioReferencialInput = document.getElementById('precio-referencial-valor');

            function toggleBodegaField() {
                if (manejaInventarioSelect.value === '1' && productoVariableSelect.value === '0') {
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

            toggleBodegaField();
            togglePrecioReferencialInput();
        });

        $(document).ready(function() {
            function resetForm() {
                $('#agregar_producto_form')[0].reset();
                $('#bodega-field').addClass('hidden-field');
                $('#precio-referencial-valor').prop('disabled', true);
            }

            $('#agregar_productoModal').on('hidden.bs.modal', function() {
                var button = document.getElementById('guardar_producto');
                button.disabled = false;
            });

            $('#agregar_producto_form').submit(function(event) {
                event.preventDefault();

                var button = document.getElementById('guardar_producto');
                button.disabled = true;

                var valid = true;
                var invalidTabs = [];

                $('#datos-basicos').find('input, select').each(function() {
                    if (!this.checkValidity()) {
                        valid = false;
                        invalidTabs.push('datos-basicos-tab');
                    }
                });

                $('#precios-stock').find('input, select').each(function() {
                    if (!this.checkValidity()) {
                        valid = false;
                        invalidTabs.push('precios-stock-tab');
                    }
                });

                if (!valid) {
                    invalidTabs = [...new Set(invalidTabs)];
                    invalidTabs.forEach(function(tab) {
                        $('#' + tab).tab('show');
                        toastr.error('Por favor, complete todos los campos requeridos en esta sección.', 'Error de Validación', {
                            positionClass: 'toast-bottom-center'
                        });
                    });
                    button.disabled = false;
                    return;
                }

                var formData = new FormData();
                formData.append('codigo_producto', $('#codigo').val());
                formData.append('nombre_producto', $('#nombre').val());
                formData.append('descripcion_producto', $('#descripcion').val());
                formData.append('id_linea_producto', $('#categoria').val());
                formData.append('inv_producto', $('#maneja-inventario').val());
                formData.append('producto_variable', $('#producto-variable').val());
                formData.append('costo_producto', $('#costo').val());
                formData.append('aplica_iva', 1);
                formData.append('estado_producto', 1);
                formData.append('date_added', new Date().toISOString().split('T')[0]);
                formData.append('formato', $('#formato-pagina').val());
                formData.append('drogshipin', 0);
                formData.append('destacado', 0);
                formData.append('stock_inicial', $('#stock-inicial').val());
                formData.append('bodega', $('#bodega').val());
                formData.append('pcp', $('#precio-proveedor').val());
                formData.append('pvp', $('#precio-venta').val());
                formData.append('pref', $('#precio-referencial-valor').val());

                $.ajax({
                    url: '' + SERVERURL + 'productos/agregar_producto',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        response = JSON.parse(response);
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
</body>

</html>