<?php

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
        /*         $this->data($data);
 */
        if ($this->model->existenciaPlataforma($id_plataforma)) {
            /*  $response = $this->log($id_plataforma, $data); */
            $valido = $this->model->productoPlataforma($id_plataforma, $data);
            print_r($valido);
            if ($valido) {
                $this->model->gestionarRequest($id_plataforma, $data);
            } else return;
        } else {
            $data = file_get_contents("php://input");
            $this->data($data);
            echo "Plataforma no registrada";
        }
    }

    public function productos()
    {
        $this->isAuth();
        return $this->views->render($this, 'productos');
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
        $this->isAuth();
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
        return $this->model->get_productos($_SESSION['id_plataforma']);
    }
}
