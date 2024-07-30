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

<div class="modal fade" id="conectar_tiktokModal" tabindex="-1" aria-labelledby="conectar_tiktokModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="conectar_tiktokModalLabel"><i class="fas fa-edit"></i> Ingresar Pixel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="conectar_tiktok_form" enctype="multipart/form-data">
                    <input type="hidden" id="id_estado_tiktok" name="id_estado_tiktok">

                    <div class="row mb-3">
                        <div>
                            <label for="script_tiktok" class="form-label">Ingrese su script de tiktok</label>
                            <textarea class="form-control" id="script_tiktok" rows="3" placeholder="Texto"></textarea>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary" id="conectar_tiktok">Conectar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        $('#conectar_tiktok_form').submit(function(event) {
            event.preventDefault(); // Evita que el formulario se envíe de la forma tradicional

            var estado = $('#id_estado_tiktok').val();

            // Crea un objeto FormData
            var formData = new FormData();
            formData.append('nombre', "TIKTOK");
            formData.append('pixel', $('#script_tiktok').val());
            formData.append('tipo', 2);

            if (estado == 0) {
                // Realiza la solicitud AJAX
                $.ajax({
                    url: SERVERURL + 'tienda/crearPixel',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        response = JSON.parse(response);
                        // Mostrar alerta de éxito
                        if (response.status == 500) {
                            toastr.error(
                                "EL HORIZONTAL NO SE AGREGRO CORRECTAMENTE",
                                "NOTIFICACIÓN", {
                                    positionClass: "toast-bottom-center"
                                }
                            );
                        } else if (response.status == 200) {
                            toastr.success("HORIZONTAL AGREGADO CORRECTAMENTE", "NOTIFICACIÓN", {
                                positionClass: "toast-bottom-center",
                            });

                            $('#conectar_tiktokModal').modal('hide');
                            resetForm();
                            initDataTableHorizonal();
                        }
                    },
                    error: function(error) {
                        alert('Hubo un error al editar el producto');
                        console.log(error);
                    }
                });

            } else {
                // Realiza la solicitud AJAX
                $.ajax({
                    url: SERVERURL + 'tienda/actualizarPixel',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        response = JSON.parse(response);
                        // Mostrar alerta de éxito
                        if (response.status == 500) {
                            toastr.error(
                                "EL HORIZONTAL NO SE AGREGRO CORRECTAMENTE",
                                "NOTIFICACIÓN", {
                                    positionClass: "toast-bottom-center"
                                }
                            );
                        } else if (response.status == 200) {
                            toastr.success("HORIZONTAL AGREGADO CORRECTAMENTE", "NOTIFICACIÓN", {
                                positionClass: "toast-bottom-center",
                            });

                            $('#conectar_tiktokModal').modal('hide');
                            resetForm();
                            initDataTableHorizonal();
                        }
                    },
                    error: function(error) {
                        alert('Hubo un error al editar el producto');
                        console.log(error);
                    }
                });
            }

        });

    });
</script>