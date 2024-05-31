<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IMPORSUITPRO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>

    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .navbar {
            background-color: #1a1a2e;
        }
        .navbar-brand {
            color: #fff;
            font-weight: bold;
        }
        .navbar-toggler {
            border: none;
            color: #fff;
            font-size: 24px;
            background: none;
        }
        .nav-link {
            color: #fff;
        }
        .right-section {
            color: #fff;
            margin-left: auto;
            display: flex;
            align-items: center;
        }
        .offcanvas-header {
            border-bottom: 1px solid #ddd;
        }
        .offcanvas-body {
            padding-top: 10px;
        }
    </style>
</head>

<body>
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasMenu" aria-controls="offcanvasMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand" href="#">IMPOR</a>
        <div class="right-section">
            <a href="#" class="nav-link">Tutoriales</a>
            <span class="nav-link">$0.00</span>
            <a href="#" class="nav-link"><i class="fa fa-sign-out-alt"></i></a>
        </div>
    </div>
</nav>

<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasMenu" aria-labelledby="offcanvasMenuLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasMenuLabel">Menu</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="#">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">About</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Services</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Contact</a>
            </li>
        </ul>
    </div>
</div>