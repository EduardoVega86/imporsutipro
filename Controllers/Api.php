<?php

class Api extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->views->render($this, 'index');
    }

    public function registro()
    {
        try {
            $this->logRequest('api/registro', $_SERVER['REQUEST_METHOD'], file_get_contents('php://input'));
            $data = json_decode(file_get_contents("php://input"), true);

            if (!$data) {
                http_response_code(400);
                echo json_encode(['status' => 400, 'message' => 'Datos inválidos']);
                return;
            }
            $data = $data["customData"];
            $nombre = $data['nombre'] ?? null;
            $correo = $data['correo'] ?? null;
            $pais = $data['pais'] ?? null;
            $telefono = $data['telefono'] ?? null;
            $contrasena = $data['contrasena'] ?? null;
            $tienda = $data['tienda'] ?? null;
            if (!$nombre || !$correo || !$pais || !$telefono || !$contrasena || !$tienda) {
                http_response_code(400);
                echo json_encode(['status' => 400, 'message' => 'Faltan datos requeridos']);
                return;
            }
            $response = $this->model->registro($nombre, $correo, $pais, $telefono, $contrasena, $tienda);
            if ($response['status'] === 200) {
                http_response_code(200);
            } else {
                http_response_code(400);
            }
            echo json_encode($response);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['status' => 500, 'message' => 'Error interno del servidor', 'error' => $e->getMessage()]);
        }
    }

    public function login()
    {
        $datos = json_decode(file_get_contents("php://input"), true);
        $correo = $datos['correo'] ?? null;
        $contrasena = $datos['contrasena'] ?? null;

        if (!$correo || !$contrasena) {
            http_response_code(400);
            echo json_encode(['status' => 400, 'message' => 'Faltan datos requeridos']);
            return;
        }

        $response = $this->model->login($correo, $contrasena);
        if ($response['status'] === 200) {
            http_response_code(200);
        } else {
            http_response_code(400);
        }
        echo json_encode($response);
    }

    public function pedido($uuid)
    {
        if ($uuid === null) {
            http_response_code(400);
            echo json_encode(['status' => 400, 'message' => 'Faltan datos requeridos']);
            return;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            http_response_code(400);
            echo json_encode(['status' => 400, 'message' => 'Método no permitido']);
        }
        $datos = json_decode(file_get_contents("php://input"), true);
        $response = $this->model->pedido($uuid, $datos);
        if ($response['status'] === 200) {
            http_response_code(200);
        } else {
            http_response_code(400);
        }
        echo json_encode($response);
    }
    public function obtenerDestinatarioWebhook($id_producto)
    {
        $datos = $this->model->obtenerDestinatarioWebhook($id_producto);
        print_r($datos);
    }
    public function getPedidos($uuid)
    {
        if ($uuid === null) {
            http_response_code(400);
            echo json_encode(['status' => 400, 'message' => 'Faltan datos requeridos']);
            return;
        }
        $response = $this->model->getPedidos($uuid);
        if ($response['status'] === 200) {
            http_response_code(200);
        } else {
            http_response_code(400);
        }
        echo json_encode($response);
    }

    public function getCiudades($uuid)
    {
        if ($uuid === null) {
            http_response_code(400);
            echo json_encode(['status' => 400, 'message' => 'Faltan datos requeridos']);
            return;
        }
        $response = $this->model->getCiudades($uuid);
        if ($response['status'] === 200) {
            http_response_code(200);
        } else {
            http_response_code(400);
        }
        echo json_encode($response);
    }

    public function generarGuia($uuid)
    {
        if ($uuid === null) {
            http_response_code(400);
            echo json_encode(['status' => 400, 'message' => 'Faltan datos requeridos']);
            return;
        }
        $data = json_decode(file_get_contents("php://input"), true);
        $response = $this->model->generarGuia($uuid, $data);
        if ($response['status'] === 200) {
            http_response_code(200);
        } else {
            http_response_code(400);
        }
        echo json_encode($response);
    }

    public function agregarProducto()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        print_r($data);
        /*  $response = $this->model->agregarProducto($data);
        if ($response['status'] === 200) {
            http_response_code(200);
        } else {
            http_response_code(400);
        }
        echo json_encode($response); */
    }

    private function logRequest($endpoint, $method, $body)
    {
        $logData = [
            'endpoint' => $endpoint,
            'method' => $method,
            'body' => $body,
            'ip_address' => $_SERVER['REMOTE_ADDR'],
            'user_agent' => $_SERVER['HTTP_USER_AGENT'],
            'timestamp' => date('Y-m-d H:i:s'),
        ];

        // Guardar en un archivo o base de datos
        $logFile = __DIR__ . '/../../api_requests ' . date('Y-m-d HH:ii:ss') . '.log';
        file_put_contents($logFile, json_encode($logData) . PHP_EOL, FILE_APPEND);
    }
}
