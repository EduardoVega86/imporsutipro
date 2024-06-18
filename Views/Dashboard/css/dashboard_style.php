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
        width: 25%;
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
    }

    .stat-box h3 {
        margin-top: 10px;
        font-size: 24px;
    }

    .slider-container {
        width: 75%;
        margin-bottom: 20px;
    }

    .slider-container img {
        width: 100%;
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

    }
</style>