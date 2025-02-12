<?php
$redis = new Redis();
$redis->connect('3.233.119.65', 6379);

// Incluir el archivo del modelo
require_once __DIR__ . '/../../Models/GuiasModel.php'; // Ajusta la ruta si es necesario

// Crear una instancia del modelo
$model = new GuiaModel(); // Asegúrate de que el nombre de la clase es correcto

// Conexión a la base de datos
$conn = new mysqli('3.233.119.65', 'imporsuit_system', 'imporsuit_system', 'imporsuitpro_new');
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Constantes de configuración de LAAR
const LAAR_USER = "import.uio.api";
const LAAR_PASSWORD = "Imp@rt*23";
const LAAR_ENDPOINT_AUTH = "https://api.laarcourier.com:9727/authenticate";
const LAAR_ENDPOINT = "https://api.laarcourier.com:9727/guias/contado";
const LLAR_ENDPOINT_CANCEL = 'https://api.laarcourier.com:9727/guias/anular/';

// Función para registrar errores en logs
function logError($message)
{
    $logFile = __DIR__ . '/logs/error_log_worker_guia.txt';
    $timestamp = date("Y-m-d H:i:s");
    file_put_contents($logFile, "[$timestamp] $message\n", FILE_APPEND);
}

while (true) {
    try {
        $job = $redis->rPop("queue:guias");
        if ($job) {
            $data = json_decode($job, true);
            if ($data) {
                procesarGuiaLaar($data, $conn, $model);
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

function procesarGuiaLaar($data, $conn, $model)
{
    // Obtener token de autenticación
    $token = $model->laarToken();

    // Obtener número de guía del modelo
    $numero_guia = $model->ultimaguia();

    // Preparar datos para la API de LAAR
    $datos = [
        "origen" => [
            "identificacionO" => "0",
            "nombreO" => $data['vendedor'],
            "ciudadO" => $data['ciudadOrigen'],
            "direccion" => $data['direccionOrigen'],
            "celular" => $data['telf_vendedor'],
            "referenciaO" => $data['referenciaOrigen'],
            "celularO" => $data['celularOrigen'],
            "postal" => "",
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
            "postal" => "",
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
        "costoflete" => 0,
        "costoproducto" => $data['costo_producto'],
        "tipoCobro" => $data['tipo_cobro'],
        "comentario" => $data['comentario'],
        "fechaPedido" => $data['fecha'],
        "extras" => []
    ];

    // Enviar datos a LAAR
    $ch = curl_init(LAAR_ENDPOINT);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($datos));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $token
    ]);
    $response = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($response, true);

    if (!empty($result["guia"])) {
        // 📌 Actualizar base de datos con la guía generada
        $model->actualizarGuia(
            $data['numero_factura'], $result["guia"], $data['nombreDestino'], $data['ciudadDestino'],
            $data['direccionDestino'], $data['telefonoDestino'], $data['celularDestino'], $data['referenciaDestino'],
            $data['cod'], $data['costo_producto'], $data['comentario'], $data['id_usuario'],
            $data['calle_principal'], $data['calle_secundaria'], $data['contiene'], $data['provincia'],
            $data['costoflete'], "LAAR", 2
        );

        // 📌 Asignar a Wallet
        $model->asignarWallet(
            $data['numero_factura'], $result["guia"], $data['fecha'], $data['nombreDestino'],
            $data['id_plataforma'], 1, $data['costo_producto'], $data['cod'], $data['costoflete']
        );

        // 📌 Descargar la guía
        $model->descargarGuia($result["guia"]);
    } else {
        logError("Error generando guía: " . json_encode($result));
    }
}

/** 🔹 Obtener Token de LAAR */
function obtenerTokenLaar()
{
    $ch = curl_init(LAAR_ENDPOINT_AUTH);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(["username" => LAAR_USER, "password" => LAAR_PASSWORD]));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    $response = json_decode(curl_exec($ch), true);
    curl_close($ch);
    return $response['token'] ?? '';
}
