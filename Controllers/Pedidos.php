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
        $celular = $_POST['telefono'];
        $calle_principal = $_POST['calle_principal'];
        $calle_secundaria = $_POST['calle_secundaria'];
        $numero_casa = $_POST['numero_casa'];
        $referencia = $_POST['referencia'];
        $ciudad = $_POST['ciudad'];
        $provincia = $_POST['provincia'];
        $observacion = $_POST['observacion'];
        $guia_enviada = 0;
        $anulado = 0;
        $transporte = NULL;
        $identificacion = NULL;
        $cod = NULL;
        $valor_segura = NULL;
        $dropshipping = $_POST['dropshipping'];
        $tienda = $_POST['tienda'];
        $plataforma_importa = NULL;
        $estado_guia_sistema = 1;
        $id_factura_origen = NULL;
        $factura_numero = NULL;
        $numero_guia = NULL;

        $response =  $this->model->generarPedido($fecha, $id_cliente, $id_vendedor, $condiciones, $monto_factura, $estado_factura, $id_user_factura, $validez, $id_sucursal, $nombre, $telefono, $celular, $calle_principal, $calle_secundaria, $numero_casa, $referencia, $ciudad, $provincia, $observacion, $guia_enviada, $anulado, $transporte, $identificacion, $cod, $valor_segura, $dropshipping, $tienda, $plataforma_importa, $estado_guia_sistema, $id_factura_origen, $factura_numero, $numero_guia, $_SESSION['id_plataforma']);
        echo json_encode($response);
    }
}
