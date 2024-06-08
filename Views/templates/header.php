<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IMPORSUITPRO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    <script src="https://www.gstatic.com/charts/loader.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-zoom"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">


    <!-- footer -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/v/bs5/jq-3.7.0/jszip-3.10.1/dt-2.0.8/af-2.7.0/b-3.0.2/b-colvis-3.0.2/b-html5-3.0.2/b-print-3.0.2/cr-2.0.3/date-1.5.2/fc-5.0.1/fh-4.0.1/kt-2.12.1/r-3.0.2/rg-1.5.0/rr-1.5.0/sc-2.4.3/sb-1.7.1/sp-2.3.1/sl-2.0.3/sr-1.4.1/datatables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
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
            background-color: #171931;
            padding-top: 1.4rem;
            transition: width 0.3s;
        }

        .sidebar a {
            color: #fff;
            padding: 10px;
            text-decoration: none;
            display: flex;
            align-items: center;
        }

        .sidebar a:hover {
            background-color: #007bff;
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
            color: #fff;
            text-decoration: none;
            display: flex;
            align-items: center;
        }

        .navbar-custom {
            background-color: #171931;
            color: #fff;
        }

        .navbar-custom .navbar-brand,
        .navbar-custom .nav-link,
        .navbar-custom .navbar-text {
            color: #fff;
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
            background-color: #171931;
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
            color: #fff;
            display: flex;
            align-items: center;
        }

        .profile-dropdown a:hover {
            background-color: #007bff;
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
            background-color: #171931;
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
    </style>
</head>

<body>
    <div class="sidebar" id="sidebar">
        <div class="menu">
            <a href="#" class="toggle-btn" id="toggle-btn">
                <box-icon name="menu" color="#fff" style="padding-right: 5px;"></box-icon>
            </a>
            <a href="<?php echo SERVERURL ?>dashboard"><box-icon name="home" color="#fff" style="padding-right: 5px;"></box-icon> <span class="menu-text">Inicio</span></a>
            <a href="#" class="dropdown-btn" data-target="#submenu1"><box-icon name="cart" color="#fff" style="padding-right: 5px;"></box-icon> <span class="menu-text">Productos</span></a>
            <div class="submenu" id="submenu1">
                <a href="<?php echo SERVERURL ?>Productos"><box-icon name="store" color="#fff" style="padding-right: 5px;"></box-icon> <span class="menu-text">Listado</span></a>



                <a href="<?php echo SERVERURL ?>Productos/categorias"><box-icon type="solid" name="category" color="#fff" style="padding-right: 5px;"></box-icon> <span class="menu-text">Categorias</span></a>
                <a href="<?php echo SERVERURL ?>Productos/marketplace"><box-icon name="shopping-bag" color="#fff" style="padding-right: 5px;"></box-icon> <span class="menu-text">Marketplace</span></a>
                 <a href="<?php echo SERVERURL ?>Productos/bodegas"><box-icon type="solid" name="truck" color="#fff" style="padding-right: 5px;"></box-icon> <span class="menu-text">Bodegas</span></a>

            </div>
            <a href="#" class="dropdown-btn" data-target="#submenu2"><box-icon name="receipt" color="#fff" style="padding-right: 5px;"></box-icon> <span class="menu-text">Pedidos</span></a>
            <div class="submenu" id="submenu2">
                <a href="<?php echo SERVERURL ?>Pedidos/nuevo"><box-icon name="file" color="#fff" style="padding-right: 5px;"></box-icon> <span class="menu-text">Nuevo</span></a>
                <a href="#"><box-icon name="history" color="#fff" style="padding-right: 5px;"></box-icon> <span class="menu-text">Historial</span></a>
                <a href="<?php echo SERVERURL ?>pedidos/guias"><box-icon name="archive" color="#fff" style="padding-right: 5px;"></box-icon> <span class="menu-text">Guías</span></a>
                <a href="#"><box-icon name="x" color="#fff" style="padding-right: 5px;"></box-icon> <span class="menu-text">Anulados</span></a>
                <a href="#"><box-icon name="info-circle" color="#fff" style="padding-right: 5px;"></box-icon> <span class="menu-text">Novedad</span></a>
            </div>
            <a href="#"><box-icon name="wallet" color="#fff" style="padding-right: 5px;"></box-icon> <span class="menu-text">Wallet</span></a>
            <a href="#"><box-icon name="cog" color="#fff" style="padding-right: 5px;"></box-icon> <span class="menu-text">Configuración</span></a>
        </div>
        <div class="footer-text">
            2024 © Imporsuit
        </div>
    </div>
    <div class="submenu-popup" id="submenu-popup">
        <!-- Este div será llenado dinámicamente con el contenido del submenú adecuado -->
    </div>
    <div class="content">
        <nav class="navbar navbar-expand-lg navbar-custom" style="padding-top: 0.26rem;">
            <div class="container-fluid">
                <a class="navbar-brand img_logo" href="#"><img src="https://tiendas.imporsuitpro.com/imgs/LOGOS-IMPORSUIT.png" alt="IMORSUIT" width="100px" height="44px"></a>
                <div class="navbar-right">
                    <a class="nav-link" href="#"><box-icon type='solid' name='videos' color="#fff"></box-icon> Tutoriales</a>
                    <span class="navbar-text"><box-icon name='wallet' color="#fff"></box-icon> $0.00</span>
                    <img src="https://new.imporsuitpro.com/public/img/img.png" class="profile-pic" id="profilePic" alt="Perfil">
                    <div class="profile-dropdown" id="profileDropdown">
                        <a href="#"><box-icon name="user" color="#fff" style="padding-right: 5px;"></box-icon> Perfil</a>
                        <a onclick="cerrar_sesion()"><box-icon name="log-out" color="#fff" style="padding-right: 5px;"></box-icon> Cerrar sesión</a>
                    </div>
                </div>
            </div>
        </nav>
        <!-- Aquí va el contenido de la página -->
        <script>
            const SERVERURL = "<?php echo SERVERURL ?>";
        </script>