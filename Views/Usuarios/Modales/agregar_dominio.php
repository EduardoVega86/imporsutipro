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

<div class="modal fade" id="agregar_dominioModal" tabindex="-1" aria-labelledby="agregar_dominioModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="agregar_dominioModalLabel"><i class="fas fa-edit"></i> Registro de Dominio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="agregar_dominio_form" enctype="multipart/form-data">
                    <div class="row mb-3">
                        <div class="col">
                            <label for="dominio" class="form-label">Dominio:</label>
                            <input type="text" class="form-control" id="dominio" placeholder="Dominio">
                        </div>
                        <div class="col">
                            <label for="subdominio" class="form-label">Subdominio:</label>
                            <input type="subdominio" class="form-control" id="subdominio" placeholder="Subdominio">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary" id="agregar_dominio">Agregar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        // Evento para reiniciar el formulario cuando se cierre el modal
        $('#agregar_dominioModal').on('hidden.bs.modal', function() {
            var button = document.getElementById('agregar_dominio');
            button.disabled = false; // Desactivar el botón
        });

        $('#agregar_dominio_form').submit(function(event) {
            event.preventDefault(); // Evita que el formulario se envíe de la forma tradicional

            var button = document.getElementById('agregar_dominio');
            button.disabled = true; // Desactivar el botón

            // Crea un objeto FormData
            var formData = new FormData();
            formData.append('dominio', $('#dominio').val());
            formData.append('subdominio', $('#subdominio').val());

            // Realiza la solicitud AJAX
            $.ajax({
                url: SERVERURL + 'tienda/anadir_dominio',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    response = JSON.parse(response);
                    // Mostrar alerta de éxito
                    if (response.status == 500) {
                        toastr.error(
                            "EL USUARIO NO SE AGREGRO CORRECTAMENTE",
                            "NOTIFICACIÓN", {
                                positionClass: "toast-bottom-center"
                            }
                        );
                    } else if (response.status == 200) {
                        toastr.success("USUARIO AGREGADO CORRECTAMENTE", "NOTIFICACIÓN", {
                            positionClass: "toast-bottom-center",
                        });

                        $('#agregar_dominioModal').modal('hide');
                        resetForm();
                        initDataTableObtenerUsuariosPlataforma();
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