<style>
    /* Contenedor principal con flexbox */
    .content-wrapper {
        display: flex;
        justify-content: space-between;
        gap: 30px;
        max-width: 1600px;
        margin: auto;
        padding: 20px;
    }

    /* Diseño de la tabla */
    .table-container {
        flex: 1;
        background: #ffffff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .table thead {
        background: #007bff;
        color: white;
    }

    .table tbody tr:hover {
        background: #f1f1f1;
    }

    /* Línea vertical divisoria */
    .divider {
        width: 3px;
        background: #007bff;
        height: auto;
        border-radius: 5px;
    }

    /* Diseño de los labels de la factura */
    .info-container {
        flex: 1;
        background: #ffffff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .info-label {
        font-weight: bold;
        color: #007bff;
    }

    .info-value {
        font-size: 18px;
        color: #333;
        padding-left: 10px;
    }