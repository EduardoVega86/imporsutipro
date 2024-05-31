<?php
session_start();
class Home extends Controller
{
    ///Vistas

    public function login()
    {
        if (isset($_SESSION['id'])) {
            header("Location: /Dashboard");
        } else {
            $this->views->render($this, "pedidos_ingresados");
        }
    }
    ///Funciones
}
