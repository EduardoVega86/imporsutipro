<?php

class Acceso extends Controller
{
    ///Vistas
    public function index()
    {
        $this->views->render($this, "index");
    }
    public function recovery()
    {
        $this->views->render($this, "recovery");
    }


    ///Funciones
    public function login()
    {
        $usuario = $_POST['usuario'];
        $password = $_POST['password'];
    }
    public function registro()
    {
        $this->views->render($this, "registro");
    }
}
