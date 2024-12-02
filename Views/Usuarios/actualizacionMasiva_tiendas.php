<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Usuarios/css/actualizacionMasiva_tiendas_style.php'; ?>

<div class="full-screen-container">
    <div class="custom-container-fluid mt-4" style="margin-right: 20px;">
        <h1>Actualizacion Masiva</h1>
       
        <button id="despachoBtn" onclick="actualizar()" class="btn btn-success">Actualizar</button>
    </div>
    <div class="guides-list-container mt-4" style="margin-right: auto; margin-left: 30px;">
     
      
    </div>
</div>

<script>
    function actualizar() {
        $.ajax({
            type: "POST",
            url: SERVERURL + "Usuarios/actualizacionMasivaTiendas",
            dataType: "json",
            success: function(response) {
                
            },
            error: function(xhr, status, error) {
                console.error("Error en la solicitud AJAX:", error);
                alert("Hubo un problema al realizar la actualizacion masiva de tiendas");
            },
        });
    }
</script>
<?php require_once './Views/templates/footer.php'; ?>