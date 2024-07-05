<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Usuarios/css/tiendaOnline_style.php'; ?>

<div class="custom-container-fluid mt-4">
    <div class="accordion" id="accordionExample">
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingOne">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                    CONFIGURACIÓN PRINCIPAL
                </button>
            </h2>
            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">LOGO DE LA EMPRESA</h5>
                    <form id="imageFormPrincipal" enctype="multipart/form-data">
                        <div class="mb-3">
                            <input type="file" class="form-control" id="imageInputPrincipal" accept="image/*" name="imagen">
                        </div>
                        <img id="imagePreviewPrincipal" class="image-preview mb-3" src="" alt="Preview" width="200px">
                    </form>
                    <h5 class="card-title">FAVICON</h5>
                    <form id="imageFormFavicon" enctype="multipart/form-data">
                        <div class="mb-3">
                            <input type="file" class="form-control" id="imageInputFav" accept="image/*" name="imagenFav">
                        </div>
                        <img id="imagePreviewFav" class="image-preview mb-3" src="" alt="Preview" width="200px">
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="form-group mb-3">
                        <label for="nombre_tienda">NOMBRE DE LA TIENDA</label>
                        <input type="text" class="form-control" id="nombre_tienda" name="nombre_tienda" value="IMPORSUIT">
                    </div>
                    <div class="form-group mb-3">
                        <label for="descripcion_tienda">DESCRIPCION</label>
                        <input type="text" class="form-control" id="descripcion_tienda" name="descripcion_tienda" value="IMPORTADORA">
                    </div>
                    <div class="form-group mb-3">
                        <label for="ruc_tienda">RUC</label>
                        <input type="text" class="form-control" id="ruc_tienda" name="ruc_tienda" value="111111111001">
                    </div>
                    <div class="form-group mb-3">
                        <label for="telefono_tienda">TELEFONO</label>
                        <input type="text" class="form-control" id="telefono_tienda" name="telefono_tienda" value="+593981702066">
                    </div>
                    <div class="form-group mb-3">
                        <label for="email_tienda">EMAIL</label>
                        <input type="email" class="form-control" id="email_tienda" name="email_tienda" value="ventas@imporshop.app">
                    </div>
                    <div class="form-group mb-3">
                        <label for="direccion_tienda">DIRECCIÓN</label>
                        <input type="text" class="form-control" id="direccion_tienda" name="direccion_tienda" value="SU DIRECCION">
                    </div>
                    <div class="form-group mb-3">
                        <label for="pais_tienda">-- Elige un país --</label>
                        <select class="form-select" id="pais_tienda" name="pais_tienda">
                            <option selected>Ecuador</option>
                            <!-- Agrega más opciones según sea necesario -->
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <!-- <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" value="" id="habilitarTextoFlotante" checked>
                        <label class="form-check-label" for="habilitarTextoFlotante">
                            Habilitar texto flotante
                        </label>
                    </div>
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Id</th>
                            <th>Nombre</th>
                            <th>Posición</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>34</td>
                            <td>COMPRA AHORA!!</td>
                            <td>Barra superior</td>
                            <td><span class="badge bg-success">Activo</span></td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-warning btn-sm"><i class="bi bi-gear"></i></button>
                                    <button type="button" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>36</td>
                            <td>COMPRA AHORA!!</td>
                            <td>Barra inferior</td>
                            <td><span class="badge bg-success">Activo</span></td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-warning btn-sm"><i class="bi bi-gear"></i></button>
                                    <button type="button" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <nav>
                        <ul class="pagination">
                            <li class="page-item disabled">
                                <a class="page-link" href="#" tabindex="-1">Anterior</a>
                            </li>
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item">
                                <a class="page-link" href="#">Siguiente</a>
                            </li>
                        </ul>
                    </nav>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" value="" id="habilitarEnvioGratis">
                        <label class="form-check-label" for="habilitarEnvioGratis">
                            Habilitar Envío Gratis en botón comprar ahora
                        </label>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" value="" id="habilitarBotonWhatsapp" checked>
                        <label class="form-check-label" for="habilitarBotonWhatsapp">
                            Habilitar Botón de whatsapp
                        </label>
                    </div>
                </div>
            </div>
        </div> -->
    </div>
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingTwo">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                    BANNER
                </button>
            </h2>
            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    Contenido de Banner.
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingThree">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                    ICONOS
                </button>
            </h2>
            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    Contenido de Iconos.
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingFour">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                    COLORES
                </button>
            </h2>
            <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    Contenido de Colores.
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingFive">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                    TESTIMONIOS
                </button>
            </h2>
            <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    Contenido de Testimonios.
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingSix">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                    REDES SOCIALES / FOOTER
                </button>
            </h2>
            <div id="collapseSix" class="accordion-collapse collapse" aria-labelledby="headingSix" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    Contenido de Redes Sociales / Footer.
                </div>
            </div>
        </div>
    </div>
</div>


<!-- <script src="<?php echo SERVERURL ?>/Views/Productos/js/marketplace.js"></script> -->

<script>
    $('#imageInputPrincipal').on('change', function(event) {
        event.preventDefault();

        // Mostrar vista previa de la imagen seleccionada
        var input = event.target;
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#imagePreviewPrincipal').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }

        // Crear un FormData y enviar la imagen mediante AJAX
        var formData = new FormData($('#imageFormPrincipal')[0]);
        $.ajax({
            url: SERVERURL + 'Usuarios/guardar_imagen_logo', // Cambia esta ruta por la ruta correcta a tu controlador
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                response = JSON.parse(response);
                if (response.status == 500) {
                    toastr.error("LA IMAGEN NO SE AGREGRO CORRECTAMENTE", "NOTIFICACIÓN", {
                        positionClass: "toast-bottom-center"
                    });
                } else if (response.status == 200) {
                    toastr.success("IMAGEN AGREGADA CORRECTAMENTE", "NOTIFICACIÓN", {
                        positionClass: "toast-bottom-center",
                    });
                    $('#imagen_productoModal').modal('hide');
                    reloadDataTableProductos();
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Error al guardar la imagen: ' + textStatus);
            }
        });
    });


    $('#imageFormFavicon').submit(function(event) {
        event.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            url: SERVERURL + 'Usuarios/guardar_imagen_favicon', // Cambia esta ruta por la ruta correcta a tu controlador
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                response = JSON.parse(response);
                if (response.status == 500) {
                    toastr.error("LA IMAGEN NO SE AGREGRO CORRECTAMENTE", "NOTIFICACIÓN", {
                        positionClass: "toast-bottom-center"
                    });
                } else if (response.status == 200) {
                    toastr.success("IMAGEN AGREGADA CORRECTAMENTE", "NOTIFICACIÓN", {
                        positionClass: "toast-bottom-center",
                    });
                    $('#imagen_productoModal').modal('hide');
                    reloadDataTableProductos();
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Error al guardar la imagen: ' + textStatus);
            }
        });
    });
</script>
<?php require_once './Views/templates/footer.php'; ?>