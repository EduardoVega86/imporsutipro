<?php

class Despacho extends Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->isAuth()) {
            header("Location:  " . SERVERURL . "login");
        }
    }

    public function index()
    {
        $this->views->render($this, "despacho");
    }
    public function despacho()
    {
        $this->views->render($this, "despacho");
    }

    public function devoluciones()
    {
        $this->views->render($this, "devoluciones");
    }
}
