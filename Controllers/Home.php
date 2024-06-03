<?php
session_start();
class Home extends Controller
{
    public function __construct()
    {
        parent::__construct();
        if ($this->isAuth()) {
            header("Location: /dashboard");
        }
    }
    ///Vistas
    public function index()
    {
        if ($this->isAuth()) {
            header("Location: /dashboard");
        } else {
            $this->views->render($this, "index");
        }
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
        if ($this->isAuth()) {
            header("Location: /dashboard");
        } else {
            $this->views->render($this, "login");
        }
    }
    public function registro()
    {
        if ($this->isAuth()) {
            header("Location: /dashboard");
        } else {
            $this->views->render($this, "registro");
        }
    }
    public function recovery()
    {
        if ($this->isAuth()) {
            header("Location: /dashboard");
        } else {
            $this->views->render($this, "recovery");
        }
    }
    public function pedidos_ingresados()
    {
        if ($this->isAuth()) {
            header("Location: /dashboard");
        } else {
            $this->views->render($this, "pedidos_ingresados");
        }
    }
    ///Funciones
}
