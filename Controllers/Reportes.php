<?php

class Reportes extends Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!$this->isAuth()) {
            header('Location: ' . SERVERURL . 'login');
            exit();
        }
    }

    public function billetera()
    {
        $this->views->render($this, 'billetera');
    }

    public function guias()
    {
        $this->views->render($this, 'guias');
    }

    public function obtenerReportes()
    {
        $anio = $_POST["anio"] != "" ? $_POST["anio"] : "2024";
        $mes = $_POST["mes"] != "" ? $_POST["mes"] : "11";

        if ($mes == "no") {
            echo json_encode([
                "status" => "400",
                "message" => "No se ha seleccionado un mes"
            ]);
            return;
        }

        $data = $this->model->getReporte($anio, $mes, $_SESSION['id_plataforma']);
        echo json_encode($data);
    }
}
