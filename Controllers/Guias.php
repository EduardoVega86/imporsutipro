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

    public function buscarStock($numero_factura)
    {
        $response = $this->model->buscarStock($numero_factura);
        if ($response == false) {
            $response = array("status" => 501);
        } else {
            $response = array("status" => 200);
        }
        return  $response;
    }
    public function generarLaar()
    {
        $this->isAuth();
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

        if ($this->buscarStock($numero_factura)["status"] == 501) {
            echo json_encode(array("status" => 501, "message" => "No contamos con stock de el/los productos para generar la guía"));
            return;
        }

        $vendedor = $this->model->obtenerVendedor($_SESSION["id_plataforma"] ?? $_POST["id_plataforma"])['nombre_tienda'];
        //uppercase
        $vendedor = strtoupper($vendedor);
        $telf_vendedor = $this->model->obtenerVendedor($_SESSION["id_plataforma"] ?? $_POST["id_plataforma"])['whatsapp'];

        $datos = $this->model->generarLaar($nombreOrigen, $ciudadOrigen, $direccionOrigen, $telefonoOrigen, $referenciaOrigen, $celularOrigen, $nombreDestino, $ciudadDestino, $direccionDestino, $telefonoDestino, $celularDestino, $referenciaDestino, $postal, $identificacion, $contiene, $peso, $valor_seguro, $valor_declarado, $tamanio, $cod, $costoflete, $costo_producto, $tipo_cobro, $comentario, $fecha, $extras, $vendedor, $telf_vendedor);
        $datos = json_decode($datos, true);
        $repitio = false;



        if (!empty($datos["guia"])) {
            $existe = false;
            while ($existe == false) {
                if (!$this->model->existeGuia($datos["guia"])) {
                    $existe = true;
                    break;
                }
                $datos = $this->model->generarLaar($nombreOrigen, $ciudadOrigen, $direccionOrigen, $telefonoOrigen, $referenciaOrigen, $celularOrigen, $nombreDestino, $ciudadDestino, $direccionDestino, $telefonoDestino, $celularDestino, $referenciaDestino, $postal, $identificacion, $contiene, $peso, $valor_seguro, $valor_declarado, $tamanio, $cod, $costoflete, $costo_producto, $tipo_cobro, $comentario, $fecha, $extras, $vendedor, $telf_vendedor);
                $repitio = true;
            }

            if ($repitio) {
                $datos = json_decode($datos, true);
                if (empty($datos["guia"])) {
                    $datos["status"] = "500";
                    echo json_encode($datos);
                    return;
                }
            }
            if (strpos($datos["guia"], "MKP") === 0) {
                $costoflete = 5.99;
            }
            $this->model->actualizarGuia($numero_factura, $datos["guia"], $nombreDestino, $ciudad_D, $direccionDestino, $telefonoDestino, $celularDestino, $referenciaDestino, $cod, $costo_producto, $comentario, $_SESSION["id"] ?? $_POST["id"], $calle_principal, $calle_secundaria, $contiene, $provincia, $costoflete, "LAAR", 2);

            $datos["status"] = "200";
            $this->model->asignarWallet($numero_factura, $datos["guia"], $fecha, $nombreDestino, $_SESSION["id_plataforma"] ?? $_POST["id_plataforma"], 1, $costo_producto, $cod, $costoflete);
            $this->model->descargarGuia($datos["guia"]);
        } else {
            $datos["status"] = "500";
        }
        echo json_encode($datos);
    }

    /* controlador para añadir a cole creacion de guias */
    public function anadir_cola_guia_laar()
    {
        try {
            // Verificar autenticación del usuario
            $this->isAuth();

            // Conexión a Redis
            $redis = new Redis();
            $redis->connect('3.233.119.65', 6379);

            // Validar si hay stock disponible
            $numero_factura = $_POST['numero_factura'] ?? null;
            if ($this->buscarStock($numero_factura)["status"] == 501) {
                echo json_encode([
                    "status" => 501,
                    "message" => "No contamos con stock de el/los productos para generar la guía",
                    "detalle" => "Stock insuficiente para la factura número: $numero_factura"
                ]);
                return;
            }

            // Crear estructura de datos similar a `generarLaar`
            $nombreOrigen = $_POST['nombreO'];
            $ciudadOrigen = $this->model->obtenerCiudadLaar($_POST['ciudadO']);
            $direccionOrigen = $_POST['direccionO'];
            $telefonoOrigen = $_POST['celularO'];
            $referenciaOrigen = $_POST['referenciaO'];
            $celularOrigen = $telefonoOrigen;

            $nombreDestino = $_POST['nombre'];
            $ciudadDestino = $this->model->obtenerCiudadLaar($_POST['ciudad']);
            $direccionDestino = $_POST['calle_principal'] . " y " . $_POST['calle_secundaria'];
            $telefonoDestino = $_POST['telefono'];
            $celularDestino = $telefonoDestino;
            $referenciaDestino = $_POST['referencia'];

            $postal = "";
            $identificacion = "0";

            $contiene = $_POST['contiene'];
            $peso = 2;
            $valor_seguro = 0;
            $valor_declarado = 0;
            $tamanio = "";
            $cod = $_POST['recaudo'] ?? 0;
            $costoflete = $_POST['costo_flete'] ?? 0;
            $costo_producto = $_POST['total_venta'];
            $tipo_cobro = 0;
            $comentario = $_POST['observacion'];
            $fecha = date("Y-m-d");
            $extras = "";

            // Generar estructura para el sistema de colas
            $data = [
                'nombreOrigen' => $nombreOrigen,
                'ciudadOrigen' => $ciudadOrigen,
                'direccionOrigen' => $direccionOrigen,
                'telefonoOrigen' => $telefonoOrigen,
                'referenciaOrigen' => $referenciaOrigen,
                'celularOrigen' => $celularOrigen,
                'nombreDestino' => $nombreDestino,
                'ciudadDestino' => $ciudadDestino,
                'direccionDestino' => $direccionDestino,
                'telefonoDestino' => $telefonoDestino,
                'celularDestino' => $celularDestino,
                'referenciaDestino' => $referenciaDestino,
                'postal' => $postal,
                'identificacion' => $identificacion,
                'contiene' => $contiene,
                'peso' => $peso,
                'valor_seguro' => $valor_seguro,
                'valor_declarado' => $valor_declarado,
                'tamanio' => $tamanio,
                'cod' => $cod,
                'costoflete' => $costoflete,
                'costo_producto' => $costo_producto,
                'tipo_cobro' => $tipo_cobro,
                'comentario' => $comentario,
                'fecha' => $fecha,
                'extras' => $extras,
                'numero_factura' => $numero_factura,
                'calle_principal' => $_POST['calle_principal'],
                'calle_secundaria' => $_POST['calle_secundaria'],
                'provincia' => $_POST['provincia']
            ];

            // Convertir los datos a JSON para Redis
            $dataJson = json_encode($data);

            // Encolar los datos en Redis
            if (!$redis->lPush("queue:guias", $dataJson)) {
                throw new Exception("No se pudo encolar la guía en Redis.");
            }

            // Respuesta exitosa
            echo json_encode([
                'status' => 200,
                'title' => 'Éxito',
                'message' => 'La guía fue añadida a la cola correctamente.',
                'detalle' => ''
            ]);
        } catch (Exception $e) {
            // Retornar el detalle del error en la respuesta
            echo json_encode([
                'status' => 500,
                'title' => 'Error',
                'message' => 'Hubo un error al intentar añadir la guía a la cola.',
                'detalle' => $e->getMessage()
            ]);
        }
    }
    /* Fin controlador para añadir a cole creacion de guias */

    public function tokenLaar()
    {
        $response = $this->model->laarToken();
        echo $response;
    }

    public function generarServientrega()
    {
        $this->isAuth();

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
        if ($this->buscarStock($numero_factura)["status"] == 501) {
            echo json_encode(array("status" => 501, "message" => "No contamos con stock de el/los productos para generar la guía"));
            return;
        }


        $flete = $_POST['flete'];
        $seguro = $_POST['seguro'];

        $comision = $_POST['comision'];
        $otros = $_POST['otros'];
        $impuestos = $_POST['impuestos'];

        $id_transporte = $_POST['id_transporte'] ?? 2;

        $vendedor = $this->model->obtenerVendedor($_SESSION["id_plataforma"] ?? $_POST["id_plataforma"])['nombre_tienda'];
        $vendedor = strtoupper($vendedor);
        $telf_vendedor = $this->model->obtenerVendedor($_SESSION["id_plataforma"] ?? $_POST["id_plataforma"])['whatsapp'];

        if ($cod == 1) {
            $response = $this->model->generarServientrega($nombreOrigen, $ciudadOrigen, $direccionOrigen, $telefonoOrigen, $referenciaOrigen, $celularOrigen, $nombreDestino, $ciudadDestino, $direccionDestino, $telefonoDestino, $celularDestino, $referenciaDestino, $postal, $identificacion, $contiene, $peso, $valor_seguro, $valor_declarado, $tamanio, $cod, $costoflete, $costo_producto, $tipo_cobro, $comentario, $fecha, $extras, $flete, $seguro, $comision, $otros, $impuestos, $vendedor, $telf_vendedor);
        } else {
            $response = $this->model->generarServientregaSinRecaudo($nombreOrigen, $ciudadOrigen, $direccionOrigen, $telefonoOrigen, $referenciaOrigen, $celularOrigen, $nombreDestino, $ciudadDestino, $direccionDestino, $telefonoDestino, $celularDestino, $referenciaDestino, $postal, $identificacion, $contiene, $peso, $valor_seguro, $valor_declarado, $tamanio, $cod, $costoflete, $costo_producto, $tipo_cobro, $comentario, $fecha, $extras, $flete, $seguro, $comision, $otros, $impuestos, $vendedor, $telf_vendedor);
        }
        $response = json_decode($response, true);
        if (isset($response["id"])) {
            $response["status"] = 200;
            $this->model->aumentarMatriz();
            $response2 = $this->model->actualizarGuia($numero_factura, $response["id"], $nombreDestino, $ciudad, $direccionDestino, $telefonoDestino, $celularDestino, $referenciaDestino, $cod, $costo_producto, $comentario, $_SESSION["id"] ?? $_POST["id"], $_POST['calle_principal'], $_POST['calle_secundaria'], $contiene, $provincia, $costoflete, "SERVIENTREGA", 100);
            $this->model->asignarWallet($numero_factura, $response["id"], $fecha, $nombreDestino, $_SESSION["id_plataforma"] ?? $_POST["id_plataforma"], 1, $costo_producto, $cod, $costoflete);
            $this->model->descargarGuia($response["id"]);
        }
        echo json_encode($response);
    }

    public function generarGintracom()
    {
        $this->isAuth();

        $procedencia = $_POST['procedencia'] ?? 2;


        $nombreOrigen = $_POST['nombreO'];
        $ciudadOrigen = $_POST['ciudadO'];
        $provinciaOrigen = $_POST['provinciaO'];
        $ciudadOrigen = $this->model->obtenerNombre($ciudadOrigen, "codigo_ciudad_gintracom")[0]['codigo_ciudad_gintracom'];
        $provinciaOrigen = $this->model->obtenerNombre($provinciaOrigen, "codigo_provincia_gintracom")[0]['codigo_provincia_gintracom'];

        if ($procedencia == 1) {
            echo $ciudadOrigen;
            echo " | ";
            echo $provinciaOrigen;
        }

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
        if ($this->buscarStock($numero_factura)["status"] == 501) {
            echo json_encode(array("status" => 501, "message" => "No contamos con stock de el/los productos para generar la guía"));
            return;
        }
        $monto_factura = $_POST['total_venta'];

        $vendedor = $this->model->obtenerVendedor($_SESSION["id_plataforma"] ?? $_POST["id_plataforma"])['nombre_tienda'];
        $vendedor = strtoupper($vendedor);
        $telf_vendedor = $this->model->obtenerVendedor($_SESSION["id_plataforma"] ?? $_POST["id_plataforma"])['whatsapp'];

        $response = $this->model->generarGintracom($nombreOrigen, $ciudadOrigen, $provinciaOrigen, $direccionOrigen, $telefonoOrigen, $referenciaOrigen, $celularOrigen, $nombreDestino, $ciudadDestino, $provinciaDestino, $direccionDestino, $telefonoDestino, $celularDestino, $referenciaDestino, $postal, $identificacion, $contiene, $peso, $valor_seguro, $valor_declarado, $tamanio, $cod, $costoflete, $costo_producto, $tipo_cobro, $comentario, $fecha, $extras, $numero_factura, $monto_factura, $vendedor, $telf_vendedor);
        $response = json_decode($response, true);
        if (isset($response["guia"])) {
            $response["status"] = 200;
            $this->model->aumentarMatriz();
            $response2 = $this->model->actualizarGuia($numero_factura, $response["guia"], $nombreDestino, $ciudad, $direccionDestino, $telefonoDestino, $celularDestino, $referenciaDestino, $cod, $costo_producto, $comentario, $_SESSION["id"] ?? $_POST["id"], $_POST['calle_principal'], $_POST['calle_secundaria'], $contiene, $provincia, $costoflete, "GINTRACOM", 1);
            $this->model->asignarWallet($numero_factura, $response["guia"], $fecha, $nombreDestino, $_SESSION["id_plataforma"] ?? $_POST["id_plataforma"], 1, $costo_producto, $cod, $costoflete);
        }

        echo json_encode($response);
    }

    public function generarSpeed()
    {
        $this->isAuth();
        $nombreO = $_POST['nombreO'];
        $ciudadO = $_POST['ciudadO'];
        $ciudadOrigen = $this->model->obtenerNombre($ciudadO, "ciudad")[0]['ciudad'];
        $direccionO = $_POST['direccionO'];
        $telefonoO = $_POST['celularO'];
        $referenciaO = $_POST['referenciaO'];


        $nombre = $_POST['nombre'];
        $ciudad = $_POST['ciudad'];
        $provincia = $_POST['provincia'];
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

        $url_google_speed_pedido = $_POST['url_google_speed_pedido'] ?? "";



        if ($this->buscarStock($numero_factura)["status"] == 501) {
            echo json_encode(array("status" => 501, "message" => "No contamos con stock de el/los productos para generar la guía"));
            return;
        }

        $vendedor = $this->model->obtenerVendedor($_SESSION["id_plataforma"] ?? $_POST["id_plataforma"])['nombre_tienda'];
        $vendedor = strtoupper($vendedor);

        $telf_vendedor = $this->model->obtenerVendedor($_SESSION["id_plataforma"] ?? $_POST["id_plataforma"])['whatsapp'];

        $response = $this->model->generarSpeed($nombreO, $ciudadOrigen, $direccionO, $telefonoO, $referenciaO, $nombre, $ciudadDestino, $direccion, $telefono, $celular, $referencia, $contiene, $fecha, $numero_factura, $_SESSION["id_plataforma"] ?? $_POST["id_plataforma"], $observacion, $recaudo, $monto_factura, MATRIZ, $url_google_speed_pedido, $vendedor, $telf_vendedor);
        $response = json_decode($response, true);

        if (isset($response["guia"])) {
            $response["status"] = 200;
            $this->model->aumentarMatriz();
            $flete_envio = $ciudad == 599 ? 5.5 : 6.5;
            $response2 = $this->model->actualizarGuia($numero_factura, $response["guia"], $nombre, $ciudad, $direccion, $telefono, $celular, $referencia, $recaudo, $monto_factura, $observacion, $_SESSION["id"] ?? $_POST["id"], $_POST['calle_principal'], $_POST['calle_secundaria'], $contiene, $provincia, $flete_envio, "SPEED", 2, $url_google_speed_pedido);
            $this->model->asignarWallet($numero_factura, $response["guia"], $fecha, $nombre, $_SESSION["id_plataforma"] ?? $_POST["id_plataforma"], 1, $monto_factura, $recaudo, $flete_envio);
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
        if (!$this->isAuth()) {

            header("Location:  " . SERVERURL . "login");
            exit();
        }

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

    public function anularFactura($numero_factura)
    {
        $response = $this->model->anularFactura($numero_factura);
        echo json_encode($response);
    }
}
