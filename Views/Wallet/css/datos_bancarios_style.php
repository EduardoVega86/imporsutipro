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

    .line {
        width: 1px;
        background-color: #000;
        margin-left: 50px;
    }

    .accordion-item {
        width: 100%;
    }

    .accordion-body {
        padding: 0;
    }

    .accordion-content {
        height: 100%;
        overflow: hidden;
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

        .line {
            width: 100%;
            height: 1px;
            margin: 0;
        }
    }
</style>