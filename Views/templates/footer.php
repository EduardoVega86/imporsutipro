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
            });

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

        </script>
        </body>

        </html>