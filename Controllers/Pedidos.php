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
        if (!$this->isAuth()) {
            header("Location: " . SERVERURL . "login");
        }
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



    ///Funciones

    public function datosPlataformas()
    {
        $tienda = $_POST["tienda"];
        $data = $this->model->datosPlataformas($tienda);
        echo json_encode($data);
    }

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

    public function obtener_guiasAdministrador2()
    {
        $fecha_inicio = $_POST['fecha_inicio'] ?? "";
        $fecha_fin = $_POST['fecha_fin'] ?? "";
        $transportadora = $_POST['transportadora'] ?? "";
        $estado = $_POST['estado'] ?? "";
        $drogshipin = $_POST['drogshipin'] ?? "";
        $impreso = $_POST['impreso'] ?? "";
        $start = $_POST['start'] ?? 0; // Índice de la primera fila a mostrar
        $length = $_POST['length'] ?? 25; // Número de filas por página

        $data = $this->model->cargarGuiasAdministrador2($fecha_inicio, $fecha_fin, $transportadora, $estado, $impreso, $drogshipin, $start, $length);

        // Contar el total de registros sin limit
        $totalData = $this->model->contarTotalGuiasAdministrador($fecha_inicio, $fecha_fin, $transportadora, $estado, $impreso, $drogshipin);

        $json_data = [
            "draw" => intval($_POST['draw']), // Valor incrementado automáticamente por DataTables
            "recordsTotal" => intval($totalData), // Total de registros sin filtrar
            "recordsFiltered" => intval($totalData), // Total de registros después de aplicar filtros
            "data" => $data // Los datos de la página actual
        ];

        echo json_encode($json_data);
    }

    public function obtener_guiasAnuladas_admin()
    {
        $fecha_inicio = $_POST['fecha_inicio'] ?? "";
        $fecha_fin = $_POST['fecha_fin'] ?? "";
        $transportadora = $_POST['transportadora'] ?? "";
        $data = $this->model->cargarGuiasAnuladas_admin($fecha_inicio, $fecha_fin, $transportadora);
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
        $sku = $_POST['sku'];
        $id_factura = $_POST['id_factura'];

        $response = $this->model->agregarDetalle($id_producto, $cantidad, $precio, $_SESSION['id_plataforma'], $sku, $id_factura);
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

    public function numero_cliente()
    {
        $id_cliente = $_POST['id_cliente'];
        $response = $this->model->numero_cliente($id_cliente, $_SESSION['id_plataforma']);
        echo json_encode($response);
    }

    public function numeros_clientes()
    {
        $response = $this->model->numeros_clientes($_SESSION['id_plataforma']);
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

    public function agregar_mensaje_enviado()
    {
        $texto_mensaje = $_POST['texto_mensaje'];
        $tipo_mensaje = $_POST['tipo_mensaje'];

        $response = $this->model->agregar_mensaje_enviado($texto_mensaje, $tipo_mensaje);
        echo json_encode($response);
    }
}
