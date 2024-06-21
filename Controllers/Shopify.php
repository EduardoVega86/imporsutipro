<?php
class Shopify extends Controller
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
            $this->model->gestionarRequest($id_plataforma, $data);
        } else {

            $response = array(
                "status" => "error",
                "message" => "No se ha encontrado la plataforma",
            );
            echo json_encode($response);
        }
    }

    public function constructor()
    {
        $this->views->render($this, "constructor");
    }

    public function configuracion()
    {
        $this->views->render($this, "configuracion");
    }

    public function generarEnlace()
    {
        $data = $this->model->generarEnlace($_SESSION["id_plataforma"]);
        echo json_encode($data);
    }
}
