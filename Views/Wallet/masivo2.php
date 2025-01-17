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
    <style>
        /* Ajuste al estilo del spinner */
        .loader {
            border: 8px solid white;
            /* Bordes transparentes */
            border-top: 8px solid #171931;
            /* Borde superior azul */
            border-radius: 50%;
            /* Forma circular */
            width: 3rem;
            /* Tamaño del spinner */
            height: 3rem;
            /* Tamaño del spinner */
            animation: spin 1s linear infinite;
            /* Rotación */
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
                /* Rotación completa */
            }
        }

        .loader-container {
            z-index: 9999;
            /* Asegura que esté al frente */
        }
    </style>
</head>

<body class="bg-[#171931]">
    <main class="container mx-auto p-4">
        <!-- Encabezado -->
        <section class="text-center">
            <h1 class="font-bold text-xl text-white md:text-2xl mb-4">Guias Masivo</h1>
        </section>
        <div id="loader" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50">
            <div class="loader"></div>
            <p id="loader-message" class="text-white font-bold text-lg"></p>

        </div>

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

            <!-- Cantidad de datos para mostrar y su cambio -->
            <div class="p-4">
                <label for="cantidad" class="block text-sm font-medium text-gray-700">Mostrar:</label>
                <select onchange="loadData()"
                    name="cantidad" id="cantidad" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                    <option value="100">200</option>
                </select>
            </div>
        </section>

        <!-- Tabla -->
        <section class="bg-white rounded-md shadow-md mt-6">
            <div class="overflow-x-auto">
                <table class=" md:table min-w-full table-auto border-collapse border border-gray-300">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-nowrap text-left text-xs font-bold text-gray-500 uppercase bg-red-200">Número Guía</th>
                            <th class="px-4 py-2 text-nowrap text-left text-xs font-bold text-gray-500 uppercase bg-red-200">Transportadora</th>
                            <th class="px-4 py-2 text-nowrap text-left text-xs font-bold text-gray-500 uppercase bg-red-200">Estado Guía</th>
                            <th class="px-4 py-2 text-nowrap text-left text-xs font-bold text-gray-500 uppercase bg-red-200">Trayecto</th>
                            <th class="px-4 py-2 text-nowrap text-left text-xs font-bold text-gray-500 uppercase bg-red-200">Fecha Pedido</th>
                            <th class="px-4 py-2 text-nowrap text-left text-xs font-bold text-gray-500 uppercase bg-red-200">Fecha Creación Guía</th>
                            <th class="px-4 py-2 text-nowrap text-left text-xs font-bold text-gray-500 uppercase bg-red-200">Estado Recolección</th>
                            <th class="px-4 py-2 text-nowrap text-left text-xs font-bold text-gray-500 uppercase bg-red-200">Fecha Recolección</th>
                            <th class="px-4 py-2 text-nowrap text-left text-xs font-bold text-gray-500 uppercase bg-red-200">Fecha Entrega</th>
                            <th class="px-4 py-2 text-nowrap text-left text-xs font-bold text-gray-500 uppercase bg-red-200"># Orden</th>
                            <th class="px-4 py-2 text-nowrap text-left text-xs font-bold text-gray-500 uppercase bg-blue-100">Canal</th>
                            <th class="px-4 py-2 text-nowrap text-left text-xs font-bold text-gray-500 uppercase bg-blue-100">Cliente</th>
                            <th class="px-4 py-2 text-nowrap text-left text-xs font-bold text-gray-500 uppercase bg-blue-100">Telefono</th>
                            <th class="px-4 py-2 text-nowrap text-left text-xs font-bold text-gray-500 uppercase bg-blue-100">Ciudad</th>
                            <th class="px-4 py-2 text-nowrap text-left text-xs font-bold text-gray-500 uppercase bg-blue-100">Provincia</th>
                            <th class="px-4 py-2 text-nowrap text-left text-xs font-bold text-gray-500 uppercase bg-blue-100">Dirección</th>
                            <th class="px-4 py-2 text-nowrap text-left text-xs font-bold text-gray-500 uppercase bg-orange-100">Contiene</th>
                            <th class="px-4 py-2 text-nowrap text-left text-xs font-bold text-gray-500 uppercase bg-orange-100">Cantidad</th>
                            <th class="px-4 py-2 text-nowrap text-left text-xs font-bold text-gray-500 uppercase bg-orange-100">Producto</th>
                            <th class="px-4 py-2 text-nowrap text-left text-xs font-bold text-gray-500 uppercase bg-orange-100">Tienda</th>
                            <th class="px-4 py-2 text-nowrap text-left text-xs font-bold text-gray-500 uppercase bg-orange-100">Proveedor</th>
                            <th class="px-4 py-2 text-nowrap text-left text-xs font-bold text-gray-500 uppercase bg-yellow-400">Valor a Cobrar</th>
                            <th class="px-4 py-2 text-nowrap text-left text-xs font-bold text-gray-500 uppercase bg-yellow-400">Costo Producto</th>
                            <th class="px-4 py-2 text-nowrap text-left text-xs font-bold text-gray-500 uppercase bg-yellow-400">Flete Imporsuit</th>
                            <th class="px-4 py-2 text-nowrap text-left text-xs font-bold text-gray-500 uppercase bg-blue-200">FulFilment</th>
                            <th class="px-4 py-2 text-nowrap text-left text-xs font-bold text-gray-500 uppercase bg-blue-200">Rentabilidad</th>

                            <th class="px-4 py-2 text-nowrap text-left text-xs font-bold text-gray-500 uppercase bg-green-500">Call Center</th>
                            <th class="px-4 py-2 text-nowrap text-left text-xs font-bold text-gray-500 uppercase bg-green-500">Ganancia Dropship</th>
                            <th class="px-4 py-2 text-nowrap text-left text-xs font-bold text-gray-500 uppercase bg-yellow-500">Costo Transportadora</th>
                            <th class="px-4 py-2 text-nowrap text-left text-xs font-bold text-gray-500 uppercase bg-yellow-500">COD Transportadora</th>
                            <th class="px-4 py-2 text-nowrap text-left text-xs font-bold text-gray-500 uppercase bg-yellow-500">Total Transportadora</th>
                            <th class="px-4 py-2 text-nowrap text-left text-xs font-bold text-gray-500 uppercase bg-yellow-500">Base Imporsuit</th>
                            <th class="px-4 py-2 text-nowrap text-left text-xs font-bold text-gray-500 uppercase bg-yellow-500">COD Imporsuit</th>
                            <th class="px-4 py-2 text-nowrap text-left text-xs font-bold text-gray-500 uppercase bg-yellow-500">Flete Imporsuit</th>
                            <th class="px-4 py-2 text-nowrap text-left text-xs font-bold text-gray-500 uppercase bg-yellow-500">Comisión Proveedor</th>
                            <th class="px-4 py-2 text-nowrap text-left text-xs font-bold text-gray-500 uppercase bg-yellow-500">Comisión Fulfilment</th>
                            <th class="px-4 py-2 text-nowrap text-left text-xs font-bold text-gray-500 uppercase bg-yellow-500">Comisión Call Center</th>
                            <th class="px-4 py-2 text-nowrap text-left text-xs font-bold text-gray-500 uppercase bg-yellow-500">Rentabilidad</th>

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
        <section id="pagination-info" class="p-4 text-gray-700">
            <p class="text-white" id="showing-info"></p>
            <div id="pagination-controls" class="flex justify-center space-x-2 mt-4">
                <!-- Controles de paginación dinámicos -->
            </div>
        </section>
        <section class="p-4">
            <button
                id="downloadAllBtn"
                class="bg-indigo-700 hover:bg-indigo-800 text-white px-4 py-2 rounded-md mt-4"
                onclick="downloadAllDataExcel()">
                Descargar Todos
            </button>
        </section>

        <!-- Tabla oculta para descarga -->
        <table id="hidden-table" class="hidden">
            <thead>
                <tr>
                    <th>Número Guía</th>
                    <th>Transportadora</th>
                    <th>Estado Guía</th>
                    <th>Trayecto</th>
                    <th>Fecha Pedido</th>
                    <th>Fecha Creación Guía</th>
                    <th>Estado Recolección</th>
                    <th>Fecha Recolección</th>
                    <th>Fecha Entrega</th>
                    <th># Orden</th>
                    <th>Canal</th>
                    <th>Cliente</th>
                    <th>Telefono</th>
                    <th>Ciudad</th>
                    <th>Provincia</th>

                    <th>Dirección</th>
                    <th>Contiene</th>
                    <th>Cantidad</th>
                    <th>Producto</th>
                    <th>Tienda</th>
                    <th>Proveedor</th>
                    <th>Valor a Cobrar</th>
                    <th>Costo Producto</th>
                    <th>Flete Imporsuit</th>
                    <th>FulFilment</th>
                    <th>Rentabilidad</th>
                    <th>Call Center</th>
                    <th>Ganancia Dropship</th>
                    <th>Costo Transportadora</th>
                    <th>COD Transportadora</th>
                    <th>Total Transportadora</th>
                    <th>Base Imporsuit</th>
                    <th>COD Imporsuit</th>
                    <th>Flete Imporsuit</th>
                    <th>Comisión Proveedor</th>
                    <th>Comisión Fulfilment</th>
                    <th>Comisión Call Center</th>
                    <th>Rentabilidad</th>
                </tr>
            </thead>
            <tbody id="hidden-table-body">
                <!-- Se llenará dinámicamente -->
            </tbody>
        </table>
    </main>

    <script>
        document.getElementById('filters-section').classList.add('hidden');
        let loaderInterval = null; // Para referenciar luego y poder detenerlo
        async function downloadAllDataExcel() {
            // 1) Mostrar loader con mensajes
            const messages = [
                'Obteniendo datos completos...',
                'Generando tabla para Excel...',
                'Ya casi está listo...',
            ];
            showDynamicLoader(true, messages);

            try {
                // 2) Obtener valores de filtros
                const transportadora = document.getElementById('transportadora').value;
                const estado = document.getElementById('estado').value;
                const filtro = document.getElementById('filtro').value.trim();

                // 3) Construir form data sin limit ni page
                const formData = new FormData();
                formData.append('transportadora', transportadora);
                formData.append('estado', estado);
                formData.append('filtro', filtro);
                // Si tu backend requiere otros campos, agrégalos aquí

                // 4) Hacer fetch
                const response = await fetch("<?php echo SERVERURL ?>wallet/obtenerCabecerasAll", {
                    method: 'POST',
                    body: formData
                });

                if (!response.ok) {
                    throw new Error('Error al obtener los datos');
                }

                // 5) Parsear resultado
                const data = await response.json();
                const datos = data.data || [];

                // 6) Llenar tabla oculta
                populateHiddenTable(datos);

                // 7) Generar el Excel a partir de la tabla
                const wb = XLSX.utils.table_to_book(document.getElementById('hidden-table'));
                XLSX.writeFile(wb, 'Reporte_Completo.xlsx');

            } catch (error) {
                console.error('Error:', error);
                alert('Hubo un error al descargar la información completa.');
            } finally {
                // 8) Ocultar loader
                showDynamicLoader(false);
            }
        }

        function showDynamicLoader(show, messages = []) {
            const loader = document.getElementById('loader');
            const loaderMessage = document.getElementById('loader-message');

            if (show) {
                let index = 0;
                // Setea el primer mensaje
                loaderMessage.textContent = messages.length ? messages[0] : 'Procesando...';
                loader.classList.remove('hidden');

                // Si hay varios mensajes, los rotamos
                if (messages.length > 1) {
                    loaderInterval = setInterval(() => {
                        index = (index + 1) % messages.length;
                        loaderMessage.textContent = messages[index];
                    }, 2000); // Cada 2 segundos cambia el mensaje
                }
            } else {
                loader.classList.add('hidden');
                loaderMessage.textContent = '';
                if (loaderInterval) {
                    clearInterval(loaderInterval);
                    loaderInterval = null;
                }
            }
        }

        function showLoader(show) {
            document.getElementById('loader').classList.toggle('hidden', !show);
        }
        async function loadData(limit = 10, page = 1) {
            showLoader(true);

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

                // Muestra la información total
                const showingInfo = document.getElementById('showing-info');
                const total = data.total || 0;
                showingInfo.innerText = `Mostrando ${Math.min(limit * page, total)} de ${total} resultados`;

                // Genera los controles de paginación
                renderPaginationControls(limit, page, total);
            } catch (error) {
                console.error("Error:", error);
                alert("Hubo un error al cargar los datos.");
            } finally {
                showLoader(false);
            }
        }

        function populateHiddenTable(datos) {
            const hiddenBody = document.getElementById('hidden-table-body');
            hiddenBody.innerHTML = '';

            datos.forEach(dato => {
                // Ejemplo de la misma transformación que haces en `populateTable`
                const tiendaURL = dato.tienda;
                const url = new URL(tiendaURL);
                let subdominio = url.hostname.split('.')[0];
                subdominio = subdominio.toUpperCase();

                let subdominioProveedor = '';
                if (dato.proveedor != null) {
                    const proveedorURL = dato.proveedor;
                    const urlProveedor = new URL(proveedorURL);
                    subdominioProveedor = urlProveedor.hostname.split('.')[0].toUpperCase();
                } else {
                    subdominioProveedor = '--Sin Proveedor--';
                }

                // Ejemplo de trayecto / base
                let baseT = 0;
                if (dato.trayecto == 'Zona 1') baseT = 70.00;
                else if (dato.trayecto == 'Zona 2') baseT = 92.80;
                else if (dato.trayecto == 'Zona 3') baseT = 139.20;
                else if (dato.trayecto == 'Zona 4') baseT = 174.00;

                let baseI = 0;
                if (dato.trayecto == 'Zona 1') baseI = 130;
                else if (dato.trayecto == 'Zona 2') baseI = 145;
                else if (dato.trayecto == 'Zona 3') baseI = 190;
                else if (dato.trayecto == 'Zona 4') baseI = 230;

                let total_transportadora = 0;
                let rentabilidad_flete;
                if (dato.estado_guia == 7) {
                    total_transportadora = baseT + (dato.total_venta * 0.02);
                    rentabilidad_flete = (dato.precio_envio) - total_transportadora;
                    rentabilidad_flete = rentabilidad_flete.toFixed(2);
                } else {
                    total_transportadora = baseT;
                    rentabilidad_flete = (dato.precio_envio) - total_transportadora;
                    rentabilidad_flete = rentabilidad_flete.toFixed(2);
                }

                // Crea fila
                const row = document.createElement('tr');
                row.innerHTML = `
            <td>${dato.guia}</td>
            <td>${dato.transporte != '0' ? dato.transporte : 'No definido'}</td>
            <td>${dato.estado_guia == 7 ? 'ENTREGADO' : dato.estado_guia == 9 ? 'DEVUELTO' : 'PROCESANDO'}</td>
            <td>${dato.trayecto}</td>
            <td>${dato.fecha_factura}</td>
            <td>${dato.fecha_guia}</td>
            <td>${dato.recolectado == 1 ? 'SI' : 'NO'}</td>
            <td>${dato.fecha_recoleccion}</td>
            <td>${dato.fecha_entrega}</td>
            <td>${dato.numero_factura}</td>
            <td>${dato.plataforma_importa == 0 ? 'Manual' : dato.plataforma_importa}</td>
            <td>${dato.cliente}</td>
            <td>${dato.telefono}</td>
            <td>${dato.municipio}</td>
            <td>${dato.estado}</td>
            <td>${dato.colonia}</td>
            <td>${dato.codigo_postal}</td>
            <td>${dato.direccion}</td>
            <td>${dato.contiene}</td>
            <td>${dato.cantidad}</td>
            <td>${dato.nombres_productos}</td>
            <td>${subdominio}</td>
            <td>${subdominioProveedor}</td>
            <td>${dato.total_venta}</td>
            <td>${dato.costo}</td>
            <td>${dato.precio_envio}</td>
            <td>${subdominioProveedor != '--Sin Proveedor--' ? dato.full_filme : 0}</td>
            <td>${dato.id_proveedor == dato.id_full ? dato.costo + dato.full_filme : dato.costo - dato.full_filme}</td>
            <td>${dato.call}</td>
            <td>${dato.monto_recibir}</td>
            <td>${baseT}</td>
            <td>${dato.estado_guia == 7 ? (dato.total_venta * 0.02) : 0}</td>
            <td>${dato.estado_guia == 7 ? (dato.total_venta * 0.02) + baseT : 0}</td>
            <td>${baseI}</td>
            <td>${dato.estado_guia == 7 ? (dato.total_venta * 0.04) : 0}</td>
            <td>${dato.precio_envio}</td>
            <td>${0}</td>
            <td>${5}</td>
            <td>${dato.call}</td>
            <td>${rentabilidad_flete}</td>
         `;
                hiddenBody.appendChild(row);
            });
        }

        function populateTable(datos) {
            const tableBody = document.getElementById('results');
            tableBody.innerHTML = ""; // Limpia contenido previo
            datos.forEach(dato => {
                const tiendaURL = dato.tienda;
                const url = new URL(tiendaURL);
                let subdominio = url.hostname.split('.')[0];
                subdominio = subdominio.toUpperCase();
                let subdominioProveedor = "";
                //proveedor
                if (dato.proveedor != null) {
                    const proveedorURL = dato.proveedor;
                    const urlProveedor = new URL(proveedorURL);
                    subdominioProveedor = urlProveedor.hostname.split('.')[0].toUpperCase();
                } else {
                    subdominioProveedor = "--Sin Proveedor--";
                }

                let cobro = 0;
                let baseT = 0;
                if (dato.trayecto == "Zona 1") {
                    baseT = 70.00;
                } else if (dato.trayecto == "Zona 2") {
                    baseT = 92.80;
                } else if (dato.trayecto == "Zona 3") {
                    baseT = 139.20;
                } else if (dato.trayecto == "Zona 4") {
                    baseT = 174.00;
                }
                let baseI = 0;
                if (dato.trayecto == "Zona 1") {
                    baseI = 130;
                } else if (dato.trayecto == "Zona 2") {
                    baseI = 145;
                } else if (dato.trayecto == "Zona 3") {
                    baseI = 190;
                } else if (dato.trayecto == "Zona 4") {
                    baseI = 230;
                }
                let total_transportadora = 0;
                let rentabilidad_flete;
                if (dato.estado_guia == 7) {
                    total_transportadora = baseT + (dato.total_venta * 0.02);


                    rentabilidad_flete = (dato.precio_envio) - total_transportadora;
                    // 2 decimales maximo
                    rentabilidad_flete = rentabilidad_flete.toFixed(2);
                } else {
                    total_transportadora = baseT;
                    rentabilidad_flete = (dato.precio_envio) - total_transportadora;
                    rentabilidad_flete = rentabilidad_flete.toFixed(2);

                }
                const row = document.createElement('tr');
                // añade clase de color según el estado
                let peso = dato.peso == null ? 0 : dato.peso;
                row.innerHTML = `
                    <td class="px-4 py-2 text-nowrap">
                        <span class="text-xs font-bold"> ${dato.guia} </span>
                    </td>
                    <td class="px-4 py-2 text-nowrap">
                       <span class="text-xs text-gray-500">${dato.transporte != "0" ? dato.transporte : "No definido"}</span>
                    </td>
                    <td class="px-4 py-2 text-nowrap">
                       <span class="text-xs text-gray-500">${dato.estado_guia == 7 ? "ENTREGADO": dato.estado_guia == 9 ? "DEVUELTO" : "PROCESAMIENTO"}</span>
                    </td>
                    <td class="px-4 py-2 text-nowrap">
                       <span class="text-xs text-gray-500">${dato.trayecto}</span>
                    </td>
                    <td class="px-4 py-2 text-nowrap">
                       <span class="text-xs text-gray-500">${dato.fecha_factura}</span>
                    </td>
                    
                    <td class="px-4 py-2 text-nowrap">
                       <span class="text-xs text-gray-500">${dato.fecha_guia}</span>
                    </td>
                    <td class="px-4 py-2 text-nowrap">
                       <span class="text-xs text-gray-500">${dato.recolectado == 1 ? "SI" : "NO"}</span>
                    </td>
                    <td class="px-4 py-2 text-nowrap">
                       <span class="text-xs text-gray-500">${dato.fecha_recoleccion}</span>
                    </td>
                    <td class="px-4 py-2 text-nowrap">
                       <span class="text-xs text-gray-500">${dato.fecha_entrega}</span>
                    </td>
                    <td class="px-4 py-2 text-nowrap"> 
                       <span class="text-xs text-gray-500">${dato.numero_factura}</span>
                    </td>
                    <td class="px-4 py-2 text-nowrap"> 
                       <span class="text-xs text-gray-500">${dato.plataforma_importa == 0 ?'Manual' : dato.plataforma_importa}</span>
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
                       <span class="text-xs text-gray-500">${dato.c}</span>
                    </td>
                    <td class="px-4 py-2 text-nowrap"> 
                       <span class="text-xs text-gray-500">${dato.contiene}</span>
                    </td>
                    <td class="px-4 py-2 text-nowrap"> 
                    <span class="text-xs text-gray-500">${dato.cantidad}</span>
                    </td>
                    <td class="px-4 py-2 text-nowrap"> 
                    <span class="text-xs text-gray-500">${dato.nombres_productos}</span>
                    </td>
                    <td class="px-4 py-2 text-nowrap"> 
                       <span class="text-xs text-gray-500">${subdominio}</span>
                    </td>
                    <td class="px-4 py-2 text-nowrap"> 
                       <span class="text-xs text-gray-500">${subdominioProveedor}</span>
                    </td>
                    <td class="px-4 py-2 text-nowrap"> 
                    <span class="text-xs text-gray-500">${dato.total_venta  }</span>
                    </td>
                    <td class="px-4 py-2 text-nowrap"> 
                    <span class="text-xs text-gray-500">${dato.costo}</span>
                    </td>
                    <td class="px-4 py-2 text-nowrap"> 
                       <span class="text-xs text-gray-500">${dato.precio_envio}</span>
                    </td>
                    <td class="px-4 py-2 text-nowrap"> 
                       <span class="text-xs text-gray-500">${subdominioProveedor != "--Sin Proveedor--" ? dato.full_filme : 0}</span>
                    </td>
                    <td class="px-4 py-2 text-nowrap"> 
                       <span class="text-xs text-gray-500">${dato.id_proveedor == dato.id_full ? dato.costo + dato.full_filme : dato.costo - dato.full_filme   }</span>
                    </td>
                    <td class="px-4 py-2 text-nowrap"> 
                       <span class="text-xs text-gray-500">${dato.call}</span>
                    </td>
                    <td class="px-4 py-2 text-nowrap"> 
                       <span class="text-xs text-gray-500">${dato.monto_recibir}</span>
                    </td>
                    <td class="px-4 py-2 text-nowrap"> 
                       <span class="text-xs text-gray-500">${baseT}</span>
                    </td>
                    <td class="px-4 py-2 text-nowrap"> 
                       <span class="text-xs text-gray-500">${dato.estado_guia == 7 ? (dato.total_venta * 0.02) : 0 }</span>
                    </td>
                    <td class="px-4 py-2 text-nowrap"> 
                       <span class="text-xs text-gray-500">${dato.estado_guia == 7 ? (dato.total_venta * 0.02)  + baseT: 0 }</span>
                    </td>
                    <td class="px-4 py-2 text-nowrap"> 
                       <span class="text-xs text-gray-500">${baseI}</span>
                    </td>
                    <td class="px-4 py-2 text-nowrap"> 
                       <span class="text-xs text-gray-500">${dato.estado_guia == 7 ? (dato.total_venta * 0.04) : 0 }</span>
                    </td>
                    <td class="px-4 py-2 text-nowrap"> 
                       <span class="text-xs text-gray-500">${dato.precio_envio }</span>
                    </td>
                    <td class="px-4 py-2 text-nowrap"> 
                       <span class="text-xs text-gray-500">${0}</span>
                    </td>
                    <td class="px-4 py-2 text-nowrap"> 
                       <span class="text-xs text-gray-500">${5}</span>
                    </td>
                    <td class="px-4 py-2 text-nowrap"> 
                       <span class="text-xs text-gray-500">${dato.call}</span>
                    </td>
                    <td class="px-4 py-2 text-nowrap"> 
                       <span class="text-xs text-gray-500">${rentabilidad_flete}</span>
                    </td>
                    
                `;
                tableBody.appendChild(row);
            });
            const download = document.getElementById('downloadAllBtn');
            download.classList.remove('hidden');
        }

        function renderPaginationControls(limit, currentPage, total) {
            const paginationControls = document.getElementById('pagination-controls');
            paginationControls.innerHTML = ""; // Limpia los controles previos

            const totalPages = Math.ceil(total / limit);
            const maxButtonsToShow = 5;

            // Botón para ir al inicio
            if (currentPage > 1) {
                const firstButton = document.createElement('button');
                firstButton.classList.add('bg-indigo-500', 'hover:bg-indigo-600', 'text-white', 'px-4', 'py-2', 'rounded-md');
                firstButton.innerText = '«';
                firstButton.onclick = () => loadData(limit, 1);
                paginationControls.appendChild(firstButton);
            }

            // Botón para página anterior
            if (currentPage > 1) {
                const prevButton = document.createElement('button');
                prevButton.classList.add('bg-indigo-500', 'hover:bg-indigo-600', 'text-white', 'px-4', 'py-2', 'rounded-md');
                prevButton.innerText = '<';
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
                nextButton.innerText = '>';
                nextButton.onclick = () => loadData(limit, currentPage + 1);
                paginationControls.appendChild(nextButton);
            }

            // Botón para ir al final
            if (currentPage < totalPages) {
                const lastButton = document.createElement('button');
                lastButton.classList.add('bg-indigo-500', 'hover:bg-indigo-600', 'text-white', 'px-4', 'py-2', 'rounded-md');
                lastButton.innerText = '»';
                lastButton.onclick = () => loadData(limit, totalPages);
                paginationControls.appendChild(lastButton);
            }
        }

        async function downloadExcel(data) {
            // Ejemplo de array con colores para cada columna (desde 0)
            const headerColors = [
                "FECACA", // col 0
                "FECACA", // col 1
                "FECACA", // ...
                "FECACA", // col 9 -> red-200
                "DBEAFE", // col 10 -> blue-100
                "DBEAFE", // col 11
                "DBEAFE", // ...
                "DBEAFE", // col 17
                "FFEDD5", // col 18 -> orange-100
                "FFEDD5", // col 19
                "FFEDD5", // col 20
                "FFEDD5", // col 21
                "FFEDD5", // col 22
                "FACC15", // col 23 -> yellow-400
                "FACC15", // col 24
                "FACC15", // col 25
                "BFDBFE", // col 26 -> blue-200
                "BFDBFE", // col 27
                "6EE7B7", // col 28 -> green-500
                "6EE7B7", // col 29
                "F59E0B", // col 30 -> yellow-500
                "F59E0B", // col 31
                "F59E0B", // ...
                "F59E0B", // col 39 (si tienes tantas columnas)
            ];

            // 1) Mostrar loader con mensajes
            const messages = [
                'Obteniendo datos completos...',
                'Generando tabla para Excel...',
                'Ya casi está listo...',
            ];
            showDynamicLoader(true, messages);

            try {
                // 2) Obtener valores de filtros
                const transportadora = document.getElementById('transportadora').value;
                const estado = document.getElementById('estado').value;
                const filtro = document.getElementById('filtro').value.trim();

                // 3) Construir form data sin limit ni page
                const formData = new FormData();
                formData.append('transportadora', transportadora);
                formData.append('estado', estado);
                formData.append('filtro', filtro);

                // 4) Hacer fetch
                const response = await fetch("<?php echo SERVERURL ?>wallet/obtenerCabecerasAll", {
                    method: 'POST',
                    body: formData
                });

                if (!response.ok) {
                    throw new Error('Error al obtener los datos');
                }

                // 5) Parsear resultado
                const data = await response.json();
                const datos = data.data || [];

                // 6) Llenar tabla oculta
                populateHiddenTable(datos);

                // 7) Generar el Excel a partir de la tabla
                let wb = XLSX.utils.table_to_book(document.getElementById('hidden-table'));
                let wsName = wb.SheetNames[0];
                let ws = wb.Sheets[wsName];

                // 8) Aplicar estilos a la fila de cabecera
                // Primero decodificamos el rango
                let range = XLSX.utils.decode_range(ws['!ref']);
                let headerRowIndex = range.s.r; // normalmente 0
                // Recorremos las columnas definidas en headerColors
                for (let colIndex = range.s.c; colIndex <= range.e.c; colIndex++) {
                    const cellAddress = {
                        r: headerRowIndex,
                        c: colIndex
                    };
                    const cellRef = XLSX.utils.encode_cell(cellAddress);

                    // Si esa celda existe en la hoja
                    if (ws[cellRef]) {
                        // Obtenemos color según la columna
                        let fillColor = headerColors[colIndex] || "FF0000"; // blanco si no existe

                        // Asignamos estilos
                        // Esto requiere { cellStyles: true } y una versión que soporte estilos
                        ws[cellRef].s = {
                            fill: {
                                patternType: "solid",
                                fgColor: {
                                    rgb: fillColor
                                },
                            },
                            font: {
                                bold: true,
                                color: {
                                    rgb: "000000"
                                } // texto negro
                            },
                            alignment: {
                                horizontal: "center",
                                vertical: "center"
                            },
                            border: {
                                top: {
                                    style: "thin",
                                    color: {
                                        rgb: "000000"
                                    }
                                },
                                bottom: {
                                    style: "thin",
                                    color: {
                                        rgb: "000000"
                                    }
                                },
                                left: {
                                    style: "thin",
                                    color: {
                                        rgb: "000000"
                                    }
                                },
                                right: {
                                    style: "thin",
                                    color: {
                                        rgb: "000000"
                                    }
                                }
                            }
                        };
                    }
                }

                // 9) Descargar el archivo
                XLSX.writeFile(wb, 'Reporte_Completo.xlsx', {
                    cellStyles: true
                });

            } catch (error) {
                console.error('Error:', error);
                alert('Hubo un error al descargar la información completa.');
            } finally {
                // 10) Ocultar loader
                showDynamicLoader(false);
            }
        }




        loadData(); // Llama a la función para cargar los datos al cargar la página
    </script>
</body>

</html>