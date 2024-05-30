<?php

class Home extends Controller
{
    ///Vistas
    public function index()
    {
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

    ///Funciones
}
