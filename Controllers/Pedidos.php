<?php
session_start();
class Pedidos extends Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->isAuth()) {
            header("Location: /login");
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
        $fecha = date("Y-m-d H:i:s");
        $id_cliente = 1;
        $id_vendedor = 1;
        $condiciones = 1;
        $monto_factura = $_POST['monto_factura'];
        $estado_factura = 1;
        $id_user_factura = 1;
        $validez = 3;
        $id_sucursal = 1;
        $nombre = $_POST['nombre'];
        $telefono = $_POST['telefono'];
        $provincia = $_POST['provincia'];
        $calle_principal = $_POST['calle_principal'];
        $ciudad = $_POST['ciudad'];
        $calle_secundaria = $_POST['calle_secundaria'];
        $referencia = $_POST['referencia'];
        $observacion = $_POST['observacion'];
        $guia_enviada = 0;
        $transporte = NULL;
        $identificacion = NULL;
        $celular = $_POST['telefono'];
        $cod = NULL;
        $valor_segura = NULL;
        $dropshipping = $_POST['dropshipping'];
        $tienda = $_POST['tienda'];
        $importado = NULL;
        $plataforma_importa = NULL;
        $estado_guia_sistema = 1;
        $id_factura_origen = NULL;
        $impreso = NULL;
        $facturada = NULL;
        $factura_numero = NULL;
        $numero_guia = NULL;
        $anulado = 0;

        $response =  $this->model->generarPedido(
            $fecha,
            $id_cliente,
            $id_vendedor,
            $condiciones,
            $monto_factura,
            $estado_factura,
            $id_user_factura,
            $validez,
            $id_sucursal,
            $nombre,
            $telefono,
            $provincia,
            $calle_principal,
            $ciudad,
            $calle_secundaria,
            $referencia,
            $observacion,
            $guia_enviada,
            $transporte,
            $identificacion,
            $celular,
            $cod,
            $valor_segura,
            $dropshipping,
            $tienda,
            $importado,
            $plataforma_importa,
            $estado_guia_sistema,
            $id_factura_origen,
            $impreso,
            $facturada,
            $factura_numero,
            $numero_guia,
            $anulado,
            $id_plataforma = $_SESSION['id_plataforma']
        );
        echo json_encode($response);
    }

    public function obtener_guias($filtro = "")
    {

        $data = $this->model->cargarGuias($filtro);
        echo json_encode($data);
    }
}
