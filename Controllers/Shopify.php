<?php
class Shopify extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /*public function index($id_plataforma): void
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
        }
    }*/

    public function index($id_plataforma) :void
    {
        if(empty($id_plataforma)) {
            echo json_encode(["error" => "No se ha especificado una plataforma", "status"=> "error", "code" => 400]);
            exit();
        }
        if(!$this->model->existenciaPlataforma($id_plataforma)){
            $this->model->agregarJson($id_plataforma, file_get_contents("php://input"));
            echo json_encode(["message" => "Se registro la plataforma", "status"=> "success", "code" => 200]);
            exit();
        }
        $data = file_get_contents("php://input");
        $this->model->agregarJson($id_plataforma, $data);
        if($this->model->productoPlataforma($id_plataforma, $data)){
            $this->model->gestionarRequest($id_plataforma, $data);
            exit();
        }
        $this->model->gestionarRequestSinProducto($id_plataforma, $data);
    }

    public function pruebaShopify($id_plataforma)
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
                $this->model->gestionarRequestPrueba($id_plataforma, $data);
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

    public function constructor_abandonado()
    {
        $this->isAuth();
        $this->views->render($this, "constructor_abandonados");
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

    public function constructor_abandonados()
    {
        $this->isAuth();
        $this->views->render($this, "constructor_abandonados");
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

    public function abandonado($id_plataforma){
        /*$data = file_get_contents("php://input");
        $response = $this->model->saveAbandonedCart($id_plataforma, $data);
        echo json_encode($response);*/

        $this->catchAsync(function () use ($id_plataforma) {
            if(empty($id_plataforma))
                throw new Exception("No se ha especificado una plataforma");
            if(!$this->model->existenciaPlataformaAbandonada($id_plataforma)){
                $this->model->saveAbandonedCart($id_plataforma, file_get_contents("php://input"));
                echo json_encode(["message" => "Se registro la plataforma", "status"=> "success", "code" => 200]);
                exit();
            }
            $data = file_get_contents("php://input");
            $response = $this->model->procesarAbandonado($id_plataforma, $data);
            if($response){
                echo json_encode(["message" => "Se registro el abandonado", "status"=> "success", "code" => 200, "data" => $response]);
                exit();
            }
            echo json_encode(["message" => "No se registro el abandonado", "status"=> "error", "code" => 400]);

        })();
    }

    public function buscarEntradaAbandonado($id_plataforma)
    {
        $this->catchAsync(function () use ($id_plataforma) {
            // $response ya es un JSON, por ejemplo: {"campo1":"valor1", "campo2":123}
            $response = $this->model->searchAbandonedCart($id_plataforma);

            // Datos adicionales a agregar
            $extraData = '"status": 200, "message": "Datos encontrados", "count": 1, "title": "Exito"';

            // Se construye el JSON final colocando $response en la clave "data"
            $finalJson = '{"data":' . $response . ', ' . $extraData . '}';

            echo $finalJson;
        })();
    }

    public function guardarAbandonado($id_plataforma)
    {
        $this->catchAsync(function () use ($id_plataforma) {
            $telefono = $_POST["telefono"];
            $producto = $_POST["producto"];

            $response = $this->model->saveAbandonedCarts($id_plataforma, $telefono, $producto);
            echo json_encode($response);
        })();
    }

    public function obtenerAbandonados($id_plataforma)
    {
        $this->catchAsync(function () use ($id_plataforma) {
            $filtro = $_POST["filtro"] ?? null;
            $response = $this->model->getAbandonedCarts($id_plataforma, $filtro);
            echo json_encode(["status" => 200, "message" => "Datos encontrados", "data" => $response, "count" => count($response)]);
        })();
    }






}
