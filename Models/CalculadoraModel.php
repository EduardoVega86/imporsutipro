<?php
class CalculadoraModel extends Query
{
    public function obtenerTarifas($ciudad, $provincia, $monto_factura, $recuado)
    {
        $select = $this->select("SELECT * FROM ciudad_cotizacion WHERE id_cotizacion = '$ciudad' ");
        $tarifas = [];
        $trayecto_laar = $select[0]['trayecto_laar'];
        $trayecto_servientrega = $select[0]['trayecto_servientrega'];
        $trayecto_gintracom = $select[0]['trayecto_gintracom'];
        $ciudad = $select[0]['ciudad'];
        $precio_laar = $this->select("SELECT * from cobertura_laar WHERE tipo_cobertura = '$trayecto_laar' ");
        $precio_servientrega = $this->select("SELECT * from cobertura_servientrega WHERE tipo_cobertura = '$trayecto_servientrega' ");
        $precio_gintracom = $this->select("SELECT * from cobertura_gintracom WHERE trayecto = '$trayecto_gintracom' ");

        if ($precio_laar[0]['precio'] == null || empty($precio_laar[0]['precio'])) {
            $precio_laar[0]['precio'] = 0;
        } else {
            $tarifas['laar'] = $precio_laar[0]['precio'];
        }
        if ($precio_servientrega[0]['precio'] == null || empty($precio_servientrega[0]['precio'])) {
            $precio_servientrega[0]['precio'] = 0;
        } else {
            $tarifas['servientrega'] = $precio_servientrega[0]['precio'];
        }
        if ($precio_gintracom[0]['precio'] == null || empty($precio_gintracom[0]['precio'])) {
            $precio_gintracom[0]['precio'] = 0;
        } else {
            $tarifas['gintracom'] = $precio_gintracom[0]['precio'];
        }

        $matriz = $this->obtenerMatriz();
        $matriz = $matriz[0]["idmatriz"];

        $previo = $monto_factura * 0.03;
        if ($trayecto_laar === "0" || $trayecto_laar === null) {
            $tarifas['laar'] = 0;
        } else {
            if ($recuado === "1") {

                $tarifas['laar'] = $tarifas['laar'] + $previo;
            } else {
                $tarifas['laar'] = $tarifas['laar'];
            }
        }
        if ($trayecto_gintracom === "0" || $trayecto_gintracom === null) {
            $tarifas['gintracom'] = 0;
        } else {
            if ($recuado === "1") {
                $tarifas['gintracom'] = $tarifas['gintracom'] + $previo;
            } else {
                $tarifas['gintracom'] = $tarifas['gintracom'];
            }
        }
        if ($previo < 1.25) {
            $previo = 1.25;
        }
        if ($trayecto_servientrega === "0" || $trayecto_servientrega === null) {
            $tarifas['servientrega'] = 0;
        } else {
            if ($recuado === "1") {
                $tarifas['servientrega'] = $tarifas['servientrega'] + $previo;
            } else {
                $tarifas['servientrega'] = $tarifas['servientrega'];
            }
        }

        if ($ciudad == "QUITO") {
            $tarifas["speed"] = 5.5;
        } else if ($ciudad == "VALLE DE LOS CHILLOS") {
            $tarifas["speed"] = 6.5;
        } else if ($ciudad == "CUMBAYA") {
            $tarifas["speed"] = 6.5;
        } else if ($ciudad == "TUMBACO") {
            $tarifas["speed"] = 6.5;
        } else if ($ciudad == "SANGOLQUI") {
            $tarifas["speed"] = 6.5;
        } else if ($ciudad == "PIFO") {
            $tarifas["speed"] = 6.5;
        } else if ($ciudad == "SAN RAFAEL") {
            $tarifas["speed"] = 6.5;
        } else if ($ciudad == "CONOCOTO") {
            $tarifas["speed"] = 6.5;
        } else {
            $tarifas["speed"] = 0;
        }
        if ($matriz == 2) {
            $tarifas['laar'] = 5.99;
        }

        return $tarifas;
    }


    public function saldo($plataforma)
    {
        $select = $this->select("SELECT * FROM plataformas WHERE id_plataforma = '$plataforma' ");
        $url = $select[0]['url_imporsuit'];

        $billetera = $this->select("SELECT * FROM billeteras WHERE tienda = '$url' ");
        if (empty($billetera)) {
            $saldo = 0;
        } else {
            $saldo = $billetera[0]['saldo'];
        }
        return $saldo;
    }

    public function calcularServi($ciudadO, $ciudadD, $provinciaD, $monto_factura)
    {
        if (strpos($ciudadD, "/") !== false) {
            $destino = $ciudadD . " (" . $provinciaD . ")-" . $provinciaD;
        } else {
            $destino = $ciudadD . "-" . $provinciaD;
        }

        $url = "https://servientrega-ecuador.appsiscore.com/app/ws/cotizador_ser_recaudo.php?wsdl";

        $xml = <<<XML
    <soapenv:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ws="https://servientrega-ecuador.appsiscore.com/app/ws/">
    <soapenv:Header/>
    <soapenv:Body>
        <ws:Consultar soapenv:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/">
            <producto xsi:type="xsd:string">MERCANCIA PREMIER</producto>
            <origen xsi:type="xsd:string">$ciudadO</origen>
            <destino xsi:type="xsd:string">$destino</destino>
            <valor_mercaderia xsi:type="xsd:string">$monto_factura</valor_mercaderia>
            <piezas xsi:type="xsd:string">1</piezas>
            <peso xsi:type="xsd:string">2</peso>
            <alto xsi:type="xsd:string">10</alto>
            <ancho xsi:type="xsd:string">50</ancho>
            <largo xsi:type="xsd:string">50</largo>
            <tokn xsi:type="xsd:string">1593aaeeb60a560c156387989856db6be7edc8dc220f9feae3aea237da6a951d</tokn>
            <usu xsi:type="xsd:string">IMPCOMEX</usu>
            <pwd xsi:type="xsd:string">Rtcom-ex9912</pwd>
        </ws:Consultar>
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

        if ($response === false) {
            echo "CURL Error: " . curl_error($ch);
            return [
                "flete" => 0,
                "seguro" => 0,
                "comision" => 0,
                "otros" => 0,
                "impuestos" => 0
            ];
        }

        echo "Raw Response: " . htmlspecialchars($response);

        // Decodificar la respuesta HTML
        $decodedResponse = html_entity_decode($response);
        echo "Decoded Response: " . htmlspecialchars($decodedResponse);

        // Cargar la respuesta en DOMDocument
        $dom = new DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadXML($decodedResponse);
        if (libxml_get_errors()) {
            echo "Failed loading XML";
            libxml_clear_errors();
            return [
                "flete" => 0,
                "seguro" => 0,
                "comision" => 0,
                "otros" => 0,
                "impuestos" => 0
            ];
        }

        // Extraer el contenido de <Result>
        $xpath = new DOMXPath($dom);
        $xpath->registerNamespace('soap', 'http://schemas.xmlsoap.org/soap/envelope/');
        $xpath->registerNamespace('ns1', 'https://servientrega-ecuador.appsiscore.com/app/ws/');
        $resultNode = $xpath->query('//soap:Body/ns1:ConsultarResponse/Result')->item(0);
        if (!$resultNode) {
            echo "No se encontró la etiqueta <Result>";
            return [
                "flete" => 0,
                "seguro" => 0,
                "comision" => 0,
                "otros" => 0,
                "impuestos" => 0
            ];
        }

        $result = $resultNode->nodeValue;
        echo "Extracted Result: " . htmlspecialchars($result);

        // Decodificar entidades HTML del resultado
        $resultDecoded = html_entity_decode($result);

        echo "Decoded Result: " . htmlspecialchars($resultDecoded);

        try {
            // Convertir el resultado de la cadena XML a un objeto SimpleXMLElement
            $resultXml = new SimpleXMLElement($resultDecoded);
        } catch (Exception $e) {
            echo "Error parsing XML: " . $e->getMessage();
            return [
                "flete" => 0,
                "seguro" => 0,
                "comision" => 0,
                "otros" => 0,
                "impuestos" => 0
            ];
        }

        $flete = (float)$resultXml->flete;
        $seguro = (float)$resultXml->seguro;
        $comision = (float)$resultXml->valor_comision;
        $otros = (float)$resultXml->otros;
        $impuestos = (float)$resultXml->impuesto;

        $data = [
            "flete" => $flete,
            "seguro" => $seguro,
            "comision" => $comision,
            "otros" => $otros,
            "impuestos" => $impuestos
        ];

        return $data;
    }
}
