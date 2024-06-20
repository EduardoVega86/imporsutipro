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
        $matriz = $matriz["idmatriz"];

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
}
