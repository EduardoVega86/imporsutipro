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

<div class="modal fade" id="agregar_ofertaModal" tabindex="-1" aria-labelledby="agregar_ofertaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="agregar_ofertaModalLabel"><i class="fas fa-edit"></i> Nuevo oferta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="agregar_oferta_form" enctype="multipart/form-data">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="nombre_oferta" class="form-label">Nombre del oferta</label>
                            <input type="text" class="form-control" id="nombre_oferta" placeholder="nombre del oferta">
                        </div>

                        <div class="col-md-6">
                            <label for="cantidad_oferta" class="form-label">Cantidad</label>
                            <input type="text" class="form-control" id="cantidad_oferta" placeholder="Cantidad">
                        </div>

                        <div class="col-md-6">
                            <label for="precio_oferta" class="form-label">Precio</label>
                            <input type="text" class="form-control" id="precio_oferta" placeholder="Precio">
                        </div>
                        <div class="col-md-6">
                            <label for="select_productos" class="form-label">Producto</label>
                            <select class="form-select" id="select_productos" style="width: 100%">
                                <option value="" selected>--- Elegir producto ---</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="rango_fechas" class="form-label">Seleccione el rango de fechas</label>
                            <input type="text" class="form-control" id="rango_fechas" name="rango_fechas" />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary" id="guardar_oferta">Guardar</button>
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
            $('#agregar_oferta_form')[0].reset();
        }

        // Evento para reiniciar el formulario cuando se cierre el modal
        $('#agregar_ofertaModal').on('hidden.bs.modal', function() {
            var button = document.getElementById('guardar_oferta');
            button.disabled = false; // Desactivar el botón
            resetForm();
        });

        $('#agregar_oferta_form').submit(function(event) {
            event.preventDefault(); // Evita que el formulario se envíe de la forma tradicional

            var button = document.getElementById('guardar_oferta');
            button.disabled = true; // Desactivar el botón

            // Extraer las fechas de inicio y fin desde el Daterangepicker
            var fechas = $('#rango_fechas').data('daterangepicker');
            var fechaInicio = fechas.startDate.format('YYYY-MM-DD HH:mm:ss');
            var fechaFin = fechas.endDate.format('YYYY-MM-DD HH:mm:ss');

            // Crea un objeto FormData
            var formData = new FormData();
            formData.append('nombre_oferta', $('#nombre_oferta').val());
            formData.append('precio_oferta', $('#precio_oferta').val());
            formData.append('cantidad', $('#cantidad_oferta').val());
            formData.append('fecha_inicio', fechaInicio);
            formData.append('fecha_fin', fechaFin);
            formData.append('id_producto', $('#select_productos').val());

            // Realiza la solicitud AJAX
            $.ajax({
                url: '' + SERVERURL + 'Productos/agregarOferta',
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

                        $('#agregar_ofertaModal').modal('hide');
                        resetForm();
                        initDataTableOfertas();
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