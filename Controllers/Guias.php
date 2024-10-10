<?php

class Guias extends Controller
{
    public function __construct()
    {
        parent::__construct();

        /* if (!$this->hasPermission(2)) {
            header("Location: /dashboard");
        } */
    }
    /// funciones

    public function generarLaar()
    {
        if (!$this->isAuth())
            header("Location:  " . SERVERURL . "login");
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
        $ciudad_D = $_POST['ciudad'];
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
            if (strpos($datos["guia"], "MKP") === 0) {
                $costoflete = 5.99;
            }
            $this->model->actualizarGuia($numero_factura, $datos["guia"], $nombreDestino, $ciudad_D, $direccionDestino, $telefonoDestino, $celularDestino, $referenciaDestino, $cod, $costo_producto, $comentario, $_SESSION["id"], $calle_principal, $calle_secundaria, $contiene, $provincia, $costoflete, "LAAR", 2);

            $datos["status"] = "200";
            $this->model->asignarWallet($numero_factura, $datos["guia"], $fecha, $nombreDestino, $_SESSION["id_plataforma"], 1, $costo_producto, $cod, $costoflete);
            $this->model->descargarGuia($datos["guia"]);
        } else {
            $datos["status"] = "500";
        }
        echo json_encode($datos);
    }

    public function tokenLaar()
    {
        $response = $this->model->laarToken();
        echo $response;
    }

    public function generarServientrega()
    {
        if (!$this->isAuth())
            header("Location:  " . SERVERURL . "login");
        $nombreOrigen = $_POST['nombreO'];
        $ciudadOrigen = $_POST['ciudadO'];
        $ciudad = $_POST['ciudad'];
        $ciudadOrigen = $this->model->obtenerNombre($ciudadOrigen, "codigo_ciudad_servientrega")[0]['codigo_ciudad_servientrega'];


        $direccionOrigen = $_POST['direccionO'];
        $telefonoOrigen = $_POST['celularO'];
        $referenciaOrigen = $_POST['referenciaO'];
        $celularOrigen = $telefonoOrigen;
        $nombreDestino = $_POST['nombre'];
        $ciudadDestino = $_POST['ciudad'];

        $ciudadDestino = $this->model->obtenerNombre($ciudadDestino, "codigo_ciudad_servientrega")[0]['codigo_ciudad_servientrega'];

        $provincia = $_POST['provincia'];
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

        $id_transporte = $_POST['id_transporte'];

        if ($cod == 1) {
            $response = $this->model->generarServientrega($nombreOrigen, $ciudadOrigen, $direccionOrigen, $telefonoOrigen, $referenciaOrigen, $celularOrigen, $nombreDestino, $ciudadDestino, $direccionDestino, $telefonoDestino, $celularDestino, $referenciaDestino, $postal, $identificacion, $contiene, $peso, $valor_seguro, $valor_declarado, $tamanio, $cod, $costoflete, $costo_producto, $tipo_cobro, $comentario, $fecha, $extras, $flete, $seguro, $comision, $otros, $impuestos);
        } else {
            $response = $this->model->generarServientregaSinRecaudo($nombreOrigen, $ciudadOrigen, $direccionOrigen, $telefonoOrigen, $referenciaOrigen, $celularOrigen, $nombreDestino, $ciudadDestino, $direccionDestino, $telefonoDestino, $celularDestino, $referenciaDestino, $postal, $identificacion, $contiene, $peso, $valor_seguro, $valor_declarado, $tamanio, $cod, $costoflete, $costo_producto, $tipo_cobro, $comentario, $fecha, $extras, $flete, $seguro, $comision, $otros, $impuestos);
        }
        $response = json_decode($response, true);
        if (isset($response["id"])) {
            $response["status"] = 200;
            $this->model->aumentarMatriz();
            $response2 = $this->model->actualizarGuia($numero_factura, $response["id"], $nombreDestino, $ciudad, $direccionDestino, $telefonoDestino, $celularDestino, $referenciaDestino, $cod, $costo_producto, $comentario, $_SESSION["id"], $_POST['calle_principal'], $_POST['calle_secundaria'], $contiene, $provincia, $costoflete, "SERVIENTREGA", 100);
            $this->model->asignarWallet($numero_factura, $response["id"], $fecha, $nombreDestino, $_SESSION["id_plataforma"], 1, $costo_producto, $cod, $costoflete);
            $this->model->descargarGuia($response["id"]);
        }
        echo json_encode($response);
    }

    public function generarGintracom()
    {
        if (!$this->isAuth())
            header("Location:  " . SERVERURL . "login");
        $nombreOrigen = $_POST['nombreO'];
        $ciudadOrigen = $_POST['ciudadO'];
        $provinciaOrigen = $_POST['provinciaO'];
        $ciudadOrigen = $this->model->obtenerNombre($ciudadOrigen, "codigo_ciudad_gintracom")[0]['codigo_ciudad_gintracom'];
        $provinciaOrigen = $this->model->obtenerNombre($provinciaOrigen, "codigo_provincia_gintracom")[0]['codigo_provincia_gintracom'];

        $direccionOrigen = $_POST['direccionO'];
        $telefonoOrigen = $_POST['celularO'];
        $referenciaOrigen = $_POST['referenciaO'];
        $celularOrigen = $telefonoOrigen;
        $nombreDestino = $_POST['nombre'];
        $ciudadDestino = $_POST['ciudad'];

        $ciudad = $_POST['ciudad'];
        $ciudadDestino = $this->model->obtenerNombre($ciudadDestino, "codigo_ciudad_gintracom")[0]['codigo_ciudad_gintracom'];
        $provincia = $_POST['provincia'];
        $provinciaDestino = $this->model->obtenerNombre($provincia, "codigo_provincia_gintracom")[0]['codigo_provincia_gintracom'];
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
        $monto_factura = $_POST['total_venta'];

        $response = $this->model->generarGintracom($nombreOrigen, $ciudadOrigen, $provinciaOrigen, $direccionOrigen, $telefonoOrigen, $referenciaOrigen, $celularOrigen, $nombreDestino, $ciudadDestino, $provinciaDestino, $direccionDestino, $telefonoDestino, $celularDestino, $referenciaDestino, $postal, $identificacion, $contiene, $peso, $valor_seguro, $valor_declarado, $tamanio, $cod, $costoflete, $costo_producto, $tipo_cobro, $comentario, $fecha, $extras, $numero_factura, $monto_factura);
        $response = json_decode($response, true);
        if (isset($response["guia"])) {
            $response["status"] = 200;
            $this->model->aumentarMatriz();
            $response2 = $this->model->actualizarGuia($numero_factura, $response["guia"], $nombreDestino, $ciudad, $direccionDestino, $telefonoDestino, $celularDestino, $referenciaDestino, $cod, $costo_producto, $comentario, $_SESSION["id"], $_POST['calle_principal'], $_POST['calle_secundaria'], $contiene, $provincia, $costoflete, "GINTRACOM", 1);
            $this->model->asignarWallet($numero_factura, $response["guia"], $fecha, $nombreDestino, $_SESSION["id_plataforma"], 1, $costo_producto, $cod, $costoflete);
        }

        echo json_encode($response);
    }

    public function generarSpeed()
    {
        if (!$this->isAuth())
            header("Location:  " . SERVERURL . "login");
        $nombreO = $_POST['nombreO'];
        $ciudadO = $_POST['ciudadO'];
        $ciudadOrigen = $this->model->obtenerNombre($ciudadO, "ciudad")[0]['ciudad'];
        $direccionO = $_POST['direccionO'];
        $telefonoO = $_POST['celularO'];
        $referenciaO = $_POST['referenciaO'];


        $nombre = $_POST['nombre'];
        $ciudad = $_POST['ciudad'];
        $ciudadDestino = $this->model->obtenerNombre($ciudad, "ciudad")[0]['ciudad'];
        $direccion = $_POST['calle_principal'] . " y " . $_POST['calle_secundaria'];
        $telefono = $_POST['telefono'];
        $celular = $telefono;
        $referencia = $_POST['referencia'];

        $contiene = $_POST['contiene'];

        $fecha = date("Y-m-d H:i:s");

        $numero_factura = $_POST['numero_factura'];

        $recaudo = $_POST['recaudo'];

        $observacion = $_POST['observacion'];

        $monto_factura = $_POST['total_venta'];



        $response = $this->model->generarSpeed($nombreO, $ciudadOrigen, $direccionO, $telefonoO, $referenciaO, $nombre, $ciudadDestino, $direccion, $telefono, $celular, $referencia, $contiene, $fecha, $numero_factura, $_SESSION["id_plataforma"], $observacion, $recaudo, $monto_factura, MATRIZ);
        $response = json_decode($response, true);

        if (isset($response["guia"])) {
            $response["status"] = 200;
            $this->model->aumentarMatriz();
            $response2 = $this->model->actualizarGuia($numero_factura, $response["guia"], $nombre, $ciudad, $direccion, $telefono, $celular, $referencia, $recaudo, $monto_factura, $observacion, $_SESSION["id"], $_POST['calle_principal'], $_POST['calle_secundaria'], $contiene, $ciudad, 0, "SPEED", 2);
            $flete_envio = $ciudad == 599 ? 5.5 : 6.5;
            $this->model->asignarWallet($numero_factura, $response["guia"], $fecha, $nombre, $_SESSION["id_plataforma"], 1, $monto_factura, $recaudo, $flete_envio);
        } else {
            echo "error" . $response;
            print_r($response);
        }
        echo json_encode($response);
    }


    private function obtenerDestinatario()
    {
        $id_producto = $_POST['id_inventario'];
        $datos = $this->model->obtenerDestinatario($id_producto);
    }

    public function anularGuia()
    {
        if (!$this->isAuth())
            header("Location:  " . SERVERURL . "login");
        $id = $_POST['guia'];
        $datos = $this->model->anularGuia($id);
        echo json_encode($datos);
    }

    public function obtenerTiendas()
    {
        if (!$this->isAuth())
            header("Location:  " . SERVERURL . "login");
        $datos = $this->model->obtenerTiendas();
        echo json_encode($datos);
    }

    public function anularServi_temporal($id)
    {
        $response = $this->model->anularServi_temporal($id);
        echo json_encode($response);
    }

    public function anularSpeed_temporal($id)
    {
        $response = $this->model->anularSpeed_temporal($id);
        echo json_encode($response);
    }

    public function pesosLaar()
    {
        $response = $this->model->pesosLaar();
    }
}
