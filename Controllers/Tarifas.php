<?php

class Tarifas extends Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->isAuth()) {
            header("Location:  " . SERVERURL . "login");
        }
        if ($_SESSION["cargo"] != 10) {
            header("Location:  " . SERVERURL . "dashboard");
        }
    }

    public function index()
    {
        $this->views->render($this, "index");
    }

    public function getTarifas()
    {
        $tarifas = $this->model->getTarifas();
        echo json_encode($tarifas);
    }

    public function crearTarifa()
    {
        $nombre = $_POST['nombre'];
        $precio = $_POST['precio'];
        $descripcion = $_POST['descripcion'];
        $data = $this->model->crearTarifa($nombre, $precio, $descripcion);
        echo json_encode($data);
    }

    public function editarTarifa()
    {
        $id_tarifa = $_POST['id_tarifa'];
        $nombre = $_POST['nombre'];
        $precio = $_POST['precio'];
        $descripcion = $_POST['descripcion'];
        $data = $this->model->editarTarifa($id_tarifa, $nombre, $precio, $descripcion);
        echo json_encode($data);
    }

    public function eliminarTarifa()
    {
        $id_tarifa = $_POST['id_tarifa'];
        $data = $this->model->eliminarTarifa($id_tarifa);
        echo json_encode($data);
    }
}
