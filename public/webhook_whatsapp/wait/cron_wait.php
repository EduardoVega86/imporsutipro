<?php
// Configuración de conexión a la base de datos
const HOST = '3.233.119.65';
const USER = "imporsuit_system";
const PASSWORD = "imporsuit_system";
const DB = "imporsuitpro_new";
const CHARSET = "utf8mb4";

// Ruta para el archivo de bloqueo
$lockFile = __DIR__ . '/cron_wait.lock';

// Ruta del archivo de log
$logFile = __DIR__ . '/error_log_wait.txt';

// Función para registrar errores en el archivo de log
function logError($message)
{
    global $logFile;
    $timestamp = date("Y-m-d H:i:s");
    file_put_contents($logFile, "[$timestamp] $message\n", FILE_APPEND);
}

// Función para conectar a la base de datos
function connectDatabase()
{
    $conn = new mysqli(HOST, USER, PASSWORD, DB);
    if ($conn->connect_error) {
        logError("Error de conexión a la base de datos: " . $conn->connect_error);
        die("Error de conexión a la base de datos.");
    }
    $conn->set_charset(CHARSET);
    return $conn;
}

// Función principal: validarTiempo
function validarTiempo($conn)
{
    try {
        // Aquí puedes agregar tu lógica específica
        $sql = "SELECT * FROM mensajes_espera";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $estado = $row["estado"];
                $posicion_json_output_wait = $row["posicion_json_output_wait"];
                $id_automatizador = (int)$row["id_automatizador"];
                $id_cliente_chat_center = $row["id_cliente_chat_center"];
                $id_mensajes_clientes = $row["id_mensajes_clientes"];
                $fecha_envio = (string)$row["fecha_envio"];
                $id_mensaje_espera = (int)$row["id"];

                // Consulta para obtener los JSONs de la base de datos
                $query = "SELECT * FROM `automatizadores` WHERE id = ?;";
                $stmt = $conn->prepare($query);
                $stmt->bind_param('i', $id_automatizador);
                $stmt->execute();
                $result = $stmt->get_result();

                // Variables de resultados
                $resultados = [];

                // Procesar cada fila de resultados
                while ($row = $result->fetch_assoc()) {
                    $json_output = json_decode($row['json_output'], true);
                    $json_bloques = json_decode($row['json_bloques'], true);

                    $id_configuracion = $row['id_configuracion'];

                    /* Variables de control */
                    $id_template_whatsapp = null;

                    $tiempo_wait = null;

                    // Buscar este ID en json_bloques
                    foreach ($json_bloques as $bloque_info) {
                        if ($bloque_info['id_block'] == (string)$posicion_json_output_wait) {
                            // Verificar si 'wait[]' está definido
                            if (isset($bloque_info['wait[]'])) {
                                // Asegurarse de que $tiempo_wait siempre sea un array (incluso con un solo valor)
                                $tiempo_wait = is_array($bloque_info['wait[]'])
                                    ? $bloque_info['wait[]']
                                    : [$bloque_info['wait[]']];
                                break; // Salir del bucle
                            }
                        }
                    }

                    /* condicion de decision */
                    if ($estado == 1) {
                        $condicion = "1";
                        enviar_template($conn, $json_output, $json_bloques, $posicion_json_output_wait, $condicion, $id_automatizador, $id_configuracion, $id_cliente_chat_center, $id_mensajes_clientes);

                        eliminar_mensaje_espera($conn, $id_mensaje_espera);
                    } else {
                        // Obtener la cantidad de horas a verificar según la clave
                        $horas_a_verificar = [0 => 1, 1 => 2, 2 => 3, 3 => 5, 4 => 12, 5 => 24];

                        // Verificar que $tiempo_wait tenga un valor válido
                        if (!empty($tiempo_wait) && isset($tiempo_wait[0])) {
                            $clave = (int)$tiempo_wait[0]; // Convertir clave a entero por seguridad

                            // Validar si la clave existe en el array de horas a verificar
                            if (isset($horas_a_verificar[$clave])) {
                                $horas_objetivo = $horas_a_verificar[$clave];

                                // Configurar la zona horaria de Ecuador
                                date_default_timezone_set('America/Guayaquil');

                                // Obtener la fecha y hora actual
                                $fecha_actual = new DateTime();

                                try {
                                    // Crear el objeto DateTime para la fecha de envío
                                    $fecha_envio_obj = new DateTime($fecha_envio, new DateTimeZone('America/Guayaquil'));

                                    // Calcular la diferencia en horas
                                    $diferencia = $fecha_envio_obj->diff($fecha_actual);
                                    $diferencia_horas = ($diferencia->days * 24) + $diferencia->h + ($diferencia->i / 60);

                                    // Registrar los datos en el log para depuración
                                    /* logError("fecha_envio: " . $fecha_envio);
                                    logError("fecha_envio_obj: " . $fecha_envio_obj->format('Y-m-d H:i:s'));
                                    logError("fecha_actual: " . $fecha_actual->format('Y-m-d H:i:s'));
                                    logError("diferencia_horas: " . $diferencia_horas);
                                    logError("horas_objetivo: " . $horas_objetivo); */

                                    // Verificar si el tiempo objetivo ha sido cumplido
                                    if ($diferencia_horas >= $horas_objetivo) {
                                        $condicion = "0";
                                        enviar_template($conn, $json_output, $json_bloques, $posicion_json_output_wait, $condicion, $id_automatizador, $id_configuracion, $id_cliente_chat_center, $id_mensajes_clientes);

                                        eliminar_mensaje_espera($conn, $id_mensaje_espera);
                                    } else {
                                        logError("Aún no se ha cumplido el tiempo de espera: $horas_objetivo horas. Han pasado $diferencia_horas horas.");
                                    }
                                } catch (Exception $e) {
                                    // Manejar errores al procesar la fecha
                                    logError("Error al procesar las fechas: " . $e->getMessage());
                                }
                            } else {
                                logError("La clave $clave no es válida en horas_a_verificar.");
                            }
                        } else {
                            logError("El array tiempo_wait está vacío o no contiene un valor válido.");
                        }
                    }
                }
            }
        } else {
            logError("No hay registros pendientes.");
        }
    } catch (Exception $e) {
        logError("Error en validarTiempo: " . $e->getMessage());
    }
}

function eliminar_mensaje_espera($conn, $id_mensaje_espera)
{
    // Consulta para eliminar el mensaje de espera
    $query = "DELETE FROM `mensajes_espera` WHERE id = ?";

    // Preparar la consulta
    $stmt = $conn->prepare($query);

    if ($stmt) {
        // Vincular el parámetro
        $stmt->bind_param('i', $id_mensaje_espera);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Verificar cuántas filas fueron afectadas
            if ($stmt->affected_rows > 0) {
                logError("El mensaje de espera con ID " . $id_mensaje_espera . " fue eliminado correctamente.");
            } else {
                logError("No se encontró ningún mensaje de espera con el ID proporcionado.");
            }
        } else {
            logError("Error al ejecutar la consulta: " . $stmt->error);
        }

        // Cerrar el statement
        $stmt->close();
    } else {
        logError("Error al preparar la consulta: " . $conn->error);
    }
}

function enviar_template($conn, $json_output, $json_bloques, $posicion_json_output_wait, $condicion, $id_automatizador, $id_configuracion, $id_cliente_chat_center, $id_mensajes_clientes)
{
    // Validar los bloques dentro de json_output
    if (isset($json_output['blocks'])) {

        foreach ($json_output['blocks'] as $block) {
            $parent = $block['parent'];

            if ($parent == $posicion_json_output_wait) {
                $block_id_condiciones = $block['id'];

                // Ahora buscar este ID en json_bloques
                foreach ($json_bloques as $bloque_info) {
                    if ($bloque_info['id_block'] == (string)$block_id_condiciones) {

                        if (isset($bloque_info['sino_condicion[]']) && $bloque_info['sino_condicion[]'] == $condicion) {
                            $block_id_condicion = $block_id_condiciones;
                            break 2; // Salir de ambos bucles
                        }
                    }
                }
            }
        }

        if (isset($block_id_condiciones)) {
            /* buscar el template despues de la condicion que se cumple */
            foreach ($json_output['blocks'] as $block) {
                $parent = $block['parent'];

                if ($parent == $block_id_condicion) {
                    $block_id_template_whatsapp = $block['id'];

                    // Ahora buscar este ID en json_bloques
                    foreach ($json_bloques as $bloque_info) {
                        if ($bloque_info['id_block'] == (string)$block_id_template_whatsapp) {

                            // Verificar si existe template de WhatsApp y tomar el código del template
                            if (isset($bloque_info['id_whatsapp_message_template'])) {

                                $id_plataforma = null;
                                $accessToken = null;
                                $waba_id = null;
                                $id_whatsapp_message_template = $bloque_info['id_whatsapp_message_template'];
                                $mensaje_template = $bloque_info['mensaje'];

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

                                    // Obtener el nombre y el idioma del template
                                    $template_info = obtenerTemplatePorID($accessToken, $waba_id, $id_whatsapp_message_template);

                                    if (!empty($template_info)) {
                                        $template_name = $template_info['name']; // Nombre del template
                                        $template_language = $template_info['language']; // Idioma del template

                                        // Llamar a la función para enviar el mensaje template a WhatsApp
                                        $mensaje = null;
                                        $business_phone_id = null;
                                        $phone_whatsapp_from = null;

                                        /* consulta mensajes_cliente */
                                        $check_cofiguraciones_stmt = $conn->prepare("SELECT uid_cliente, celular_cliente FROM clientes_chat_center WHERE id = ?");
                                        $check_cofiguraciones_stmt->bind_param('s', $id_cliente_chat_center);
                                        $check_cofiguraciones_stmt->execute();
                                        $check_cofiguraciones_stmt->store_result();
                                        // Enlazar los resultados a variables
                                        $check_cofiguraciones_stmt->bind_result($business_phone_id, $phone_whatsapp_from);
                                        $check_cofiguraciones_stmt->fetch();  // Obtener los valores vinculados
                                        /* fin consulta mensajes_cliente */


                                        /* consultar ruta_archivo de mensaje_cliente anterior */
                                        $ruta_archivo_ultimo_tempalte = null;
                                        $check_rutaArchivo_stmt = $conn->prepare("SELECT ruta_archivo FROM mensajes_clientes WHERE id = ?");
                                        $check_rutaArchivo_stmt->bind_param('s', $id_mensajes_clientes);
                                        $check_rutaArchivo_stmt->execute();
                                        $check_rutaArchivo_stmt->store_result();
                                        // Enlazar los resultados a variables
                                        $check_rutaArchivo_stmt->bind_result($ruta_archivo_ultimo_tempalte);
                                        $check_rutaArchivo_stmt->fetch();  // Obtener los valores vinculados
                                        /* fin consultar ruta_archivo de mensaje_cliente anterior */

                                        enviarMensajeTemplateWhatsApp($accessToken, $business_phone_id, $phone_whatsapp_from, $template_name, $template_language, $mensaje, $conn, $id_plataforma, $id_configuracion, $mensaje_template, $ruta_archivo_ultimo_tempalte);
                                    } else {
                                        logError("No se pudo obtener el nombre o idioma del template con el ID " . $id_whatsapp_message_template);
                                    }
                                } else {
                                    // Si no hay resultados, maneja el error apropiadamente
                                    logError("Error: No se encontró configuración con id: " . $id_configuracion);
                                }

                                break 2; // Salir de ambos bucles
                            } else if (isset($bloque_info['templates_a[]'])) {

                                $id_plataforma = null;
                                $accessToken = null;
                                $waba_id = null;
                                $id_template = $bloque_info['templates_a[]'];

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

                                    // Llamar a la función para enviar el mensaje template a WhatsApp
                                    $mensaje = null;
                                    $business_phone_id = null;
                                    $phone_whatsapp_from = null;

                                    /* consulta mensajes_cliente */
                                    $check_cofiguraciones_stmt = $conn->prepare("SELECT uid_cliente, celular_cliente FROM clientes_chat_center WHERE id = ?");
                                    $check_cofiguraciones_stmt->bind_param('s', $id_cliente_chat_center);
                                    $check_cofiguraciones_stmt->execute();
                                    $check_cofiguraciones_stmt->store_result();
                                    // Enlazar los resultados a variables
                                    $check_cofiguraciones_stmt->bind_result($business_phone_id, $phone_whatsapp_from);
                                    $check_cofiguraciones_stmt->fetch();  // Obtener los valores vinculados
                                    /* fin consulta mensajes_cliente */

                                    enviarMensajeTextoWhatsApp($accessToken, $business_phone_id, $phone_whatsapp_from, $conn, $id_plataforma, $id_configuracion, $id_template);
                                } else {
                                    // Si no hay resultados, maneja el error apropiadamente
                                    logError("Error: No se encontró configuración con id: " . $id_configuracion);
                                }

                                break 2; // Salir de ambos bucles
                            }
                        }
                    }
                }
            }
        }
    }
}

function enviarMensajeTextoWhatsApp($accessToken, $business_phone_id, $phone_whatsapp_to, $conn, $id_plataforma, $id_configuracion, $id_template)
{
    $texto_mensaje = "";

    // Consulta para obtener el mensaje del template
    $check_automatizadores_stmt = $conn->prepare("SELECT mensaje FROM `templates_chat_center` WHERE id_plataforma = ? AND id_template = ?");
    $check_automatizadores_stmt->bind_param('ii', $id_plataforma, $id_template);
    $check_automatizadores_stmt->execute();
    $check_automatizadores_stmt->store_result();
    $check_automatizadores_stmt->bind_result($texto_mensaje);
    $check_automatizadores_stmt->fetch();
    $check_automatizadores_stmt->close();

    // Configurar el envío del mensaje como texto simple
    $url = "https://graph.facebook.com/v20.0/$business_phone_id/messages";
    $data = [
        "messaging_product" => "whatsapp",
        "to" => $phone_whatsapp_to,
        "type" => "text",
        "text" => [
            "body" => $texto_mensaje
        ]
    ];

    // Inicializar cURL para la solicitud HTTP POST
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

    // Registrar el código HTTP en el log para depuración
    logError("HTTP Code: $http_code\nRespuesta: $response\n");

    // Verificar si la solicitud fue exitosa (aceptando cualquier 2xx)
    if ($http_code >= 200 && $http_code < 300) {
        logError("Mensaje enviado correctamente a $phone_whatsapp_to.\n");

        // Obtener nombres y teléfono de configuración
        $telefono_configuracion = 0;
        $nombre_configuracion = "";

        $check_configuracion_cliente_stmt = $conn->prepare("SELECT telefono, nombre_configuracion FROM configuraciones WHERE id = ?");
        $check_configuracion_cliente_stmt->bind_param('s', $id_configuracion);
        $check_configuracion_cliente_stmt->execute();
        $check_configuracion_cliente_stmt->store_result();
        $check_configuracion_cliente_stmt->bind_result($telefono_configuracion, $nombre_configuracion);
        $check_configuracion_cliente_stmt->fetch();
        $check_configuracion_cliente_stmt->close();

        // Guardar el mensaje en la base de datos
        procesarMensajeTexto($conn, $id_plataforma, $business_phone_id, $nombre_configuracion, "", $telefono_configuracion, $phone_whatsapp_to, "text", $texto_mensaje, null);
    } else {
        logError("Error al enviar el mensaje. HTTP Code: $http_code\nRespuesta: $response\n");
    }

    curl_close($ch);
}

function obtenerTemplatePorID($accessToken, $waba_id, $id_whatsapp_message_template)
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
        logError("Error al obtener los templates: " . $error_msg);
        return null;
    }

    // Cerrar cURL
    curl_close($ch);

    // Decodificar respuesta JSON
    $responseArray = json_decode($response, true);

    // Verificar si la respuesta contiene datos
    if (isset($responseArray['data'])) {
        $facebook_templates = $responseArray['data'];

        // Buscar el template por el ID y obtener su idioma
        foreach ($facebook_templates as $template) {
            if ($template['id'] == $id_whatsapp_message_template) {
                return [
                    'name' => $template['name'], // Retornar el nombre del template
                    'language' => $template['language'], // Retornar el idioma del template
                ];
            }
        }
    }

    logError("No se encontró un template con el ID " . $id_whatsapp_message_template);
    return null;
}

function enviarMensajeTemplateWhatsApp($accessToken, $business_phone_id, $phone_whatsapp_from, $template_name, $template_language, $mensaje = null, $conn, $id_plataforma, $id_configuracion, $mensaje_template, $ruta_archivo_ultimo_tempalte)
{
    // Paso 1: Configurar el envío del mensaje de WhatsApp usando el nombre del template
    $url = "https://graph.facebook.com/v20.0/$business_phone_id/messages";

    // Configuramos los datos básicos del mensaje
    $data = [
        "messaging_product" => "whatsapp",
        "to" => $phone_whatsapp_from,
        "type" => "template",
        "template" => [
            "name" => $template_name,  // Usar el nombre del template
            "language" => ["code" => $template_language],  // Usar el idioma del template
        ]
    ];

    // Solo añadimos el cuerpo (components) si el template acepta parámetros
    // Esto verifica si el template tiene marcadores {{}} en su cuerpo para recibir parámetros
    if ($mensaje !== null && strpos($template_name, '{{') !== false) {
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
        logError("Mensaje template enviado correctamente a " . $phone_whatsapp_from . " usando el template " . $template_name);

        /* Obtener nombres y teléfono config */
        $telefono_configuracion = 0;
        $nombre_configuracion = "";

        $check_configuracion_cliente_stmt = $conn->prepare("SELECT telefono, nombre_configuracion FROM configuraciones WHERE id = ?");
        $check_configuracion_cliente_stmt->bind_param('s', $id_configuracion);  // Buscamos por el celular_cliente
        $check_configuracion_cliente_stmt->execute();
        $check_configuracion_cliente_stmt->store_result();
        $check_configuracion_cliente_stmt->bind_result($telefono_configuracion, $nombre_configuracion);
        $check_configuracion_cliente_stmt->fetch();
        $check_configuracion_cliente_stmt->close();

        logError("Mensaje template: " . $mensaje);

        // Guardar el mensaje enviado como un registro en la base de datos
        $tipo_mensaje = "text";
        $texto_mensaje = $mensaje_template;
        $ruta_archivo = $ruta_archivo_ultimo_tempalte;
        $nombre_cliente = $nombre_configuracion;
        $apellido_cliente = "";

        // Llamar a la función interna para procesar y guardar el mensaje
        procesarMensaje_template($conn, $id_plataforma, $business_phone_id, $nombre_cliente, $apellido_cliente, $telefono_configuracion, $phone_whatsapp_from, $tipo_mensaje, $texto_mensaje, $ruta_archivo, $template_language);
    } else {
        logError("Error al enviar el mensaje template. HTTP Code: " . $http_code . " Respuesta: " . $response);
    }

    curl_close($ch);
}

function procesarMensaje_template($conn, $id_plataforma, $business_phone_id, $nombre_cliente, $apellido_cliente, $telefono_configuracion, $phone_whatsapp_from, $tipo_mensaje, $texto_mensaje, $ruta_archivo, $template_language)
{

    // Registrar en el log de depuración
    $id_cliente = 0;
    $debug_log = [];
    $debug_log['texto_mensaje'] = $texto_mensaje;
    logError("Mensaje procesado: " . $texto_mensaje);


    logError("Probando con idioma: " . $template_language);

    // Verificar si el cliente ya existe en la tabla clientes_chat_center por celular_cliente
    $check_client_stmt = $conn->prepare("SELECT id FROM clientes_chat_center WHERE celular_cliente = ? AND id_plataforma = ? ");
    $check_client_stmt->bind_param('si', $telefono_configuracion, $id_plataforma);  // Buscamos por el celular_cliente
    $check_client_stmt->execute();
    $check_client_stmt->store_result();

    if ($check_client_stmt->num_rows == 0) {
        // El cliente no existe, creamos uno nuevo
        $insert_client_stmt = $conn->prepare("
                INSERT INTO clientes_chat_center (id_plataforma, uid_cliente, nombre_cliente, apellido_cliente, celular_cliente, created_at, updated_at) 
                VALUES (?, ?, ?, ?, ?, NOW(), NOW())
            ");
        $insert_client_stmt->bind_param('issss', $id_plataforma, $business_phone_id, $nombre_cliente, $apellido_cliente, $telefono_configuracion);
        $insert_client_stmt->execute();
        $id_cliente = $insert_client_stmt->insert_id;  // Obtener el ID autoincrementado del cliente recién creado
        $insert_client_stmt->close();
    } else {
        // El cliente existe, obtenemos su ID
        $check_client_stmt->bind_result($id_cliente);
        $check_client_stmt->fetch();
    }

    $check_client_stmt->close();

    $id_cliente_recibe = 0;

    // Obtener ID del cliente que recibe
    $check_idCliente_recibe_stmt = $conn->prepare("SELECT id FROM clientes_chat_center WHERE celular_cliente = ? AND id_plataforma = ? ");
    $check_idCliente_recibe_stmt->bind_param('si', $phone_whatsapp_from, $id_plataforma);  // Buscamos por el celular_cliente
    $check_idCliente_recibe_stmt->execute();
    $check_idCliente_recibe_stmt->store_result();
    $check_idCliente_recibe_stmt->bind_result($id_cliente_recibe);
    $check_idCliente_recibe_stmt->fetch();
    $check_idCliente_recibe_stmt->close();

    // Ahora puedes proceder a insertar el mensaje en la tabla mensajes_clientes
    $stmt = $conn->prepare("
            INSERT INTO mensajes_clientes (id_plataforma, id_cliente, mid_mensaje, tipo_mensaje, texto_mensaje, ruta_archivo, rol_mensaje, celular_recibe, uid_whatsapp, created_at, updated_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
        ");

    $mid_mensaje = $business_phone_id;  // Usamos el ID del mensaje de WhatsApp
    $rol_mensaje = 1;  // Valor por defecto para rol_mensaje, ya que es bigint

    $stmt->bind_param('iissssiss', $id_plataforma, $id_cliente, $mid_mensaje, $tipo_mensaje, $texto_mensaje, $ruta_archivo, $rol_mensaje, $id_cliente_recibe, $phone_whatsapp_from);

    // Si se ejecuta correctamente, detener el ciclo
    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Mensaje procesado correctamente en el idioma $template_language."]);
        logError("Mensaje procesado correctamente en el idioma " . $template_language);
        break;
    } else {
        logError("Error al procesar el mensaje en el idioma " . $template_language . " SQL Error: " . $stmt->error);
    }

    $stmt->close();

    // Guardar el log en un archivo para depuración
    logError("" . print_r($debug_log, true));
}

// Evitar ejecuciones simultáneas
if (file_exists($lockFile)) {
    logError("El proceso ya está en ejecución.");
    exit;
}

file_put_contents($lockFile, getmypid());

try {
    // Conectar a la base de datos
    $conn = connectDatabase();

    // Ejecutar la función principal
    validarTiempo($conn);

    // Cerrar la conexión
    $conn->close();
} catch (Exception $e) {
    logError("Error en el script: " . $e->getMessage());
} finally {
    // Eliminar el archivo de bloqueo
    unlink($lockFile);
}

logError("Ejecución completada.");
echo "Ejecución completada.\n";
