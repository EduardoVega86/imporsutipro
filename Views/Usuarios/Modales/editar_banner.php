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

<div class="modal fade" id="editar_bannerModal" tabindex="-1" aria-labelledby="editar_bannerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editar_bannerModalLabel"><i class="fas fa-edit"></i> Editar Banner</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editar_banner_form" enctype="multipart/form-data">
                    <input type="hidden" id="id_banner" name="id_banner">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="titulo_editar" class="form-label">Título</label>
                            <input type="text" class="form-control" id="titulo_editar" placeholder="Título">
                        </div>
                        <div class="col-md-6">
                            <label for="texto_banner_editar" class="form-label">Texto del banner</label>
                            <textarea class="form-control" id="texto_banner_editar" rows="3" placeholder="Texto del banner"></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="texto_boton_editar" class="form-label">Botón</label>
                            <input type="text" class="form-control" id="texto_boton_editar" placeholder="Texto del botón">
                        </div>
                        <div class="col-md-6">
                            <label for="enlace_boton_editar" class="form-label">Enlace Botón</label>
                            <input type="text" class="form-control" id="enlace_boton_editar" placeholder="URL del botón">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="alineacion_editar" class="form-label">Alineación</label>
                            <select class="form-select" id="alineacion_editar">
                                <option value="1">Izquierda</option>
                                <option value="2">Centro</option>
                                <option value="3">Derecha</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="imagen_editar" class="form-label">Imagen</label>
                            <input type="file" class="form-control" id="imagen_editar" name="imagen_editar" accept="image/*">
                            <img id="preview-imagen-editar" src="#" alt="Vista previa de la imagen" style="display: none; margin-top: 10px; max-width: 100%;">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="input-box d-flex flex-column">
                            <input onchange="cambiarcolor_banner('color_texto_banner',this.value)" id="color_texto_banner" name="color_texto_banner" type="color" value="#ff0000">
                            <h6><strong>Color texto</strong></h6>
                        </div>
                        <div class="input-box d-flex flex-column">
                            <input onchange="cambiarcolor_banner('color_btn_banner',this.value)" id="color_btn_banner" name="color_btn_banner" type="color" value="#ff0000">
                            <h6><strong>Color boton</strong></h6>
                        </div>
                        <div class="input-box d-flex flex-column">
                            <input onchange="cambiarcolor_banner('color_textoBtn_banner',this.value)" id="color_textoBtn_banner" name="color_textoBtn_banner" type="color" value="#ff0000">
                            <h6><strong>Color texto boton</strong></h6>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary" id="actualizar_banner">Actualizar</button>
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
            $('#editar_banner_form')[0].reset();
            $('#preview-imagen-editar').attr('src', '#').hide();
        }

        // Evento para reiniciar el formulario cuando se cierre el modal
        $('#editar_bannerModal').on('hidden.bs.modal', function() {
            var button = document.getElementById('actualizar_banner');
            button.disabled = false; // Desactivar el botón
            resetForm();
        });

        // Vista previa de la imagen
        $('#imagen_editar').change(function() {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#preview-imagen-editar').attr('src', e.target.result).show();
            }
            reader.readAsDataURL(this.files[0]);
        });

        $('#editar_banner_form').submit(function(event) {
            event.preventDefault(); // Evita que el formulario se envíe de la forma tradicional

            var button = document.getElementById('actualizar_banner');
            button.disabled = true; // Desactivar el botón

            // Crea un objeto FormData
            var formData = new FormData();
            formData.append('id_banner', $('#id_banner').val());
            formData.append('titulo', $('#titulo_editar').val());
            formData.append('texto_banner', $('#texto_banner_editar').val());
            formData.append('texto_boton', $('#texto_boton_editar').val());
            formData.append('enlace_boton', $('#enlace_boton_editar').val());
            formData.append('alineacion', $('#alineacion_editar').val());
            formData.append('imagen', $('#imagen_editar')[0].files[0]);

            // Realiza la solicitud AJAX
            $.ajax({
                url: SERVERURL + 'Usuarios/editarBanner',
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

                        $('#editar_bannerModal').modal('hide');
                        resetForm();
                        initDataTableBanner();
                    }
                },
                error: function(error) {
                    alert('Hubo un error al editar el producto');
                    console.log(error);
                }
            });
        });

    });
</script>