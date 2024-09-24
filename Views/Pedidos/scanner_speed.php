<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Pedidos/css/chat_imporsuit_style.php'; ?>

<div class="custom-container-fluid mt-4">
    <div id="scanner-container">
        <h2>Escanea el código de barras</h2>
        <div id="scanner"></div>
        <div id="result">Resultado: <span id="barcode-result">---</span></div>
        <button onclick="startScanner()">Iniciar Escáner</button>
        <button onclick="stopScanner()">Detener Escáner</button>
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
                    facingMode: {
                        exact: "environment"
                    } // Forzar cámara trasera
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
            Quagga.start();
        });

        Quagga.onDetected(function(data) {
            const code = data.codeResult.code;
            document.getElementById("barcode-result").textContent = code;
            alert('Código escaneado: ' + code);
            stopScanner();
        });
    }

    function stopScanner() {
        Quagga.stop();
    }
</script>

<!-- <script src="<?php echo SERVERURL ?>/Views/Pedidos/js/chat_imporsuit.js"></script> -->
<?php require_once './Views/templates/footer.php'; ?>