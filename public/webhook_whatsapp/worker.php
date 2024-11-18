<?php
// worker.php

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
$logFile = $logDirectory . '/error_log_worker.txt';

// Función para registrar errores en el archivo de log
function logError($message)
{
    global $logFile;
    $timestamp = date("Y-m-d H:i:s");
    file_put_contents($logFile, "[$timestamp] $message\n", FILE_APPEND);
}

// Establecer conexión con la base de datos
$conn = new mysqli(HOST, USER, PASSWORD, DB);

if ($conn->connect_error) {
    logError("Connection failed: " . $conn->connect_error);
    die("Connection failed: " . $conn->connect_error);
}

// Función para insertar el mensaje en espera
function insertar_mensaje_espera($conn, $id_plataforma, $id_cliente, $id_mensaje_insertado, $created_at, $id_whatsapp_message_template)
{
    $stmt = $conn->prepare("INSERT INTO mensajes_espera (id_plataforma, id_cliente_chat_center, id_mensajes_clientes, estado, id_whatsapp_message_template, fecha_envio) VALUES (?, ?, ?, ?, ?, ?)");
    if ($stmt === false) {
        logError("Failed to prepare insertar_mensaje_espera query: " . $conn->error);
        return;
    }

    // Convertir variables a los tipos correctos
    $id_plataforma = (int)$id_plataforma;
    $id_cliente = (int)$id_cliente;
    $id_mensaje_insertado = (int)$id_mensaje_insertado;
    $estado = 0;
    $id_whatsapp_message_template = (string)$id_whatsapp_message_template;
    $created_at = (string)$created_at;

    $stmt->bind_param('iiiiss', $id_plataforma, $id_cliente, $id_mensaje_insertado, $estado, $id_whatsapp_message_template, $created_at);
    if (!$stmt->execute()) {
        logError("Failed to execute insertar_mensaje_espera query: " . $stmt->error);
    }

    $stmt->close();
}

// Función insertMessageDetails
function insertMessageDetails($conn, $id_automatizador, $uid_whatsapp, $mensaje, $json_mensaje, $id_configuracion, $user_info, $id_whatsapp_message_template)
{
    $id_plataforma = "";
    $uid_cliente = "";
    $id_cliente = "";
    $telefono_configuracion = "";

    // Consulta configuración
    $check_configuracion_stmt = $conn->prepare("SELECT id_plataforma, id_telefono, telefono FROM configuraciones WHERE id = ?");
    if ($check_configuracion_stmt === false) {
        logError("Failed to prepare check_configuracion query: " . $conn->error);
        return;
    }
    $check_configuracion_stmt->bind_param('s', $id_configuracion);
    $check_configuracion_stmt->execute();
    $check_configuracion_stmt->store_result();
    $check_configuracion_stmt->bind_result($id_plataforma, $uid_cliente, $telefono_configuracion);
    $check_configuracion_stmt->fetch();
    $check_configuracion_stmt->close();

    // Obtener id_cliente_configuracion
    $id_cliente_configuracion = "";
    $check_idCliente_configuracion_stmt = $conn->prepare("SELECT id FROM clientes_chat_center WHERE celular_cliente = ? AND id_plataforma = ?");
    if ($check_idCliente_configuracion_stmt === false) {
        logError("Failed to prepare check_idCliente_configuracion query: " . $conn->error);
        return;
    }
    $check_idCliente_configuracion_stmt->bind_param('si', $telefono_configuracion, $id_plataforma);
    $check_idCliente_configuracion_stmt->execute();
    $check_idCliente_configuracion_stmt->store_result();
    $check_idCliente_configuracion_stmt->bind_result($id_cliente_configuracion);
    $check_idCliente_configuracion_stmt->fetch();
    $check_idCliente_configuracion_stmt->close();

    // Verificar si el cliente ya existe en la tabla
    $check_client_stmt = $conn->prepare("SELECT id FROM clientes_chat_center WHERE celular_cliente = ? AND id_plataforma = ?");
    if ($check_client_stmt === false) {
        logError("Failed to prepare check_client query: " . $conn->error);
        return;
    }
    $check_client_stmt->bind_param('si', $uid_whatsapp, $id_plataforma);
    $check_client_stmt->execute();
    $check_client_stmt->store_result();

    if ($check_client_stmt->num_rows == 0) {
        // El cliente no existe, lo creamos
        $insert_client_stmt = $conn->prepare("INSERT INTO clientes_chat_center (id_plataforma, uid_cliente, nombre_cliente, celular_cliente, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())");
        if ($insert_client_stmt === false) {
            logError("Failed to prepare insert_client query: " . $conn->error);
            return;
        }
        $insert_client_stmt->bind_param('isss', $id_plataforma, $uid_cliente, $user_info['nombre'], $uid_whatsapp);
        $insert_client_stmt->execute();
        $id_cliente = $insert_client_stmt->insert_id;
        $insert_client_stmt->close();
    } else {
        // El cliente existe, obtenemos su ID
        $check_client_stmt->bind_result($id_cliente);
        $check_client_stmt->fetch();
    }
    $check_client_stmt->close();

    date_default_timezone_set('America/Guayaquil');
    $created_at = date('Y-m-d H:i:s');
    $updated_at = date('Y-m-d H:i:s');
    $user_info_json = json_encode($user_info);

    $stmt = $conn->prepare("INSERT INTO mensajes_clientes (id_plataforma, id_cliente, mid_mensaje, tipo_mensaje, celular_recibe, ruta_archivo, id_automatizador, uid_whatsapp, texto_mensaje, rol_mensaje, json_mensaje, created_at, updated_at, notificacion_estado) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if ($stmt === false) {
        logError("Failed to prepare insertMessageDetails query: " . $conn->error);
        return;
    }

    $stmt->bind_param('iissssississsi', $id_plataforma, $id_cliente_configuracion, $uid_cliente, $tipo_mensaje, $id_cliente, $user_info_json, $id_automatizador, $uid_whatsapp, $mensaje, $rol, $json_mensaje, $created_at, $updated_at, $estado_notificacion);
    if (!$stmt->execute()) {
        logError("Failed to execute insertMessageDetails query: " . $stmt->error);
    }

    $id_mensaje_insertado = $stmt->insert_id;
    $stmt->close();

    insertar_mensaje_espera($conn, $id_plataforma, $id_cliente, $id_mensaje_insertado, $created_at, $id_whatsapp_message_template);
}

// Condición para Postman o clientes HTTP
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = file_get_contents('php://input');
    $message_data = json_decode($data, true);

    if (!$message_data) {
        echo json_encode(["status" => "error", "message" => "Invalid JSON"]);
        exit();
    }

    $id_automatizador = $message_data['id_automatizador'] ?? null;
    $uid_whatsapp = $message_data['uid_whatsapp'] ?? null;
    $mensaje = $message_data['mensaje'] ?? null;
    $json_mensaje = $message_data['json_mensaje'] ?? null;
    $id_configuracion = $message_data['id_configuracion'] ?? null;
    $user_info = $message_data['user_info'] ?? null;
    $id_whatsapp_message_template = $message_data['id_whatsapp_message_template'] ?? null;

    insertMessageDetails($conn, $id_automatizador, $uid_whatsapp, $mensaje, $json_mensaje, $id_configuracion, $user_info, $id_whatsapp_message_template);

    echo json_encode(["status" => "success", "message" => "Processed"]);
    exit();
}

// Bucle principal del Worker
while (true) {
    $message_json = $redis->rPop("message_queue");

    if ($message_json) {
        $message_data = json_decode($message_json, true);

        $id_automatizador = $message_data['id_automatizador'];
        $uid_whatsapp = $message_data['uid_whatsapp'];
        $mensaje = $message_data['mensaje'];
        $json_mensaje = $message_data['json_mensaje'];
        $id_configuracion = $message_data['id_configuracion'];
        $user_info = $message_data['user_info'];
        $id_whatsapp_message_template = $message_data['id_whatsapp_message_template'];

        insertMessageDetails($conn, $id_automatizador, $uid_whatsapp, $mensaje, $json_mensaje, $id_configuracion, $user_info, $id_whatsapp_message_template);
    } else {
        sleep(1);
    }
}
