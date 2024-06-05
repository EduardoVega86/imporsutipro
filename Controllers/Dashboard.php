<?php
session_start();
class Dashboard extends Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->isAuth()) {
            header("Location:  " . SERVERURL . "login");
        }
    }
    ///Vistas
    public function index()
    {
        $this->views->render($this, "dashboard");
    }
    public function dashboard()
    {
        $this->views->render($this, "dashboard");
    }

    ///Funciones
}
