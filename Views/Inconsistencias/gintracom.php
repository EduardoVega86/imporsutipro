<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inconsistencias</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>

    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });
    </script>
</head>

<body class="bg-gray-100 min-h-screen w-full grid place-content-center items-center">
    <!-- Loading -->
    <div id="loading" class="hidden fixed top-0 left-0 w-full h-full bg-gray-900 bg-opacity-50 z-50 flex justify-center items-center">
        <div class="bg-white p-4 rounded-lg shadow-lg">
            <h1 class="text-2xl font-bold text-center">Cargando...</h1>
            <!-- spinner -->
            <div class="w-20 h-20 border-8 border-t-8 border-gray-200 border-t-blue-500 rounded-full animate-spin mt-4 mx-auto"></div>

        </div>

    </div>
    </div>

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
            <div id="accionesTabla" class="hidden mt-4">
                <div class="mt-4">
                    <label for="filtroResultado" class="block text-sm font-medium text-gray-700">Filtrar por resultado:</label>
                    <select id="filtroResultado" class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="">Todos</option>
                        <option value="Correcto">Correcto</option>
                        <option value="Sin estado en webhook">Sin estado en webhook</option>
                        <option value="Inconsistencia">Inconsistencia</option>
                    </select>
                </div>

                <div class="mt-4">
                    <label for="filtroValor" class="block text-sm font-medium text-gray-700">Filtrar por valor:</label>
                    <select id="filtroValor" class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="">Todos</option>
                        <option value="null">Valor nulo</option>
                        <option value="not_null">Valor no nulo</option>
                    </select>
                </div>

                <div class="mt-4">
                    <button id="btnDescargarExcel" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        Descargar Excel
                    </button>
                </div>
            </div>


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
        const loading = document.getElementById("loading");
        document.getElementById("filtroResultado").addEventListener("change", filtrarTabla);
        document.getElementById("filtroValor").addEventListener("change", filtrarTabla);

        function filtrarTabla() {
            const filtroResultado = document.getElementById("filtroResultado").value;
            const filtroValor = document.getElementById("filtroValor").value;

            const filas = tblInconsistenciasBody.querySelectorAll("tr");

            filas.forEach(fila => {
                const resultado = fila.children[5].textContent.trim();
                const valor = fila.children[3].textContent.trim();

                let mostrar = true;

                if (filtroResultado && resultado !== filtroResultado) {
                    mostrar = false;
                }

                if (filtroValor === "null" && valor !== "null") {
                    mostrar = false;
                } else if (filtroValor === "not_null" && valor === "null") {
                    mostrar = false;
                }

                fila.style.display = mostrar ? "" : "none";
            });
        }
        document.getElementById("btnDescargarExcel").addEventListener("click", () => {
            const filas = Array.from(tblInconsistenciasBody.querySelectorAll("tr"));

            if (filas.length === 0) {
                Toast.fire({
                    icon: 'info',
                    title: 'No hay datos para descargar'
                });
                return;
            }

            const filtroResultado = document.getElementById("filtroResultado").value;
            const filtroValor = document.getElementById("filtroValor").value;

            // Crear un arreglo para almacenar los datos filtrados de la tabla
            const datos = [];

            // Agregar los encabezados de la tabla
            const headers = ["Numero Guia", "Estado Webhook", "Estado Factura", "Valor", "Fecha", "Resultado"];
            datos.push(headers);

            // Filtrar y agregar los datos de las filas
            filas.forEach(fila => {
                const resultado = fila.children[5].textContent.trim();
                const valor = fila.children[3].textContent.trim();

                let incluir = true;

                if (filtroResultado && resultado !== filtroResultado) {
                    incluir = false;
                }

                if (filtroValor === "null" && valor !== "null") {
                    incluir = false;
                } else if (filtroValor === "not_null" && valor === "null") {
                    incluir = false;
                }

                if (incluir) {
                    const columnas = Array.from(fila.children).map(columna => columna.textContent.trim());
                    datos.push(columnas);
                }
            });

            if (datos.length === 1) {
                Toast.fire({
                    icon: 'info',
                    title: 'No hay datos para descargar con los filtros aplicados'
                });
                return;
            }

            // Crear un libro de trabajo y una hoja
            const ws = XLSX.utils.aoa_to_sheet(datos);
            const wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, "Inconsistencias");

            // Generar y descargar el archivo Excel
            XLSX.writeFile(wb, "inconsistencias.xlsx");
        });


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
                loading.classList.remove("hidden");
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
                const formData = new FormData();
                formData.append("tipo", tipoBusqueda);
                if (tipoBusqueda === "mes") {
                    formData.append("fecha", data.fecha);
                } else if (tipoBusqueda === "dia") {
                    formData.append("fecha", data.fecha);
                } else if (tipoBusqueda === "rango") {
                    formData.append("fechaInicio", data.fechaInicio);
                    formData.append("fechaFin", data.fechaFin);
                }
                // Llama a la API
                try {
                    const response = await fetch(url, {
                        method: "POST",

                        body: formData
                    });

                    const resultados = await response.json();
                    if (resultados.length > 0) {
                        loading.classList.add("hidden");
                        mostrarResultados(resultados);
                    } else {
                        loading.classList.add("hidden");
                        mostrarSinResultados();
                    }
                } catch (error) {
                    loading.classList.add("hidden");
                    Toast.fire({
                        icon: 'error',
                        title: 'Ocurrió un error al buscar las inconsistencias'
                    });
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
                    <td class="border px-4 py-2">${fila.estado_facturas}</td>
                    <td class="border px-4 py-2">${fila.valor}</td>
                    <td class="border px-4 py-2">${fila.fecha}</td>
                    <td class="border px-4 py-2">${fila.resultado}</td>
                `;
                    tblInconsistenciasBody.appendChild(row);
                });
                // Mostrar filtros y botón de descarga
                const accionesTabla = document.getElementById("accionesTabla");
                accionesTabla.classList.remove("hidden");
            }

            // Muestra un mensaje de "Sin resultados"
            function mostrarSinResultados() {
                tblInconsistencias.classList.add("hidden");
                Toast.fire({
                    icon: 'info',
                    title: 'No se encontraron resultados'
                });
            }
        });
    </script>


</body>

</html>