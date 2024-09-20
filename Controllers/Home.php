<?php

use FontLib\EOT\Header;

session_start();
class Home extends Controller
{
    public function __construct()
    {
        parent::__construct();
        if ($this->isAuth()) {
            header("Location:  " . SERVERURL . "dashboard");
        }
    }
    ///Vistas
    public function index()
    {
        Header("Location: " . SERVERURL . "login");
        $this->views->render($this, "index");
    }
    public function promociones()
    {
        $this->views->render($this, "promociones");
    }
    public function about()
    {
        $this->views->render($this, "about");
    }
    public function login()
    {
        $this->views->render($this, "login");
    }
    public function registro()
    {

        $this->views->render($this, "registro");
    }
    public function recovery()
    {
        $this->views->render($this, "recovery");
    }
    public function pedidos_ingresados()
    {
        $this->views->render($this, "pedidos_ingresados");
    }

    public function refers($id)
    {
        $ver = $this->model->validarRefiere($id);
        if ($ver) {
            $this->views->render($this, "refers");
        } else {
            $this->views->render($this, "refers_error");
        }
    }
    ///Funciones
    
      public function consultaNotificaciones()
    {
        $data = $this->model->consulta_notificaciones($_SESSION['id_plataforma']);
        echo json_encode($data);
    }

    
}
