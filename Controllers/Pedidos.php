<?php


session_start();
class Pedidos extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index($filtro = "")
    {
        if (!$this->isAuth()) {
            header("Location: " . SERVERURL . "login");
        }
        $data = $this->model->cargarPedidosIngresados($filtro);
        $this->views->render($this, "index", $data);
    }
    public function ingreso($filtro = "")
    {
        if (!$this->isAuth()) {
            header("Location: " . SERVERURL . "login");
        }
        $data = $this->model->cargarPedidosIngresados($filtro);
        $this->views->render($this, "index", $data);
    }
    public function guias($filtro = "")
    {
        if (!$this->isAuth()) {
            header("Location: " . SERVERURL . "login");
        }
        $this->views->render($this, "guias");
    }
    public function guias_administrador($filtro = "")
    {
        if (!$this->isAuth()) {
            header("Location: " . SERVERURL . "login");
        }
        $this->views->render($this, "guias_administrador");
    }
    public function guias_administrador_2($filtro = "")
    {
        $this->views->render($this, "guias_administrador_2");
    }
    public function anuladas($filtro = "")
    {
        if (!$this->isAuth()) {
            header("Location: " . SERVERURL . "login");
        }
        $data = $this->model->cargarAnuladas($filtro);
        $this->views->render($this, "anuladas");
    }
    public function anuladas_administrador($filtro = "")
    {
        if (!$this->isAuth()) {
            header("Location: " . SERVERURL . "login");
        }
        $data = $this->model->cargarAnuladas($filtro);
        $this->views->render($this, "anuladas_administrador");
    }
    public function nuevo()
    {
        if (!$this->isAuth()) {
            header("Location: " . SERVERURL . "login");
        }
        $this->views->render($this, "nuevo");
    }
    public function nuevo_2()
    {
        if (!$this->isAuth()) {
            header("Location: " . SERVERURL . "login");
        }
        $this->views->render($this, "nuevo_2");
    }
    public function ver($id)
    {
        if (!$this->isAuth()) {
            header("Location: " . SERVERURL . "login");
        }
        if (empty($id))
            header("Location: " . SERVERURL . "Pedidos");

        $this->views->render($this, "ver", $id);
    }

    public function editar($id)
    {
        if (!$this->isAuth()) {
            header("Location: " . SERVERURL . "login");
        }
        if (empty($id))
            header("Location: " . SERVERURL . "Pedidos");

        $this->views->render($this, "editar_pedido", $id);
    }

    public function editar_2($id)
    {
        if (!$this->isAuth()) {
            header("Location: " . SERVERURL . "login");
        }
        if (empty($id))
            header("Location: " . SERVERURL . "Pedidos");

        $this->views->render($this, "editar_pedido_2", $id);
    }
    public function novedades()
    {
        if (!$this->isAuth()) {
            header("Location: " . SERVERURL . "login");
        }
        $this->views->render($this, "novedades");
    }

    public function chat_imporsuit($filtro = "")
    {
        if (!$this->isAuth()) {
            header("Location: " . SERVERURL . "login");
        }
        $this->views->render($this, "chat_imporsuit");
    }

    public function plantillas_chat_center($filtro = "")
    {
        if (!$this->isAuth()) {
            header("Location: " . SERVERURL . "login");
        }
        $this->views->render($this, "plantillas_chat_center");
    }

    public function configuracion_chats_imporsuit($filtro = "")
    {
        if (!$this->isAuth()) {
            header("Location: " . SERVERURL . "login");
        }
        $this->views->render($this, "configuracion_chats_imporsuit");
    }

    public function local()
    {
        if (!$this->isAuth()) {
            header("Location: " . SERVERURL . "login");
        }
        $this->views->render($this, "guias_speed");
    }

    public function scanner_speed()
    {
        if (!$this->isAuth()) {
            header("Location: " . SERVERURL . "login");
        }
        $this->views->render($this, "scanner_speed");
    }

    ///Funciones

    public function datosPlataformas()
    {
        $tienda = $_POST["tienda"];
        $data = $this->model->datosPlataformas($tienda);
        echo json_encode($data);
    }

    /* worker cola pedido*/
    public function anadir_cola()
    {
        try {
            // Conexión a Redis
            $redis = new Redis();
            $redis->connect('3.233.119.65', 6379);

            $tmp = session_id();

            // Obtén los datos enviados desde la solicitud AJAX
            $input = file_get_contents('php://input');
            $pedidoData = json_decode($input, true);

            if (!$pedidoData) {
                throw new Exception("Datos de pedido inválidos.");
            }

            // Generar estructura del pedido en base a los datos recibidos
            $pedido = [
                'fecha_factura' => date("Y-m-d H:i:s"),
                'id_usuario' => $_SESSION['id'] ?? 0,
                'monto_factura' => $pedidoData['total_venta'],
                'estado_factura' => 1,
                'nombre_cliente' => $pedidoData['nombre'],
                'telefono_cliente' => $pedidoData['telefono'],
                'c_principal' => $pedidoData['calle_principal'],
                'ciudad_cot' => $pedidoData['ciudad'],
                'c_secundaria' => $pedidoData['calle_secundaria'],
                'provincia' => $pedidoData['provincia'],
                'referencia' => $pedidoData['referencia'],
                'observacion' => $pedidoData['observacion'],
                'guia_enviada' => 0,
                'transporte' => $pedidoData['transporte'],
                'identificacion' => $pedidoData['identificacion'] ?? "",
                'celular' => $pedidoData['celular'] ?? $pedidoData['telefono'],

                // Datos adicionales
                'id_producto_venta' => $pedidoData['id_producto_venta'],
                'dropshipping' => $pedidoData['dropshipping'] ?? 0,
                'importado' => $pedidoData['importado'] ?? 0,
                'plataforma_importa' => $pedidoData['plataforma_importa'] ?? 0,
                'cod' => $pedidoData['cod'] ?? 0,
                'estado_guia_sistema' => 1,
                'impreso' => 0,
                'facturada' => 0,
                'factura_numero' => 0,
                'numero_guia' => 0,
                'anulada' => 0,
                'id_plataforma' => $_SESSION['id_plataforma'] ?? $pedidoData['id_plataforma'],
                'dueño_id' => $this->obtener_propietario($pedidoData['id_producto_venta']),

                // Datos del origen
                'identificacionO' => $pedidoData['identificacionO'] ?? "",
                'celularO' => $pedidoData['celularO'] ?? $pedidoData['telefono'],
                'nombreO' => $pedidoData['nombreO'],
                'ciudadO' => $pedidoData['ciudadO'] ?? 0,
                'provinciaO' => $pedidoData['provinciaO'] ?? 0,
                'direccionO' => $pedidoData['direccionO'] ?? "vacio",
                'referenciaO' => $pedidoData['referenciaO'],
                'numeroCasaO' => $pedidoData['numeroCasaO'] ?? 0,

                // Más datos
                'valor_segura' => $pedidoData['valor_seguro'] ?? 0,
                'no_piezas' => $pedidoData['no_piezas'] ?? 1,
                'tipo_servicio' => "201202002002013",
                'peso' => "2",
                'contiene' => $pedidoData['contiene'] ?? "",
                'costo_flete' => $pedidoData['costo_flete'] ?? 0,
                'costo_producto' => $pedidoData['costo_producto'] ?? 0,
                'comentario' => $pedidoData['comentario'] ?? "",
                'id_transporte' => $pedidoData['id_transporte'] ?? 0,
                'id_bodega' => $pedidoData['id_propietario'],
                'tmp' => $tmp,
            ];

            // Convierte el pedido a JSON para encolarlo
            $pedidoJson = json_encode($pedido);

            // Añadir pedido a la cola en Redis
            if (!$redis->lPush("queue:facturas", $pedidoJson)) {
                throw new Exception("No se pudo encolar el pedido en Redis.");
            }

            // Respuesta de éxito
            echo json_encode([
                'status' => 200,
                'title' => 'Éxito',
                'message' => 'El pedido fue añadido a la cola correctamente.',
            ]);
        } catch (Exception $e) {
            // Manejo de errores
            file_put_contents('/ruta/a/logs/redis_error.log', $e->getMessage() . PHP_EOL, FILE_APPEND);

            echo json_encode([
                'status' => 500,
                'title' => 'Error',
                'message' => 'Hubo un error al intentar añadir el pedido a la cola.',
            ]);
        }
    }

    /* fin worker cola pedido*/

    public function nuevo_pedido()
    {
        $fecha_factura = date("Y-m-d H:i:s");
        $id_usuario = $_SESSION['id'] ?? 0;
        $monto_factura = $_POST['total_venta'];
        $estado_factura = 1;
        $nombre_cliente = $_POST['nombre'];
        $telefono_cliente = $_POST['telefono'];
        $c_principal = $_POST['calle_principal'];
        $ciudad_cot = $_POST['ciudad'];
        $c_secundaria = $_POST['calle_secundaria'];
        $provincia = $_POST['provincia'];
        $referencia = $_POST['referencia'];
        $observacion    = $_POST['observacion'];
        $guia_enviada = 0;
        $transporte = $_POST['transporte'];
        $identificacion = $_POST['identificacion'] ?? "";
        $celular = $_POST['celular'] ?? $telefono_cliente;
        ///

        $id_producto_venta = $_POST['id_producto_venta'];

        $dropshipping = $_POST['dropshipping'] ?? 0;
        $id_plataforma = $_SESSION['id_plataforma'] ?? $_POST['id_plataforma'];
        $dueño_id = $this->obtener_propietario($id_producto_venta);

        if ($dueño_id == $id_plataforma) {
            $dropshipping = 0;
        } else {
            $dropshipping = 1;
        }

        $importado = $_POST['importado'] ?? 0;
        $plataforma_importa = $_POST['plataforma_importa'] ?? 0;
        $cod = $_POST['recaudo'] ?? 0;
        $estado_guia_sistema = 1;
        $impreso = 0;
        $facturada = 0;
        $factura_numero = 0;
        $numero_guia = 0;
        $anulada = 0;
        $id_plataforma = $_SESSION['id_plataforma'] ?? $_POST['id_plataforma'];

        ///origen
        $identificacionO = $_POST['identificacionO'] ?? "";
        $celularO = $_POST['celularO'] ?? $telefono_cliente;
        $nombreO = $_POST['nombreO'];
        $ciudadO = $_POST['ciudadO'] ?? 0;
        $provinciaO = $_POST['provinciaO'] ?? 0;
        $direccionO = $_POST['direccionO'] ?? "vacio";
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
        $id_bodega = $_POST['id_propietario']; //CODIGO DE LA BODEGA
        $response = $this->model->nuevo_pedido($fecha_factura, $id_usuario, $monto_factura, $estado_factura, $nombre_cliente, $telefono_cliente, $c_principal, $ciudad_cot, $c_secundaria, $referencia, $observacion, $guia_enviada, $transporte, $identificacion, $celular, $dueño_id, $dropshipping, $id_plataforma, $dueño_id, $importado, $plataforma_importa, $cod, $estado_guia_sistema, $impreso, $facturada, $factura_numero, $numero_guia, $anulada, $identificacionO, $celularO, $nombreO, $ciudadO, $provinciaO, $direccionO, $referenciaO, $numeroCasaO, $valor_segura, $no_piezas, $tipo_servicio, $peso, $contiene, $costo_flete, $costo_producto, $comentario, $id_transporte, $provincia, $id_bodega);

        echo json_encode($response);
    }

    public function nuevo_pedido_shopify()
    {

        $fecha_factura = date("Y-m-d H:i:s");
        $id_usuario = $_SESSION['id'] ?? 0;
        $monto_factura = $_POST['total_venta'];
        $estado_factura = 1;
        $nombre_cliente = $_POST['nombre'];
        $telefono_cliente = $_POST['telefono'];
        $c_principal = $_POST['calle_principal'];
        $ciudad_cot = $_POST['ciudad'];
        $c_secundaria = $_POST['calle_secundaria'];
        $provincia = $_POST['provincia'];
        $referencia = $_POST['referencia'];
        $observacion    = $_POST['observacion'];
        $guia_enviada = 0;
        $transporte = $_POST['transporte'];
        $identificacion = $_POST['identificacion'] ?? "";
        $celular = $_POST['celular'] ?? $telefono_cliente;
        ///

        $id_producto_venta = $_POST['id_producto_venta'];

        $dropshipping = $_POST['dropshipping'] ?? 0;
        $id_plataforma = $_SESSION['id_plataforma'] ?? $_POST['id_plataforma'];
        $dueño_id = $this->obtenerDestinatarioShopify($id_producto_venta);

        if ($dueño_id == $id_plataforma) {
            $dropshipping = 0;
        } else {
            $dropshipping = 1;
        }

        $importado = $_POST['importado'] ?? 0;
        $plataforma_importa = $_POST['plataforma_importa'] ?? 0;
        $cod = $_POST['recaudo'] ?? 0;
        $estado_guia_sistema = 1;
        $impreso = 0;
        $facturada = 0;
        $factura_numero = 0;
        $numero_guia = 0;
        $anulada = 0;
        $id_plataforma = $_SESSION['id_plataforma'] ?? $_POST['id_plataforma'];

        ///origen
        $identificacionO = $_POST['identificacionO'] ?? "";
        $celularO = $_POST['celularO'] ?? $telefono_cliente;
        $nombreO = $_POST['nombreO'];
        $ciudadO = $_POST['ciudadO'] ?? 0;
        $provinciaO = $_POST['provinciaO'] ?? 0;
        $direccionO = $_POST['direccionO'] ?? "vacio";
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

        $productos = $_POST['productos'];

        $id_bodega = $_POST['id_bodega']; //CODIGO DE LA BODEGA

        $response = $this->model->nuevo_pedido_shopify($fecha_factura, $id_usuario, $monto_factura, $estado_factura, $nombre_cliente, $telefono_cliente, $c_principal, $ciudad_cot, $c_secundaria, $referencia, $observacion, $guia_enviada, $transporte, $identificacion, $celular, $dueño_id, $dropshipping, $id_plataforma, $dueño_id, $importado, $plataforma_importa, $cod, $estado_guia_sistema, $impreso, $facturada, $factura_numero, $numero_guia, $anulada, $identificacionO, $celularO, $nombreO, $ciudadO, $provinciaO, $direccionO, $referenciaO, $numeroCasaO, $valor_segura, $no_piezas, $tipo_servicio, $peso, $contiene, $costo_flete, $costo_producto, $comentario, $id_transporte, $provincia, $productos, $id_bodega);

        echo json_encode($response);
    }

    public function nuevo_pedido_tienda()
    {
        $fecha_factura = date("Y-m-d H:i:s");
        $id_usuario = $_SESSION['id'] ?? 0;
        $monto_factura = $_POST['total_venta'];
        $estado_factura = 1;
        $nombre_cliente = $_POST['nombre'];
        $telefono_cliente = $_POST['telefono'];
        $c_principal = $_POST['calle_principal'];
        $ciudad_cot = $_POST['ciudad'];
        $c_secundaria = $_POST['calle_secundaria'];
        $provincia = $_POST['provincia'];
        $referencia = $_POST['referencia'];
        $observacion    = $_POST['observacion'];
        $guia_enviada = 0;
        $transporte = $_POST['transporte'];
        $identificacion = $_POST['identificacion'] ?? "";
        $celular = $_POST['celular'] ?? $telefono_cliente;

        $id_producto_venta = $_POST['id_producto_venta'];

        $dropshipping = $_POST['dropshipping'] ?? 0;
        $id_plataforma = $_SESSION['id_plataforma'] ?? $_POST['id_plataforma'];

        $productos = $_POST['productos'];
    }

    public function cargarPedidos()
    {
        $data = $this->model->pedidos($_SESSION["id_plataforma"]);
        echo json_encode($data);
    }

    public function eliminarPedido($id_factura)
    {
        $response = $this->model->eliminarPedido($id_factura);
        echo json_encode($response);
    }

    public function verPedido($id)
    {
        $data = $this->model->cargarPedido($id);
        echo json_encode($data);
    }


    public function anular_pedido($id)
    {
        $response = $this->model->anular_pedido($id);
        echo json_encode($response);
    }

    public function datos_pedido($id)
    {
        $data = $this->model->datosPedido($id);
        echo json_encode($data);
    }

    public function editar_pedido()
    {
        $id_factura = $_POST['id_factura'];
        $nombre_cliente = $_POST['nombre'];
        $telefono_cliente = $_POST['telefono'];
        $c_principal = $_POST['calle_principal'];
        $ciudad_cot = $_POST['ciudad'];
        $c_secundaria = $_POST['calle_secundaria'];
        $provincia = $_POST['provincia'];
        $referencia = $_POST['referencia'];
        $observacion    = $_POST['observacion'];
        $guia_enviada = 1;
        $transporte = $_POST['transporte'];
    }

    public function obtener_propietario($id_producto)
    {
        $datos = $this->model->obtenerDestinatario($id_producto);
        return  $datos;
    }
    public function obtenerDestinatarioShopify($id_producto)
    {
        $datos = $this->model->obtenerDestinatarioShopify($id_producto);
        return  $datos;
    }

    public function obtener_guias()
    {
        $fecha_inicio = $_POST['fecha_inicio'] ?? "";
        $fecha_fin = $_POST['fecha_fin'] ?? "";
        $transportadora = $_POST['transportadora'] ?? "";
        $estado = $_POST['estado'] ?? "";
        $drogshipin = $_POST['drogshipin'] ?? "";
        $impreso = $_POST['impreso'] ?? "";
        $despachos = $_POST['despachos'] ?? "";

        $data = $this->model->cargarGuias($_SESSION['id_plataforma'], $fecha_inicio, $fecha_fin, $transportadora, $estado, $impreso, $drogshipin, $despachos);
        echo json_encode($data);
    }

    public function obtener_guias_anuladas()
    {
        $fecha_inicio = $_POST['fecha_inicio'] ?? "";
        $fecha_fin = $_POST['fecha_fin'] ?? "";
        $transportadora = $_POST['transportadora'] ?? "";

        $data = $this->model->cargarGuiasAnuladas($_SESSION['id_plataforma'], $fecha_inicio, $fecha_fin, $transportadora);
        echo json_encode($data);
    }


    public function obtener_guias_retraso()
    {
        $fecha_inicio = $_POST['fecha_inicio'] ?? "";
        $fecha_fin = $_POST['fecha_fin'] ?? "";
        $transportadora = $_POST['transportadora'] ?? "";
        $estado = $_POST['estado'] ?? "";
        $drogshipin = $_POST['drogshipin'] ?? "";
        $impreso = $_POST['impreso'] ?? "";
        $data = $this->model->cargarGuiasRetraso($_SESSION['id_plataforma'], $fecha_inicio, $fecha_fin, $transportadora, $impreso, $drogshipin);
        echo json_encode($data);
    }

    public function obtener_guiasAdministrador()
    {
        $fecha_inicio = $_POST['fecha_inicio'] ?? "";
        $fecha_fin = $_POST['fecha_fin'] ?? "";
        $transportadora = $_POST['transportadora'] ?? "";
        $estado = $_POST['estado'] ?? "";
        $drogshipin = $_POST['drogshipin'] ?? "";
        $impreso = $_POST['impreso'] ?? "";
        $despachos = $_POST['despachos'] ?? "";
        /*  $start = $_POST['start'] ?? 0;
        $length = $_POST['length'] ?? 25; */
        $data = $this->model->cargarGuiasAdministrador($fecha_inicio, $fecha_fin, $transportadora, $estado, $impreso, $drogshipin, $despachos);
        echo json_encode($data);
    }
    private function sanitizeDate($date)
    {
        $timestamp = strtotime($date);
        if ($timestamp === false) {
            throw new InvalidArgumentException("Fecha inválida: $date");
        }
        return date('Y-m-d', $timestamp); // Retorna formato estándar
    }

    private function sanitizeString($string)
    {
        return htmlspecialchars(trim($string), ENT_QUOTES, 'UTF-8');
    }

    public function obtener_guiasAdministrador2()
    {
        $fecha_inicio = $_POST['fecha_inicio'] ?? "";
        $fecha_fin = $_POST['fecha_fin'] ?? "";
        $transportadora = $_POST['transportadora'] ?? "";
        $estado = $_POST['estado'] ?? "";
        $drogshipin = $_POST['drogshipin'] ?? "";
        $impreso = $_POST['impreso'] ?? "";
        $despachos = $_POST['despachos'] ?? "";
        /*  $start = $_POST['start'] ?? 0;
        $length = $_POST['length'] ?? 25; */
        $data = $this->model->cargarGuiasAdministrador2($fecha_inicio, $fecha_fin, $transportadora, $estado, $impreso, $drogshipin, $despachos, "2024-12-21", 25);
        echo json_encode($data);
    }


    public function obtener_guiasAnuladas_admin()
    {
        $fecha_inicio = $_POST['fecha_inicio'] ?? "";
        $fecha_fin = $_POST['fecha_fin'] ?? "";
        $transportadora = $_POST['transportadora'] ?? "";
        $data = $this->model->cargarGuiasAnuladas_admin($fecha_inicio, $fecha_fin, $transportadora);
        echo json_encode($data);
    }

    public function obtener_guiasSpeed()
    {
        $fecha_inicio = $_POST['fecha_inicio'] ?? "";
        $fecha_fin = $_POST['fecha_fin'] ?? "";
        $transportadora = $_POST['transportadora'] ?? "";
        $estado = $_POST['estado'] ?? "";
        $drogshipin = $_POST['drogshipin'] ?? "";
        $impreso = $_POST['impreso'] ?? "";
        $despachos = $_POST['despachos'] ?? "";
        $recibo = $_POST['recibo'] ?? "";
        $data = $this->model->cargarGuiasSpeed($fecha_inicio, $fecha_fin, $transportadora, $estado, $impreso, $drogshipin, $despachos, $recibo);
        echo json_encode($data);
    }

    public function info()
    {
        print_r($_SESSION);
    }

    public function buscarTmp()
    {

        $response = $this->model->buscarTmp();
        //print_r($response);
        if ($response == null || empty($response)) {

            $arr = array([
                'id_producto' => 0,
                'nombre' => '',
                'precio' => 0,
                'descuento' => 0,
                'cantidad' => 0,
                'total' => 0,
                'sku' => '',
                'id_tmp' => 0
            ]);
            $tmp = array(
                'tmp' => $arr,
                'bodega' => []
            );
            echo json_encode($tmp);
            return;
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

        $response = $this->model->buscarProductosBodega($id_producto, $sku);
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

    public function buscarBodega($id_producto)
    {
        $response = $this->model->buscarBodega($id_producto);
        echo json_encode($response);
    }

    public function actualizarTmp($id_tmp)
    {
        $descuento = $_POST['descuento'];
        $precio = $_POST['precio'];
        $cantidad = $_POST['cantidad'];
        $response = $this->model->actualizarTmp($id_tmp, $descuento, $precio, $cantidad);

        echo json_encode($response);
    }

    public function actualizarDetalle($id_detalle)
    {
        $descuento = $_POST['descuento'];
        $precio = $_POST['precio'];
        $cantidad = $_POST['cantidad'];
        $response = $this->model->actualizarDetalle($id_detalle, $descuento, $precio, $cantidad);

        echo json_encode($response);
    }

    public function agregarDetalle()
    {
        $cantidad = $_POST['cantidad'];
        $precio = $_POST['precio'];
        $id_producto = $_POST['id_producto'];
        $id_inventario = $_POST['id_inventario'];
        $sku = $_POST['sku'];
        $id_factura = $_POST['id_factura'];

        $response = $this->model->agregarDetalle($id_producto, $cantidad, $precio, $_SESSION['id_plataforma'], $sku, $id_factura, $id_inventario);
        echo json_encode($response);
    }

    public function obtenerDetalle()
    {
        $id_factura = $_POST['id_factura'];

        $response = $this->model->obtenerDetalleFactura($id_factura, $_SESSION['id_plataforma']);
        echo json_encode($response);
    }

    public function obtenerDetalleWallet()
    {
        $numero_factura = $_POST['numero_factura'];
        $response = $this->model->obtenerDetalleWallet($numero_factura);
        echo json_encode($response);
    }

    public function eliminarDescripcion($id_detalle)
    {

        $response = $this->model->eliminarDescripcion($id_detalle);

        echo json_encode($response);
    }

    public function validaDevolucion($telefono)
    {
        $response = $this->model->validaDevolucion($telefono);

        echo json_encode($response);
    }

    public function novedadSpeed()
    {
        $id_pedido = $_POST['id_pedido'];
        $novedad = $_POST['novedad'];
        $tipo = $_POST['tipo'];
        $response = $this->model->novedadSpeed($id_pedido, $novedad, $tipo);
        echo json_encode($response);
    }

    /* APIS Chat center */
    public function mensajes_clientes()
    {
        $id_cliente = $_POST['id_cliente'];
        $response = $this->model->mensajes_clientes($id_cliente, $_SESSION['id_plataforma']);
        echo json_encode($response);
    }

    public function cambiar_vistos()
    {
        $id_cliente = $_POST['id_cliente'];
        $response = $this->model->cambiar_vistos($id_cliente);
        echo json_encode($response);
    }

    public function ultimo_mensaje_cliente()
    {
        $id_cliente = $_POST['id_cliente'];
        $ultimo_mensaje_id = isset($_POST['ultimo_mensaje_id']) ? $_POST['ultimo_mensaje_id'] : null;
        $response = $this->model->ultimo_mensaje_cliente($id_cliente, $ultimo_mensaje_id);
        echo json_encode($response);
    }

    public function numero_cliente()
    {
        $id_cliente = $_POST['id_cliente'];
        $response = $this->model->numero_cliente($id_cliente, $_SESSION['id_plataforma']);
        echo json_encode($response);
    }

    public function numeros_clientes()
    {
        $busqueda = $_POST['busqueda'];
        $response = $this->model->numeros_clientes($_SESSION['id_plataforma'], $busqueda);
        echo json_encode($response);
    }

    public function obtener_url_video_mensaje()
    {
        $id_mensaje = $_POST['id_mensaje'];
        $response = $this->model->obtener_url_video_mensaje($id_mensaje);
        echo json_encode($response);
    }

    public function agregar_mensajes_enviados()
    {
        $id_cliente = $_POST['id_cliente'];

        $response = $this->model->agregar_detalle_combo($id_cliente, $_SESSION['id_plataforma']);

        echo json_encode($response);
    }

    public function guardar_audio_Whatsapp()
    {
        $response = $this->model->guardar_audio_Whatsapp($_FILES['audio']);
        echo json_encode($response);
    }

    public function guardar_documento_Whatsapp()
    {
        $response = $this->model->guardar_documento_Whatsapp($_FILES['documento']);
        echo json_encode($response);
    }


    public function guardar_imagen_Whatsapp()
    {
        $response = $this->model->guardar_imagen_Whatsapp($_FILES['imagen']);
        echo json_encode($response);
    }

    public function guardar_video_Whatsapp()
    {
        $response = $this->model->guardar_video_Whatsapp($_FILES['video']);
        echo json_encode($response);
    }


    public function agregar_mensaje_enviado()
    {
        $texto_mensaje = $_POST['texto_mensaje'];
        $tipo_mensaje = $_POST['tipo_mensaje'];
        $mid_mensaje = $_POST['mid_mensaje'];
        $id_recibe = $_POST['id_recibe'];
        $ruta_archivo = $_POST['ruta_archivo'];
        $telefono_configuracion = $_POST['telefono_configuracion'];
        $telefono_recibe = $_POST['telefono_recibe'];

        // Verificar si existe la sesión de id_plataforma
        $id_plataforma = isset($_SESSION['id_plataforma']) ? $_SESSION['id_plataforma'] : $_POST['id_plataforma'];

        $response = $this->model->agregar_mensaje_enviado($texto_mensaje, $tipo_mensaje, $mid_mensaje, $id_recibe, $id_plataforma, $ruta_archivo, $telefono_configuracion, $telefono_recibe);
        echo json_encode($response);
    }

    public function obtener_etiquetas()
    {
        // Verificar si existe la sesión de id_plataforma
        $id_plataforma = isset($_SESSION['id_plataforma']) ? $_SESSION['id_plataforma'] : $_POST['id_plataforma'];

        $response = $this->model->obtener_etiquetas($id_plataforma);
        echo json_encode($response);
    }

    public function obtener_etiquetas_asignadas()
    {
        $id_cliente_chat_center = $_POST['id_cliente_chat_center'];

        $response = $this->model->obtener_etiquetas_asignadas($id_cliente_chat_center);
        echo json_encode($response);
    }

    public function toggle_etiqueta_asignacion()
    {
        $id_cliente_chat_center = $_POST['id_cliente_chat_center'];
        $id_etiqueta = $_POST['id_etiqueta'];
        $id_plataforma = $_POST['id_plataforma'];

        $response = $this->model->toggle_etiqueta_asignacion($id_cliente_chat_center, $id_etiqueta, $id_plataforma);
        echo json_encode($response);
    }

    public function asignar_etiqueta_automatizador()
    {
        $id_cliente = $_POST['id_cliente'];
        $id_etiqueta = $_POST['id_etiqueta'];
        $id_plataforma = $_POST['id_plataforma'];

        $response = $this->model->asignar_etiqueta_automatizador($id_cliente, $id_etiqueta, $id_plataforma);
        echo json_encode($response);
    }

    public function eliminarEtiqueta($id_etiqueta)
    {
        $response = $this->model->eliminarEtiqueta($id_etiqueta);
        echo json_encode($response);
    }

    public function agregar_etiqueta()
    {
        $nombre_etiqueta = $_POST['nombre_etiqueta'];
        $color_etiqueta = $_POST['color_etiqueta'];

        // Verificar si existe la sesión de id_plataforma
        $id_plataforma = isset($_SESSION['id_plataforma']) ? $_SESSION['id_plataforma'] : $_POST['id_plataforma'];

        $response = $this->model->agregar_etiqueta($nombre_etiqueta, $color_etiqueta, $id_plataforma);
        echo json_encode($response);
    }

    public function asignar_etiqueta()
    {
        $id_etiqueta = $_POST['id_etiqueta'];
        $id_cliente_chat = $_POST['id_cliente_chat'];

        $response = $this->model->asignar_etiqueta($id_etiqueta, $id_cliente_chat);
        echo json_encode($response);
    }

    public function cambiar_estado_pedido()
    {
        $id_factura = $_POST['id_factura'];
        $estado_nuevo = $_POST['estado_nuevo'];
        $detalle_noDesea_pedido = $_POST['detalle_noDesea_pedido'];

        $response = $this->model->cambiar_estado_pedido($id_factura, $estado_nuevo, $detalle_noDesea_pedido);
        echo json_encode($response);
    }

    public function agregar_detalle_noDesea()
    {
        $id_factura = $_POST['id_factura_ingresar_motivo'];
        $motivo_noDesea = $_POST['motivo_noDesea'];

        $response = $this->model->agregar_detalle_noDesea($id_factura, $motivo_noDesea);
        echo json_encode($response);
    }

    /* automatizador */
    public function configuraciones_automatizador()
    {
        $response = $this->model->configuraciones_automatizador($_SESSION['id_plataforma']);
        echo json_encode($response);
    }

    public function agregar_configuracion()
    {
        $nombre_configuracion = $_POST['nombre_configuracion'];
        $telefono = $_POST['telefono'];
        $id_telefono = $_POST['id_telefono'];
        $id_whatsapp = $_POST['id_whatsapp'];
        $token = $_POST['token'];
        $webhook_url = $_POST['webhook_url'];

        $response = $this->model->agregar_configuracion($nombre_configuracion, $telefono, $id_telefono, $id_whatsapp, $token, $webhook_url, $_SESSION['id_plataforma']);
        echo json_encode($response);
    }

    public function agregar_automatizador()
    {
        $nombre_automatizador = $_POST['nombre_automatizador'];
        $id_configuracion = $_POST['id_configuracion'];

        $response = $this->model->agregar_automatizador($nombre_automatizador, $id_configuracion);
        echo json_encode($response);
    }

    public function obtenerPedidosPorTelefono()
    {

        $id_plataforma = $_SESSION['id_plataforma'] ?? $_POST['id_plataforma'] ?? 1160;

        $telefono = $_POST['telefono'];


        $response = $this->model->obtenerPedidoPorTelefono($telefono, $id_plataforma);

        echo json_encode($response);
    }
    public function transito($id)
    {
        $response = $this->model->transito($id);
        echo json_encode($response);
    }

    public function devolucion($id)
    {
        $response = $this->model->devolucion($id);
        echo json_encode($response);
    }

    public function entregar($id)
    {
        $response = $this->model->entregar($id);
        echo json_encode($response);
    }
    public function obtenerDetallesPedido()
    {
        $id_pedido = $_POST['id_factura'];
        $response = $this->model->obtenerDetallesPedido($id_pedido);
        echo json_encode($response);
    }

    public function cancelarPedido()
    {
        $id_pedido = $_POST['id_pedido'];
        $response = $this->model->cancelarPedido($id_pedido);
        echo json_encode($response);
    }

    public function actualizarDetallePedido()
    {
        $id_detalle = $_POST['id_detalle'];
        $id_pedido = $_POST['id_pedido'];
        $cantidad = $_POST['cantidad'];
        $precio = $_POST['precio'];
        $total = $_POST['total'];
        $response = $this->model->actualizarDetallePedido($id_detalle, $id_pedido, $cantidad, $precio, $total);
        echo json_encode($response);
    }

    public function agregarProductoAPedido()
    {
        $id_pedido = $_POST['id_pedido'];
        $id_producto = $_POST['id_producto'];
        $cantidad = $_POST['cantidad'];
        $precio = $_POST['precio'];
        $sku = $_POST['sku'];
        $id_inventario = $_POST['id_inventario'];

        $response = $this->model->agregarProductoAPedido($id_pedido, $id_producto, $cantidad, $precio, $sku, $id_inventario);
        echo json_encode($response);
    }

    public function eliminarProductoDePedido()
    {
        $id_detalle = $_POST['id_detalle'];
        $response = $this->model->eliminarProductoDePedido($id_detalle);
        echo json_encode($response);
    }

    public function actualizarContiene()
    {
        $id_pedido = $_POST['id_pedido'];
        $contiene = $_POST['contiene'];
        $response = $this->model->actualizarContienePedido($id_pedido, $contiene);
        echo json_encode($response);
    }

    public function redirect()
    {
        /* session_start();
        $token = $_SESSION['token'];
        $direccion = $_POST['direccion'];

        header("Location: " . $direccion . "?token=" . $token); */

        return $this->views->render($this, "redirects");
    }

    public function buscarProductosTiendas()
    {
        $id_plataforma = $_POST['id_plataforma'];

        $response = $this->model->buscarProductosTiendas($id_plataforma);
        echo json_encode($response);
    }

    public function obtener_templates()
    {
        $palabra_busqueda = $_POST['palabra_busqueda'];
        $response = $this->model->obtener_templates($_SESSION['id_plataforma'], $palabra_busqueda);
        echo json_encode($response);
    }

    public function validar_telefonos_clientes()
    {
        $telefono = $_POST['telefono'];
        $response = $this->model->validar_telefonos_clientes($_SESSION['id_plataforma'], $telefono);
        echo json_encode($response);
    }

    public function agregar_numero_chat()
    {
        $telefono = $_POST['telefono'];
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];

        // Verificar si existe la sesión de id_plataforma
        $id_plataforma = isset($_SESSION['id_plataforma']) ? $_SESSION['id_plataforma'] : $_POST['id_plataforma'];

        $response = $this->model->agregar_numero_chat($telefono, $nombre, $apellido, $id_plataforma);
        echo json_encode($response);
    }
}
