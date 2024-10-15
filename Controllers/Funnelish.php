<?php

class Funnelish extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }


    public function index($id_plataforma)
    {
        if (empty($id_plataforma)) {
            die("Error: No se ha especificado una plataforma");
        }
        if ($this->model->existenciaPlataforma($id_plataforma)) {
            $data = file_get_contents("php://input");
            $response = $this->model->log($id_plataforma, $data);
            $valido = $this->model->productoPlataforma($id_plataforma, $data);
            if ($valido) {
                $this->model->gestionarRequest($id_plataforma, $data);
            } else return;
        } else {
            $data = file_get_contents("php://input");
            $response = $this->model->log($id_plataforma, $data);
            echo json_encode($response);
        }
    }


    public function data()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $this->model->saveData($data);
    }

    public function log($data, $id_plataforma)
    {
        $this->model->saveDataPlatform($data, $id_plataforma);
    }
}
