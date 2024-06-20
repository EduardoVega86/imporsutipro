<style>
    /* reponsive de secciones */
    .left_right {
        display: flex;
        flex-direction: row;
    }

    .left {
        display: flex;
        flex-direction: column;
        max-width: 30%;
        min-width: 30%;
    }

    .right {
        display: flex;
        flex-direction: column;
        max-width: 60%;
    }

    .line {
        width: 1px;
        background-color: #000;
        margin-left: 50px;
    }



    @media (max-width: 768px) {
        .left_right {
            flex-direction: column;
        }

        .left {
            max-width: 100%;
            min-width: 100%;
        }

        .right {

            max-width: 100%;
        }

        .line {
            width: 100%;
            height: 1px;
            margin: 0;
        }
    }
</style>