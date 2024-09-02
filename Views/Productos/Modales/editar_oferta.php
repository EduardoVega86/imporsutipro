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

<div class="modal fade" id="editar_comboModal" tabindex="-1" aria-labelledby="editar_comboModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editar_comboModalLabel"><i class="fas fa-edit"></i> Nuevo Combo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editar_combo_form" enctype="multipart/form-data">
                    <input type="hidden" id="id_oferta_editar" name="id_oferta_editar">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="editar_nombre_combo" class="form-label">Nombre del combo</label>
                            <input type="text" class="form-control" id="editar_nombre_combo" placeholder="nombre del combo">
                        </div>

                        <div class="col-md-6">
                            <label for="editar_cantidad_oferta" class="form-label">Cantidad</label>
                            <input type="text" class="form-control" id="editar_cantidad_oferta" placeholder="Cantidad">
                        </div>

                        <div class="col-md-6">
                            <label for="editar_precio_oferta" class="form-label">Precio</label>
                            <input type="text" class="form-control" id="editar_precio_oferta" placeholder="Precio">
                        </div>

                        <div class="col-md-6">
                            <label for="select_productos_editar" class="form-label">Producto</label>
                            <select class="form-select" id="select_productos_editar" style="width: 100%">
                                <option value="" selected>--- Elegir producto ---</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="rango_fechas_editar" class="form-label">Seleccione el rango de fechas</label>
                            <input type="text" class="form-control" id="rango_fechas_editar" name="rango_fechas_editar" />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary" id="guardar_combo">Guardar</button>
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
            $('#editar_combo_form')[0].reset();
        }

        // Evento para reiniciar el formulario cuando se cierre el modal
        $('#editar_comboModal').on('hidden.bs.modal', function() {
            var button = document.getElementById('guardar_combo');
            button.disabled = false; // Desactivar el botón
            resetForm();
        });

        $('#editar_combo_form').submit(function(event) {
            event.preventDefault(); // Evita que el formulario se envíe de la forma tradicional

            var button = document.getElementById('guardar_combo');
            button.disabled = true; // Desactivar el botón

            // Extraer las fechas de inicio y fin desde el Daterangepicker
            var fechas = $('#rango_fechas_editar').data('daterangepicker');
            var fechaInicio = fechas.startDate.format('YYYY-MM-DD HH:mm:ss');
            var fechaFin = fechas.endDate.format('YYYY-MM-DD HH:mm:ss');

            // Crea un objeto FormData
            var formData = new FormData();
            formData.append('nombre', $('#editar_nombre_combo').val());
            formData.append('id_oferta', $('#id_oferta_editar').val());
            formData.append('editar_precio_oferta', $('#editar_precio_oferta').val());
            formData.append('editar_cantidad_oferta', $('#editar_cantidad_oferta').val());
            formData.append('fecha_inicio', fechaInicio);
            formData.append('fecha_fin', fechaFin);
            formData.append('id_producto_combo', $('#select_productos_editar').val());

            // Realiza la solicitud AJAX
            $.ajax({
                url: '' + SERVERURL + 'Productos/editarcombos',
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

                        $('#editar_comboModal').modal('hide');
                        resetForm();
                        initDataTableCombos();
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