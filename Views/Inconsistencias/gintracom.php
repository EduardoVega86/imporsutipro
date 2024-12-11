<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inconsistencias</title>
    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="bg-gray-100 min-h-screen w-full grid place-content-center items-center">
    <div class="bg-white p-4 rounded-lg shadow-lg">
        <h1 class="text-2xl font-bold text-center">Inconsistencias de Gintracom</h1>
        <div class="flex justify-center items-center mt-4">
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" id="btnGeneral">General</button>
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-4" id="btnMes">Por mes</button>
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-4" id="btnDia">Por día</button>
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-4" id="btnRango">Por rango</button>
        </div>
        <div class="mt-4">
            <div class="hidden" id="divMes">
                <label for="fechaMes" class="block text-sm font-medium text-gray-700">Mes:</label>
                <input type="month" name="fechaMes" id="fechaMes" class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
            <div class="hidden mt-4" id="divDia">
                <label for="fechaDia" class="block text-sm font-medium text-gray-700">Día:</label>
                <input type="date" name="fechaDia" id="fechaDia" class="mt-1 block
                w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
            <div class="hidden mt-4" id="divRango">
                <label for="fechaInicio" class="block text-sm font-medium text-gray-700">Fecha inicio:</label>
                <input type="date" name="fechaInicio" id="fechaInicio" class="mt-1 block
                w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                <label for="fechaFin" class="block text-sm font-medium text-gray-700 mt-4">Fecha fin:</label>
                <input type="date" name="fechaFin" id="fechaFin" class="mt-1 block
                w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
            <div class="mt-4">
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" id="btnBuscar">Buscar</button>
            </div>
        </div>
        <div class="mt-4">
            <table class="hidden border-collapse border border-gray-300 w-full text-center mt-4" id="tblInconsistencias">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border px-4 py-2">Numero Guia</th>
                        <th class="border px-4 py-2">Estado Webhook</th>
                        <th class="border px-4 py-2">Estado Factura</th>
                        <th class="border px-4 py-2">Valor</th>
                        <th class="border px-4 py-2">Fecha</th>
                        <th class="border px-4 py-2">Resultado</th>
                    </tr>
                </thead>
                <tbody id="tblInconsistenciasBody"></tbody>
            </table>

        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const btnGeneral = document.getElementById("btnGeneral");
            const btnMes = document.getElementById("btnMes");
            const btnDia = document.getElementById("btnDia");
            const btnRango = document.getElementById("btnRango");
            const btnBuscar = document.getElementById("btnBuscar");
            const divMes = document.getElementById("divMes");
            const divDia = document.getElementById("divDia");
            const divRango = document.getElementById("divRango");
            const tblInconsistencias = document.getElementById("tblInconsistencias");
            const tblInconsistenciasBody = document.getElementById("tblInconsistenciasBody");

            let tipoBusqueda = "general";

            // Muestra el formulario correspondiente
            btnGeneral.addEventListener("click", () => {
                tipoBusqueda = "general";
                ocultarFormularios();
            });

            btnMes.addEventListener("click", () => {
                tipoBusqueda = "mes";
                ocultarFormularios();
                divMes.classList.remove("hidden");
            });

            btnDia.addEventListener("click", () => {
                tipoBusqueda = "dia";
                ocultarFormularios();
                divDia.classList.remove("hidden");
            });

            btnRango.addEventListener("click", () => {
                tipoBusqueda = "rango";
                ocultarFormularios();
                divRango.classList.remove("hidden");
            });

            function ocultarFormularios() {
                divMes.classList.add("hidden");
                divDia.classList.add("hidden");
                divRango.classList.add("hidden");
            }

            // Realiza la búsqueda
            btnBuscar.addEventListener("click", async () => {
                let url = "https://desarrollo.imporsuitpro.com/inconsistencias/getInconsistencias_Gintracom"; // Cambiar a la ruta de tu API
                let data = {
                    tipo: tipoBusqueda
                };

                if (tipoBusqueda === "mes") {
                    const fechaMes = document.getElementById("fechaMes").value;
                    data.fecha = fechaMes;
                } else if (tipoBusqueda === "dia") {
                    const fechaDia = document.getElementById("fechaDia").value;
                    data.fecha = fechaDia;
                } else if (tipoBusqueda === "rango") {
                    const fechaInicio = document.getElementById("fechaInicio").value;
                    const fechaFin = document.getElementById("fechaFin").value;
                    data.fechaInicio = fechaInicio;
                    data.fechaFin = fechaFin;
                }

                // Llama a la API
                try {
                    const response = await fetch(url, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                        },
                        body: JSON.stringify(data),
                    });

                    const resultados = await response.json();
                    if (resultados.length > 0) {
                        mostrarResultados(resultados);
                    } else {
                        mostrarSinResultados();
                    }
                } catch (error) {
                    console.error("Error al buscar inconsistencias:", error);
                    alert("Hubo un error al buscar las inconsistencias.");
                }
            });

            // Rellena la tabla con resultados
            function mostrarResultados(resultados) {
                tblInconsistencias.classList.remove("hidden");
                tblInconsistenciasBody.innerHTML = "";

                resultados.forEach((fila) => {
                    const row = document.createElement("tr");
                    row.innerHTML = `
                    <td class="border px-4 py-2">${fila.numero_guia}</td>
                    <td class="border px-4 py-2">${fila.estado_webhook}</td>
                    <td class="border px-4 py-2">${fila.estado_factura}</td>
                    <td class="border px-4 py-2">${fila.valor}</td>
                    <td class="border px-4 py-2">${fila.fecha}</td>
                    <td class="border px-4 py-2">${fila.resultado}</td>
                `;
                    tblInconsistenciasBody.appendChild(row);
                });
            }

            // Muestra un mensaje de "Sin resultados"
            function mostrarSinResultados() {
                tblInconsistencias.classList.add("hidden");
                alert("No se encontraron resultados.");
            }
        });
    </script>


</body>

</html>