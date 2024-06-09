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

        $importado = $_POST['importado'] ?? 0;
        $plataforma_importa = $_POST['plataforma_importa'] ?? 0;
        $cod = $_POST['cod'] ?? 0;
        $estado_guia_sistema = 1;
        $impreso = 0;
        $facturada = 0;
        $factura_numero = 0;
        $numero_guia = 0;
        $anulada = 0;
        $id_plataforma = $_SESSION['id_plataforma'];

        ///origen
        $identificacionO = $_POST['identificacionO'] ?? "";
        $celularO = $_POST['celularO'] ?? $telefono_cliente;
        $nombreO = $_POST['nombreO'];
        $ciudadO = $_POST['ciudadO'];
        $provinciaO = $_POST['provinciaO'];
        $direccionO = $_POST['direccionO'];
        $referenciaO = $_POST['referenciaO'];
        $numeroCasaO = $_POST['numeroCasaO'] ?? 0;

        $valor_segura = $_POST['valor_segura'] ?? 0;
        $no_piezas = $_POST['no_piezas'] ?? 1;
        $tipo_servicio = "201202002002013";
        $peso = "2";
        $contiene = $_POST['contiene'] ?? "";
        $costo_flete = $_POST['costo_flete'] ?? 0;
        $costo_producto = $_POST['costo_producto'] ?? 0;
        $comentario = $_POST['comentario'] ?? "";
        $id_transporte = $_POST['id_transporte'] ?? 0;

        $response = $this->model->nuevo_pedido($fecha_factura, $id_usuario, $monto_factura, $estado_factura, $nombre_cliente, $telefono_cliente, $c_principal, $ciudad_cot, $c_secundaria, $referencia, $observacion, $guia_enviada, $transporte, $identificacion, $celular, $id_producto_venta, $dropshipping, $id_plataforma, $dueño_id, $importado, $plataforma_importa, $cod, $estado_guia_sistema, $impreso, $facturada, $factura_numero, $numero_guia, $anulada, $identificacionO, $celularO, $nombreO, $ciudadO, $provinciaO, $direccionO, $referenciaO, $numeroCasaO, $valor_segura, $no_piezas, $tipo_servicio, $peso, $contiene, $costo_flete, $costo_producto, $comentario, $id_transporte);
        echo json_encode($response);
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

    public function buscarTmp()
    {
        $response = $this->model->buscarTmp();
        if($response == null || empty($response)){
            $arr = array([
                'id_producto' => 0,
                'nombre' => '',
                'precio' => 0,
                'descuento' => 0,
                'cantidad' => 0,
                'total' => 0,
                'sku' => '',
                'id_tmp' => 0]);
          return   json_encode($arr)    ;
        }
        $id_producto = $response[0]['id_producto'];

        $response2 = $this->model->buscarBodega($id_producto);

        $response = array(
            'tmp' => $response,
            'bodega' => $response2
        );
        echo json_encode($response);
    }

    public function buscarProductosBodega($id_producto)
    {
        $sku = $_POST['sku'];
        $response = $this->model->buscarProductosBodega($id_producto, $sku, $_SESSION['id_plataforma']);
        echo json_encode($response);
    }

    public function recuperarOrigenBodega($producto)
    {
        $sku = $_POST['sku'];
        $response = $this->model->recuperarOrigenBodega($producto, $sku, $_SESSION['id_plataforma']);
        echo json_encode($response);
    }

    public function eliminarTmp($id_tmp)
    {
        $response = $this->model->eliminarTmp($id_tmp);
        echo json_encode($response);
    }

    public function buscarBodega ($id_producto)
    {
        $response = $this->model->buscarBodega($id_producto);
        echo json_encode($response);
    }

    public function actualizarTmp($id_tmp){
        $descuento = $_POST['descuento'];
        $precio = $_POST['precio'];
        $response = $this->model->actualizarTmp($id_tmp, $descuento, $precio);

        echo json_encode($response);
    }


    
}
