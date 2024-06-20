<style>
    /* reponsive de secciones */
    .left_right {
        display: flex;
        flex-direction: row;
    }

    .left {
        display: flex;
        flex-direction: column;
        max-width: 40%;
    }

    .right {
        display: flex;
        flex-direction: column;
        max-width: 60%;
    }

    @media (max-width: 768px) {
        .left_right {
            flex-direction: column;
        }

        .left {
            max-width: 100%;
        }

        .right {
            
            max-width: 100%;
        }
    }
</style>