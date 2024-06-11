<?php
class GuiasModel extends Query
{
    public function __construct()
    {
        parent::__construct();
    }

    public function obtenerDestinatario($id_producto)
    {
        $sql = "SELECT * FROM inventario_bodegas WHERE id_inventario = $id_producto";
        return $this->select($sql);
    }

    public function generarLaar($nombreOrigen, $ciudadOrigen, $direccionOrigen, $telefonoOrigen, $referenciaOrigen, $celularOrigen, $nombreDestino, $ciudadDestino, $direccionDestino, $telefonoDestino, $celularDestino, $referenciaDestino, $postal, $identificacion, $contiene, $peso, $valor_seguro, $valor_declarado, $tamanio, $cod, $costoflete, $costo_producto, $tipo_cobro, $comentario, $fecha, $extras)
    {
        $numero_guia = $this->ultimaguia();
        $datos = array(
            "origen" => array(
                "identificacionO" => $identificacion,
                "nombreO" => $nombreOrigen,
                "ciudadO" => $ciudadOrigen,
                "direccionO" => $direccionOrigen,
                "telefonoO" => $telefonoOrigen,
                "referenciaO" => $referenciaOrigen,
                "celularO" => $celularOrigen,
                "postal" => $postal,
                "numeroCasa" => "0"
            ),
            "destino" => array(
                "identificacionD" => "0",
                "nombreD" => $nombreDestino,
                "ciudadD" => $ciudadDestino,
                "direccionD" => $direccionDestino,
                "telefonoD" => $telefonoDestino,
                "celularD" => $celularDestino,
                "referenciaD" => $referenciaDestino,
                "postal" => $postal,
                "numeroCasa" => "0"
            ),
            "numeroGuia" => $numero_guia,
            "tipoServicio" => "201202002002013",
            "noPiezas" => 1,
            "peso" => $peso,
            "valorDeclarado" => $valor_declarado,
            "contiene" => $contiene,
            "tamanio" => $tamanio,
            "cod" => $cod,
            "costoflete" => $costoflete,
            "costoproducto" => $costo_producto,
            "tipoCobro" => $tipo_cobro,
            "comentario" => $comentario,
            "fechaPedido" => $fecha,
            "extras" => array(
                "Campo1" => "",
                "Campo2" => "",
                "Campo3" => "",
            )
        );

        //iniciar curl
        $token = $this->laarToken();
        $ch = curl_init(LAAR_ENDPOINT);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($datos));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $token
        ));

        //execute post
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error en la solicitud cURL: ' . curl_error($ch);
        }
        curl_close($ch);
        return $result;
    }

    public function ultimaguia()
    {
        $sql = "SELECT MAX(numero_guia) as numero_guia FROM facturas_cot";
        $numero_guia = $this->select($sql);
        $numero_guia = $numero_guia[0]['numero_guia'];
        if ($numero_guia == null || empty($numero_guia)) {
            $numero_guia = "IMP000001";
        }
        return $numero_guia;
    }

    public function laarToken()
    {
        $ch = curl_init(LAAR_ENDPOINT_AUTH);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $auth = json_encode(array(
            "username" => LAAR_USER,
            "password" => LAAR_PASSWORD
        ));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $auth);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($auth)
        ));
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error en la solicitud cURL para obtener el token: ' . curl_error($ch);
        }
        curl_close($ch);
        return $response;
    }

    public function obtenerCiudadLaar($ciudad)
    {
        $sql = "SELECT codigo_ciudad_laar FROM ciudad_cotizacion WHERE id_cotizacion = '$ciudad'";
        $ciudad = $this->select($sql);
        return $ciudad[0]['codigo_ciudad_laar'];
    }
}
