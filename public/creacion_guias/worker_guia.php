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
const PREFIJOS = "IMP";
const MATRIZ = 1;

// Función para registrar errores en logs
function logError($message)
{
    $logFile = __DIR__ . '/logs/error_log_worker_guia.txt';
    $timestamp = date("Y-m-d H:i:s");
    file_put_contents($logFile, "[$timestamp] $message\n", FILE_APPEND);
}

// PROCESAR COLA REDIS
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

/** 🔹 PROCESAR GUÍA */
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

/** 🔹 OBTENER TOKEN DE LAAR */
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

/** 🔹 OBTENER ÚLTIMA GUÍA */
function ultimaguia($conn)
{
    $sql = "SELECT MAX(numero_guia) as numero_guia FROM facturas_cot WHERE numero_guia LIKE '" . PREFIJOS . "00%' FOR UPDATE";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['numero_guia'] ? incrementarGuia($row['numero_guia']) : PREFIJOS . "000001";
}

/** 🔹 Incrementar número de guía */
function incrementarGuia($guia)
{
    // Encontrar la posición del primer dígito en la cadena
    $pos = strcspn($guia, '0123456789');
    // Separar el prefijo del número de serie
    $prefijo = substr($guia, 0, $pos);
    $numero = substr($guia, $pos);

    // Incrementar el número de serie
    $numero = str_pad((int)$numero + 1, strlen($numero), '0', STR_PAD_LEFT);

    // Unir el prefijo con el número de serie
    $guia = $prefijo . $numero;

    return $guia;
}

/** 🔹 ACTUALIZAR GUÍA EN LA BD */
function actualizarGuia($conn, $data, $guia)
{
    $stmt = $conn->prepare("UPDATE facturas_cot SET numero_guia=?, estado_guia_sistema=2 WHERE numero_factura=?");
    $stmt->bind_param("ss", $guia, $data['numero_factura']);
    $stmt->execute();
    $stmt->close();
}

/** 🔹 DESCARGAR GUÍA */
function descargarGuia($guia)
{
    $url = (str_contains($guia, "IMP") || str_contains($guia, "MKP") || str_contains($guia, "RCK"))
        ? "https://api.laarcourier.com:9727/guias/pdfs/DescargarV2?guia=$guia"
        : "https://guias.imporsuitpro.com/Servientrega/guia/$guia";

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    curl_close($ch);

    $rutaCarpeta = __DIR__ . '/public/repositorio/guias/';
    $nombreArchivo = "guia_$guia.pdf";
    $rutaCompleta = $rutaCarpeta . $nombreArchivo;

    if (!file_exists($rutaCarpeta)) {
        mkdir($rutaCarpeta, 0777, true);
    }

    file_put_contents($rutaCompleta, $response);

    return file_exists($rutaCompleta) ? $rutaCompleta : false;
}

/** 🔹 Asignar Wallet */
function asignarWallet($conn, $data, $guia)
{
    $sql = "SELECT * FROM detalle_fact_cot WHERE numero_factura = '{$data['numero_factura']}'";
    $detalle = $conn->query($sql)->fetch_assoc();

    $sql = "SELECT * FROM inventario_bodegas WHERE id_inventario = '{$detalle['id_inventario']}'";
    $inventario = $conn->query($sql)->fetch_assoc();

    $sql = "SELECT * FROM bodega WHERE id = '{$inventario['bodega']}'";
    $bodega = $conn->query($sql)->fetch_assoc();

    $valor_full = $bodega['full_filme'];
    $existe_full = $valor_full > 0 ? 1 : 0;

    $sql = "SELECT url_imporsuit FROM plataformas WHERE id_plataforma = '{$bodega['id_plataforma']}'";
    $proveedor = $conn->query($sql)->fetch_assoc()['url_imporsuit'];

    $sql = "SELECT url_imporsuit FROM plataformas WHERE id_plataforma = '{$data['id_plataforma']}'";
    $tienda_venta = $conn->query($sql)->fetch_assoc()['url_imporsuit'];

    $sql = "SELECT costo_producto FROM facturas_cot WHERE numero_factura = '{$data['numero_factura']}'";
    $costo_o = $conn->query($sql)->fetch_assoc()['costo_producto'];

    $costo_o = ($data['id_plataforma'] == $inventario['id_plataforma']) ? 0 : $costo_o;

    if ($tienda_venta == $proveedor) {
        $proveedor = null;
    }

    $costo_producto = $data['cod'] == 1 ? $data['costo_producto'] : 0;
    $monto_recibir = $costo_producto - $data['precio_envio'] - $valor_full - $costo_o;

    $sql = "SELECT refiere FROM plataformas WHERE id_plataforma = '{$data['id_plataforma']}'";
    $id_referido = $conn->query($sql)->fetch_assoc()['refiere'] ?? 0;

    if (!$existe_full) {
        $bodega['id_plataforma'] = 0;
    }

    $stmt = $conn->prepare("INSERT INTO cabecera_cuenta_pagar (numero_factura, fecha, cliente, tienda, proveedor, estado_guia, total_venta, costo, precio_envio, monto_recibir, valor_cobrado, valor_pendiente, full, guia, cod, id_matriz, id_plataforma, id_proveedor, id_full, id_referido) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param("ssssssssssssssssssss", 
        $data['numero_factura'], $data['fecha'], $data['nombreDestino'], $tienda_venta, 
        $proveedor, $data['estado'], $costo_producto, $costo_o, $data['precio_envio'], $monto_recibir, 
        0, $monto_recibir, $valor_full, $guia, $data['cod'], MATRIZ, 
        $data['id_plataforma'], $bodega['id_plataforma'], $bodega['id_plataforma'], $id_referido
    );

    $stmt->execute();
    $stmt->close();
}