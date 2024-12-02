<?php

class FunnelishModel extends Query
{
    public function __construct()
    {
        parent::__construct();
    }

    public function saveData($data)
    {
        $sql = "INSERT INTO `funnel_log` (`json`) VALUES (?);";
        $params = [json_encode($data)];
        return $this->insert($sql, $params);
    }
    public function saveDataPlatform($data, $id_plataforma)
    {
        $sql = "INSERT INTO `funnel_log` (`json`, `id_plataforma`) VALUES (?, ?);";
        $params = [$data, $id_plataforma];
        return $this->insert($sql, $params);
    }

    public function existenciaPlataforma($id_plataforma)
    {
        echo $id_plataforma . "-";
        $sql = "SELECT * FROM productos_funnel WHERE id_plataforma = ?";
        $response = $this->simple_select($sql, [$id_plataforma]);
        print_r($response);
        if ($response > 0) {

            return true;
        } else {
            return false;
        }
    }

    public function listar($id_plataforma)
    {
        $sql = "SELECT pf.id_funnel as 'codigo_funnelish', pf.id_producto as 'codigo_producto', p.nombre_producto, pf.id FROM `productos_funnel` pf INNER JOIN `inventario_bodegas` ib ON pf.id_producto = ib.id_inventario INNER JOIN `productos` p ON ib.id_producto = p.id_producto WHERE pf.id_plataforma = $id_plataforma; ";
        $response = $this->select($sql);
        return $response;
    }

    public function productoPlataforma($id_plataforma, $data)
    {
        $data = json_decode($data, true);
        if (isset($data["products"]) && is_array($data["products"])) {
            $sql = "SELECT id_producto FROM productos_funnel WHERE id_funnel = ? AND id_plataforma = ?";
            foreach ($data["products"] as $product) {

                $response = $this->simple_select($sql, [$product["id"], $id_plataforma]);
                if ($response > 0) {
                    return true;
                }
            }
            return false;
        } else {
            return false;
        }
    }

    public function gestionarRequest($id_plataforma, $data)
    {
        $json_string = mb_convert_encoding($data, 'UTF-8', 'UTF-8');
        $json = json_decode($json_string, true);
        $products = $json["products"];
        $orden = $this->crearOrden($id_plataforma, $json, $products);
    }

    public function crearOrden($id_plataforma, $json, $products)
    {
        $orden = $this->insertarOrden($id_plataforma, $json);
    }

    public function insertarOrden($id_plataforma, $json)
    {
        $nombre = $json["first_name"] . " " . $json["last_name"];
        $telefono = str_replace("+", "", $json["phone"]);
        // detectar un "y", "," o "&" en la dirección para separarla en dos
        // Definir los separadores que deseas considerar
        $direccion = $json["shipping_address"];
        $separadores = '/,|&| y | Y | e | E | o | O | a | A | en | En | al | Al | para | Para | por | Por /';

        // Usar preg_split para dividir la dirección
        $partes_direccion = preg_split($separadores, $direccion, 2, PREG_SPLIT_NO_EMPTY);

        // Asignar calle principal y secundaria
        $calle_principal = trim($partes_direccion[0]);

        if (isset($partes_direccion[1])) {
            $calle_secundaria = trim($partes_direccion[1]);
        } else {
            $calle_secundaria = ' ';
        }

        $provincia = $json["shipping_state"];
        $ciudad = $json["shipping_city"];

        $provincia = $this->obtenerProvincia($provincia);
        $provincia = $provincia[0]["codigo_provincia"];
        $ciudad = $this->obtenerCiudad($ciudad);
        if (!empty($ciudad)) {
            $ciudad = $ciudad[0]['id_cotizacion'];
        } else {
            $ciudad = 0;
        }

        $referencia = " ";
        $observaciones = "Ciudad: " . $json["shipping_city"] . " Provincia: " . $json["shipping_state"];
        $transporte = 0;
        $importado = 1;
        $plataforma_importa = "Funnelish";
        $recaudo = 1;

        $contiene = "";
        $costo_producto = 0;

        $productos = [];
        $productosSinSkus = [];
        $total = 0;
        $totalSinSkus = 0;
        $costo = 0;
        $total_units = 0;

        $primerProductoConSku = null;


        // recorre los productos y verifica las condiciones
        foreach ($json["products"] as $product) {
            $existe = $this->existeProducto($product["id"]);
            if ($existe) {
                $id_producto_venta = $this->buscarProducto($product["id"])["id_inventario"];
                $datos_telefono = $this->obtenerBodegaInventario($id_producto_venta);
                $producto_costo = $this->obtenerCosto($id_producto_venta);
                $costo += $producto_costo * $product["qty"];
                $bodega = $datos_telefono[0];

                $celularO = $bodega["contacto"];
                $nombreO = $bodega["nombre"];
                $ciudadO = $bodega["localidad"];
                $provinciaO = $bodega["provincia"];
                $direccionO = $bodega["direccion"];
                $referenciaO = $bodega["referencia"] ?? " ";
                $numeroCasaO = $bodega["num_casa"] ?? " ";
                $valor_segura = 0;

                $id_bodega = $bodega["id"];
                $no_piezas = $product["qty"];
                $contiene .= $product["name"] . " x " . $product["qty"] . " ";

                $costo_flete = 0;
                $costo_producto += $product["amount"] * $product["qty"];
                $id_transporte = 0;

                $items_total = $product["amount"] * $product["qty"];
                $total += $items_total;
                $total_units += $product["qty"];

                $productos[] = [
                    "id_producto_venta" => $id_producto_venta,
                    "nombre" => $product["name"],
                    "cantidad" => $product["qty"],
                    "precio" => $product["amount"],
                    "item_total_price" => $items_total,
                ];
            } else {
                $productosSinSkus[] = [
                    "id_producto_venta" => null,
                    "nombre" => $product["name"],
                    "cantidad" => $product["qty"],
                    "precio" => $product["amount"],
                    "item_total_price" => $product["amount"] * $product["qty"],
                ];
                $totalSinSkus += $product["amount"] * $product["qty"];
            }
        }


        if (count($productosSinSkus) > 0) {
            foreach ($productosSinSkus as $productoS) {
                $cantidadProductos = $productos[0]["cantidad"];
                $divisible = $productoS["item_total_price"] / $cantidadProductos;

                foreach ($productos as &$productoA) { // Usamos referencia aquí
                    $productoA["precio"] += $divisible;
                    $productoA["item_total_price"] = $productoA["precio"] * $productoA["cantidad"];
                }
                unset($productoA); // Rompemos la referencia
            }
        }

        // Recalcular el total de la venta después de ajustar los precios
        $total_venta = 0;
        foreach ($productos as $producto) {
            $total_venta += $producto["item_total_price"];
        }

        $comentario = "Orden generada por Funnelish, numero de orden: " . $json["id"];

        $contiene = trim($contiene);

        if (count($productosSinSkus) > 0) {
            $observacion = "Productos sin SKU: ";
            $observaciones = [];

            foreach ($productosSinSkus as $producto) {
                $observaciones[] = $producto["nombre"] . " x " . $producto["cantidad"];
            }

            $observacion .= implode(", ", $observaciones);
        } else {
            $observacion = "";
        }

        print_r($productos);

        $observacion .= " Numero de orden Funnelish: " . $json["id"];

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
            'dueño_id' => $id_plataforma,
            'dropshipping' => 0,
            'id_plataforma' =>  $id_plataforma,
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
            'costo_producto' => $costo,
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
            'id_bodega' => $id_bodega,
        );

        $data = http_build_query($data);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        // Puedes manejar la respuesta aquí si es necesario

        echo $response;
    }

    public function obtenerCosto($id_producto_venta)
    {
        $sql = "SELECT * FROM inventario_bodegas WHERE id_inventario = $id_producto_venta";
        $response = $this->select($sql);
        return $response[0]['pcp'];
    }

    public function obtenerBodegaInventario($id_producto_venta)
    {
        $sql = "SELECT * FROM inventario_bodegas WHERE id_inventario = $id_producto_venta";
        $response = $this->select($sql);
        $bodega = $response[0]['bodega'];
        $sql2 = "SELECT * FROM bodega WHERE id = $bodega";
        $response2 = $this->select($sql2);
        return $response2;
    }
    public function buscarProducto($sku)
    {
        $sql = "SELECT id_producto FROM productos_funnel WHERE id_funnel = ?";
        $response = $this->dselect($sql, [$sku]);

        $id_producto = $response[0]["id_producto"];
        $sql = "SELECT * FROM inventario_bodegas WHERE id_inventario = ?";
        $response = $this->dselect($sql, [$id_producto]);

        return $response[0];
    }

    public function existeProducto($sku)
    {
        $sql = "SELECT id_producto FROM productos_funnel WHERE id_funnel = ?";
        $response = $this->simple_select($sql, [$sku]);
        if ($response > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function obtenerProvincia($provincia)
    {
        $sql = "SELECT * FROM provincia_laar WHERE provincia = '$provincia'";
        $response = $this->select($sql);
        return $response;
    }

    public function obtenerCiudad($ciudad)
    {
        $sql = "SELECT * FROM ciudad_cotizacion WHERE ciudad = '$ciudad'";
        $response = $this->select($sql);
        return $response;
    }

    public function get_productos($id_plataforma)
    {
        $sql = "select ib.id_inventario, ib.id_producto, p.nombre_producto, pf.id_funnel, p.image_path, pf.id_plataforma from  `productos_funnel` pf
        inner join inventario_bodegas ib
        on pf.id_producto = ib.id_inventario
        inner join productos p
        on p.id_producto = ib.id_producto where pf.id_plataforma = $id_plataforma";
        $response = $this->select($sql);

        return $response;
    }

    public function ultimoProductos($id_inventario, $id_plataforma)
    {
        // 1. Buscar el último registro asignado
        $sql = "SELECT * FROM funnel_links WHERE id_plataforma = ? AND asignado = 1 ORDER BY id_registro DESC LIMIT 1";
        $resAsignado = $this->dselect($sql, [$id_plataforma]);

        if (count($resAsignado) > 0) {
            // Si hay un registro asignado, obtener su id_registro
            print_r($resAsignado);
            $ultimoRegistro = $resAsignado[0]['id_registro'];
        } else {
            // 2. Si no hay asignados, buscar el último sin asignar
            $sql = "SELECT * FROM funnel_links WHERE id_plataforma = ? AND asignado = 0 ORDER BY id_registro DESC LIMIT 1";
            $resNoAsignado = $this->dselect($sql, [$id_plataforma]);

            if (count($resNoAsignado) > 0) {
                // Si hay un registro no asignado, obtener su id_registro
                $ultimoRegistro = $resNoAsignado[0]['id_registro'];
            } else {
                // 3. Si no hay registros, crear el primer producto con id_registro = 0
                $ultimoRegistro = 0;
                $sql = "INSERT INTO funnel_links (id_plataforma, id_inventario, id_registro, asignado) VALUES (?, ?, ?, ?)";
                $data = [$id_plataforma, $id_inventario, $ultimoRegistro, 0];
                $resInsert = $this->insert($sql, $data);

                if ($resInsert == 1) {
                    return [
                        "status" => 200,
                        "mensaje" => "Primer producto",
                        "enlace" => SERVERURL . "funnelish/index/" . $id_plataforma . '/0'
                    ];
                } else {
                    return [
                        "status" => 400,
                        "mensaje" => "No se pudo crear el primer producto, intente nuevamente."
                    ];
                }
            }
        }

        // Verificar si el producto con el último id_registro está asignado
        $sql = "SELECT * FROM funnel_links WHERE id_plataforma = ? AND id_registro = ? AND asignado = 1";
        $resVerificacion = $this->dselect($sql, [$id_plataforma, $ultimoRegistro]);

        if (count($resVerificacion) == 0) {
            // Producto aún no asignado
            return [
                "status" => 200,
                "mensaje" => "Producto aún no asignado",
                "enlace" => SERVERURL . "funnelish/index/" . $id_plataforma . '/' . $ultimoRegistro
            ];
        } else {
            // Crear un nuevo registro incrementando id_registro
            $nuevoRegistro = $ultimoRegistro + 1;
            $sql = "INSERT INTO funnel_links (id_plataforma, id_inventario, id_registro, asignado) VALUES (?, ?, ?, ?)";
            $data = [$id_plataforma, $id_inventario, $nuevoRegistro, 0];
            $resNuevoInsert = $this->insert($sql, $data);

            if ($resNuevoInsert == 1) {
                return [
                    "status" => 200,
                    "mensaje" => "Producto nuevo",
                    "enlace" => SERVERURL . "funnelish/index/" . $id_plataforma . '/' . $nuevoRegistro
                ];
            } else {
                return [
                    "status" => 400,
                    "mensaje" => "Producto no pudo ser asignado, intente nuevamente."
                ];
            }
        }
    }

    public function buscarPedido($id_plataforma, $id_registro)
    {
        $sql = "SELECT * FROM funnel_log where id_plataforma = ? and id_registro = ?";
        $res = $this->dselect($sql, [$id_plataforma, $id_registro]);

        if (count($res) > 0) {
            $json_data = $res[0]["json"];
            $data = json_decode($json_data, true);

            if ($data && isset($data[0]['id'])) {
                $product_id = $data['products'][0]['id'];
                return [
                    "status" => 200,
                    "encontrado" => true,
                    "id_producto" => $product_id
                ];
            }
        }
        return [
            "status" => 200,
            "encontrado" => false,
        ];
    }
}
