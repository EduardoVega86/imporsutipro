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
        $nombre = $_POST['nombre'];
        $correo = $_POST['correo'];
        $pais = $_POST['pais'];
        $telefono = $_POST['telefono'];
        $contrasena = $_POST['contrasena'];
        $tienda = $_POST['tienda'];

        $response = $this->model->registro($nombre, $correo, $pais, $telefono, $contrasena, $tienda);

        echo json_encode($response);
    }
}
