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

<div class="modal fade" id="editar_comboModal" tabindex="-1" aria-labelledby="editar_comboModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editar_comboModalLabel"><i class="fas fa-edit"></i> Nuevo Combo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editar_combo_form" enctype="multipart/form-data">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="editar_nombre_combo" class="form-label">Nombre del combo</label>
                            <input type="text" class="form-control" id="editar_nombre_combo" placeholder="nombre del combo">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="select_productos_editar" class="form-label">Producto</label>
                            <select class="form-select" id="select_productos_editar" style="width: 100%">
                                <option value="" selected>--- Elegir producto ---</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="imagen" class="form-label">Imagen</label>
                            <input type="file" class="form-control" id="imagen_editar" name="imagen_editar" accept="image/*">
                            <img id="preview-imagen_editar" src="#" alt="Vista previa de la imagen" style="display: none; margin-top: 10px; max-width: 100%;">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary" id="guardar_combo">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Función para reiniciar el formulario
        function resetForm() {
            $('#editar_combo_form')[0].reset();
            $('#preview-imagen_editar').attr('src', '#').hide();
        }

        // Evento para reiniciar el formulario cuando se cierre el modal
        $('#editar_comboModal').on('hidden.bs.modal', function() {
            var button = document.getElementById('guardar_combo');
            button.disabled = false; // Desactivar el botón
            resetForm();
        });

        // Vista previa de la imagen
        $('#imagen_editar').change(function() {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#preview-imagen_editar').attr('src', e.target.result).show();
            }
            reader.readAsDataURL(this.files[0]);
        });

        $('#editar_combo_form').submit(function(event) {
            event.preventDefault(); // Evita que el formulario se envíe de la forma tradicional

            var button = document.getElementById('guardar_combo');
            button.disabled = true; // Desactivar el botón

            // Crea un objeto FormData
            var formData = new FormData();
            formData.append('nombre', $('#editar_nombre_combo').val());
            formData.append('id_producto_combo', $('#select_productos_editar').val());
            formData.append('imagen', $('#imagen_editar')[0].files[0]);

            // Realiza la solicitud AJAX
            $.ajax({
                url: '' + SERVERURL + 'Productos/editarcombos',
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

                        $('#editar_comboModal').modal('hide');
                        resetForm();
                        initDataTableCombos();
                    }
                },
                error: function(error) {
                    alert('Hubo un error al editar el producto');
                    console.log(error);
                }
            });
        });
    });

    /* llenar select productos editar */
    document.addEventListener("DOMContentLoaded", () => {
        // Inicializa Select2 en el select
        $("#select_productos_editar").select2({
            placeholder: "--- Elegir producto ---",
            allowClear: true,
            dropdownAutoWidth: true, // Habilita auto width para ajustarse correctamente
            templateResult: formatProduct, // Formato para mostrar los productos en el dropdown
            templateSelection: formatProductSelection, // Formato para mostrar la selección
            dropdownParent: $("#agregar_comboModal"), // Forzar que el dropdown se muestre dentro del modal
        });

        // Cuando se abra el modal, carga los productos
        $("#agregar_comboModal").on("shown.bs.modal", function() {
            fetchProductos();
        });

        function fetchProductos() {
            fetch(SERVERURL + "productos/obtener_productos")
                .then((response) => response.json())
                .then((data) => {
                    const selectProductos = $("#select_productos_editar");
                    selectProductos.empty(); // Limpia el select
                    selectProductos.append(new Option("--- Elegir producto ---", ""));

                    // Llenar el select con los datos recibidos
                    data.forEach((item) => {
                        const option = new Option(
                            `${item.nombre_producto} - $${item.pvp}`, // Lo que ves en el select
                            item.id_producto, // El valor del option
                            false, // No seleccionado por defecto
                            false // No preseleccionado
                        );
                        option.setAttribute("data-image", SERVERURL + item.image_path); // Añadir imagen como atributo
                        selectProductos.append(option);
                    });

                    // Refrescar Select2
                    selectProductos.trigger("change");
                })
                .catch((error) => console.error("Error al cargar productos:", error));
        }

        function formatProduct(product) {
            if (!product.id) {
                return product.text;
            }

            // Obtén la imagen desde los datos
            let imgPath = $(product.element).data("image") ?
                $(product.element).data("image") :
                "default-image-path.jpg";

            var $product = $(
                `<div class='select2-result-repository clearfix'>
                <div class='select2-result-repository__avatar'>
                    <img src='${imgPath}' alt='Imagen del producto' style='width: 50px; height: 50px; margin-right: 10px;'/>
                </div>
                <div class='select2-result-repository__meta'>
                    <div class='select2-result-repository__title'>${product.text}</div>
                </div>
            </div>`
            );

            return $product;
        }

        function formatProductSelection(product) {
            return product.text || product.nombre_producto;
        }

        // Reposiciona el dropdown de select2 cuando el modal está abierto
        $("#select_productos_editar").on("select2:open", function() {
            const modal = $("#agregar_comboModal");
            const select2Dropdown = $(".select2-container .select2-dropdown");

            // Asegura que el dropdown esté correctamente posicionado dentro del modal
            select2Dropdown.position({
                my: "top",
                at: "bottom",
                of: $("#select_productos_editar"),
            });
        });
    });
    /* Fin llenar select productos editar */
</script>