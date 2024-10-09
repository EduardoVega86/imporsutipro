<?php
// Configuración de los encabezados
/* header("Access-Control-Allow-Origin: *"); */
header("Content-Type: application/json; charset=UTF-8");

$webhook_token = $_GET['webhook'];  // Token de verificación
$id_configuracion = $_GET['id'];
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

// Preparar la consulta
$check_cofiguraciones_stmt = $conn->prepare("SELECT id_plataforma, token, id_whatsapp FROM configuraciones WHERE id = ?");
$check_cofiguraciones_stmt->bind_param('s', $id_configuracion);  // Usamos id_configuracion como parámetro
$check_cofiguraciones_stmt->execute();
$check_cofiguraciones_stmt->store_result();  // Almacenar el resultado antes de bind_result

// Verificar si la consulta devolvió alguna fila
if ($check_cofiguraciones_stmt->num_rows > 0) {
    // Enlazar los resultados a variables
    $check_cofiguraciones_stmt->bind_result($id_plataforma, $accessToken, $waba_id);
    $check_cofiguraciones_stmt->fetch();  // Obtener los valores vinculados
} else {
    // Si no hay resultados, maneja el error apropiadamente
    file_put_contents('debug_log.txt', "Error: No se encontró configuración con id: " . $id_configuracion . "\n", FILE_APPEND);
    echo json_encode(["status" => "error", "message" => "No se encontró la configuración para id: " . $id_configuracion]);
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

function descargarVideoWhatsapp($mediaId, $accessToken)
{
    // Ruta completa donde quieres que se guarden los videos
    $directory = __DIR__ . "/../whatsapp/videos_recibidos/";

    // Verificar si el directorio existe, si no lo creamos
    if (!is_dir($directory)) {
        mkdir($directory, 0755, true);  // Crear el directorio si no existe
        file_put_contents('debug_log.txt', "Directorio creado: " . $directory . "\n", FILE_APPEND);
    }

    // Paso 1: Obtener la URL de descarga del archivo de video desde la API de WhatsApp
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

    // Verificar si hubo errores al obtener la URL del archivo de video
    if ($http_code != 200 || empty($response)) {
        file_put_contents('debug_log.txt', "Error al obtener la URL del archivo de video. HTTP Code: $http_code\n", FILE_APPEND);
        return null;
    }

    // Decodificar la respuesta JSON para obtener la URL real del archivo
    $media = json_decode($response, true);
    if (!isset($media['url'])) {
        file_put_contents('debug_log.txt', "Error: No se pudo obtener la URL del archivo de video\n", FILE_APPEND);
        return null;
    }

    $fileUrl = $media['url'];
    file_put_contents('debug_log.txt', "URL del archivo de video: $fileUrl\n", FILE_APPEND);

    // Paso 2: Descargar el archivo de video desde la URL obtenida
    $ch = curl_init($fileUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  // Obtener datos binarios
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $accessToken",
        "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36" // Simular un navegador real
    ]);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Deshabilitar verificación SSL para pruebas
    $videoData = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // Verificar si hubo errores en la descarga
    if ($http_code != 200 || $videoData === false || strlen($videoData) == 0) {
        file_put_contents('debug_log.txt', "Error al descargar el archivo de video. HTTP Code: $http_code\n", FILE_APPEND);
        return null;
    }

    // Paso 3: Verificar que los datos descargados son de un archivo de video válido
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime_type = finfo_buffer($finfo, $videoData);
    finfo_close($finfo);

    // Comprobar si el MIME type es de un archivo de video
    $valid_video_mime_types = ['video/mp4', 'video/avi', 'video/mov'];
    if (!in_array($mime_type, $valid_video_mime_types)) {
        file_put_contents('debug_log.txt', "Error: Tipo de archivo descargado no es video. MIME type: $mime_type\n", FILE_APPEND);
        return null;
    }

    // Generar el nombre de archivo con extensión según el tipo de archivo
    $extension = ($mime_type === 'video/mp4') ? '.mp4' : '.avi';  // Adaptar según el tipo MIME
    $fileName = $mediaId . $extension;
    $filePath = $directory . $fileName;

    // Paso 4: Guardar el archivo descargado en el servidor
    if (file_put_contents($filePath, $videoData) === false) {
        file_put_contents('debug_log.txt', "Error al guardar el archivo en la ruta: $filePath\n", FILE_APPEND);
        return null;
    }

    // Verificar el tamaño del archivo guardado
    $file_size = filesize($filePath);
    file_put_contents('debug_log.txt', "Archivo guardado correctamente: " . $filePath . " con tamaño: $file_size bytes\n", FILE_APPEND);

    // Devuelve la ruta desde `public/whatsapp/videos_recibidos/` para almacenar en la base de datos
    return "public/whatsapp/videos_recibidos/" . $fileName;
}

function validar_automatizador($conn, $payload, $id_configuracion)
{
    $json_output = "";
    $json_bloques = "";

    // Consulta para obtener los JSONs de la base de datos
    $check_automatizadores_stmt = $conn->prepare("SELECT json_output, json_bloques FROM `automatizadores` INNER JOIN `condiciones` ON automatizadores.id = condiciones.id_automatizador 
    WHERE automatizadores.id_configuracion = ? AND condiciones.texto = ?");
    $check_automatizadores_stmt->bind_param('ss', $id_configuracion, $payload);
    $check_automatizadores_stmt->execute();
    $check_automatizadores_stmt->store_result();
    $check_automatizadores_stmt->bind_result($json_output, $json_bloques);
    $check_automatizadores_stmt->fetch();
    $check_automatizadores_stmt->close();

    // Decodificar los JSONs
    $json_output = json_decode($json_output, true);
    $json_bloques = json_decode($json_bloques, true);

    // Variable para guardar el ID si se encuentra coincidencia
    $found_block_id = null;
    $id_whatsapp_message_template = null;
    $mensaje = null;

    // Recorrer los bloques en json_output para encontrar los bloques con blockelemtype = 10
    if (isset($json_output['blocks'])) {
        foreach ($json_output['blocks'] as $block) {
            // Verificar si el bloque tiene blockelemtype igual a 10
            foreach ($block['data'] as $data_item) {
                if ($data_item['name'] == 'blockelemtype' && $data_item['value'] == '10') {
                    // Obtener el ID del bloque que tiene blockelemtype = 10
                    $block_id = $block['id'];

                    // Ahora buscar este ID en json_bloques
                    foreach ($json_bloques as $bloque_info) {
                        if ($bloque_info['id_block'] == (string)$block_id) {
                            // Verificar si "texto_recibir" es igual al $payload
                            if (isset($bloque_info['texto_recibir']) && $bloque_info['texto_recibir'] == $payload) {
                                // Guardar el id_block y detener la búsqueda
                                $found_block_id = $block_id;
                                break 2; // Salir de ambos bucles
                            }
                        }
                    }
                }
            }
        }
    }

    // Si encontramos una coincidencia, podemos hacer algo con ese ID
    if ($found_block_id !== null) {
        // Buscar los bloques que tengan como padre el bloque encontrado
        foreach ($json_output['blocks'] as $block) {
            // Verificar si este bloque tiene como padre el found_block_id
            if ($block['parent'] == (string)$found_block_id) {
                // Ahora buscar este ID en json_bloques
                foreach ($json_bloques as $bloque_info) {
                    if ($bloque_info['id_block'] == (string)$block['id']) {
                        $id_whatsapp_message_template = $bloque_info['id_whatsapp_message_template'];
                        $mensaje = $bloque_info['mensaje'];
                    }
                }
            }
        }
    } else {
        file_put_contents('debug_log.txt', "No se encontró coincidencia en validar_automatizador.\n", FILE_APPEND);
    }

    // Devolver los resultados como un array
    return [
        'id_whatsapp_message_template' => $id_whatsapp_message_template,
        'mensaje' => $mensaje
    ];
}

function obtenerNombreTemplatePorID($accessToken, $waba_id, $id_whatsapp_message_template)
{
    // URL para obtener la lista de templates desde la API de WhatsApp Business
    $url = 'https://graph.facebook.com/v20.0/' . $waba_id . '/message_templates';
    $params = array(
        'access_token' => $accessToken
    );
    $url .= '?' . http_build_query($params);

    // Inicializar cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    // Ejecutar la solicitud cURL
    $response = curl_exec($ch);

    // Manejar errores de cURL
    if (curl_errno($ch)) {
        $error_msg = curl_error($ch);
        curl_close($ch);
        file_put_contents('debug_log.txt', "Error al obtener los templates: $error_msg\n", FILE_APPEND);
        return null;
    }

    // Cerrar cURL
    curl_close($ch);

    // Decodificar respuesta JSON
    $responseArray = json_decode($response, true);

    // Verificar si la respuesta contiene datos
    if (isset($responseArray['data'])) {
        $facebook_templates = $responseArray['data'];

        // Buscar el nombre del template por el ID
        foreach ($facebook_templates as $template) {
            if ($template['id'] == $id_whatsapp_message_template) {
                return $template['name'];  // Retornar el nombre del template
            }
        }
    }

    file_put_contents('debug_log.txt', "No se encontró un template con el ID $id_whatsapp_message_template\n", FILE_APPEND);
    return null;
}

function enviarMensajeTemplateWhatsApp($accessToken, $business_phone_id, $phone_whatsapp_from, $template_name, $mensaje = null)
{
    // Paso 1: Configurar el envío del mensaje de WhatsApp usando el nombre del template
    $url = "https://graph.facebook.com/v20.0/$business_phone_id/messages";

    // Verifica si el template permite parámetros en el cuerpo del mensaje
    $data = [
        "messaging_product" => "whatsapp",
        "to" => $phone_whatsapp_from,
        "type" => "template",
        "template" => [
            "name" => $template_name,  // Usar el nombre del template
            "language" => ["code" => "en_US"],  // Cambiar a 'en_US' o 'es_MX'
        ]
    ];

    // Si el template espera parámetros, los añadimos
    if ($mensaje !== null) {
        $data['template']['components'] = [
            [
                "type" => "body",
                "parameters" => [
                    ["type" => "text", "text" => $mensaje]
                ]
            ]
        ];
    }

    // Inicializamos cURL para hacer la solicitud HTTP POST
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $accessToken",
        "Content-Type: application/json"
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    // Ejecutar la solicitud
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    // Verificar si la solicitud fue exitosa
    if ($http_code === 200) {
        file_put_contents('debug_log.txt', "Mensaje template enviado correctamente a $phone_whatsapp_from usando el template $template_name.\n", FILE_APPEND);
    } else {
        file_put_contents('debug_log.txt', "Error al enviar el mensaje template. HTTP Code: $http_code\nRespuesta: $response\n", FILE_APPEND);
    }

    curl_close($ch);
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
        $videoId = $respuesta_WEBHOOK_messages['video']['id'];
        $ruta_archivo = descargarVideoWhatsapp($videoId, $accessToken);  // Descargar el video y obtener la ruta

        $texto_mensaje = $respuesta_WEBHOOK_messages['video']['caption'] ?? '';
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

    case 'button':
        $payload = $respuesta_WEBHOOK_messages['button']['payload'] ?? '';

        // Llamar a validar_automatizador para obtener los datos
        $resultado_automatizador = validar_automatizador($conn, $payload, $id_configuracion);

        // Extraer los valores devueltos
        $id_whatsapp_message_template = $resultado_automatizador['id_whatsapp_message_template'] ?? null;
        $mensaje = $resultado_automatizador['mensaje'] ?? null;

        // Verifica si los datos de id_whatsapp_message_template y mensaje están presentes
        if (!empty($id_whatsapp_message_template) && !empty($mensaje)) {
            // Obtener el nombre del template usando el ID
            $template_name = obtenerNombreTemplatePorID($accessToken, $waba_id, $id_whatsapp_message_template);

            if (!empty($template_name)) {
                // Llamar a la función para enviar el mensaje template a WhatsApp
                enviarMensajeTemplateWhatsApp($accessToken, $business_phone_id, $phone_whatsapp_from, $template_name, $mensaje);

                file_put_contents('debug_log.txt', "Mensaje enviado a $phone_whatsapp_from con el template $template_name\n", FILE_APPEND);
            } else {
                file_put_contents('debug_log.txt', "No se pudo obtener el nombre del template con el ID $id_whatsapp_message_template\n", FILE_APPEND);
            }
        } else {
            file_put_contents('debug_log.txt', "No se encontraron los datos necesarios para enviar el mensaje template.\n", FILE_APPEND);
        }

        $texto_mensaje = "Respuesta de botón con payload: " . $payload;
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
$check_client_stmt = $conn->prepare("SELECT id FROM clientes_chat_center WHERE celular_cliente = ?");
$check_client_stmt->bind_param('s', $phone_whatsapp_from);  // Buscamos por el celular_cliente
$check_client_stmt->execute();
$check_client_stmt->store_result();


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
