<?php require_once './Views/templates/header.php'; ?>
<style>
    .card-custom {
        border-radius: 15px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s, box-shadow 0.3s;
        height: 500px;
        width: 250px;
        margin: 10px;
        display: flex;
        flex-direction: column;
    }

    .card-custom:hover {
        transform: translateY(-10px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.2);
    }

    .card-custom img {
        border-top-left-radius: 15px;
        border-top-right-radius: 15px;
        height: 200px;
        width: 100%;
        object-fit: cover;
    }

    .card-custom .card-body {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .card-custom .btn-description,
    .card-custom .btn-import {
        border-radius: 50px;
        padding: 10px 20px;
        margin: 5px auto;
        /* Center the buttons */
        width: 80%;
    }

    .btn-description {
        background-color: #00aaff;
        color: white;
    }

    .btn-import {
        background-color: #ffc107;
        color: white;
    }
    .card-text{
        margin-bottom: 10px;
    }

    @media (max-width: 768px) {
        .card-custom {
            width: 100%;
            margin: 10px 0;
        }

        .card-group {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }
    }

    @media (max-width: 576px) {
        .card-custom {
            width: calc(50% - 20px);
            /* Two cards side by side */
        }
    }
</style>

<div class="container mt-4">
    <div class="row mb-3">
        <div class="col-md-4 mb-3 mb-md-0">
            <input type="text" class="form-control" placeholder="Código o Nombre">
        </div>
        <div class="col-md-4 mb-3 mb-md-0">
            <select class="form-control">
                <option>-- Selecciona Línea --</option>
                <option>Línea 1</option>
                <option>Línea 2</option>
                <option>Línea 3</option>
            </select>
        </div>
        <div class="col-md-3 mb-3 mb-md-0">
            <select class="form-control">
                <option>Selecciona una opción</option>
                <option>Opción 1</option>
                <option>Opción 2</option>
                <option>Opción 3</option>
            </select>
        </div>
        <div class="col-md-1">
            <button class="btn btn-warning w-100"><i class="fa fa-search"></i></button>
        </div>
    </div>
    <div class="card-group">
        <div class="col-md-4">
            <div class="card card-custom">
                <img src="https://significado.com/wp-content/uploads/Imagen-Animada.jpg" class="card-img-top" alt="Product Image">
                <div class="card-body text-center d-flex flex-column justify-content-between">
                    <div>
                        <h5 class="card-title">Producto 1</h5>
                        <p class="card-text">Stock: <strong>8</strong></p>
                        <p class="card-text">Precio Proveedor: <strong>$12.50</strong></p>
                        <p class="card-text">Precio Sugerido: <strong>$27.50</strong></p>
                        <p class="card-text">Proveedor: <a href="#">CLIJISPOREPRS</a></p>
                    </div>
                    <div>
                        <button class="btn btn-description">Descripción</button>
                        <button class="btn btn-import">Importar</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-custom">
                <img src="https://img.freepik.com/foto-gratis/colores-arremolinados-interactuan-danza-fluida-sobre-lienzo-que-muestra-tonos-vibrantes-patrones-dinamicos-que-capturan-caos-belleza-arte-abstracto_157027-2892.jpg" class="card-img-top" alt="Product Image">
                <div class="card-body text-center d-flex flex-column justify-content-between">
                    <div>
                        <h5 class="card-title">Producto 1</h5>
                        <p class="card-text">Stock: <strong>8</strong></p>
                        <p class="card-text">Precio Proveedor: <strong>$12.50</strong></p>
                        <p class="card-text">Precio Sugerido: <strong>$27.50</strong></p>
                        <p class="card-text">Proveedor: <a href="#">CLIJISPOREPRS</a></p>
                    </div>
                    <div>
                        <button class="btn btn-description">Descripción</button>
                        <button class="btn btn-import">Importar</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Repetir para más productos -->
    </div>
    <?php require_once './Views/templates/footer.php'; ?>