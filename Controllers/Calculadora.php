<?php
class Calculadora extends Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->isAuth()) {
            header("Location:  " . SERVERURL . "login");
        }
    }

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

    public function calcularServi()
    {
        $ciudadO = $_POST['ciudadO'];
        $provinciaD = $_POST['provinciaD'];
        $monto_factura = $_POST['monto_factura'];
        $ciudadD = $_POST['ciudadD'];
        $tarifas = $this->model->calcularServi($ciudadO, $ciudadD, $provinciaD, $monto_factura);
        echo json_encode($tarifas);
    }
}
