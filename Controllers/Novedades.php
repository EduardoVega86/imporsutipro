<?php

class Novedades extends Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->isAuth())
            header("Location:  " . SERVERURL . "login");
    }

    public function  index()
    {
        $this->views->render($this, "index");
    }

    public function cargarNovedades()
    {
        $data = $this->model->cargarNovedades();
        return $data;
    }

    public function solventarNovedadLaar()
    {
        $id_novedad = $_POST['id_novedad'];
        $guia = $_POST['guia'];
        $ciudad = $_POST['ciudad'];
        $nombre = $_POST['nombre'];
        $cedula = 0;
        $callePrincipal = $_POST['callePrincipal'];
        $calleSecundaria = $_POST['calleSecundaria'];
        $numeracion = $_POST['numeracion'];
        $referencia = $_POST['referencia'];
        $telefono = $_POST['telefono'];
        $celular = $_POST['celular'];
        $observacion  = $_POST['observacion'];
        $correo = "";

        $isDevolucion = $_POST['isDevolucion'];
        $nombreA = "IMPORSUIT";
        $observacionA = $_POST['observacionA'];

        $data = $this->model->solventarNovedadLaar($guia, $ciudad, $nombre, $cedula, $callePrincipal, $calleSecundaria, $numeracion, $referencia, $telefono, $celular, $observacion, $correo, $isDevolucion, $nombreA, $observacionA, $id_novedad);
        $response = array(
            "status" => 200,
            "message" => "Novedad solventada"
        );
        echo json_encode($response);
    }

    public function solventarNovedadServientrega()
    {
        $guia = $_POST['guia'];
        $observacion = $_POST['observacion'];
        $id_novedad = $_POST['id_novedad'];

        $data = $this->model->solventarNovedadServientrega($guia, $observacion, $id_novedad);
        $response = array(
            "status" => 200,
            "message" => "Novedad solventada"
        );
        echo json_encode($response);
    }
}
