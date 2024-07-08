<?php
class ShopifyModel extends Query
{
    public function __construct()
    {
        parent::__construct();
    }


    public function gestionarRequest($plataforma, $data)
    {
        $data = json_decode($data, true);
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

        // Mostrar resultados (para depuración)
        /*   var_dump($resultados);
        var_dump($lineItems);
 */
        // Gestión de creación de orden
        $orden = $this->crearOrden($resultados, $lineItems, $plataforma);
    }

    public function crearOrden($data, $lineItems, $plataforma)
    {
        $total_venta = $data['total'];
        print_r($data["total"]);
        $nombre = $data['nombre'] . " " . $data['apellido'];
        $telefono = $data['telefono'];
        // Quitar el + de la cadena
        $telefono = str_replace("+", "", $telefono);
        $calle_principal = $data['principal'];
        $calle_secundaria = $data['secundario'] ?? "";
        $provincia = $data['provincia'];
        $provincia = $this->obtenerProvincia($provincia);
        $provincia = $provincia[0]['codigo_provincia'];
        $ciudad = $data['ciudad'];
        $ciudad = $this->obtenerCiudad($ciudad);
        if (!empty($ciudad)) {
            $ciudad = $ciudad[0]['id_cotizacion'];
        } else {
            $ciudad = 0;
        }
        $referencia = "Referencia: ";
        $observacion = "Ciudad: " . $data["ciudad"];
        $transporte = 0;
        $importado = 0;
        $plataforma_importa = "Shopify";
        $recaudo = 1;

        $contiene = "";
        $costo_producto = 0;
        // Procesar cada producto en lineItems
        $productos = [];



        // Recorre los items y verifica las condiciones necesarias
        foreach ($lineItems as $item) {
            if (empty($item['sku'])) {
                // Si solo hay un ítem y no tiene SKU, detiene el proceso
                if (count($lineItems) == 1) {
                    die("Proceso detenido: el único ítem no tiene SKU.");
                }

                // Si el SKU está vacío, salta al siguiente ítem
                $observacion .= ", SKU vacío: " . $item['name'] . " x" . $item['quantity'] . ": $" . $item['price'] . " ";
                continue;
            }


            $id_producto_venta = $item['sku'];

            // Obtener información de la bodega
            echo $id_producto_venta;
            $datos_telefono = $this->obtenerBodegaInventario($id_producto_venta);
            print_r($datos_telefono);
            $bodega = $datos_telefono[0];

            $celularO = $bodega['contacto'];
            $nombreO = $bodega['nombre'];
            $ciudadO = $bodega['localidad'];
            $provinciaO = $bodega['provincia'];
            $direccionO = $bodega['direccion'];
            $referenciaO = $bodega['referencia'] ?? " ";
            $numeroCasaO = $bodega['num_casa'] ?? " ";
            $valor_segura = 0;

            $no_piezas = $item['quantity'];
            $contiene .= $item['name'] . " x" . $item['quantity'] . " ";

            $costo_flete = 0;
            $costo_producto += $item['price'] * $item['quantity'];
            $id_transporte = 0;

            $productos[] = [
                'id_producto_venta' => $id_producto_venta,
                'nombre' => $item['name'],
                'cantidad' => $item['quantity'],
                'precio' => $item['price'],
            ];

            // Aquí puedes añadir el código para guardar la orden en la base de datos
        }
        $comentario = "Orden creada desde Shopify";

        $contiene = trim($contiene); // Eliminar el espacio extra al final

        // Aquí se pueden continuar los procesos necesarios para la orden
        ///iniciar curl
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
            'celularO' => $celularO,
            'nombreO' => $nombreO,
            'ciudadO' => $ciudadO,
            'provinciaO' => $provinciaO,
            'direccionO' => $direccionO,
            'referenciaO' => $referenciaO,
            'numeroCasaO' => $numeroCasaO,
            'valor_segura' => $valor_segura,
            'no_piezas' => $no_piezas,
            'tipo_servicio' => "201202002002013",
            'peso' => "2",
            'contiene' => $contiene,
            'costo_flete' => $costo_flete,
            'costo_producto' => $costo_producto,
            'comentario' => $comentario,
            'id_transporte' => $id_transporte,
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
            'id_producto_venta' => $id_producto_venta,
            'productos' => $productos,
        );

        $data = http_build_query($data);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        //print_r($response);
        /*  $datos = json_decode($response, true);
        $numero_factura = $datos['numero_factura'];

        // obtener datos del producto
        $ch = curl_init();
        $url = SERVERURL . "marketplace/obtener_producto/" . $id_producto_venta;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        $datos = json_decode($response, true);
 */
        // Como guardar en base de datos, enviar notificaciones, etc.
    }

    public function obtenerBodegaInventario($id_producto_venta)
    {
        $sql = "SELECT * FROM inventario_bodegas WHERE id_producto = $id_producto_venta";
        echo $sql;
        $response = $this->select($sql);
        $bodega = $response[0]['bodega'];
        $sql2 = "SELECT * FROM bodega WHERE id = $bodega";
        $response2 = $this->select($sql2);
        return $response2;
    }

    public function obtenerProvincia($provincia)
    {

        $sql = "SELECT * FROM provincia_laar WHERE provincia = '$provincia'";
        echo $sql;
        $response = $this->select($sql);
        return $response;
    }

    public function obtenerCiudad($ciudad)
    {
        $sql = "SELECT * FROM ciudad_cotizacion WHERE ciudad = '$ciudad'";
        $response = $this->select($sql);
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
        $sql = "SELECT * FROM configuracion_shopify WHERE id_plataforma = $id_plataforma";
        $response = $this->select($sql);
        return $response;
    }

    public function iniciarPlataforma($id_plataforma)
    {
        $sql = "INSERT INTO shopify (id_plataforma) VALUES (:id_plataforma)";
    }

    public function existenciaPlataforma($id_plataforma)
    {
        $sql = "SELECT id_plataforma FROM configuracion_shopify WHERE id_plataforma =?";
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
        $sql = "SELECT id_matriz from plataformas where id_plataforma = $id_plataforma";
        $response = $this->select($sql);
        $url = $response[0]["id_matriz"];

        $sql = "SELECT * FROM matriz WHERE idmatriz = $url";
        $response = $this->select($sql);
        $url = $response[0]["url_matriz"];

        $responses = array(
            "url_imporsuit" =>  $url . "shopify/index/" . $id_plataforma,
        );
        return $responses;
    }

    public function guardarConfiguracion($nombre, $apellido, $principal, $secundario, $provincia, $ciudad, $codigo_postal, $pais, $telefono, $email, $total, $descuento, $id_plataforma)
    {
        $sql = "REPLACE INTO configuracion_shopify (`nombre`, `apellido`, `principal`, `secundaria`, `provincia`, `ciudad`, `codigo_postal`, `pais`, `telefono`, `email`, `total`, `discount`, `id_plataforma`) VALUES (?, ?, ?, ? ,?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $response = $this->insert($sql, [$nombre, $apellido, $principal, $secundario, $provincia, $ciudad, $codigo_postal, $pais, $telefono, $email, $total, $descuento, $id_plataforma]);
        if ($response == 2 || $response == 1) {
            $responses["status"] = 200;
            $responses["message"] = "Configuracion guardada correctamente";
        } else {
            $responses["status"] = 500;
            $responses["message"] = "Error al guardar la configuracion";
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
                "message" => "Configuracion encontrada",
            );
        } else {
            $response = array(
                "status" => "500",
                "message" => "No se ha encontrado la configuracion",
            );
        }
        return $response;
    }

    public function agregarJson($id_plataforma, $data)
    {
        $sql = "INSERT INTO web_hook_shopify (id_plataforma, json) VALUES (?, ?)";
        $response = $this->insert($sql, [$id_plataforma, $data]);
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
        $sql = "SELECT json FROM web_hook_shopify WHERE id_plataforma = $id_plataforma ORDER BY id_wbs DESC LIMIT 1;";
        $response = $this->select($sql);
        return $response;
    }
}
