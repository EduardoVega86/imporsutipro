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

    /* contenido mapa */
    .contenido_mapa {
        display: flex;
        flex-direction: row;
        gap: 10px;
    }

    .fomulario {
        width: 30%;
    }

    .mapa_google {
        width: 70%;
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

        .contenido_mapa {
            flex-direction: column;
        }

        .fomulario {
            width: 100%;
        }

        .mapa_google {
            width: 100%;
        }
    }
</style>