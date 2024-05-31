<?php
session_start();
class Home extends Controller
{
    ///Vistas
    ///Vistas
    public function index()
    {
        $this->views->render($this, "index");
    }
    public function recovery()
    {
        $this->views->render($this, "recovery");
    }
}
