<?php require_once './Views/templates/landing/header.php'; ?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IMORSUIT Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #11143b;
            color: #fff;
            font-family: Arial, sans-serif;
        }

        .container {
            align-self: center;
            max-width: 600px;
            margin: 50px;
            background-color: #fff;
            color: #000;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header img {
            max-width: 150px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-control {
            height: 45px;
            font-size: 16px;
        }

        .btn-primary {
            background-color: #11143b;
            border: none;
        }

        .btn-primary:hover {
            background-color: #0a0b29;
        }

        .imagen_logo {
            text-align: center;
        }

        .header-notice {
            background-color: #000;
            color: #fff;
            text-align: center;
            padding: 10px 0;
            font-size: 18px;
            margin-bottom: 20px;
        }

        .step {
            display: none;
            animation: fadeIn 0.5s forwards;
        }

        .step-active {
            display: block;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @media (max-width: 768px) {
            .menu_derecha {
                display: flex !important;
            }

            .menu_izquierda {
                display: none !important;
            }
        }
    </style>
</head>

<div class="header-notice">
    ¡No desaproveches esta oportunidad, únete a IMPORSUIT!
</div>
<div class="d-flex flex-column" style="padding: 20px;">
    <div class="imagen_logo">
        <img src="https://tiendas.imporsuitpro.com/imgs/logo.png" alt="IMORSUIT" width="300px" height="100px">
    </div>
    <div class="container">
        <div class="header">
            <p>¿Estás listo para unirte a este mundo de ecommerce? Comencemos!!!😉</p>
        </div>
        <form id="multiStepForm">
            <!-- Step 1 -->
            <div class="step step-active">
                <div class="form-group">
                    <label for="nombre">Nombre</label>
                    <input type="text" class="form-control" id="nombre" placeholder="Nombre">
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" placeholder="Email">
                </div>
                <div class="d-flex flex-row">
                    <div class="form-group" style="width: 35%;">
                        <label for="pais">País</label>
                        <select class="form-control" id="pais">
                            <option selected="selected" value="EC"> 🇪🇨 Ecuador (+593)</option>
                            <!-- Add other countries as needed -->
                        </select>
                    </div>
                    <div class="form-group" style="width: 65%;">
                        <label for="telefono">Teléfono</label>
                        <input type="text" class="form-control" id="telefono" placeholder="Teléfono">
                    </div>
                </div>
                <div class="form-group">
                    <label for="contrasena">Contraseña</label>
                    <input type="password" class="form-control" id="contrasena" placeholder="Contraseña">
                </div>
                <div class="form-group">
                    <label for="repetir-contrasena">Repetir Contraseña</label>
                    <input type="password" class="form-control" id="repetir-contrasena" placeholder="Repetir Contraseña">
                </div>
                <button type="button" class="btn btn-primary w-100" onclick="nextStep()">Siguiente</button>
            </div>

            <!-- Step 2 -->
            <div class="step">
                <div class="form-group">
                    <label for="address">Tienda</label>
                    <input type="text" class="form-control" id="tienda" placeholder="Tienda">
                </div>
                <button type="button" class="btn btn-secondary w-100 mb-2" onclick="prevStep()">Anterior</button>
                <button type="submit" class="btn btn-primary w-100">Enviar</button>
            </div>
        </form>
    </div>
</div>

<script>
    let currentStep = 0;
    const steps = document.querySelectorAll(".step");

    function showStep(step) {
        steps.forEach((stepElement, index) => {
            stepElement.classList.remove("step-active");
            if (index === step) {
                stepElement.classList.add("step-active");
            }
        });
    }

    function nextStep() {
        if (currentStep < steps.length - 1) {
            currentStep++;
            showStep(currentStep);
        }
    }

    function prevStep() {
        if (currentStep > 0) {
            currentStep--;
            showStep(currentStep);
        }
    }

    document.getElementById("multiStepForm").addEventListener("submit", function(event) {
        event.preventDefault();
        // Add form submission logic here
        alert("Formulario enviado!");
    });
</script>

</body>

</html>

<?php require_once './Views/templates/landing/footer.php'; ?>