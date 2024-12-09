<style>
    body {
        display: flex;
        height: 100vh;
        overflow-x: hidden;
    }

    .sidebar {
        display: flex;
        flex-direction: column;
        width: 150px;
        position: fixed;
        top: 0;
        bottom: 0;
        left: 0;
        background-color: <?php echo COLOR_FONDO; ?>;
        padding-top: 1.4rem;
        transition: width 0.3s;
    }

    .sidebar a {
        color: <?php echo COLOR_LETRAS; ?>;
        padding: 10px;
        text-decoration: none;
        display: flex;
        align-items: center;
    }

    .sidebar a:hover {
        background-color: <?php echo COLOR_HOVER; ?>;
        color: <?php echo COLOR_LETRA_HOVER; ?>;
    }

    .menu-icon {
        padding-right: 5px !important;
        font-size: 20px;
    }

    .sidebar .menu {
        flex-grow: 1;
    }

    .sidebar .submenu {
        display: none;
        flex-direction: column;
        padding-left: 20px;
    }

    .sidebar .submenu a {
        padding: 5px 10px;
    }

    .sidebar .submenu.active {
        display: flex;
    }

    .sidebar .footer-text {
        font-size: 13px;
        text-align: center;
        color: #959595;
        padding: 10px;
        user-select: none;
    }

    .content {
        margin-left: 150px;
        width: 100%;
        transition: margin-left 0.3s;
    }

    .sidebar-collapsed {
        width: 45px;
    }

    .content-collapsed {
        margin-left: 45px;
    }

    .menu-text {
        display: inline;
        transition: opacity 0.3s;
    }

    .sidebar-collapsed .menu-text {
        opacity: 0;
    }

    .submenu-popup .menu-text {
        opacity: 1 !important;
        display: inline !important;
    }

    .submenu-popup a {
        padding: 5px 10px;
        color: <?php echo COLOR_LETRAS; ?>;
        text-decoration: none;
        display: flex;
        align-items: center;
    }

    .navbar-custom {
        background-color: <?php echo COLOR_FONDO; ?>;
        color: <?php echo COLOR_LETRAS; ?>;
    }

    .navbar-custom .navbar-brand,
    .navbar-custom .nav-link,
    .navbar-custom .navbar-text {
        color: <?php echo COLOR_LETRAS; ?>;
    }

    .navbar-custom .navbar-right {
        display: flex;
        align-items: center;
        margin-left: auto;
    }

    .navbar-custom .nav-link,
    .navbar-custom .navbar-text {
        display: flex;
        align-items: center;
        margin-left: 15px;
        margin-right: 15px;
    }

    .profile-dropdown {
        position: absolute;
        top: 60px;
        right: 15px;
        background-color: <?php echo COLOR_FONDO; ?>;
        border: 1px solid #444;
        border-radius: 5px;
        display: none;
        flex-direction: column;
        z-index: 1000;
        padding: 10px;
    }

    .profile-dropdown a {
        padding: 10px;
        text-decoration: none;
        color: <?php echo COLOR_LETRAS; ?> !important;
        display: flex;
        align-items: center;
    }

    .profile-dropdown a:hover {
        background-color: <?php echo COLOR_HOVER; ?> !important;
        color: <?php echo COLOR_LETRA_HOVER; ?> !important;
        cursor: pointer;
    }

    .profile-pic {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        cursor: pointer;
    }

    .submenu-popup {
        position: fixed;
        top: 0;
        left: 45px;
        width: 150px;
        background-color: <?php echo COLOR_FONDO; ?>;
        display: none;
        flex-direction: column;
        border-left: 1px solid #444;
        z-index: 1000;
    }

    .submenu-popup.active {
        display: flex;
    }

    .img_logo {
        margin: 0;
    }

    .custom-container-fluid {
        padding-left: 15px;
        padding-right: 15px;
        margin-left: auto;
        margin-right: auto;
        background-color: #f8f9fa;
        min-height: 80%;
    }

    @media (max-width: 768px) {
        .sidebar {
            margin: 0;
            z-index: 50;
        }

        .content {
            margin: 0;
        }

        .img_logo {
            margin-left: 42px;
        }

        .custom-container-fluid {
            width: 90%;
            margin-left: 35px;
        }

        .profile-dropdown {
            top: 105px;
            /* Ajusta esta altura según sea necesario */
            right: 10px;
        }
    }

    /* Badge de notificaciones */
    .notification-icon {
        position: relative;
        cursor: pointer;
    }

    .badge {
        position: absolute;
        top: -5px;
        right: -10px;
        background-color: red;
        color: white;
        border-radius: 50%;
        padding: 4px 7px;
        font-size: 12px;
        font-weight: bold;
    }

    /* Dropdown de notificaciones */
    .notification-dropdown {
        position: relative;
    }

    .dropdown-menu {
        display: none;
        position: absolute;
        top: 35px;
        right: 0;
        background-color: white;
        border: 1px solid #ddd;
        border-radius: 8px;
        width: 250px;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        z-index: 1000;
    }

    .dropdown-header {
        font-weight: bold;
        padding: 10px;
        border-bottom: 1px solid #ddd;
    }

    .dropdown-item {
        padding: 10px;
        border-bottom: 1px solid #ddd;
    }

    .dropdown-item:hover {
        background-color: #f8f8f8;
    }

    .dropdown-footer {
        text-align: center;
        padding: 10px;
        font-size: 14px;
        background-color: #f9f9f9;
    }

    .dropdown-footer a {
        text-decoration: none;
        color: #007bff;
    }

    .dropdown-footer a:hover {
        text-decoration: underline;
    }

    /* Mostrar el dropdown cuando se activa */
    .notification-dropdown.active .dropdown-menu {
        display: block;
    }

    /* Fin Badge de notificaciones */

    /* whatsapp flotante */
    .whatsapp-float {
        position: fixed;
        bottom: 20px;
        right: 20px;
        background-color: #25D366;
        color: white;
        border-radius: 50%;
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        z-index: 1000;
        cursor: pointer;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        text-decoration: none;
        /* Elimina la raya */
    }

    .whatsapp-float:hover {
        transform: scale(1.1);
        box-shadow: 0px 6px 12px rgba(0, 0, 0, 0.3);
    }
    /* Fin whatsapp flotante */
</style>