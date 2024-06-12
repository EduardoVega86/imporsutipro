<?php
class CalculadoraModel extends Query
{
    public function obtenerTarifas($ciudad, $provincia, $monto_factura)
    {
        $select = $this->select("SELECT * FROM ciudad_cotizacion WHERE id_cotizacion = '$ciudad' ");
        $tarifas = [];
        $trayecto_laar = $select[0]['trayecto_laar'];
        $trayecto_servientrega = $select[0]['trayecto_servientrega'];
        $trayecto_gintracom = $select[0]['trayecto_gintracom'];

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

        $previo = $monto_factura * 0.03;
        if ($trayecto_laar === "0" || $trayecto_laar === null) {
            $tarifas['laar'] = 0;
        } else {
            $tarifas['laar'] = $tarifas['laar'] + $previo;
        }
        if ($trayecto_gintracom === "0" || $trayecto_gintracom === null) {
            $tarifas['gintracom'] = 0;
        } else {
            $tarifas['gintracom'] = $tarifas['gintracom'] + $previo;
        }
        if ($previo < 1.25) {
            $previo = 1.25;
        }
        if ($trayecto_servientrega === "0" || $trayecto_servientrega === null) {
            $tarifas['servientrega'] = 0;
        } else {
            $tarifas['servientrega'] = $tarifas['servientrega'] + $previo;
        }

        return $tarifas;
    }
}
