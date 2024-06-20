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
            height: 100px; /* Adjust the height for vertical line */
            background-color: #000;
            margin: 0 auto; /* Center the line horizontally */
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
                width: 100px; /* Adjust the width for horizontal line */
                height: 1px;
            }
    }
</style>