<?php
// worker_pedido.php

// Conexión a Redis
$redis = new Redis();
$redis->connect('3.233.119.65', 6379);

// Datos de conexión a la base de datos
const HOST = '3.233.119.65';
const USER = "imporsuit_system";
const PASSWORD = "imporsuit_system";
const DB = "imporsuitpro_new";
const CHARSET = "utf8mb4";

// Crear carpeta de logs si no existe
$logDirectory = __DIR__ . '/logs';
if (!is_dir($logDirectory)) {
    mkdir($logDirectory, 0777, true);
}

// Ruta del archivo de log
$logFile = $logDirectory . '/error_log_worker_pedido.txt';

// Función para registrar errores en el archivo de log
function logError($message)
{
    global $logFile;
    $timestamp = date("Y-m-d H:i:s");
    file_put_contents($logFile, "[$timestamp] $message\n", FILE_APPEND);
}

function checkConnection($conn)
{
    if (!$conn->ping()) {
        global $conn;
        logError("Conexión a MySQL perdida. Intentando reconectar...");
        $conn = new mysqli(HOST, USER, PASSWORD, DB);

        if ($conn->connect_error) {
            logError("Error al reconectar a MySQL: " . $conn->connect_error);
            die("Error al reconectar a MySQL: " . $conn->connect_error);
        }
    }
}

// Establecer conexión con la base de datos
$conn = new mysqli(HOST, USER, PASSWORD, DB);

if ($conn->connect_error) {
    logError("Connection failed: " . $conn->connect_error);
    die("Connection failed: " . $conn->connect_error);
}

// Función para incrementar número de factura
function incrementarNumeroFactura($numero_factura)
{
    $numero = (int)substr($numero_factura, 4);
    $nuevo_numero = str_pad($numero + 1, 10, '0', STR_PAD_LEFT);
    return 'COT-' . $nuevo_numero;
}

// Función para procesar un pedido desde la cola
function procesarPedido($conn, $data)
{
    checkConnection($conn);

    try {
        // Iniciar transacción
        $conn->begin_transaction();

        // Obtener el último número de factura
        $ultimaFactura = $conn->query("SELECT MAX(numero_factura) as factura_numero FROM facturas_cot");
        $row = $ultimaFactura->fetch_assoc();
        $facturaNumero = $row['factura_numero'] ?? 'COT-0000000000';

        $nuevaFactura = incrementarNumeroFactura($facturaNumero);

        // Insertar en la tabla `facturas_cot`
        $sql = "INSERT INTO facturas_cot (
            numero_factura, fecha_factura, id_usuario, monto_factura, estado_factura,
            nombre, telefono, c_principal, ciudad_cot, c_secundaria, referencia, observacion,
            guia_enviada, transporte, identificacion, celular, id_propietario, drogshipin,
            id_plataforma, importado, plataforma_importa, cod, estado_guia_sistema, impreso,
            facturada, anulada, identificacionO, nombreO, ciudadO, provinciaO, provincia,
            direccionO, referenciaO, numeroCasaO, valor_seguro, no_piezas, tipo_servicio,
            peso, contiene, costo_flete, costo_producto, comentario, id_transporte, telefonoO, id_bodega
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";        

        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            throw new Exception("Error al preparar la consulta principal: " . $conn->error);
        }

        $stmt->bind_param(
            'ssidsissssssssssiiisisiiissssssissisdsdssddsssi',
            $nuevaFactura,             // varchar(20)
            $data['fecha_factura'],    // datetime
            $data['id_usuario'],       // int
            $data['monto_factura'],    // double
            $data['estado_factura'],   // tinyint(1)
            $data['nombre_cliente'],   // varchar(500)
            $data['telefono_cliente'], // varchar(500)
            $data['c_principal'],      // varchar(500)
            $data['ciudad_cot'],       // varchar(255)
            $data['c_secundaria'],     // varchar(1500)
            $data['referencia'],       // varchar(1500)
            $data['observacion'],      // varchar(1500)
            $data['guia_enviada'],     // int
            $data['transporte'],       // varchar(100)
            $data['identificacion'],   // varchar(20)
            $data['celular'],          // varchar(20)
            $data['dueño_id'],         // int
            $data['dropshipping'],     // int
            $data['id_plataforma'],    // int
            $data['importado'],        // int
            $data['plataforma_importa'], // varchar(100)
            $data['cod'],              // tinyint(1)
            $data['estado_guia_sistema'], // int
            $data['impreso'],          // tinyint(1)
            $data['facturada'],        // tinyint(1)
            $data['anulada'],          // tinyint(1)
            $data['identificacionO'],  // varchar(100)
            $data['nombreO'],          // text
            $data['ciudadO'],          // text
            $data['provinciaO'],       // int
            $data['provincia'],        // varchar(500)
            $data['direccionO'],       // text
            $data['referenciaO'],      // text
            $data['numeroCasaO'],      // text
            $data['valor_segura'],     // double
            $data['no_piezas'],        // int
            $data['tipo_servicio'],    // varchar(200)
            $data['peso'],             // double
            $data['contiene'],         // varchar(200)
            $data['costo_flete'],      // double
            $data['costo_producto'],   // double
            $data['comentario'],       // varchar(500)
            $data['id_transporte'],    // int
            $data['celularO'],         // text
            $data['id_bodega']         // int
        );        

        if (!$stmt->execute()) {
            throw new Exception("Error al ejecutar la consulta principal: " . $stmt->error);
        }

        $facturaId = $conn->insert_id;

        // Insertar detalles desde `detalle_fact_cot`
        foreach ($data['detalle'] as $detalle) {
            $detalleSql = "INSERT INTO detalle_fact_cot (
                numero_factura, id_factura, id_producto, cantidad, desc_venta,
                precio_venta, id_plataforma, sku, id_inventario
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $detalleStmt = $conn->prepare($detalleSql);

            if (!$detalleStmt) {
                throw new Exception("Error al preparar la consulta de detalle: " . $conn->error);
            }

            $detalleStmt->bind_param(
                'siiiidisi',
                $nuevaFactura,              // `numero_factura` - string (s)
                $facturaId,                 // `id_factura` - int (i)
                $detalle['id_producto'],    // `id_producto` - int (i)
                $detalle['cantidad'],       // `cantidad` - int (i)
                $detalle['desc_venta'],     // `desc_venta` - int (i)
                $detalle['precio_venta'],   // `precio_venta` - double (d)
                $detalle['id_plataforma'],  // `id_plataforma` - int (i)
                $detalle['sku'],            // `sku` - string (s)
                $detalle['id_inventario']   // `id_inventario` - int (i)
            );            

            if (!$detalleStmt->execute()) {
                throw new Exception("Error al ejecutar la consulta de detalle: " . $detalleStmt->error);
            }
        }

        // Confirmar transacción
        $conn->commit();
        logError("Pedido procesado correctamente: $nuevaFactura");
    } catch (Exception $e) {
        $conn->rollback();
        logError("Error al procesar el pedido: " . $e->getMessage());
    }
}

// Bucle principal del Worker
while (true) {
    try {
        checkConnection($conn);
        $job = $redis->rPop("queue:facturas");

        if ($job) {
            $data = json_decode($job, true);

            if (!$data) {
                logError("Error al decodificar JSON: $job");
                continue;
            }

            procesarPedido($conn, $data);
        } else {
            sleep(1); // Esperar si no hay pedidos en la cola
        }
    } catch (Exception $e) {
        logError("Error en el Worker: " . $e->getMessage());
    }
}