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

<body class="bg-[#171931]">
    <main class="container mx-auto p-4">
        <!-- Encabezado -->
        <section class="text-center">
            <h1 class="font-bold text-xl text-white md:text-2xl mb-4">Guias por acreditar</h1>
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
                            <option value="5">FIO</option>

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

            <!-- Cantidad de datos para mostrar y su cambio -->
            <div class="p-4">
                <label for="cantidad" class="block text-sm font-medium text-gray-700">Mostrar:</label>
                <select onchange="loadData()"
                    name="cantidad" id="cantidad" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
        </section>

        <!-- Tabla -->
        <section class="bg-white rounded-md shadow-md mt-6">
            <div class="overflow-x-auto">
                <table class="hidden md:table min-w-full table-auto border-collapse border border-gray-300">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-nowrap text-left text-xs font-medium text-gray-500 uppercase">⭕</th>
                            <th class="px-4 py-2 text-nowrap text-left text-xs font-medium text-gray-500 uppercase">Factura</th>
                            <th class="px-4 py-2 text-nowrap text-left text-xs font-medium text-gray-500 uppercase">Cliente</th>
                            <th class="px-4 py-2 text-nowrap text-left text-xs font-medium text-gray-500 uppercase">Tienda / Proveedor</th>
                            <th class="px-4 py-2 text-nowrap text-left text-xs font-medium text-gray-500 uppercase">Dato de Factura</th>

                            <th class="px-4 py-2 text-nowrap text-left text-xs font-medium text-gray-500 uppercase">Monto a Recibir</th>
                            <th class="px-4 py-2 text-nowrap text-left text-xs font-medium text-gray-500 uppercase">Opciones</th>
                        </tr>
                    </thead>
                    <tbody id="results" class="text-sm text-gray-700 divide-y divide-gray-200">
                        <!-- Aquí se insertarán los resultados dinámicamente -->
                    </tbody>
                </table>
                <div id="card-results" class="block md:hidden space-y-4">
                    <!-- Aquí se llenarán las tarjetas dinámicamente -->
                </div>
            </div>
        </section>
    </main>

    <script>
        document.getElementById('filters-section').classList.add('hidden');

        async function loadData(limit = 10, page = 1) {
            try {

                limit = document.getElementById('cantidad').value;
                const formData = new FormData();
                formData.append('limit', limit);
                formData.append('page', page);

                const response = await fetch("<?php echo SERVERURL ?>wallet/obtenerCabeceras", {
                    method: 'POST',
                    body: formData
                });
                if (!response.ok) {
                    throw new Error("Error al obtener los datos");
                }

                const datos = await response.json();
                populateTable(datos);
                populateCards(datos);
            } catch (error) {
                console.error("Error:", error);
                alert("Hubo un error al cargar los datos.");
            }
        }

        function populateTable(datos) {
            const tableBody = document.getElementById('results');
            tableBody.innerHTML = ""; // Limpia contenido previo
            datos.forEach(dato => {
                const tiendaURL = dato.tienda;
                const url = new URL(tiendaURL);
                const subdominio = url.hostname.split('.')[0];
                let subdominioProveedor = "";
                //proveedor
                if (dato.proveedor != null) {
                    const proveedorURL = dato.proveedor;
                    const urlProveedor = new URL(proveedorURL);
                    subdominioProveedor = urlProveedor.hostname.split('.')[0];
                } else {
                    subdominioProveedor = "--Sin Proveedor--";
                }


                const row = document.createElement('tr');
                // añade clase de color según el estado
                row.classList.add(dato.estado_guia == '7' ? 'bg-green-100' : 'bg-red-100');
                let peso = dato.peso == null ? 0 : dato.peso;
                row.innerHTML = `
                    <td class="px-4 py-2"><input type="checkbox" class="form-checkbox h-4 w-4 text-indigo-600" id="check_${dato.id_cabecera}" 
                        name="check_${dato.id_cabecera}" value="${dato.id_cabecera}"
                     /></td>
                    <td class="px-4 py-2 text-nowrap grid ${dato.cod == '1' ? 'bg-purple-100' : 'bg-red-100'}">
                        <span  class="text-xs font-bold"> ${dato.numero_factura} </span>
                        <span class="text-xs text-gray-500">${dato.guia}</span>
                        <span class="text-xs text-gray-500">(${dato.fecha})</span>
                        <span class="text-xs ${dato.cod == '1' ? 'text-purple-500' : 'text-red-500'}">${dato.cod == '1' ? "Recaudo": "Sin Recaudo"} - ${dato.trayecto} </span>
                    </td>
                    <td class="px-4 py-2 text-nowrap">
                        <div class="grid">
                            <span class="text-xs font-bold">${dato.cliente}</span>
                            <span class="text-xs text-gray-500">${dato.ciudad} - ${dato.provincia}</span>
                        </div>
                    </td>
                    <td class="px-4 py-2 text-nowrap">${subdominio.toUpperCase()}  <p class="text-xs text-gray-400">${subdominioProveedor.toUpperCase()}</p></td>
                    <td class="px-4 py-2 text-nowrap">
                        <div class="grid grid-cols-2 items-center">
                            <span class="text-xs font-bold">Venta:</span>
                             <p class="font-thin text-xs">${dato.total_venta}</p>
                             
                            <span class="text-xs font-bold">Costo:</span>
                             <p class="font-thin text-xs">${dato.costo}</p>

                             <span class="text-xs font-bold">Envio:</span>
                             <p class="font-thin text-xs">${dato.precio_envio}</p>

                            <span class="text-xs font-bold">Peso:</span>
                            <p class="font-thin text-xs">${peso}</p>
                        </div>
                    </td>
                    <td class="px-4 py-2 text-nowrap">${dato.monto_recibir}</td>
                    <td class="px-4 py-2 text-nowrap">Opciones aquí</td>
                `;
                tableBody.appendChild(row);
            });
        }

        function populateCards(datos) {
            const cardResults = document.getElementById('card-results');
            cardResults.innerHTML = ""; // Limpia contenido previo
            datos.forEach(dato => {
                const card = document.createElement('div');
                card.classList.add('border', 'rounded-md', 'p-4', 'shadow-sm', 'bg-white');
                card.innerHTML = `
                    <p class="grid"><strong class="col-span-2"> Factura:</strong> 
                        <span  class="text-indigo-600 underline cursor-pointer"> ${dato.numero_factura} </span>
                        <span class="text-xs text-gray-500">(${dato.guia})</span>
                        <span class="text-xs text-gray-500">(${dato.fecha})</span>
                        <span class="text-xs text-gray-500">${dato.recaudo == 1 ? "Recaudo": "Sin Recaudo"}</span>
                        
                    </p>
                    <p><strong>Cliente:</strong> ${dato.cliente}</p>
                    <p><strong>Tienda:</strong> ${dato.tienda}</p>
                    <p><strong>Monto:</strong> ${dato.monto_recibir}</p>
                `;
                cardResults.appendChild(card);
            });
        }

        loadData(); // Llama a la función para cargar los datos al cargar la página
    </script>
</body>

</html>