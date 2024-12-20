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
        <section class="text-center">
            <h1 class="font-bold text-xl mb-4">
                Guias por acreditar
            </h1>
        </section>

        <section class="bg-white rounded-sm shadow-md">
            <!-- Preparar filtros -->
            <div id="filters-section" class="p-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <!-- Filtros -->
                    <div>
                        <label for="transportadora"
                            class="block text-sm font-medium text-gray-700">Transportadora</label>
                        <select name="transportadora" id="transportadora"
                            class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="0">Todas</option>
                            <option value="1">VIRTECH</option>
                            <option value="2">IMPOREXPRESS</option>
                        </select>
                    </div>

                    <div>
                        <label for="estado" class="block text-sm font-medium text-gray-700">Estado</label>
                        <select name="estado" id="estado"
                            class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="0">Todas</option>
                            <option value="1">Pendiente</option>
                            <option value="2">Acreditada</option>
                        </select>
                    </div>

                    <!-- Buscar -->
                    <div>
                        <label for="filtro" class="block text-sm font-medium text-gray-700">Buscar:</label>
                        <input type="text" name="filtro" id="filtro" oninput="onSearchInput()"
                            class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                </div>

                <!-- Botón de aplicar filtros -->
                <div class="flex justify-end mt-4">
                    <button onclick="applyFilters()"
                        class="bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded-md">Aplicar
                        filtros</button>
                </div>
            </div>

            <!-- Botón para mostrar/ocultar filtros -->
            <div class="p-4">
                <button onclick="toggleFilters()"
                    class="bg-gray-700 hover:bg-gray-800 text-white px-4 py-2 rounded-md">Mostrar/Ocultar
                    Filtros</button>
            </div>

            <!-- Tabla de resultados -->
            <table class="min-w-full border-t">
                <thead>
                    <tr>
                        <th
                            class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                            Nombre
                        </th>
                        <th
                            class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                            Fecha de creación
                        </th>
                        <th
                            class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="bg-white border-b">
                        <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
                            Ejemplo
                        </td>
                        <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
                            2024-12-19
                        </td>
                        <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
                            <button class="text-indigo-600 hover:text-indigo-900">Acción</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </section>
    </main>
    <script>
        document.getElementById('filters-section').classList.add('hidden');

        window.onload = async function() {
            const response = await fetch("<?php echo SERVERURL ?>" + 'wallet/obtenerCabeceras');
            const data = await response.json();
            console.log(data);
        }
    </script>
</body>

</html>