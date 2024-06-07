<?php
session_start();
class Pedidos extends Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->isAuth()) {
            header("Location:  " . SERVERURL . "login");
        }
    }
    ///Vistas
    public function index($filtro = "")
    {
        $data = $this->model->cargarPedidosIngresados($filtro);
        $this->views->render($this, "index", $data);
    }
    public function ingreso($filtro = "")
    {
        $data = $this->model->cargarPedidosIngresados($filtro);
        $this->views->render($this, "index", $data);
    }
    public function guias($filtro = "")
    {
        $data = $this->model->cargarGuias($filtro);
        $this->views->render($this, "guias", $data);
    }
    public function anuladas($filtro = "")
    {
        $data = $this->model->cargarAnuladas($filtro);
        $this->views->render($this, "anuladas");
    }
    public function nuevo()
    {
        $this->views->render($this, "nuevo");
    }
    public function ver($id)
    {
        if (empty($id))
            header("Location: " . SERVERURL . "Pedidos");

        $this->views->render($this, "ver", $id);
    }
    public function novedades()
    {
        $this->views->render($this, "novedades");
    }



    ///Funciones
    public function nuevo_pedido()
    {
        $fecha_factura = date("Y-m-d H:i:s");
        $id_usuario = $_SESSION['id'];
    }

    public function obtener_guias($filtro = "")
    {

        $data = $this->model->cargarGuias($filtro);
        echo json_encode($data);
    }

    public function info()
    {
        print_r($_SESSION);
    }
}
