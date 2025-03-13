<?php require_once './Views/templates/landing/header.php'; ?>
<?php require_once './Views/templates/landing/css/registro_style.php'; ?>

<div class="header-notice">
    ¡No desaproveches esta oportunidad, únete a <?php echo MARCA; ?>!
</div>
<div class="d-flex flex-column" style="padding: 20px;">
    <div class="imagen_logo">
        <img src="<?php echo LOGIN_IMAGE; ?>" alt="IMPORSUIT" width="300px" height="150px">
    </div>
    <div class="container">
        <div id="token_valido" class="hidden" style="text-align-last: center;">
            <div class="d-flex flex-column">
                <i class="fa-solid fa-face-frown" style="font-size: 60px;"></i>
                <h1>TOKEN NO VALIDO</h1>
            </div>
            <a href="<?php echo SERVERURL ?>login" class="forgot-password">
                <i class="fa-solid fa-arrow-left"></i> Volver
            </a>
        </div>
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

    document.getElementById("multiStepForm").addEventListener("keydown", function(event) {
        if (event.key === "Enter") {
            event.preventDefault();
            if (currentStep === 0) {
                validateEmailAndPassword();
            }
        }
    });

    document.getElementById("multiStepForm").addEventListener("submit", function(event) {
        event.preventDefault();

        validateStoreName(function(isValid) {
            if (isValid) {
                enviarFormulario();
            }
        });
    });

    function validateStoreName(callback) {
        const input = document.getElementById('tienda');
        const label = document.querySelector('label[for="tienda"]');
        const errorDiv = document.getElementById('tienda-error');
        const regex = /^[a-zA-Z]*$/;

        input.value = input.value.toLowerCase();

        if (!regex.test(input.value)) {
            label.classList.remove("text-green-500");
            label.classList.add("text-red-500", "border-red-500");
            errorDiv.textContent = "El nombre de la tienda no puede contener espacios ni caracteres especiales como (/, ^, *, $, @, \\)";
            errorDiv.style.display = "block";
            input.value = input.value.slice(0, -1);
            callback(false);
            return;
        }

        fetch('Acceso/validar_tiendas', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    tienda: input.value
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.exists) {
                    errorDiv.textContent = "Esta tienda ya existe.";
                    errorDiv.style.display = "block";
                    callback(false);
                } else {
                    errorDiv.style.display = "none";
                    callback(true);
                }
            })
    }

    function enviarFormulario() {
        const formData = new FormData(document.getElementById("multiStepForm"));
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
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la respuesta de la API');
                }
                return response.json();
            })
            .then(data => {
                console.log('Success:', data);
                if (data.status == 500) {
                    Swal.fire({
                        icon: 'error',
                        title: data.title,
                        text: data.message
                    });
                } else if (data.status == 200) {

                    Swal.fire({
                        icon: 'success',
                        title: data.title,
                        text: data.message,
                        showConfirmButton: false,
                        timer: 2000
                    }).then(() => {
                        window.location.href = '' + SERVERURL + 'dashboard';
                    });
                }
            })
            .catch((error) => {
                console.error('Error en el registro:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Hubo un problema con el registro.'
                });
            });
    }

    $(document).ready(function() {
        function getQueryParam(param) {
            var urlParams = new URLSearchParams(window.location.search);
            return urlParams.get(param);
        }
    });
</script>

<?php require_once './Views/templates/landing/footer.php'; ?>