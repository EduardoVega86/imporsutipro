<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Pedidos/css/chat_imporsuit_style.php'; ?>
<?php require_once './Views/Pedidos/Modales/gestionar_speed_repartidores.php'; ?>

<div class="custom-container-fluid mt-4">
    <div id="scanner-container" class="text-center p-4 bg-light rounded shadow-lg" style="display: none;">
        <h2 class="mb-4">Escanea el código de barras</h2>
        <div id="scanner" class="mb-4"></div>
        <div id="result" class="mb-4">Resultado: <span id="barcode-result">---</span></div>

    </div>

    <button class="btn btn-primary me-2" onclick="startScanner()">Iniciar Escáner</button>
    <!-- <button class="btn btn-danger" onclick="stopScanner()">Detener Escáner</button> -->

    <div class="informacion_guia" id="informacion_guia" style="display: none;">
        <h3 class="mb-3" style="text-decoration:underline;"><strong>Informacion de guia</strong></h3>
        <input type="hidden" id="id_factura" name="id_factura">
        <p class="texto_infoVenta"><strong>Nombre:</strong> <span id="nombre"></span></p>
        <p class="texto_infoVenta"><strong>Ciudad:</strong> <span id="ciudad"></span></p>
        <p class="texto_infoVenta"><strong>Direccion:</strong> <span id="direccion"></span></p>
        <p class="texto_infoVenta"><strong>Numero guia:</strong> <span id="numero_guia"></span></p>
        <p class="texto_infoVenta"><strong>Factura:</strong> <span id="factura"></span></p>
        <p class="texto_infoVenta"><strong>Estado:</strong> <span id="estado"></span></p>
        <p class="texto_infoVenta"><strong>Costo Flete:</strong> <span id="flete_costo"></span></p>

        <select class="form-select select-estado-speed" style="max-width: 130px;">
            <option value="0">-- Selecciona estado --</option>
            <option value="2">Generado</option>
            <option value="3">Transito</option>
            <option value="14">Novedad</option>
            <option value="7">Entregado</option>
            <option value="9">Devuelto</option>
        </select>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>
<script>
    function startScanner() {
        $("#scanner-container").show();
        $("#informacion_guia").hide();


        Quagga.init({
            inputStream: {
                name: "Live",
                type: "LiveStream",
                target: document.querySelector('#scanner'),
                constraints: {
                    facingMode: "environment", // Para laptops usa la cámara frontal
                },

            },
            decoder: {
                readers: ["code_128_reader", "ean_reader", "ean_8_reader", "upc_reader"]
            },
        }, function(err) {
            if (err) {
                console.error(err);
                return;
            }

            // Inicia el escáner
            Quagga.start();

            // Ajustamos los estilos del video y canvas después de que Quagga los crea
            const video = document.querySelector('#scanner video');
            const canvas = document.querySelector('#scanner canvas');

            if (video) {
                video.style.width = '100%';
                video.style.height = 'auto';
                video.style.maxWidth = '640px';
                video.style.maxHeight = '480px';
            }

            if (canvas) {
                canvas.style.width = '0%';
                canvas.style.height = 'auto';
                canvas.style.maxWidth = '640px';
                canvas.style.maxHeight = '480px';
            }
        });

        Quagga.onDetected(function(data) {
            const code = data.codeResult.code;
            document.getElementById("barcode-result").textContent = code;

            // Llamamos a la función para hacer la consulta AJAX
            sendCodeToAPI(code);
        });
    }

    function stopScanner() {
        Quagga.stop();
    }

    // Función para enviar el código de barras a la API mediante AJAX
    function sendCodeToAPI(barcode) {
        // URL de tu API
        const apiUrl = 'https://guias.imporsuitpro.com/Speed/buscar/' + barcode; // Cambia esta URL por la tuya

        // Configuración de la solicitud AJAX usando fetch
        fetch(apiUrl, {
                method: 'POST', // Puedes cambiar a GET si tu API lo requiere
                headers: {
                    'Content-Type': 'application/json'
                },
            })
            .then(response => response.json()) // Convertir la respuesta a JSON
            .then(data => {
                // Manejar la respuesta de la API
                if (data.status == 200) {
                    $("#scanner-container").hide();
                    $("#informacion_guia").show();

                    estado = validar_estadoSpeed(data.data.guia);

                    $("#nombre").text(data.data.nombre_destino);
                    $("#direccion").text(data.data.direccion_destino);
                    $("#ciudad").text(data.data.ciudad_destino);
                    $("#numero_guia").text(data.data.guia);
                    $("#factura").text(data.data.factura);
                    $("#estado").text(estado);
                    $("#flete_costo").text(data.data.flete_costo);

                    let formData = new FormData();
                    formData.append("numero_factura", data.data.factura);
                    $.ajax({
                        url: SERVERURL + "speed/buscarFactura",
                        type: "POST",
                        data: formData,
                        processData: false, // No procesar los datos
                        contentType: false, // No establecer ningún tipo de contenido
                        dataType: "json",
                        success: function(response2) {
                            $("#id_factura").val(response2.data.id_factura);
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            alert(errorThrown);
                        },
                    });


                    stopScanner();
                } else {

                }
            })
            .catch(error => {});
    }

    function validar_estadoSpeed(estado) {
        var estado_guia = "";
        if (estado == 2) {
            estado_guia = "generado";
        } else if (estado == 3) {
            estado_guia = "En transito";
        } else if (estado == 7) {
            estado_guia = "Entregado";
        } else if (estado == 9) {
            estado_guia = "Devuelto";
        } else if (estado == 14) {
            estado_guia = "Novedad";
        }

        return estado_guia
    }

    // Event delegation for select change
    document.addEventListener("change", async (event) => {
        if (event.target && event.target.classList.contains("select-estado-speed")) {
            const numeroGuia = $("#numero_guia").text();
            const nuevoEstado = event.target.value;

            const idFactura = $("#id_factura").val();

            if (nuevoEstado == 7) {
                $("#seccion_speed").hide();
                $("#seccion_transito").hide();

                $("#numeroGuia_novedad_speed").val(numeroGuia);
                $("#nuevoEstado_novedad_speed").val(nuevoEstado);
                $("#idFactura_novedad_speed").val(idFactura);

                $("#gestionar_speed_repartidoresModal").modal("show");

            } else if (nuevoEstado == 3) {
                $("#seccion_speed").hide();
                $("#seccion_transito").show();

                $("#numeroGuia_novedad_speed").val(numeroGuia);
                $("#nuevoEstado_novedad_speed").val(nuevoEstado);
                $("#idFactura_novedad_speed").val(idFactura);

                $("#gestionar_speed_repartidoresModal").modal("show");

            } else if (nuevoEstado == 9) {
                $("#seccion_speed").show();
                $("#seccion_transito").hide();

                $("#numeroGuia_novedad_speed").val(numeroGuia);
                $("#nuevoEstado_novedad_speed").val(nuevoEstado);

                $("#idFactura_novedad_speed").val(idFactura);

                $("#tipo_speed").val("recibir").change();

                $("#gestionar_speed_repartidoresModal").modal("show");
            } else if (nuevoEstado == 14) {
                $("#seccion_speed").show();
                $("#seccion_transito").hide();

                $("#numeroGuia_novedad_speed").val(numeroGuia);
                $("#nuevoEstado_novedad_speed").val(nuevoEstado);
                $("#idFactura_novedad_speed").val(idFactura);

                $("#gestionar_speed_repartidoresModal").modal("show");

            } else {
                const formData = new FormData();
                formData.append("estado", nuevoEstado);

                if (nuevoEstado == 9) {
                    $("#tipo_speed").val("recibir").change();
                }

                try {
                    const response = await fetch(
                        `https://guias.imporsuitpro.com/Speed/estado/${numeroGuia}`, {
                            method: "POST",
                            body: formData,
                        }
                    );
                    const result = await response.json();
                    if (result.status == 200) {
                        toastr.success("ESTADO ACTUALIZADO CORRECTAMENTE", "NOTIFICACIÓN", {
                            positionClass: "toast-bottom-center",
                        });

                        reloadDataTable();
                    }
                } catch (error) {
                    console.error("Error al conectar con la API", error);
                    alert("Error al conectar con la API");
                }
            }
        }
    });


    function enviar_speedNovedad() {
        /* var button = document.getElementById("boton_speed");
        button.disabled = true; // Desactivar el botón */

        var tipo_speed = $("#tipo_speed").val();
        var observacion_nov_speed = $("#observacion_nov_speed").val();
        var id_factura = $("#idFactura_novedad_speed").val();
        var numeroGuia_novedad_speed = $("#numeroGuia_novedad_speed").val();
        var nuevoEstado_novedad_speed = $("#nuevoEstado_novedad_speed").val();
        var link_ubicacion_google = $("#link_ubicacion_google").val();

        const inputFoto = document.getElementById('input_foto');
        const file = inputFoto.files[0];

        if (!file) {
            alert('Por favor, toma o selecciona una foto.');
            return;
        }

        let formData = new FormData();
        formData.append("estado", nuevoEstado_novedad_speed);
        formData.append("tipo", tipo_speed);
        formData.append("observacion", observacion_nov_speed);
        formData.append("id_factura", id_factura);
        formData.append('imagen', file);
        formData.append('googlemaps', link_ubicacion_google);

        $.ajax({
            url: SERVERURL + "speed/estados",
            type: "POST",
            data: formData,
            processData: false, // No procesar los datos
            contentType: false, // No establecer ningún tipo de contenido
            dataType: "json",
            success: function(response) {
                if (response.status == 500) {
                    toastr.error(
                        "ESTADO NO AGREGANDO CORRECTAMENTE",
                        "NOTIFICACIÓN", {
                            positionClass: "toast-bottom-center"
                        }
                    );
                } else if (response.status == 200) {
                    toastr.success("ESTADO AGREGADA CORRECTAMENTE", "NOTIFICACIÓN", {
                        positionClass: "toast-bottom-center",
                    });
                    $('#gestionar_speed_repartidoresModal').modal('hide');

                    reiniciarModal();
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert(errorThrown);
            },
        });

    }
</script>

<!-- <script src="<?php echo SERVERURL ?>/Views/Pedidos/js/chat_imporsuit.js"></script> -->
<?php require_once './Views/templates/footer.php'; ?>