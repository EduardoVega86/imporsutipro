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
        <h1 class="text-2xl font-bold text-center">Inconsistencias</h1>
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
            <table class="hidden" id="tblInconsistencias">
                <thead>
                    <tr>
                        <th class="px-4 py-2">ID</th>
                        <th class="px-4 py-2">Fecha</th>
                        <th class="px-4 py-2">Descripción</th>
                        <th class="px-4 py-2">Estado</th>
                    </tr>
                </thead>
                <tbody id="tblInconsistenciasBody">
                </tbody>
            </table>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://cdn.jsdelivr.net/npm/lodash@4"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5"></script>
    <script src="https://cdn.jsdelivr.net/npm/datatables.net@1.11"></script>
    <script src="https://cdn.jsdelivr.net/npm/datatables.net-bs5@1.11"></script>
    <script src="https://cdn.jsdelivr.net/npm/datatables.net-buttons@2"></script>
    <script src="https://cdn.jsdelivr.net/npm/datatables.net-buttons-bs5@2"></script>
    <script src="https://cdn.jsdelivr.net/npm/datatables.net-buttons-html5@2"></script>
    <script src="https://cdn.jsdelivr.net/npm/datatables.net-buttons-print@2"></script>
    <script src="https://cdn.jsdelivr.net/npm/datatables.net-colreorder@1"></script>
    <script src="https://cdn.jsdelivr.net/npm/datatables.net-colreorder-bs5@1"></script>
    <script src="https://cdn.jsdelivr.net/npm/datatables.net-fixedcolumns@4"></script>
    <script src="https://cdn.jsdelivr.net/npm/datatables.net-fixedcolumns-bs5@4"></script>
    <script src="https://cdn.jsdelivr.net/npm/datatables.net-fixedheader@3"></script>
    <script src="https://cdn.jsdelivr.net/npm/datatables.net-fixedheader-bs5@3"></script>
    <script src="https://cdn.jsdelivr.net/npm/datatables.net-keytable@2"></script>
    <script src="https://cdn.jsdelivr.net/npm/datatables.net-keytable-bs5@2"></script>
    <script src="https://cdn.jsdelivr.net/npm/datatables.net-responsive@2"></script>
    <script src="https://cdn.jsdelivr.net/npm/datatables.net-responsive-bs5@2"></script>
    <script src="https://cdn.jsdelivr.net/npm/datatables.net-rowgroup@1"></script>
    <script src="https://cdn.jsdelivr.net/npm/datatables.net-rowgroup-bs5@1"></script>
    <script src="https://cdn.jsdelivr.net/npm/datatables.net-rowreorder@1"></script>
    <script src="https://cdn.jsdelivr.net/npm/datatables.net-rowreorder-bs5@1"></script>
    <script src="https://cdn.jsdelivr.net/npm/datatables.net-scroller@2"></script>
    <script src="https://cdn.jsdelivr.net/npm/datatables.net-scroller-bs5@2"></script>
    <script src="https://cdn.jsdelivr.net/npm/datatables.net-searchbuilder@1"></script>
    <script src="https://cdn.jsdelivr.net/npm/datatables.net-searchbuilder-bs5@1"></script>
    <script src="https://cdn.jsdelivr.net/npm/datatables.net-searchpanes@1"></script>
    <script src="https://cdn.jsdelivr.net/npm/datatables.net-searchpanes-bs5@1"></script>
    <script src="https://cdn.jsdelivr.net/npm/datatables.net-select@1"></script>
    <script src="https://cdn.jsdelivr.net/npm/datatables.net-select-bs5@1"></script>
    <script src="https://cdn.jsdelivr.net/npm/datatables.net-buttons@2"></script>



</body>

</html>