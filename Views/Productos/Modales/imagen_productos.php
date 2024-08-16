<style>
    .modal-content {
        border-radius: 15px;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }

    .modal-header {
        background-color: <?php echo COLOR_FONDO; ?>;
        color: <?php echo COLOR_LETRAS; ?>;
        border-top-left-radius: 15px;
        border-top-right-radius: 15px;
    }

    .modal-header .btn-close {
        color: white;
    }

    .modal-body {
        padding: 20px;
    }

    .modal-footer {
        border-top: none;
        padding: 10px 20px;
    }

    .modal-footer .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
    }

    .modal-footer .btn-primary {
        background-color: #ffc107;
        border-color: #ffc107;
        color: white;
    }

    .image-preview-container img {
        width: 100px;
        margin-right: 10px;
        margin-bottom: 10px;
    }
</style>

<div class="modal fade" id="imagen_productoModal" tabindex="-1" aria-labelledby="imagen_productoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imagen_productoModalLabel"><i class="fas fa-edit"></i> Nueva imagen producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="principal-tab" data-bs-toggle="tab" data-bs-target="#principal" type="button" role="tab" aria-controls="principal" aria-selected="true">Imagen Principal</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="adicionales-tab" data-bs-toggle="tab" data-bs-target="#adicionales" type="button" role="tab" aria-controls="adicionales" aria-selected="false">Imágenes Adicionales</button>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="principal" role="tabpanel" aria-labelledby="principal-tab">
                        <form id="imageFormPrincipal" enctype="multipart/form-data">
                            <input type="hidden" id="id_imagenproducto" name="id_producto">
                            <div class="form-group mt-3">
                                <label for="imageInputPrincipal">Imagen Principal</label>
                                <input type="file" class="form-control-file" id="imageInputPrincipal" accept="image/*" name="imagen">
                            </div>
                            <img id="imagePreviewPrincipal" class="image-preview mt-2" src="" alt="Preview" width="200px">
                        </form>
                    </div>
                    <div class="tab-pane fade" id="adicionales" role="tabpanel" aria-labelledby="adicionales-tab">
                        <div>
                            <form id="imageFormAdicional1" enctype="multipart/form-data">
                                
                                <div class="form-group mt-3">
                                    <label for="imageInputAdicional1">Imagen Adicional 1</label>
                                    <input type="file" class="form-control-file" id="imageInputAdicional1" accept="image/*" name="imagen">
                                </div>
                                <img id="imagePreviewAdicional1" class="image-preview mt-2" src="" alt="Preview" width="200px">
                            </form>
                        </div>
                        <div>
                            <form id="imageFormAdicional2" enctype="multipart/form-data">
                                
                                <div class="form-group mt-3">
                                    <label for="imageInputAdicional2">Imagen Adicional 2</label>
                                    <input type="file" class="form-control-file" id="imageInputAdicional2" accept="image/*" name="imagen">
                                </div>
                                <img id="imagePreviewAdicional2" class="image-preview mt-2" src="" alt="Preview" width="200px">
                            </form>
                        </div>
                        <div>
                            <form id="imageFormAdicional3" enctype="multipart/form-data">
                                
                                <div class="form-group mt-3">
                                    <label for="imageInputAdicional3">Imagen Adicional 3</label>
                                    <input type="file" class="form-control-file" id="imageInputAdicional3" accept="image/*" name="imagen">
                                </div>
                                <img id="imagePreviewAdicional3" class="image-preview mt-2" src="" alt="Preview" width="200px">
                            </form>
                        </div>
                        <div>
                            <form id="imageFormAdicional4" enctype="multipart/form-data">
                                
                                <div class="form-group mt-3">
                                    <label for="imageInputAdicional4">Imagen Adicional 4</label>
                                    <input type="file" class="form-control-file" id="imageInputAdicional4" accept="image/*" name="imagen">
                                </div>
                                <img id="imagePreviewAdicional4" class="image-preview mt-2" src="" alt="Preview" width="200px">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
    var additionalImages = [];

    $(document).ready(function() {
        $('#imageInputPrincipal').change(function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#imagePreviewPrincipal').attr('src', e.target.result);
                    $('#imagePreviewPrincipal').show();
                    $('#imageFormPrincipal').submit();
                }
                reader.readAsDataURL(file);
            } else {
                $('#imagePreviewPrincipal').hide();
            }
        });

        $('#imageFormPrincipal').submit(function(event) {
            event.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                url: SERVERURL + 'Productos/guardar_imagen_productos', // Cambia esta ruta por la ruta correcta a tu controlador
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
        /* imagen adicional 1 */
        $('#imageInputAdicional1').change(function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#imagePreviewAdicional1').attr('src', e.target.result);
                    $('#imagePreviewAdicional1').show();
                    $('#imageFormAdicional1').submit();
                }
                reader.readAsDataURL(file);
            } else {
                $('#imagePreviewAdicional1').hide();
            }
        });

        $('#imageFormAdicional1').submit(function(event) {
            event.preventDefault();
            
            var imagen = $("#imageInputAdicionales")[0].files[0];

            let formData = new FormData();
            formData.append("id_producto", $('#id_imagenproducto').val());
            formData.append("imagen", imagen);
            
            $.ajax({
                url: SERVERURL + 'Productos/guardar_imagen_productos', // Cambia esta ruta por la ruta correcta a tu controlador
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
        /* Fin imagen adicional 1 */
    });
</script>