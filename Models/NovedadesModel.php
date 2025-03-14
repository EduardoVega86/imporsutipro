
<?php
class NovedadesModel extends Query
{
    public function __construct()
    {
        parent::__construct();
    }

    public function cargarNovedades($plataforma)
    {
        $sql = "SELECT * 
            FROM novedades 
            WHERE id_plataforma = $plataforma 
            AND NOT ((guia_novedad LIKE 'IMP%' OR guia_novedad LIKE 'MKP%' ) AND estado_novedad IN (97, 108, 118, 57, 44, 56, 53, 52, 123, 121, 51, 10, 54, 119, 109, 55, 99, 120, 104, 122, 93, 111, 3, 8, 98, 15, 113))
            AND NOT (guia_novedad LIKE 'I00%' AND estado_novedad = 6);
            ";

        $response = $this->select($sql);
        return $response;
    }

    public function solventarNovedad($id_novedad, $observacion = "")
    {
        $sql = "UPDATE novedades SET solucionada = 1, solucion_novedad ='$observacion' WHERE id_novedad = $id_novedad";
        $response = $this->select($sql);
        return $response;
    }

    public function solventarNovedadLaar($guia, $ciudad, $nombre, $cedula, $callePrincipal, $calleSecundaria, $numeracion, $referencia, $telefono, $celular, $observacion, $correo, $isDevolucion, $nombreA, $observacionA, $id_novedad)
    {
        $sql_update = "UPDATE `novedades` SET `solucionada` = ? WHERE `guia_novedad` = ?";
        $update_data = [1, $guia];

        // Ejecutar la actualización
        $actualizar_novedad = $this->update($sql_update, $update_data);

        $data = array(
            "guia" => $guia,
            "destino" => array(
                "ciudad" => $ciudad,
                "nombre" => $nombre,
                "cedula" => $cedula,
                "callePrincipal" => $callePrincipal,
                "calleSecundaria" => $calleSecundaria,
                "numeracion" => $numeracion,
                "referencia" => $referencia,
                "telefono" => $telefono,
                "celular" => $celular,
                "observacion" => $observacion,
                "correo" => $correo
            ),
            "autorizado" => array(
                "isDevolucion" => $isDevolucion,
                "nombre" => $nombreA,
                "observacion" => $observacionA
            )
        );
        $dataJson = json_encode($data);

        // Obtener token Laar
        $urlToken  = "https://new.imporsuitpro.com/guias/tokenLaar";
        $chToken = curl_init($urlToken);
        curl_setopt($chToken, CURLOPT_RETURNTRANSFER, true);
        $tokenResponse = curl_exec($chToken);
        curl_close($chToken);

        $token = $tokenResponse;

        // Actualizar datos en Laar
        $urlActualizar = "https://api.laarcourier.com:9727/guias/datos/actualizar";
        $chActualizar = curl_init($urlActualizar);
        curl_setopt($chActualizar, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($chActualizar, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: Bearer ' . $token));
        curl_setopt($chActualizar, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($chActualizar, CURLOPT_POSTFIELDS, $dataJson);
        $response = curl_exec($chActualizar);
        curl_close($chActualizar);

        $responseArray = json_decode($response, true);

        // Mostrar respuesta para depuración


        // Verificar si la respuesta es positiva antes de llamar a solventarNovedad
        if (isset($responseArray['data']['codigo']) && $responseArray['data']['codigo'] == 1) {
            // Llamar a solventarNovedad si la respuesta es positiva
            $this->solventarNovedad($id_novedad, $observacion);
        } else {
            $responseArray = array(
                "status" => 400,
                "message" =>  $responseArray["Message"]
            );
        }

        return $responseArray;
    }


    public function solventarNovedadServientrega($guia, $observacion, $id_novedad)
    {

        $sql_update = "UPDATE `novedades` SET `solucionada` = ? WHERE `guia_novedad` = ?";
        $update_data = [1, $guia];

        // Ejecutar la actualización
        $actualizar_novedad = $this->update($sql_update, $update_data);

        $url = "https://servientrega-ecuador.appsiscore.com/app/ws/confirmaciones.php?wsdl";

        $xml = <<<XML
        <soapenv:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/"
        xmlns:ws="https://servientrega-ecuador.appsiscore.com/app/ws">
        <soapenv:Header/>
        <soapenv:Body>
        <ws:getXML soapenv:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/">
        <guia xsi:type="xsd:string">$guia</guia>
        <observacion xsi:type="xsd:string">$observacion</observacion>
        <usugenera xsi:type="xsd:string">imp.1793168264001</usugenera>
        <usu xsi:type="xsd:string">IMPCOMEX</usu>
        <pwd xsi:type="xsd:string">Rtcom-ex9912</pwd>
        <tokn xsi:type="xsd:string">1593aaeeb60a560c156387989856db6be7edc8dc220f9feae3aea237da6a951d</tokn>
        </ws:getXML>
        </soapenv:Body>
        </soapenv:Envelope>
        XML;

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);


        curl_close($ch);

        $this->solventarNovedad($id_novedad, $observacion);

        return $response;
    }
    public function datos($guia)
    {
        $sql = "SELECT *, (SELECT codigo_ciudad_laar FROM ciudad_cotizacion where id_cotizacion = ciudad_cot) as ciudad FROM facturas_cot WHERE numero_guia= '$guia'";


        $data1 = $this->select($sql);

        //buscar ciudad


        $sql = "SELECT * FROM novedades WHERE guia_novedad = '$guia'";
        $data2 = $this->select($sql);
        $data = array(
            "factura" => $data1,
            "novedad" => $data2
        );
        return $data;
    }

    public function solventarNovedadGintracom($tipo, $guia, $observacion, $id_novedad, $recaudo, $fecha)
    {
        $sql_update = "UPDATE `novedades` SET `solucionada` = ? WHERE `guia_novedad` = ?";
        $update_data = [1, $guia];

        // Ejecutar la actualización
        $actualizar_novedad = $this->update($sql_update, $update_data);

        $data = array(
            "guia" => $guia,
            "observacion" => $observacion,
        );

        if ($tipo == "ofrecer") {
            $data["solucion"] = "Volver a Ofrecer";
            $data["fecha_entrega"] = $fecha;
        } else if ($tipo == "rechazar") {
            $data["solucion"] = "Efectuar devolución";
            $data["fecha_entrega"] = $fecha;
        } else if ($tipo == "recaudo") {
            $data["solucion"] = "Ajustar Recaudo";
            $data["recaudo"] = $recaudo;
            $data["fecha_entrega"] = $fecha;
        }


        $data = json_encode($data);
        $url = "https://ec.gintracom.site/web/import-suite/solucion";
        $username = "importsuite";
        $password = "ab5b809caf73b2c1abb0e4586a336c3a";

        $hashed = base64_encode($username . ":" . $password);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $response = curl_exec($ch);
        curl_close($ch);

        $this->solventarNovedad($id_novedad, $observacion);

        return $response;
    }

    public function historial($guia, $id_plataforma, $id_novedad, $medida, $id_usuario, $data)
    {
        $sql = "INSERT INTO `historial_novedad`(`id_responsable`, `guia`, `medida`, `id_plataforma`, `id_novedad`, `respuesta_transportadora`) VALUES (?, ?, ?, ?, ?, ?)";
        $data = array($id_usuario, $guia, $medida, $id_plataforma, $id_novedad, $data);
        $response = $this->insert($sql, $data);
        return $response;
    }

    public function cargarHistorial($plataforma, $guia)
    {
        $sql = "SELECT *, (SELECT nombre_users from users where id_users = id_responsable) as nombre_responsable FROM historial_novedad where id_plataforma = '$plataforma' AND guia = '$guia';";
        $response = $this->select($sql);
        return $response;
    }







    //////////8/// DEBUGGING ///////*///////
    public function revision()
    {
        $sql = "SELECT * FROM novedades where terminado= 0 and guia_novedad like 'IMP%";

        $response = $this->select($sql);

        ///debugging
        $url = "https://api.laarcourier.com:9727/guias/";
        foreach ($response as $r) {
            $guia = $r['guia_novedad'];
            $ch = curl_init($url . $guia);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            $response = json_decode($response, true);
        }
        return $response;
    }
}
