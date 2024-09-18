<?php
class ShopifyModel extends Query
{
    public function __construct()
    {
        parent::__construct();
    }

    public function productoPlataforma($id_plataforma, $data)
    {

        $data = json_decode($data, true);
        if (isset($data['line_items']) && is_array($data['line_items'])) {
            foreach ($data['line_items'] as $item) {

                $sql = "SELECT * FROM shopify_tienda WHERE id_plataforma = $id_plataforma and id_inventario =" . $item['sku'];
                $response = $this->select($sql);
                if (count($response) == 0) {
                    return false;
                } else {
                    return true;
                }
            }
            return true;
        }
    }

    public function gestionarRequest($plataforma, $data)
    {
        $json_String = mb_convert_encoding($data, "UTF-8", "auto");
        $data = json_decode($json_String, true);
        $order_number = $data['order_number'];
        $configuraciones = $this->obtenerConfiguracion($plataforma);
        $configuraciones = $configuraciones[0];
        $resultados = [];

        foreach ($configuraciones as $key => $value) {
            $resultados[$key] = $this->obtenerData($data, $value);
        }
        $lineItems = [];
        if (isset($data['line_items']) && is_array($data['line_items'])) {
            foreach ($data['line_items'] as $item) {
                $lineItems[] = [
                    'id' => $item['id'],
                    'sku' => $item['sku'],
                    'name' => $item['name'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'vendor' => $item['vendor']
                ];
            }
        }

        // Gestión de creación de orden
        $orden = $this->crearOrden($resultados, $lineItems, $plataforma, $order_number);
    }

    public function crearOrden($data, $lineItems, $plataforma, $order_number)
    {
        $nombre = $data['nombre'] . " " . $data['apellido'];
        $telefono = $data['telefono'];
        // Quitar el + de la cadena
        $telefono = str_replace("+", "", $telefono);
        $calle_principal = $data['principal'];
        $calle_secundaria = $data['secundaria'] ?? "";
        $provincia = $data['provincia'];
        if ($provincia == "SANTO DOMINGO DE LOS TSACHILAS") {
            $provincia = "SANTO DOMINGO";
        } else if ($provincia == "Santo Domingo de los Tsáchilas") {
            $provincia = "SANTO DOMINGO";
        } else if ($provincia == "ZAMORA CHINCHIPE") {
            $provincia = "ZAMORA";
        }
        $provinciaData = $this->obtenerProvincia($provincia);
        $provincia = $provinciaData[0]['codigo_provincia'] ?? null;
        $ciudad = $data['ciudad'];
        $ciudadData = $this->obtenerCiudad($ciudad);
        if (!empty($ciudadData)) {
            $ciudad = $ciudadData[0]['id_cotizacion'];
        } else {
            $ciudad = 0;
        }
        $referencia = "Referencia: " . ($data["referencia"] ?? "");
        $observacion = "Ciudad: " . $data["ciudad"];
        $transporte = 0;
        $importado = 1;
        $plataforma_importa = "Shopify";
        $recaudo = 1;

        $contiene = "";
        $costo_producto = 0;

        $productos = [];
        $total_line_items = 0;
        $costo = 0;
        $total_units = 0;

        // Recorre los items y verifica las condiciones necesarias
        foreach ($lineItems as $item) {
            if (empty($item['sku'])) {
                // Si solo hay un ítem y no tiene SKU, detiene el proceso
                if (count($lineItems) == 1) {
                    die("Proceso detenido: el único ítem no tiene SKU.");
                }

                // Si el SKU está vacío, salta al siguiente ítem
                $observacion .= ", SKU vacío: " . $item['name'] . " x" . $item['quantity'] . ": $" . $item['price'] . "";
                $item_total_price = $item['price'] * $item['quantity'];
                $total_line_items += $item_total_price;
                $total_units += $item['quantity'];

                $productos[] = [
                    'id_producto_venta' => null, // O algún identificador para productos sin SKU
                    'nombre' => $this->remove_emoji($item['name']),
                    'cantidad' => $item['quantity'],
                    'precio' => $item['price'],
                    'item_total_price' => $item_total_price,
                ];

                continue;
            }

            $id_producto_venta = $item['sku'];

            // Obtener información de la bodega
            $datos_telefono = $this->obtenerBodegaInventario($id_producto_venta);

            $product_costo = $this->obtenerCosto($id_producto_venta);
            $costo += $product_costo * $item['quantity']; // Multiplica por la cantidad

            if (empty($datos_telefono)) {
                // Manejar el caso en que no se obtengan datos de la bodega
                die("No se encontraron datos de bodega para el producto con SKU: $id_producto_venta");
            }

            $bodega = $datos_telefono[0];

            $celularO = $bodega['contacto'];
            $nombreO = $bodega['nombre'];
            $ciudadO = $bodega['localidad'];
            $provinciaO = $bodega['provincia'];
            $direccionO = $bodega['direccion'];
            $referenciaO = $bodega['referencia'] ?? " ";
            $numeroCasaO = $bodega['num_casa'] ?? " ";
            $valor_segura = 0;

            $id_bodega = $bodega['id'];

            $no_piezas = $item['quantity'];
            $contiene .= $item['name'] . " x" . $item['quantity'] . " ";

            $costo_flete = 0;
            $costo_producto += $item['price'] * $item['quantity'];
            $id_transporte = 0;

            $item_total_price = $item['price'] * $item['quantity'];
            $total_line_items += $item_total_price;
            $total_units += $item['quantity'];

            $productos[] = [
                'id_producto_venta' => $id_producto_venta,
                'nombre' => $this->remove_emoji($item['name']),
                'cantidad' => $item['quantity'],
                'precio' => $item['price'],
                'item_total_price' => $item_total_price,
            ];
        }

        $discount = $data['discount'] ?? 0;

        if ($discount > 0) {
            // Distribuir el descuento proporcionalmente entre los productos
            foreach ($productos as &$producto) {
                // Calcula la proporción del descuento que corresponde a este producto
                $product_discount = ($producto['item_total_price'] / $total_line_items) * $discount;

                // Ajusta el precio por unidad
                $discount_per_unit = $product_discount / $producto['cantidad'];
                $producto['precio'] = $producto['precio'] - $discount_per_unit;
            }
            unset($producto); // Rompe la referencia con el último elemento
        }

        // Recalcular el total de la venta
        $total_venta = 0;
        foreach ($productos as $producto) {
            $total_venta += $producto['precio'] * $producto['cantidad'];
        }

        $comentario = "Orden creada desde Shopify, número de orden: " . $order_number;

        $contiene = trim($contiene); // Eliminar el espacio extra al final
        // Eliminar emojis o caracteres especiales
        $contiene = $this->remove_emoji($contiene);

        $observacion .= " Número de orden: " . $order_number;

        // Aquí se pueden continuar los procesos necesarios para la orden
        // Iniciar cURL
        $ch = curl_init();
        $url = "https://new.imporsuitpro.com/pedidos/nuevo_pedido_shopify";

        $data = array(
            'fecha_factura' => date("Y-m-d H:i:s"),
            'id_usuario' => 1176,
            'monto_factura' => $total_venta,
            'estado_factura' => 1,
            'nombre_cliente' => $nombre,
            'telefono_cliente' => $telefono,
            'calle_principal' => $calle_principal,
            'ciudad_cot' => $ciudad,
            'calle_secundaria' => $calle_secundaria,
            'referencia' => $referencia,
            'observacion' => $observacion,
            'guia_enviada' => 0,
            'transporte' => $transporte,
            'identificacion' => "",
            'celular' => $telefono,
            'dueño_id' => $plataforma,
            'dropshipping' => 0,
            'id_plataforma' =>  $plataforma,
            'importado' => $importado,
            'plataforma_importa' => $plataforma_importa,
            'cod' => $recaudo,
            'estado_guia_sistema' => 1,
            'impreso' => 0,
            'facturada' => 0,
            'factura_numero' => 0,
            'numero_guia' => 0,
            'anulada' => 0,
            'identificacionO' => "",
            'celularO' => $celularO ?? "",
            'nombreO' => $nombreO ?? "",
            'ciudadO' => $ciudadO ?? "",
            'provinciaO' => $provinciaO ?? "",
            'direccionO' => $direccionO ?? "",
            'referenciaO' => $referenciaO ?? "",
            'numeroCasaO' => $numeroCasaO ?? "",
            'valor_segura' => $valor_segura ?? 0,
            'no_piezas' => $no_piezas ?? 0,
            'tipo_servicio' => "201202002002013",
            'peso' => "2",
            'contiene' => $contiene,
            'costo_flete' => $costo_flete ?? 0,
            'costo_producto' => $costo,
            'comentario' => $comentario,
            'id_transporte' => $id_transporte ?? 0,
            'provincia' => $provincia,
            'ciudad' => $ciudad,
            'total_venta' => $total_venta,
            'nombre' => $nombre,
            'telefono' => $telefono,
            'calle_principal' => $calle_principal,
            'calle_secundaria' => $calle_secundaria,
            'referencia' => $referencia,
            'observacion' => $observacion,
            'transporte' => $transporte,
            'importado' => $importado,
            'id_producto_venta' => $id_producto_venta ?? "",
            'productos' => $productos,
            'id_bodega' => $id_bodega ?? "",
        );

        $data = http_build_query($data);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        // Puedes manejar la respuesta aquí si es necesario
    }

    public function obtenerBodegaInventario($id_producto_venta)
    {
        $sql = "SELECT * FROM inventario_bodegas WHERE id_inventario = ?";
        $params = array($id_producto_venta);
        $response = $this->simple_select($sql, $params);
        if (!empty($response)) {
            $bodega_id = $response[0]['bodega'];
            $sql2 = "SELECT * FROM bodega WHERE id = ?";
            $params2 = array($bodega_id);
            $response2 = $this->simple_select($sql2, $params2);
            return $response2;
        } else {
            return [];
        }
    }

    public function obtenerProvincia($provincia)
    {
        $sql = "SELECT * FROM provincia_laar WHERE provincia = ?";
        $params = array($provincia);
        $response = $this->simple_select($sql, $params);
        return $response;
    }

    public function obtenerCiudad($ciudad)
    {
        $sql = "SELECT * FROM ciudad_cotizacion WHERE ciudad = ?";
        $params = array($ciudad);
        $response = $this->simple_select($sql, $params);
        return $response;
    }

    function obtenerData($data, $ruta)
    {
        $partes = explode("/", $ruta);
        foreach ($partes as $parte) {
            if (isset($data[$parte])) {
                $data = $data[$parte];
            } else {
                return null;
            }
        }
        return $data;
    }

    public function obtenerConfiguracion($id_plataforma)
    {
        $sql = "SELECT * FROM configuracion_shopify WHERE id_plataforma = ?";
        $params = array($id_plataforma);
        $response = $this->simple_select($sql, $params);
        return $response;
    }

    public function iniciarPlataforma($id_plataforma)
    {
        $sql = "INSERT INTO shopify (id_plataforma) VALUES (?)";
        $params = array($id_plataforma);
        $response = $this->insert($sql, $params);
        return $response;
    }

    public function existenciaPlataforma($id_plataforma)
    {
        $sql = "SELECT id_plataforma FROM configuracion_shopify WHERE id_plataforma = ?";
        $params = array($id_plataforma);
        $response = $this->simple_select($sql, $params);
        if ($response > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function generarEnlace($id_plataforma)
    {
        $sql = "SELECT id_matriz FROM plataformas WHERE id_plataforma = ?";
        $params = array($id_plataforma);
        $response = $this->simple_select($sql, $params);
        $url = $response[0]["id_matriz"];

        $sql = "SELECT * FROM matriz WHERE idmatriz = ?";
        $params = array($url);
        $response = $this->simple_select($sql, $params);
        $url = $response[0]["url_matriz"];

        $responses = array(
            "url_imporsuit" =>  $url . "shopify/index/" . $id_plataforma,
        );
        return $responses;
    }

    function remove_emoji($string)
    {
        // Expresiones regulares para eliminar emojis
        $regex_patterns = [
            '/[\x{1F100}-\x{1F1FF}]/u', // Enclosed Alphanumeric Supplement
            '/[\x{1F300}-\x{1F5FF}]/u', // Miscellaneous Symbols and Pictographs
            '/[\x{1F600}-\x{1F64F}]/u', // Emoticons
            '/[\x{1F680}-\x{1F6FF}]/u', // Transport And Map Symbols
            '/[\x{1F900}-\x{1F9FF}]/u', // Supplemental Symbols and Pictographs
            '/[\x{2600}-\x{26FF}]/u',   // Miscellaneous Symbols
            '/[\x{2700}-\x{27BF}]/u',   // Dingbats
        ];

        foreach ($regex_patterns as $regex) {
            $string = preg_replace($regex, '', $string);
        }

        return $string;
    }

    public function guardarConfiguracion($nombre, $apellido, $principal, $secundario, $provincia, $ciudad, $codigo_postal, $pais, $telefono, $email, $total, $descuento, $referencia, $id_plataforma)
    {
        $sql = "REPLACE INTO configuracion_shopify (`nombre`, `apellido`, `principal`, `secundaria`, `provincia`, `ciudad`, `codigo_postal`, `pais`, `telefono`, `email`, `total`, `discount`, `referencia`, `id_plataforma`) VALUES (?, ?, ?, ?, ? ,?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $params = [$nombre, $apellido, $principal, $secundario, $provincia, $ciudad, $codigo_postal, $pais, $telefono, $email, $total, $descuento, $referencia, $id_plataforma];
        $response = $this->insert($sql, $params);
        if ($response == 2 || $response == 1) {
            $responses["status"] = 200;
            $responses["message"] = "Configuración guardada correctamente";
        } else {
            $responses["status"] = 500;
            $responses["message"] = $response["message"];
        }
        return $responses;
    }

    public function verificarConfiguracion($id_plataforma)
    {
        $sql = "SELECT * FROM configuracion_shopify WHERE id_plataforma = ?";
        $params = array($id_plataforma);
        $response = $this->simple_select($sql, $params);
        if ($response > 0) {
            $response = array(
                "status" => "200",
                "message" => "Configuración encontrada",
            );
        } else {
            $response = array(
                "status" => "500",
                "message" => "No se ha encontrado la configuración",
            );
        }
        return $response;
    }

    public function obtenerCosto($id_producto_venta)
    {
        $sql = "SELECT * FROM inventario_bodegas WHERE id_inventario = ?";
        $params = array($id_producto_venta);
        $response = $this->simple_select($sql, $params);
        return $response[0]['pcp'] ?? 0;
    }

    public function agregarJson($id_plataforma, $data)
    {
        $sql = "INSERT INTO web_hook_shopify (id_plataforma, json) VALUES (?, ?)";
        $params = [$id_plataforma, $data];
        $response = $this->insert($sql, $params);
        if ($response == 1) {
            $responses["status"] = "200";
            $responses["message"] = "Json guardado correctamente";
        } else {
            $responses["status"] = "500";
            $responses["message"] = "Error al guardar el json";
        }
        return $responses;
    }

    public function ultimoJson($id_plataforma)
    {
        $sql = "SELECT json FROM web_hook_shopify WHERE id_plataforma = ? ORDER BY id_wbs DESC LIMIT 1";
        $params = array($id_plataforma);
        $response = $this->simple_select($sql, $params);
        return $response;
    }

    public function modificarConfiguracion($nombre, $apellido, $principal, $secundario, $provincia, $ciudad, $codigo_postal, $pais, $telefono, $email, $total, $descuento, $referencia, $plataforma)
    {
        $sql = "UPDATE configuracion_shopify SET nombre = ?, apellido = ?, principal = ?, secundaria = ?, provincia = ?, ciudad = ?, codigo_postal = ?, pais = ?, telefono = ?, email = ?, total = ?, discount = ?, referencia = ? WHERE id_plataforma = ?";
        $params = [$nombre, $apellido, $principal, $secundario, $provincia, $ciudad, $codigo_postal, $pais, $telefono, $email, $total, $descuento, $referencia, $plataforma];
        $response = $this->update($sql, $params);
        if ($response == 1) {
            $responses["status"] = 200;
            $responses["message"] = "Configuración modificada correctamente";
        } else if ($response == 0) {
            $responses["status"] = 202;
            $responses["message"] = "No se ha modificado la configuración";
        } else {
            $responses["status"] = 500;
            $responses["message"] = $response["message"];
        }
        return $responses;
    }

    public function pagos_laar()
    {
        $sql = "SELECT * FROM cabecera_cuenta_pagar WHERE estado_guia = 7 AND visto = 0";
        $response = $this->select($sql);
        return $response;
    }
}
