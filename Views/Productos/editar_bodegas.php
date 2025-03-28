<?php
require_once './Views/templates/header.php';

/**
 * @var int $data se recibe id desde el render
 */
$bodega_id = $data;
?>
<?php require_once './Views/Productos/css/editar_bodega_style.php'; ?>

    <div class="container-fluid my-3">
        <form id="frmBodega">
            <div class="row">
                <!-- Sidebar de navegación -->
                <div class="col-md-3">
                    <ul class="nav flex-column nav-pills" id="menu">
                        <li class="nav-item">
                            <a class="nav-link active" data-section="general" href="#">Información General</a>
                        </li>
                        <li id="menu_full" class="nav-item hidden-all ">
                            <a class="nav-link" data-section="fullfilment" href="#">Fullfilment</a>
                        </li>
                        <li class="nav-item mt-5 w-100">
                            <button type="submit" class="btn btn-primary w-100" disabled>Guardar</button>
                        </li>
                    </ul>
                </div>
                <!-- Contenido dinámico -->
                <div class="col-md-9">
                    <div id="general" class="content-section">
                        <h4>Información General</h4>
                        <input type="hidden" id="id" name="id" value="<?php echo $bodega_id; ?>">
                        <div class="mb-3">
                            <label for="nombre_bodega" class="form-label">Nombre de la Bodega <span
                                        style="color: red">*</span></label>
                            <input id="nombre_bodega" name="nombre_bodega" class="form-control" type="text"
                                   placeholder="Nombre de la Bodega" required/>
                        </div>

                        <div class="mb-3">
                            <label for="provincia" class="form-label">Provincia donde se ubica <span style="color: red">*</span></label>
                            <select class="datos form-control" id="provincia" name="provincia"
                                    onchange="cargarCiudades()" required>
                                <option value="">Provincia *</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="ciudad" class="form-label">Ciudad donde se ubica <span
                                        style="color: red">*</span></label>
                            <select name="ciudad" id="ciudad" class="form-control" disabled>
                                <option value="0"> -- Seleccione una ciudad --</option>
                            </select>

                        </div>

                        <div class="mb-3">
                            <label for="direccion" class="form-label">Dirección de bodega <span
                                        style="color: red">*</span></label>
                            <input id="direccion" name="direccion"
                                   class="form-control"
                                   type="text"
                                   placeholder="Ingresa una dirección"/>
                        </div>

                        <div class="mb-3">
                            <label for="num_casa" class="form-label">Número de casa</label>
                            <input id="num_casa" name="num_casa" class="form-control" type="text"
                                   placeholder="Numero de Casa">
                        </div>

                        <div class="mb-3">
                            <label for="responsable" class="form-label">Responsable de la bodega <span
                                        style="color: red">*</span>
                            </label>
                            <input id="responsable" name="responsable" class="form-control " type="text"
                                   placeholder="Ingrese nombre del responsable">
                        </div>

                        <div class="mb-3">
                            <label for="telefono" class="form-label">Teléfono de la bodega <span
                                        style="color: red">*</span>
                            </label>
                            <input id="telefono" name="telefono" class="form-control " type="text"
                                   placeholder="Telefono de contacto"/>
                        </div>

                        <div class="mb-3">
                            <label for="referencia" class="form-label">Referencia de ubicación <span style="color: red">*</span></label>
                            <input id="referencia" name="referencia" class="form-control " type="text"
                                   placeholder="Ingrese referencia">
                        </div>

                    </div>
                    <!-- Sección Fullfilment -->
                    <div id="fullfilment" class="content-section hidden-all">
                        <h4>Fullfilment</h4>
                        <p>Servicio de almacenamiento y envío de productos</p>

                        <div class="mb-3 ">
                            <label for="full" class="form-label">
                                ¿Desea ofrecer servicios de fullfilment?
                            </label>
                            <label class="switch">

                                <input type="checkbox" id="full" name="full" value="SI"/>
                                <span class="sliderB"></span>
                            </label>
                        </div>
                        <div id="input-full" class="mb-3   hidden-all">
                            <label for="valor_full" class="form-label">
                                Valor de servicio de fullfilment
                                <input id="valor_full" name="valor_full" class="form-control">
                            </label>
                        </div>
                    </div>
                </div>
        </form>
    </div>

<?php loadViewScripts("Productos", "editar_bodega") ?>
<?php require_once './Views/templates/footer.php'; ?>