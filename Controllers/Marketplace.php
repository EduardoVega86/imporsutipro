<?php

class Marketplace extends Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->isAuth())
            header("Location:  " . SERVERURL . "login");
        /* if (!$this->hasPermission(2)) {
            header("Location: /dashboard");
        } */
    }
    ///Vistas
    public function index()
    {
        $this->views->render($this, "index");
    }

    public function obtener_productos()
    {
        $response = $this->model->obtener_productos($_SESSION['id_plataforma']);
        echo json_encode($response);
    }

    public function agregarMarketplace()
    {
        $codigo_producto = $_POST['codigo_producto'];
        $response = $this->model->agregarMarketplace($codigo_producto, $_SESSION['id_plataforma']);
        echo json_encode($response);
    }

    public function agregarTmp()
    {
        $codigo_producto = $_POST['codigo_producto'];
        $cantidad = $_POST['cantidad'];
        $precio = $_POST['precio'];
        $response = $this->model->agregarTmp($codigo_producto, $cantidad, $precio, $_SESSION['id_plataforma']);
        echo json_encode($response);
    }
}
