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
            $this->model->iniciarPlataforma($id_plataforma);
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
}
