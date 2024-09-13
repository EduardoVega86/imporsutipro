<?php
require 'db.php';

// Configuración de los encabezados
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Array para el log de depuración
$debug_log = [];
$webhook_token = "ABCDEFG1234";  // Token de verificación

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

if (!$data_msg_whatsapp) {
    echo json_encode(["status" => "error", "message" => "Datos inválidos."]);
    exit;
}

// Guardar los datos recibidos en el log de depuración
$debug_log['data_msg_whatsapp'] = $data_msg_whatsapp;

// Extraer los datos generales del mensaje
$business_phone_id = $data_msg_whatsapp['entry'][0]['id'] ?? '';
$phone_whatsapp_from = $data_msg_whatsapp['entry'][0]['changes'][0]['value']['messages'][0]['from'] ?? '';
$from_chatbot_phone = $data_msg_whatsapp['entry'][0]['changes'][0]['value']['metadata']['display_phone_number'] ?? '';
$name_whatsapp_from = $data_msg_whatsapp['entry'][0]['changes'][0]['value']['contacts'][0]['profile']['name'] ?? '';
$tipo_mensaje = $data_msg_whatsapp['entry'][0]['changes'][0]['value']['messages'][0]['type'] ?? '';

// Procesar diferentes tipos de mensajes de WhatsApp
$texto_mensaje = "";
$respuesta_WEBHOOK_messages = $data_msg_whatsapp['entry'][0]['changes'][0]['value']['messages'][0];

// Procesamiento del mensaje basado en el tipo
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
            $contact_first_name_whatsapp = $contact['name']['first_name'] ?? '';
            $contact_middle_name_whatsapp = $contact['name']['middle_name'] ?? '';
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
        $texto_mensaje = "Tipo de mensaje no reconocido";
}

// Registrar en el log de depuración
$debug_log['texto_mensaje'] = $texto_mensaje;

// Guardar los datos del mensaje en la base de datos
$stmt = $conn->prepare("INSERT INTO mensajes_whatsapp (business_phone_id, phone_whatsapp_from, name_whatsapp_from, tipo_mensaje, body) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param('sssss', $business_phone_id, $phone_whatsapp_from, $name_whatsapp_from, $tipo_mensaje, $texto_mensaje);

if ($stmt->execute()) {
    $debug_log['insert'] = "Mensaje guardado correctamente.";
    echo json_encode(["status" => "success", "message" => "Mensaje procesado correctamente."]);
} else {
    $debug_log['insert'] = "Error al guardar el mensaje.";
    echo json_encode(["status" => "error", "message" => "Error al procesar el mensaje."]);
}

$stmt->close();
