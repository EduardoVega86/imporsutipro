<?php require_once './Views/templates/landing/header.php'; ?>

<style>
    body {
        background-color: #171931;
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

<div class="header-notice">
    ¡No desaproveches esta oportunidad, únete a IMPORSUIT!
</div>
<div class="d-flex flex-column" style="padding: 20px;">
    <div class="imagen_logo">
        <img src="https://tiendas.imporsuitpro.com/imgs/logo.png" alt="IMORSUIT" width="300px" height="100px">
    </div>
    <div class="container">
        <div class="header">
            <p>¿Estás listo para unirte al mundo del ecommerce? Comencemos!!!😉</p>
        </div>
        <form id="multiStepForm">
            <!-- Step 1 -->
            <div class="step step-active">
                <div class="form-group">
                    <label for="nombre">Nombre</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre">
                </div>
                <div class="form-group">
                    <label for="correo">Email</label>
                    <input type="email" class="form-control" id="correo" name="correo" placeholder="Email">
                </div>
                <div id="email-error" style="color: red; display: none;">Formato de correo inválido.</div>
                <div class="d-flex flex-row">
                    <div class="form-group" style="width: 35%;">
                        <label for="pais">País</label>
                        <select class="form-control" id="pais" name="pais">
                            <option selected="selected" value="EC"> 🇪🇨 Ecuador (+593)</option>
                            <option value="AR">🇦🇷 Argentina (+54)</option>
                            <option value="BO">🇧🇴 Bolivia (+591)</option>
                            <option value="BR">🇧🇷 Brazil (+55)</option>
                            <option value="CL">🇨🇱 Chile (+56)</option>
                            <option value="CO">🇨🇴 Colombia (+57)</option>
                            <option value="CR">🇨🇷 Costa Rica (+506)</option>
                            <option value="CU">🇨🇺 Cuba (+53)</option>
                            <option value="DO">🇩🇴 Dominican Republic (+1)</option>
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
                            <!-- Add other countries as needed -->
                        </select>
                    </div>
                    <div class="form-group" style="width: 65%;">
                        <label for="telefono">Teléfono</label>
                        <input type="text" class="form-control" id="telefono" name="telefono" placeholder="Teléfono">
                    </div>
                </div>
                <div class="form-group">
                    <label for="contrasena">Contraseña</label>
                    <input type="password" class="form-control" id="contrasena" name="contrasena" placeholder="Contraseña">
                </div>
                <div class="form-group">
                    <label for="repetir-contrasena">Repetir Contraseña</label>
                    <input type="password" class="form-control" id="repetir-contrasena" name="repetir-contrasena" placeholder="Repetir Contraseña">
                </div>
                <div id="password-error" style="color: red; display: none;">Las contraseñas no coinciden.</div>
                <button type="button" class="btn btn-primary w-100" onclick="validateEmailAndPassword()">Siguiente</button>
            </div>

            <!-- Step 2 -->
            <div class="step">
                <div class="form-group">
                    <label for="tienda">Nombre de tu tienda</label>
                    <input type="text" class="form-control" id="tienda" name="tienda" placeholder="Tienda" oninput="validateStoreName()">
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

    function validateEmailAndPassword() {
        const email = document.getElementById("correo").value;
        const emailErrorDiv = document.getElementById("email-error");
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        const password = document.getElementById("contrasena").value;
        const repeatPassword = document.getElementById("repetir-contrasena").value;
        const passwordErrorDiv = document.getElementById("password-error");

        let isValid = true;

        if (emailPattern.test(email)) {
            emailErrorDiv.style.display = "none";
        } else {
            emailErrorDiv.style.display = "block";
            isValid = false;
        }

        if (password === repeatPassword) {
            passwordErrorDiv.style.display = "none";
        } else {
            passwordErrorDiv.style.display = "block";
            isValid = false;
        }

        if (isValid) {
            nextStep();
        }
    }

    function validateStoreName() {
        const input = document.getElementById('tienda');
        const label = document.querySelector('label[for="tienda"]');
        const regex = /^[a-zA-Z]*$/;

        input.value = input.value.toLowerCase();

        if (!regex.test(input.value)) {
            label.classList.remove("text-green-500");
            label.classList.add("text-red-500", "border-red-500");
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "El nombre de la tienda no puede contener espacios ni caracteres especiales como (/, ^, *, $, @, \\)",
                showConfirmButton: false,
                timer: 2000
            }).then(() => {
                input.value = input.value.slice(0, -1);
            });
        } else {
            label.classList.remove("text-red-500", "border-red-500");
            label.classList.add("text-green-500");
        }
    }

    document.getElementById("multiStepForm").addEventListener("submit", function(event) {
        event.preventDefault();

        const formData = new FormData(this);
        const data = {};
        formData.forEach((value, key) => {
            data[key] = value;
        });

        const url = '<?php echo SERVERURL; ?>Acceso/registro'; // Asegúrate de definir SERVERURL en tu backend PHP

        fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                console.log('Success:', data);
                // Mostrar alerta de éxito
                if (data.status == 500) {
                    Swal.fire({
                        icon: 'error',
                        title: data.title,
                        text: data.message
                    });
                } else {
                    Swal.fire({
                        icon: 'success',
                        title: data.title,
                        text: data.message
                    });
                }
            })
            .catch((error) => {
                console.error('Error:', error);
                // Mostrar alerta de error
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Hubo un problema con el registro.'
                });
            });
    });
</script>

<?php require_once './Views/templates/landing/footer.php'; ?>
