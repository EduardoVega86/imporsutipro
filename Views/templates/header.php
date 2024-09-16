<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo MARCA; ?></title>
    <link rel="icon" type="image/png" href="<?php echo FAVICON; ?>">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    <script src="https://www.gstatic.com/charts/loader.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-zoom"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.5.0/nouislider.min.css">
</head>


<!-- footer -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" rel="stylesheet">


<script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/v/bs5/jq-3.7.0/jszip-3.10.1/dt-2.0.8/af-2.7.0/b-3.0.2/b-colvis-3.0.2/b-html5-3.0.2/b-print-3.0.2/cr-2.0.3/date-1.5.2/fc-5.0.1/fh-4.0.1/kt-2.12.1/r-3.0.2/rg-1.5.0/rr-1.5.0/sc-2.4.3/sb-1.7.1/sp-2.3.1/sl-2.0.3/sr-1.4.1/datatables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@ffmpeg/ffmpeg@0.10.0/dist/ffmpeg.min.js"></script>
<script src="https://kit.fontawesome.com/0022adc953.js" crossorigin="anonymous"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs4.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs4.min.js"></script>

<?php require_once './Views/templates/css/header_style.php'; ?>
</head>

<body>
    <div class="sidebar" id="sidebar">
        <div class="menu">
            <a href="#" class="toggle-btn" id="toggle-btn">
                <i class='bx bx-menu menu-icon'></i>
            </a>
            <a href="<?php echo SERVERURL ?>dashboard"><i class='bx bx-home menu-icon'></i> <span class="menu-text">Inicio</span></a>
            <a href="<?php echo SERVERURL ?>Productos/marketplace"><i class='bx bx-shopping-bag menu-icon'></i> <span class="menu-text">Marketplace</span></a>

            <a href="#" class="dropdown-btn" data-target="#submenu1"><i class='bx bx-cart menu-icon'></i> <span class="menu-text">Mis Productos</span></a>

            <div class="submenu" id="submenu1">
                <a href="<?php echo SERVERURL ?>Productos"><i class='bx bxs-store menu-icon'></i> <span class="menu-text">Listado</span></a>
                <a href="<?php echo SERVERURL ?>Productos/marketplace_privado"><i class='bx bxs-box menu-icon'></i> <span class="menu-text">Producto Privados</span></a>
                <a href="<?php echo SERVERURL ?>Productos/categorias"><i class='bx bxs-category menu-icon'></i> <span class="menu-text">Categorias</span></a>

                <a href="<?php echo SERVERURL ?>Productos/importacion_masiva"><i class='bx bxs-cart-download menu-icon'></i> <span class="menu-text">Importación Masiva</span></a>


            </div>
            <a href="#" class="dropdown-btn" data-target="#tienda"><i class='bx bx-store menu-icon'></i> <span class="menu-text">Tienda Online</span></a>
            <div class="submenu" id="tienda">
                <a href="<?php echo SERVERURL ?>Productos/productos_tienda"><i class='bx bxs-cart menu-icon'></i> <span class="menu-text">Productos Tienda</span></a>
                <a href="<?php echo SERVERURL ?>Productos/combos"><i class='bx bxs-category menu-icon'></i> <span class="menu-text">Combos</span></a>
                <a href="<?php echo SERVERURL ?>usuarios/checkout"><i class='bx bxs-joystick-button menu-icon'></i> <span class="menu-text">Editar Checkout</span></a>
                <a href="<?php echo SERVERURL ?>usuarios/tienda_online"><i class='bx bxs-store menu-icon'></i> <span class="menu-text">Tienda Online</span></a>
            </div>

            <?php if ($_SESSION['cargo'] != 5) { ?>
                <a href="#" class="dropdown-btn" data-target="#submenu_inventario"><i class='bx bx-list-plus menu-icon'></i> <span class="menu-text">Inventarios</span></a>
            <?php } ?>

            <div class="submenu" id="submenu_inventario">


                <a href="<?php echo SERVERURL ?>Productos/bodegas"><i class='bx bxs-truck menu-icon'></i> <span class="menu-text">Bodegas</span></a>
                <a href="<?php echo SERVERURL ?>Productos/inventario"><i class='bx bx-list-plus menu-icon'></i> <span class="menu-text">Inventario</span></a>
                <a href="<?php echo SERVERURL ?>despacho/lista_despachos"><i class='bx bxs-box menu-icon'></i> <span class="menu-text">Relacion Despacho</span></a>
                <a href="<?php echo SERVERURL ?>despacho/lista_devoluciones"><i class='bx bx-box menu-icon'></i> <span class="menu-text">Devoluciones</span></a>

            </div>

            <a href="#" class="dropdown-btn" data-target="#submenu2"><i class='bx bx-receipt menu-icon'></i> <span class="menu-text">Pedidos</span></a>
            <div class="submenu" id="submenu2">
                <a href="<?php echo SERVERURL ?>Pedidos"><i class='bx bx-history menu-icon'></i> <span class="menu-text">Historial</span></a>
                <?php if ($_SESSION['cargo'] == 10) { ?>
                    <a href="<?php echo SERVERURL ?>pedidos/guias_administrador"><i class='bx bx-archive menu-icon'></i> <span class="menu-text">Guías</span></a>
                    <a href="<?php echo SERVERURL ?>pedidos/anuladas_administrador"><i class='bx bx-x menu-icon'></i> <span class="menu-text">Anulados</span></a>
                <?php
                } else {
                ?>
                    <a href="<?php echo SERVERURL ?>pedidos/guias"><i class='bx bx-archive menu-icon'></i> <span class="menu-text">Guías</span></a>
                    <a href="<?php echo SERVERURL ?>pedidos/anuladas"><i class='bx bx-x menu-icon'></i> <span class="menu-text">Anulados</span></a>
                <?php
                }
                ?>
                <a href="<?php echo SERVERURL ?>pedidos/novedades"><i class='bx bx-info-circle menu-icon'></i> <span class="menu-text">Novedades</span></a>
            </div>

            <?php if ($_SESSION['cargo'] != 5) { ?>
                <a href="#" class="dropdown-btn" data-target="#submenu3"><i class='bx bx-wallet menu-icon'></i> <span class="menu-text">Wallet</span></a>
            <?php } ?>
            <div class="submenu" id="submenu3">
                <a href="<?php echo SERVERURL ?>wallet"><i class="fa-solid fa-money-bill-trend-up menu-icon"></i> <span class="menu-text">Detalle</span></a>
                <a href="<?php echo SERVERURL ?>wallet/datos_bancarios"><i class='bx bxs-bank menu-icon'></i> <span class="menu-text">Datos bancarios</span></a>
                <a href="<?php echo SERVERURL ?>referidos"><i class='bx bxs-bank menu-icon'></i> <span class="menu-text">Referidos</span></a>
                <?php if ($_SESSION['cargo'] != 10) {
                ?>
                    <a href="<?php echo SERVERURL ?>wallet/historial_solicitudes"><i class='bx bx-task'></i> <span class="menu-text">Solicitudes</span></a>
                <?php
                }
                ?>
                <?php if ($_SESSION['cargo'] == 10) { ?>
                    <a href="<?php echo SERVERURL ?>wallet/solicitudes"><i class="fa-solid fa-clipboard-list menu-icon"></i> <span class="menu-text">Solicitudes</span></a>
                    <a href="<?php echo SERVERURL ?>wallet/auditoria_guias"><i class="fa-solid fa-search menu-icon"></i> <span class="menu-text">Auditoria</span></a>
                <?php
                }
                ?>
            </div>

            <?php if ($_SESSION['cargo'] != 5) { ?>
                <a href="#" class="dropdown-btn" data-target="#submenu4"><i class='bx bx-cog menu-icon'></i> <span class="menu-text">Configuración</span></a>
            <?php } ?>
            <div class="submenu" id="submenu4">
                <?php if ($_SESSION['cargo'] == 10) { ?>
                    <a href="<?php echo SERVERURL ?>usuarios/plataformas"><i class='bx bx-box menu-icon'></i> <span class="menu-text">Plataformas Marketplace</span></a>
                    <a href="<?php echo SERVERURL ?>usuarios/listamatriz"><i class='bx bx-user menu-icon'></i> <span class="menu-text">Usuarios</span></a>
                    <a href="<?php echo SERVERURL ?>usuarios/actualizacionMasiva_tiendas"><i class='bx bx-user menu-icon'></i> <span class="menu-text">Actualizacion Masiva</span></a>
                <?php
                } else {
                ?>
                    <a href="<?php echo SERVERURL ?>usuarios/listado"><i class='bx bx-user menu-icon'></i> <span class="menu-text">Usuarios</span></a>
                <?php
                }
                ?>
                <!--a href="#"><i class='bx bxs-user-detail menu-icon'></i> <span class="menu-text">Roles</span></a-->
                <a href="<?php echo SERVERURL ?>shopify/constructor"><i class="fa-brands fa-shopify menu-icon"></i> <span class="menu-text">Shopify</span></a>
                <a href="<?php echo SERVERURL ?>shopify/constructor_vista"><i class="fa-brands fa-shopify menu-icon"></i> <span class="menu-text">Datos Shopify</span></a>
                <a href="<?php echo SERVERURL ?>integraciones"><i class="fa-solid fa-globe menu-icon"></i> <span class="menu-text">Integracio- nes</span></a>
            </div>
        </div>
        <div class="footer-text">
            2024 © <?php echo MARCA; ?>
        </div>
    </div>
    <div class="submenu-popup" id="submenu-popup">
        <!-- Este div será llenado dinámicamente con el contenido del submenú adecuado -->
    </div>
    <div class="content">
        <nav class="navbar navbar-expand-lg navbar-custom" style="padding-top: 0.26rem;">
            <div class="container-fluid">
                <a class="navbar-brand img_logo" href="<?php echo SERVERURL ?>dashboard"><img src="<?php echo IMAGEN_LOGO; ?>" alt="IMORSUIT" width="100px" height="44px"></a>
                <div class="navbar-right">
                    <?php if (MARCA == "IMPORSUIT") { ?>
                        <a class="nav-link" href="https://danielbonilla522-9.funnels.mastertools.com/#primeros-pasos" target="_blank"><box-icon type='solid' name='videos' color="<?php echo COLOR_LETRAS; ?>"></box-icon> Tutoriales</a>
                    <?php } ?>

                    <span class="navbar-text"><box-icon name='wallet' color="<?php echo COLOR_LETRAS; ?>"></box-icon> $<span id="precio_wallet"></span></span>

                    <!-- Notificación con icono y dropdown -->
                    <div class="notification-dropdown">
                        <span class="navbar-text notification-icon" onclick="toggleNotifications()">
                            <box-icon type='solid' name='bell' color="<?php echo COLOR_LETRAS; ?>"></box-icon>
                            <!-- <span id="notificaciones" class="badge">3</span> --> <!-- Número de notificaciones -->
                        </span>
                        <div id="notificationList" class="dropdown-menu">
                            <div class="dropdown-header">Notificaciones</div>
                            <div class="dropdown-item">Nueva orden recibida</div>
                            <div class="dropdown-item">Mensaje del soporte</div>
                            <div class="dropdown-item">Actualización disponible</div>
                            <div class="dropdown-footer"><a href="#">Ver todas las notificaciones</a></div>
                        </div>
                    </div>

                    <img src="https://new.imporsuitpro.com/public/img/img.png" class="profile-pic" id="profilePic" alt="Perfil">
                    <div class="profile-dropdown" id="profileDropdown">
                        <a href="#"><i class='bx bx-user menu-icon'></i> <?php echo $_SESSION["tienda"] ?></a>
                        <a onclick="cerrar_sesion()"><i class='bx bx-log-out menu-icon'></i> Cerrar sesión</a>
                    </div>
                </div>
            </div>
        </nav>
        <!-- Aquí va el contenido de la página -->
        <script>
            const SERVERURL = "<?php echo SERVERURL ?>";
            const MARCA = "<?php echo MARCA ?>";
            const CARGO = <?php echo $_SESSION['cargo']; ?>;
            const ID_PLATAFORMA = <?php echo $_SESSION['id_plataforma']; ?>;
            const MATRIZ = <?php echo MATRIZ; ?>;
        </script>