<?php

use FontLib\Table\Type\head;

class Funnelish extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /*  public function index($id_plataforma)
    {
        if (empty($id_plataforma)) {
            die("Error: No se ha especificado una plataforma");
        }
        $data = file_get_contents("php://input");
        $this->log($data, $id_plataforma, "");

        if ($this->model->existenciaPlataforma($id_plataforma)) {
            $valido = $this->model->productoPlataforma($id_plataforma, $data);
            print_r($valido);
            if ($valido) {
                $this->model->gestionarRequest($id_plataforma, $data);
            } else return;
        } /* else {
            $data = file_get_contents("php://input");
            $this->data($data);
            echo "Plataforma no registrada";
        } 
    } */
    public function index($id_plataforma)
    {
        if (empty($id_plataforma)) {
            die("Error: No se ha especificado una plataforma");
        }
        $datas = explode("-||-", $id_plataforma);
        $id_plataforma = $datas[0];
        $id_registro = $datas[1];
        $data = file_get_contents("php://input");
        $this->log($data, $id_plataforma, $id_registro);

        if ($this->model->existenciaPlataforma($id_plataforma)) {

            $valido = $this->model->productoPlataforma($id_plataforma, $data, $id_registro);
            print_r($valido);
            if ($valido) {
                $this->model->gestionarRequest($id_plataforma, $data);
            } else return;
        }
        /* else {
            $data = file_get_contents("php://input");
            $this->data($data);
            echo "Plataforma no registrada";
        } */
    }

    public function productos()
    {
        if ($this->isAuth()) {
            $this->views->render($this, 'productos');
        } else {
            header('Location: ' . SERVERURL . 'Login');
            exit();
        }
    }

    public function listar()
    {
        $this->isAuth();
        $id_plataforma = $_SESSION['id_plataforma'];
        $data = $this->model->listar($id_plataforma);
        echo json_encode($data);
    }

    public function constructor_vista()
    {
        if (!$this->isAuth()) {
            header('Location: ' . SERVERURL . 'login');
            exit();
        }
        $this->views->render($this, "constructor_vista");
    }

    public function data()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $this->model->saveData($data);
    }

    public function log($data, $id_plataforma, $id_registro)
    {
        $res = $this->model->saveDataPlatform($data, $id_plataforma, $id_registro);
        print_r($res);
    }

    public function get_productos()
    {
        $this->isAuth();
        $response =  $this->model->get_productos($_SESSION['id_plataforma']);
        echo json_encode($response);
    }

    public function ultimoProductos($id_plataforma)
    {
        $id_inventario = $_POST["id_inventario"];
        $response = $this->model->ultimoProductos($id_inventario, $id_plataforma);
        echo json_encode($response);
    }

    public function validarPedido($enlace)
    {
        // Definir el delimitador utilizado en el enlace
        $delimiter = '-||-';

        // 1. Dividir el enlace utilizando el delimitador
        $parts = explode($delimiter, $enlace);

        // 2. Validar que el enlace tiene al menos los elementos necesarios
        if (count($parts) < 6) {
            echo json_encode([
                'status' => 400,
                'mensaje' => 'Formato de enlace incorrecto. Número insuficiente de elementos.'
            ]);
        }

        // 3. Limpiar los elementos vacíos (si los hay)
        $parts = array_filter($parts, function ($value) {
            return trim($value) !== '';
        });

        // Reindexar el array después de filtrar
        $parts = array_values($parts);

        // 4. Extraer los dos últimos elementos
        $numParts = count($parts);
        $id_plataforma = $parts[$numParts - 2];
        $id_registro = $parts[$numParts - 1];

        // 5. Validar que los identificadores son numéricos
        if (!is_numeric($id_plataforma) || !is_numeric($id_registro)) {
            echo json_encode([
                'status' => 400,
                'mensaje' => 'Los identificadores deben ser numéricos.'
            ]);
        }
        $response = $this->model->buscarPedido($id_plataforma, $id_registro);
        echo json_encode($response);
    }

    public function asignarProducto($id_plataforma)
    {
        $id_inventario = $_POST["id_inventario"] ?? null;
        $id_registro = $_POST["id_registro"] ?? null;
        $id_funnel = $_POST["id_funnel"] ?? null;
        $sku = $_POST["sku"] ?? null;

        if ($sku == null || $id_funnel == null || $id_registro == null || $id_inventario == null) {
            echo json_encode([
                "status" => "400",
                "message" => "Enviar los datos completos."
            ]);
            exit;
        }

        $response = $this->model->asignarProducto($id_inventario, $id_registro, $id_funnel, $sku, $id_plataforma);
        echo json_encode($response);
    }
}
