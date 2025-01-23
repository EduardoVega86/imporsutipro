<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Editar Producto - Wizard Mejorado</title>
    <style>
        .form-group {
            margin-bottom: 15px;
        }

        /* Para ocultar temporalmente un paso */
        .wizard-step {
            display: none;
        }

        .wizard-step.active {
            display: block;
        }
    </style>
</head>

<body>

    <!-- Modal Edición de Producto con estilo Wizard de 2 pasos -->
    <div class="modal fade" id="editar_productoModal" tabindex="-1" aria-labelledby="editar_productoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <!-- Encabezado del modal -->
                <div class="modal-header">
                    <h5 class="modal-title" id="editar_productoModalLabel">
                        <i class="fas fa-edit"></i> Editar Producto
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!-- Cuerpo del modal (2 pasos en un mismo formulario) -->
                <div class="modal-body">
                    <form id="editar_producto_form">

                        <!-- Paso 1: Datos Básicos -->
                        <div class="wizard-step active" id="editar-datos-basicos">
                            <h5 class="mb-3"><strong>Datos Básicos</strong></h5>

                            <!-- Campo oculto para guardar el ID del producto a editar -->
                            <input type="hidden" id="editar_id_producto" name="id_producto">

                            <div class="d-flex flex-column">
                                <div class="d-flex flex-row gap-3">
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
                                                <option value="3">Funnelish</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div style="display: none;" id="funnelish_editar" class="flex-column w-100">
                                        <div class="form-group">
                                            <label for="editar-enlace_funnelish">Enlace de Funnelish:</label>
                                            <input type="text" class="form-control" id="editar-enlace_funnelish">
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex flex-row gap-3 mt-3">
                                    <div class="form-group">
                                        <label>Formato:</label>
                                        <div class="d-flex">
                                            <img src="https://new.imporsuitpro.com/public/img/formato_pro.jpg" alt="Formato" class="me-2" width="350px;">
                                        </div>
                                    </div>

                                    <div class="d-flex flex-column w-100">
                                        <div class="form-group">
                                            <label for="editar_envio_prioritario">Envío prioritario:</label>
                                            <select class="form-select" id="editar_envio_prioritario" required>
                                                <option selected value="">-- Selecciona --</option>
                                                <option value="1">Sí</option>
                                                <option value="0">No</option>
                                            </select>
                                        </div>
                                        <div class="alert alert-warning" role="alert">
                                            <strong>Atención:</strong> Esta opción convierte el producto en complemento logístico
                                            y no se vende solo, sino como un agregado.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Paso 2: Precios y Stock -->
                        <div class="wizard-step" id="editar-precios-stock">
                            <h5 class="mb-3"><strong>Precios y Stock</strong></h5>
                            <div class="d-flex flex-column">
                                <div class="d-flex flex-row gap-3">
                                    <div class="form-group w-100">
                                        <label for="editar_precio_proveedor">Precio Proveedor:</label>
                                        <input type="text" class="form-control" id="editar_precio_proveedor">
                                    </div>
                                    <div class="form-group w-100">
                                        <label for="editar_precio_venta">Precio de Venta (Sugerido):</label>
                                        <input type="text" class="form-control" id="editar_precio_venta">
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

                    </form> <!-- fin del formulario -->
                </div>

                <!-- Footer del modal, con botones que cambian según el paso -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="closeBtnEditar">Cerrar</button>
                    <!-- Botones para controlar el wizard -->
                    <button type="button" class="btn btn-primary" id="previousStepBtnEditar" style="display:none;">Anterior</button>
                    <button type="button" class="btn btn-primary" id="nextStepBtnEditar">Siguiente</button>
                    <!-- Botón Actualizar que envía el formulario (solo visible en el paso 2) -->
                    <button type="submit" form="editar_producto_form" class="btn btn-success" id="actualizar_producto_btn" style="display:none;">Actualizar</button>
                </div>

            </div>
        </div>
    </div>


    <script>
        // =============== CONTROL DEL WIZARD ===============
        document.addEventListener('DOMContentLoaded', function() {
            let currentStep = 1; // Empieza en 1 (Datos Básicos)

            const step1 = document.getElementById('editar-datos-basicos');
            const step2 = document.getElementById('editar-precios-stock');
            const previousBtn = document.getElementById('previousStepBtnEditar');
            const nextBtn = document.getElementById('nextStepBtnEditar');
            const updateBtn = document.getElementById('actualizar_producto_btn');

            // Función para mostrar/ocultar pasos
            function showStep(step) {
                switch (step) {
                    case 1:
                        step1.classList.add('active');
                        step2.classList.remove('active');

                        // Botones
                        previousBtn.style.display = 'none'; // No se puede ir atrás desde el primer paso
                        nextBtn.style.display = 'inline-block';
                        updateBtn.style.display = 'none'; // No se muestra el botón Actualizar en el primer paso
                        break;
                    case 2:
                        step1.classList.remove('active');
                        step2.classList.add('active');

                        // Botones
                        previousBtn.style.display = 'inline-block';
                        nextBtn.style.display = 'none';
                        updateBtn.style.display = 'inline-block';
                        break;
                }
            }

            // Botón "Siguiente" (valida brevemente el paso 1)
            nextBtn.addEventListener('click', function() {
                // Valida que haya datos mínimos
                if (!document.getElementById('editar_codigo').value.trim()) {
                    toastr.error("Falta el código del producto", "NOTIFICACIÓN", {
                        positionClass: "toast-bottom-center"
                    });
                    return;
                }
                if (!document.getElementById('editar_nombre').value.trim()) {
                    toastr.error("Falta el nombre del producto", "NOTIFICACIÓN", {
                        positionClass: "toast-bottom-center"
                    });
                    return;
                }
                if (!document.getElementById('editar_envio_prioritario').value.trim()) {
                    toastr.error("Falta indicar si es Envío prioritario", "NOTIFICACIÓN", {
                        positionClass: "toast-bottom-center"
                    });
                    return;
                }

                // Si todo OK, avanza al paso 2
                currentStep = 2;
                showStep(currentStep);
            });

            // Botón "Anterior"
            previousBtn.addEventListener('click', function() {
                currentStep = 1;
                showStep(currentStep);
            });

            // Inicialmente mostrar el paso 1
            showStep(currentStep);
        });


        // =============== LÓGICA PARA MOSTRAR/OCULTAR FUNNELISH EN EDITAR ===============
        function formato_editar() {
            let formatoSeleccionado = $("#editar_formato_pagina").val();
            if (formatoSeleccionado == '3') {
                $("#funnelish_editar").show();
            } else {
                $("#funnelish_editar").hide();
            }
        }


        // =============== ENVÍO DE DATOS (AJAX) PARA EDITAR PRODUCTO ===============
        $(document).ready(function() {

            // Al cerrar el modal, restablece el formulario si lo deseas
            $('#editar_productoModal').on('hidden.bs.modal', function() {
                // Aquí puedes limpiar los campos si lo necesitas
                // document.getElementById('editar_producto_form').reset();
            });

            // Manejar el submit (botón "Actualizar")
            $('#editar_producto_form').submit(function(event) {
                event.preventDefault(); // Evita envío tradicional

                // Realiza validaciones extra del paso 2 aquí si lo deseas
                var maneja_inventario = $('#editar_maneja_inventario').val();
                var producto_variable = $('#editar_producto_variable').val();
                var bodega = $('#editar_bodega').val();

                if (!maneja_inventario) {
                    toastr.error("Falta seleccionar Manejo de Inventario", "NOTIFICACIÓN", {
                        positionClass: "toast-bottom-center"
                    });
                    return;
                }
                if (!producto_variable) {
                    toastr.error("Falta seleccionar si es Producto Variable", "NOTIFICACIÓN", {
                        positionClass: "toast-bottom-center"
                    });
                    return;
                }
                if (bodega === "-- Selecciona Bodega --") {
                    toastr.error("Falta seleccionar la Bodega", "NOTIFICACIÓN", {
                        positionClass: "toast-bottom-center"
                    });
                    return;
                }

                // Bloquear el botón mientras se envía (opcional)
                let updateBtn = document.getElementById('actualizar_producto_btn');
                updateBtn.disabled = true;

                // Construye FormData
                var formData = new FormData();
                formData.append('id_producto', $('#editar_id_producto').val());
                formData.append('codigo_producto', $('#editar_codigo').val());
                formData.append('nombre_producto', $('#editar_nombre').val());
                formData.append('descripcion_producto', $('#editar_descripcion').val());
                formData.append('id_linea_producto', $('#editar_categoria').val());
                formData.append('inv_producto', $('#editar_maneja_inventario').val());
                formData.append('producto_variable', $('#editar_producto_variable').val());
                formData.append('aplica_iva', 1); // Asumiendo que aplica IVA
                formData.append('estado_producto', 1); // Activo
                formData.append('date_added', new Date().toISOString().split('T')[0]);
                formData.append('formato', $('#editar_formato_pagina').val());
                formData.append('drogshipin', 0);
                formData.append('destacado', 0);
                formData.append('stock_inicial', $('#editar_stock_inicial').val());
                formData.append('bodega', $('#editar_bodega').val());
                formData.append('pcp', $('#editar_precio_proveedor').val());
                formData.append('pvp', $('#editar_precio_venta').val());
                // Si tienes precio referencial, asegúrate de tener el campo o quítalo
                // formData.append('pref', $('#editar_precio_referencial').val());
                formData.append('envio_prioritario', $('#editar_envio_prioritario').val());
                formData.append('enlace_funnelish', $('#editar-enlace_funnelish').val());

                // Llamada AJAX (ajusta la URL según tu proyecto)
                $.ajax({
                    url: SERVERURL + 'productos/editar_producto', // Ajusta tu ruta
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        response = JSON.parse(response);

                        if (response.status == 500) {
                            toastr.error("EL PRODUCTO NO SE EDITÓ CORRECTAMENTE", "NOTIFICACIÓN", {
                                positionClass: "toast-bottom-center"
                            });
                            updateBtn.disabled = false;
                        } else if (response.status == 200) {
                            toastr.success("PRODUCTO EDITADO CORRECTAMENTE", "NOTIFICACIÓN", {
                                positionClass: "toast-bottom-center"
                            });
                            $('#editar_productoModal').modal('hide');
                            // Llama aquí tu función para recargar la tabla de productos
                            // initDataTableProductos();
                            reloadDataTableProductos();
                        }
                    },
                    error: function(error) {
                        alert('Hubo un error al editar el producto');
                        console.log(error);
                        updateBtn.disabled = false;
                    }
                });
            });
        });
    </script>

</body>

</html>