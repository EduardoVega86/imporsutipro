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
        var_dump($resultados);
        var_dump($lineItems);

        //gestion de creacion de orden
        $orden = $this->crearOrden($resultados, $lineItems);
    }

    public function crearOrden($data, $lineItems)
    {
        $total_venta = $data['total'];
        $nombre = $data['nombre'] . " " . $data['apellido'];
        $telefono = $data['telefono'];
        ///quitar el + de la cadena
        $telefono = str_replace("+", "", $telefono);
        $calle_principal = $data['principal'];
        $calle_secundaria = $data['secundario'];
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
        $id_producto_venta = $lineItems["sku"];
        $importado = 0;
        $plataforma_importa = "Shopify";
        $recaudo = 1;

        //origen

        $datos_telefono = $this->obtenerBodegaInventario($id_producto_venta);
        $bodega = $datos_telefono[0];

        $celularO =  $bodega['contacto'];
        $nombreO = $bodega['nombre'];
        $ciudadO    = $bodega['localidad'];
        $provinciaO = $bodega['provincia'];
        $direccionO     = $bodega['direccion'];
        $referenciaO   = $bodega['referencia'] ?? " ";
        $numeroCasaO = $bodega['num_casa'] ?? " ";
        $valor_segura = 0;

        $no_piezas = 1;
        $contiene = "";
        $costo_flete = 0;
        $costo_producto     = 0;
        $id_transporte = 0;
    }

    public function obtenerBodegaInventario($id_producto_venta)
    {
        $sql = "SELECT * FROM inventario_bodegas WHERE id_producto = $id_producto_venta";
        $response = $this->select($sql);
        $bodega = $response[0]['bodega'];
        $sql2 = "SELECT * FROM bodega WHERE id_bodega = $bodega";
        $response2 = $this->select($sql2);
        return $response2;
    }

    public function obtenerProvincia($provincia)
    {
        $sql = "SELECT * FROM provincia_laar WHERE provincia = $provincia";
        $response = $this->select($sql);
        return $response;
    }

    public function obtenerCiudad($ciudad)
    {
        $sql = "SELECT * FROM ciudad_cotizacion WHERE ciudad = $ciudad";
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

    public function guardarConfiguracion($id_plataforma, $data)
    {
        $sql = "INSERT INTO configuracion_shopify (id_plataforma, nombre, apellido, principal, secundario, provincia, ciudad, codigo_postal, pais, telefono, email, total, descuento) VALUES (?, ?, ?, ? ,?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $response = $this->insert($sql, $data);
        if ($response == 1) {
            $response["status"] = "200";
            $response["message"] = "Configuracion guardada correctamente";
        } else {
            $response["status"] = "500";
            $response["message"] = "Error al guardar la configuracion";
        }
        return $response;
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
