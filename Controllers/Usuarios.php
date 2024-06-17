<?php
class Usuarios extends Controller
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
        $this->views->render($this, "index");
    }

    public function roles()
    {
        $this->views->render($this, "roles");
    }

    public function cargarUsuarios()
    {
        $data = $this->model->cargarUsuarios();
        return $data;
    }

    public function cargarRoles()
    {
        $data = $this->model->cargarRoles();
        return $data;
    }

    public function guardarUsuario()
    {
        $data = file_get_contents('php://input');
        $data = json_decode($data, true);
        $response = $this->model->guardarUsuario($data);
        echo json_encode($response);
    }

    public function actualizarUsuario()
    {
        $data = file_get_contents('php://input');
        $data = json_decode($data, true);
        $response = $this->model->actualizarUsuario($data);
        echo json_encode($response);
    }

    public function eliminarUsuario()
    {
        $data = file_get_contents('php://input');
        $data = json_decode($data, true);
        $response = $this->model->eliminarUsuario($data);
        echo json_encode($response);
    }
}
