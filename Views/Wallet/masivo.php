<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsive Filters</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        function toggleFilters() {
            const filters = document.getElementById('filters-section');
            filters.classList.toggle('hidden');
        }

        function applyFilters() {
            document.getElementById('filters-section').classList.add('hidden');
        }

        function resetFilters() {
            document.getElementById('transportadora').value = "0";
            document.getElementById('estado').value = "0";
        }

        function onSearchInput() {
            resetFilters();
        }
    </script>
</head>

<body class="bg-gray-200">
    <main class="container mx-auto p-4">
        <!-- Encabezado -->
        <section class="text-center">
            <h1 class="font-bold text-xl md:text-2xl mb-4">Guias por acreditar</h1>
        </section>

        <!-- Filtros -->
        <section class="bg-white rounded-md shadow-md">
            <div id="filters-section" class="p-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <!-- Transportadora -->
                    <div>
                        <label for="transportadora" class="block text-sm font-medium text-gray-700">Transportadora</label>
                        <select name="transportadora" id="transportadora" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="0">Todas</option>
                            <option value="1">LAAR</option>
                            <option value="2">SERVIENTREGA</option>
                            <option value="3">GINTRACOM</option>
                            <option value="4">SPEED</option>
                        </select>
                    </div>

                    <!-- Estado -->
                    <div>
                        <label for="estado" class="block text-sm font-medium text-gray-700">Estado</label>
                        <select name="estado" id="estado" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="0">Todas</option>
                            <option value="1">Pendiente</option>
                            <option value="2">Acreditada</option>
                        </select>
                    </div>

                    <!-- Buscar -->
                    <div>
                        <label for="filtro" class="block text-sm font-medium text-gray-700">Buscar:</label>
                        <input type="text" name="filtro" id="filtro" oninput="onSearchInput()" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                </div>

                <!-- Botón de aplicar filtros -->
                <div class="flex justify-end mt-4">
                    <button onclick="applyFilters()" class="bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 text-nowrap rounded-md">Aplicar filtros</button>
                </div>
            </div>

            <!-- Botón para mostrar/ocultar filtros -->
            <div class="p-4">
                <button onclick="toggleFilters()" class="bg-gray-700 hover:bg-gray-800 text-white px-4 py-2 text-nowrap rounded-md w-full sm:w-auto">Mostrar/Ocultar Filtros</button>
            </div>
        </section>

        <!-- Tabla -->
        <section class="bg-white rounded-md shadow-md mt-6">
            <div class="overflow-x-auto">
                <table class="hidden md:table min-w-full table-auto border-collapse border border-gray-300">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-nowrap text-left text-xs font-medium text-gray-500 uppercase"> ⭕ </th>
                            <th class="px-4 py-2 text-nowrap text-left text-xs font-medium text-gray-500 uppercase">Factura</th>
                            <th class="px-4 py-2 text-nowrap text-left text-xs font-medium text-gray-500 uppercase">Cliente</th>
                            <th class="px-4 py-2 text-nowrap text-left text-xs font-medium text-gray-500 uppercase">Tienda / Proveedor</th>
                            <th class="px-4 py-2 text-nowrap text-left text-xs font-medium text-gray-500 uppercase">Detalle de Factura</th>
                            <th class="px-4 py-2 text-nowrap text-left text-xs font-medium text-gray-500 uppercase">Monto a Recibir</th>
                            <th class="px-4 py-2 text-nowrap text-left text-xs font-medium text-gray-500 uppercase">Accesos</th>
                            <th class="px-4 py-2 text-nowrap text-left text-xs font-medium text-gray-500 uppercase">Editar</th>
                            <th class="px-4 py-2 text-nowrap text-left text-xs font-medium text-gray-500 uppercase">Eliminar</th>
                            <th class="px-4 py-2 text-nowrap text-left text-xs font-medium text-gray-500 uppercase">Otras Opciones</th>
                        </tr>
                    </thead>
                    <tbody id="results" class="text-sm text-gray-700 divide-y divide-gray-200">
                        <!-- Aquí se insertarán los resultados dinámicamente -->
                    </tbody>
                </table>

                <!-- Diseño tipo tarjeta para pantallas pequeñas -->
                <div id="card-results" class="block md:hidden space-y-4">
                    <!-- Aquí se llenarán las tarjetas dinámicamente -->
                </div>
            </div>
        </section>
    </main>

    <script>
        document.getElementById('filters-section').classList.add('hidden');

        document.addEventListener("load", async function() {
            const response = await fetch(<?php echo SERVERURL ?> 'wallet/obtenerCabeceras');
            const data = await response.json();
            console.log(data);
        });
        // Simulación de datos
        const datos = [{
            acreditar: "Sí",
            factura: "COT-0000000116",
            cliente: "NO DESPACHAR",
            tienda: "https://einzas.imporsuitpro.com",
            detalle: "Detalle aquí",
            monto: "$3",
            accesos: "Ver",
            editar: "Editar",
            eliminar: "Eliminar",
            opciones: "Opciones adicionales"
        }];

        const tableBody = document.getElementById('results');
        const cardResults = document.getElementById('card-results');

        datos.forEach(dato => {
            // Fila para tabla
            const row = document.createElement('tr');
            row.innerHTML = `
                <td class="px-4 py-2 text-nowrap">${dato.acreditar}</td>
                <td class="px-4 py-2 text-nowrap">${dato.factura}</td>
                <td class="px-4 py-2 text-nowrap">${dato.cliente}</td>
                <td class="px-4 py-2 text-nowrap">${dato.tienda}</td>
                <td class="px-4 py-2 text-nowrap">${dato.detalle}</td>
                <td class="px-4 py-2 text-nowrap">${dato.monto}</td>
                <td class="px-4 py-2 text-nowrap">${dato.accesos}</td>
                <td class="px-4 py-2 text-nowrap">${dato.editar}</td>
                <td class="px-4 py-2 text-nowrap">${dato.eliminar}</td>
                <td class="px-4 py-2 text-nowrap">${dato.opciones}</td>
            `;
            tableBody.appendChild(row);

            // Tarjeta para diseño móvil
            const card = document.createElement('div');
            card.classList.add('border', 'rounded-md', 'p-4', 'shadow-sm', 'bg-white');
            card.innerHTML = `
                <p><span class="font-semibold">Acreditar:</span> ${dato.acreditar}</p>
                <p><span class="font-semibold">Factura:</span> ${dato.factura}</p>
                <p><span class="font-semibold">Cliente:</span> ${dato.cliente}</p>
                <p><span class="font-semibold">Tienda / Proveedor:</span> ${dato.tienda}</p>
                <p><span class="font-semibold">Detalle de Factura:</span> ${dato.detalle}</p>
                <p><span class="font-semibold">Monto a Recibir:</span> ${dato.monto}</p>
                <p><span class="font-semibold">Accesos:</span> ${dato.accesos}</p>
                <p><span class="font-semibold">Editar:</span> ${dato.editar}</p>
                <p><span class="font-semibold">Eliminar:</span> ${dato.eliminar}</p>
                <p><span class="font-semibold">Otras Opciones:</span> ${dato.opciones}</p>
            `;
            cardResults.appendChild(card);
        });
    </script>
</body>

</html>