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

<div class="modal fade" id="agregar_bannerModal" tabindex="-1" aria-labelledby="agregar_bannerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="agregar_bannerModalLabel"><i class="fas fa-edit"></i> Nuevo Banner</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="agregar_banner_form" enctype="multipart/form-data">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="titulo" class="form-label">Título</label>
                            <input type="text" class="form-control" id="titulo" placeholder="Título">
                        </div>
                        <div class="col-md-6">
                            <label for="texto_banner" class="form-label">Texto del banner</label>
                            <textarea class="form-control" id="texto_banner" rows="3" placeholder="Texto del banner"></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="texto_boton" class="form-label">Botón</label>
                            <input type="text" class="form-control" id="texto_boton" placeholder="Texto del botón">
                        </div>
                        <div class="col-md-6">
                            <label for="enlace_boton" class="form-label">Enlace Botón</label>
                            <input type="text" class="form-control" id="enlace_boton" placeholder="URL del botón">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="alineacion" class="form-label">Alineación</label>
                            <select class="form-select" id="alineacion">
                                <option value="1">Izquierda</option>
                                <option value="2">Centro</option>
                                <option value="3">Derecha</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="imagen" class="form-label">Imagen</label>
                            <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*">
                            <img id="preview-imagen" src="#" alt="Vista previa de la imagen" style="display: none; margin-top: 10px; max-width: 100%;">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="input-box d-flex flex-column">
                            <input id="color_texto_banner" name="color_texto_banner" type="color" value="#ff0000">
                            <h6><strong>Color texto</strong></h6>
                        </div>
                        <div class="input-box d-flex flex-column">
                            <input id="color_btn_banner" name="color_btn_banner" type="color" value="#ff0000">
                            <h6><strong>Color boton</strong></h6>
                        </div>
                        <div class="input-box d-flex flex-column">
                            <input id="color_textoBtn_banner" name="color_textoBtn_banner" type="color" value="#ff0000">
                            <h6><strong>Color texto boton</strong></h6>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary" id="guardar_banner">Guardar</button>
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
            $('#agregar_banner_form')[0].reset();
            $('#bodega-field').addClass('hidden-field');
            $('#precio-referencial-valor').prop('disabled', true);
            $('#preview-imagen').attr('src', '#').hide();
        }

        // Evento para reiniciar el formulario cuando se cierre el modal
        $('#agregar_bannerModal').on('hidden.bs.modal', function() {
            var button = document.getElementById('guardar_banner');
            button.disabled = false; // Desactivar el botón
            resetForm();
        });

        // Vista previa de la imagen
        $('#imagen').change(function() {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#preview-imagen').attr('src', e.target.result).show();
            }
            reader.readAsDataURL(this.files[0]);
        });

        $('#agregar_banner_form').submit(function(event) {
            event.preventDefault(); // Evita que el formulario se envíe de la forma tradicional

            var button = document.getElementById('guardar_banner');
            button.disabled = true; // Desactivar el botón

            // Crea un objeto FormData
            var formData = new FormData();
            formData.append('titulo', $('#titulo').val());
            formData.append('texto_banner', $('#texto_banner').val());
            formData.append('texto_boton', $('#texto_boton').val());
            formData.append('enlace_boton', $('#enlace_boton').val());
            formData.append('alineacion', $('#alineacion').val());
            formData.append('imagen', $('#imagen')[0].files[0]);

            formData.append('color_texto_banner', $('#color_texto_banner').val());
            formData.append('color_btn_banner', $('#color_btn_banner').val());
            formData.append('color_textoBtn_banner', $('#color_textoBtn_banner').val());

            // Realiza la solicitud AJAX
            $.ajax({
                url: '' + SERVERURL + 'Usuarios/agregarBanner',
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

                        $('#agregar_bannerModal').modal('hide');
                        resetForm();
                        initDataTableBanner();
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