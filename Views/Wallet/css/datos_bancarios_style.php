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

    .hr-vertical {
        position: relative;
        border: none;
        height: 1px;
        background: #000;
        margin: 2rem 0;
    }

    .hr-vertical::before {
        content: '';
        position: absolute;
        top: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 1px;
        height: 100px;
        background: #000;
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