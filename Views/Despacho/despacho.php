<?php require_once './Views/templates/header.php'; ?>

<style>
    .full-screen-container {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        background-color: #f0f0f0;
        padding-left: 20px;
    }

    .custom-container-fluid {
        background-color: #171931;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        text-align: center;
    }

    .custom-container-fluid h1 {
        color: white;
        margin-bottom: 20px;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        color: white;
        margin-bottom: 5px;
    }

    .form-group input {
        width: 100%;
        padding: 10px;
        border: 1px solid #ced4da;
        border-radius: 4px;
    }

    .form-group input::placeholder {
        color: #6c757d;
    }

    .btn {
        background-color: #198754;
        color: black;
        border: none;
        padding: 10px 20px;
        border-radius: 4px;
        cursor: pointer;
    }
    @media (max-width: 768px) {
        .full-screen-container{
            flex-direction: column;
            gap: 2px;
        }
    }
</style>

<div class="full-screen-container">
    <div class="custom-container-fluid mt-4" style="margin-right: 20px;">
        <h1>Despacho de guías</h1>
        <div class="form-group">
            <label for="numeroGuia">Número de Guía</label>
            <input type="text" id="numeroGuia" placeholder="Coloca el cursor aquí antes de">
        </div>
        <button id="despachoBtn" class="btn btn-success">Despacho</button>
    </div>
    <div class="guides-list-container mt-4" style="margin-right: auto;">
        <h2>Guías Ingresadas</h2>
        <button id="generarImpresionBtn" class="btn btn-success">Generar Impresion</button>
        <ul id="guidesList" class="list-group"></ul>
    </div>
</div>

<script>
    function ejecutarDespacho() {
        var numeroGuia = document.getElementById('numeroGuia').value;


        $.ajax({
            type: "POST",
            url: SERVERURL + "Inventarios/generarDespacho/" + numeroGuia,
            dataType: "json",
            success: function(response) {
                if (response.status == 500) {
                    toastr.error(
                        ""+ response.message,
                        "NOTIFICACIÓN", {
                            positionClass: "toast-bottom-center"
                        }
                    );
                } else if (response.status == 200) {
                    toastr.success(""+ response.message, "NOTIFICACIÓN", {
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
    }

    // Escuchar el evento 'keypress' del input
    document.getElementById('numeroGuia').addEventListener('keypress', function(event) {
        if (event.key === 'Enter') {
            ejecutarDespacho();
        }
    });

    // Escuchar el evento 'click' del botón
    document.getElementById('despachoBtn').addEventListener('click', ejecutarDespacho);


    // Función para generar JSON con la lista de guías y imprimirlo en consola
    function generarImpresion() {
        var guias = [];
        var listItems = document.querySelectorAll('#guidesList .list-group-item');
        listItems.forEach(function(item) {
            guias.push(item.textContent);
        });
        var guiasJSON = JSON.stringify(guias, null, 2);
        console.log(guiasJSON);
        
        let formData = new FormData();
  formData.append("guias", guiasJSON);

  $.ajax({
    type: "POST",
    url: SERVERURL + "/Manifiestos/generarManifiesto", // Asegúrate de que SERVERURL esté definida
    data: formData,
    processData: false, // Necesario para FormData
    contentType: false, // Necesario para FormData
    dataType: "json",
    success: function (response) {
      console.log("Respuesta del servidor:", response);
      // Manejar la respuesta exitosa aquí
    },
    error: function (xhr, status, error) {
      console.error("Error en la solicitud AJAX:", error);
      alert("Hubo un problema al obtener la información de la categoría");
    },
  });
  
    }
    
    // Escuchar el evento 'click' del botón de generar impresión
    document.getElementById('generarImpresionBtn').addEventListener('click', generarImpresion);
</script>

<?php require_once './Views/templates/footer.php'; ?>