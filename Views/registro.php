<?php require_once './Views/templates/landing/header.php'; ?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IMORSUIT Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="container">
        <div class="d-flex flex-column">
            <div>

            </div>
            <div class="d-flex flex-row">
                <div class="aling-center">

                </div>
                <div class="d-flex flex-column">
                    <img src="logo.png" alt="IMORSUIT">
                    <h1>IMORSUIT</h1>
                    <p>¿Estás listo para unirte a este mundo de ecommerce? Llena tus datos para empezar.😉</p>

                    <form>
                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input type="text" class="form-control" id="nombre" placeholder="Nombre">
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" placeholder="Email">
                        </div>
                        <div class="form-group">
                            <label for="pais">País</label>
                            <select class="form-control" id="pais">
                                <option value="+593">Ecuador (+593)</option>
                                <!-- Add other countries as needed -->
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="telefono">Teléfono</label>
                            <input type="text" class="form-control" id="telefono" placeholder="Teléfono">
                        </div>
                        <div class="form-group">
                            <label for="contrasena">Contraseña</label>
                            <input type="password" class="form-control" id="contrasena" placeholder="Contraseña">
                        </div>
                        <div class="form-group">
                            <label for="repetir-contrasena">Repetir Contraseña</label>
                            <input type="password" class="form-control" id="repetir-contrasena" placeholder="Repetir Contraseña">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Siguiente</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>

</html>

<?php require_once './Views/templates/landing/footer.php'; ?>