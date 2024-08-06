
<?php
class NovedadesModel extends Query
{
    public function __construct()
    {
        parent::__construct();
    }

    public function cargarNovedades($plataforma)
    {
        $sql = "SELECT * FROM novedades where id_plataforma = $plataforma and solucionada = 0";
        $response = $this->select($sql);
        return $response;
    }

    public function solventarNovedad($id_novedad)
    {
        $sql = "UPDATE novedades SET solventada = 1 WHERE id_novedad = $id_novedad";
        $response = $this->select($sql);
        return $response;
    }

    public function solventarNovedadLaar($guia, $ciudad, $nombre, $cedula, $callePrincipal, $calleSecundaria, $numeracion, $referencia, $telefono, $celular, $observacion, $correo, $isDevolucion, $nombreA, $observacionA, $id_novedad)
    {
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

        $data = json_encode($data);

        //token laar GET
        $url  = "https://new.imporsuitpro.com/guias/tokenLaar";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $response = $response;
        $token = $response['token'];

        $url = "https://api.laarcourier.com:9727/guias/datos/actualizar";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: Bearer ' . $token));
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $response = curl_exec($ch);
        $response = json_decode($response, true);

        $this->solventarNovedad($id_novedad);

        return $response;
    }

    public function solventarNovedadServientrega($guia, $observacion, $id_novedad)
    {

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
        <usugenera xsi:type="xsd:string">integracion.api.1</usugenera>
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

        $this->solventarNovedad($id_novedad);

        return $response;
    }
    public function datos($guia)
    {
        $sql = "SELECT * FROM facturas_cot WHERE numero_guia = '$guia'";
        $data1 = $this->select($sql);
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
        return $response;
    }

    public function historial($guia, $id_plataforma, $id_novedad, $medida, $id_usuario)
    {
        $sql = "INSERT INTO `historial_novedad`(`id_responsable`, `guia`, `medida`, `id_plataforma`, `id_novedad`) VALUES (?, ?, ?, ?, ?)";
        $data = array($id_usuario, $guia, $medida, $id_plataforma, $id_novedad);
        $response = $this->insert($sql, $data);
        return $response;
    }
}
