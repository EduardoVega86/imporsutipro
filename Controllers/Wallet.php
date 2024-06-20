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
        $this->views->render($this, "index");
        if ($_SESSION["id_plataforma"] != 1167) {
            header("Location: /wallet/billetera");
        }
    }

    public function billetera()
    {
        $this->views->render($this, "billetera");
    }

    public function pagar()
    {
        $tienda = $_GET['tienda'];
        if ($_SESSION["id_plataforma"] != 1167) {
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
}
