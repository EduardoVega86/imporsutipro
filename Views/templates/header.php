<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IMPORSUITPRO</title>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body class="bg-gray-200">
    <div class="flex">
        <!-- Sidebar -->
        <div id="sidebar" class="w-64 h-screen bg-[#191731] w--full p-4 absolute md:relative hidden md:block">
            <h1 class="text-white text-xl font-bold mb-4">FINANSUIT</h1>
            <ul>
                <li><a href="#inicio" class="block text-white py-2 px-4 rounded hover:bg-gray-700">Inicio</a></li>
                <li class="relative">
                    <button class="block w-full text-left text-white py-2 px-4 rounded hover:bg-gray-700 focus:outline-none" onclick="toggleDropdown('dropdown1')">Financiero</button>
                    <ul id="dropdown1" class="hidden bg-gray-800 rounded">
                        <li><a href="#" class="block text-white py-2 px-4 hover:bg-gray-600">Laar</a></li>
                        <li><a href="#general" class="block text-white py-2 px-4 hover:bg-gray-600">General</a></li>
                    </ul>
                </li>
                <li class="relative">
                    <button class="block w-full text-left text-white py-2 px-4 rounded hover:bg-gray-700 focus:outline-none" onclick="toggleDropdown('dropdown2')">Datos</button>
                    <ul id="dropdown2" class="hidden bg-gray-800 rounded">
                        <li><a href="/Admin/cargas" class="block text-white py-2 px-4 hover:bg-gray-600">Cargar
                                Datos</a></li>
                        <li><a href="/Admin/comparar" class="block text-white py-2 px-4 hover:bg-gray-600">Comparativa</a></li>
                        <li><a href="/Admin/excels" class="block text-white py-2 px-4 hover:bg-gray-600">Archivos</a>
                        </li>
                    </ul>
                </li>
                <li><a href="#contacto" class="block text-white py-2 px-4 rounded hover:bg-gray-700">Contacto</a></li>
            </ul>
        </div>
        <!-- Main content -->
        <div class="flex-1 p-6">
            <!-- Your content here -->