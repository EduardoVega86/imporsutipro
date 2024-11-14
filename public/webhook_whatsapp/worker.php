<?php
// worker.php

// Configuración del archivo de log
$logDirectory = __DIR__ . '/logs';
$logFile = $logDirectory . '/error_log.log';

// Crear la carpeta de logs si no existe
if (!is_dir($logDirectory)) {
    mkdir($logDirectory, 0777, true);
}

// Conexión a Redis
$redis = new Redis();
$redis->connect('3.233.119.65', 6379);

// Datos de conexión a la base de datos
const HOST = '3.233.119.65';
const USER = "imporsuit_system";
const PASSWORD = "imporsuit_system";
const DB = "imporsuitpro_new";
const CHARSET = "utf8mb4";

// Establecer conexión con la base de datos
$conn = new mysqli(HOST, USER, PASSWORD, DB);

if ($conn->connect_error) {
    error_log("Connection failed: " . $conn->connect_error, 3, $logFile);
    die("Connection failed: " . $conn->connect_error);
}

// Función insertMessageDetails
function insertMessageDetails($conn, $id_automatizador, $uid_whatsapp, $mensaje, $json_mensaje, $id_configuracion, $user_info, $id_whatsapp_message_template)
{
    global $logFile;
    $id_plataforma = "";
    $uid_cliente = "";
    $id_cliente = "";
    $telefono_configuracion = "";

    // Consulta configuración
    $check_configuracion_stmt = $conn->prepare("SELECT id_plataforma, id_telefono, telefono FROM configuraciones WHERE id = ?");
    if ($check_configuracion_stmt === false) {
        error_log("Error preparando la consulta: " . $conn->error, 3, $logFile);
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
        error_log("Error preparando la consulta para clientes_chat_center: " . $conn->error, 3, $logFile);
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
        error_log("Error preparando la consulta para verificar cliente existente: " . $conn->error, 3, $logFile);
        return;
    }
    $check_client_stmt->bind_param('si', $uid_whatsapp, $id_plataforma);
    $check_client_stmt->execute();
    $check_client_stmt->store_result();

    if ($check_client_stmt->num_rows == 0) {
        // El cliente no existe, lo creamos
        $insert_client_stmt = $conn->prepare("INSERT INTO clientes_chat_center (id_plataforma, uid_cliente, nombre_cliente, celular_cliente, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())");
        if ($insert_client_stmt === false) {
            error_log("Error preparando la consulta para insertar nuevo cliente: " . $conn->error, 3, $logFile);
            return;
        }
        $insert_client_stmt->bind_param('isss', $id_plataforma, $uid_cliente, $user_info['nombre'], $uid_whatsapp);
        $insert_client_stmt->execute();
        if ($insert_client_stmt->error) {
            error_log("Error ejecutando la consulta para insertar nuevo cliente: " . $insert_client_stmt->error, 3, $logFile);
        }
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
    $estado_notificacion = isset($user_info['estado_notificacion']) ? $user_info['estado_notificacion'] : 0;

    // Insertar mensaje en mensajes_clientes
    $stmt = $conn->prepare("INSERT INTO mensajes_clientes (id_plataforma, id_cliente, mid_mensaje, tipo_mensaje, celular_recibe, ruta_archivo, id_automatizador, uid_whatsapp, texto_mensaje, rol_mensaje, json_mensaje, created_at, updated_at, notificacion_estado) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if ($stmt === false) {
        error_log("Error preparando la consulta para mensajes_clientes: " . $conn->error, 3, $logFile);
        return;
    }

    // Convertir variables y ejecutar
    $stmt->bind_param('iissssississsi', $id_plataforma, $id_cliente_configuracion, $uid_cliente, "text", $uid_whatsapp, "", $id_automatizador, $uid_whatsapp, $mensaje, 1, $json_mensaje, $created_at, $updated_at, $estado_notificacion);
    $stmt->execute();
    if ($stmt->error) {
        error_log("Error ejecutando la consulta para mensajes_clientes: " . $stmt->error, 3, $logFile);
    }
    $id_mensaje_insertado = $stmt->insert_id;
    $stmt->close();

    // Llamar a insertar_mensaje_espera
    insertar_mensaje_espera($conn, $id_plataforma, $id_cliente, $id_mensaje_insertado, $created_at, $id_whatsapp_message_template);
}

// Función para insertar el mensaje en espera
function insertar_mensaje_espera($conn, $id_plataforma, $id_cliente, $id_mensaje_insertado, $created_at, $id_whatsapp_message_template)
{
    global $logFile;
    $stmt = $conn->prepare("INSERT INTO mensajes_espera (id_plataforma, id_cliente_chat_center, id_mensajes_clientes, estado, id_whatsapp_message_template, fecha_envio) VALUES (?, ?, ?, ?, ?, ?)");
    if ($stmt === false) {
        error_log("Error preparando la consulta para mensajes_espera: " . $conn->error, 3, $logFile);
        return;
    }

    // Convertir variables y ejecutar
    $estado = 0;
    $stmt->bind_param('iiiiss', $id_plataforma, $id_cliente, $id_mensaje_insertado, $estado, $id_whatsapp_message_template, $created_at);
    $stmt->execute();
    if ($stmt->error) {
        error_log("Error ejecutando la consulta para mensajes_espera: " . $stmt->error, 3, $logFile);
    }
    $stmt->close();
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
