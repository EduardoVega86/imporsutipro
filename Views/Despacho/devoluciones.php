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
        <ul id="guidesList" class="list-group"></ul>
    </div>
</div>

<script>
    function ejecutarDevolucion() {
        var numeroGuia = document.getElementById('numeroGuiaDevolucion').value;
        $.ajax({
            type: "POST",
            url: SERVERURL + "Inventarios/generarDevolucion/" + numeroGuia,
            dataType: "json",
            success: function(response) {
                if (response.status == 500) {
                    toastr.error(
                        ""+response.message,
                        "NOTIFICACIÓN", {
                            positionClass: "toast-bottom-center"
                        }
                    );
                } else if (response.status == 200) {
                    toastr.success(""+response.message, "NOTIFICACIÓN", {
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
    document.getElementById('numeroGuiaDevolucion').addEventListener('keypress', function(event) {
        if (event.key === 'Enter') {
            ejecutarDevolucion();
        }
    });

    // Escuchar el evento 'click' del botón
    document.getElementById('devolucionBtn').addEventListener('click', ejecutarDevolucion);
</script>
<?php require_once './Views/templates/footer.php'; ?>