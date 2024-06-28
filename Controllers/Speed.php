<?php

class Speed extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->views->render($this, "index");
    }

    public function cargarNovedades()
    {
        $data = $this->model->cargarNovedades();
        return $data;
    }
}
