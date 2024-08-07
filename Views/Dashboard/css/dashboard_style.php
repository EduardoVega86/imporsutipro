<style>
    body {
        background-color: #f8f9fa;
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        height: 100vh;
        overflow-y: auto;
    }

    .header {
        text-align: center;
        margin: 20px 0;
    }

    .stats-container {
        display: flex;
        flex-direction: column;
        justify-content: space-around;
        margin-bottom: 20px;
        width: 33%;
    }

    .stat-box {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        padding: 20px;
        margin: 10px;
        text-align: center;
        flex: 1 1 calc(25% - 40px);
        width: 50%;
        position: relative;
        /* Añadido para el posicionamiento del icono */
    }

    .icon-container {
        width: 50px;
        height: 50px;
        margin: 0 auto 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        color: #fff;
        font-size: 24px;
    }

    .stat-box h3 {
        margin-top: 10px;
        font-size: 24px;
    }

    .stat-box p {
        margin: 0;
        color: #777;
    }

    .slider-container {
        width: 67%;
        margin-bottom: 20px;
        align-content: center;
        text-align: center;
    }

    .slider-container img {
        width: 80%;
        border-radius: 10px;
    }

    .content-container {
        display: flex;
        justify-content: space-between;
        width: 100%;
    }

    .content-box {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        padding: 20px;
        margin: 10px;
        flex: 1 1 calc(50% - 40px);
        max-width: calc(50% - 40px);
    }

    .table-responsive {
        max-height: 200px;
        overflow-y: auto;
    }

    .table thead th {
        background: #343a40;
        color: #fff;
    }

    /* secciones principales  */
    .banner_estadisticas {
        display: flex;
        flex-direction: row;
    }

    .tablas_estaditicas .content-container {
        display: flex;
        flex-direction: row;
    }

    #pastelChart {
        max-width: 300px;
        max-height: 300px;
    }

    /* responsive */
    @media (max-width: 768px) {
        .banner_estadisticas {
            flex-direction: column-reverse !important;
        }

        .tablas_estaditicas .content-container {
            flex-direction: column !important;
        }

        .stats-container {
            width: 100%;
        }

        .slider-container {
            width: 100%;
        }

        .content-box {
            width: 100%;
            max-width: calc(105% - 40px);
        }

        .slider-container img {
            width: 100%;
        }
    }
</style>