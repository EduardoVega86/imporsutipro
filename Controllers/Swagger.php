<?php

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="Documentación de la API Externa",
 *     version="1.0.0",
 *     description="Documentación de la API generada con Swagger | Ante cualquier duda, contactar con el administrador",
 *    @OA\Contact(
 *        email="jjara@imporfactorylatam.com"
 *   )
 * )
 */

class Swagger extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function swagger()
    {
        $this->views->render($this, "index");
    }

    /**
     * @OA\Post(
     *    path="/api/registro",
     *   tags={"Usuarios"},
     *  summary="Registro de usuarios",
     * description="Registro de usuarios",
     * @OA\RequestBody(
     *   required=true,
     *  @OA\MediaType(
     *    mediaType="application/json",
     *  @OA\Schema(
     *   @OA\Property(
     *    property="nombre",
     *  type="string"
     * ),
     * @OA\Property(
     *   property="correo",
     * type="string"
     * ),
     * @OA\Property(
     *  property="pais",
     * type="string"
     * ),
     * @OA\Property(
     * property="telefono",
     * type="string"
     * ),
     * @OA\Property(
     * property="contrasena",
     * type="string"
     * ),
     * @OA\Property(
     * property="tienda",
     * type="string"
     * )
     * )
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="Registro exitoso"
     * ),
     * @OA\Response(
     * response=400,
     * description="Error en el registro"
     * )
     * )
     */

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

    /**
     * @OA\Post(
     *      path="/api/login",
     *      tags={"Usuarios"},
     *      summary="Inicio de sesión",
     *      description="Inicio de sesión",
     *          @OA\RequestBody(
     *          required=true,
     *              @OA\MediaType(
     *              mediaType="application/json",
     *                  @OA\Schema(
     *                      @OA\Property(
     *                      property="correo",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                  property="contrasena",
     *                  type="string"
     *                  )
     *              )
     *        )
     * ),
     * @OA\Response(
     *      response=200,
     *      description="Inicio de sesión exitoso"
     * ),
     * @OA\Response(
     *      response=400,
     *      description="Error en el inicio de sesión"
     * )
     * )
     */

    public function login()
    {
        try {
            $this->logRequest('api/login', $_SERVER['REQUEST_METHOD'], file_get_contents('php://input'));
            $data = json_decode(file_get_contents("php://input"), true);

            if (!$data) {
                http_response_code(400);
                echo json_encode(['status' => 400, 'message' => 'Datos inválidos']);
                return;
            }
            // $data = $data["customData"];
            $correo = $data['correo'] ?? null;
            $contrasena = $data['contrasena'] ?? null;
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
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['status' => 500, 'message' => 'Error interno del servidor', 'error' => $e->getMessage()]);
        }
    }

    /**
     * @OA\Get(
     *   path="/api/pedidos",
     *  tags={"Pedidos"},
     * summary="Obtener pedidos",
     * description="Obtener pedidos por plataforma",
     * @OA\Parameter(
     *  name="uuid",
     * in="query",
     * description="UUID de la plataforma",
     * required=true,
     * @OA\Schema(
     * type="string"
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="Pedidos obtenidos"
     * ),
     * @OA\Response(
     * response=400,
     * description="Error al obtener pedidos"
     * )
     * )
     */
    public function pedidos($uuid)
    {
        try {
            $this->logRequest('api/pedidos', $_SERVER['REQUEST_METHOD'], file_get_contents('php://input'));
            $response = $this->model->getPedidos($uuid);
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

    public function docs()
    {
        $openapi = \OpenApi\Generator::scan([__DIR__ . "/Swagger.php"]);;
        header('Content-Type: application/json');
        echo $openapi->toJson();
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
