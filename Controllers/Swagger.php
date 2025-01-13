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
     *      path="/swagger/validar_tiendas",
     *      tags={"Usuarios"},
     *      summary="Validación de tiendas",
     *      description="Endpoint utilizado para validar las tiendas existentes.",
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
     *      description="Validación exitosa de la tienda"
     * ),
     * @OA\Response(
     *      response=400,
     *      description="Error en la validación de la tienda"
     * )
     * )
     */

    public function validar_tiendas()
    {
        try {
            // Log de la solicitud para depuración
            $this->logRequest('api/validar_tiendas', $_SERVER['REQUEST_METHOD'], file_get_contents('php://input'));

            // Decodificación del cuerpo de la solicitud
            $data = json_decode(file_get_contents("php://input"), true);
            $tienda = $data['tienda'] ?? null;

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

            // Construcción de la respuesta basada en la validación
            if ($response['exists']) {
                $this->handleResponse([
                    'status' => 200,
                    'message' => "La tienda '$tienda' existe en la base de datos.",
                    'data' => $response
                ], 200); // Devuelve explícitamente el código 200
            } else {
                $this->handleResponse([
                    'status' => 200,
                    'message' => "La tienda '$tienda' no existe en la base de datos.",
                    'data' => $response
                ], 200); // También 200 porque la solicitud fue válida, aunque no exista
            }
        } catch (Exception $e) {
            // Manejo de excepciones
            $this->handleException($e);
        }
    }



    /**
     * @OA\Post(
     *     path="/swagger/recuperar_contrasena",
     *     tags={"Usuarios"},
     *     summary="Recuperación de contraseña",
     *     description="Endpoint que permite generar un token de recuperación y enviar un correo al usuario con un enlace para restablecer la contraseña.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="correo",
     *                     type="string",
     *                     description="Correo electrónico del usuario"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Correo enviado correctamente"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="El campo correo es requerido"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al procesar la solicitud"
     *     )
     * )
     */
    public function recuperar_contrasena()
    {
        try {
            // Log de la solicitud para depuración
            $this->logRequest('api/recuperar_contrasena', $_SERVER['REQUEST_METHOD'], file_get_contents('php://input'));
            // Obtener el cuerpo de la solicitud
            $data = json_decode(file_get_contents("php://input"), true);
            $correo = $data['correo'] ?? null;

            // Validar que se envíe el correo
            if (!$correo) {
                $this->handleResponse([
                    'status' => 400,
                    'message' => 'El campo correo es requerido'
                ]);
                return;
            }

            // Llamar al modelo para realizar la recuperación de contraseña
            $response = $this->model->recuperarContrasena($correo);
            $this->handleResponse($response);
        } catch (Exception $e) {
            // Manejo de errores
            $this->handleException($e);
        }
    }

    /**
     * @OA\Post(
     *     path="/swagger/validar_token",
     *     tags={"Usuarios"},
     *     summary="Validación del token",
     *     description="Endpoint que permite validar el token del usuario.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="token",
     *                     type="string",
     *                     description="Token del usuario"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Validación exitosa"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="El campo token es requerido"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al procesar la solicitud"
     *     )
     * )
     */

    public function validar_token()
    {
        try {
            // Log de la solicitud para depuración
            $this->logRequest('api/validar_token', $_SERVER['REQUEST_METHOD'], file_get_contents('php://input'));
            // Obtener el cuerpo de la solicitud
            $data = json_decode(file_get_contents("php://input"), true);
            $token = $data['token'] ?? null;

            if (!$token) {
                $this->handleResponse([
                    'status' => 400,
                    'message' => 'El campo token es requerido'
                ]);
                return;
            }

            $response = $this->model->validarToken($token);

            // Construcción de la respuesta basada en la validación
            if ($response['exists']) {
                $this->handleResponse([
                    'status' => 200,
                    'message' => "El token: '$token' es valido.",
                    'data' => $response
                ], 200); // Devuelve explícitamente el código 200
            } else {
                $this->handleResponse([
                    'status' => 200,
                    'message' => "El token: '$token' es invalido.",
                    'data' => $response
                ], 200); // También 200 porque la solicitud fue válida, aunque no exista
            }
        } catch (Exception $e) {
            // Manejo de errores
            $this->handleException($e);
        }
    }

    /**
     * @OA\Post(
     *     path="/swagger/cambiar_contrasena",
     *     tags={"Usuarios"},
     *     summary="Modificación de contraseña",
     *     description="Endpoint que permite cambiar la contraseña de los usuarios.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="contraseña",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="token",
     *                     type="string"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Actualización exitosa"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error al actualizar la contraseña"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno al procesar la solicitud"
     *     )
     * )
     */



    public function cambiar_contrasena()
    {
        try {
            // Log de la solicitud para depuración
            $this->logRequest('api/cambiar_contrasena', $_SERVER['REQUEST_METHOD'], file_get_contents('php://input'));
            // Obtener el cuerpo de la solicitud
            $data = json_decode(file_get_contents("php://input"), true);
            $contrasena = $data['contraseña'] ?? null;
            $token = $data['token'] ?? null;

            if (!$contrasena) {
                $this->handleResponse([
                    'status' => 400,
                    'message' => 'El campo contraseña es requerido'
                ]);
                return;
            }

            if (!$token) {
                $this->handleResponse([
                    'status' => 400,
                    'message' => 'El campo token es requerido'
                ]);
                return;
            }

            // Llamar al modelo para realizar el cambio de contraseña
            $response = $this->model->cambiarContrasena($token, $contrasena);
            $this->handleResponse($response);
        } catch (Exception $e) {
            // Manejo de errores
            $this->handleException($e);
        }
    }

    /**
     * @OA\Post(
     *     path="/swagger/validar_refiere",
     *     tags={"Usuarios"},
     *     summary="Validación del token refiere",
     *     description="Endpoint que permite validar el token referido de las plataformas.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="id_referido",
     *                     type="string",
     *                     description="Token referido de plataforma"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Validación exitosa"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="El campo token referido es requerido"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al procesar la solicitud"
     *     )
     * )
     */

    public function validar_refiere()
    {
        try {
            // Log de la solicitud para depuración
            $this->logRequest('api/validar_refiere', $_SERVER['REQUEST_METHOD'], file_get_contents('php://input'));
            // Obtener el cuerpo de la solicitud
            $data = json_decode(file_get_contents("php://input"), true);
            $id_referido = $data['id_referido'] ?? null;

            // Validar que se envíe el correo
            if (!$id_referido) {
                $this->handleResponse([
                    'status' => 400,
                    'message' => 'El campo id referido es requerido'
                ]);
                return;
            }
            // Llamar al modelo para realizar la validacion del token
            $response = $this->model->validarRefiere($id_referido);

            // Construcción de la respuesta basada en la validación
            if ($response['exists']) {
                $this->handleResponse([
                    'status' => 200,
                    'message' => "El token: '$id_referido' es valido.",
                    'data' => $response
                ], 200); // Devuelve explícitamente el código 200
            } else {
                $this->handleResponse([
                    'status' => 200,
                    'message' => "El token: '$id_referido' es invalido.",
                    'data' => $response
                ], 200); // También 200 porque la solicitud fue válida, aunque no exista
            }
        } catch (Exception $e) {
            // Manejo de errores
            $this->handleException($e);
        }
    }

    /**
     * @OA\Get(
     *     path="/swagger/obtener_productos",
     *     tags={"Productos"},
     *     summary="Obtener productos por plataforma",
     *     description="Endpoint para obtener la lista de productos asociados a una plataforma.",
     *     @OA\Parameter(
     *         name="uuid",
     *         in="query",
     *         description="UUID del usuario o plataforma",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Productos obtenidos exitosamente"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error al obtener productos"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado"
     *     )
     * )
     */
    public function obtener_productos()
    {
        try {
            $this->logRequest('swagger/obtener_productos', $_SERVER['REQUEST_METHOD'], file_get_contents('php://input'));
            $data = json_decode(file_get_contents("php://input"), true);

            // Si $id está vacío, intenta capturarlo desde $_GET
            if (empty($uuid)) {
                $uuid = $_GET['uuid'] ?? null;
            }

            if (!$data) {
                http_response_code(400);
                echo json_encode(['status' => 400, 'message' => 'Datos inválidos']);
                return;
            }

            $uuid = $data['uuid'] ?? null;


            // Validación de todos los datos requeridos
            if (!$uuid) {
                http_response_code(400);
                echo json_encode(['status' => 400, 'message' => 'Faltan datos requeridos']);
                return;
            }

            // Llamada al modelo para registrar el referido
            $response = $this->model->obtener_productos($uuid);
            $this->handleResponse($response);
        } catch (Exception $e) {
            $this->handleException($e);
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
