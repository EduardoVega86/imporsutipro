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

    .boton_eliminar_etiqueta:hover {
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
<div class="modal fade" id="agregar_numero_clienteModal" tabindex="-1" aria-labelledby="agregar_numero_clienteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="agregar_numero_clienteModalLabel"><i class="fas fa-edit"></i> Asignar nuevo telefono</h5>
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
                            <div class="alert alert-warning" role="alert">
                                <strong>Atención:</strong> Ingrese primero el numero de telefono.
                            </div>
                            <label for="numero_telefono_creacion" class="form-label">Teléfono:</label>
                            <input type="text" class="form-control" id="numero_telefono_creacion" placeholder="Ingrese el telefono" oninput="validar_telefono_chat(this.value)">
                            <div id="telefono-error" style="color: red; display: none;">Este telefono ya existe.</div>

                            <input type="hidden" id="id_crear_chat" name="id_crear_chat">


                            <label for="nombre_creacion" class="form-label">Nombre:</label>
                            <input type="text" class="form-control" id="nombre_creacion" placeholder="Ingrese el nombre">

                            <label for="apellido_creacion" class="form-label">Apellido:</label>
                            <input type="text" class="form-control" id="apellido_creacion" placeholder="Ingrese el apellido">

                            <div id="seccion_informacion_numero" style="display: none;">
                                <button type="button" class="btn btn-primary" onclick="agregar_numero_cliente()">Agregar</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Envio de tempale -->
                <div class="card">
                    <div class="card-header">
                        Lista de plantillas whatsapp
                    </div>
                    <div class="card-body">

                        <div id="lista_tempaltes">
                            <select id="lista_templates">
                                <option value="">Selecciona un template</option>
                            </select>

                            <textarea id="template_textarea" rows="8" class="form-control" style="margin-top: 10px;"></textarea>

                            <!-- Contenedor para los campos de placeholders -->
                            <div id="placeholders-container" style="margin-top: 10px;"></div>

                            <button type="button" class="btn btn-primary" style="margin-top: 10px;" onclick="enviarTemplate()">Enviar</button>

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
    function formatearTelefono(telefono) {
        // Si el número tiene exactamente 9 dígitos, agrega "593" al inicio
        if (telefono.length === 9 && /^\d{9}$/.test(telefono)) {
            return '593' + telefono;
        }
        // Si el número empieza con "0", reemplaza el "0" por "593"
        if (telefono.startsWith('0')) {
            return '593' + telefono.slice(1);
        }
        // Si el número empieza con "+593", quita el "+"
        if (telefono.startsWith('+593')) {
            return telefono.slice(1);
        }
        // Si el número ya comienza con "593", lo deja igual
        if (telefono.startsWith('593')) {
            return telefono;
        }
        // Si no cumple con ninguno de los casos anteriores, retorna el número tal cual
        return telefono;

    }

    function validar_telefono_chat(telefono) {
        telefono = formatearTelefono(telefono);
        let formData = new FormData();
        formData.append("telefono", telefono);
        $.ajax({
            url: SERVERURL + "Pedidos/validar_telefonos_clientes",
            type: "POST",
            data: formData,
            processData: false, // No procesar los datos
            contentType: false, // No establecer ningún tipo de contenido
            dataType: "json",
            success: function(response) {
                if (response.status == true) {
                    $("#nombre_creacion").val(response.nombre);
                    $("#apellido_creacion").val(response.apellido);
                    $("#id_crear_chat").val(response.id_cliente);

                    $("#telefono-error").show();
                    $("#seccion_informacion_numero").hide();
                } else {
                    $("#nombre_creacion").val("");
                    $("#apellido_creacion").val("");
                    $("#id_crear_chat").val("");

                    $("#telefono-error").hide();
                    $("#seccion_informacion_numero").show();
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert(errorThrown);
            },
        });
    }

    function agregar_numero_cliente() {
        telefono = formatearTelefono($('#numero_telefono_creacion').val());
        nombre = formatearTelefono($('#nombre_creacion').val());
        apellido = formatearTelefono($('#apellido_creacion').val());

        let formData = new FormData();
        formData.append("telefono", telefono);
        formData.append("nombre", nombre);
        formData.append("apellido", apellido);

        $.ajax({
            url: SERVERURL + "Pedidos/agregar_numero_chat",
            type: "POST",
            data: formData,
            processData: false, // No procesar los datos
            contentType: false, // No establecer ningún tipo de contenido
            dataType: "json",
            success: function(response) {
                if (response.status == 500) {
                    toastr.error(
                        "EL NUMERO NO SE AGREGRO CORRECTAMENTE",
                        "NOTIFICACIÓN", {
                            positionClass: "toast-bottom-center"
                        }
                    );
                } else if (response.status == 200) {
                    toastr.success("NUMERO AGREGADO CORRECTAMENTE", "NOTIFICACIÓN", {
                        positionClass: "toast-bottom-center",
                    });
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert(errorThrown);
            },
        });
    }

    // Enviar el template con los valores ingresados
    async function enviarTemplate() {
        const fromPhoneNumberId = $("#id_whatsapp").val();
        const accessToken = $("#token_configruacion").val();
        const templateName = document.getElementById("lista_templates").value;
        const recipientPhone = formatearTelefono($('#numero_telefono_creacion').val());

        if (!recipientPhone) {
            alert("Debes ingresar un número de destinatario.");
            return;
        }

        // Extraer los valores dentro de los {{}} en el textarea
        const templateText = document.getElementById("template_textarea").value;
        const placeholders = [...templateText.matchAll(/{{(.*?)}}/g)].map(match => match[1]);

        console.log("Valores extraídos:", placeholders);

        // Construir el cuerpo del mensaje para la API de WhatsApp
        const body = {
            messaging_product: "whatsapp",
            to: recipientPhone,
            type: "template",
            template: {
                name: templateName,
                language: {
                    code: "es"
                },
                components: [{
                    type: "body",
                    parameters: placeholders.map(value => ({
                        type: "text",
                        text: value
                    }))
                }]
            }
        };

        try {
            const response = await fetch(
                `https://graph.facebook.com/v19.0/${fromPhoneNumberId}/messages`, {
                    method: "POST",
                    headers: {
                        Authorization: `Bearer ${accessToken}`,
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify(body),
                }
            );

            if (!response.ok) {
                throw new Error(`Error al enviar template: ${response.statusText}`);
            }

            const data = await response.json();
            console.log("Template enviado exitosamente:", data);
            /* alert("Template enviado exitosamente."); */
        } catch (error) {
            console.error("Error al enviar el template:", error);
            /* alert("Error al enviar el template."); */
        }
    }
</script>