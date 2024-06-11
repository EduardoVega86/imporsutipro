<?php
class Calculadora extends Controller
{
    public function obtenerTarifas()
    {
        $ciudad = $_POST['ciudad'];
        $provincia = $_POST['provincia'];
        $monto_factura = $_POST['monto_factura'];

        $tarifas = $this->model->obtenerTarifas($ciudad, $provincia, $monto_factura);
        echo json_encode($tarifas);
    }
}
