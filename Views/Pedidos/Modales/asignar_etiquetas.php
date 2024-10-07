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

    @media (max-width: 768px) {
        .descripcion_producto {
            flex-direction: column-reverse;
        }

        .informacion_producto {
            width: 100%;
        }
    }
</style>
<div class="modal fade" id="asignar_etiquetaModal" tabindex="-1" aria-labelledby="asignar_etiquetaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="asignar_etiquetaModalLabel"><i class="fas fa-edit"></i> Etiquetas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Lista de etiquetas -->
                <div class="card">
                    <div class="card-header">
                        Lista de etiquetas
                    </div>
                    <div class="card-body">

                        <div id="lista_etiquetas_asignar">

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
    function cargarEtiquetas_asignar() {
        $.ajax({
            url: SERVERURL + "Pedidos/obtener_etiquetas",
            type: "GET",
            dataType: "json",
            success: function(response) {
                if (response.length > 0) {
                    // Limpiamos el contenido existente en el div
                    $('#lista_etiquetas_asignar').empty();

                    var id_etiqueta_select = $('#id_etiqueta_select').val();

                    // Recorremos cada etiqueta recibida en la respuesta
                    response.forEach(function(etiqueta) {
                        let check = "";
                        if (id_etiqueta_select == etiqueta.id_etiqueta){
                            check = "checked";
                        }
                        // Creamos el HTML para cada etiqueta como radio button
                        var etiquetaHTML = `
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="radio" name="etiqueta" id="etiqueta_${etiqueta.id_etiqueta}" value="${etiqueta.id_etiqueta}" onchange="seleccionarEtiqueta(${etiqueta.id_etiqueta})" ${check}>
                                <label class="form-check-label d-flex align-items-center" for="etiqueta_${etiqueta.id_etiqueta}">
                                    <div class="etiqueta-color" style="background-color: ${etiqueta.color_etiqueta}; width: 20px; height: 20px; border-radius: 50%; margin-right: 10px;"></div>
                                    <div class="etiqueta-nombre">${etiqueta.nombre_etiqueta}</div>
                                </label>
                            </div>
                        `;

                        // Añadimos el HTML de la etiqueta al contenedor
                        $('#lista_etiquetas_asignar').append(etiquetaHTML);
                    });
                } else {
                    // Si no hay etiquetas, mostramos un mensaje
                    $('#lista_etiquetas_asignar').html('<p>No hay etiquetas disponibles.</p>');
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Error al obtener etiquetas:', errorThrown);
            }
        });
    }

    // Función que se ejecuta al seleccionar una etiqueta
    function seleccionarEtiqueta(idEtiqueta) {
        // Crear el formData con el id de la etiqueta seleccionada
        let formData = new FormData();
        formData.append("id_etiqueta", idEtiqueta);

        // Hacer la petición AJAX para asignar la etiqueta
        $.ajax({
            url: SERVERURL + "Pedidos/asignar_etiqueta", // URL para asignar la etiqueta
            type: "POST",
            data: formData,
            processData: false, // No procesar los datos
            contentType: false, // No establecer ningún tipo de contenido
            success: function(response) {
                if (response.status === 200) {
                    toastr.success("Etiqueta asignada correctamente", "NOTIFICACIÓN", {
                        positionClass: "toast-bottom-center"
                    });
                } else {
                    toastr.error("Error al asignar la etiqueta", "NOTIFICACIÓN", {
                        positionClass: "toast-bottom-center"
                    });
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Error al asignar etiqueta:', errorThrown);
            }
        });
    }
</script>