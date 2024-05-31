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
    <style>
        body {
            display: flex;
            height: 100vh;
            overflow: hidden;
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
            padding-top: 1rem;
            transition: width 0.3s;
        }

        .sidebar a {
            color: #fff;
            padding: 10px;
            text-decoration: none;
            display: block;
        }

        .sidebar a:hover {
            background-color: #007bff;
        }

        .sidebar .menu {
            flex-grow: 1;
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

        .navbar-custom {
            background-color: #171931;
            color: #fff;
        }

        .navbar-custom .navbar-brand,
        .navbar-custom .nav-link,
        .navbar-custom .navbar-text {
            color: #fff;
        }
    </style>
</head>

<body>
    <div class="sidebar" id="sidebar">
        <div class="menu">
            <a href="#" class="toggle-btn" id="toggle-btn">
                <box-icon name="menu" color="#fff"></box-icon>
            </a>
            <a href="#"><box-icon name="home" color="#fff"></box-icon> <span class="menu-text">Inicio</span></a>
            <a href="#"><box-icon name="cart" color="#fff"></box-icon> <span class="menu-text">Marketplace</span></a>
            <a href="#"><box-icon name="user" color="#fff"></box-icon> <span class="menu-text">Clientes</span></a>
            <a href="#"><box-icon name="cube" color="#fff"></box-icon> <span class="menu-text">Productos</span></a>
            <a href="#"><box-icon name="shopping-bag" color="#fff"></box-icon> <span class="menu-text">Compras</span></a>
            <a href="#"><box-icon name="receipt" color="#fff"></box-icon> <span class="menu-text">Pedidos</span></a>
            <!-- Agrega más enlaces según sea necesario -->
        </div>
        <div class="footer-text">
            2024 © Imporsuit
        </div>
    </div>
    <div class="content">
        <nav class="navbar navbar-expand-lg navbar-custom" style="padding-top: 0.26rem;">
            <div class="container-fluid">
                <a class="navbar-brand" href="#"><img src="https://tiendas.imporsuitpro.com/imgs/LOGOS-IMPORSUIT.png" alt="IMORSUIT" width="100px" height="44px"></a>
                <div class="d-flex">
                    <a class="nav-link" href="#">Tutoriales</a>
                    <span class="navbar-text">$0.00</span>
                </div>
            </div>
        </nav>
        <!-- Aquí va el contenido de la página -->