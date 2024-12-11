<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Arreglos</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
</head>

<body class="bg-gray-100 min-h-screen w-full grid place-content-center items-center">
    <div class="bg-white p-4 rounded-lg shadow-lg w-96">
        <h1 class="text-2xl font-bold text-center">Arreglos</h1>
        <div class="flex justify-center mt-4">
            <input type="file" id="file" class="hidden" accept=".xlsx" />
            <button id="btnFile" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Subir archivo</button>
        </div>
    </div>

    <script>
        document.getElementById('btnFile').addEventListener('click', () => {
            document.getElementById('file').click();
        });

        document.getElementById('file').addEventListener('change', async (event) => {
            const file = event.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function(e) {
                const data = new Uint8Array(e.target.result);
                const workbook = XLSX.read(data, {
                    type: 'array'
                });

                // Supongamos que tomas la primera hoja del libro:
                const sheetName = workbook.SheetNames[0];
                const worksheet = workbook.Sheets[sheetName];
                const jsonData = XLSX.utils.sheet_to_json(worksheet, {
                    header: 1
                });

                // jsonData es un array de arrays. La primera fila (header) podría ser algo como ["campo1", "campo2", ...]
                const headerRow = jsonData[3];

                // Ejemplo: enviar la primera cabecera como {"datos": headerRow}
                const payload = {
                    datos: headerRow
                };

                // Realizar el fetch al endpoint. Modificar la URL según corresponda
                fetch('https://guias.imporsuitpro.com/Gintracom', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        // Importante: JSON.stringify convierte objeto JS en JSON correcto
                        body: headerRow
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log('Respuesta del servidor:', data);
                        Swal.fire('Éxito', 'Datos enviados correctamente', 'success');
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire('Error', 'No se pudo enviar la información', 'error');
                    });
            };

            reader.readAsArrayBuffer(file);
        });
    </script>
</body>

</html>