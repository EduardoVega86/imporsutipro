document.getElementById('trigger-container').addEventListener('click', function() {
    // Mostrar la animación de carga
    document.getElementById('loading').style.display = 'block';

    // Esperar 5 segundos y luego mostrar la sección de enlace generado
    setTimeout(function() {
        document.getElementById('loading').style.display = 'none';
        $.ajax({
            url: SERVERURL + "shopify/generarEnlace",
            type: "GET",
            dataType: "json",
            success: function(response) {
                $("#generador_enlace").val(response.url_imporsuit);
                document.getElementById('enlace-section').style.display = 'block';
            },
            error: function(error) {
                console.error("Error al obtener la lista de bodegas:", error);
            },
        });
    }, 3000);
});