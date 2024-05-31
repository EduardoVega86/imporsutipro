<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IMPORSUITPRO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            display: flex;
            height: 100vh;
            overflow: hidden;
        }
        .sidebar {
            width: 250px;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            background-color: #343a40;
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
        .content {
            margin-left: 250px;
            padding: 1rem;
            width: 100%;
            transition: margin-left 0.3s;
        }
        .sidebar-collapsed {
            width: 80px;
        }
        .content-collapsed {
            margin-left: 80px;
        }
        .menu-text {
            display: inline;
            transition: opacity 0.3s;
        }
        .sidebar-collapsed .menu-text {
            opacity: 0;
        }
        .navbar-custom {
            background-color: #343a40;
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
    <div class="content">
        <nav class="navbar navbar-expand-lg navbar-custom">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">IMPORSUITPRO</a>
                <div class="d-flex">
                    <a class="nav-link" href="#">Tutoriales</a>
                    <span class="navbar-text">$0.00</span>
                </div>
            </div>
        </nav>
        <!-- Aquí va el contenido de la página -->