<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Api Webhook</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-gradient-to-br from-[#171931] via-[#20264d] to-[#171931] flex items-center justify-center">
    <!-- Contenedor principal (ajustamos a max-w-2xl para tener más ancho) -->
    <div class="container max-w-2xl mx-auto px-4 py-8">
        <!-- LOGO / TÍTULO -->
        <div class="text-center mb-8">
            <h1 class="text-teal-400 text-4xl font-bold tracking-wide">
                Api Webhook
            </h1>
            <p id="loginT" class="text-gray-300 mt-2">
                ¡Bienvenido! Inicia sesión o regístrate para continuar
            </p>
            <p id="apiT" class="hidden text-gray-300 mt-2">
                ¡¡¡Conecta todas tus aplicaciones con nuestra API Webhook para automatizar tus procesos de negocio!!!
            </p>
        </div>

        <!-- Sección LOGIN -->
        <div
            id="login"
            class="bg-white/5 backdrop-blur-md rounded-lg shadow-lg p-6 space-y-6">
            <h2 class="text-2xl font-semibold text-teal-300">Iniciar Sesión</h2>
            <form id="loginForm" class="space-y-4">
                <div>
                    <label for="correo" class="block mb-1 text-gray-200">
                        Correo
                    </label>
                    <input
                        type="email"
                        name="correo"
                        id="correo"
                        placeholder="Ingresa tu correo"
                        class="w-full p-2 border border-white/20 rounded bg-white/10 text-gray-100 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-teal-400" />
                </div>
                <div>
                    <label for="contrasena" class="block mb-1 text-gray-200">
                        Contraseña
                    </label>
                    <input
                        type="password"
                        name="contrasena"
                        id="contrasena"
                        placeholder="Ingresa tu contraseña"
                        class="w-full p-2 border border-white/20 rounded bg-white/10 text-gray-100 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-teal-400" />
                </div>
                <button
                    type="submit"
                    class="w-full bg-teal-500 hover:bg-teal-600 text-white font-semibold p-2 rounded transition-colors duration-200">
                    Iniciar sesión
                </button>
            </form>
        </div>

        <!-- Sección APIS (oculta por defecto, con más altura y padding) -->
        <div
            id="apis"
            class="mt-8 bg-white/5 backdrop-blur-md rounded-lg shadow-lg p-8 space-y-6 hidden min-h-[500px]">
            <h2 class="text-xl text-teal-300 font-semibold">Conexión con nuestras APIs</h2>
            <p class="text-gray-300">
                ¡Bienvenido! Ahora puedes hacer uso de nuestras APIs
            </p>
            <p class="text-gray-300">
                <strong>Tu identificador único es: </strong>
                <span id="uuidSpan" class="text-teal-400"></span>
            </p>

            <div>
                <p class="text-gray-300 font-bold mb-2">APIs disponibles:</p>
                <ul class="space-y-4 text-gray-300">

                    <!-- GET API -->
                    <li>
                        <div class="flex flex-col md:flex-row md:items-center gap-2">
                            <span class="font-semibold text-gray-200">GET:</span>
                            <a
                                id="getCiudadesLink"
                                href="#"
                                target="_blank"
                                class="text-teal-400 hover:underline break-all">
                                <?php echo SERVERURL; ?>api/getCiudades/uuid
                            </a>
                            <!-- Botón que abre el modal de GET -->
                            <button
                                type="button"
                                class="bg-teal-500 hover:bg-teal-600 text-white px-3 py-1 rounded transition duration-200"
                                onclick="openModal('getCiudades')">
                                Ver Ejemplo
                            </button>
                        </div>
                    </li>
                    <!-- GET API -->
                    <li>
                        <div class="flex flex-col md:flex-row md:items-center gap-2">
                            <span class="font-semibold text-gray-200">GET:</span>
                            <a
                                id="getApiLink"
                                href="#"
                                target="_blank"
                                class="text-teal-400 hover:underline break-all">
                                <?php echo SERVERURL; ?>api/Getpedidos/uuid
                            </a>
                            <!-- Botón que abre el modal de GET -->
                            <button
                                type="button"
                                class="bg-teal-500 hover:bg-teal-600 text-white px-3 py-1 rounded transition duration-200"
                                onclick="openModal('getPedidos')">
                                Ver Ejemplo
                            </button>
                        </div>
                    </li>

                    <!-- POST API -->
                    <li>
                        <div class="flex flex-col md:flex-row md:items-center gap-2">
                            <span class="font-semibold text-gray-200">POST:</span>
                            <a
                                id="postApiLink"
                                href="#"
                                target="_blank"
                                class="text-teal-400 hover:underline break-all">
                                <?php echo SERVERURL; ?>api/pedido/uuid
                            </a>
                            <!-- Botón que abre el modal de POST -->
                            <button
                                type="button"
                                class="bg-teal-500 hover:bg-teal-600 text-white px-3 py-1 rounded transition duration-200"
                                onclick="openModal('postPedido')">
                                Ver Ejemplo
                            </button>
                        </div>
                    </li>


                </ul>
            </div>
        </div>
    </div>

    <!-- MODAL GET -->
    <div
        id="getCiudades"
        class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50">
        <div class="bg-white/10 backdrop-blur-lg p-6 rounded-lg max-w-lg w-full space-y-4">
            <h2 class="text-teal-300 text-xl font-semibold">GET Pedidos</h2>
            <p class="text-gray-200">
                Obtienes todas las ciudades disponibles en el sistema. Ideal para seleccionar la ciudad de destino de tus pedidos.
            </p>
            <p class="text-gray-200">
                Puedes probarlo fácilmente con cualquier cliente HTTP (Postman, cURL, etc).
            </p>
            <div class="bg-gray-800 p-3 rounded">
                <code class="text-gray-200 text-sm">
                    curl -X GET <span id="getApiCiudades" class="text-teal-400"></span>
                </code>
            </div>
            <button
                type="button"
                onclick="closeModal('getCiudades')"
                class="w-full bg-teal-500 hover:bg-teal-600 text-white font-semibold p-2 rounded">
                Cerrar
            </button>
        </div>
    </div>
    <!-- MODAL GET -->
    <div
        id="getPedidos"
        class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50">
        <div class="bg-white/10 backdrop-blur-lg p-6 rounded-lg max-w-lg w-full space-y-4">
            <h2 class="text-teal-300 text-xl font-semibold">GET Pedidos</h2>
            <p class="text-gray-200">
                Obtiene todos tus pedidos almacenados en el sistema. Ideal para ver tu historial o estado actual de pedidos.
            </p>
            <p class="text-gray-200">
                Puedes probarlo fácilmente con cualquier cliente HTTP (Postman, cURL, etc).
            </p>
            <div class="bg-gray-800 p-3 rounded">
                <code class="text-gray-200 text-sm">
                    curl -X GET <span id="getApiLinkModal" class="text-teal-400"></span>
                </code>
            </div>
            <button
                type="button"
                onclick="closeModal('getPedidos')"
                class="w-full bg-teal-500 hover:bg-teal-600 text-white font-semibold p-2 rounded">
                Cerrar
            </button>
        </div>
    </div>

    <!-- MODAL POST -->
    <div
        id="postPedido"
        class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50">
        <div class="bg-white/10 backdrop-blur-lg p-6 rounded-lg max-w-lg w-full space-y-4">
            <h2 class="text-teal-300 text-xl font-semibold">POST API</h2>
            <p class="text-gray-200">
                Crea un nuevo pedido enviando los datos en JSON. Puedes adjuntar información como:
            </p>
            <!-- AÑADIMOS SCROLL AL BLOQUE GRANDE DE JSON -->
            <pre class="bg-gray-800 text-gray-200 p-3 rounded whitespace-pre-wrap text-sm max-h-60 overflow-y-auto">
{
    "monto_factura": 100.00,
    "cliente": "Nombre del cliente", 
    "telefono": "1234567890",
    "calle_principal": "Calle principal",
    "calle_secundaria": "Calle secundaria",
    "provincia": 201001001, // provincia pichincha
    "ciudad": 599, // ciudad quito
    "referencia": "Referencia de la dirección",
    "observacion": "Observaciones adicionales",
    "id_inventario": 7,
    "productos": [
        {
            "id_producto_venta": 7,
            "nombre": "Full - Gomas energéticas",
            "cantidad": 2,
            "precio": 39.99,
            "item_total_price": 79.98
        }
    ]
}
      </pre>
            <p class="text-gray-200">
                Ejemplo de comando cURL:
            </p>
            <div class="bg-gray-800 p-3 rounded">
                <code class="text-gray-200 text-sm">
                    curl -X POST <span id="postApiLinkModal" class="text-teal-400"></span> \
                    -H "Content-Type: application/json" \
                    -d 'curl -X POST http://localhost/imporsutipro/api/pedido/184ceb01-a1ad-4ece-be58-9ae245f64b1d \
                    -H "Content-Type: application/json" \
                    -d '{"monto_factura": 100.00, "cliente": "Nombre del cliente", "telefono": "1234567890", "calle_principal": "Calle principal", "calle_secundaria": "Calle secundaria",
                    "provincia": 201001001, "ciudad": 599, "referencia": "Referencia de la dirección", "observacion": "Observaciones adicionales", "id_inventario": 7,
                    "productos": [{"id": 7, "sku": "SKU-123", "name": "Full - Gomas energéticas", "quantity": 2, "price": 39.99 } ] }'

                </code>
            </div>
            <button
                type="button"
                onclick="closeModal('postPedido')"
                class="w-full bg-teal-500 hover:bg-teal-600 text-white font-semibold p-2 rounded">
                Cerrar
            </button>
        </div>
    </div>
    <!-- MODAL POST -->
    <div
        id="postGuia"
        class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50">
        <div class="bg-white/10 backdrop-blur-lg p-6 rounded-lg max-w-lg w-full space-y-4">
            <h2 class="text-teal-300 text-xl font-semibold">POST API</h2>
            <p class="text-gray-200">
                Crea un nuevo pedido enviando los datos en JSON. Puedes adjuntar información como:
            </p>
            <!-- AÑADIMOS SCROLL AL BLOQUE GRANDE DE JSON -->
            <pre class="bg-gray-800 text-gray-200 p-3 rounded whitespace-pre-wrap text-sm max-h-60 overflow-y-auto">
{
    "monto_factura": 100.00,
    "cliente": "Nombre del cliente", 
    "telefono": "1234567890",
    "calle_principal": "Calle principal",
    "calle_secundaria": "Calle secundaria",
    "provincia": 201001001, // provincia pichincha
    "ciudad": 599, // ciudad quito
    "referencia": "Referencia de la dirección",
    "observacion": "Observaciones adicionales",
    "id_inventario": 7,
    "productos": [
        {
            "id_producto_venta": 7,
            "nombre": "Full - Gomas energéticas",
            "cantidad": 2,
            "precio": 39.99,
            "item_total_price": 79.98
        }
    ]
}
      </pre>
            <p class="text-gray-200">
                Ejemplo de comando cURL:
            </p>
            <div class="bg-gray-800 p-3 rounded">
                <code class="text-gray-200 text-sm">
                    curl -X POST <span id="generarGuiaModal" class="text-teal-400"></span> \
                    -H "Content-Type: application/json" \
                    -d 'curl -X POST http://localhost/imporsutipro/api/pedido/184ceb01-a1ad-4ece-be58-9ae245f64b1d \
                    -H "Content-Type: application/json" \
                    -d '{"monto_factura": 100.00, "cliente": "Nombre del cliente", "telefono": "1234567890", "calle_principal": "Calle principal", "calle_secundaria": "Calle secundaria",
                    "provincia": 201001001, "ciudad": 599, "referencia": "Referencia de la dirección", "observacion": "Observaciones adicionales", "id_inventario": 7,
                    "productos": [{"id": 7, "sku": "SKU-123", "name": "Full - Gomas energéticas", "quantity": 2, "price": 39.99 } ] }'

                </code>
            </div>
            <button
                type="button"
                onclick="closeModal('postGuia')"
                class="w-full bg-teal-500 hover:bg-teal-600 text-white font-semibold p-2 rounded">
                Cerrar
            </button>
        </div>
    </div>

    <!-- Script para manejar el login, actualizar la UI y el uso de modales -->
    <script>
        // verificamos si ya hay un uuid guardado en el localStorage
        const uuid = localStorage.getItem("uuid");

        // Si ya hay un uuid, lo usamos para crear el bloque de APIs
        if (uuid) {
            crearBloque(uuid);
        }

        function openModal(modalId) {
            document.getElementById(modalId).classList.remove("hidden");
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add("hidden");
        }

        document.getElementById("loginForm").addEventListener("submit", (e) => {
            e.preventDefault();
            const correo = document.getElementById("correo").value;
            const contrasena = document.getElementById("contrasena").value;

            fetch("http://localhost/imporsutipro/api/login", {
                    method: "POST",
                    body: JSON.stringify({
                        correo,
                        contrasena
                    }),
                    headers: {
                        "Content-Type": "application/json",
                    },
                })
                .then((response) => response.json())
                .then((data) => {
                    if (data.status === 200) {
                        localStorage.setItem("uuid", data.uuid);
                        crearBloque(data.uuid);
                    } else {
                        alert(data.message);
                    }
                });
        });

        function crearBloque(uuid) {
            if (uuid) {
                // Ocultamos el login
                document.getElementById("login").classList.add("hidden");
                // Mostramos la sección de APIs
                document.getElementById("apis").classList.remove("hidden");
                // Cambiamos textos
                document.getElementById("loginT").classList.add("hidden");
                document.getElementById("apiT").classList.remove("hidden");

                // Asignamos el uuid
                const uuidSpan = document.getElementById("uuidSpan");
                uuidSpan.textContent = uuid;

                // URLs dinámicas con el uuid
                const getApiLink = `<?php echo SERVERURL; ?>api/getPedidos/${uuid}`;
                const postApiLink = `<?php echo SERVERURL; ?>api/pedido/${uuid}`;
                const getApiCiudades = `<?php echo SERVERURL; ?>api/getCiudades/${uuid}`;
                // Enlaces en la interfaz
                document.getElementById("getApiLink").textContent = getApiLink;
                document.getElementById("getApiLink").href = getApiLink;
                document.getElementById("postApiLink").textContent = postApiLink;
                document.getElementById("postApiLink").href = postApiLink;
                document.getElementById("getCiudadesLink").textContent = getApiCiudades;
                document.getElementById("getCiudadesLink").href = getApiCiudades;


                // Enlaces dentro de los modales
                document.getElementById("getApiLinkModal").textContent = getApiLink;
                document.getElementById("postApiLinkModal").textContent = postApiLink;
                document.getElementById("getApiCiudades").textContent = getApiCiudades;
            }
        }
    </script>
</body>

</html>