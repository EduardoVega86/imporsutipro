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

        $id_plataforma = $_POST['id_plataforma'] ?? 0;


        $tarifas = $this->model->obtenerTarifas($ciudad, $provincia, $monto_factura, $recuado, $id_plataforma);
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

        $tarifas = $this->model->calcularServi($ciudadO, $ciudadD, $provinciaD, $monto_factura);
        echo json_encode($tarifas);
    }

    public function calcularGuiaDirecta()
    {
        $this->isAuth();
        $id_producto = $_POST['id_producto'];
        $total = $_POST['total'];
        $tarifa = $_POST['tarifa'];
        $costo = $_POST['costo'];

        $tarifas = $this->model->calcularGuiaDirecta($id_producto, $total, $tarifa, $_SESSION['id_plataforma'], $costo);
        echo json_encode($tarifas);
    }
}
