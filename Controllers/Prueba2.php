<?php

class Prueba2 extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    public function mensaje($text)
    {
        $this->model->saludo($text);
    }
}
