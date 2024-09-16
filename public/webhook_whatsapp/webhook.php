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
$data_msg_whatsapp = json_decode($input, true);

// Validar que los datos recibidos no están vacíos
if (empty($data_msg_whatsapp)) {
    echo json_encode(["status" => "error", "message" => "Datos inválidos o vacíos."]);
    exit;
}

// Guardar los datos recibidos en el log de depuración
$debug_log['data_msg_whatsapp'] = $data_msg_whatsapp;

// Extraer los datos generales del mensaje
$business_phone_id = $data_msg_whatsapp['entry'][0]['id'] ?? '';
$phone_whatsapp_from = $data_msg_whatsapp['entry'][0]['changes'][0]['value']['messages'][0]['from'] ?? '';
$name_whatsapp_from = $data_msg_whatsapp['entry'][0]['changes'][0]['value']['contacts'][0]['profile']['name'] ?? '';
$tipo_mensaje = $data_msg_whatsapp['entry'][0]['changes'][0]['value']['messages'][0]['type'] ?? '';

// Verificación si los datos claves están presentes
if (empty($phone_whatsapp_from) || empty($business_phone_id)) {
    echo json_encode(["status" => "error", "message" => "Datos del mensaje incompletos."]);
    exit;
}

// Procesar diferentes tipos de mensajes de WhatsApp
$texto_mensaje = "";
$respuesta_WEBHOOK_messages = $data_msg_whatsapp['entry'][0]['changes'][0]['value']['messages'][0];

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

// Guardar los datos del mensaje en la base de datos
$stmt = $conn->prepare("
    INSERT INTO mensajes_clientes (id_plataforma, id_cliente, mid_mensaje, tipo_mensaje, texto_mensaje, rol_mensaje, created_at, updated_at) 
    VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())
");

$id_plataforma = 1; // Ejemplo de un valor fijo para la plataforma, ajustar según necesidad
$id_cliente = $phone_whatsapp_from; // Usamos el número de teléfono como el ID del cliente
$mid_mensaje = $business_phone_id; // Usamos el ID del mensaje de WhatsApp
$rol_mensaje = 0; // Este campo no está en uso en este caso, puedes asignar un valor si lo necesitas
$texto_corregido_mensaje = ''; // Campo vacío para corrección de texto

$stmt->bind_param('issssi', $id_plataforma, $id_cliente, $mid_mensaje, $tipo_mensaje, $texto_mensaje, $rol_mensaje);

// Ejecutar la consulta e insertar los datos en la base de datos
if ($stmt->execute()) {
    $debug_log['insert'] = "Mensaje guardado correctamente.";
    echo json_encode(["status" => "success", "message" => "Mensaje procesado correctamente."]);
} else {
    $error = $stmt->error;  // Obtener el error específico de la base de datos
    $debug_log['insert'] = "Error al guardar el mensaje en la base de datos: " . $error;
    echo json_encode(["status" => "error", "message" => "Error al procesar el mensaje: " . $error]);
}

$stmt->close();
$conn->close();

// Opcional: Guardar el log en un archivo para depuración
file_put_contents('debug_log.txt', print_r($debug_log, true));
