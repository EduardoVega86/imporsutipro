<?php
session_start();
class Home extends Controller
{
    ///Vistas
    public function index()
    {
        if (isset($_SESSION['id'])) {
            header("Location: /Dashboard");
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
        if (isset($_SESSION['id'])) {
            header("Location: /Dashboard");
        } else {
            $this->views->render($this, "login");
        }
    }
    public function registro()
    {
        if (isset($_SESSION['id'])) {
            header("Location: /Dashboard");
        } else {
            $this->views->render($this, "registro");
        }
    }
    public function pedidos()
    {
        if (isset($_SESSION['id'])) {
            header("Location: /Dashboard");
        } else {
            $this->views->render($this, "pedidos_ingresados");
        }
    }
    ///Funciones
}
