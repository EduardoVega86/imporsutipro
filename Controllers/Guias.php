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
            $this->model->asignarWallet($numero_factura, $datos["guia"], $fecha, $nombreDestino, $_SESSION["id_plataforma"], 1, $costo_producto, $cod, $costoflete);
        } else {
            $datos["status"] = "500";
        }
        echo json_encode($datos);
    }

    public function generarServientrega()
    {
        $nombreOrigen = $_POST['nombreO'];
        $ciudadOrigen = $_POST['ciudadO'];

        $ciudadOrigen = $this->model->obtenerNombre($ciudadOrigen, "codigo_ciudad_servientrega")[0]['codigo_ciudad_servientrega'];


        $direccionOrigen = $_POST['direccionO'];
        $telefonoOrigen = $_POST['celularO'];
        $referenciaOrigen = $_POST['referenciaO'];
        $celularOrigen = $telefonoOrigen;
        $nombreDestino = $_POST['nombre'];
        $ciudadDestino = $_POST['ciudad'];

        $ciudadDestino = $this->model->obtenerNombre($ciudadDestino, "codigo_ciudad_servientrega")[0]['codigo_ciudad_servientrega'];


        $direccionDestino = $_POST['calle_principal'] . " y " . $_POST['calle_secundaria'];
        $telefonoDestino = $_POST['telefono'];
        $celularDestino = $telefonoDestino;
        $referenciaDestino = $_POST['referencia'];
        $postal = "";
        $identificacion  = "";
        $contiene = $_POST['contiene'];
        $peso = 2;
        $valor_seguro = 0;
        $valor_declarado = 0;
        $tamanio = 2;
        $cod = $_POST['recaudo'];
        $costoflete = $_POST['costo_flete'] ?? 0;
        $costo_producto = $_POST['total_venta'];
        $tipo_cobro = 0;
        $comentario = $_POST['observacion'];
        $fecha = date("Y-m-d");
        $extras = "";
        $numero_factura = $_POST['numero_factura'];

        $flete = $_POST['flete'];
        $seguro = $_POST['seguro'];

        $comision = $_POST['comision'];
        $otros = $_POST['otros'];
        $impuestos = $_POST['impuestos'];

        if ($cod == 1) {
            $response = $this->model->generarServientrega($nombreOrigen, $ciudadOrigen, $direccionOrigen, $telefonoOrigen, $referenciaOrigen, $celularOrigen, $nombreDestino, $ciudadDestino, $direccionDestino, $telefonoDestino, $celularDestino, $referenciaDestino, $postal, $identificacion, $contiene, $peso, $valor_seguro, $valor_declarado, $tamanio, $cod, $costoflete, $costo_producto, $tipo_cobro, $comentario, $fecha, $extras, $flete, $seguro, $comision, $otros, $impuestos);
        } else {
            $response = $this->model->generarServientregaSinRecaudo($nombreOrigen, $ciudadOrigen, $direccionOrigen, $telefonoOrigen, $referenciaOrigen, $celularOrigen, $nombreDestino, $ciudadDestino, $direccionDestino, $telefonoDestino, $celularDestino, $referenciaDestino, $postal, $identificacion, $contiene, $peso, $valor_seguro, $valor_declarado, $tamanio, $cod, $costoflete, $costo_producto, $tipo_cobro, $comentario, $fecha, $extras, $flete, $seguro, $comision, $otros, $impuestos);
        }
        //$response["status"] = 200;
        echo $response;
    }


    private function obtenerDestinatario()
    {
        $id_producto = $_POST['id_inventario'];
        $datos = $this->model->obtenerDestinatario($id_producto);
    }

    public function anularGuia()
    {
        $id = $_POST['guia'];
        $datos = $this->model->anularGuia($id);
        echo json_encode($datos);
    }

    public function obtenerTiendas()
    {
        $datos = $this->model->obtenerTiendas();
        echo json_encode($datos);
    }
}
