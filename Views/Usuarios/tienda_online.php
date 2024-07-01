<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Productos/css/marketplace_style.php'; ?>

<?php require_once './Views/Productos/Modales/descripcion_marketplace.php'; ?>
<?php require_once './Views/Productos/Modales/Seleccion_productoAtributo.php'; ?>

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
                        <div class="col-md-2">
                             <form id="imageFormPrincipal" enctype="multipart/form-data">
                            <input type="hidden" id="id_imagenproducto" name="id_producto">
                            <div class="form-group mt-3">
                                <label for="imageInputPrincipal">Logo</label>
                                <input type="file" class="form-control-file" id="imageInputPrincipal" accept="image/*" name="imagen">
                            </div>
                            <img id="imagePreviewPrincipal" class="image-preview mt-2" src="" alt="Preview" width="200px">
                        </form>
                            <form id="imageFormFavicon" enctype="multipart/form-data">
                            <input type="hidden" id="id_imagenproducto" name="id_producto">
                            <div class="form-group mt-3">
                                <label for="imageInputPrincipal">Favicon</label>
                                <input type="file" onchange="guardar_logo()" class="form-control-file" id="imageInputPrincipal" accept="image/*" name="imagenFav">
                            </div>
                            <img id="imagePreviewFav" class="image-preview mt-2" src="" alt="Preview" width="200px">
                        </form>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                           <div class="form-group">
                        <label for="nombre_linea">Nombre tienda:</label>
                        <input type="text" class="form-control" id="nombre_tienda" name="nombre_tienda" placeholder="Nombre">
                    </div>
                                </div>
                        </div>
                               
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


<script src="<?php echo SERVERURL ?>/Views/Productos/js/marketplace.js"></script>
<script src="<?php echo SERVERURL ?>/Views/Productos/js/tablaSeleccion_Producto.js"></script>
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