<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Imporsuit</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
        integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
        crossorigin="anonymous" referrerpolicy="no-referrer">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        .whatsapp {
            position: fixed;
            width: 60px;
            height: 60px;
            bottom: 40px;
            right: 40px;
            background-color: #25d366;
            color: #FFF;
            border-radius: 50px;
            text-align: center;
            font-size: 40px;
            z-index: 1000;
        }
    </style>
</head>

<body class="bg-[#171931] w-full grid place-items-center">
    <main class="grid gap-5 mt-10">
        <section class="flex justify-center items-center">
            <article class="w-64 h-32">
                <img src="https://tiendas.imporsuitpro.com/imgs/LOGOS-IMPORSUIT.png" alt="">
            </article>
        </section>

        <!-- Tiendas -->
        <section id="tiendas" class="hidden bg-black/40 w-full z-10 min-h-screen fixed top-0 right-0 ">
            <article id="tiendas_contenedor"
                class="bg-white rounded-md shadow absolute top-1/2 left-1/2 -translate-y-1/2 -translate-x-1/2 px-5 py-3">
                <h3 class="text-center text-xl mt-3 mb-5">
                    Selecciona tu tienda
                </h3>

                <!--   <div class="grid  gap-5 items-center">
                    <div id="tienda1" class="grid grid-cols-2 gap-5 items-center hover:scale-110  hover:text-blue-700">

                        <div class="border-2 hover:border-blue-700 p-5 border-black  text-center">
                            <i class="fas text-2xl fa-store"></i>
                        </div>
                        <span class="text-center">Tienda 1</span>
                    </div>
                    <div id="nueva" class="grid grid-cols-2 gap-5 items-center  hover:scale-110  hover:text-blue-700">

                        <div class=" border-2 hover:border-blue-700 p-5 border-black border-dashed text-center">
                            <i class="fas text-2xl fa-plus"></i>
                        </div>
                        <span class="text-center">Crear tienda</span>
                    </div>
                </div> -->

            </article>
        </section>
        <!-- advertencia de suscripcion -->
        <section class="absolute top-1 md:top-3 right-5 flex flex-row items-center gap-2 text-end">

            <!-- Mensaje de advertencia (visible en móviles y PC) -->
            <article class="text-white p-2 rounded-xl border border-white inline-block">
                <span class="dias"></span>
            </article>

            <!-- Imagen de perfil -->
            <div class="relative inline-block">
                <img src="https://new.imporsuitpro.com/public/img/img.png"
                    class="profile-pic w-12 h-12 rounded-full cursor-pointer border border-white" id="profilePic"
                    alt="Perfil">

                <!-- Menú desplegable -->
                <div id="menuDropdown"
                    class="hidden absolute right-0 mt-2 w-48 bg-white text-black rounded-lg shadow-lg z-10">
                    <ul class="py-2">
                        <li class="px-4 py-2 hover:bg-gray-200 cursor-pointer" id="logoutBtn">Cerrar sesión</li>
                    </ul>
                </div>
            </div>
        </section>

        <!-- botton whatsapp -->
        <section class="absolute bottom-5 md:bottom-5 right-4">
            <article class="text-white">
                <a href="https://wa.link/vyghso">
                    <i
                        class="fab fa-whatsapp text-5xl hover:cursor-pointer hover:scale-125 duration-200 hover:text-green-500"></i>
                </a>
            </article>
        </section>

        <!-- slider -->
        <section class=" w-full max-w-4xl">
            <article class="relative overflow-hidden">
                <div id="imagenes_slider" class="flex w-[400%] duration-300">
                    <!-- Aquí las imágenes del slider -->
                </div>
                <button id="anterior"
                    class="absolute top-1/2 left-0 transform -translate-y-1/2 bg-black bg-opacity-50 text-white p-2 rounded-full">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                        </path>
                    </svg>
                </button>
                <button id="siguiente"
                    class="absolute top-1/2 right-0 transform -translate-y-1/2 bg-black bg-opacity-50 text-white p-2 rounded-full">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                        </path>
                    </svg>
                </button>
            </article>
        </section>

        <section
            class="gap-y-14 sm:gap-11 grid grid-cols-3 px-10  sm:grid-cols-4  md:grid-cols-5 gap-5 text-center items-center">
            <article id="infoaduana"
                class="grayscale w-[75px] sm:h-16 sm:w-16 md:h-32 md:w-32 h-[75px] sm:h-16 sm:w-16 md:h-32 md:w-32 grid justify-center items-center place-items-center ">
                <span class="hover:cursor-pointer text-white mb-5">Infoaduana</span>
                <img class="hover:cursor-pointer hover:scale-110 duration-200 sm:h-16 sm:w-16 md:h-32 md:w-32 h-[75px] w-[75px] hover:shadow-md hover:shadow-white/60 border rounded-xl border-white"
                    src="https://tiendas.imporsuitpro.com/imgs/herramientas/infoaduana.png" alt="">
            </article>
            <article id="cotizador"
                class="grayscale w-[75px] sm:h-16 sm:w-16 md:h-32 md:w-32 h-[75px] sm:h-16 sm:w-16 md:h-32 md:w-32 grid justify-center place-items-center ">
                <span class="hover:cursor-pointer text-white mb-5">Cotizador</span>
                <img class="hover:cursor-pointer hover:scale-110 duration-200 sm:h-16 sm:w-16 md:h-32 md:w-32 h-[75px] w-[75px] hover:shadow-md hover:shadow-white/60 border rounded-xl border-white"
                    src="https://tiendas.imporsuitpro.com/imgs/herramientas/cotizador.png" alt="">
            </article>
            <article id="cursos"
                class="grayscale w-[75px] sm:h-16 sm:w-16 md:h-32 md:w-32 h-[75px] sm:h-16 sm:w-16 md:h-32 md:w-32 grid justify-center place-items-center ">
                <span class="hover:cursor-pointer text-white mb-5">Cursos</span>
                <img class="hover:cursor-pointer hover:scale-110 duration-200 sm:h-16 sm:w-16 md:h-32 md:w-32 h-[75px] w-[75px] hover:shadow-md hover:shadow-white/60 border rounded-xl border-white"
                    src="https://tiendas.imporsuitpro.com/imgs/herramientas/cursos.jpeg" class="rounded-xl" alt="">
            </article>
            <article id="proveedores"
                class="grayscale w-[75px] sm:h-16 sm:w-16 md:h-32 md:w-32 h-[75px] sm:h-16 sm:w-16 md:h-32 md:w-32 grid justify-center place-items-center ">
                <span class="hover:cursor-pointer text-white mb-5">Proveedores</span>
                <img class="hover:cursor-pointer hover:scale-110 duration-200 sm:h-16 sm:w-16 md:h-32 md:w-32 h-[75px] w-[75px] hover:shadow-md hover:shadow-white/60 border rounded-xl border-white"
                    src="https://tiendas.imporsuitpro.com/imgs/herramientas/proveedores.png" class="rounded-xl" alt="">
            </article>
            <article id="tienda"
                class="w-[75px] sm:h-16 sm:w-16 md:h-32 md:w-32 h-[75px] sm:h-16 sm:w-16 md:h-32 md:w-32 grid justify-center place-items-center ">
                <span class="hover:cursor-pointer text-white mb-5">Tienda</span>
                <img class="hover:cursor-pointer hover:scale-110 duration-200 sm:h-16 sm:w-16 md:h-32 md:w-32 h-[75px] w-[75px] hover:shadow-md hover:shadow-white/60 border rounded-xl border-white"
                    src="https://tiendas.imporsuitpro.com/imgs/herramientas/sistema.png" alt="">
            </article>
        </section>
    </main>

    <script>
        document.addEventListener("DOMContentLoaded", async () => {
            await fetch('https://herramientas.imporfactory.app/suscripciones', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        email: "<?php echo $_SESSION['user'] ?>"
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.dias === 0) {
                        document.querySelector('.dias').innerHTML = "¡Tu suscripción vence hoy!";
                        return;
                    }
                    if (data.status === 'error') {
                        document.querySelector('.dias').innerHTML = "¡No tienes suscripción activa!";
                        return;
                    }
                    document.querySelectorAll('.dias').forEach((dias) => {
                        dias.innerHTML = `Tu suscripción vence en ${data.suscripciones[0].dias} días`;
                    });
                    /* barre  las suscripciones*/
                    data.suscripciones.forEach((suscripcion) => {
                        if (suscripcion.sistema === 'Infoaduana' && suscripcion.dias > 0) {
                            document.querySelector('#infoaduana').classList.remove('grayscale');
                        }
                        if (suscripcion.sistema === 'Cotizador' && suscripcion.dias > 0) {
                            document.querySelector('#cotizador').classList.remove('grayscale');
                        }
                        if (suscripcion.sistema === 'METODOLOGIA ECOMMERCE 3EN1' || suscripcion.sistema === 'METODOLOGIA IMPORTADOR 3 EN 1') {
                            document.querySelector('#cursos').classList.remove('grayscale');
                        }
                        if (suscripcion.sistema === 'Productos' && suscripcion.dias > 0) {
                            document.querySelector('#proveedores').classList.remove('grayscale');
                        }

                    });
                })
                .catch(error => console.error('Error en la petición:', error));



            let id_usuario = "<?php echo $_SESSION['id'] ?>";
            const frm = new FormData();
            frm.append("id_usuario", id_usuario);
            await fetch("<?php echo SERVERURL ?>" + "/suscripciones/tiendas", {
                    method: 'POST',
                    body: frm
                }).then(response => response.json())
                .then(data => {
                    data.forEach((tienda) => {
                        document.querySelector('#tiendas_contenedor').innerHTML += `<div id="tienda${tienda.id_plataforma}" class="grid grid-cols-2 gap-5 items-center hover:scale-110  hover:text-blue-700">
                                <div class="border-2 hover:border-blue-700 p-5 border-black  text-center">
                                    <i class="fas text-2xl fa-store"></i>
                                </div>
                                <span class="text-center">${tienda.nombre_tienda}</span>
                            </div>`;
                        document.querySelector(`#tienda${tienda.id_plataforma}`).addEventListener('click', () => {
                            window.location.href = `https://tiendas.imporsuitpro.com/tienda/${tienda.id_plataforma}`;
                        });
                    });
                })
                .catch(error => console.error('Error en la petición:', error));
        });
    </script>

    <script>
        // Capturar elementos del DOM
        const profilePic = document.getElementById('profilePic');
        const menuDropdown = document.getElementById('menuDropdown');

        // Función para alternar la visibilidad del menú
        profilePic.addEventListener('click', () => {
            if (menuDropdown.classList.contains('hidden')) {
                menuDropdown.classList.remove('hidden');
            } else {
                menuDropdown.classList.add('hidden');
            }
        });

        // Función para cerrar sesión
        document.getElementById('logoutBtn').addEventListener('click', () => {
            window.location.href = '<?php echo SERVERURL; ?>acceso/logout';
        });

        // Si se hace clic fuera del menú, se cierra
        document.addEventListener('click', (event) => {
            if (!profilePic.contains(event.target) && !menuDropdown.contains(event.target)) {
                menuDropdown.classList.add('hidden');
            }
        });

        //estado inicial slider global
        let sliderGlobal = 0;

        //imagenes del slider
        const imagenes = [
            'https://tiendas.imporsuitpro.com/imgs/herramientas/infoaduana_b.jpg',
            'https://tiendas.imporsuitpro.com/imgs/herramientas/cotizador_b.jpg',
            'https://tiendas.imporsuitpro.com/imgs/herramientas/imporlab_b.jpg',
            'https://tiendas.imporsuitpro.com/imgs/herramientas/facturacion_b.jpg',
        ];

        const posiciones = {
            0: '-ml-[0%]',
            1: '-ml-[100%]',
            2: '-ml-[200%]',
            3: '-ml-[300%]',
        };

        //cargar imagenes del slider
        imagenes.forEach((imagen) => {
            document.querySelector('#imagenes_slider').innerHTML += `<img id="slider" class="h-[calc(100%_/_3)] w-[calc(100%_/_4)]" src="${imagen}" alt="">`;
        });

        //funcion para cambiar la imagen del slider
        const cambiarImagen = () => {
            document.querySelector('#slider').src = imagenes[sliderGlobal];
            document.querySelector('#imagenes_slider').classList.remove('-ml-[0%]', '-ml-[100%]', '-ml-[200%]', '-ml-[300%]');
            document.querySelector('#imagenes_slider').classList.add(posiciones[sliderGlobal]);
        }

        // funcion para cambiar la imagen del slider anterior
        const cambiarImagenAnterior = () => {
            if (sliderGlobal === 0) {
                sliderGlobal = imagenes.length - 1;
            } else {
                sliderGlobal--;
            }
            cambiarImagen();
        }

        //funcion para cambiar la imagen del slider siguiente
        const cambiarImagenSiguiente = () => {
            if (sliderGlobal >= imagenes.length - 1) {
                sliderGlobal = 0;
            } else {
                sliderGlobal++;
            }
            cambiarImagen();
        }

        //auto cambio de imagenes
        setInterval(() => {
            if (sliderGlobal >= imagenes.length - 1) {
                sliderGlobal = 0;
            } else {
                sliderGlobal++;
            }
            cambiarImagen();
        }, 3000);

        //evento para cambiar la imagen del slider anterior
        document.querySelector('#anterior').addEventListener('click', cambiarImagenAnterior);

        //evento para cambiar la imagen del slider siguiente
        document.querySelector('#siguiente').addEventListener('click', cambiarImagenSiguiente);

        //evento para cambiar la imagen del slider
        document.querySelector('#slider').addEventListener('click', cambiarImagen);

        //evento para redireccionar a la pagina de infoaduana
        document.querySelector('#infoaduana').addEventListener('click', () => {
            if (document.querySelector('#infoaduana').classList.contains('grayscale')) {
                Toast.fire({

                    icon: "error",
                    title: "No tienes una suscripción activa para este sistema, contacta a tu asesor de ventas.",
                    position: "bottom-end",
                })
                return;
            }

            window.location.href = 'https://infoaduana.imporfactory.app/newlogin?token=<?php echo $_SESSION['token'] ?>';
        });

        //evento para redireccionar a la pagina de cotizador
        document.querySelector('#cotizador').addEventListener('click', () => {
            if (document.querySelector('#cotizador').classList.contains('grayscale')) {
                Toast.fire({

                    icon: "error",
                    title: "No tienes una suscripción activa para este sistema, contacta a tu asesor de ventas.",
                    position: "bottom-end",
                })
                return;
            }
            window.location.href = 'https://cotizador.imporfactory.app/newlogin?token=<?php echo $_SESSION['token'] ?>';
        });

        //evento para redireccionar a la pagina de cursos
        document.querySelector('#cursos').addEventListener('click', () => {
            if (document.querySelector('#cursos').classList.contains('grayscale')) {
                Toast.fire({

                    icon: "error",
                    title: "No tienes una suscripción activa para este sistema, contacta a tu asesor de ventas.",
                    position: "bottom-end",
                })
                return;
            }
            window.location.href = 'https://cursos.imporfactory.app/newlogin?token=<?php echo $_SESSION['token'] ?>';
        });

        //evento para redireccionar a la pagina de tienda
        document.querySelector('#tienda').addEventListener('click', () => {
            if (document.querySelector('#tienda').classList.contains('grayscale')) {
                Toast.fire({

                    icon: "error",
                    title: "No tienes una suscripción activa para este sistema, contacta a tu asesor de ventas.",
                    position: "bottom-end",
                })
                return;
            }
            document.querySelector('#tiendas').classList.remove('hidden');
        });

        //evento para redireccionar a la pagina de proveedores
        document.querySelector('#proveedores').addEventListener('click', () => {
            if (document.querySelector('#proveedores').classList.contains('grayscale')) {
                Toast.fire({

                    icon: "error",
                    title: "No tienes una suscripción activa para este sistema, contacta a tu asesor de ventas.",
                    position: "bottom-end",
                })
                return;
            }
            window.location.href = 'https://proveedores.imporsuitpro.com/';
        });
    </script>

    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener("mouseenter", Swal.stopTimer);
                toast.addEventListener("mouseleave", Swal.resumeTimer);
            },
        });
    </script>

</body>

</html>