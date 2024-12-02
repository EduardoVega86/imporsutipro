<?php

use FontLib\Table\Type\head;

class Funnelish extends Controller
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
        $data = file_get_contents("php://input");
        $this->log($data, $id_plataforma);

        if ($this->model->existenciaPlataforma($id_plataforma)) {
            /*  $response = $this->log($id_plataforma, $data); */
            $valido = $this->model->productoPlataforma($id_plataforma, $data);
            print_r($valido);
            if ($valido) {
                $this->model->gestionarRequest($id_plataforma, $data);
            } else return;
        } /* else {
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

    public function log($data, $id_plataforma)
    {
        $this->model->saveDataPlatform($data, $id_plataforma);
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
            return [
                'status' => 400,
                'mensaje' => 'Formato de enlace incorrecto. Número insuficiente de elementos.'
            ];
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
            return [
                'status' => 400,
                'mensaje' => 'Los identificadores deben ser numéricos.'
            ];
        }

        // 6. Validar el resto del formato del enlace (opcional)
        // Puedes agregar validaciones adicionales según tus necesidades, por ejemplo:
        // - Verificar que los elementos específicos coinciden con valores esperados (como 'funnelish' o 'index')

        // 7. Si todo está correcto, proceder con la lógica adicional
        // Aquí puedes agregar el código que necesites utilizando $id_plataforma y $id_registro

        // Ejemplo de respuesta
        return [
            'status' => 200,
            'mensaje' => 'Enlace válido.',
            'id_plataforma' => $id_plataforma,
            'id_registro' => $id_registro
        ];
    }
}
