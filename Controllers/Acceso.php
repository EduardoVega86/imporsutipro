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
        $data = json_decode(file_get_contents("php://input"), true);

        $correo = $data['correo'];
        $contrasena = $data['contrasena'];
        $response = $this->model->login($correo, $contrasena);

        echo json_encode($response);
    }
    public function registro()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        //print_r($data);
        $nombre = $data['nombre'];
        $correo = $data['correo'];
        $pais = $data['pais'];
        $telefono = $data['telefono'];
        $contrasena = $data['contrasena'];
        $tienda = $data['tienda'];


        $response = $this->model->registro($nombre, $correo, $pais, $telefono, $contrasena, $tienda);

        echo json_encode($response);
    }

    public function logout()
    {
        session_start();
        session_unset();
        session_destroy();
        header("Location:  " . SERVERURL . "login");
    }

    public function validar_tiendas()
    {
        $tienda = file_get_contents("php://input");
        $tienda = json_decode($tienda, true);
        $exists = $this->model->validarTiendas($tienda['tienda']);
        echo json_encode(['exists' => $exists]);
    }

    public function recuperar_contrasena()
    {
        $correo = $_POST['correo'];
        $response = $this->model->recuperar_contrasena($correo);
        echo json_encode($response);
    }

    public function validarToken()
    {
        $token = $_POST['token'];
        $response = $this->model->validarToken($token);
        echo json_encode($response);
    }

    public function cambiarContraseña()
    {
        $token = $_POST['token'];
        $contrasena = $_POST['contrasena'];
        $response = $this->model->cambiarContraseña($token, $contrasena);
        echo json_encode($response);
    }
}
