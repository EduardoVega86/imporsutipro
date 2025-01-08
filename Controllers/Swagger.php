<?php
require 'vendor/autoload.php';

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

    public function index()
    {
        $this->views->render($this, "index");
    }

    private function handleResponse(array $response)
    {
        if (isset($response['status']) && $response['status'] == 200) {
            http_response_code(200);
        } else {
            http_response_code(400);
        }
        echo json_encode($response);
    }

    //Manejo de excepciones
    private function handleException(Exception $e)
    {
        http_response_code(500);
        echo json_encode([
            'status' => 500,
            'message' => 'message',
            'error' => $e->getMessage()

        ]);
    }


    /**
     * @OA\Post(
     *     path="/swagger/registro",
     *     tags={"Usuarios"},
     *     summary="Registro de usuarios",
     *     description="Endpoint utilizado para el registro de usuarios.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="nombre",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="correo",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="pais",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="telefono",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="contrasena",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="tienda",
     *                     type="string"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Registro exitoso"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error en el registro"
     *     )
     * )
     */

    public function registro()
    {
        try {
            $this->logRequest('swagger/registro', $_SERVER['REQUEST_METHOD'], file_get_contents('php://input'));
            $data = json_decode(file_get_contents("php://input"), true);

            if (!$data) {
                http_response_code(400);
                echo json_encode(['status' => 400, 'message' => 'Datos inválidos']);
                return;
            }

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
            $this->handleResponse($response);
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }

    /**
     * @OA\Post(
     *     path="/swagger/registro_referido",
     *     tags={"Usuarios"},
     *     summary="Registro de usuarios referidos",
     *     description="Endpoint utilizado para el registro de usuarios referidos.",
     *     @OA\Parameter(
     *        name="id_plataforma",
     *        in="query",
     *        description="id de la plataforma",
     *        required=true,
     *        @OA\Schema(
     *            type="string"
     *        )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="nombre",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="correo",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="pais",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="telefono",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="contrasena",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="tienda",
     *                     type="string"
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Registro exitoso"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error en el registro"
     *     )
     * )
     */

    public function registro_referido($id)
    {
        try {
            $this->logRequest('swagger/registro_referido', $_SERVER['REQUEST_METHOD'], file_get_contents('php://input'));
            $data = json_decode(file_get_contents("php://input"), true);

            // Si $id está vacío, intenta capturarlo desde $_GET
            if (empty($id)) {
                $id = $_GET['id_plataforma'] ?? null;
            }

            if (!$data) {
                http_response_code(400);
                echo json_encode(['status' => 400, 'message' => 'Datos inválidos']);
                return;
            }

            $nombre = $data['nombre'] ?? null;
            $correo = $data['correo'] ?? null;
            $pais = $data['pais'] ?? null;
            $telefono = $data['telefono'] ?? null;
            $contrasena = $data['contrasena'] ?? null;
            $tienda = $data['tienda'] ?? null;

            // Validación de todos los datos requeridos
            if (!$nombre || !$correo || !$pais || !$telefono || !$contrasena || !$tienda || !$id) {
                http_response_code(400);
                echo json_encode(['status' => 400, 'message' => 'Faltan datos requeridos']);
                return;
            }

            // Llamada al modelo para registrar el referido
            $response = $this->model->registro_referido($nombre, $correo, $pais, $telefono, $tienda, $contrasena, $id);
            $this->handleResponse($response);
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }


    /**
     * @OA\Post(
     *      path="/swagger/login",
     *      tags={"Usuarios"},
     *      summary="Inicio de sesión",
     *      description="Endpoint utilizado para el inicio de sesión de los usuarios.",
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
            $correo = $data['correo'] ?? null;
            $contrasena = $data['contrasena'] ?? null;
            if (!$correo || !$contrasena) {
                http_response_code(400);
                echo json_encode(['status' => 400, 'message' => 'Faltan datos requeridos']);
                return;
            }
            $response = $this->model->login($correo, $contrasena);
            $this->handleResponse($response);
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }


    /**
     * @OA\Post(
     *      path="/swagger/validarTiendas",
     *      tags={"Usuarios"},
     *      summary="Validar tiendas",
     *      description="Endpoint utilizado para validar tiendas existentes.",
     *          @OA\RequestBody(
     *          required=true,
     *              @OA\MediaType(
     *              mediaType="application/json",
     *                  @OA\Schema(
     *                      @OA\Property(
     *                      property="tienda",
     *                      type="string"
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

    public function validarTiendas()
    {
        try {
            // Log de la solicitud para depuración
            $this->logRequest('api/validarTiendas', $_SERVER['REQUEST_METHOD'], file_get_contents('php://input'));

            // Decodificación del cuerpo de la solicitud
            $data = json_decode(file_get_contents("php://input"), true);
            $tienda = $data['tienda'] ?? null;

            // Log para verificar la tienda enviada
            print_r([$tienda]);

            // Verificación del dato requerido
            if (!$tienda) {
                $this->handleResponse([
                    'status' => 400,
                    'message' => 'El campo tienda es requerido'
                ]);
                return;
            }

            // Llamada al modelo para validar la tienda
            $response = $this->model->validarTiendas($tienda);

            // Manejo de la respuesta del modelo
            if (isset($response['exists'])) {
                $this->handleResponse([
                    'status' => 200,
                    'message' => 'Validación exitosa',
                    'data' => $response
                ]);
            } else {
                $this->handleResponse([
                    'status' => 400,
                    'message' => 'La tienda no existe o respuesta inválida'
                ]);
            }
        } catch (Exception $e) {
            // Manejo de excepciones
            $this->handleResponse([
                'status' => 500,
                'message' => 'Error interno',
                'error' => $e->getMessage()
            ]);
        }
    }




























    /**
     * @OA\Get(
     *     path="/swagger/pedidos",
     *     tags={"Pedidos"},
     *     summary="Obtener pedidos",
     *     description="Obtener pedidos por plataforma",
     *     @OA\Parameter(
     *         name="uuid",
     *         in="query",
     *         description="UUID de la plataforma",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Pedidos obtenidos"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error al obtener pedidos"
     *     )
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
        $openapi = \OpenApi\Generator::scan([__DIR__ . "/Swagger.php"]);
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
