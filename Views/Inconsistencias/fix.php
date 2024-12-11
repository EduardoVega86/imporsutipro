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
            reader.onload = async function(e) {
                const data = new Uint8Array(e.target.result);
                const workbook = XLSX.read(data, {
                    type: 'array'
                });

                const sheetName = workbook.SheetNames[0];
                const worksheet = workbook.Sheets[sheetName];
                const jsonData = XLSX.utils.sheet_to_json(worksheet, {
                    header: 1
                });

                // Suponiendo que:
                // - jsonData[0] son las cabeceras
                // - jsonData[i][3] (i > 0) contiene el JSON en forma de cadena
                for (let i = 1; i < jsonData.length; i++) {
                    const cellValue = jsonData[i][3];
                    if (!cellValue) continue; // Si la celda está vacía o no existe

                    // cellValue es una cadena JSON, hay que parsearla:
                    let parsedData;
                    try {
                        parsedData = JSON.parse(cellValue);
                    } catch (error) {
                        console.error(`Error al parsear el JSON en la fila ${i}:`, error);
                        continue;
                    }

                    try {
                        const response = await fetch('https://guias.imporsuitpro.com/Gintracom', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify(parsedData) // Se envía el objeto ya parseado
                        });

                        const respData = await response.json();
                        console.log(`Fila ${i} - Respuesta del servidor:`, respData);
                    } catch (error) {
                        console.error(`Fila ${i} - Error al enviar:`, error);
                    }
                }

                Swal.fire('Éxito', 'Datos enviados correctamente', 'success');
            };

            reader.readAsArrayBuffer(file);
        });
    </script>
</body>

</html>