<?php

class Bitacora extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function estados()
    {
        $guia = $_POST['guia'];
        $estado = $_POST['estado'];
        $transportadora = $_POST['transportadora'];
        $fecha = new DateTime();
        $fecha = $fecha->format('Y-m-d H:i:s');
        $response = $this->model->estados($guia, $estado, $transportadora, $fecha);
        echo json_encode($response);
    }
}
