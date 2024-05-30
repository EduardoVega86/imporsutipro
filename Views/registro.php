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
            overflow: hidden;
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
            transition: transform 0.5s ease-in-out;
            transform: translateX(100%);
            position: absolute;
            width: 100%;
        }

        .step-active {
            display: block;
            transform: translateX(0);
        }

        .step-next {
            transform: translateX(100%);
        }

        .step-prev {
            transform: translateX(-100%);
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
<body>

    <div class="header-notice">
        ¡No desaproveches esta oportunidad, únete a IMPORSUIT!
    </div>
    <div class="d-flex flex-column" style="padding: 20px; position: relative;">
        <div class="imagen_logo">
            <img src="https://tiendas.imporsuitpro.com/imgs/logo.png" alt="IMORSUIT" width="300px" height="100px">
        </div>
        <div class="container">
            <div class="header">
                <p>¿Estás listo para unirte a este mundo de ecommerce? Comencemos!!!😉</p>
            </div>
            <form id="multiStepForm" style="position: relative;">
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
                    <div class="form-group">
                        <label for="pais">País</label>
                        <select class="form-control" id="pais">
                            <option value="AR">🇦🇷 Argentina (+54)</option>
                            <option value="BO">🇧🇴 Bolivia (+591)</option>
                            <option value="BR">🇧🇷 Brazil (+55)</option>
                            <option value="CL">🇨🇱 Chile (+56)</option>
                            <option value="CO">🇨🇴 Colombia (+57)</option>
                            <option value="CR">🇨🇷 Costa Rica (+506)</option>
                            <option value="CU">🇨🇺 Cuba (+53)</option>
                            <option value="DO">🇩🇴 Dominican Republic (+1)</option>
                            <option value="EC">🇪🇨 Ecuador (+593)</option>
                            <option value="SV">🇸🇻 El Salvador (+503)</option>
                            <option value="GT">🇬🇹 Guatemala (+502)</option>
                            <option value="HN">🇭🇳 Honduras (+504)</option>
                            <option value="MX">🇲🇽 Mexico (+52)</option>
                            <option value="NI">🇳🇮 Nicaragua (+505)</option>
                            <option value="PA">🇵🇦 Panama (+507)</option>
                            <option value="PY">🇵🇾 Paraguay (+595)</option>
                            <option value="PE">🇵🇪 Peru (+51)</option>
                            <option value="PR">🇵🇷 Puerto Rico (+1)</option>
                            <option value="UY">🇺🇾 Uruguay (+598)</option>
                            <option value="VE">🇻🇪 Venezuela (+58)</option>
                            <option value="US">🇺🇸 United States (+1)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="telefono">Teléfono</label>
                        <input type="text" class="form-control" id="telefono" placeholder="Teléfono">
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
                        <label for="address">Dirección</label>
                        <input type="text" class="form-control" id="address" placeholder="Dirección">
                    </div>
                    <div class="form-group">
                        <label for="city">Ciudad</label>
                        <input type="text" class="form-control" id="city" placeholder="Ciudad">
                    </div>
                    <div class="form-group">
                        <label for="state">Provincia/Estado</label>
                        <input type="text" class="form-control" id="state" placeholder="Provincia/Estado">
                    </div>
                    <div class="form-group">
                        <label for="zip">Código Postal</label>
                        <input type="text" class="form-control" id="zip" placeholder="Código Postal">
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
                stepElement.classList.remove("step-active", "step-next", "step-prev");
                if (index === step) {
                    stepElement.classList.add("step-active");
                } else if (index > step) {
                    stepElement.classList.add("step-next");
                } else {
                    stepElement.classList.add("step-prev");
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

        // Initial call to show the first step correctly
        showStep(currentStep);
    </script>

</body>
</html>

<?php require_once './Views/templates/landing/footer.php'; ?>