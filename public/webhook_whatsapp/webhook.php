<?php
// Configuración de los encabezados
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

$webhook_token = "ABCDEFG1234";  // Token de verificación
$debug_log = [];

// Datos de conexión a la base de datos
const HOST = '3.233.119.65';
const USER = "imporsuit_system";
const PASSWORD = "imporsuit_system";
const DB = "imporsuitpro_new";
const CHARSET = "utf8";

// Establecer conexión con la base de datos
$conn = new mysqli(HOST, USER, PASSWORD, DB);

// Verificar la conexión
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Error al conectar con la base de datos: " . $conn->connect_error]));
}

// Configurar el charset de la conexión
if (!$conn->set_charset(CHARSET)) {
    echo json_encode(["status" => "error", "message" => "Error al establecer el charset de la base de datos: " . $conn->error]);
    exit;
}

// Verificación del webhook para el desafío de validación
if (isset($_GET['hub_challenge']) && isset($_GET['hub_verify_token'])) {
    if ($webhook_token === $_GET['hub_verify_token']) {
        echo $_GET['hub_challenge'];
        exit;
    } else {
        echo json_encode(["status" => "error", "message" => "Token de verificación incorrecto."]);
        exit;
    }
}

// Leer los datos enviados por WhatsApp
$input = file_get_contents("php://input");

// Guardar el JSON recibido en un archivo para verificar qué datos están llegando
file_put_contents('whatsapp_debug_raw.txt', $input . "\n", FILE_APPEND);

$data_msg_whatsapp = json_decode($input, true);

// Validar que los datos recibidos no están vacíos
if (empty($data_msg_whatsapp)) {
    file_put_contents('debug_log.txt', "Error: Datos inválidos o vacíos\n", FILE_APPEND);
    echo json_encode(["status" => "error", "message" => "Datos inválidos o vacíos."]);
    exit;
}

// Guardar los datos recibidos en el log de depuración
$debug_log['data_msg_whatsapp'] = $data_msg_whatsapp;
file_put_contents('debug_log.txt', print_r($debug_log, true) . "\n", FILE_APPEND);

// Extraer los datos generales del mensaje de la estructura real de WhatsApp
if (isset($data_msg_whatsapp['entry'][0]['changes'][0]['value'])) {
    $whatsapp_value = $data_msg_whatsapp['entry'][0]['changes'][0]['value'];
} else {
    // Si no encontramos la estructura esperada, registramos el error
    file_put_contents('debug_log.txt', "Error: Estructura del mensaje no válida\n", FILE_APPEND);
    echo json_encode(["status" => "error", "message" => "Estructura del mensaje no válida."]);
    exit;
}

// Extraer datos del mensaje
$business_phone_id = $whatsapp_value['metadata']['phone_number_id'] ?? '';  // Obtenemos el phone_number_id
$phone_whatsapp_from = $whatsapp_value['messages'][0]['from'] ?? '';  // Obtenemos el remitente
$name_whatsapp_from = $whatsapp_value['contacts'][0]['profile']['name'] ?? '';  // Nombre del remitente
$tipo_mensaje = $whatsapp_value['messages'][0]['type'] ?? '';  // Tipo de mensaje

// Separar el nombre y apellido (en caso de que estén juntos en el campo "name")
$nombre_completo = explode(" ", $name_whatsapp_from);
$nombre_cliente = $nombre_completo[0] ?? '';  // Primer nombre
$apellido_cliente = isset($nombre_completo[1]) ? $nombre_completo[1] : '';  // Primer apellido (si existe)

// Verificación si los datos claves están presentes
if (empty($phone_whatsapp_from) || empty($business_phone_id)) {
    file_put_contents('debug_log.txt', "Error: Datos del mensaje incompletos\n", FILE_APPEND);
    echo json_encode(["status" => "error", "message" => "Datos del mensaje incompletos."]);
    exit;
}

// Procesar diferentes tipos de mensajes de WhatsApp
$texto_mensaje = "";
$respuesta_WEBHOOK_messages = $whatsapp_value['messages'][0];  // Ajuste para obtener el mensaje correctamente

// Procesar el mensaje basado en el tipo recibido
switch ($tipo_mensaje) {
    case 'text':
        $texto_mensaje = $respuesta_WEBHOOK_messages['text']['body'];
        break;

    case 'image':
        $texto_mensaje = "Imagen recibida con ID: " . $respuesta_WEBHOOK_messages['image']['id'];
        if (isset($respuesta_WEBHOOK_messages['image']['caption'])) {
            $texto_mensaje .= ", con pie de foto: " . $respuesta_WEBHOOK_messages['image']['caption'];
        }
        break;

    case 'video':
        $texto_mensaje = "Video recibido con ID: " . $respuesta_WEBHOOK_messages['video']['id'];
        if (isset($respuesta_WEBHOOK_messages['video']['caption'])) {
            $texto_mensaje .= ", con pie de foto: " . $respuesta_WEBHOOK_messages['video']['caption'];
        }
        break;

    case 'audio':
        $texto_mensaje = "Audio recibido con ID: " . $respuesta_WEBHOOK_messages['audio']['id'];
        break;

    case 'document':
        $texto_mensaje = "Documento recibido con ID: " . $respuesta_WEBHOOK_messages['document']['id'];
        if (isset($respuesta_WEBHOOK_messages['document']['filename'])) {
            $texto_mensaje .= ", nombre de archivo: " . $respuesta_WEBHOOK_messages['document']['filename'];
        }
        break;

    case 'location':
        $location = $respuesta_WEBHOOK_messages['location'];
        $texto_mensaje = "Ubicación recibida: Latitud " . $location['latitude'] . ", Longitud " . $location['longitude'];
        break;

    case 'contacts':
        $contacts_whatsapp = [];
        foreach ($respuesta_WEBHOOK_messages['contacts'] as $contact) {
            $contact_formatted_name_whatsapp = $contact['name']['formatted_name'] ?? '';
            $contact_phone_whatsapp = $contact['phones'][0]['wa_id'] ?? '';
            $contacts_whatsapp[] = "Nombre: $contact_formatted_name_whatsapp, Teléfono: $contact_phone_whatsapp";
        }
        $texto_mensaje = implode(", ", $contacts_whatsapp);
        break;

    case 'interactive':
        if ($respuesta_WEBHOOK_messages['interactive']['type'] === "button_reply") {
            $texto_mensaje = "Respuesta de botón: " . $respuesta_WEBHOOK_messages['interactive']['button_reply']['title'];
        } else if ($respuesta_WEBHOOK_messages['interactive']['type'] === "list_reply") {
            $texto_mensaje = "Respuesta de lista: " . $respuesta_WEBHOOK_messages['interactive']['list_reply']['title'];
        }
        break;

    case 'sticker':
        $texto_mensaje = "Sticker recibido con ID: " . $respuesta_WEBHOOK_messages['sticker']['id'];
        break;

    default:
        $texto_mensaje = "Tipo de mensaje no reconocido.";
}

// Registrar en el log de depuración
$debug_log['texto_mensaje'] = $texto_mensaje;
file_put_contents('debug_log.txt', "Mensaje procesado: " . $texto_mensaje . "\n", FILE_APPEND);

// Verificar si el cliente ya existe en la tabla clientes_chat_center por uid_cliente
$check_client_stmt = $conn->prepare("SELECT id FROM clientes_chat_center WHERE uid_cliente = ?");
$check_client_stmt->bind_param('s', $business_phone_id);  // Buscamos por el uid_cliente
$check_client_stmt->execute();
$check_client_stmt->store_result();

$id_plataforma = 1190;  // Ajustar según sea necesario

if ($check_client_stmt->num_rows == 0) {
    // El cliente no existe, creamos uno nuevo
    $insert_client_stmt = $conn->prepare("
        INSERT INTO clientes_chat_center (id_plataforma, uid_cliente, nombre_cliente, apellido_cliente, celular_cliente, created_at, updated_at) 
        VALUES (?, ?, ?, ?, ?, NOW(), NOW())
    ");
    $insert_client_stmt->bind_param('issss', $id_plataforma, $business_phone_id, $nombre_cliente, $apellido_cliente, $phone_whatsapp_from);
    $insert_client_stmt->execute();
    $id_cliente = $insert_client_stmt->insert_id;  // Obtener el ID autoincrementado del cliente recién creado
    $insert_client_stmt->close();
} else {
    // El cliente existe, obtenemos su ID
    $check_client_stmt->bind_result($id_cliente);
    $check_client_stmt->fetch();
}

$check_client_stmt->close();

// Ahora puedes proceder a insertar el mensaje en la tabla mensajes_clientes
$stmt = $conn->prepare("
    INSERT INTO mensajes_clientes (id_plataforma, id_cliente, mid_mensaje, tipo_mensaje, texto_mensaje, rol_mensaje, celular_recibe, created_at, updated_at) 
    VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
");

$mid_mensaje = $business_phone_id;  // Usamos el ID del mensaje de WhatsApp
$rol_mensaje = 0;  // Valor por defecto para rol_mensaje, ya que es bigint

$stmt->bind_param('iisssi', $id_plataforma, $id_cliente, $mid_mensaje, $tipo_mensaje, $texto_mensaje, $rol_mensaje, $id_cliente);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Mensaje procesado correctamente."]);
} else {
    file_put_contents('debug_log.txt', "Error SQL: " . $stmt->error . "\n", FILE_APPEND);  // Agregar log del error
    echo json_encode(["status" => "error", "message" => "Error al procesar el mensaje: " . $stmt->error]);
}

$stmt->close();
$conn->close();

// Opcional: Guardar el log en un archivo para depuración
file_put_contents('debug_log.txt', print_r($debug_log, true) . "\n", FILE_APPEND);
