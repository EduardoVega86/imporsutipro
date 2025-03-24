<style>
    /* Reemplaza el body si gustas */
    body {
        background-color: #f8f9fa;
        font-family: Arial, sans-serif;
    }

    /* Quitamos margin/padding extra al .container-fluid ya que Bootstrap maneja eso */
    .container-fluid {
        min-height: 100vh;
    }

    /* Tarjetas: ajustamos para que el borde de color aparezca en la izquierda 
   (al estilo “border-start” en Bootstrap 5) 
   y con “border-4” para que sea de un grosor notable. */

    .card {
        border: none;
        /* elimina bordes extra */
        border-radius: 0.5rem;
    }

    /* Alineación de texto dentro de las cards */
    .card h6 {
        font-size: 1rem;
        margin-bottom: 0.5rem;
    }

    /* Ajustamos el tamaño del número grande */
    .card h3 {
        font-size: 1.5rem;
        margin: 0;
    }

    /* Si quieres personalizar la altura mínima de las cards en desktop */
    .card-body {
        min-height: 100px;
    }

    /* Gráfico que se adapta en horizontal */
    #salesChart,
    #distributionChart {
        width: 100% !important;
    }

    /* Respuesta para móvil */
    @media (max-width: 576px) {
        .card h3 {
            font-size: 1.25rem;
        }

        .card h6 {
            font-size: 0.95rem;
        }
    }
</style>