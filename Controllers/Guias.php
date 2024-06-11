<?php

class Guias extends Controller
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
    /// funciones

    public function generarLaar()
    {
        /// remitente
        $nombreOrigen = $_POST['nombreO'];
        $ciudadOrigen = $_POST['ciudadO'];
        $ciudadOrigen = $this->model->obtenerCiudadLaar($ciudadOrigen);
        $direccionOrigen = $_POST['direccionO'];
        $telefonoOrigen = $_POST['celularO'];
        $referenciaOrigen = $_POST['referenciaO'];
        $celularOrigen = $telefonoOrigen;
        /// destinatario
        $nombreDestino = $_POST['nombre'];
        $ciudadDestino = $_POST['ciudad'];
        $ciudadDestino = $this->model->obtenerCiudadLaar($ciudadDestino);
        $direccionDestino = $_POST['c_principal'] + " y " + $_POST['c_secundaria'] + " " + $_POST['referencia'];
        $telefonoDestino = $_POST['telefono'];
        $celularDestino = $telefonoDestino;
        $referenciaDestino = $_POST['referencia'];

        $postal = "";
        $identificacion = "0";

        /// variables
        $contiene = $_POST['contiene'];
        $peso = 2;
        $valor_seguro = 0;
        $valor_declarado = 0;
        $tamanio = "";
        $cod = $_POST['cod'];
        $costoflete = 0;
        $costo_producto = $_POST['monto_factura'];
        $tipo_cobro = 0;
        $comentario = $_POST['observacion'];
        $fecha = date("Y-m-d");
        $extras = "";

        $datos = $this->model->generarLaar($nombreOrigen, $ciudadOrigen, $direccionOrigen, $telefonoOrigen, $referenciaOrigen, $celularOrigen, $nombreDestino, $ciudadDestino, $direccionDestino, $telefonoDestino, $celularDestino, $referenciaDestino, $postal, $identificacion, $contiene, $peso, $valor_seguro, $valor_declarado, $tamanio, $cod, $costoflete, $costo_producto, $tipo_cobro, $comentario, $fecha, $extras);
        echo json_encode($datos);
    }

    private function obtenerDestinatario()
    {
        $id_producto = $_POST['id_inventario'];
        $datos = $this->model->obtenerDestinatario($id_producto);
    }
}
