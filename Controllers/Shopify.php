<?php
class Shopify extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index($id_plataforma)
    {
        if (empty($id_plataforma)) {
            die("Error: No se ha especificado una plataforma");
        }
        if ($this->model->existenciaPlataforma($id_plataforma)) {
            $data = file_get_contents("php://input");
            $response = $this->model->agregarJson($id_plataforma, $data);
            echo json_encode($response);
            $valido = $this->model->productoPlataforma($id_plataforma, $data);
            if ($valido) {
                $this->model->gestionarRequest($id_plataforma, $data);
            } else return;
        } else {
            $data = file_get_contents("php://input");
            $response = $this->model->agregarJson($id_plataforma, $data);
            echo json_encode($response);
        }
    }

    public function constructor()
    {
        $this->isAuth();
        $this->views->render($this, "constructor");
    }

    public function constructor_vista()
    {
        $this->isAuth();
        $this->views->render($this, "constructor_vista");
    }

    public function configuracion()
    {
        $this->views->render($this, "configuracion");
    }

    public function generarEnlace()
    {
        $this->isAuth();
        $data = $this->model->generarEnlace($_SESSION["id_plataforma"]);
        echo json_encode($data);
    }


    public function obtenerPlataforma()
    {
        $data = $this->model->obtenerPlataforma($_SESSION["id_plataforma"]);
        echo json_encode($data);
    }

    public function guardarConfiguracion()
    {
        $this->isAuth();
        $nombre = $_POST["nombre"];
        $apellido = $_POST["apellido"];
        $principal = $_POST["principal"];
        $secundario = $_POST["secundario"];
        $provincia = $_POST["provincia"];
        $ciudad = $_POST["ciudad"];
        $codigo_postal = $_POST["codigo_postal"];
        $pais = $_POST["pais"];
        $telefono = $_POST["telefono"];
        $email = $_POST["email"];
        $total = $_POST["total"];
        $descuento = $_POST["descuento"];
        $referencia = $_POST["referencia"];


        $response = $this->model->guardarConfiguracion($nombre, $apellido, $principal, $secundario, $provincia, $ciudad, $codigo_postal, $pais, $telefono, $email, $total, $descuento, $referencia, $_SESSION["id_plataforma"]);
        echo json_encode($response);
    }

    public function verificarConfiguracion()
    {
        $response = $this->model->verificarConfiguracion($_SESSION["id_plataforma"]);
        echo json_encode($response);
    }

    public function obtenerConfiguracion()
    {
        $this->isAuth();
        $response = $this->model->obtenerConfiguracion($_SESSION["id_plataforma"]);
        echo json_encode($response);
    }

    public function modificarConfiguracion()
    {
        $this->isAuth();
        $nombre = $_POST["nombre"];
        $apellido = $_POST["apellido"];
        $principal = $_POST["principal"];
        $secundario = $_POST["secundario"];
        $provincia = $_POST["provincia"];
        $ciudad = $_POST["ciudad"];
        $codigo_postal = $_POST["codigo_postal"];
        $pais = $_POST["pais"];
        $telefono = $_POST["telefono"];
        $email = $_POST["email"];
        $total = $_POST["total"];
        $descuento = $_POST["descuento"];
        $referencia = $_POST["referencia"];

        $response = $this->model->modificarConfiguracion($nombre, $apellido, $principal, $secundario, $provincia, $ciudad, $codigo_postal, $pais, $telefono, $email, $total, $descuento, $referencia, $_SESSION["id_plataforma"]);
        echo json_encode($response);
    }

    public function ultimoJson()
    {
        $this->isAuth();
        $response = $this->model->ultimoJson($_SESSION["id_plataforma"]);
        echo $response[0]["json"];
    }
}
