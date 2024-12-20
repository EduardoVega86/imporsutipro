<?php
// Configuración de la base de datos
$host = "localhost";
$db_name = "sistema_oneshow"; // Cambia esto
$username = "root"; // Cambia si usas otro usuario
$password = ""; // Cambia si tienes contraseña
$conn = null;

try {
    $conn = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
    exit;
}

// Obtener el ID del bar y el evento
$id_bar = isset($_GET['id_bar']) ? intval($_GET['id_bar']) : null;
$id_evento = isset($_GET['id_evento']) ? intval($_GET['id_evento']) : null;

if (!$id_bar || !$id_evento) {
    die("Error: ID del bar o evento no especificado.");
}

$success_message = null;

// Manejar la actualización de saldos
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_producto'])) {
    $id_producto = intval($_POST['id_producto']);
    $saldo_final = floatval($_POST['saldo_final']);

    // Calcular la cantidad vendida
    $query_cantidad = "SELECT 
                          COALESCE(SUM(CASE WHEN tipo_movimiento = 'entrada' THEN cantidad ELSE 0 END), 0) AS entradas,
                          COALESCE(SUM(CASE WHEN tipo_movimiento = 'salida' THEN cantidad ELSE 0 END), 0) AS salidas
                       FROM movimientos_tmp 
                       WHERE id_producto = :id_producto AND id_bar = :id_bar";
    $stmt_cantidad = $conn->prepare($query_cantidad);
    $stmt_cantidad->bindParam(':id_producto', $id_producto);
    $stmt_cantidad->bindParam(':id_bar', $id_bar);
    $stmt_cantidad->execute();
    $result = $stmt_cantidad->fetch(PDO::FETCH_ASSOC);

    $entradas = $result['entradas'];
    $salidas = $result['salidas'];
    $vendidos = $entradas - $salidas - $saldo_final;

    // Obtener el valor del producto y las unidades
    $query_valor = "SELECT valor, unidades FROM productos_tmp WHERE id_producto = :id_producto";
    $stmt_valor = $conn->prepare($query_valor);
    $stmt_valor->bindParam(':id_producto', $id_producto);
    $stmt_valor->execute();
    $producto_data = $stmt_valor->fetch(PDO::FETCH_ASSOC);

    $valor = $producto_data['valor'];
    $unidades = $producto_data['unidades'];

    $ganancia = ($entradas - $salidas) * $valor;

    // Registrar el saldo final como movimiento de salida
    $query_salida = "INSERT INTO movimientos_tmp (id_bar, id_producto, cantidad, tipo_movimiento) 
                     VALUES (:id_bar, :id_producto, :cantidad, 'salida')";
    $stmt_salida = $conn->prepare($query_salida);
    $stmt_salida->bindParam(':id_bar', $id_bar);
    $stmt_salida->bindParam(':id_producto', $id_producto);
    $stmt_salida->bindParam(':cantidad', $saldo_final);
    $stmt_salida->execute();

    // Guardar un mensaje de éxito en la sesión y redirigir (PRG)
    session_start();
    $_SESSION['success_message'] = "Saldo registrado correctamente.";
    header("Location: ingresar_saldos.php?id_bar=$id_bar&id_evento=$id_evento");
    exit;
}

// Obtener movimientos agrupados por producto (solo asignados)
$query_movimientos = "SELECT 
                          p.id_producto,
                          p.nombre_producto,
                          p.valor,
                          p.unidades,
                          COALESCE(SUM(CASE WHEN tipo_movimiento = 'entrada' THEN cantidad ELSE 0 END), 0) AS total_entradas,
                          COALESCE(SUM(CASE WHEN tipo_movimiento = 'salida' THEN cantidad ELSE 0 END), 0) AS total_salidas
                      FROM productos_tmp p
                      LEFT JOIN movimientos_tmp m ON p.id_producto = m.id_producto AND m.id_bar = :id_bar
                      GROUP BY p.id_producto, p.nombre_producto, p.valor, p.unidades
                      HAVING total_entradas > 0";
$stmt_movimientos = $conn->prepare($query_movimientos);
$stmt_movimientos->bindParam(':id_bar', $id_bar);
$stmt_movimientos->execute();
$productos = $stmt_movimientos->fetchAll(PDO::FETCH_ASSOC);

// Obtener el mensaje de éxito desde la sesión y limpiarlo
session_start();
if (isset($_SESSION['success_message'])) {
    $success_message = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ingresar Saldos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .sin-saldo {
            background-color: #f8d7da !important; /* Rojo claro */
        }
        .con-saldo {
            background-color: #d4edda !important; /* Verde claro */
        }
    </style>
</head>
<body>
    <div class="container my-5">
        <h1 class="text-center mb-4">Ingresar Saldos Finales</h1>

        <?php if ($success_message): ?>
            <div class="alert alert-success"><?= $success_message ?></div>
        <?php endif; ?>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Entradas</th>
                    <th>Salidas</th>
                    <th>Ingresos - Salidas</th>
                    <th>Total por Unidades</th>
                    <th>Ganancia</th>
                    <th>Saldo Final</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($productos as $producto): ?>
                <?php
                    $entradas = $producto['total_entradas'];
                    $salidas = $producto['total_salidas'];
                    $restante = $entradas - $salidas;
                    $total_unidades = $restante * $producto['unidades'];
                    $ganancia = $restante * $producto['valor'];

                    // Determinar la clase CSS para la fila
                    $row_class = ($salidas > 0) ? 'con-saldo' : 'sin-saldo';
                ?>
                <tr class="<?= $row_class ?>">
                    <form action="" method="POST">
                        <input type="hidden" name="id_producto" value="<?= $producto['id_producto'] ?>">
                        <td><?= htmlspecialchars($producto['nombre_producto']) ?></td>
                        <td><?= htmlspecialchars($entradas) ?></td>
                        <td><?= htmlspecialchars($salidas) ?></td>
                        <td><?= htmlspecialchars($restante) ?></td>
                        <td><?= htmlspecialchars($total_unidades) ?></td>
                        <td><?= htmlspecialchars($ganancia) ?></td>
                        <td>
                            <input type="number" step="0.01" name="saldo_final" 
                                   class="form-control" placeholder="Saldo final" required>
                        </td>
                        <td>
                            <button type="submit" class="btn btn-primary btn-sm">Guardar</button>
                        </td>
                    </form>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="text-center mt-4">
            <a href="gestionar_bar.php?id_bar=<?= $id_bar ?>&id_evento=<?= $id_evento ?>" class="btn btn-secondary">Regresar a Gestión de Bar</a>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
