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
        $data = json_decode(file_get_contents("php://input"), true);
        print_r($data);
        $nombre = $data['nombre'];
        $correo = $data['correo'];
        $pais = $data['pais'];
        $telefono = $data['telefono'];
        $contrasena = $data['contrasena'];
        $tienda = $data['tienda'];

        $response = $this->model->registro($nombre, $correo, $pais, $telefono, $contrasena, $tienda);

        echo json_encode($response);
    }
}
