<?php

class Referidos extends Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->isAuth()) {
            header("Location:  " . SERVERURL . "login");
        }
    }
    public function index()
    {
        $xd = $this->crearBilletera($_SESSION['id_plataforma']);
        $this->views->render($this, "index");
    }
    public function referidos()
    {
        $this->views->render($this, "referidos");
    }

    ///funciones

    public function getReferidos()
    {
        $referidos = $this->model->getReferidos($_SESSION['id_plataforma']);
        $cabeceras = $this->model->getCabecerasReferencias($_SESSION['id_plataforma']);
        $gananciasTotal = $this->model->getGananciasTotal($_SESSION['id_plataforma']);
        $data = array(
            "referidos" => $referidos,
            "cantidad" => count($referidos),
            "cabeceras" => $cabeceras,
            "ganancias" => $gananciasTotal
        );
        echo json_encode($data);
    }

    public function  crearReferido()
    {
        $data = $this->model->crearReferido($_SESSION['id_plataforma']);
        echo json_encode($data);
    }

    public function crearBilletera()
    {
        $data = $this->model->crearBilletera($_SESSION['id_plataforma']);
        echo json_encode($data);
    }

    public function solicitar_pago()
    {
        $monto = $_POST['monto'];
        $cuenta = $_POST['cuenta'];
        $otro = $_POST['otro'];
        $plataforma = $_SESSION['id_plataforma'];
        $response = $this->model->solicitar_pago($monto, $cuenta, $plataforma, $otro);
        echo json_encode($response);
    }
}
