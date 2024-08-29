<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Despacho/css/devoluciones_style.php'; ?>



<div class="full-screen-container">
    <div class="custom-container-fluid mt-4" style="margin-right: 20px;">
        <h1>Devolución de guías</h1>
        <div class="form-group">
            <label for="numeroGuiaDevolucion">Número de Guía</label>
            <input type="text" id="numeroGuiaDevolucion" placeholder="Coloca el cursor aquí antes de">
        </div>
        <button id="devolucionBtn" class="btn">Devolución</button>
    </div>
    <div class="guides-list-container mt-4" style="margin-right: auto; margin-left: 30px;">
        <h2>Guías Ingresadas</h2>
        <button id="generarImpresionBtn" class="btn btn-success">Generar Impresion</button>
        <ul id="guidesList" class="list-group"></ul>
    </div>

</div>

<script>
    // Función para obtener el valor de un parámetro de la URL
    function getParameterByName(name) {
        name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
        var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
        var results = regex.exec(location.search);
        return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
    }

    function ejecutarDevolucion() {
        var numeroGuia = document.getElementById('numeroGuiaDevolucion').value;

        // Verificar si la guía ya está en la lista
        var guiasExistentes = document.querySelectorAll('#guidesList .list-group-item');
        for (var i = 0; i < guiasExistentes.length; i++) {
            if (guiasExistentes[i].childNodes[0].textContent.trim() === numeroGuia) {
                toastr.warning("La guía ya está en la lista", "NOTIFICACIÓN", {
                    positionClass: "toast-bottom-center",
                });
                return; // No agregar la guía si ya existe
            }
        }


        $.ajax({
            type: "POST",
            url: SERVERURL + "Inventarios/generarDevolucion/" + numeroGuia,
            processData: false, // No procesar los datos
            contentType: false, // No establecer ningún tipo de contenido
            success: function(response) {
                response = JSON.parse(response);
                if (response.status == 500) {
                    toastr.error(
                        "" + response.message,
                        "NOTIFICACIÓN", {
                            positionClass: "toast-bottom-center"
                        }
                    );
                } else if (response.status == 200) {
                    toastr.success("" + response.message, "NOTIFICACIÓN", {
                        positionClass: "toast-bottom-center",
                    });

                    agregarGuia(numeroGuia);
                }
            },
            error: function(xhr, status, error) {
                console.error("Error en la solicitud AJAX:", error);
                alert("Hubo un problema al obtener la información de la guia");
            },
        });
    }

    // Función para agregar una guía a la lista
    function agregarGuia(numeroGuia) {
        var listItem = document.createElement('li');
        listItem.className = 'list-group-item';
        listItem.textContent = numeroGuia;
        document.getElementById('guidesList').appendChild(listItem);

        // Limpiar el campo de entrada y enfocar el cursor
        var inputGuia = document.getElementById('numeroGuiaDevolucion');
        inputGuia.value = '';
        inputGuia.focus();
    }

    // Escuchar el evento 'keypress' del input
    document.getElementById('numeroGuiaDevolucion').addEventListener('keypress', function(event) {
        if (event.key === 'Enter') {
            ejecutarDevolucion();
        }
    });

    // Escuchar el evento 'click' del botón
    document.getElementById('devolucionBtn').addEventListener('click', ejecutarDevolucion);


    function generarImpresion() {
        var button = document.getElementById("generarImpresionBtn");
        button.disabled = true; // Desactivar el botón

        var guias = [];
        var listItems = document.querySelectorAll('#guidesList .list-group-item');
        listItems.forEach(function(item) {
            var numeroGuia = item.childNodes[0].textContent.trim(); // Obtener solo el número de guía
            guias.push(numeroGuia);
        });
        var guiasJSON = JSON.stringify(guias, null, 2);
        console.log(guiasJSON);

        let formData = new FormData();
        formData.append("guias", guiasJSON);

        $.ajax({
            type: "POST",
            url: SERVERURL + "/Manifiestos/generarManifiestoDevolucion",
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json",
            success: function(response) {
                if (response.status == 200) {
                    const link = document.createElement("a");
                    link.href = response.download;
                    link.download = "";
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);

                    button.disabled = false;
                    window.location.href = '' + SERVERURL + 'despacho/lista_despachos';
                }
            },
            error: function(xhr, status, error) {
                console.error("Error en la solicitud AJAX:", error);
                alert("Hubo un problema al obtener la información de la categoría");
            },
        });
    }

    // Escuchar el evento 'click' del botón de generar impresión
    document.getElementById('generarImpresionBtn').addEventListener('click', generarImpresion);
</script>
<?php require_once './Views/templates/footer.php'; ?>