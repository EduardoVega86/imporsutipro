        <!-- Fin del contenido de la página -->
        </div>

        <script>
            $(document).ready(function() {
                const sidebar = $('#sidebar');
                const toggleBtn = $('#toggle-btn');
                const dropdownBtn = $('.dropdown-btn');
                const submenu = $('.submenu');
                const submenuPopup = $('#submenu-popup');
                const profilePic = $('#profilePic');
                const profileDropdown = $('#profileDropdown');
                const isSidebarCollapsed = localStorage.getItem('isSidebarCollapsed') === 'true';

                if (isSidebarCollapsed) {
                    sidebar.addClass('sidebar-collapsed');
                    $('.content').addClass('content-collapsed');
                    $('.menu-text').hide();
                    $('.footer-text').hide();
                }

                toggleBtn.on('click', function() {
                    sidebar.toggleClass('sidebar-collapsed');
                    $('.content').toggleClass('content-collapsed');
                    $('.menu-text').toggle();
                    $('.footer-text').toggle(!sidebar.hasClass('sidebar-collapsed'));
                    localStorage.setItem('isSidebarCollapsed', sidebar.hasClass('sidebar-collapsed'));

                    // Ocultar el submenú emergente si el menú principal se expande
                    if (!sidebar.hasClass('sidebar-collapsed')) {
                        submenuPopup.removeClass('active');
                    }
                });

                dropdownBtn.on('click', function() {
                    const targetSubmenu = $($(this).data('target'));

                    if (sidebar.hasClass('sidebar-collapsed')) {
                        // Ocultar todos los submenús normales
                        submenu.removeClass('active');
                        // Llenar y mostrar el submenú emergente
                        submenuPopup.html(targetSubmenu.html());
                        const offset = $(this).offset();
                        submenuPopup.css({
                            top: offset.top + 'px',
                            left: offset.left + $(this).outerWidth() + 'px'
                        }).toggleClass('active');
                    } else {
                        // Ocultar el submenú emergente
                        submenuPopup.removeClass('active');
                        // Mostrar el submenú normal
                        targetSubmenu.slideToggle().toggleClass('active');
                    }
                });

                // Cerrar el submenú emergente al hacer clic fuera de él
                $(document).on('click', function(event) {
                    if (!$(event.target).closest('.dropdown-btn, .submenu-popup').length) {
                        submenuPopup.removeClass('active');
                    }
                });

                // Cerrar el submenú normal al hacer clic fuera de él
                $(document).on('click', function(event) {
                    if (!$(event.target).closest('.dropdown-btn, .submenu').length) {
                        submenu.removeClass('active').slideUp();
                    }
                });

                // Mostrar/ocultar menú de perfil
                profilePic.on('click', function() {
                    profileDropdown.toggle();
                });

                // Cerrar menú de perfil al hacer clic fuera de él
                $(document).on('click', function(event) {
                    if (!$(event.target).closest('#profilePic, #profileDropdown').length) {
                        profileDropdown.hide();
                    }
                });

                cargar_saldoWallet();

                cargar_ultima_pagina();

                // Ejecutar la función al cargar la página
                // Ejecutar la función cada 60 segundos
                /* sigue_logeado();
                setInterval(sigue_logeado, 60000); */

                $.ajax({
                    type: "POST",
                    url: SERVERURL + "Usuarios/crear_json",
                    dataType: "json",
                    success: function(response) {
                        /* console.log("Respuesta recibida:", response);  */
                        // Aquí manejas la respuesta del servidor
                    },
                    error: function(xhr, status, error) {
                        console.error("Error en la solicitud AJAX:", error);
                        console.log("Estado:", status);
                        console.log("XHR:", xhr);
                        alert("Hubo un problema al obtener la información de la categoría");
                    }
                });
            });

            function cargar_saldoWallet() {
                $.ajax({
                    url: SERVERURL + "calculadora/saldo",
                    type: "GET",
                    dataType: "json",
                    success: function(response) {
                        $("#precio_wallet").text(parseFloat(response).toFixed(2));
                    },
                    error: function(error) {
                        console.error("Error al obtener la lista de bodegas:", error);
                    },
                });
            }

            function cargar_ultima_pagina() {
                var urlPath = window.location.pathname; // Esto devuelve "/Pedidos/chat_imporsuit"
                var cleanPath = urlPath.substring(1); // Esto elimina la barra inclinada inicial, devolviendo "Pedidos/chat_imporsuit"
                let formData = new FormData();
                formData.append("url", urlCompleto);
                $.ajax({
                    url: SERVERURL + "acceso/guardaUltimoPunto",
                    type: "POST",
                    data: formData,
                    processData: false, // No procesar los datos
                    contentType: false, // No establecer ningún tipo de contenido
                    dataType: "json",
                    success: function(response) {

                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert(errorThrown);
                    },
                });

            }

            function sigue_logeado() {
                $.ajax({
                    url: SERVERURL + "usuarios/sigue_logeado",
                    type: "GET",
                    dataType: "json",
                    success: function(response) {
                        response = JSON.parse(response);
                        if (response.status == 400 || response.status == 500) {
                            location.reload(); // Recargar la página
                        }
                    },
                    error: function(error) {
                        console.error("Error al obtener la lista de bodegas:", error);
                    },
                });
            }

            //funcion cerrar sesion 
            function cerrar_sesion() {
                $.ajax({
                    url: '<?php echo SERVERURL; ?>acceso/logout',
                    method: 'GET',
                    success: function(response) {
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        console.error('Error al cerrar la sesión', status, error);
                    }
                });
            }

            /* seccion notificaciones */
            function toggleNotifications() {
                document.querySelector('.notification-dropdown').classList.toggle('active');
            }
            /* fin seccion notificaciones */
        </script>

        <!-- librerias de filtro fecha -->
        <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/min/moment.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.5.0/nouislider.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/wnumb/1.1.0/wNumb.min.js"></script>
        </body>

        </html>