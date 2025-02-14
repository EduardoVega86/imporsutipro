<?php
$redis = new Redis();
$redis->connect('3.233.119.65', 6379);

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
                procesarGuiaLaar($data, $conn);
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

/** 🔹 Procesar guía */
function procesarGuiaLaar($data, $conn)
{
    $token = laarToken();
    $numero_guia = ultimaguia($conn);

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
        actualizarGuia($conn, $data, $result["guia"]);
        asignarWallet($conn, $data, $result["guia"]);
        descargarGuia($result["guia"]);
    } else {
        logError("Error generando guía: " . json_encode($result));
    }
}

/** 🔹 Obtener Token de LAAR */
function laarToken()
{
    $ch = curl_init(LAAR_ENDPOINT_AUTH);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(["username" => LAAR_USER, "password" => LAAR_PASSWORD]));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    $response = json_decode(curl_exec($ch), true);
    curl_close($ch);
    return $response['token'] ?? '';
}

/** 🔹 Obtener última guía */
function ultimaguia($conn)
{
    $sql = "SELECT MAX(numero_guia) as numero_guia FROM facturas_cot WHERE numero_guia LIKE 'LAAR00%' FOR UPDATE";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['numero_guia'] ? incrementarGuia($row['numero_guia']) : 'LAAR000001';
}

/** 🔹 Actualizar la guía en la BD */
function actualizarGuia($conn, $data, $guia)
{
    $stmt = $conn->prepare("UPDATE facturas_cot SET numero_guia=?, estado_guia_sistema=2 WHERE numero_factura=?");
    $stmt->bind_param("ss", $guia, $data['numero_factura']);
    $stmt->execute();
    $stmt->close();
}

/** 🔹 Asignar Wallet */
function asignarWallet($conn, $data, $guia)
{
    $stmt = $conn->prepare("INSERT INTO cabecera_cuenta_pagar (numero_factura, fecha, guia) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $data['numero_factura'], $data['fecha'], $guia);
    $stmt->execute();
    $stmt->close();
}

/** 🔹 Descargar la guía */
function descargarGuia($guia)
{
    $url = "https://api.laarcourier.com:9727/guias/pdfs/DescargarV2?guia=$guia";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    $rutaCarpeta = __DIR__ . '/guias/';
    $nombreArchivo = "guia_$guia.pdf";
    $rutaCompleta = $rutaCarpeta . $nombreArchivo;

    if (!file_exists($rutaCarpeta)) {
        mkdir($rutaCarpeta, 0777, true);
    }

    file_put_contents($rutaCompleta, $response);
}

/** 🔹 Incrementar número de guía */
function incrementarGuia($numero_guia)
{
    $numero = intval(substr($numero_guia, -6)) + 1;
    return 'LAAR' . str_pad($numero, 6, "0", STR_PAD_LEFT);
}
