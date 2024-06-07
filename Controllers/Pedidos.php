<?php
session_start();
class Pedidos extends Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->isAuth()) {
            header("Location:  " . SERVERURL . "login");
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
        $fecha_factura = date("Y-m-d H:i:s");
        $id_usuario = $_SESSION['id'];
        $monto_factura = $_POST['total_venta'];
        $estado_factura = 1;
        $nombre_cliente = $_POST['nombre'];
        $telefono_cliente = $_POST['telefono'];
        $c_principal = $_POST['calle_principal'];
        $ciudad_cot = $_POST['ciudad'];
        $c_secundaria = $_POST['calle_secundaria'];
        $referencia = $_POST['referencia'];
        $observacion    = $_POST['observacion'];
        $guia_enviada = 1;
        $transporte = $_POST['transporte'];
        $identificacion = $_POST['identificacion'] ?? "";
        $celular = $_POST['celular'] ?? $telefono_cliente;
        ///

        $id_producto_venta = $_POST['id_producto_venta'];

        $dropshipping = $_POST['dropshipping'] ?? 0;
        $id_plataforma = $_SESSION['id_plataforma'];
        $dueño_id = $this->obtener_propietario($id_producto_venta);
        if ($dueño_id == $id_plataforma) {
            $dropshipping = 0;
        } else {
            $dropshipping = 1;
        }
    }

    public function obtener_propietario($id_producto)
    {
        $datos = $this->model->obtenerDestinatario($id_producto);
    }

    public function obtener_guias($filtro = "")
    {

        $data = $this->model->cargarGuias($filtro);
        echo json_encode($data);
    }

    public function info()
    {
        print_r($_SESSION);
    }
}
