<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Agregar Producto</title>

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

        .hidden-field {
            display: none;
        }
    </style>
</head>

<body>

    <!-- Modal con estilo Wizard de 2 pasos -->
    <div class="modal fade" id="agregar_productoModal" tabindex="-1" aria-labelledby="agregar_productoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <!-- Encabezado del modal -->
                <div class="modal-header">
                    <h5 class="modal-title" id="agregar_productoModalLabel">
                        <i class="fas fa-edit"></i> Nuevo Producto
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!-- Cuerpo del modal, con pasos -->
                <div class="modal-body">
                    <!-- Formulario único que contiene ambos pasos -->
                    <form id="agregar_producto_form">

                        <!-- Paso 1: Datos Básicos -->
                        <div class="wizard-step active" id="datos-basicos">
                            <h5 class="mb-3"><strong>Datos Básicos</strong></h5>
                            <div class="d-flex flex-column">
                                <div class="d-flex flex-row gap-3">
                                    <div class="form-group w-100">
                                        <label for="codigo">Código:</label>
                                        <input type="text" class="form-control" id="codigo" required>
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
                                            <label for="enlace_funnelish">Enlace de Funnelish:</label>
                                            <input type="text" class="form-control" id="enlace_funnelish">
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
                                </div>
                            </div>
                        </div>

                        <!-- Paso 2: Precios y Stock -->
                        <div class="wizard-step" id="precios-stock">
                            <h5 class="mb-3"><strong>Precios y Stock</strong></h5>
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

                    </form>
                </div>

                <!-- Footer del modal, con botones que cambian según el paso -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="closeBtn">Cerrar</button>
                    <!-- Botones para controlar el wizard -->
                    <button type="button" class="btn btn-primary" id="previousStepBtn" style="display:none;">Anterior</button>
                    <button type="button" class="btn btn-primary" id="nextStepBtn">Siguiente</button>
                    <!-- Botón Guardar que envía el formulario (solo visible en el paso 2) -->
                    <button type="submit" form="agregar_producto_form" class="btn btn-success" id="guardar_producto" style="display:none;">Guardar</button>
                </div>

            </div>
        </div>
    </div>

    <script>
        // =============== CONTROL DEL WIZARD ===============
        document.addEventListener('DOMContentLoaded', function() {
            let currentStep = 1; // Empieza en 1 (Datos Básicos)

            const step1 = document.getElementById('datos-basicos');
            const step2 = document.getElementById('precios-stock');
            const previousBtn = document.getElementById('previousStepBtn');
            const nextBtn = document.getElementById('nextStepBtn');
            const saveBtn = document.getElementById('guardar_producto');

            // Mostrar el paso actual y ocultar los demás
            function showStep(step) {
                switch (step) {
                    case 1:
                        step1.classList.add('active');
                        step2.classList.remove('active');

                        // Botones visibles
                        previousBtn.style.display = 'none'; // No se puede ir "atrás" desde el primer paso
                        nextBtn.style.display = 'inline-block';
                        saveBtn.style.display = 'none'; // No se muestra Guardar en el primer paso
                        break;
                    case 2:
                        step1.classList.remove('active');
                        step2.classList.add('active');

                        // Botones visibles
                        previousBtn.style.display = 'inline-block';
                        nextBtn.style.display = 'none';
                        saveBtn.style.display = 'inline-block';
                        break;
                }
            }

            // Botón "Siguiente"
            nextBtn.addEventListener('click', function() {
                // Ejemplo: Validar que "codigo" y "nombre" no vengan vacíos
                if (!document.getElementById('codigo').value.trim()) {
                    toastr.error("Falta el código del producto", "NOTIFICACIÓN", {
                        positionClass: "toast-bottom-center"
                    });
                    return;
                }
                if (!document.getElementById('nombre').value.trim()) {
                    toastr.error("Falta el nombre del producto", "NOTIFICACIÓN", {
                        positionClass: "toast-bottom-center"
                    });
                    return;
                }
                if (!document.getElementById('categoria').value.trim()) {
                    toastr.error("Falta seleccionar una categoría", "NOTIFICACIÓN", {
                        positionClass: "toast-bottom-center"
                    });
                    return;
                }

                // Si todo ok, avanza
                currentStep = 2;
                showStep(currentStep);
            });

            // Botón "Anterior"
            previousBtn.addEventListener('click', function() {
                currentStep = 1;
                showStep(currentStep);
            });

            // Iniciar mostrando el paso 1
            showStep(currentStep);
        });


        // 1. Ocultar/Mostrar campo "bodega" si es necesario
        // 2. Controlar "funnelish"
        // 3. Manejar envío AJAX
        document.addEventListener('DOMContentLoaded', function() {
            const productoVariableSelect = document.getElementById('producto-variable');
            const manejaInventarioSelect = document.getElementById('maneja-inventario');
            const bodegaField = document.getElementById('bodega-field');
        });

        // Función para mostrar/ocultar el enlace funnelish
        function formato() {
            let formatoSeleccionado = document.getElementById("formato-pagina").value;
            if (formatoSeleccionado == '3') {
                document.getElementById("funnelish").style.display = 'block';
            } else {
                document.getElementById("funnelish").style.display = 'none';
            }
        }

        // =============== LÓGICA PARA ENVIAR EL FORMULARIO VIA AJAX ===============
        $(document).ready(function() {

            // Función para reiniciar el formulario
            function resetForm() {
                $('#agregar_producto_form')[0].reset();
            }

            // Reiniciar el formulario al cerrar el modal
            $('#agregar_productoModal').on('hidden.bs.modal', function() {
                var button = document.getElementById('guardar_producto');
                button.disabled = false; // Reactivar el botón
                resetForm();
            });

            // Submit del formulario
            $('#agregar_producto_form').submit(function(event) {
                event.preventDefault(); // Evita el envío normal

                // Validaciones del paso 2
                var maneja_inventario = $('#maneja-inventario').val();
                var producto_variable = $('#producto-variable').val();
                var bodega = $('#bodega').val();

                if (maneja_inventario == "") {
                    toastr.error("Falta llenar Maneja Inventario", "NOTIFICACIÓN", {
                        positionClass: "toast-bottom-center"
                    });
                    return;
                }

                if (producto_variable == "") {
                    toastr.error("Falta llenar Producto Variable", "NOTIFICACIÓN", {
                        positionClass: "toast-bottom-center"
                    });
                    return;
                }

                if (bodega == "0") {
                    toastr.error("Falta seleccionar bodega", "NOTIFICACIÓN", {
                        positionClass: "toast-bottom-center"
                    });
                    return;
                }

                var button = document.getElementById('guardar_producto');
                button.disabled = true; // Desactivar el botón mientras se envía

                // Crea un objeto FormData con todos los campos necesarios
                var formData = new FormData();
                formData.append('codigo_producto', $('#codigo').val());
                formData.append('nombre_producto', $('#nombre').val());
                formData.append('descripcion_producto', $('#descripcion').val());
                formData.append('id_linea_producto', $('#categoria').val());
                formData.append('envio_prioritario', 0);
                formData.append('inv_producto', $('#maneja-inventario').val());
                formData.append('producto_variable', $('#producto-variable').val());
                formData.append('aplica_iva', 1); // Si aplica IVA
                formData.append('estado_producto', 1); // Activo
                formData.append('date_added', new Date().toISOString().split('T')[0]);
                formData.append('formato', $('#formato-pagina').val());
                formData.append('drogshipin', 0); // Ejemplo
                formData.append('destacado', 0);
                formData.append('stock_inicial', $('#stock-inicial').val());
                formData.append('bodega', $('#bodega').val());
                formData.append('pcp', $('#precio-proveedor').val());
                formData.append('pvp', $('#precio-venta').val());
                formData.append('pref', ''); // Si tienes precio referencial
                formData.append('enlace_funnelish', $('#enlace_funnelish').val());

                // Llamada AJAX (ajusta la URL según tu estructura)
                $.ajax({
                    url: '' + SERVERURL + 'productos/agregar_producto', // Ajusta con tu ruta real
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        response = JSON.parse(response);

                        if (response.status == 500) {
                            toastr.error("EL PRODUCTO NO SE AGREGÓ CORRECTAMENTE", "NOTIFICACIÓN", {
                                positionClass: "toast-bottom-center"
                            });
                            button.disabled = false;
                        } else if (response.status == 200) {
                            toastr.success("PRODUCTO AGREGADO CORRECTAMENTE", "NOTIFICACIÓN", {
                                positionClass: "toast-bottom-center"
                            });
                            $('#agregar_productoModal').modal('hide');
                            // Llama a tu función para refrescar la tabla
                            initDataTableProductos();
                        }
                    },
                    error: function(error) {
                        alert('Hubo un error al agregar el producto');
                        console.log(error);
                        button.disabled = false; // Reactivar el botón en caso de error
                    }
                });
            });
        });
    </script>

</body>

</html>