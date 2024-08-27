<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Productos/css/combos_style.php'; ?>
<?php require_once './Views/Productos/Modales/agregar_combo.php'; ?>
<?php require_once './Views/Productos/Modales/editar_combo.php'; ?>

<div class="custom-container-fluid">
    <div class="container mt-5" style="max-width: 1600px;">
        <h2 class="text-center mb-4">Gestion de Productos Privados</h2>
        <!-- <div class="filtros_producos justify-content-between align-items-center mb-3"></div>
        </div> -->
        <div class="left_right gap-2">
            <div class="table-responsive left">
                <div class="justify-content-between align-items-center mb-3">
                    <div class="d-flex">
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#agregar_comboModal"><i class="fas fa-plus"></i> Agregar</button>
                    </div>
                </div>
                <!-- <table class="table table-bordered table-striped table-hover"> -->
                <table id="datatable_combos" class="table table-striped" style="min-width: 100%;">
                    <thead>
                        <tr>
                            <th class="centered">ID</th>
                            <th class="centered"></th>
                            <th class="centered">Nombre combo</th>
                            <th class="centered">Nombre Producto</th>
                            <th class="centered">Visualizar combo</th>
                            <th class="centered">Accion</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody_combos"></tbody>
                </table>
            </div>
            <div class="right gap-2 hidden" id="inventarioSection">
                <div class="card" style="height: auto; padding: 10px;">
                    <div class="form-group" id="tiendas-field">
                        <input type="hidden" id="id_producto_privado" name="id_producto_privado">
                        <label for="tiendas">Tiendas:</label>
                        <select class="form-select" id="select_tiendas">
                            <option value="0" selected>Selecciona tiendas</option>
                        </select>
                    </div>

                    <!-- Contenedor de Información de la Tienda -->
                    <div id="informacion_tienda" class="card mt-3 p-3" style="display: none; max-width: 100%; border: 1px solid #ddd; border-radius: 5px;">
                        <img src="tu-imagen.png" alt="Producto" id="image_tienda" class="img-fluid rounded" style="max-height: 200px; margin-bottom: 15px;">

                        <h6 class="text-center mb-3"><strong><span id="nombre_tienda"></span></strong></h6>

                        <hr class="mb-3">

                        <div class="d-flex flex-column gap-2">
                            <label for="url"><strong>URL: </strong><span id="url"></span></label>
                            <label for="telefono"><strong>Teléfono: </strong><span id="telefono"></span></label>
                            <label for="correo"><strong>Correo: </strong><span id="correo"></span></label>
                        </div>

                        <button class="btn btn-primary btn-block mt-3" onclick="agregar_tienda()">Agregar tienda</button>
                    </div>
                </div>
                <div class="table-responsive">
                    <!-- <table class="table table-bordered table-striped table-hover"> -->
                    <table id="datatable_stockIndividual" class="table table-striped" style="min-width: 100%;">
                        <!-- <caption>
                    DataTable.js Demo
                </caption> -->
                        <thead>
                            <tr>
                                <th class="centered">Nombre tienda</th>
                                <th class="centered">Correo</th>
                                <th class="centered">Telefono</th>
                                <th class="centered">URL</th>
                                <th class="centered">Accción</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody_stockIndividual"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    /* llenar select de productos */
    document.addEventListener("DOMContentLoaded", () => {
        // Inicializa Select2 en el select
        $("#select_productos").select2({
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
                    const selectProductos = $("#select_productos");
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
        $("#select_productos").on("select2:open", function() {
            const modal = $("#agregar_comboModal");
            const select2Dropdown = $(".select2-container .select2-dropdown");

            // Asegura que el dropdown esté correctamente posicionado dentro del modal
            select2Dropdown.position({
                my: "top",
                at: "bottom",
                of: $("#select_productos"),
            });
        });
    });
    /* Fin llenar select productos */
    /* llenar select productos editar */
    document.addEventListener("DOMContentLoaded", () => {
        // Función para inicializar Select2 en el modal de edición
        function initializeSelect2() {
            // Destruir Select2 si ya existe en el select_productos_editar
            if ($.fn.select2 && $("#select_productos_editar").hasClass("select2-hidden-accessible")) {
                $("#select_productos_editar").select2('destroy');
            }

            // Inicializar Select2 en el select_productos_editar
            $("#select_productos_editar").select2({
                placeholder: "--- Elegir producto ---",
                allowClear: true,
                dropdownAutoWidth: true,
                templateResult: formatProduct, // Formato para mostrar los productos en el dropdown
                templateSelection: formatProductSelection, // Formato para mostrar la selección
                dropdownParent: $("#editar_comboModal") // Especificar el modal correcto
            });
        }

        // Llamar a la función de inicialización cuando se abra el modal de edición
        $("#editar_comboModal").on("shown.bs.modal", function() {
            fetchProductos(); // Llamar a la función para llenar el select
        });

        function fetchProductos() {
            fetch(SERVERURL + "productos/obtener_productos")
                .then((response) => response.json())
                .then((data) => {
                    const selectProductos = $("#select_productos_editar");
                    selectProductos.empty(); // Limpiar el select
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

                    // Forzar actualización de Select2
                    initializeSelect2(); // Inicializar Select2 después de llenar el select
                    selectProductos.trigger("change"); // Asegurar que el select se actualiza correctamente
                })
                .catch((error) => console.error("Error al cargar productos:", error));
        }

        function formatProduct(product) {
            if (!product.id) {
                return product.text;
            }

            // Obtener la imagen desde los datos
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

        // Reposicionar el dropdown de Select2 cuando se abre
        $("#select_productos_editar").on("select2:open", function() {
            const modal = $("#editar_comboModal");
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
<script src="<?php echo SERVERURL ?>/Views/Productos/js/combos.js"></script>
<?php require_once './Views/templates/footer.php'; ?>