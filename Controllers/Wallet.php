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
            $this->model->crearBilltera($tienda);
            echo "XD";
        }

        $this->views->render($this, "pagar");
    }


    ///
    public function obtenerDatos()
    {

        $datos = $this->model->obtenerTiendas();
        echo $datos;
    }

    public function abonarBilletera()
    {
        $id_cabecera = $_POST['id_cabecera'];
        $valor = $_POST['valor'];
        $usuario = $_SESSION['id'];

        $datos = $this->model->abonarBilletera($id_cabecera, $valor, $usuario);
    }

    public function reversarAbono()
    {
        $id_cabecera = $_POST['id_cabecera'];
        $valor = $_POST['valor'];
        $usuario = $_SESSION['id'];

        $datos = $this->model->reversarAbono($id_cabecera, $valor, $usuario);
    }
}
