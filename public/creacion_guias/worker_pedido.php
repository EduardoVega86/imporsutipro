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
        global $conn; // Asegúrate de usar la variable global $conn
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

// Función para insertar factura y detalles
function procesarFactura($conn, $data)
{
    // Verificar conexión activa
    checkConnection($conn);

    try {
        // Iniciar transacción
        $conn->begin_transaction();

        // Obtener el último número de factura
        $query = "SELECT MAX(numero_factura) as factura_numero FROM facturas_cot";
        $result = $conn->query($query);
        $row = $result->fetch_assoc();
        $ultima_factura = $row['factura_numero'] ?? 'COT-0000000000';

        $nueva_factura = incrementarNumeroFactura($ultima_factura);

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
        $stmt->bind_param(
            'ssidsissssssssssississississssssissssss',
            $nueva_factura,
            $data['fecha_factura'],
            $data['id_usuario'],
            $data['monto_factura'],
            $data['estado_factura'],
            $data['nombre_cliente'],
            $data['telefono_cliente'],
            $data['c_principal'],
            $data['ciudad_cot'],
            $data['c_secundaria'],
            $data['referencia'],
            $data['observacion'],
            $data['guia_enviada'],
            $data['transporte'],
            $data['identificacion'],
            $data['celular'],
            $data['dueño_id'],
            $data['dropshipping'],
            $data['id_plataforma'],
            $data['importado'],
            $data['plataforma_importa'],
            $data['cod'],
            $data['estado_guia_sistema'],
            $data['impreso'],
            $data['facturada'],
            $data['anulada'],
            $data['identificacionO'],
            $data['nombreO'],
            $data['ciudadO'],
            $data['provinciaO'],
            $data['provincia'],
            $data['direccionO'],
            $data['referenciaO'],
            $data['numeroCasaO'],
            $data['valor_segura'],
            $data['no_piezas'],
            $data['tipo_servicio'],
            $data['peso'],
            $data['contiene'],
            $data['costo_flete'],
            $data['costo_producto'],
            $data['comentario'],
            $data['id_transporte'],
            $data['celularO'],
            $data['id_bodega']
        );

        if (!$stmt->execute()) {
            throw new Exception("Error al insertar factura: " . $stmt->error);
        }

        $factura_id = $conn->insert_id;

        // Insertar detalles de cotización
        foreach ($data['detalle'] as $detalle) {
            $detalle_sql = "INSERT INTO detalle_fact_cot (
                numero_factura, id_factura, id_producto, cantidad, desc_venta,
                precio_venta, id_plataforma, sku, id_inventario
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $detalle_stmt = $conn->prepare($detalle_sql);
            $detalle_stmt->bind_param(
                'siidsssii',
                $nueva_factura,
                $factura_id,
                $detalle['id_producto'],
                $detalle['cantidad'],
                $detalle['desc_venta'],
                $detalle['precio_venta'],
                $detalle['id_plataforma'],
                $detalle['sku'],
                $detalle['id_inventario']
            );

            if (!$detalle_stmt->execute()) {
                throw new Exception("Error al insertar detalle: " . $detalle_stmt->error);
            }
        }

        // Confirmar transacción
        $conn->commit();
        logError("Factura procesada correctamente: $nueva_factura");
    } catch (Exception $e) {
        $conn->rollback();
        logError("Error al procesar factura: " . $e->getMessage());
    }
}

// Bucle principal del worker
while (true) {
    try {
        // Verificar conexión activa
        checkConnection($conn);

        // Obtener el siguiente mensaje de la cola
        $job = $redis->rPop("queue:facturas");

        if ($job) {
            $data = json_decode($job, true);

            if (!$data) {
                logError("Error al decodificar JSON: $job");
                continue;
            }

            // Procesar el trabajo
            procesarFactura($conn, $data);
        } else {
            sleep(1); // Esperar si no hay trabajos en la cola
        }
    } catch (Exception $e) {
        logError("Error en el Worker: " . $e->getMessage());
    }
}
