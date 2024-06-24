<?php
class Calculadora extends Controller
{
    public function __construct()
    {
        parent::__construct();
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
        $this->isAuth();
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

        $ciudadD = $this->model->obtenerNombre($ciudadD, "ciudad")[0]['ciudad'];
        $provinciaD = $this->model->obtenerNombre($provinciaD, "provincia")[0]['provincia'];
        $ciudadO = $this->model->obtenerNombre($ciudadO, "ciudad")[0]['ciudad'];

        print_r($provinciaD);

        $tarifas = $this->model->calcularServi($ciudadO, $ciudadD, $provinciaD, $monto_factura);
        echo json_encode($tarifas);
    }
}
