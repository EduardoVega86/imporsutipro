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
        $this->views->render($this, "index");
    }
    public function referido()
    {
        $this->views->render($this, "referido");
    }

    ///funciones

    public function getReferidos()
    {
        $referidos = $this->model->getReferidos();
        echo json_encode($referidos);
    }

    public function  crearReferido()
    {
        $data = $this->model->crearReferido($_SESSION['id']);
        echo json_encode($data);
    }
}
