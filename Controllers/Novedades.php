<?php

class Novedades extends Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->isAuth())
            header("Location:  " . SERVERURL . "login");
    }

    public function  index()
    {
        $this->views->render($this, "index");
    }

    public function cargarNovedades()
    {
        $data = $this->model->cargarNovedades();
        return $data;
    }

    public function solventarNovedad()
    {
        $data = file_get_contents('php://input');
        $data = json_decode($data, true);
    }
}
