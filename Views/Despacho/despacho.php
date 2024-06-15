<?php require_once './Views/templates/header.php'; ?>

<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f0f0f0;
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 0;
    }

    .custom-container-fluid {
        background-color: #6c757d;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        text-align: center;
    }

    .custom-container-fluid h1 {
        color: white;
        margin-bottom: 20px;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        color: white;
        margin-bottom: 5px;
    }

    .form-group input {
        width: 100%;
        padding: 10px;
        border: 1px solid #ced4da;
        border-radius: 4px;
    }

    .form-group input::placeholder {
        color: #6c757d;
    }

    .btn {
        background-color: #ffeb3b;
        color: black;
        border: none;
        padding: 10px 20px;
        border-radius: 4px;
        cursor: pointer;
    }
</style>

<div class="custom-container-fluid mt-4">
    <h1>Devolución de guías</h1>
    <div class="form-group">
        <label for="numeroGuia">Número de Guía</label>
        <input type="text" id="numeroGuia" placeholder="Coloca el cursor aquí antes de">
    </div>
    <button class="btn">Devolución</button>
</div>

<?php require_once './Views/templates/footer.php'; ?>