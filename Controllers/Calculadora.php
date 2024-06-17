<?php
class Calculadora extends Controller
{
    public function obtenerTarifas()
    {
        $ciudad = $_POST['ciudad'];
        $provincia = $_POST['provincia'];
        $monto_factura = $_POST['monto_factura'];
        $recuado = $_POST['recaudo'];

        $tarifas = $this->model->obtenerTarifas($ciudad, $provincia, $monto_factura, $recuado);
        echo json_encode($tarifas);
    }

    public function saldo()
    {
        $plataforma = $_SESSION['id_plataforma'];
        $saldo = $this->model->saldo($plataforma);
        echo json_encode($saldo);
    }
}
