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

    .texto_modal {
        font-size: 20px;
        margin-bottom: 5px;
    }

    .descripcion_producto {
        display: flex;
        flex-direction: row;
    }

    .informacion_producto {
        width: 50%;
        /* Aproximadamente la mitad del contenedor, similar a col-6 */
        margin-bottom: 1rem;
        /* Espaciado en la parte inferior, similar a mb-4 */
    }

    .boton_eliminar_etiqueta {
        background-color: transparent;
        border: hidden;
        color: #afaea9;
    }

    .boton_eliminar_etiqueta:hover{
        color: black;
    }

    @media (max-width: 768px) {
        .descripcion_producto {
            flex-direction: column-reverse;
        }

        .informacion_producto {
            width: 100%;
        }
    }
</style>
<div class="modal fade" id="agregar_etiquetaModal" tabindex="-1" aria-labelledby="agregar_etiquetaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="agregar_etiquetaModalLabel"><i class="fas fa-edit"></i> Etiquetas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <!-- Agregar etiqueta -->
                <div class="card mb-4">
                    <div class="card-header">
                        Agregar etiqueta
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="nombre_etiqueta" class="form-label">Nombre etiqueta</label>
                            <input type="text" class="form-control" id="nombre_etiqueta" placeholder="Ingrese el nombre de la etiqueta">
                            <div class="input-box d-flex flex-column">
                                <input id="color_etiqueta" name="color_etiqueta" type="color" value="#ff0000">
                                <h6><strong>Color etiqueta</strong></h6>
                            </div>
                            <button type="button" class="btn btn-primary" onclick="agregar_etiqueta()">Agregar etiqueta</button>
                        </div>
                    </div>
                </div>

                <!-- Lista de etiquetas -->
                <div class="card">
                    <div class="card-header">
                        Lista de etiquetas
                    </div>
                    <div class="card-body">

                        <div id="lista_etiquetas">

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
    function agregar_etiqueta() {
        var nombre_etiqueta = $('#nombre_etiqueta').val();
        var color_etiqueta = $('#color_etiqueta').val();

        let formData = new FormData();
        formData.append("nombre_etiqueta", nombre_etiqueta);
        formData.append("color_etiqueta", color_etiqueta);

        $.ajax({
            url: SERVERURL + "Pedidos/agregar_etiqueta",
            type: "POST",
            data: formData,
            processData: false, // No procesar los datos
            contentType: false, // No establecer ningún tipo de contenido
            dataType: "json",
            success: function(response) {
                if (response.status == 500) {
                    toastr.error(
                        "LA CONFIGURACION NO SE AGREGRO CORRECTAMENTE",
                        "NOTIFICACIÓN", {
                            positionClass: "toast-bottom-center"
                        }
                    );
                } else if (response.status == 200) {
                    toastr.success("CONFIGURACION AGREGADA CORRECTAMENTE", "NOTIFICACIÓN", {
                        positionClass: "toast-bottom-center",
                    });

                    cargarEtiquetas();

                }

            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert(errorThrown);
            },
        });

    }

    function cargarEtiquetas() {
        $.ajax({
            url: SERVERURL + "Pedidos/obtener_etiquetas",
            type: "GET",
            dataType: "json",
            success: function(response) {
                if (response.length > 0) {
                    // Limpiamos el contenido existente en el div
                    $('#lista_etiquetas').empty();

                    // Recorremos cada etiqueta recibida en la respuesta
                    response.forEach(function(etiqueta) {
                        // Creamos el HTML para cada etiqueta
                        var etiquetaHTML = `
                            <div class="etiqueta-item d-flex align-items-center mb-3">
                                <div class="etiqueta-color" style="background-color: ${etiqueta.color_etiqueta}; width: 20px; height: 20px; border-radius: 50%; margin-right: 10px;"></div>
                                <div class="etiqueta-nombre">${etiqueta.nombre_etiqueta}</div>
                                <button class="boton_eliminar_etiqueta" onclick="boton_eliminarEtiqueta(${etiqueta.id_etiqueta})"><i class="fa-solid fa-trash"></i></button>
                            </div>
                        `;

                        // Añadimos el HTML de la etiqueta al contenedor
                        $('#lista_etiquetas').append(etiquetaHTML);
                    });
                } else {
                    // Si no hay etiquetas, mostramos un mensaje
                    $('#lista_etiquetas').html('<p>No hay etiquetas disponibles.</p>');
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Error al obtener etiquetas:', errorThrown);
            }
        });
    }

    function boton_eliminarEtiqueta(id_etiqueta) {
        $.ajax({
            type: "POST",
            url: SERVERURL + "Pedidos/eliminarEtiqueta/" + id_etiqueta,
            dataType: "json",
            success: function(response) {
                if (response.status == 500) {
                    toastr.error(
                        "LA ETIQUETA NO SE ELIMINO CORECTAMENTE",
                        "NOTIFICACIÓN", {
                            positionClass: "toast-bottom-center"
                        }
                    );
                } else if (response.status == 200) {
                    toastr.success("ETIQUETA ELIMINADA", "NOTIFICACIÓN", {
                        positionClass: "toast-bottom-center",
                    });

                    cargarEtiquetas();
                }
            },
            error: function(xhr, status, error) {
                console.error("Error en la solicitud AJAX:", error);
                alert("Hubo un problema al eliminar etiqueta");
            },
        });
    }

    // Llamamos a la función cargarEtiquetas cuando el modal se abre
    $('#agregar_etiquetaModal').on('shown.bs.modal', function() {
        cargarEtiquetas();
    });
</script>