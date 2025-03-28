<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Productos/css/agregar_productos_style.php'; ?>

<div class="container-fluid my-3">


    <div class="row">
        <!-- Sidebar de navegación -->
        <div class="col-md-3">
            <ul class="nav flex-column nav-pills" id="menu">
                <li class="nav-item">
                    <a class="nav-link active" data-section="general" href="#">Información General</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-section="bodega" href="#">Bodega</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-section="imagen" href="#">Imagenes del producto</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-section="variable" href="#">Variables</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-section="privados" href="#">Productos privados</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-section="tiendas" href="#">Tienda Virtual</a>
                </li>
            </ul>
        </div>

        <!-- Contenido dinámico -->
        <div class="col-md-9">
            <!-- Sección General -->
            <div id="general" class="content-section">
                <h4>Información General</h4>
                <div class="mb-3">
                    <label class="form-label">Código del producto</label>
                    <input type="text" id="codigo_producto" name="codigo_producto" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Nombre del producto</label>
                    <input type="text" id="nombre_producto" name="nombre_producto" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Descripción</label>
                    <textarea name="descripcion" id="descripcion" class="form-control" cols="20" rows="4"></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Categoría</label>
                    <select name="id_linea_producto" id="id_linea_producto" class="form-control">
                        <option value="0"> -- Seleccione una categoría --</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Precio de Venta</label>
                    <input type="number" id="pvp" name="pvp" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">Precio como Proveedor</label>
                    <input type="number" id="pvp" name="pvp" class="form-control">
                </div>

            </div>

            <!-- Sección Existencia -->
            <div id="bodega" class="content-section hidden">
                <h4>Bodega</h4>
                <p>Aquí se administrará la cantidad de productos en inventario.</p>
            </div>

            <!-- Sección Imagen -->
            <div id="imagen" class="content-section hidden">
                <h4>Imagen del producto</h4>
                <input type="file" class="form-control">
            </div>

            <!-- Sección Recursos -->
            <div id="variable" class="content-section hidden">
                <h4>¿Este producto tiene variedades?</h4>
            </div>

            <!-- Sección Productos Privados -->
            <div id="privados" class="content-section hidden">
                <h4>Productos Privados</h4>
                <input type="checkbox" class="form-check-input" id="privateProduct">
                <label class="form-check-label" for="privateProduct">Marcar como privado</label>
            </div>

            <!-- Sección Garantías -->
            <div id="tiendas" class="content-section hidden">
                <h4>Tiendas</h4>
                <p>¿Mostrar producto en la tienda virtual?</p>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const menuItems = document.querySelectorAll("#menu .nav-link");
        const sections = document.querySelectorAll(".content-section");

        menuItems.forEach(item => {
            item.addEventListener("click", function (e) {
                e.preventDefault();

                // Quitar la clase activa de todos los enlaces
                menuItems.forEach(link => link.classList.remove("active"));
                this.classList.add("active");

                // Ocultar todas las secciones
                sections.forEach(section => section.classList.add("hidden"));

                // Mostrar la sección correspondiente
                const sectionId = this.getAttribute("data-section");
                document.getElementById(sectionId).classList.remove("hidden");
            });
        });
    });
</script>
<?php require_once './Views/templates/footer.php'; ?>
