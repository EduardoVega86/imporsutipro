<?php
class Tienda extends Controller
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

    public function registro()
    {
        $nombre = $_POST['nombre'];
        $response = $this->model->crearSubdominio($nombre, $_SESSION['id_plataforma']);
    }


    public function dominio()
    {
        $nombre = $_POST['nombre'];
        $response = $this->model->crearDominio($nombre, $_SESSION['id_plataforma']);
    }
    
    
    
    public function informaciontienda()
    {        //$nombre = $_POST['nombre'];
        $response = $this->model->informaciontienda($_SESSION['id_plataforma']);
       echo json_encode($response);
       
    }
    
    
}
