<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Imporsuit</title>
    <link rel="stylesheet" href="<?php echo SERVERURL ?>Views/Dashboard/css/redirect.css" />
    <script>
        window.SERVERURL = "<?php echo SERVERURL ?>";
        window.USUARIO_ID = "<?php echo $_SESSION['id'] ?>";
        window.EMAIL = "<?php echo $_SESSION['user'] ?>";
        window.TOKEN = "<?php echo $_SESSION['token'] ?>";
    </script>
    <script src="<?php echo SERVERURL ?>Views/Dashboard/js/redirect.js"></script>

    <script src="https://cdn.tailwindcss.com"></script>
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
        integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
        crossorigin="anonymous"
        referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-[#171931] w-full grid place-items-center">
    <main class="grid gap-5 mt-10">
        <!-- Sección del logotipo -->
        <section class="flex justify-center items-center">
            <article class="w-64 h-32">
                <img src="https://tiendas.imporsuitpro.com/imgs/LOGOS-IMPORSUIT.png" alt="Logo Imporsuit" />
            </article>
        </section>

        <!-- Sección de tiendas (modal) -->
        <section id="tiendas" class="hidden bg-black/40 w-full z-10 min-h-screen fixed top-0 right-0">
            <article
                id="tiendas_contenedor"
                class="bg-white rounded-md shadow absolute top-1/2 left-1/2 -translate-y-1/2 -translate-x-1/2 px-5 py-3">
                <h3 class="text-center text-xl mt-3 mb-5">Selecciona tu tienda</h3>
                <!-- Contenido dinámico de las tiendas -->
            </article>
        </section>

        <!-- Sección de advertencia y perfil -->
        <section class="absolute top-1 md:top-3 right-5 flex flex-row items-center gap-2 text-end">
            <article class="text-white p-2 rounded-xl border border-white inline-block">
                <span class="dias"></span>
            </article>
            <div class="relative inline-block">
                <img
                    src="https://new.imporsuitpro.com/public/img/img.png"
                    class="profile-pic w-12 h-12 rounded-full cursor-pointer border border-white"
                    id="profilePic"
                    alt="Perfil" />
                <div
                    id="menuDropdown"
                    class="hidden absolute right-0 mt-2 w-48 bg-white text-black rounded-lg shadow-lg z-10">
                    <ul class="py-2">
                        <li class="px-4 py-2 hover:bg-gray-200 cursor-pointer" id="logoutBtn">Cerrar sesión</li>
                    </ul>
                </div>
            </div>
        </section>

        <!-- Botón de WhatsApp -->
        <section class="absolute bottom-5 md:bottom-5 right-4">
            <article class="text-white">
                <a href="https://wa.link/vyghso">
                    <i
                        class="fab fa-whatsapp text-5xl hover:cursor-pointer hover:scale-125 duration-200 hover:text-green-500"></i>
                </a>
            </article>
        </section>

        <!-- Slider de imágenes -->
        <section class="w-full max-w-4xl">
            <article class="relative overflow-hidden">
                <div id="imagenes_slider" class="flex w-[400%] duration-300">
                    <!-- Imágenes dinámicas del slider -->
                </div>
                <button
                    id="anterior"
                    class="absolute top-1/2 left-0 transform -translate-y-1/2 bg-black bg-opacity-50 text-white p-2 rounded-full">
                    <!-- Icono de anterior -->
                    <svg
                        class="w-6 h-6"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </button>
                <button
                    id="siguiente"
                    class="absolute top-1/2 right-0 transform -translate-y-1/2 bg-black bg-opacity-50 text-white p-2 rounded-full">
                    <!-- Icono de siguiente -->
                    <svg
                        class="w-6 h-6"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
            </article>
        </section>

        <!-- Sección de herramientas -->
        <section
            class="gap-y-14 sm:gap-11 grid grid-cols-3 px-10 sm:grid-cols-4 md:grid-cols-5 gap-5 text-center items-center">
            <!-- Artículos dinámicos de las herramientas -->
            <!-- Ejemplo de artículo -->
            <article id="infoaduana" class="herramienta grayscale">
                <span class="hover:cursor-pointer text-white mb-5">Infoaduana</span>
                <img
                    src="https://tiendas.imporsuitpro.com/imgs/herramientas/infoaduana.png"
                    alt="Infoaduana" />
            </article>
            <article id="cotizador" class="herramienta grayscale">
                <span class="hover:cursor-pointer text-white mb-5">Cotizador</span>
                <img
                    src="https://tiendas.imporsuitpro.com/imgs/herramientas/cotizador.png"
                    alt="Cotizador" />
            </article>
            <article id="cursos" class="herramienta grayscale">
                <span class="hover:cursor-pointer text-white mb-5">cursos</span>
                <img
                    src="https://tiendas.imporsuitpro.com/imgs/herramientas/cursos.jpeg"
                    alt="cursos" />
            </article>
            <article id="proveedores" class="herramienta grayscale">
                <span class="hover:cursor-pointer text-white mb-5">proveedores</span>
                <img
                    src="https://tiendas.imporsuitpro.com/imgs/herramientas/proveedores.png"
                    alt="proveedores" />
            </article>
            <article id="sistema" class="herramienta grayscale">
                <span class="hover:cursor-pointer text-white mb-5">Tienda</span>
                <img
                    src="https://tiendas.imporsuitpro.com/imgs/herramientas/sistema.png"
                    alt="sistema" />
            </article>
            <!-- Repetir para otras herramientas -->
        </section>
    </main>

    <!-- Scripts -->
</body>

</html>