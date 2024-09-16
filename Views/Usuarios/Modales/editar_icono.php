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

<div class="modal fade" id="editar_iconoModal" tabindex="-1" aria-labelledby="editar_iconoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editar_iconoModalLabel"><i class="fas fa-edit"></i> Nuevo Flotante</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editar_icono_form" enctype="multipart/form-data">
                    <div class="row mb-3">
                        <input type="hidden" id="id_icono" name="id_icono">
                        <div class="col-md-6">
                            <label for="texto_icono" class="form-label">Texto Icono:</label>
                            <input type="text" class="form-control" id="texto_icono" rows="3" placeholder="Texto del icono"></input>
                        </div>
                        <div class="col-md-6">
                            <label for="subTexto_icono" class="form-label">Sub-texto Icono</label>
                            <textarea class="form-control" id="subTexto_icono" rows="3" placeholder="Escriba el sub-texto"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label for="enlace_icono" class="form-label">Enlace Icono:</label>
                            <input type="text" class="form-control" id="enlace_icono" rows="3" placeholder="URL"></input>
                        </div>
                        <div class="col-md-6">
                            <label for="icono" class="form-label">Icono:</label>
                            <select class="form-select" id="icono" style="width: 100%;">
                                <option selected value="">-- Selecciona Icono --</option>
                            </select>
                        </div>
                        <div class="input-box d-flex flex-column">
                            <input onchange="cambiarcolor_icono('color_icono',this.value)" id="color_icono" name="color_icono" type="color" value="#ff0000">
                            <h6><strong>Color icono</strong></h6>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary" id="editar_icono">Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function cambiarcolor_icono(campo, valor) {
        const formData = new FormData();
        formData.append('id', $('#id_icono').val());
        formData.append("campo", campo);
        formData.append("valor", valor);

        $.ajax({
            type: "POST",
            url: "" + SERVERURL + "Usuarios/cambiarcolor_icono",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response2) {
                response2 = JSON.parse(response2);

                if (response2.status == 500) {
                    toastr.error("EL COLOR NO SE CAMBIO CORRECTAMENTE", "NOTIFICACIÓN", {
                        positionClass: "toast-bottom-center",
                    });
                } else if (response2.status == 200) {
                    toastr.success("COLOR CAMBIADO CORRECTAMENTE", "NOTIFICACIÓN", {
                        positionClass: "toast-bottom-center",
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error("Error en la solicitud AJAX:", error);
                alert("Hubo un problema al agregar el producto temporalmente");
            },
        });
    }

    $(document).ready(function() {
        // Función para reiniciar el formulario
        function resetForm() {
            $('#editar_icono_form')[0].reset();
        }

        // Evento para reiniciar el formulario cuando se cierre el modal
        $('#editar_iconoModal').on('hidden.bs.modal', function() {
            var button = document.getElementById('editar_icono');
            button.disabled = false; // Desactivar el botón
            resetForm();
        });

        $('#editar_icono_form').submit(function(event) {
            event.preventDefault(); // Evita que el formulario se envíe de la forma tradicional

            var button = document.getElementById('editar_icono');
            button.disabled = true; // Desactivar el botón

            // Crea un objeto FormData
            var formData = new FormData();
            formData.append('id', $('#id_icono').val());
            formData.append('texto', $('#texto_icono').val());
            formData.append('subtexto_icon', $('#subTexto_icono').val());
            formData.append('enlace_icon', $('#enlace_icono').val());
            formData.append('icon_text', $('#icono').val());

            // Realiza la solicitud AJAX
            $.ajax({
                url: '' + SERVERURL + 'Usuarios/editarIcono',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    response = JSON.parse(response);
                    // Mostrar alerta de éxito
                    if (response.status == 500) {
                        toastr.error(
                            "EL ICONO NO SE AGREGRO CORRECTAMENTE",
                            "NOTIFICACIÓN", {
                                positionClass: "toast-bottom-center"
                            }
                        );
                    } else if (response.status == 200) {
                        toastr.success("ICONO ACTUALIZADO CORRECTAMENTE", "NOTIFICACIÓN", {
                            positionClass: "toast-bottom-center",
                        });

                        $('#editar_iconoModal').modal('hide');
                        resetForm();
                        initDataTableCaracteristicas();
                    }
                },
                error: function(error) {
                    alert('Hubo un error al editar el flotante');
                    console.log(error);
                }
            });
        });
    });
</script>