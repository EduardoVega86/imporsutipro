<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Pedidos/css/chat_imporsuit_style.php'; ?>

<div class="custom-container-fluid mt-4">
    <div id="scanner-container" class="text-center p-4 bg-light rounded shadow-lg">
        <h2 class="mb-4">Escanea el código de barras</h2>
        <div id="scanner" class="mb-4"></div>
        <div id="result" class="mb-4">Resultado: <span id="barcode-result">---</span></div>
        <button class="btn btn-primary me-2" onclick="startScanner()">Iniciar Escáner</button>
        <button class="btn btn-danger" onclick="stopScanner()">Detener Escáner</button>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>
<script>
    function startScanner() {
        Quagga.init({
            inputStream: {
                name: "Live",
                type: "LiveStream",
                target: document.querySelector('#scanner'),
                constraints: {
                    facingMode: "user", // Para laptops usa la cámara frontal
                },
            },
            decoder: {
                readers: ["code_128_reader", "ean_reader", "ean_8_reader", "upc_reader"]
            },
        }, function(err) {
            if (err) {
                console.error(err);
                return;
            }

            // Inicia el escáner
            Quagga.start();

            // Ajustamos los estilos del video y canvas después de que Quagga los crea
            const video = document.querySelector('#scanner video');
            const canvas = document.querySelector('#scanner canvas');

            if (video) {
                video.style.width = '100%';
                video.style.height = 'auto';
                video.style.maxWidth = '640px';
                video.style.maxHeight = '480px';
            }

            if (canvas) {
                canvas.style.width = '100%';
                canvas.style.height = 'auto';
                canvas.style.maxWidth = '640px';
                canvas.style.maxHeight = '480px';
            }
        });

        Quagga.onDetected(function(data) {
            const code = data.codeResult.code;
            document.getElementById("barcode-result").textContent = code;

            // Llamamos a la función para hacer la consulta AJAX
            sendCodeToAPI(code);

            // Detener el escáner después de detectar el código
            stopScanner();
        });
    }

    function stopScanner() {
        Quagga.stop();
    }

    // Función para enviar el código de barras a la API mediante AJAX
    function sendCodeToAPI(barcode) {
        // URL de tu API
        const apiUrl = 'https://miapi.com/barcode'; // Cambia esta URL por la tuya

        // Configuración de la solicitud AJAX usando fetch
        fetch(apiUrl, {
                method: 'POST', // Puedes cambiar a GET si tu API lo requiere
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    barcode: barcode
                }) // Enviar el código de barras en el cuerpo de la solicitud
            })
            .then(response => response.json()) // Convertir la respuesta a JSON
            .then(data => {
                // Manejar la respuesta de la API
                if (data.success) {
                    alert(`Producto encontrado: ${data.productName}`);
                    // Aquí puedes actualizar el DOM con los datos de la API
                } else {
                    alert('Producto no encontrado o error en la consulta');
                }
            })
            .catch(error => {
                console.error('Error al consultar la API:', error);
                alert('Ocurrió un error al consultar la API');
            });
    }
</script>

<!-- <script src="<?php echo SERVERURL ?>/Views/Pedidos/js/chat_imporsuit.js"></script> -->
<?php require_once './Views/templates/footer.php'; ?>