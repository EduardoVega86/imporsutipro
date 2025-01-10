<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guías Masivas</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/png" href="https://tiendas.imporsuitpro.com/imgs/favicon.png">
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
            <h1 class="font-bold text-xl text-white md:text-2xl mb-4">Guias Masivo</h1>
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
                            <th class="px-4 py-2 text-nowrap text-left text-xs font-medium text-gray-500 uppercase">Canal</th>
                            <th class="px-4 py-2 text-nowrap text-left text-xs font-medium text-gray-500 uppercase">F. Pedido</th>
                            <th class="px-4 py-2 text-nowrap text-left text-xs font-medium text-gray-500 uppercase">Id Pedido</th>
                            <th class="px-4 py-2 text-nowrap text-left text-xs font-medium text-gray-500 uppercase">Cliente</th>
                            <th class="px-4 py-2 text-nowrap text-left text-xs font-medium text-gray-500 uppercase">Telefono</th>
                            <th class="px-4 py-2 text-nowrap text-left text-xs font-medium text-gray-500 uppercase">Ciudad</th>
                            <th class="px-4 py-2 text-nowrap text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                            <th class="px-4 py-2 text-nowrap text-left text-xs font-medium text-gray-500 uppercase">Producto</th>
                            <th class="px-4 py-2 text-nowrap text-left text-xs font-medium text-gray-500 uppercase">Cantidad</th>
                            <th class="px-4 py-2 text-nowrap text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                            <th class="px-4 py-2 text-nowrap text-left text-xs font-medium text-gray-500 uppercase">F. Entrega</th>
                            <th class="px-4 py-2 text-nowrap text-left text-xs font-medium text-gray-500 uppercase">Forma de Pago</th>
                            <th class="px-4 py-2 text-nowrap text-left text-xs font-medium text-gray-500 uppercase">Transportadora</th>
                            <th class="px-4 py-2 text-nowrap text-left text-xs font-medium text-gray-500 uppercase">Guia</th>
                            <th class="px-4 py-2 text-nowrap text-left text-xs font-medium text-gray-500 uppercase">Estado Empresa</th>
                            <th class="px-4 py-2 text-nowrap text-left text-xs font-medium text-gray-500 uppercase">Recepción</th>
                            <th class="px-4 py-2 text-nowrap text-left text-xs font-medium text-gray-500 uppercase">Estado Cliente</th>
                            <th class="px-4 py-2 text-nowrap text-left text-xs font-medium text-gray-500 uppercase">Precio Delivery</th>
                            <th class="px-4 py-2 text-nowrap text-left text-xs font-medium text-gray-500 uppercase">Precio Delivery 2</th>
                            <th class="px-4 py-2 text-nowrap text-left text-xs font-medium text-gray-500 uppercase">Precio Call Center</th>
                            <th class="px-4 py-2 text-nowrap text-left text-xs font-medium text-gray-500 uppercase">Comisión Dropshipper</th>
                            <th class="px-4 py-2 text-nowrap text-left text-xs font-medium text-gray-500 uppercase">Comisión Proveedor</th>
                            <th class="px-4 py-2 text-nowrap text-left text-xs font-medium text-gray-500 uppercase">Comisión Fulfillment</th>
                            <th class="px-4 py-2 text-nowrap text-left text-xs font-medium text-gray-500 uppercase">Costo Fulfillment</th>
                            <th class="px-4 py-2 text-nowrap text-left text-xs font-medium text-gray-500 uppercase">Ganancia Dropshipper</th>
                            <th class="px-4 py-2 text-nowrap text-left text-xs font-medium text-gray-500 uppercase">Ganancia Proveedor</th>
                            <th class="px-4 py-2 text-nowrap text-left text-xs font-medium text-gray-500 uppercase">Nota</th>
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
            <div id="download" class="flex justify-center mt-4 hidden">
                <button class="bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 text-nowrap rounded-md">Descargar</button>
            </div>
        </section>
        <section id="pagination-info" class="p-4 text-gray-700">
            <p id="showing-info"></p>
            <div id="pagination-controls" class="flex justify-center space-x-2 mt-4">
                <!-- Controles de paginación dinámicos -->
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

                const data = await response.json();
                const datos = data.data;

                // Actualiza la tabla y tarjetas
                populateTable(datos);
                populateCards(datos);

                // Muestra la información total
                const showingInfo = document.getElementById('showing-info');
                const total = data.total || 0;
                showingInfo.innerText = `Mostrando ${Math.min(limit * page, total)} de ${total} resultados`;

                // Genera los controles de paginación
                renderPaginationControls(limit, page, total);
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
                row.classList.add(dato.estado_guia == '7' || dato.estado_guia == '400' ? 'bg-green-100' : 'bg-red-100');
                let peso = dato.peso == null ? 0 : dato.peso;
                row.innerHTML = `
                    <td class="px-4 py-2 text-nowrap ${dato.cod == '1' ? 'bg-purple-100' : 'bg-red-100'}">
                        <span class="text-xs font-bold"> ${dato.plataforma_importa == 0 ? 'Manual': dato.plataforma_importa} </span>
                    </td>
                    <td class="px-4 py-2 text-nowrap">
                        <span class="text-xs text-gray-500">${dato.fecha}</span>
                    </td>
                    <td class="px-4 py-2 text-nowrap">
                       <span class="text-xs text-gray-500">${dato.numero_factura}</span>
                    </td>
                    <td class="px-4 py-2 text-nowrap">
                       <span class="text-xs text-gray-500">${dato.cliente}</span>
                    </td>
                    <td class="px-4 py-2 text-nowrap">
                       <span class="text-xs text-gray-500">${dato.telefono}</span>
                    </td>
                    
                    <td class="px-4 py-2 text-nowrap">
                       <span class="text-xs text-gray-500">${dato.ciudad}</span>
                    </td>
                    <td class="px-4 py-2 text-nowrap">
                       <span class="text-xs text-gray-500">${dato.provincia}</span>
                    </td>
                    <td class="px-4 py-2 text-nowrap">
                       <span class="text-xs text-gray-500">${dato.nombres_productos}</span>
                    </td>
                    <td class="px-4 py-2 text-nowrap">
                       <span class="text-xs text-gray-500">${dato.cantidad}</span>
                    </td>
                    <td class="px-4 py-2 text-nowrap"> 
                       <span class="text-xs text-gray-500">${Number(dato.cantidad) * Number(dato.precio_venta)}</span>
                    </td>
                    <td class="px-4 py-2 text-nowrap"> 
                       <span class="text-xs text-gray-500">----------</span>
                    </td>
                    <td class="px-4 py-2 text-nowrap"> 
                       <span class="text-xs text-gray-500">${dato.cod == "1" ? "Pago Contraentrega" : "Otros"}</span>
                    </td>
                    
                    <td class="px-4 py-2 text-nowrap"> 
                       <span class="text-xs text-gray-500">${dato.transporte != "0" ? dato.transporte : "No definido"}</span>
                    </td>
                    <td class="px-4 py-2 text-nowrap"> 
                       <span class="text-xs text-gray-500">${dato.guia}</span>
                    </td>
                    <td class="px-4 py-2 text-nowrap"> 
                       <span class="text-xs text-green-500">Activa</span>
                    </td>
                    <td class="px-4 py-2 text-nowrap"> 
                       <span class="text-xs text-gray-500">----------</span>
                    </td>
                    <td class="px-4 py-2 text-nowrap"> 
                       <span class="text-xs text-gray-500">${dato.estado_guia == 7 ? "Entregado" : "Devuelta"}</span>
                    </td>
                    <td class="px-4 py-2 text-nowrap"> 
                       <span class="text-xs text-gray-500">${dato.precio_envio}</span>
                    </td>
                    <td class="px-4 py-2 text-nowrap"> 
                       <span class="text-xs text-gray-500">----------</span>
                    </td>
                    <td class="px-4 py-2 text-nowrap"> 
                       <span class="text-xs text-gray-500">----------</span>
                    </td>
                    <td class="px-4 py-2 text-nowrap"> 
                       <span class="text-xs text-gray-500">----------</span>
                    </td>
                    <td class="px-4 py-2 text-nowrap"> 
                       <span class="text-xs text-gray-500">${dato.costo}</span>
                    </td>
                    <td class="px-4 py-2 text-nowrap"> 
                       <span class="text-xs text-gray-500">${dato.full}</span>
                    </td>
                    <td class="px-4 py-2 text-nowrap"> 
                       <span class="text-xs text-gray-500">${dato.full}</span>
                    </td>
                    <td class="px-4 py-2 text-nowrap"> 
                       <span class="text-xs text-gray-500">${dato.monto_recibir}</span>
                    </td>
                    <td class="px-4 py-2 text-nowrap"> 
                       <span class="text-xs text-gray-500">${dato.costo}</span>
                    </td>
                    <td class="px-4 py-2 text-nowrap"> 
                       <span class="text-xs text-gray-500">----------</span>
                    </td>
                    
                `;
                tableBody.appendChild(row);
            });
            const download = document.getElementById('download');
            download.classList.remove('hidden');
        }

        function renderPaginationControls(limit, currentPage, total) {
            const paginationControls = document.getElementById('pagination-controls');
            paginationControls.innerHTML = ""; // Limpia los controles previos

            const totalPages = Math.ceil(total / limit);
            const maxButtonsToShow = 10;

            // Botón para ir al inicio
            if (currentPage > 1) {
                const firstButton = document.createElement('button');
                firstButton.classList.add('bg-indigo-500', 'hover:bg-indigo-600', 'text-white', 'px-4', 'py-2', 'rounded-md');
                firstButton.innerText = 'Inicio';
                firstButton.onclick = () => loadData(limit, 1);
                paginationControls.appendChild(firstButton);
            }

            // Botón para página anterior
            if (currentPage > 1) {
                const prevButton = document.createElement('button');
                prevButton.classList.add('bg-indigo-500', 'hover:bg-indigo-600', 'text-white', 'px-4', 'py-2', 'rounded-md');
                prevButton.innerText = 'Anterior';
                prevButton.onclick = () => loadData(limit, currentPage - 1);
                paginationControls.appendChild(prevButton);
            }

            // Mostrar un rango limitado de botones de página
            const startPage = Math.max(1, currentPage - Math.floor(maxButtonsToShow / 2));
            const endPage = Math.min(totalPages, startPage + maxButtonsToShow - 1);

            if (startPage > 1) {
                const dotsBefore = document.createElement('span');
                dotsBefore.innerText = '...';
                dotsBefore.classList.add('px-3', 'py-2', 'text-gray-500');
                paginationControls.appendChild(dotsBefore);
            }

            for (let i = startPage; i <= endPage; i++) {
                const pageButton = document.createElement('button');
                pageButton.classList.add('px-3', 'py-2', 'rounded-md');
                if (i === currentPage) {
                    pageButton.classList.add('bg-indigo-600', 'text-white');
                } else {
                    pageButton.classList.add('bg-gray-200', 'text-gray-800');
                    pageButton.onclick = () => loadData(limit, i);
                }
                pageButton.innerText = i;
                paginationControls.appendChild(pageButton);
            }

            if (endPage < totalPages) {
                const dotsAfter = document.createElement('span');
                dotsAfter.innerText = '...';
                dotsAfter.classList.add('px-3', 'py-2', 'text-gray-500');
                paginationControls.appendChild(dotsAfter);
            }

            // Botón para página siguiente
            if (currentPage < totalPages) {
                const nextButton = document.createElement('button');
                nextButton.classList.add('bg-indigo-500', 'hover:bg-indigo-600', 'text-white', 'px-4', 'py-2', 'rounded-md');
                nextButton.innerText = 'Siguiente';
                nextButton.onclick = () => loadData(limit, currentPage + 1);
                paginationControls.appendChild(nextButton);
            }

            // Botón para ir al final
            if (currentPage < totalPages) {
                const lastButton = document.createElement('button');
                lastButton.classList.add('bg-indigo-500', 'hover:bg-indigo-600', 'text-white', 'px-4', 'py-2', 'rounded-md');
                lastButton.innerText = 'Final';
                lastButton.onclick = () => loadData(limit, totalPages);
                paginationControls.appendChild(lastButton);
            }
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