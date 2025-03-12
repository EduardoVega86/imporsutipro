<?php


session_start();

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;


// Para estilos de borde, relleno, alineación
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class Pedidos extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index($filtro = ""): void
    {
        if (!$this->isAuth()) {
            header("Location: " . SERVERURL . "login");
        }
        $data = $this->model->cargarPedidosIngresados($filtro);
        $this->views->render($this, "index", $data);
    }
    public function ingreso($filtro = ""): void
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
    public function guias2($filtro = "")
    {
        if (!$this->isAuth()) {
            header("Location:" . SERVERURL . "login");
        }
        $this->views->render($this, "guias2");
    }
    public function guias_administrador($filtro = "")
    {
        if (!$this->isAuth()) {
            header("Location: " . SERVERURL . "login");
        }
        $this->views->render($this, "guias_administrador");
    }
    public function guias_administrador3($filtro = "")
    {
        if (!$this->isAuth()) {
            header("Location: " . SERVERURL . "login");
        }
        $this->views->render($this, "guias_administrador_3");
    }
    public function historial_2($filtro = "")
    {
        if (!$this->isAuth()) {
            header("Location: " . SERVERURL . "login");
        }
        $this->views->render($this, "historial_2");
    }

    public function historial_abandonados($filtro = "")
    {
        if (!$this->isAuth()) {
            header("Location: " . SERVERURL . "login");
        }
        $this->views->render($this, "historial_abandonados");
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

    public function novedades_2()
    {
        if (!$this->isAuth()) {
            header("Location: " . SERVERURL . "login");
        }
        $this->views->render($this, "novedades_2");
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

    public function inicio_automatizador($filtro = "")
    {
        if (!$this->isAuth()) {
            header("Location: " . SERVERURL . "login");
        }
        $this->views->render($this, "inicio_automatizador");
    }

    public function pedidos_sin_producto()
    {
        if (!$this->isAuth()) {
            header("Location: " . SERVERURL . "login");
        }
        $this->views->render($this, "pedidos_sin_producto");
    }

    public function vista_anadir_sin_producto()
    {
        if (!$this->isAuth()) {
            header("Location: " . SERVERURL . "login");
        }
        $this->views->render($this, "vista_anadir_sin_producto");
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
        $nombre_responsable = $_POST['nombre_responsable'] ?? "";
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
        $response = $this->model->nuevo_pedido($fecha_factura, $id_usuario, $monto_factura, $estado_factura, $nombre_cliente, $telefono_cliente, $c_principal, $ciudad_cot, $c_secundaria, $referencia, $observacion, $guia_enviada, $transporte, $identificacion, $celular, $dueño_id, $dropshipping, $id_plataforma, $dueño_id, $importado, $plataforma_importa, $cod, $estado_guia_sistema, $impreso, $facturada, $factura_numero, $numero_guia, $anulada, $identificacionO, $celularO, $nombreO, $ciudadO, $provinciaO, $direccionO, $referenciaO, $numeroCasaO, $valor_segura, $no_piezas, $tipo_servicio, $peso, $contiene, $costo_flete, $costo_producto, $comentario, $id_transporte, $provincia, $id_bodega, $nombre_responsable);

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

    public function enviar_mensaje_automatizador()
    {
        $nueva_factura = $_POST['nueva_factura'] ?? "";
        $ciudad_cot = $_POST['ciudad_cot'] ?? "";
        $celular = $_POST['celular'] ?? "";
        $nombre_cliente = $_POST['nombre'] ?? "";
        $c_principal = $_POST['c_principal'] ?? "";
        $c_secundaria = $_POST['c_secundaria'] ?? "";
        $contiene = $_POST['contiene'] ?? "";
        $monto_factura = $_POST['monto_factura'] ?? "";

        $data = $this->model->enviar_mensaje_automatizador($_SESSION['id_plataforma'], $nueva_factura, $ciudad_cot, $celular, $nombre_cliente, $c_principal, $c_secundaria, $contiene, $monto_factura);
        echo json_encode($data);
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
    public function obtenerDestinatarioWebhook($id_producto)
    {
        $datos = $this->model->obtenerDestinatarioWebhook($id_producto);
        return  $datos;
    }
    public function nuevo_pedido_webhook($id_usuario, $monto_factura, $nombre_cliente, $telefono_cliente, $c_principal, $c_secundaria, $ciudad_cot, $provincia, $referencia, $observacion, $id_producto_venta, $id_plataforma, $productos, $webhook = null)
    {

        $fecha_factura = date("Y-m-d H:i:s");
        $estado_factura = 1;
        $guia_enviada = 0;
        $transporte = 0;
        $identificacion = $_POST['identificacion'] ?? "";
        $celular = $_POST['celular'] ?? $telefono_cliente;
        ///

        $dropshipping = 1;
        $dueño_id = $this->obtenerDestinatarioShopify($id_producto_venta);

        if ($dueño_id == $id_plataforma) {
            $dropshipping = 0;
        } else {
            $dropshipping = 1;
        }

        $importado = "1";
        $plataforma_importa = "Webhook" ?? 0;
        $cod = 1;
        $estado_guia_sistema = 1;
        $impreso = 0;
        $facturada = 0;
        $factura_numero = 0;
        $numero_guia = 0;
        $anulada = 0;

        ///origen
        $datos_origen = $this->obtenerDestinatarioWebhook($id_producto_venta);

        $identificacionO = "0";
        $celularO = $datos_origen['contacto'] ?? $telefono_cliente;
        $nombreO = $datos_origen['nombre'];
        $ciudadO = $datos_origen['localidad'] ?? 0;
        $provinciaO = $datos_origen['provincia'] ?? 0;
        $direccionO = $datos_origen['direccion'] ?? "vacio";
        $referenciaO = $datos_origen['referencia'];
        $numeroCasaO = $datos_origen['num_casa'] ?? 0;

        $valor_segura =  0;
        $no_piezas =  1;
        $tipo_servicio = "201202002002013";
        $peso = "2";
        $contiene = "";
        foreach ($productos as $producto) {
            $contiene .= $producto['nombre'] . " x" . $producto['cantidad'] . " ";
        }

        $costo_flete = 0;
        $costo_producto =  $this->model->costo_producto($id_producto_venta);
        $comentario = "Pedido Generado via Webhook";
        $id_transporte = 0;

        $id_bodega = $this->model->obtenerIdBodega($id_producto_venta); //CODIGO DE LA BODEGA  

        $response = $this->model->nuevo_pedido_shopify($fecha_factura, $id_usuario, $monto_factura, $estado_factura, $nombre_cliente, $telefono_cliente, $c_principal, $ciudad_cot, $c_secundaria, $referencia, $observacion, $guia_enviada, $transporte, $identificacion, $celular, $dueño_id, $dropshipping, $id_plataforma, $dueño_id, $importado, $plataforma_importa, $cod, $estado_guia_sistema, $impreso, $facturada, $factura_numero, $numero_guia, $anulada, $identificacionO, $celularO, $nombreO, $ciudadO, $provinciaO, $direccionO, $referenciaO, $numeroCasaO, $valor_segura, $no_piezas, $tipo_servicio, $peso, $contiene, $costo_flete, $costo_producto, $comentario, $id_transporte, $provincia, $productos, $id_bodega);
        if ($webhook) {
            return $response;
        }
        echo json_encode($response);
    }
    public function cargarPedidos($id_plataforma = null)
    {
        $data = $this->model->pedidos($_SESSION["id_plataforma"] ?? $id_plataforma);
        echo json_encode($data);
    }

    public function cargarPedidos_imporsuit()
    {
        $fecha_inicio = $_POST['fecha_inicio'] ?? "";
        $fecha_fin = $_POST['fecha_fin'] ?? "";
        $estado_pedido = $_POST['estado_pedido'] ?? "";
        $data = $this->model->cargarPedidos_imporsuit($_SESSION["id_plataforma"], $fecha_inicio, $fecha_fin, $estado_pedido);
        echo json_encode($data);
    }

    public function cargarPedidosAnulados()
    {
        $fecha_inicio = $_POST['fecha_inicio'] ?? "";
        $fecha_fin    = $_POST['fecha_fin'] ?? "";

        // Forzamos que la guía enviada siempre sea 0
        $guia_enviada = 0;
        // Y que se traigan los pedidos anulados (anulada = 1)
        $anulada = 1;

        $data = $this->model->cargarPedidosAnulados($_SESSION["id_plataforma"], $fecha_inicio, $fecha_fin, $guia_enviada, $anulada);
        echo json_encode($data);
    }



    public function cargar_pedidos_sin_producto()
    {
        $fecha_inicio = $_POST['fecha_inicio'] ?? "";
        $fecha_fin = $_POST['fecha_fin'] ?? "";
        $estado_pedido = $_POST['estado_pedido'] ?? "";
        $data = $this->model->cargar_pedidos_sin_producto($_SESSION["id_plataforma"], $fecha_inicio, $fecha_fin, $estado_pedido);
        echo json_encode($data);
    }

    public function cargar_cards_pedidos($id_plataforma = null)
    {
        $fecha_inicio = $_POST['fecha_inicio'] ?? "";
        $fecha_fin = $_POST['fecha_fin'] ?? "";
        $estado_pedido = $_POST['estado_pedido'] ?? "";

        $data = $this->model->cargar_cards_pedidos($_SESSION["id_plataforma"], $fecha_inicio, $fecha_fin, $estado_pedido);
        echo json_encode($data);
    }
    public function cargar_cards_pedidos_mes($id_plataforma = null)
    {
        $fecha_inicio = $_POST['fecha_inicio'] != "" ? $_POST['fecha_inicio'] : date('Y-m-01');
        $fecha_fin = $_POST['fecha_fin']  != "" ? $_POST['fecha_fin'] : date('Y-m-t', strtotime($fecha_inicio));
        $estado_pedido = $_POST['estado_pedido'] ?? "";
        $data = $this->model->cargar_cards_pedidos($_SESSION["id_plataforma"], $fecha_inicio, $fecha_fin, $estado_pedido);
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

        // Inicializamos los totales para mostrar cards en guías
        $totals = [
            "total"        => count($data),
            "generada"     => 0,
            "en_transito"  => 0,
            "zona_entrega" => 0,
            "entregada"    => 0,
            "novedad"      => 0,
            "devolucion"   => 0,
        ];

        // Recorremos cada guía y calculamos los totales
        foreach ($data as $guia) {
            $estado_guia = intval($guia['estado_guia_sistema']);
            $transporte  = intval($guia['id_transporte']);

            // "Generada"
            if (($transporte == 2 && in_array($estado_guia, [100, 102, 103])) ||
                ($transporte == 1 && in_array($estado_guia, [1, 2])) ||
                ($transporte == 3 && in_array($estado_guia, [1, 2, 3])) ||
                ($transporte == 4 && $estado_guia === 2)
            ) {
                $totals['generada']++;
            }

            // "En tránsito"
            if (($transporte == 2 && $estado_guia >= 300 && $estado_guia <= 317 && $estado_guia != 307) ||
                ($transporte == 1 && in_array($estado_guia, [5, 11, 12])) ||
                ($transporte == 3 && in_array($estado_guia, [4])) ||
                ($transporte == 4 && $estado_guia === 3)
            ) {
                $totals['en_transito']++;
            }

            // Zona de entrega 
            if (($transporte == 2 && $estado_guia == 307) ||
                ($transporte == 1 && in_array($estado_guia, [6])) ||
                ($transporte == 3 && in_array($estado_guia, [5]))
            ) {
                $totals['zona_entrega']++;
            }

            // "Entregada"
            if (($transporte == 2 && $estado_guia >= 400 && $estado_guia <= 403) ||
                ($transporte == 1 && $estado_guia === 7) ||
                ($transporte == 3 && $estado_guia === 7) ||
                ($transporte == 4 && $estado_guia === 7)
            ) {
                $totals['entregada']++;
            }

            // "Novedad"
            if (($transporte == 2 && $estado_guia >= 318 && $estado_guia <= 351) ||
                ($transporte == 1 && $estado_guia === 14) ||
                ($transporte == 3 && $estado_guia === 6) ||
                ($transporte == 4 && $estado_guia === 14)
            ) {
                $totals['novedad']++;
            }

            // "Devolución"
            if (($transporte == 2 && $estado_guia >= 500 && $estado_guia <= 502) ||
                ($transporte == 1 && $estado_guia === 9) ||
                ($transporte == 4 && $estado_guia === 9) ||
                ($transporte == 3 && in_array($estado_guia, [8, 9, 13]))
            ) {
                $totals['devolucion']++;
            }
            $guia['pagado'] = ($guia['pagado'] == 1) ? 'Pagado' : 'Pendiente';
        }

        $result = [
            "data"   => $data,
            "totals" => $totals
        ];

        echo json_encode($result);
    }

    public function obtener_guias_estado_guia_sistema()
    {
        $fecha_inicio = $_POST['fecha_inicio'] ?? "";
        $fecha_fin = $_POST['fecha_fin'] ?? "";
        $transportadora = $_POST['transportadora'] ?? "";
        $estado = $_POST['estado'] ?? "";
        $drogshipin = $_POST['drogshipin'] ?? "";
        $impreso = $_POST['impreso'] ?? "";
        $despachos = $_POST['despachos'] ?? "";

        $data = $this->model->cargarGuiasEstadoGuiaSistema($_SESSION['id_plataforma'], $fecha_inicio, $fecha_fin, $transportadora, $estado, $impreso, $drogshipin, $despachos);

        // Inicializamos los totales para mostrar cards en guías
        $totals = [
            "total"        => count($data),
            "generada"     => 0,
            "en_transito"  => 0,
            "zona_entrega" => 0,
            "entregada"    => 0,
            "novedad"      => 0,
            "devolucion"   => 0,
        ];

        // Recorremos cada guía y calculamos los totales
        foreach ($data as $guia) {
            $estado_guia = intval($guia['estado_guia_sistema']);
            $transporte  = intval($guia['id_transporte']);

            // "Generada"
            if (($transporte == 2 && in_array($estado_guia, [100, 102, 103])) ||
                ($transporte == 1 && in_array($estado_guia, [1, 2])) ||
                ($transporte == 3 && in_array($estado_guia, [1, 2, 3])) ||
                ($transporte == 4 && $estado_guia === 2)
            ) {
                $totals['generada']++;
            }

            // "En tránsito"
            if (($transporte == 2 && $estado_guia >= 300 && $estado_guia <= 317 && $estado_guia != 307) ||
                ($transporte == 1 && in_array($estado_guia, [5, 11, 12])) ||
                ($transporte == 3 && in_array($estado_guia, [4])) ||
                ($transporte == 4 && $estado_guia === 3)
            ) {
                $totals['en_transito']++;
            }

            // Zona de entrega 
            if (($transporte == 2 && $estado_guia == 307) ||
                ($transporte == 1 && in_array($estado_guia, [6])) ||
                ($transporte == 3 && in_array($estado_guia, [5]))
            ) {
                $totals['zona_entrega']++;
            }

            // "Entregada"
            if (($transporte == 2 && $estado_guia >= 400 && $estado_guia <= 403) ||
                ($transporte == 1 && $estado_guia === 7) ||
                ($transporte == 3 && $estado_guia === 7) ||
                ($transporte == 4 && $estado_guia === 7)
            ) {
                $totals['entregada']++;
            }

            // "Novedad"
            if (($transporte == 2 && $estado_guia >= 318 && $estado_guia <= 351) ||
                ($transporte == 1 && $estado_guia === 14) ||
                ($transporte == 3 && $estado_guia === 6) ||
                ($transporte == 4 && $estado_guia === 14)
            ) {
                $totals['novedad']++;
            }

            // "Devolución"
            if (($transporte == 2 && $estado_guia >= 500 && $estado_guia <= 502) ||
                ($transporte == 1 && $estado_guia === 9) ||
                ($transporte == 4 && $estado_guia === 9) ||
                ($transporte == 3 && in_array($estado_guia, [8, 9, 13]))
            ) {
                $totals['devolucion']++;
            }
        }

        $result = [
            "data"   => $data,
            "totals" => $totals
        ];

        echo json_encode($result);
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

        // Inicializamos los totales para mostrar cards en guias
        $totals = [
            "total"       => count($data),
            "generada"    => 0,
            "en_transito" => 0,
            "zona_entrega" => 0,
            "entregada"   => 0,
            "novedad"     => 0,
            "devolucion"  => 0,
        ];

        // Recorremos cada guía y calculamos los totales
        foreach ($data as $guia) {
            $guia['pagado'] = ($guia['pagado'] == 1) ? 'Pagado' : 'Pendiente';
            $estado_guia = intval($guia['estado_guia_sistema']);
            $transporte  = intval($guia['id_transporte']);

            // "Generada"
            if (($transporte == 2 && in_array($estado_guia, [100, 102, 103])) ||
                ($transporte == 1 && in_array($estado_guia, [1, 2])) ||
                ($transporte == 3 && in_array($estado_guia, [1, 2, 3])) ||
                ($transporte == 4 && $estado_guia === 2)
            ) {
                $totals['generada']++;
            }

            // "En tránsito"
            if (($transporte == 2 && $estado_guia >= 300 && $estado_guia <= 317 && $estado_guia != 307) ||
                ($transporte == 1 && in_array($estado_guia, [5, 11, 12])) ||
                ($transporte == 3 && in_array($estado_guia, [4])) ||
                ($transporte == 4 && $estado_guia === 3)
            ) {
                $totals['en_transito']++;
            }

            // Zona de entrega 
            if (($transporte == 2 && $estado_guia == 307) ||
                ($transporte == 1 && in_array($estado_guia, [6])) ||
                ($transporte == 3 && in_array($estado_guia, [5]))
            ) {
                $totals['zona_entrega']++;
            }

            // "Entregada"
            if (($transporte == 2 && $estado_guia >= 400 && $estado_guia <= 403) ||
                ($transporte == 1 && $estado_guia === 7) ||
                ($transporte == 3 && $estado_guia === 7) ||
                ($transporte == 4 && $estado_guia === 7)
            ) {
                $totals['entregada']++;
            }

            // "Novedad"
            if (($transporte == 2 && $estado_guia >= 318 && $estado_guia <= 351) ||
                ($transporte == 1 && $estado_guia === 14) ||
                ($transporte == 3 && $estado_guia === 6) ||
                ($transporte == 4 && $estado_guia === 14)
            ) {
                $totals['novedad']++;
            }

            // "Devolución"
            if (($transporte == 2 && $estado_guia >= 500 && $estado_guia <= 502) ||
                ($transporte == 1 && $estado_guia === 9) ||
                ($transporte == 4 && $estado_guia === 9) ||
                ($transporte == 3 && in_array($estado_guia, [8, 9, 13]))
            ) {
                $totals['devolucion']++;
            }
        }

        $result = [
            "data"   => $data,
            "totals" => $totals
        ];
        echo json_encode($result);
    }

    /**
     * Exporta las guías en un archivo Excel o CSV con un reporte detallado.
     *
     * Este método recibe parámetros a través de una solicitud POST para filtrar guías 
     * según la plataforma, fecha, transportadora, estado, entre otros. Luego, genera 
     * un archivo Excel con los datos obtenidos, incluyendo un resumen de estados 
     * y estadísticas gráficas.
     *
     * @return void
     *
     * @throws \PhpOffice\PhpSpreadsheet\Exception En caso de errores con la manipulación de la hoja de cálculo.
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception En caso de errores al generar el archivo.
     *
     * @api
     */
    public function exportarGuias()
    {
        // 1) Recogemos los parámetros POST
        $fecha_inicio   = $_POST['fecha_inicio']   ?? "";
        $fecha_fin      = $_POST['fecha_fin']      ?? "";
        $transportadora = $_POST['transportadora'] ?? "";
        $estado         = $_POST['estado']         ?? "";
        $drogshipin     = $_POST['drogshipin']     ?? "";
        $impreso        = $_POST['impreso']        ?? "";
        $despachos      = $_POST['despachos']      ?? "";
        $formato        = $_POST['formato']        ?? "excel";

        // Para pedidos:
        $estado_pedido  = $_POST['estado_pedido']  ?? "";

        $id_plataforma = $_SESSION['id_plataforma'];

        // ================================================================
        // 2) Consulta principal de GUÍAS
        // ================================================================
        $data = $this->model->cargarGuias(
            $id_plataforma,
            $fecha_inicio,
            $fecha_fin,
            $transportadora,
            $estado,
            $impreso,
            $drogshipin,
            $despachos
        );

        // 3) Calculamos los “counts” (estados) basados en la misma lógica
        $counts = [
            'generada'      => 0,
            'en_transito'   => 0,
            'zona_entrega'  => 0,
            'entregada'     => 0,
            'novedad'       => 0,
            'devolucion'    => 0,
        ];
        foreach ($data as $idx => $guia) {
            $estado_guia = intval($guia['estado_guia_sistema']);
            $transporte  = intval($guia['id_transporte']);

            // Generada
            if (
                ($transporte == 2 && in_array($estado_guia, [100, 102, 103])) ||
                ($transporte == 1 && in_array($estado_guia, [1, 2])) ||
                ($transporte == 3 && in_array($estado_guia, [1, 2, 3])) ||
                ($transporte == 4 && $estado_guia == 2)
            ) {
                $counts['generada']++;
            }
            // En tránsito
            if (
                ($transporte == 2 && $estado_guia >= 300 && $estado_guia <= 317 && $estado_guia != 307) ||
                ($transporte == 1 && in_array($estado_guia, [5, 11, 12])) ||
                ($transporte == 3 && in_array($estado_guia, [4])) ||
                ($transporte == 4 && $estado_guia == 3)
            ) {
                $counts['en_transito']++;
            }
            // Zona de entrega
            if (
                ($transporte == 2 && $estado_guia == 307) ||
                ($transporte == 1 && in_array($estado_guia, [6])) ||
                ($transporte == 3 && in_array($estado_guia, [5]))
            ) {
                $counts['zona_entrega']++;
            }
            // Entregada
            if (
                ($transporte == 2 && $estado_guia >= 400 && $estado_guia <= 403) ||
                ($transporte == 1 && $estado_guia == 7) ||
                ($transporte == 3 && $estado_guia == 7) ||
                ($transporte == 4 && $estado_guia == 7)
            ) {
                $counts['entregada']++;
            }
            // Novedad
            if (
                ($transporte == 2 && $estado_guia >= 318 && $estado_guia <= 351) ||
                ($transporte == 1 && $estado_guia == 14) ||
                ($transporte == 3 && $estado_guia == 6) ||
                ($transporte == 4 && $estado_guia == 14)
            ) {
                $counts['novedad']++;
            }
            // Devolución
            if (
                ($transporte == 2 && $estado_guia >= 500 && $estado_guia <= 502) ||
                ($transporte == 1 && $estado_guia == 9) ||
                ($transporte == 4 && $estado_guia == 9) ||
                ($transporte == 3 && in_array($estado_guia, [8, 9, 13]))
            ) {
                $counts['devolucion']++;
            }

            // Ajuste local de 'pagado' sólo para la impresión
            $data[$idx]['pagado'] = ($guia['pagado'] == 1) ? 'Pagado' : 'Pendiente';
        }
        $total = count($data);

        // ================================================================
        // 4) Construimos el Spreadsheet y la PRIMERA HOJA (GUÍAS)
        // ================================================================
        $spreadsheet = new Spreadsheet();
        // Obtenemos la primera hoja (por defecto)
        $sheetGuias = $spreadsheet->getActiveSheet();
        $sheetGuias->setTitle('GUÍAS');

        // --- Encabezado Título en A1:Q1 ---
        $sheetGuias->mergeCells('A1:Q1');
        $sheetGuias->setCellValue('A1', 'REPORTE DE GUÍAS');
        $sheetGuias->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 18,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '0D1566']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ]
        ]);

        // --- Encabezados en la fila 3 ---
        $sheetGuias->setCellValue('A3', '# Guia');
        $sheetGuias->setCellValue('B3', 'Fecha Factura');
        $sheetGuias->setCellValue('C3', 'Cliente');
        $sheetGuias->setCellValue('D3', 'Teléfono');
        $sheetGuias->setCellValue('E3', 'Dirección');
        $sheetGuias->setCellValue('F3', 'Destino');
        $sheetGuias->setCellValue('G3', 'Transportadora');
        $sheetGuias->setCellValue('H3', 'Estado');
        $sheetGuias->setCellValue('I3', 'Despachado');
        $sheetGuias->setCellValue('J3', 'Impreso');
        $sheetGuias->setCellValue('K3', 'Venta Total');
        $sheetGuias->setCellValue('L3', 'Costo Producto');
        $sheetGuias->setCellValue('M3', 'Costo Flete');
        $sheetGuias->setCellValue('N3', 'Fulfillment');
        $sheetGuias->setCellValue('O3', 'Monto a Recibir');
        $sheetGuias->setCellValue('P3', 'Recaudo');
        $sheetGuias->setCellValue('Q3', 'Por acreditar');

        $sheetGuias->getStyle('A3:Q3')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 14,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '0D1566']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ]
        ]);

        foreach (range('A', 'Q') as $col) {
            $sheetGuias->getColumnDimension($col)->setAutoSize(true);
        }

        // 5) Rellenar la data de GUÍAS
        $fila = 4;
        foreach ($data as $guia) {
            $sheetGuias->setCellValue("A{$fila}", $guia['numero_guia']);
            $sheetGuias->setCellValue("B{$fila}", $guia['fecha_factura']);
            $sheetGuias->setCellValue("C{$fila}", $guia['nombre']);
            $sheetGuias->setCellValue("D{$fila}", $guia['telefono']);

            // Truncar dirección a 20 chars
            $direccionCompleta = $guia['c_principal'] . ' ' . $guia['c_secundaria'];
            if (!empty($guia['referencia'])) {
                $direccionCompleta .= ' (Ref: ' . $guia['referencia'] . ')';
            }
            if (strlen($direccionCompleta) > 20) {
                $direccionCompleta = substr($direccionCompleta, 0, 20) . '...';
            }
            $sheetGuias->setCellValue("E{$fila}", $direccionCompleta);

            // Destino
            $destino = $guia['provinciaa'] . ' - ' . $guia['ciudad'];
            $sheetGuias->setCellValue("F{$fila}", $destino);

            $sheetGuias->setCellValue("G{$fila}", $guia['transporte']);

            // Estado traducido (función personalizada)
            $estadoGuia = $this->traducirEstado($guia['id_transporte'], $guia['estado_guia_sistema']);
            $sheetGuias->setCellValue("H{$fila}", $estadoGuia);

            // Despachado
            $despachado = "";
            if ($guia['estado_factura'] == 1) $despachado = "No despachado";
            if ($guia['estado_factura'] == 2) $despachado = "Despachado";
            if ($guia['estado_factura'] == 3) $despachado = "Devuelto";
            $sheetGuias->setCellValue("I{$fila}", $despachado);

            // Impreso
            $sheetGuias->setCellValue("J{$fila}", ($guia['impreso'] == 1 ? 'SI' : 'NO'));

            // Venta total
            $sheetGuias->setCellValue("K{$fila}", $guia['monto_factura']);
            // Costo Producto
            $sheetGuias->setCellValue("L{$fila}", $guia['costo_producto']);
            // Costo Flete
            $sheetGuias->setCellValue("M{$fila}", $guia['costo_flete']);
            // Fulfillment => vacío
            $sheetGuias->setCellValue("N{$fila}", "");
            // Monto a recibir
            $montoRecibir = $guia['monto_factura'] - $guia['costo_producto'] - $guia['costo_flete'];
            $sheetGuias->setCellValue("O{$fila}", $montoRecibir);

            // Recaudo => SI/NO
            $sheetGuias->setCellValue("P{$fila}", ($guia['cod'] == 1 ? 'SI' : 'NO'));

            // Por acreditar => ACREDITADO/PENDIENTE
            $sheetGuias->setCellValue("Q{$fila}", ($guia['pagado'] == 'Pagado' ? 'ACREDITADO' : 'PENDIENTE'));

            $fila++;
        }
        $ultimaFila = $fila - 1;

        // Estilos de alineación
        if ($ultimaFila >= 3) {
            // centrado general
            $sheetGuias->getStyle("A3:Q{$ultimaFila}")
                ->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_LEFT);
        }

        // Bordes
        if ($ultimaFila >= 3) {
            $sheetGuias->getStyle("A3:Q{$ultimaFila}")->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => '000000']
                    ]
                ]
            ]);
        }

        // 6) Mini tabla + diagrama de barras en la HOJA de GUÍAS
        // Cabecera minitabla
        $miniTableStart = 3;
        $sheetGuias->setCellValue("S{$miniTableStart}", "Estado");
        $sheetGuias->setCellValue("T{$miniTableStart}", "Porcentaje");
        $sheetGuias->getStyle("S{$miniTableStart}:T{$miniTableStart}")->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '0D1566'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ]
        ]);
        $sheetGuias->getColumnDimension('S')->setAutoSize(true);
        $sheetGuias->getColumnDimension('T')->setAutoSize(true);

        $labelsEstados = ["Generada", "En tránsito", "Zona entrega", "Entregada", "Novedad", "Devolución"];
        $keysEstados   = ["generada", "en_transito", "zona_entrega", "entregada", "novedad", "devolucion"];

        // Porcentajes
        $porcentajesRaw = [];
        foreach ($keysEstados as $k) {
            $porcentajesRaw[$k] = ($total > 0) ? (($counts[$k] / $total) * 100) : 0;
        }
        $sumRaw = array_sum($porcentajesRaw);
        $dif    = 100 - $sumRaw;
        if ($sumRaw > 0) {
            $maxKey = array_search(max($porcentajesRaw), $porcentajesRaw);
            $porcentajesRaw[$maxKey] += $dif;
        }

        // Pegamos en la minitabla
        $rowAux = $miniTableStart + 1;
        foreach ($keysEstados as $i => $k) {
            $sheetGuias->setCellValue("S{$rowAux}", $labelsEstados[$i]);
            $sheetGuias->setCellValue("T{$rowAux}", round($porcentajesRaw[$k], 2));
            $rowAux++;
        }
        $lastAux = $rowAux - 1;

        // Bordes minitabla
        $sheetGuias->getStyle("S{$miniTableStart}:T{$lastAux}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000']
                ]
            ]
        ]);

        // Diagrama de barras
        $numEstados = 6;
        $startData  = $miniTableStart + 1; // 4
        $endData    = $startData + $numEstados - 1; // 9

        $labels = [
            new DataSeriesValues('String', $sheetGuias->getTitle() . '!T' . $miniTableStart, null, 1),
        ];
        $categories = [
            new DataSeriesValues('String', $sheetGuias->getTitle() . "!S{$startData}:S{$endData}", null, $numEstados),
        ];
        $values = [
            new DataSeriesValues('Number', $sheetGuias->getTitle() . "!T{$startData}:T{$endData}", null, $numEstados),
        ];

        $series = new DataSeries(
            DataSeries::TYPE_BARCHART,
            DataSeries::GROUPING_CLUSTERED,
            range(0, count($values) - 1),
            $labels,
            $categories,
            $values
        );
        $series->setPlotDirection(DataSeries::DIRECTION_COL);

        $plotArea = new PlotArea(null, [$series]);
        $legend   = new Legend(Legend::POSITION_RIGHT, null, false);
        $title    = new Title('% de Estados');
        $yAxisLab = new Title('Porcentaje (%)');

        $chart = new Chart('chart_estados', $title, $legend, $plotArea, true, 0, null, $yAxisLab);

        // Ubicamos el chart debajo de la data
        $posChartTop = $ultimaFila + 2;
        if ($posChartTop < 10) {
            $posChartTop = 10;
        }
        $chart->setTopLeftPosition("D{$posChartTop}");
        $chart->setBottomRightPosition("J" . ($posChartTop + 15));

        $sheetGuias->addChart($chart);

        // ================================================================
        // 7) Consultamos PEDIDOS y creamos HOJAS PARA CADA REPORTE
        // ================================================================

        // A) Pedidos pendientes
        $pedidos = $this->model->cargarPedidos_imporsuit(
            $id_plataforma,
            $fecha_inicio,
            $fecha_fin,
            $estado_pedido
        );
        // B) Pedidos no vinculados
        $pedidosNoVinculados = $this->model->cargar_pedidos_sin_producto(
            $id_plataforma,
            $fecha_inicio,
            $fecha_fin,
            $estado_pedido
        );
        // C) Pedidos anulados
        $pedidosAnulados = $this->model->cargarPedidosAnulados(
            $id_plataforma,
            $fecha_inicio,
            $fecha_fin,
            0,
            1
        );

        // =======================================================================
        // HOJA 2: PEDIDOS PENDIENTES (Y AQUÍ TAMBIÉN MOSTRAREMOS ANULADOS EN ROJO)
        // =======================================================================

        // Creamos la hoja de PENDIENTES
        $sheetPendientes = $spreadsheet->createSheet();
        $sheetPendientes->setTitle('PENDIENTES');

        // Encabezado grande
        $fila = 1;
        $sheetPendientes->mergeCells("A{$fila}:K{$fila}");
        $sheetPendientes->setCellValue("A{$fila}", 'REPORTE PEDIDOS PENDIENTES + ANULADOS');
        $sheetPendientes->getStyle("A{$fila}")->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 16,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '5A178B']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ]
        ]);

        $fila += 2;  // dejamos un espacio

        // Encabezados de columna (usamos 11 columnas, A..K) para incluir “Estado (Pendiente/Anulado)”
        $sheetPendientes->setCellValue("A{$fila}", '# Orden');
        $sheetPendientes->setCellValue("B{$fila}", 'Fecha');
        $sheetPendientes->setCellValue("C{$fila}", 'Cliente');
        $sheetPendientes->setCellValue("D{$fila}", 'Teléfono');
        $sheetPendientes->setCellValue("E{$fila}", 'Dirección');
        $sheetPendientes->setCellValue("F{$fila}", 'Destino');
        $sheetPendientes->setCellValue("G{$fila}", 'Canal venta');
        $sheetPendientes->setCellValue("H{$fila}", 'Estado Pedido');
        $sheetPendientes->setCellValue("I{$fila}", 'Monto Factura');
        $sheetPendientes->setCellValue("J{$fila}", 'Costo Producto');
        $sheetPendientes->setCellValue("K{$fila}", 'Estado Final');
        // (Esta "K" la usaremos para diferenciar “PENDIENTE” o “ANULADO” si quieres)

        $sheetPendientes->getStyle("A{$fila}:K{$fila}")->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 14,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '5A178B']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ]
        ]);

        // Ajustar ancho automático
        foreach (range('A', 'K') as $col) {
            $sheetPendientes->getColumnDimension($col)->setAutoSize(true);
        }

        $filaCabecera = $fila;
        $fila++;

        // -----------------------------------------------------------------------------
        // 1) Agregamos la DATA DE PEDIDOS PENDIENTES en el color normal (sin rojo)
        // -----------------------------------------------------------------------------
        foreach ($pedidos as $p) {
            $sheetPendientes->setCellValue("A{$fila}", $p['numero_factura']);
            $sheetPendientes->setCellValue("B{$fila}", $p['fecha_factura']);
            $sheetPendientes->setCellValue("C{$fila}", $p['nombre']);
            $sheetPendientes->setCellValue("D{$fila}", $p['telefono']);

            // Dirección truncada
            $dire = $p['c_principal'] . ' ' . $p['c_secundaria'];
            if (strlen($dire) > 20) {
                $dire = substr($dire, 0, 20) . '...';
            }
            $sheetPendientes->setCellValue("E{$fila}", $dire);

            $destino = $p['provinciaa'] . ' - ' . $p['ciudad'];
            $sheetPendientes->setCellValue("F{$fila}", $destino);

            $sheetPendientes->setCellValue("G{$fila}", $p['plataforma_importa']);

            // Estado pedido (tu función traducirEstadoPedido)
            $estadoPedido = $this->traducirEstadoPedido($p['estado_pedido']);
            $sheetPendientes->setCellValue("H{$fila}", $estadoPedido);

            $sheetPendientes->setCellValue("I{$fila}", $p['monto_factura']);
            $sheetPendientes->setCellValue("J{$fila}", $p['costo_producto']);

            // Columna K - “PENDIENTE” 
            $sheetPendientes->setCellValue("K{$fila}", 'PENDIENTE');

            $fila++;
        }

        // -----------------------------------------------------------------------------
        // 2) Agregamos la DATA DE PEDIDOS ANULADOS, pero pintamos la fila en rojo
        // -----------------------------------------------------------------------------
        foreach ($pedidosAnulados as $pa) {
            $sheetPendientes->setCellValue("A{$fila}", $pa['numero_factura']);
            $sheetPendientes->setCellValue("B{$fila}", $pa['fecha_factura']);
            $sheetPendientes->setCellValue("C{$fila}", $pa['nombre']);
            $sheetPendientes->setCellValue("D{$fila}", $pa['telefono']);

            // Dirección truncada
            $direA = $pa['c_principal'] . ' ' . $pa['c_secundaria'];
            if (strlen($direA) > 20) {
                $direA = substr($direA, 0, 20) . '...';
            }
            $sheetPendientes->setCellValue("E{$fila}", $direA);

            $destinoA = $pa['provinciaa'] . ' - ' . $pa['ciudad'];
            $sheetPendientes->setCellValue("F{$fila}", $destinoA);

            $sheetPendientes->setCellValue("G{$fila}", $pa['plataforma_importa']);

            // Puedes forzar “ANULADO” en H o en K, o usar algo como $this->traducirEstadoPedido($pa['estado_pedido'])
            $sheetPendientes->setCellValue("H{$fila}", 'ANULADO');

            $sheetPendientes->setCellValue("I{$fila}", $pa['monto_factura']);
            $sheetPendientes->setCellValue("J{$fila}", $pa['costo_producto']);

            // Columna K - “ANULADO”
            $sheetPendientes->setCellValue("K{$fila}", 'ANULADO');

            // Ahora, pintamos toda la fila de rojo claro:
            $sheetPendientes->getStyle("A{$fila}:K{$fila}")
                ->applyFromArray([
                    'fill' => [
                        'fillType'   => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'FFC0CB'] // Ejemplo rosa claro 
                        // puedes poner 'FF9999' para un rojo más fuerte, etc.
                    ],
                ]);

            $fila++;
        }

        // -----------------------------------------------------------------------------
        // 3) Agregamos bordes a TODO el rango (incluyendo pendientes y anulados)
        // -----------------------------------------------------------------------------
        $ultimaFila = $fila - 1;
        if ($ultimaFila >= $filaCabecera) {
            $sheetPendientes->getStyle("A{$filaCabecera}:K{$ultimaFila}")
                ->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000']
                        ]
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_LEFT,
                    ]
                ]);
        }

        // -------------------------------------------------------
        // HOJA 3: PEDIDOS NO VINCULADOS
        // -------------------------------------------------------
        $sheetNoVinc = $spreadsheet->createSheet();
        $sheetNoVinc->setTitle('NO VINCULADOS');

        $fila = 1;
        $sheetNoVinc->mergeCells("A{$fila}:J{$fila}");
        $sheetNoVinc->setCellValue("A{$fila}", 'REPORTE PEDIDOS (NO VINCULADOS)');
        $sheetNoVinc->getStyle("A{$fila}")->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 16,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '0D3B05']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ]
        ]);

        foreach (range('A', 'J') as $col) {
            $sheetNoVinc->getColumnDimension($col)->setAutoSize(true);
        }

        $fila += 2;
        // Encabezados
        $sheetNoVinc->setCellValue("A{$fila}", '# Orden');
        $sheetNoVinc->setCellValue("B{$fila}", 'Fecha');
        $sheetNoVinc->setCellValue("C{$fila}", 'Cliente');
        $sheetNoVinc->setCellValue("D{$fila}", 'Teléfono');
        $sheetNoVinc->setCellValue("E{$fila}", 'Dirección');
        $sheetNoVinc->setCellValue("F{$fila}", 'Destino');
        $sheetNoVinc->setCellValue("G{$fila}", 'Canal de venta');
        $sheetNoVinc->setCellValue("H{$fila}", 'Estado Pedido');
        $sheetNoVinc->setCellValue("I{$fila}", 'Monto Factura');
        $sheetNoVinc->setCellValue("J{$fila}", 'Costo Producto');

        $sheetNoVinc->getStyle("A{$fila}:J{$fila}")->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 14,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '0D3B05']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ]
        ]);

        $filaPedidosInicio = $fila;
        $fila++;

        // Llenado
        foreach ($pedidosNoVinculados as $pe) {
            $sheetNoVinc->setCellValue("A{$fila}", $pe['numero_factura']);
            $sheetNoVinc->setCellValue("B{$fila}", $pe['fecha_factura']);
            $sheetNoVinc->setCellValue("C{$fila}", $pe['nombre']);
            $sheetNoVinc->setCellValue("D{$fila}", $pe['telefono']);

            $direNV = $pe['c_principal'] . ' ' . $pe['c_secundaria'];
            if (strlen($direNV) > 20) {
                $direNV = substr($direNV, 0, 20) . '...';
            }
            $sheetNoVinc->setCellValue("E{$fila}", $direNV);

            $destNV = $pe['provinciaa'] . ' - ' . $pe['ciudad'];
            $sheetNoVinc->setCellValue("F{$fila}", $destNV);

            $sheetNoVinc->setCellValue("G{$fila}", $pe['plataforma_importa']);

            $estNV = $this->traducirEstadoPedido($pe['estado_pedido']);
            $sheetNoVinc->setCellValue("H{$fila}", $estNV);

            $sheetNoVinc->setCellValue("I{$fila}", $pe['monto_factura']);
            $sheetNoVinc->setCellValue("J{$fila}", $pe['costo_producto']);

            $fila++;
        }
        $ultimaFilaPedidos = $fila - 1;

        if ($ultimaFilaPedidos >= $filaPedidosInicio) {
            $sheetNoVinc->getStyle("A{$filaPedidosInicio}:J{$ultimaFilaPedidos}")
                ->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000']
                        ]
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_LEFT,
                    ]
                ]);
        }

        // ================================================================
        // 8) Exportar según formato
        // ================================================================
        // Preparamos un filename base
        // Justo antes de exportar:
        $fecha_inicio = $_POST['fecha_inicio'] ?? "";
        $fecha_fin    = $_POST['fecha_fin']    ?? "";

        $fechaInicioCorta = !empty($fecha_inicio) ? date('Y-m-d', strtotime($fecha_inicio)) : "";
        $fechaFinCorta    = !empty($fecha_fin)    ? date('Y-m-d', strtotime($fecha_fin))    : "";

        if ($fechaInicioCorta && $fechaFinCorta) {
            $filename = "guias_{$fechaInicioCorta}_al_{$fechaFinCorta}";
        } elseif ($fechaInicioCorta) {
            $filename = "guias_{$fechaInicioCorta}";
        } else {
            $filename = "guias_" . date('Y-m-d');
        }

        // Formato
        if ($formato === 'csv') {
            $writer = new Csv($spreadsheet);
            $filename .= '.csv';

            header('Content-Type: text/csv');
            header("Content-Disposition: attachment;filename=\"{$filename}\"");
            header('Cache-Control: max-age=0');

            $writer->save('php://output');
            exit;
        } else {
            $writer = new Xlsx($spreadsheet);
            $writer->setIncludeCharts(true);
            $filename .= '.xlsx';

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header("Content-Disposition: attachment; filename=\"{$filename}\"");
            header('Cache-Control: max-age=0');

            $writer->save('php://output');
            exit;
        }
    }



    public function exportarGuiasAdministrador()
    {
        $fecha_inicio   = $_POST['fecha_inicio']   ?? "";
        $fecha_fin      = $_POST['fecha_fin']      ?? "";
        $transportadora = $_POST['transportadora'] ?? "";
        $estado         = $_POST['estado']         ?? "";
        $drogshipin     = $_POST['drogshipin']     ?? "";
        $impreso        = $_POST['impreso']        ?? "";
        $despachos      = $_POST['despachos']      ?? "";
        $formato        = $_POST['formato']        ?? "excel";

        // Obtener los datos
        $data = $this->model->cargarGuiasAdministrador(
            $fecha_inicio,
            $fecha_fin,
            $transportadora,
            $estado,
            $impreso,
            $drogshipin,
            $despachos
        );

        // ========================================================
        // A) Calcular conteos por estado
        // ========================================================
        $counts = [
            'generada'      => 0,
            'en_transito'   => 0,
            'zona_entrega'  => 0,
            'entregada'     => 0,
            'novedad'       => 0,
            'devolucion'    => 0,
        ];

        foreach ($data as $guia) {
            $estado_guia = intval($guia['estado_guia_sistema']);
            $transporte  = intval($guia['id_transporte']);

            // Generada
            if (
                ($transporte == 2 && in_array($estado_guia, [100, 102, 103])) ||
                ($transporte == 1 && in_array($estado_guia, [1, 2])) ||
                ($transporte == 3 && in_array($estado_guia, [1, 2, 3])) ||
                ($transporte == 4 && $estado_guia === 2)
            ) {
                $counts['generada']++;
            }
            // En tránsito
            if (
                ($transporte == 2 && $estado_guia >= 300 && $estado_guia <= 317 && $estado_guia != 307) ||
                ($transporte == 1 && in_array($estado_guia, [5, 11, 12])) ||
                ($transporte == 3 && in_array($estado_guia, [4])) ||
                ($transporte == 4 && $estado_guia === 3)
            ) {
                $counts['en_transito']++;
            }
            // Zona de entrega
            if (
                ($transporte == 2 && $estado_guia == 307) ||
                ($transporte == 1 && in_array($estado_guia, [6])) ||
                ($transporte == 3 && in_array($estado_guia, [5]))
            ) {
                $counts['zona_entrega']++;
            }
            // Entregada
            if (
                ($transporte == 2 && $estado_guia >= 400 && $estado_guia <= 403) ||
                ($transporte == 1 && $estado_guia === 7) ||
                ($transporte == 3 && $estado_guia === 7) ||
                ($transporte == 4 && $estado_guia === 7)
            ) {
                $counts['entregada']++;
            }
            // Novedad
            if (
                ($transporte == 2 && $estado_guia >= 318 && $estado_guia <= 351) ||
                ($transporte == 1 && $estado_guia === 14) ||
                ($transporte == 3 && $estado_guia === 6) ||
                ($transporte == 4 && $estado_guia === 14)
            ) {
                $counts['novedad']++;
            }
            // Devolución
            if (
                ($transporte == 2 && $estado_guia >= 500 && $estado_guia <= 502) ||
                ($transporte == 1 && $estado_guia === 9) ||
                ($transporte == 4 && $estado_guia === 9) ||
                ($transporte == 3 && in_array($estado_guia, [8, 9, 13]))
            ) {
                $counts['devolucion']++;
            }
            $guia['pagado'] = ($guia['pagado'] == 1) ? 'Pagado' : 'Pendiente';
        }
        $total = count($data);

        // ========================================================
        // B) Crear Spreadsheet
        // ========================================================
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // 1) Título en A1:P1
        $sheet->mergeCells('A1:R1');
        $sheet->setCellValue('A1', 'REPORTE DE GUÍAS ADMINISTRADOR');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 18,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '0D1566'] // color de fondo
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ]
        ]);

        // 2) Encabezados en la fila 3
        $sheet->setCellValue('A3', '# Guia');
        $sheet->setCellValue('B3', 'Fecha Factura');
        $sheet->setCellValue('C3', 'Cliente');
        $sheet->setCellValue('D3', 'Teléfono');
        $sheet->setCellValue('E3', 'Dirección');
        $sheet->setCellValue('F3', 'Destino');
        $sheet->setCellValue('G3', 'Transportadora');
        $sheet->setCellValue('H3', 'Estado');
        $sheet->setCellValue('I3', 'Despachado');
        $sheet->setCellValue('J3', 'Impreso');
        $sheet->setCellValue('K3', 'Venta Total');
        $sheet->setCellValue('L3', 'Costo Producto');
        $sheet->setCellValue('M3', 'Costo Flete');
        $sheet->setCellValue('N3', 'Utilidad Flete'); // <-- Añadimos esta columna
        $sheet->setCellValue('O3', 'Fulfillment');
        $sheet->setCellValue('P3', 'Monto a Recibir');
        $sheet->setCellValue('Q3', 'Recaudo');
        $sheet->setCellValue('R3', 'Por acreditar');

        // Aplicar estilos a los encabezados
        $sheet->getStyle('A3:R3')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 14,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '0D1566'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ]
        ]);

        foreach (range('A', 'R') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // 3) Llenar la tabla principal
        $fila = 4;
        foreach ($data as $guia) {
            $sheet->setCellValue("A{$fila}", $guia['numero_guia']);
            $sheet->setCellValue("B{$fila}", $guia['fecha_factura']);
            $sheet->setCellValue("C{$fila}", $guia['nombre']);
            $sheet->setCellValue("D{$fila}", $guia['telefono']);

            // Truncar dirección a 20 chars
            $direccionCompleta = $guia['c_principal'] . ' ' . $guia['c_secundaria'];
            if (!empty($guia['referencia'])) {
                $direccionCompleta .= ' (Ref: ' . $guia['referencia'] . ')';
            }
            if (strlen($direccionCompleta) > 20) {
                $direccion = substr($direccionCompleta, 0, 20) . '...';
            } else {
                $direccion = $direccionCompleta;
            }
            $sheet->setCellValue("E{$fila}", $direccion);

            $destino = $guia['provinciaa'] . ' - ' . $guia['ciudad'];
            $sheet->setCellValue("F{$fila}", $destino);

            $sheet->setCellValue("G{$fila}", $guia['transporte']);

            // Traducir estado
            $estadoGuia = $this->traducirEstado($guia['id_transporte'], $guia['estado_guia_sistema']);
            $sheet->setCellValue("H{$fila}", $estadoGuia);

            // Despachado
            $despachado = "";
            if ($guia['estado_factura'] == 1) $despachado = "No despachado";
            if ($guia['estado_factura'] == 2) $despachado = "Despachado";
            if ($guia['estado_factura'] == 3) $despachado = "Devuelto";
            $sheet->setCellValue("I{$fila}", $despachado);

            // Impreso
            $sheet->setCellValue("J{$fila}", ($guia['impreso'] == 1 ? 'SI' : 'NO'));

            // Venta total
            $sheet->setCellValue("K{$fila}", $guia['monto_factura']);

            // Costo Producto
            $sheet->setCellValue("L{$fila}", $guia['costo_producto']);

            // Costo Flete
            $sheet->setCellValue("M{$fila}", $guia['costo_flete']);

            // Utilidad 
            $sheet->setCellValue("N{$fila}", $guia['utilidad']);

            // Fulfillment => vacío
            $sheet->setCellValue("O{$fila}", "");

            // Monto a recibir
            $montoRecibir = $guia['monto_factura'] - $guia['costo_producto'] - $guia['costo_flete'];
            $sheet->setCellValue("P{$fila}", $montoRecibir);

            // Recaudo => SI/NO
            $sheet->setCellValue("Q{$fila}", ($guia['cod'] == 1 ? 'SI' : 'NO'));

            // Por acreditar => ACREDITADO/PENDIENTE
            $sheet->setCellValue("R{$fila}", ($guia['pagado'] == 1 ? 'ACREDITADO' : 'PENDIENTE'));

            $fila++;
        }
        $ultimaFila = $fila - 1;

        // Alinear contenido de la tabla principal
        if ($ultimaFila >= 3) {
            $sheet->getStyle("A3:R{$ultimaFila}")
                ->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_LEFT);
        }

        // Bordes a la tabla principal
        if ($ultimaFila >= 3) {
            $sheet->getStyle("A3:R{$ultimaFila}")->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => '000000']
                    ]
                ]
            ]);
        }

        // =========================================================
        // 4) Mini tabla + Forzar suma 100% + Diagrama de barras
        // =========================================================
        $miniTableStart = 3; // fila 3
        $sheet->setCellValue("T{$miniTableStart}", "Estado");
        $sheet->setCellValue("U{$miniTableStart}", "Porcentaje");
        // Estilo encabezado minitabla
        $sheet->getStyle("T{$miniTableStart}:U{$miniTableStart}")->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '0D1566'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ]
        ]);
        $sheet->getColumnDimension('T')->setAutoSize(true);
        $sheet->getColumnDimension('U')->setAutoSize(true);

        // Preparar array para estados
        $labelsEstados = ["Generada", "En tránsito", "Zona entrega", "Entregada", "Novedad", "Devolución"];
        $keysEstados   = ["generada", "en_transito", "zona_entrega", "entregada", "novedad", "devolucion"];

        // 4.1) Primero calculamos sin redondear:
        $porcentajesRaw = [];
        foreach ($keysEstados as $k) {
            if ($total > 0) {
                $porcentajesRaw[$k] = ($counts[$k] / $total) * 100; // sin round
            } else {
                $porcentajesRaw[$k] = 0;
            }
        }
        // 4.2) Ajustar para forzar sum 100 => se lo damos a la categoría con valor mayor
        $sumRaw = array_sum($porcentajesRaw);
        $dif    = 100 - $sumRaw;
        if ($sumRaw > 0) {
            $maxKey = array_search(max($porcentajesRaw), $porcentajesRaw);
            $porcentajesRaw[$maxKey] += $dif;
        }

        // 4.3) Volvemos a iterar para "pegar" en la hoja (redondeando c/2 decimales)
        $rowAux = $miniTableStart + 1; // fila 4
        foreach ($keysEstados as $i => $k) {
            $sheet->setCellValue("T{$rowAux}", $labelsEstados[$i]);
            $porcentajeFinal = round($porcentajesRaw[$k], 2);
            $sheet->setCellValue("U{$rowAux}", $porcentajeFinal);
            $rowAux++;
        }
        $lastAux = $rowAux - 1;

        // Bordes mini tabla
        $sheet->getStyle("T{$miniTableStart}:U{$lastAux}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000']
                ]
            ]
        ]);

        // 4.4) Chart de barras
        $numEstados = 6;
        $startData  = $miniTableStart + 1; // 4
        $endData    = $startData + $numEstados - 1; // 9
        $labels = [
            new DataSeriesValues('String', $sheet->getTitle() . '!U' . $miniTableStart, null, 1), // S3 => "Porcentaje"
        ];
        $categories = [
            new DataSeriesValues('String', $sheet->getTitle() . "!T{$startData}:T{$endData}", null, $numEstados),
        ];
        $values = [
            new DataSeriesValues('Number', $sheet->getTitle() . "!U{$startData}:U{$endData}", null, $numEstados),
        ];

        $series = new DataSeries(
            DataSeries::TYPE_BARCHART,
            DataSeries::GROUPING_CLUSTERED,
            range(0, count($values) - 1),
            $labels,
            $categories,
            $values
        );
        $series->setPlotDirection(DataSeries::DIRECTION_COL);

        $plotArea = new PlotArea(null, [$series]);
        $legend   = new Legend(Legend::POSITION_RIGHT, null, false);
        $title    = new Title('% de Estados de Guías');
        $yAxisLab = new Title('Porcentaje (%)');

        $chart = new Chart('chart_estados', $title, $legend, $plotArea, true, 0, null, $yAxisLab);

        // Lo ubicamos debajo de la tabla principal (ejemplo: col D..J)
        $posChartTop = $ultimaFila + 2;
        if ($posChartTop < 10) {
            // Si hay pocas filas, que empiece en la fila 10 para no chocar
            $posChartTop = 10;
        }
        $chart->setTopLeftPosition("D{$posChartTop}");
        $chart->setBottomRightPosition("J" . ($posChartTop + 15));

        // Insertar chart
        $sheet->addChart($chart);

        // =========================================================
        // 5) Generar archivo
        // =========================================================
        if ($formato === 'csv') {
            $writer = new Csv($spreadsheet);
            $filename = "guias_{$fecha_inicio}_al_{$fecha_fin}.csv";

            header('Content-Type: text/csv');
            header("Content-Disposition: attachment;filename=\"{$filename}\"");
            header('Cache-Control: max-age=0');

            $writer->save('php://output');
            exit;
        } else {
            $writer = new Xlsx($spreadsheet);
            $writer->setIncludeCharts(true);
            $filename = "guias_{$fecha_inicio}_al_{$fecha_fin}.xlsx";
            // Ejemplo: "guias_2023-02-01_al_2023-02-10.xlsx"

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header("Content-Disposition: attachment; filename=\"{$filename}\"");
            header('Cache-Control: max-age=0');

            $writer->save('php://output');
            exit;
        }
    }

    /**
     * Retorna la descripción de estado del pedido en texto, .
     */
    private function traducirEstadoPedido($estado)
    {
        $estados = [
            1 => "Pendiente",
            2 => "Gestionado",
            3 => "No desea",
            4 => "1ra llamada",
            5 => "2da llamada",
            6 => "Observación"
        ];

        return $estados[$estado] ?? "Estado desconocido";
    }



    /**
     * Retorna la descripción de estado de la guía en texto, 
     * basado en la transportadora (idTransporte) y el estado_guia_sistema.
     */
    private function traducirEstado($idTransporte, $estado)
    {
        // Convertimos a int por si viene como string
        $estado = (int) $estado;

        switch ($idTransporte) {
            case 1: // LAAR
                // Basado en tu "validar_estadoLaar"
                switch ($estado) {
                    case 1:
                        return "Generado";
                    case 2:
                        return "Por recolectar";
                    case 3:
                        return "Recolectado";
                    case 4:
                        return "En bodega";
                    case 5:
                        return "En tránsito";
                    case 6:
                        return "Zona de entrega";
                    case 7:
                        return "Entregado";
                    case 8:
                        return "Anulado";
                    case 9:
                        return "Devuelto";
                    case 11:
                        return "En tránsito";
                    case 12:
                        return "En tránsito";
                    case 14:
                        return "Novedad";
                    default:
                        return "Estado LAAR desconocido";
                }

            case 2: // SERVIENTREGA
                // Basado en tu "validar_estadoServi"
                //   100,102,103 => Generado
                //   200,201,202 => Recolectado
                //   300..317 => Procesamiento
                //   400..403 => Entregado
                //   318..351 => Novedad
                //   500..502 => Devuelto
                //   101 => Anulado
                if ($estado == 101) {
                    return "Anulado";
                } elseif (in_array($estado, [100, 102, 103])) {
                    return "Generado";
                } elseif (in_array($estado, [200, 201, 202])) {
                    return "Recolectado";
                } elseif ($estado >= 300 && $estado <= 317) {
                    return "Procesamiento";
                } elseif ($estado >= 400 && $estado <= 403) {
                    return "Entregado";
                } elseif ($estado >= 318 && $estado <= 351) {
                    return "Novedad";
                } elseif ($estado >= 500 && $estado <= 502) {
                    return "Devuelto";
                } else {
                    return "Estado Servientrega desconocido";
                }

            case 3: // GINTRACOM
                // Basado en tu "validar_estadoGintracom"
                switch ($estado) {
                    case 1:
                        return "Generada";
                    case 2:
                        return "Picking";
                    case 3:
                        return "Packing";
                    case 4:
                        return "En tránsito";
                    case 5:
                        return "En reparto";
                    case 6:
                        return "Novedad";
                    case 7:
                        return "Entregado";
                    case 8:
                        return "Devuelto";
                    case 9:
                        return "Devuelto";
                    case 10:
                        return "Cancelada por transportadora";
                    case 11:
                        return "Indemnización";
                    case 12:
                        return "Anulada";
                    case 13:
                        return "Devuelto";
                    default:
                        return "Estado GINTRACOM desconocido";
                }

            case 4: // SPEED / MerkaLogistic
                // Basado en tu "validar_estadoSpeed"
                switch ($estado) {
                    case 2:
                        return "Generado";
                    case 3:
                        return "En tránsito";
                    case 7:
                        return "Entregado";
                    case 9:
                        return "Devuelto";
                    case 14:
                        return "Novedad";
                    default:
                        return "Estado SPEED desconocido";
                }

            default:
                return "Transportadora no identificada";
        }
    }



    /// sebastian
    public function obtener_guiasAdministrador3()
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
        $data = $this->model->cargarGuiasAdministrador3($fecha_inicio, $fecha_fin, $transportadora, $estado, $impreso, $drogshipin, $despachos);

        // Inicializamos los totales para mostrar cards en guias
        $totals = [
            "total"       => count($data),
            "generada"    => 0,
            "en_transito" => 0,
            "zona_entrega" => 0,
            "entregada"   => 0,
            "novedad"     => 0,
            "devolucion"  => 0,
        ];

        // Recorremos cada guía y calculamos los totales
        foreach ($data as $guia) {
            $estado_guia = intval($guia['estado_guia_sistema']);
            $transporte  = intval($guia['id_transporte']);

            // "Generada"
            if (($transporte == 2 && in_array($estado_guia, [100, 102, 103])) ||
                ($transporte == 1 && in_array($estado_guia, [1, 2])) ||
                ($transporte == 3 && in_array($estado_guia, [1, 2, 3])) ||
                ($transporte == 4 && $estado_guia === 2)
            ) {
                $totals['generada']++;
            }

            // "En tránsito"
            if (($transporte == 2 && $estado_guia >= 300 && $estado_guia <= 317 && $estado_guia != 307) ||
                ($transporte == 1 && in_array($estado_guia, [5, 11, 12])) ||
                ($transporte == 3 && in_array($estado_guia, [4])) ||
                ($transporte == 4 && $estado_guia === 3)
            ) {
                $totals['en_transito']++;
            }

            // Zona de entrega 
            if (($transporte == 2 && $estado_guia == 307) ||
                ($transporte == 1 && in_array($estado_guia, [6])) ||
                ($transporte == 3 && in_array($estado_guia, [5]))
            ) {
                $totals['zona_entrega']++;
            }

            // "Entregada"
            if (($transporte == 2 && $estado_guia >= 400 && $estado_guia <= 403) ||
                ($transporte == 1 && $estado_guia === 7) ||
                ($transporte == 3 && $estado_guia === 7) ||
                ($transporte == 4 && $estado_guia === 7)
            ) {
                $totals['entregada']++;
            }

            // "Novedad"
            if (($transporte == 2 && $estado_guia >= 318 && $estado_guia <= 351) ||
                ($transporte == 1 && $estado_guia === 14) ||
                ($transporte == 3 && $estado_guia === 6) ||
                ($transporte == 4 && $estado_guia === 14)
            ) {
                $totals['novedad']++;
            }

            // "Devolución"
            if (($transporte == 2 && $estado_guia >= 500 && $estado_guia <= 502) ||
                ($transporte == 1 && $estado_guia === 9) ||
                ($transporte == 4 && $estado_guia === 9) ||
                ($transporte == 3 && in_array($estado_guia, [8, 9, 13]))
            ) {
                $totals['devolucion']++;
            }
        }

        $result = [
            "data"   => $data,
            "totals" => $totals
        ];
        echo json_encode($result);
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

    public function buscar_id_recibe()
    {
        header('Content-Type: application/json'); // Asegura que la respuesta sea JSON
        $telefono = $_POST['telefono'];
        $plataforma = $_POST['plataforma'];

        $response = $this->model->buscar_id_recibe($telefono, $plataforma);
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

    public function agregar_detalle_observacion()
    {
        $id_factura = $_POST['id_factura_ingresar_observacion'];
        $observacion_pedido = $_POST['observacion_pedido'];

        $response = $this->model->agregar_detalle_observacion($id_factura, $observacion_pedido);
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

    public function devolver_novedad($guia_novedad)
    {
        $response = $this->model->devolver_novedad($guia_novedad);
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

    public function agregar_mensaje()
    {
        // Recibir y validar datos del cliente
        $id_plataforma = $_POST['id_plataforma'] ?? null;
        $id_cliente = $_POST['id_cliente'] ?? null;
        $mid_mensaje = $_POST['mid_mensaje'] ?? null;
        $tipo_mensaje = $_POST['tipo_mensaje'] ?? null;
        $texto_mensaje = $_POST['texto_mensaje'] ?? null;
        $ruta_archivo = $_POST['ruta_archivo'] ?? null;
        $rol_mensaje = $_POST['rol_mensaje'] ?? 1; // Valor predeterminado
        $celular_recibe = $_POST['celular_recibe'] ?? null;
        $phone_whatsapp_from = $_POST['phone_whatsapp_from'] ?? null;

        // Validar datos requeridos
        if (!$id_plataforma || !$id_cliente || !$mid_mensaje || !$tipo_mensaje || !$texto_mensaje || !$celular_recibe || !$phone_whatsapp_from) {
            echo json_encode([
                'status' => 400,
                'title' => 'Datos incompletos',
                'message' => 'Faltan datos obligatorios para insertar el mensaje.',
            ]);
            return;
        }

        // Llamar al modelo para insertar el mensaje
        $response = $this->model->insertar_mensaje(
            $id_plataforma,
            $id_cliente,
            $mid_mensaje,
            $tipo_mensaje,
            $texto_mensaje,
            $ruta_archivo,
            $rol_mensaje,
            $celular_recibe,
            $phone_whatsapp_from
        );

        // Responder con JSON
        echo json_encode($response);
    }

    public function obtener_factura_sin_producto($id_factura)
    {
        $this->catchAsync(function () use ($id_factura) {
            $response = $this->model->obtener_factura_sin_producto($id_factura);
            if (count($response) == 0) {
                throw new Exception("No se encontraron facturas");
            }
            echo json_encode([
                "status" => 200,
                "title" => "Éxito",
                "message" => "Facturas obtenidas correctamente",
                "data" => $response,
                "count" => count($response)
            ]);
        })();
    }

    public function actualizar_productos_psp($id_factura)
    {
        $this->catchAsync(function () use ($id_factura) {
            $productos = $_POST['productos'] ?? [];
            if (count($productos) == 0) {
                throw new Exception("No envió productos");
            }
            $response = $this->model->actualizar_productos_psp($id_factura, $productos);
            // devolver una response en application/json
            echo json_encode($response);
        })();
    }
}
