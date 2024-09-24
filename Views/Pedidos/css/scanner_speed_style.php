<style>
    #scanner-container {
        max-width: 600px;
        margin: 0 auto;
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    #scanner {
        width: 100%;
        height: auto;
        border: 2px solid #007bff;
        border-radius: 5px;
        background-color: #f8f9fa;
        box-sizing: border-box;
        overflow: hidden;
    }

    #result {
        font-size: 1.2em;
        margin-top: 20px;
        font-weight: bold;
        color: #333;
    }

    button {
        width: 45%;
    }

    @media (max-width: 576px) {
        #scanner {
            height: 200px;
        }

        button {
            width: 100%;
            margin-bottom: 10px;
        }
    }
</style>