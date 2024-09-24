<?php

class Speed extends Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->isAuth()) {
            header('Location: ' . SERVERURL);
        }
    }

    public function index()
    {
        $this->views->render($this, "index");
    }

    public function solventarNovedad()
    {
        $observacion = $_POST['observacion'];
        $id_novedad = $_POST['id_novedad'];

        $response = $this->model->solventarNovedad($observacion, $id_novedad);

        echo json_encode($response);
    }

    public function cargarNovedades()
    {
        $data = $this->model->cargarNovedades();
        return $data;
    }

    public function guardarRecibo()
    {
        $recibo = $_FILES['recibo'];
        $id_factura = $_POST['id_factura'];

        $response = $this->model->guardarRecibo($recibo, $id_factura);

        echo json_encode($response);
    }

    public function guardarMotorizado()
    {
        $nombre = $_POST['nombre'];
        $celular = $_POST['celular'];

        $response = $this->model->guardarMotorizado($nombre, $celular, $_SESSION["id_plataforma"]);
        echo json_encode($response);
    }

    public function asignarMotorizado()
    {
        $id_motorizado = $_POST['id_motorizado'];
        $guia = $_POST['guia'];

        $response = $this->model->asignarMotorizado($id_motorizado, $guia);
        echo json_encode($response);
    }

    public function estados()
    {
        $estado = $_POST['estado'];
        $imagen = $_FILES['imagen'] ?? null;
        $tipo = $_POST['tipo'] ?? null;
        $observacion = $_POST['observacion'] ?? null;
        $id_factura = $_POST['id_factura'];
        $googlemaps = $_POST['googlemaps'] ?? null;

        $response = $this->model->estados($estado, $imagen, $tipo, $observacion, $id_factura, $googlemaps);
        echo json_encode($response);
    }
}
