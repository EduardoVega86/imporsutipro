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
                            <option value="1">VIRTECH</option>
                            <option value="2">IMPOREXPRESS</option>
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
                    <button onclick="applyFilters()" class="bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded-md">Aplicar filtros</button>
                </div>
            </div>

            <!-- Botón para mostrar/ocultar filtros -->
            <div class="p-4">
                <button onclick="toggleFilters()" class="bg-gray-700 hover:bg-gray-800 text-white px-4 py-2 rounded-md w-full sm:w-auto">Mostrar/Ocultar Filtros</button>
            </div>
        </section>

        <!-- Tabla -->
        <section class="bg-white rounded-md shadow-md mt-6">
            <table class="min-w-full border-t table-auto">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Acreditar</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Factura</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Cliente</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tienda / Proveedor</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Detalle de Factura</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Monto a Recibir</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Accesos</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Editar</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Eliminar</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Otras Opciones</th>
                    </tr>
                </thead>
                <tbody id="results" class="text-sm text-gray-700 divide-y divide-gray-200">
                    <!-- Aquí se insertarán los resultados dinámicamente -->
                </tbody>
            </table>
        </section>
    </main>

    <script>
        document.getElementById('filters-section').classList.add('hidden');

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

        // Llenar la tabla dinámicamente
        const tableBody = document.getElementById('results');
        datos.forEach(dato => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td class="px-4 py-2">${dato.acreditar}</td>
                <td class="px-4 py-2">${dato.factura}</td>
                <td class="px-4 py-2">${dato.cliente}</td>
                <td class="px-4 py-2">${dato.tienda}</td>
                <td class="px-4 py-2">${dato.detalle}</td>
                <td class="px-4 py-2">${dato.monto}</td>
                <td class="px-4 py-2">${dato.accesos}</td>
                <td class="px-4 py-2">${dato.editar}</td>
                <td class="px-4 py-2">${dato.eliminar}</td>
                <td class="px-4 py-2">${dato.opciones}</td>
            `;
            tableBody.appendChild(row);
        });
    </script>
</body>

</html>