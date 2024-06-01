        <!-- Fin del contenido de la página -->
        </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/v/bs5/jq-3.7.0/jszip-3.10.1/dt-2.0.8/af-2.7.0/b-3.0.2/b-colvis-3.0.2/b-html5-3.0.2/b-print-3.0.2/cr-2.0.3/date-1.5.2/fc-5.0.1/fh-4.0.1/kt-2.12.1/r-3.0.2/rg-1.5.0/rr-1.5.0/sc-2.4.3/sb-1.7.1/sp-2.3.1/sl-2.0.3/sr-1.4.1/datatables.min.js"></script>

    <script>
        $(document).ready(function() {
            const sidebar = $('#sidebar');
            const toggleBtn = $('#toggle-btn');
            const dropdownBtn = $('.dropdown-btn');
            const submenu = $('.submenu');
            const submenuPopup = $('#submenu-popup');
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
        });
    </script>
</body>
</html>
