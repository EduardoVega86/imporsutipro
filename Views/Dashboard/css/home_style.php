<style>
    .slider-container {
        width: 100%;
        max-width: 350px;
        height: auto;
    }

    .card {
        border: none;
    }

    .module-card img {
        width: 100%;
        height: auto;
    }

    .schedule-btn {
        background-color: #171931;
        color: white;
        border-radius: 5px;
    }

    .schedule-btn:hover {
        background-color: #0d6efd;
    }

    .icon-circle {
        width: 20%;
        height: 20%;
        border-radius: 50%;
        background-color: #171931;
    }

    .icon-circle img {
        max-width: 80%;
        max-height: 60%;
    }

    .megafono {
        display: flex;
        flex-direction: row;
    }

    .seccion_agendar {
        display: flex;
        flex-direction: row;
    }

    .icono_agendar {
        width: 7%;
    }

    @media (max-width: 768px) {
        .megafono {
            flex-direction: column;
        }

        .seccion_agendar {
            flex-direction: column;
        }

        .icono_agendar {
        width: 25%;
    }
    }
</style>