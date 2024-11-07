<?php
session_start();
class Dashboard extends Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->isAuth()) {
            header("Location:  " . SERVERURL . "login");
            exit;
        }
    }
    ///Vistas
    public function index()
    {
        $this->views->render($this, "dashboard");
    }
    public function dashboard()
    {
        $this->views->render($this, "dashboard");
    }

    public function dashboard2()
    {
        $this->views->render($this, "dashboard2");
    }

    public function home()
    {
        $this->views->render($this, "home");
    }

    public function redirect()
    {
        $this->views->render($this, "redirect");
    }

    ///Funciones


    public function filtroInicial()
    {
        $fecha_i = $_POST['fechai'] != "" ? $_POST['fechai'] : date('Y-m-01');
        $fecha_f = $_POST['fechaf']  != "" ? $_POST['fechaf'] : date('Y-m-t', strtotime($fecha_i));

        echo $fecha_f;

        $data = $this->model->filtroInicial($fecha_i, $fecha_f, $_SESSION['enlace'], $_SESSION['id_plataforma']);
        echo json_encode($data);
    }
}
