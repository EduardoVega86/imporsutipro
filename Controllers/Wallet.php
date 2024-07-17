<?php

class Wallet extends Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->isAuth())
            header("Location:  " . SERVERURL . "login");
    }
    ///vistas

    public function index()
    {
        if ($_SESSION["cargo"] != 10) {
            header("Location: /wallet/billetera");
        }
        $this->views->render($this, "index");
    }

    public function billetera()
    {
        define("ENLACE", $_SESSION["id_plataforma"]);
        $this->views->render($this, "billetera");
    }

    public function editar($id)
    {
        $total_venta = $_POST['total_venta'];
        $precio_envio = $_POST['precio_envio'];
        $full = $_POST['full'];
        $costo = $_POST['costo'];

        $response = $this->model->editar($id, $total_venta, $precio_envio, $full, $costo);
        echo json_encode($response);
    }

    public function solicitudes()
    {
        if ($_SESSION["cargo"] != 10) {
            header("Location: /wallet/billetera");
        }
        $this->views->render($this, "solicitudes");
    }

    //funciones

    public function obtenerCabecera($id)
    {
        $response = $this->model->obtenerCabecera($id);
        echo json_encode($response);
    }

    public  function  eliminar($id)
    {
        $response = $this->model->eliminar($id);
        echo json_encode($response);
    }

    public function cambiarEstado()
    {
        $id = $_POST['id_cabecera'];
        $estado = $_POST['estado'];

        $response = $this->model->cambiarEstado($id, $estado);
        echo json_encode($response);
    }

    public function pagar()
    {
        $tienda = $_GET['id_plataforma'];
        if ($_SESSION["cargo"] != 10) {
            header("Location: /wallet/billetera");
        }
        $existe = $this->model->existeTienda($tienda);

        if (empty($existe)) {
            $this->model->crearBilletera($tienda);
        }

        $this->views->render($this, "pagar");
    }

    public function datos_bancarios()
    {
        $this->views->render($this, "datos_bancarios");
    }

    ///
    public function obtenerDatos()
    {
        $datos = $this->model->obtenerTiendas();
        echo $datos;
    }

    public function obtenerDetalles()
    {
        $tienda = $_POST['tienda'];
        $datos = $this->model->obtenerDatos($tienda);
        echo json_encode($datos);
    }

    public function obtenerFacturas()
    {
        $tienda = $_POST['tienda'];
        $filtro = $_POST['filtro'];
        $datos = $this->model->obtenerFacturas($tienda, $filtro);
        echo json_encode($datos);
    }

    public function abonarBilletera()
    {
        $id_cabecera = $_POST['id_cabecera'];
        $valor = $_POST['valor'];
        $usuario = $_SESSION['id'];

        $datos = $this->model->abonarBilletera($id_cabecera, $valor, $usuario);
        echo json_encode($datos);
    }

    public function verificarPago()
    {
        $id_solicitud = $_POST['id_solicitud'];
        $datos = $this->model->verificarPago($id_solicitud);
        echo json_encode($datos);
    }

    public function reversarAbono()
    {
        $id_cabecera = $_POST['id_cabecera'];
        $valor = $_POST['valor'];
        $usuario = $_SESSION['id'];

        $datos = $this->model->reversarAbono($id_cabecera, $valor, $usuario);
    }

    public function obtenerDatosBancarios()
    {
        $datos = $this->model->obtenerDatosBancarios($_SESSION["id_plataforma"]);
        echo json_encode($datos);
    }

    public function guardarDatosBancarios()
    {
        $banco = $_POST['banco'];
        $tipo_cuenta = $_POST['tipo_cuenta'];
        $numero_cuenta = $_POST['numero_cuenta'];
        $nombre = $_POST['nombre'];
        $cedula = $_POST['cedula'];
        $correo = $_POST['correo'];
        $telefono    = $_POST['telefono'];

        $datos = $this->model->guardarDatosBancarios($banco, $tipo_cuenta, $numero_cuenta, $nombre, $cedula, $correo, $telefono, $_SESSION["id_plataforma"]);;
        echo json_encode($datos);
    }

    public function eliminarDatoBancario()
    {
        $id_cuenta = $_POST['id_cuenta'];
        $datos = $this->model->eliminarDatoBancario($id_cuenta);
        echo json_encode($datos);
    }

    public function guardarDatosFacturacion()
    {
        $ruc = $_POST['ruc'];
        $razon_social = $_POST['razon_social'];
        $direccion = $_POST['direccion'];
        $correo = $_POST['correo'];
        $telefono = $_POST['telefono'];

        $datos = $this->model->guardarDatosFacturacion($ruc, $razon_social, $direccion, $correo, $telefono, $_SESSION["id_plataforma"]);
        echo json_encode($datos);
    }

    public function eliminarDatoFacturacion()
    {
        $id_facturacion = $_POST['id_facturacion'];
        $datos = $this->model->eliminarDatoFacturacion($id_facturacion);
        echo json_encode($datos);
    }


    public function obtenerDatosFacturacion()
    {
        $dato_bancarios = $this->model->obtenerDatosBancarios($_SESSION["id_plataforma"]);
        $dato_facturacion = $this->model->obtenerDatosFacturacion($_SESSION["id_plataforma"]);

        $datos = [
            "datos_bancarios" => $dato_bancarios,
            "datos_facturacion" => $dato_facturacion
        ];

        echo json_encode($datos);
    }


    public function solicitarPago()
    {
        $id_cuenta = $_POST['id_cuenta'];
        $valor = $_POST['valor'];
        $otro = $_POST['otro'] ?? 0;
        $puedeSolicitar = $this->model->puedeSolicitar($_SESSION["enlace"], $valor);
        if ($puedeSolicitar == false) {
            echo json_encode(["status" => 400, "message" => "No puedes solicitar un pago mayor al saldo disponible en tu billetera o ya tienes una solicitud pendiente"]);
            return;
        }
        $fecha = date("Y-m-d H:i:s");

        $response = $this->model->solicitarPago($id_cuenta, $valor, $fecha, $_SESSION["enlace"], $_SESSION["id_plataforma"], $otro);
        if ($response["status"] == 200) {
            $correo = $this->model->obtenerCorreo($_SESSION["id_plataforma"]);
            $this->model->enviarMensaje("solicitud", $correo[0]["correo"], $valor);
        }
        echo json_encode($response);
    }

    public function pagarFactura()
    {
        $valor = $_POST['valor'];
        $documento = $_POST['documento'];
        $forma_pago = $_POST['forma_pago'];
        $fecha = date("Y-m-d H:i:s");
        $imagen = $_FILES['imagen'];
        $id_plataforma = $_POST['id_plataforma'];

        $subirImagen = $this->model->subirImagen($imagen);

        if ($subirImagen['status'] == 1) {
            $response = $this->model->pagarFactura($valor, $documento, $forma_pago, $fecha, $subirImagen['dir'], $id_plataforma);
            echo json_encode($response);
        } else {
            echo json_encode($subirImagen);
        }
    }

    public function obtenerHistorial()
    {
        $tienda     = $_POST['tienda'];
        $response = $this->model->obtenerHistorial($tienda);
        echo json_encode($response);
    }

    public function obtenerCuentas()
    {
        $id_plataforma = $_SESSION['id_plataforma'];
        $response = $this->model->obtenerCuentas($id_plataforma);
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

    public function agregarOtroPago()
    {
        $tipo = $_POST['tipo'];
        $cuenta = $_POST['cuenta'];
        $nombre     = $_POST['nombre'];
        $telefono   = $_POST['telefono'];
        $cedula    = $_POST['cedula'];
        $correo    = $_POST['correo'];
        $red = $_POST['red'];



        $response = $this->model->agregarOtroPago($tipo, $cuenta, $_SESSION['id_plataforma'], $nombre, $telefono, $cedula, $correo, $red);
        echo json_encode($response);
    }

    public function buscarFull()
    {
        $numero_factura = $_POST['numero_factura'];
        $id_plataforma = $_SESSION['id_plataforma'];
        $response = $this->model->buscarFull($numero_factura, $id_plataforma);
    }

    public function eliminarOtroPago()
    {
        $id = $_POST['id'];

        $response = $this->model->eliminarOtroPago($id);
        echo json_encode($response);
    }

    public function obtenerOtroPago()
    {
        $response = $this->model->obtenerOtroPago($_SESSION['id_plataforma']);
        echo json_encode($response);
    }


    public function obtenerSolicitudes()
    {
        $response = $this->model->obtenerSolicitudes();
        echo json_encode($response);
    }
    
    public function obtenerGuiasAuditoria($estado)
    {
        $response = $this->model->obtenerGuiasAuditoria();
        echo json_encode($response);
    }
}
