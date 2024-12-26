<?php

class Prueba2 extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    /*Vistas */
    public function visor()
    {
        $this->views->render($this, "index");
    }
    // _________________
    public function mensaje_a_cliente($text)
    {
        $this->model->saludoACliente($text);
    }
}
