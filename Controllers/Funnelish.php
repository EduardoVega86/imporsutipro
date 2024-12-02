<?php

use FontLib\Table\Type\head;

class Funnelish extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index($id_plataforma)
    {
        if (empty($id_plataforma)) {
            die("Error: No se ha especificado una plataforma");
        }
        $data = file_get_contents("php://input");
        $this->log($data, $id_plataforma);

        if ($this->model->existenciaPlataforma($id_plataforma)) {
            /*  $response = $this->log($id_plataforma, $data); */
            $valido = $this->model->productoPlataforma($id_plataforma, $data);
            print_r($valido);
            if ($valido) {
                $this->model->gestionarRequest($id_plataforma, $data);
            } else return;
        } /* else {
            $data = file_get_contents("php://input");
            $this->data($data);
            echo "Plataforma no registrada";
        } */
    }

    public function productos()
    {
        if ($this->isAuth()) {
            $this->views->render($this, 'productos');
        } else {
            header('Location: ' . SERVERURL . 'Login');
            exit();
        }
    }

    public function listar()
    {
        $this->isAuth();
        $id_plataforma = $_SESSION['id_plataforma'];
        $data = $this->model->listar($id_plataforma);
        echo json_encode($data);
    }

    public function constructor_vista()
    {
        if (!$this->isAuth()) {
            header('Location: ' . SERVERURL . 'login');
            exit();
        }
        $this->views->render($this, "constructor_vista");
    }

    public function data()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $this->model->saveData($data);
    }

    public function log($data, $id_plataforma)
    {
        $this->model->saveDataPlatform($data, $id_plataforma);
    }

    public function get_productos()
    {
        $this->isAuth();
        $response =  $this->model->get_productos($_SESSION['id_plataforma']);
        echo json_encode($response);
    }

    public function ultimoProductos($id_plataforma)
    {
        $id_inventario = $_POST["id_inventario"];
        $response = $this->model->ultimoProductos($id_inventario, $id_plataforma);
        echo json_encode($response);
    }
}
