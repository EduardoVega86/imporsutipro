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
        echo json_encode($referidos);
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
        $data = json_decode(file_get_contents("php://input"), true);
        $monto = $data['monto'];
        $cuenta = $data['cuenta'];
        $otro = $data['otro'];
        $plataforma = $_SESSION['id_plataforma'];
        $response = $this->model->solicitar_pago($monto, $cuenta, $plataforma, $otro);
        echo json_encode($response);
    }
}
