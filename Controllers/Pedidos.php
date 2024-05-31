<?php
session_start();
class Pedidos extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    ///Vistas
    public function index()
    {
        $this->views->render($this, "index");
    }
    public function historial()
    {
        $this->views->render($this, "index");
    }
    public function nuevo()
    {
        $this->views->render($this, "nuevo");
    }
    public function ver($id)
    {
        if (empty($id))
            header("Location: " . SERVERURL . "Pedidos");

        $this->views->render($this, "ver", $id);
    }
    public function novedades()
    {
        $this->views->render($this, "novedades");
    }

    ///Funciones
}
