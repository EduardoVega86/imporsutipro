<?php

use FontLib\Table\Type\head;

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

    public function bienvenida()
    {
        $this->model->bienvenida();
    }

    public function referido_token($id)
    {
        $data = json_decode(file_get_contents("php://input"), true);
        //print_r($data);
        $nombre = $data['nombre'];
        $correo = $data['correo'];
        $pais = $data['pais'];
        $telefono = $data['telefono'];
        $contrasena = $data['contrasena'];
        $tienda = $data['tienda'];

        $response = $this->model->registro_referido($nombre, $correo, $pais, $telefono, $contrasena, $tienda, $id);

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
        echo json_encode($exists);
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

    public function cambiarContrasena()
    {
        $token = $_POST['token'];
        $contrasena = $_POST['contrasena'];
        $response = $this->model->cambiarContrasena($token, $contrasena);
        echo json_encode($response);
    }

    public function validarRefiere()
    {
        $id_referido = $_POST['id_referido'];
        $response = $this->model->validarRefiere($id_referido);
        echo json_encode($response);
    }

    public function guardaUltimoPunto()
    {
        session_start();
        $url = $_POST['url'];

        $response = $this->model->guardaUltimoPunto($url, $_SESSION['id']);

        echo json_encode($response);
    }

    public function jwt($token)
    {
        //separar token mediante: -||-
        $token = explode("-||-", $token);
        //verificar si el token es valido
        $response = $this->model->jwt($token[0], $token[1]);

        if ($response["status"] == 200) {
            if ($token[1] == "nuevo") {
                header("Location:  " . SERVERURL . 'Pedidos/nuevo');
                exit;
            }
            header("Location:  " . SERVERURL . 'Pedidos/editar/' . $token[1]);
            exit;
        }
        echo json_encode($response);
    }

    public function jwt_home($token)
    {
        //separar token mediante: -||-
        $token = explode("-||-", $token);
        //verificar si el token es valido
        $response = $this->model->jwt($token[0], $token[1]);

        if ($response["status"] == 200) {
            header("Location:  " . SERVERURL . 'dashboard/home');
            exit;
        }
        echo json_encode($response);
    }
}
