<?php

use FontLib\EOT\Header;

session_start();
class Home extends Controller
{
    public function __construct()
    {
        parent::__construct();
        if ($this->isAuth()) {
            header("Location:  " . SERVERURL . "dashboard");
        }
    }
    ///Vistas
    public function index()
    {
        Header("Location: " . SERVERURL . "login");
        $this->views->render($this, "index");
    }
    public function promociones()
    {
        $this->views->render($this, "promociones");
    }
    public function about()
    {
        $this->views->render($this, "about");
    }
    public function login()
    {
        $this->views->render($this, "login");
    }
    public function registro()
    {

        $this->views->render($this, "registro");
    }
    public function recovery()
    {
        $this->views->render($this, "recovery");
    }
    public function pedidos_ingresados()
    {
        $this->views->render($this, "pedidos_ingresados");
    }
    ///Funciones
}
