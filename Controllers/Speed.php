<?php

class Speed extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->views->render($this, "index");
    }

    public function solventarNovedad()
    {
        $observacion = $_POST['observacion'];
        $id_factura = $_POST['id_factura'];

        $response = $this->model->solventarNovedad($observacion, $id_factura);

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
}
