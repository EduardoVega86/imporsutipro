<style>
    body {
        background-color: #f8f9fa;
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        height: 100vh;
        overflow-y: auto;
    }

    .cuerpo_mapa {
        margin: 10px;
        width: 100%;
    }

    .contenido {
        display: flex;
        flex-direction: row;
    }

    /* responsive */
    @media (max-width: 768px) {
        .cuerpo_mapa {
            margin-left: 35px;
            width: 90%;
        }

        .contenido {
            flex-direction: column-reverse;
        }
    }
</style>