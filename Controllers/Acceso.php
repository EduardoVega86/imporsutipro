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
    header('Content-Type: application/json');
    
    // Verificar si los datos se recibieron correctamente
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo json_encode(['error' => 'Invalid JSON input']);
        return;
    }

    // Asegúrate de que la clave 'tienda' está presente
    if (!isset($data['tienda'])) {
        echo json_encode(['error' => 'Missing "tienda" key']);
        return;
    }

    $tienda = $data['tienda'];
    $exists = $this->model->validarTiendas($tienda);
    echo json_encode(['exists' => $exists]);
}
}
