<?php
// Configuración de los encabezados
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

$webhook_token = "ABCDEFG1234";  // Token de verificación
$accessToken = "EAAVZAG5oL9G4BO3vZAhKcOTpfZAQJgNDzTNDArOp8VitYT8GUFqcYKIsZAO0pBkf0edoZC1DgfXICkIEP7xZCkPkj8nS1gfDqI4jNeEVDmseyba3l2os8EoYgf1Mdnl2MwaYhmrdfZBgUnItwT8nZBVvjinB7j8IAfZBx2LZA1WNZCqqsZBZC2cqDdObeiLqEsih9U3XOQwZDZD"; // Token de acceso a la API de WhatsApp Business
$debug_log = [];

// Datos de conexión a la base de datos
const HOST = '3.233.119.65';
const USER = "imporsuit_system";
const PASSWORD = "imporsuit_system";
const DB = "imporsuitpro_new";
const CHARSET = "utf8mb4";

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
$ruta_archivo = null;  // Inicializar ruta_archivo como nulo para otros tipos de mensajes
$respuesta_WEBHOOK_messages = $whatsapp_value['messages'][0];  // Ajuste para obtener el mensaje correctamente

// Función para descargar la imagen de perfil del contacto de WhatsApp usando su número
function descargarImagenPerfilContacto($phone_whatsapp_from, $accessToken)
{
    $directory = __DIR__ . "/../whatsapp/imagenes_perfil/";

    // Crear el directorio si no existe
    if (!is_dir($directory)) {
        mkdir($directory, 0755, true);
        file_put_contents('debug_log.txt', "Directorio creado: " . $directory . "\n", FILE_APPEND);
    }

    // Obtener la URL de la imagen de perfil del usuario usando su número de WhatsApp
    // Nota: Aquí asumimos que ya tienes un API endpoint para obtener la imagen de perfil usando el número
    $url = "https://graph.facebook.com/v12.0/$phone_whatsapp_from/picture";  // Ajusta según la API correcta

    // Iniciar cURL para descargar la imagen
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $accessToken",  // Incluir el token de acceso
        "User-Agent: Mozilla/5.0"
    ]);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $imageData = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // Verificar si la imagen fue descargada correctamente
    if ($http_code != 200 || strlen($imageData) == 0) {
        file_put_contents('debug_log.txt', "Error al descargar la imagen de perfil. HTTP Code: $http_code\n", FILE_APPEND);
        return null;
    }

    // Guardar la imagen en la carpeta 'imagenes_perfil'
    $fileName = $phone_whatsapp_from . ".jpg";
    $filePath = $directory . $fileName;

    if (file_put_contents($filePath, $imageData) === false) {
        file_put_contents('debug_log.txt', "Error al guardar la imagen de perfil en la ruta: $filePath\n", FILE_APPEND);
        return null;
    }

    file_put_contents('debug_log.txt', "Imagen de perfil guardada correctamente: " . $filePath . "\n", FILE_APPEND);

    return "public/whatsapp/imagenes_perfil/" . $fileName;
}

// Función para descargar audio de WhatsApp
function descargarAudioWhatsapp($mediaId, $accessToken)
{
    // Ruta completa donde quieres que se guarden los audios
    $directory = __DIR__ . "/../whatsapp/audios_recibidos/";

    // Verificar si el directorio existe, si no lo creamos
    if (!is_dir($directory)) {
        mkdir($directory, 0755, true);  // Crear el directorio si no existe
        file_put_contents('debug_log.txt', "Directorio creado: " . $directory . "\n", FILE_APPEND);
    }

    // Paso 1: Obtener la URL de descarga del archivo de audio desde la API de WhatsApp
    $url = "https://graph.facebook.com/v12.0/$mediaId";

    // Inicializar cURL para obtener la URL de descarga real
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $accessToken",
        "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36" // Simular un navegador real
    ]);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);  // Seguir redirecciones
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Deshabilitar verificación SSL para pruebas
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // Verificar si hubo errores al obtener la URL del archivo de audio
    if ($http_code != 200 || empty($response)) {
        file_put_contents('debug_log.txt', "Error al obtener la URL del archivo de audio. HTTP Code: $http_code\n", FILE_APPEND);
        return null;
    }

    // Guardar la respuesta para depuración
    file_put_contents('debug_log.txt', "Respuesta cruda de WhatsApp API: $response\n", FILE_APPEND);

    // Decodificar la respuesta JSON para obtener la URL real del archivo de audio
    $media = json_decode($response, true);
    if (!isset($media['url'])) {
        file_put_contents('debug_log.txt', "Error: No se pudo obtener la URL del archivo de audio\n", FILE_APPEND);
        return null;
    }

    $fileUrl = $media['url'];
    file_put_contents('debug_log.txt', "URL del archivo de audio: $fileUrl\n", FILE_APPEND);

    // Paso 2: Descargar el archivo de audio desde la URL obtenida
    $ch = curl_init($fileUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  // Obtener datos binarios
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $accessToken",
        "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36" // Simular un navegador real
    ]);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);  // Seguir redirecciones
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Deshabilitar verificación SSL para pruebas
    $audioData = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // Verificar si hubo errores en la descarga
    if ($http_code != 200 || $audioData === false || strlen($audioData) == 0) {
        file_put_contents('debug_log.txt', "Error al descargar el archivo de audio. HTTP Code: $http_code\n", FILE_APPEND);
        return null;
    }

    // Paso 3: Verificar que los datos descargados son de un archivo de audio válido
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime_type = finfo_buffer($finfo, $audioData);
    finfo_close($finfo);

    // Comprobar si el MIME type es de un archivo de audio
    $valid_audio_mime_types = ['audio/ogg', 'audio/mpeg', 'audio/mp4', 'audio/aac'];
    if (!in_array($mime_type, $valid_audio_mime_types)) {
        file_put_contents('debug_log.txt', "Error: Tipo de archivo descargado no es audio. MIME type: $mime_type\n", FILE_APPEND);
        return null;
    }

    // Generar el nombre de archivo con extensión según el tipo de archivo
    $extension = ($mime_type === 'audio/ogg') ? '.ogg' : '.mp3';  // Adaptar según el tipo MIME
    $fileName = $mediaId . $extension;
    $filePath = $directory . $fileName;

    // Paso 4: Guardar el archivo descargado en el servidor
    if (file_put_contents($filePath, $audioData) === false) {
        file_put_contents('debug_log.txt', "Error al guardar el archivo en la ruta: $filePath\n", FILE_APPEND);
        return null;
    }

    // Verificar el tamaño del archivo guardado
    $file_size = filesize($filePath);
    file_put_contents('debug_log.txt', "Archivo guardado correctamente: " . $filePath . " con tamaño: $file_size bytes\n", FILE_APPEND);

    // Devuelve la ruta desde `public/whatsapp/audios_recibidos/` para almacenar en la base de datos
    return "public/whatsapp/audios_recibidos/" . $fileName;
}

function descargarImagenWhatsapp($mediaId, $accessToken)
{
    $directory = __DIR__ . "/../whatsapp/imagenes_recibidas/";

    // Crear el directorio si no existe
    if (!is_dir($directory)) {
        mkdir($directory, 0755, true);
        file_put_contents('debug_log.txt', "Directorio creado: " . $directory . "\n", FILE_APPEND);
    }

    // Paso 1: Obtener la URL de descarga del archivo de imagen desde la API de WhatsApp
    $url = "https://graph.facebook.com/v12.0/$mediaId";

    // Iniciar cURL para obtener la URL de descarga real
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $accessToken",
        "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36" // Simular un navegador real
    ]);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);  // Seguir redirecciones
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Deshabilitar verificación SSL para pruebas
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // Verificar si hubo errores al obtener la URL de la imagen
    if ($http_code != 200 || empty($response)) {
        file_put_contents('debug_log.txt', "Error al obtener la URL del archivo de imagen. HTTP Code: $http_code\n", FILE_APPEND);
        return null;
    }

    // Decodificar la respuesta para obtener la URL real del archivo
    $mediaData = json_decode($response, true);
    if (!isset($mediaData['url'])) {
        file_put_contents('debug_log.txt', "Error: No se pudo obtener la URL de la imagen\n", FILE_APPEND);
        return null;
    }

    // Paso 2: Descargar el archivo de la URL obtenida
    $fileUrl = $mediaData['url'];
    $ch = curl_init($fileUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $accessToken",
        "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36" // Simular un navegador real
    ]);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Deshabilitar verificación SSL para pruebas
    $imageData = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // Verificar que la imagen se descargó correctamente
    if ($http_code != 200 || $imageData === false || strlen($imageData) == 0) {
        file_put_contents('debug_log.txt', "Error al descargar la imagen. HTTP Code: $http_code\n", FILE_APPEND);
        return null;
    }

    // Paso 3: Verificar que los datos descargados son de imagen (no un error de API)
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime_type = finfo_buffer($finfo, $imageData);
    finfo_close($finfo);

    // Comprobar si el MIME type es de una imagen
    if (!in_array($mime_type, ['image/jpeg', 'image/png', 'image/gif'])) {
        file_put_contents('debug_log.txt', "Error: Tipo de archivo descargado no es imagen. MIME type: $mime_type\n", FILE_APPEND);
        return null;
    }

    // Generar el nombre de archivo con extensión
    $fileName = $mediaId . ".jpg";  // Suponemos que es JPEG; puedes adaptar esto según el tipo de archivo
    $filePath = $directory . $fileName;

    // Paso 4: Guardar la imagen descargada
    if (file_put_contents($filePath, $imageData) === false) {
        file_put_contents('debug_log.txt', "Error al guardar la imagen en la ruta: $filePath\n", FILE_APPEND);
        return null;
    }

    // Verificar el tamaño del archivo guardado
    $file_size = filesize($filePath);
    file_put_contents('debug_log.txt', "Imagen guardada correctamente: " . $filePath . " con tamaño: $file_size bytes\n", FILE_APPEND);

    return "public/whatsapp/imagenes_recibidas/" . $fileName;
}

function descargarDocumentoWhatsapp($mediaId, $accessToken, $fileName = null)
{
    // Ruta completa donde quieres que se guarden los documentos
    $directory = __DIR__ . "/../whatsapp/documentos_recibidos/";

    // Verificar si el directorio existe, si no lo creamos
    if (!is_dir($directory)) {
        mkdir($directory, 0755, true);  // Crear el directorio si no existe
        file_put_contents('debug_log.txt', "Directorio creado: " . $directory . "\n", FILE_APPEND);
    }

    // Obtener la URL de descarga del archivo de documento desde la API de WhatsApp
    $url = "https://graph.facebook.com/v12.0/$mediaId";

    // Inicializar cURL para obtener la URL de descarga real
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $accessToken",  // Incluir el token de autorización
        "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36" // Simular un navegador real
    ]);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);  // Seguir redirecciones
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Deshabilitar verificación SSL si es necesario
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // Verificar si hubo errores en la respuesta de WhatsApp
    if ($http_code != 200 || empty($response)) {
        file_put_contents('debug_log.txt', "Error al obtener la URL del archivo de documento. HTTP Code: $http_code\n", FILE_APPEND);
        return null;
    }

    // Decodificar la respuesta para obtener la URL real del archivo
    $mediaData = json_decode($response, true);
    if (!isset($mediaData['url'])) {
        file_put_contents('debug_log.txt', "Error: No se pudo obtener la URL del documento\n", FILE_APPEND);
        return null;
    }

    // Paso 2: Descargar el archivo desde la URL obtenida
    $fileUrl = $mediaData['url'];
    $ch = curl_init($fileUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  // Obtener datos binarios
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $accessToken",  // Incluir el token de autorización
        "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36" // Simular un navegador real
    ]);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Deshabilitar verificación SSL para pruebas
    $fileData = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // Verificar que el archivo se descargó correctamente
    if ($http_code != 200 || $fileData === false || strlen($fileData) == 0) {
        file_put_contents('debug_log.txt', "Error al descargar el documento. HTTP Code: $http_code\n", FILE_APPEND);
        return null;
    }

    // Paso 3: Obtener la extensión del archivo desde la URL de descarga (por ejemplo, .pdf, .docx, etc.)
    $fileExtension = pathinfo(parse_url($fileUrl, PHP_URL_PATH), PATHINFO_EXTENSION);

    // Si no se pasó un nombre de archivo, usar el ID del media como nombre
    if ($fileName === null) {
        $fileName = $mediaId . "." . $fileExtension;
    }

    // Paso 4: Añadir la fecha y hora actual al nombre del archivo
    $fechaHoraActual = date("Ymd_His");  // Formato: YYYYMMDD_HHMMSS

    // El formato final será: "fecha_hora_nombre.extensión"
    $nombre_principal_archivo = $fileName;
    $fileName = $fechaHoraActual . "_" . $fileName;

    // Guardar el archivo con su extensión original
    $filePath = $directory . $fileName;

    // Paso 5: Guardar el archivo en el servidor
    if (file_put_contents($filePath, $fileData) === false) {
        file_put_contents('debug_log.txt', "Error al guardar el archivo en la ruta: $filePath\n", FILE_APPEND);
        return null;
    }

    // Verificar el tamaño del archivo guardado
    $file_size = filesize($filePath);
    file_put_contents('debug_log.txt', "Documento guardado correctamente: " . $filePath . " con tamaño: $file_size bytes\n", FILE_APPEND);

    // Paso 6: Retornar los datos como un JSON
    $result = [
        "nombre" => $nombre_principal_archivo,               // Nombre del archivo
        "size" => $file_size,                // Tamaño del archivo
        "ruta" => "public/whatsapp/documentos_recibidos/" . $fileName  // Ruta donde se guardó
    ];

    // Retornar el resultado en formato JSON
    return json_encode($result);
}

// Procesar el mensaje basado en el tipo recibido
switch ($tipo_mensaje) {
    case 'text':
        $texto_mensaje = $respuesta_WEBHOOK_messages['text']['body'];
        break;

    case 'image':
        $imageId = $respuesta_WEBHOOK_messages['image']['id'];
        $ruta_archivo = descargarImagenWhatsapp($imageId, $accessToken);  // Descargar la imagen y obtener la ruta

        // Guardamos solo el pie de foto (si existe) en texto_mensaje
        $texto_mensaje = $respuesta_WEBHOOK_messages['image']['caption'] ?? '';  // Guardar solo el pie de foto si existe
        break;

    case 'video':
        $texto_mensaje = "Video recibido con ID: " . $respuesta_WEBHOOK_messages['video']['id'];
        if (isset($respuesta_WEBHOOK_messages['video']['caption'])) {
            $texto_mensaje .= ", con pie de foto: " . $respuesta_WEBHOOK_messages['video']['caption'];
        }
        break;

    case 'audio':
        $audioId = $respuesta_WEBHOOK_messages['audio']['id'];
        $texto_mensaje = "Audio recibido con ID: " . $audioId;
        $ruta_archivo = descargarAudioWhatsapp($audioId, $accessToken);  // Descargar el audio y obtener la ruta
        if ($ruta_archivo) {
            $texto_mensaje .= ". Archivo guardado en: " . $ruta_archivo;
        } else {
            $texto_mensaje .= ". Error al descargar el archivo.";
        }
        break;

    case 'document':
        // Obtener el ID del documento desde la respuesta del webhook
        $documentId = $respuesta_WEBHOOK_messages['document']['id'];

        // Descargar el documento utilizando la función descargarDocumentoWhatsapp
        $rutaDocumento = descargarDocumentoWhatsapp($documentId, $accessToken, $respuesta_WEBHOOK_messages['document']['filename'] ?? null);

        // Guardamos solo el pie de documento si existe (similar a cómo se hace con las imágenes)
        $texto_mensaje = $respuesta_WEBHOOK_messages['document']['caption'] ?? '';  // Guardar solo el pie de documento si existe

        // Verificar si la descarga fue exitosa
        if ($rutaDocumento !== null) {
            $ruta_archivo = $rutaDocumento;  // Guardar la ruta para almacenarla en la base de datos
            /* $texto_mensaje .= "\nDocumento guardado en: " . $rutaDocumento; */
        } else {
            $texto_mensaje .= "\nError al descargar el documento.";
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

// Verificar si el cliente ya existe en la tabla clientes_chat_center por celular_cliente
$check_client_stmt = $conn->prepare("SELECT id, imagePath FROM clientes_chat_center WHERE celular_cliente = ?");
$check_client_stmt->bind_param('s', $phone_whatsapp_from);  // Buscamos por el celular_cliente
$check_client_stmt->execute();
$check_client_stmt->store_result();

$id_plataforma = 1190;  // Ajustar según sea necesario
$imagePath = null;

if ($check_client_stmt->num_rows == 0) {
    // El cliente no existe, creamos uno nuevo
    $ruta_imagen_perfil = descargarImagenPerfilContacto($phone_whatsapp_from, $accessToken);  // Intentamos descargar la imagen del perfil del número

    $insert_client_stmt = $conn->prepare("
        INSERT INTO clientes_chat_center (id_plataforma, uid_cliente, nombre_cliente, apellido_cliente, celular_cliente, imagePath, created_at, updated_at) 
        VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())
    ");
    $insert_client_stmt->bind_param('isssss', $id_plataforma, $business_phone_id, $nombre_cliente, $apellido_cliente, $phone_whatsapp_from, $ruta_imagen_perfil);
    $insert_client_stmt->execute();
    $id_cliente = $insert_client_stmt->insert_id;  // Obtener el ID autoincrementado del cliente recién creado
    $insert_client_stmt->close();
} else {
    // El cliente existe, obtenemos su ID y su ruta de imagen actual
    $check_client_stmt->bind_result($id_cliente, $imagePath);
    $check_client_stmt->fetch();

    // Si el cliente existe pero no tiene una imagen de perfil, la descargamos y actualizamos
    if (is_null($imagePath)) {
        $ruta_imagen_perfil = descargarImagenPerfilContacto($phone_whatsapp_from, $accessToken);  // Intentamos descargar la imagen del perfil

        if ($ruta_imagen_perfil) {
            $update_image_stmt = $conn->prepare("UPDATE clientes_chat_center SET imagePath = ?, updated_at = NOW() WHERE id = ?");
            $update_image_stmt->bind_param('si', $ruta_imagen_perfil, $id_cliente);
            $update_image_stmt->execute();
            $update_image_stmt->close();
        }
    }
}

$check_client_stmt->close();

// Ahora puedes proceder a insertar el mensaje en la tabla mensajes_clientes
$stmt = $conn->prepare("
    INSERT INTO mensajes_clientes (id_plataforma, id_cliente, mid_mensaje, tipo_mensaje, texto_mensaje, ruta_archivo, rol_mensaje, celular_recibe, created_at, updated_at) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
");

$mid_mensaje = $business_phone_id;  // Usamos el ID del mensaje de WhatsApp
$rol_mensaje = 0;  // Valor por defecto para rol_mensaje, ya que es bigint

$stmt->bind_param('iissssis', $id_plataforma, $id_cliente, $mid_mensaje, $tipo_mensaje, $texto_mensaje, $ruta_archivo, $rol_mensaje, $id_cliente);

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
