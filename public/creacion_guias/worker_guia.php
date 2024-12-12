<?php
// worker_guia.php

// Conexión a Redis
$redis = new Redis();
$redis->connect('3.233.119.65', 6379);

// Constantes de configuración de LAAR
const LAAR_USER = "import.uio.api";
const LAAR_PASSWORD = "Imp@rt*23";
const LAAR_ENDPOINT_AUTH = "https://api.laarcourier.com:9727/authenticate";
const LAAR_ENDPOINT = "https://api.laarcourier.com:9727/guias/contado";
const LLAR_ENDPOINT_CANCEL = 'https://api.laarcourier.com:9727/guias/anular/';

// Crear carpeta de logs si no existe
$logDirectory = __DIR__ . '/logs';
if (!is_dir($logDirectory)) {
    mkdir($logDirectory, 0777, true);
}

// Ruta del archivo de log
$logFile = $logDirectory . '/error_log_worker_guia.txt';


// Función para registrar errores o mensajes en el archivo de log
function logError($message)
{
    global $logFile;
    $timestamp = date("Y-m-d H:i:s");
    file_put_contents($logFile, "[$timestamp] $message\n", FILE_APPEND);
}

// Función para obtener un token de LAAR
function laarToken()
{
    $ch = curl_init(LAAR_ENDPOINT_AUTH);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $auth = json_encode([
        "username" => LAAR_USER,
        "password" => LAAR_PASSWORD
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $auth);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Content-Length: ' . strlen($auth)
    ]);
    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        throw new Exception('Error en la solicitud cURL para obtener el token: ' . curl_error($ch));
    }
    curl_close($ch);
    $response = json_decode($response, true);
    return $response['token'] ?? null;
}

// Función para generar el número de guía
function incrementarGuia($guia)
{
    $pos = strcspn($guia, '0123456789');
    $prefijo = substr($guia, 0, $pos);
    $numero = substr($guia, $pos);
    $numero = str_pad((int)$numero + 1, strlen($numero), '0', STR_PAD_LEFT);
    return $prefijo . $numero;
}

// Función para obtener el último número de guía
function ultimaguia($conn)
{
    $prefijo = "MKP"; // Modifica este prefijo según tu lógica
    $conn->begin_transaction();
    try {
        $query = "SELECT MAX(numero_guia) as numero_guia FROM facturas_cot WHERE numero_guia LIKE '$prefijo%' FOR UPDATE";
        $result = $conn->query($query);
        $row = $result->fetch_assoc();
        $numero_guia = $row['numero_guia'];
        if (!$numero_guia) {
            $numero_guia = $prefijo . "000001";
        } else {
            $numero_guia = incrementarGuia($numero_guia);
        }
        $conn->query("UPDATE matriz SET guia_generadas = guia_generadas + 1 WHERE idmatriz = 1"); // Modifica el `idmatriz` si es necesario
        $conn->commit();
        return $numero_guia;
    } catch (Exception $e) {
        $conn->rollback();
        throw $e;
    }
}

// Función para procesar una guía desde la cola
function procesarGuia($data, $conn)
{
    try {
        // Obtener el token de LAAR
        $token = laarToken();
        logError("Token obtenido correctamente");

        // Generar número de guía
        $numero_guia = ultimaguia($conn);

        // Estructura de datos para la API de LAAR
        $datos = [
            "origen" => [
                "identificacionO" => $data['identificacion'],
                "nombreO" => $data['nombreOrigen'],
                "ciudadO" => $data['ciudadOrigen'],
                "direccion" => $data['direccionOrigen'],
                "telefono" => $data['telefonoOrigen'],
                "celular" => $data['celularOrigen'],
                "referenciaO" => $data['referenciaOrigen'],
                "postal" => $data['postal'],
                "numeroCasa" => "0"
            ],
            "destino" => [
                "identificacionD" => "0",
                "nombreD" => $data['nombreDestino'],
                "ciudadD" => $data['ciudadDestino'],
                "direccion" => $data['direccionDestino'],
                "telefono" => $data['telefonoDestino'],
                "celular" => $data['celularDestino'],
                "referencia" => $data['referenciaDestino'],
                "postal" => $data['postal'],
                "numeroCasa" => "0"
            ],
            "numeroGuia" => $numero_guia,
            "tipoServicio" => "201202002002013",
            "noPiezas" => 1,
            "peso" => $data['peso'],
            "valorDeclarado" => $data['valor_declarado'],
            "contiene" => $data['contiene'],
            "tamanio" => $data['tamanio'],
            "cod" => $data['cod'] == 1,
            "costoflete" => $data['costoflete'],
            "costoproducto" => $data['costo_producto'],
            "tipoCobro" => $data['tipo_cobro'],
            "comentario" => $data['comentario'],
            "fechaPedido" => $data['fecha'],
            "extras" => [
                "Campo1" => "",
                "Campo2" => "",
                "Campo3" => ""
            ]
        ];

        // Enviar datos a la API de LAAR
        $ch = curl_init(LAAR_ENDPOINT);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($datos));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $token
        ]);

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            throw new Exception('Error en la solicitud cURL: ' . curl_error($ch));
        }
        curl_close($ch);

        // Procesar la respuesta de la API
        $response = json_decode($response, true);
        if (empty($response['guia'])) {
            throw new Exception("Error al generar la guía: " . json_encode($response));
        }

        // Registrar guía en la base de datos
        $sql = "UPDATE facturas_cot SET numero_guia = ? WHERE numero_factura = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $response['guia'], $data['numero_factura']);
        if (!$stmt->execute()) {
            throw new Exception("Error al actualizar la guía en la base de datos: " . $stmt->error);
        }

        logError("Guía generada correctamente: " . $response['guia']);
    } catch (Exception $e) {
        logError("Error al procesar la guía: " . $e->getMessage());
    }
}

// Bucle principal del Worker
$conn = new mysqli('3.233.119.65', 'imporsuit_system', 'imporsuit_system', 'imporsuitpro_new');
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

while (true) {
    try {
        $job = $redis->rPop("queue:guias");
        if ($job) {
            $data = json_decode($job, true);
            if ($data) {
                procesarGuia($data, $conn);
            } else {
                logError("Error al decodificar JSON: $job");
            }
        } else {
            sleep(1);
        }
    } catch (Exception $e) {
        logError($e->getMessage());
    }
}
