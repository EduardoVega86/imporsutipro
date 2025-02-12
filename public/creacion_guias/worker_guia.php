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

function procesarGuiaLaar($data, $conn)
{
    // Obtener token de autenticación
    $token = obtenerTokenLaar();

    // Generar número de guía
    $numero_guia = obtenerUltimaGuia($conn);

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
        actualizarGuia($conn, $data, $result["guia"]);

        // 📌 Asignar a Wallet
        asignarWallet($conn, $data, $result["guia"]);

        // 📌 Descargar la guía
        descargarGuia($result["guia"]);
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

/** 🔹 Obtener el último número de guía */
function obtenerUltimaGuia($conn)
{
    $sql = "SELECT MAX(numero_guia) as numero_guia FROM facturas_cot WHERE numero_guia LIKE 'LAAR00%' FOR UPDATE";
    $result = $conn->query($sql);
    return $result->fetch_assoc()['numero_guia'] + 1;
}

/** 🔹 Actualizar la base de datos con la guía generada */
function actualizarGuia($conn, $data, $guia)
{
    $sql = "UPDATE facturas_cot SET 
            id_usuario = ?, monto_factura = ?, nombre = ?, telefono = ?, provincia = ?, 
            c_principal = ?, ciudad_cot = ?, c_secundaria = ?, referencia = ?, 
            observacion = ?, guia_enviada = 1, transporte = 'LAAR', celular = ?, 
            estado_guia_sistema = 2, numero_guia = ?, cod = ?, contiene = ?, 
            comentario = ?, id_transporte = 1, costo_flete = ?, fecha_guia = NOW() 
            WHERE numero_factura = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "idsssssssssssdsi",
        $_SESSION["id"] ?? $data["id_usuario"],
        $data["costo_producto"],
        $data["nombreDestino"],
        $data["telefonoDestino"],
        $data["provincia"],
        $data["calle_principal"],
        $data["ciudadDestino"],
        $data["calle_secundaria"],
        $data["referenciaDestino"],
        $data["comentario"],
        $data["celularDestino"],
        $guia,
        $data["cod"],
        $data["contiene"],
        $data["comentario"],
        $data["costoflete"],
        $data["numero_factura"]
    );
    $stmt->execute();
}

/** 🔹 Asignar la guía al Wallet */
function asignarWallet($conn, $data, $guia)
{
    $sql = "INSERT INTO cabecera_cuenta_pagar 
            (numero_factura, fecha, cliente, tienda, proveedor, estado_guia, 
            total_venta, costo, precio_envio, monto_recibir, valor_cobrado, 
            valor_pendiente, full, guia, cod, id_matriz, id_plataforma, 
            id_proveedor, id_full, id_referido) 
            VALUES (?, NOW(), ?, ?, ?, ?, ?, ?, ?, ?, 0, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "sssssiidddddiiii",
        $data["numero_factura"],
        $data["nombreDestino"],
        $data["tienda_venta"],
        $data["proveedor"],
        1, // estado_guia
        $data["costo_producto"],
        $data["costo_o"],
        $data["precio_envio"],
        $data["monto_recibir"],
        $data["monto_recibir"],
        $data["full"],
        $guia,
        $data["cod"],
        $data["id_matriz"],
        $data["id_plataforma"],
        $data["id_plataforma_producto"],
        $data["id_plataforma_bodega"],
        $data["id_referido"]
    );
    $stmt->execute();
}

/** 🔹 Descargar la guía */
function descargarGuia($guia)
{
    file_put_contents("public/repositorio/guias/guia_$guia.pdf", file_get_contents("https://api.laarcourier.com:9727/guias/pdfs/DescargarV2?guia=$guia"));
}
