<?php

class Prueba extends Controller
{
    //siempre se returna con echo json_encode
    public function __construct()
    {
        parent::__construct();
    }
    // vistas - capa de presentación
    public function index()
    {
        $this->views->render($this, "index");
    }

    public function hola_mundo()
    {
        echo "Hola mundo";
    }

    public function plataformas()
    {
        $response = $this->model->getPlataformas();
        echo json_encode($response);
    }
}
