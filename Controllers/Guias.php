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
        $ciudad = $_POST['ciudadO'];
        $ciudadOrigen = $this->model->obtenerCiudadLaar($ciudad);
        $direccionOrigen = $_POST['direccionO'];
        $telefonoOrigen = $_POST['celularO'];
        $referenciaOrigen = $_POST['referenciaO'];
        $celularOrigen = $telefonoOrigen;
        /// destinatario
        $nombreDestino = $_POST['nombre'];
        $ciudadDestino = $_POST['ciudad'];
        $provincia = $_POST['provincia'];
        $ciudadDestino = $this->model->obtenerCiudadLaar($ciudadDestino);
        $direccionDestino = $_POST['calle_principal'] . " y " . $_POST['calle_secundaria'];

        $calle_principal = $_POST['calle_principal'];
        $calle_secundaria = $_POST['calle_secundaria'];
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
        $cod = $_POST['recaudo'];
        $costoflete = $_POST['costo_flete'] ?? 0;
        $costo_producto = $_POST['total_venta'];
        $tipo_cobro = 0;
        $comentario = $_POST['observacion'];
        $fecha = date("Y-m-d");
        $extras = "";

        $numero_factura = $_POST['numero_factura'];

        $datos = $this->model->generarLaar($nombreOrigen, $ciudadOrigen, $direccionOrigen, $telefonoOrigen, $referenciaOrigen, $celularOrigen, $nombreDestino, $ciudadDestino, $direccionDestino, $telefonoDestino, $celularDestino, $referenciaDestino, $postal, $identificacion, $contiene, $peso, $valor_seguro, $valor_declarado, $tamanio, $cod, $costoflete, $costo_producto, $tipo_cobro, $comentario, $fecha, $extras);
        $datos = json_decode($datos, true);
        if (!empty($datos["guia"])) {
            $this->model->actualizarGuia($numero_factura, $datos["guia"], $nombreDestino, $ciudad, $direccionDestino, $telefonoDestino, $celularDestino, $referenciaDestino, $cod, $costo_producto, $comentario, $_SESSION["id"], $calle_principal, $calle_secundaria, $contiene, $provincia, $costoflete);
            $datos["status"] = "200";

            //   $this->model->asignarWallet($datos["guia"], $costo_producto);
        } else {
            $datos["status"] = "500";
        }
        echo json_encode($datos);
    }

    private function obtenerDestinatario()
    {
        $id_producto = $_POST['id_inventario'];
        $datos = $this->model->obtenerDestinatario($id_producto);
    }
}
