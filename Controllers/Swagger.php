<?php
require 'vendor/autoload.php';
require_once 'Models/ProductosModel.php';

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
     *     tags={"Acceso - Usuarios"},
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
     *     tags={"Acceso - Usuarios"},
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
     *      tags={"Acceso - Usuarios"},
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
     *      tags={"Acceso - Usuarios"},
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
     *     tags={"Acceso - Usuarios"},
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
     *     tags={"Acceso - Usuarios"},
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
     *     tags={"Acceso - Usuarios"},
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

            if (!$contrasena || !$token) {
                $this->handleResponse([
                    'status' => 400,
                    'message' => 'Faltan datos requeridos'
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
     *     tags={"Acceso - Usuarios"},
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
            // Log de la solicitud
            $this->logRequest('swagger/obtener_productos', $_SERVER['REQUEST_METHOD'], file_get_contents('php://input'));

            // Captura el UUID desde los parámetros GET
            $uuid = $_GET['uuid'] ?? null;

            // Validar UUID
            if (!$uuid) {
                http_response_code(400);
                echo json_encode(['status' => 400, 'message' => 'Faltan datos requeridos: uuid']);
                return;
            }

            // Llamar al modelo para obtener productos
            $response = $this->model->obtener_productos($uuid);
            $this->handleResponse($response);
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }
    /**
     * @OA\Get(
     *     path="/swagger/obtener_productos_todos",
     *     tags={"Productos"},
     *     summary="Obtener todos los productos por id y nombre de productos",
     *     description="Endpoint para obtener el id_producto y nombre_producto de la tabla productos",
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
     * )
     */
    public function obtener_productos_todos()
    {
        try {
            // Log de la solicitud
            $this->logRequest('swagger/obtener_productos_todos', $_SERVER['REQUEST_METHOD'], file_get_contents('php://input'));

            $uuid = $_GET['uuid'] ?? null; // Capturar UUID desde query parameters

            // Validar UUID
            if (!$uuid) {
                http_response_code(400);
                echo json_encode(['status' => 400, 'message' => 'Faltan datos requeridos: uuid']);
                return;
            }

            // Llamar al modelo para obtener productos
            $response = $this->model->obtenerProductosTodos($uuid);
            $this->handleResponse($response);
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }

    /**
     * @OA\Get(
     *     path="/swagger/obtener_bovedas_plataforma",
     *     tags={"Productos"},
     *     summary="Obtener bovedas por plataforma",
     *     description="Permite obtener la lista de bovedas asociadas a una plataforma.",
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
     *         description="Bovedas obtenidas exitosamente"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error al obtener bovedas"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado"
     *     )
     * )
     */
    public function obtener_bovedas_plataforma()
    {
        try {
            // Log de la solicitud
            $this->logRequest('swagger/obtener_bovedas_plataforma', $_SERVER['REQUEST_METHOD'], file_get_contents('php://input'));

            // Captura el UUID desde los parámetros GET
            $uuid = $_GET['uuid'] ?? null;

            // Validar UUID
            if (!$uuid) {
                http_response_code(400);
                echo json_encode(['status' => 400, 'message' => 'Faltan datos requeridos: uuid']);
                return;
            }

            // Llamar al modelo para obtener bovedas
            $response = $this->model->obtener_bovedas($uuid);
            $this->handleResponse($response);
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }

    /**
     * @OA\Get(
     *     path="/swagger/obtener_bovedas_todas",
     *     tags={"Productos"},
     *     summary="Obtener todas las bovedas",
     *     description="Permite obtener toda la lista de bovedas para los usuarios estudiantes.",
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
     *         description="Bovedas obtenidas exitosamente"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error al obtener bovedas"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado"
     *     )
     * )
     */
    public function obtener_bovedas_todas()
    {
        try {
            // Log de la solicitud
            $this->logRequest('swagger/obtener_bovedas_todas', $_SERVER['REQUEST_METHOD'], file_get_contents('php://input'));

            // Captura el UUID desde los parámetros GET
            $uuid = $_GET['uuid'] ?? null;

            // Validar UUID
            if (!$uuid) {
                http_response_code(400);
                echo json_encode(['status' => 400, 'message' => 'Faltan datos requeridos: uuid']);
                return;
            }

            // Llamar al modelo para obtener bovedas
            $response = $this->model->obtenerBovedasTodas($uuid);
            $this->handleResponse($response);
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }

    /**
     * @OA\Get(
     *     path="/swagger/obtener_boveda_id",
     *     tags={"Productos"},
     *     summary="Obtener boveda por id",
     *     description="Permite obtener el id de la boveda, el cual se usa para editar las bovedas.",
     *     @OA\Parameter(
     *         name="uuid",
     *         in="query",
     *         description="UUID del usuario o plataforma",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="id_boveda",
     *         in="query",
     *         description="id de la boveda",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Boveda obtenida exitosamente"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error al obtener boveda"
     *     )
     * )
     */
    public function obtener_boveda_id($id)
    {
        try {
            // Log de la solicitud
            $this->logRequest('swagger/obtener_boveda_id', $_SERVER['REQUEST_METHOD'], file_get_contents('php://input'));

            // Si $id está vacío, intenta capturarlo desde $_GET
            if (empty($id)) {
                $id = $_GET['id_boveda'] ?? null;
            }
            // Captura el UUID desde los parámetros GET
            $uuid = $_GET['uuid'] ?? null;

            if (!$uuid || !$id) {
                http_response_code(400);
                echo json_encode(['status' => 400, 'message' => 'Faltan datos requeridos']);
                return;
            }

            // Llamar al modelo para obtener bovedas
            $response = $this->model->obtenerBovedasId($uuid, $id);
            $this->handleResponse($response);
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }

    /**
     * @OA\Get(
     *     path="/swagger/obtener_proveedores_uno",
     *     tags={"Productos"},
     *     summary="Obtener proveedores de plataformas",
     *     description="Permite obtener el nombre de la tienda y el id plataforma de los proveedores (tabla plataformas) donde su estado como proveedor es 1.",
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
     *         description="Proveedores obtenidos exitosamente"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error al obtener proveedores"
     *     )
     * )
     */
    public function obtener_proveedores_uno()
    {
        try {
            // Log de la solicitud
            $this->logRequest('swagger/obtener_proveedores_uno', $_SERVER['REQUEST_METHOD'], file_get_contents('php://input'));

            // Captura el UUID desde los parámetros GET
            $uuid = $_GET['uuid'] ?? null;

            if (!$uuid) {
                http_response_code(400);
                echo json_encode(['status' => 400, 'message' => 'Faltan datos requeridos']);
                return;
            }

            // Llamar al modelo para obtener bovedas
            $response = $this->model->obtenerProveedoresUno($uuid);
            $this->handleResponse($response);
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }


    /**
     * @OA\Get(
     *     path="/swagger/obtener_lineas_global",
     *     tags={"Productos"},
     *     summary="Obtener lineas globales por plataforma",
     *     description="Permite obtener la lista de lineas globales asociadas a una plataforma.",
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
     *         description="Lineas obtenidas exitosamente"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error al obtener lineas"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado"
     *     )
     * )
     */
    public function obtener_lineas_global()
    {
        try {
            // Log de la solicitud
            $this->logRequest('swagger/obtener_lineas_global', $_SERVER['REQUEST_METHOD'], file_get_contents('php://input'));

            // Captura el UUID desde los parámetros GET
            $uuid = $_GET['uuid'] ?? null;

            // Validar UUID
            if (!$uuid) {
                http_response_code(400);
                echo json_encode(['status' => 400, 'message' => 'Faltan datos requeridos: uuid']);
                return;
            }

            // Llamar al modelo para obtener proveedores
            $response = $this->model->obtener_lineas_global($uuid);
            $this->handleResponse($response);
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }

    /**
     * @OA\Get(
     *     path="/swagger/obtener_productos_privados",
     *     tags={"Productos"},
     *     summary="Obtener productos privados",
     *     description="Permite obtener los productos privados por plataforma.",
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
     *         description="Productos privados obtenidos exitosamente"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Faltan datos requeridos"
     *     ),
     * )
     */
    public function obtener_productos_privados()
    {
        try {
            // Log de la solicitud
            $this->logRequest('swagger/obtener_productos_privados', $_SERVER['REQUEST_METHOD'], file_get_contents('php://input'));
            // Obtener los parámetros desde la URL
            $uuid = $_GET['uuid'] ?? null;

            // Validar que ambos parámetros estén presentes
            if (!$uuid) {
                http_response_code(400);
                echo json_encode(['status' => 400, 'message' => 'UUID e ID de plataforma son requeridos']);
                return;
            }

            // Llamar al modelo para obtener los productos privados
            $response = $this->model->obtenerProductosPrivados($uuid);
            echo json_encode($response);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['status' => 500, 'message' => 'Error interno', 'error' => $e->getMessage()]);
        }
    }

    /**
     * @OA\Get(
     *     path="/swagger/obtener_productos_tienda",
     *     tags={"Productos"},
     *     summary="Obtener productos de tienda",
     *     description="Permite obtener los productos de tienda donde coincida el id_producto de la tabla productos_tienda con el id_producto de la tabla productos y donde coincida el id_inventario de la tabla productos_tienda con el id_invetario de la tabla inventario_bodegas por plataforma.",
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
     *         description="Productos tienda obtenidos exitosamente"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Faltan datos requeridos"
     *     ),
     * )
     */
    public function obtener_productos_tienda()
    {
        try {
            $this->logRequest('swagger/obtener_productos_tienda', $_SERVER['REQUEST_METHOD'], file_get_contents('php://input'));
            // Obtener los parámetros desde la URL
            $uuid = $_GET['uuid'] ?? null;

            // Validar que ambos parámetros estén presentes
            if (!$uuid) {
                http_response_code(400);
                echo json_encode(['status' => 400, 'message' => 'Faltan campos requeridos']);
                return;
            }

            // Llamar al modelo para obtener los productos privados
            $response = $this->model->obtenerProductosTienda($uuid);
            echo json_encode($response);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['status' => 500, 'message' => 'Error interno', 'error' => $e->getMessage()]);
        }
    }
    /**
     * @OA\Get(
     *     path="/swagger/obtener_productos_tienda_automatizador",
     *     tags={"Productos"},
     *     summary="Obtener productos de tienda automatizador",
     *     description="Permite obtener los productos de tienda dependiendo del ID de plataforma. Si la plataforma es 1206, se realiza una consulta que une las tablas shopify_tienda, inventario_bodegas y productos, donde coincida el id_inventario. Para otras plataformas, se obtienen los productos donde coincidan los id_producto entre productos_tienda y productos, y los id_inventario entre productos_tienda e inventario_bodegas.",
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
     *         description="Productos tienda automatizador obtenidos exitosamente"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Faltan datos requeridos"
     *     ),
     * )
     */
    public function obtener_productos_tienda_automatizador()
    {
        try {
            $this->logRequest('swagger/obtener_productos_tienda_automatizador', $_SERVER['REQUEST_METHOD'], file_get_contents('php://input'));
            // Obtener los parámetros desde la URL
            $uuid = $_GET['uuid'] ?? null;

            // Validar que ambos parámetros estén presentes
            if (!$uuid) {
                http_response_code(400);
                echo json_encode(['status' => 400, 'message' => 'Faltan campos requeridos']);
                return;
            }

            // Llamar al modelo para obtener los productos privados
            $response = $this->model->obtenerProductosTiendaAutomatizador($uuid);
            echo json_encode($response);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['status' => 500, 'message' => 'Error interno', 'error' => $e->getMessage()]);
        }
    }
    /**
     * @OA\Get(
     *     path="/swagger/obtener_productos_inventario",
     *     tags={"Productos"},
     *     summary="Obtener productos de inventario",
     *     description="Permite obtener los productos de la plataforma especificada mediante una consulta que combina las tablas productos, inventario_bodegas y variedades, filtrando los resultados por la plataforma especificada.",
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
     *         description="Productos del inventario obtenidos exitosamente"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Faltan datos requeridos"
     *     ),
     * )
     */
    public function obtener_productos_inventario()
    {
        try {
            $this->logRequest('swagger/obtener_productos_inventario', $_SERVER['REQUEST_METHOD'], file_get_contents('php://input'));
            // Obtener los parámetros desde la URL
            $uuid = $_GET['uuid'] ?? null;

            // Validar que ambos parámetros estén presentes
            if (!$uuid) {
                http_response_code(400);
                echo json_encode(['status' => 400, 'message' => 'Faltan campos requeridos']);
                return;
            }

            // Llamar al modelo para obtener los productos privados
            $response = $this->model->obtenerProductosInventario($uuid);
            echo json_encode($response);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['status' => 500, 'message' => 'Error interno', 'error' => $e->getMessage()]);
        }
    }
    /**
     * @OA\Get(
     *     path="/swagger/obtener_producto",
     *     tags={"Productos"},
     *     summary="Obtener producto de inventario",
     *     description="Obtiene un producto específico y su relación con inventario_bodegas, donde coincida el id_producto en ambas tablas, limitado a un resultado.",
     *     @OA\Parameter(
     *         name="uuid",
     *         in="query",
     *         description="UUID del usuario o plataforma",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="id_producto",
     *         in="query",
     *         description="ID del producto",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Producto del inventario obtenido exitosamente"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Faltan datos requeridos"
     *     ),
     * )
     */
    public function obtener_producto()
    {
        try {
            $this->logRequest('swagger/obtener_producto', $_SERVER['REQUEST_METHOD'], file_get_contents('php://input'));
            // Obtener los parámetros desde la URL
            $uuid = $_GET['uuid'] ?? null;
            $id_producto = $_GET['id_producto'] ?? null;

            // Validar que ambos parámetros estén presentes
            if (!$uuid || !$id_producto) {
                http_response_code(400);
                echo json_encode(['status' => 400, 'message' => 'UUID e ID producto son requeridos']);
                return;
            }

            // Llamar al modelo para obtener los productos 
            $response = $this->model->obtenerProducto($uuid, $id_producto);
            echo json_encode($response);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['status' => 500, 'message' => 'Error interno', 'error' => $e->getMessage()]);
        }
    }

    /**
     * @OA\Get(
     *     path="/swagger/obtener_bodega",
     *     tags={"Productos"},
     *     summary="Obtener bodega por id",
     *     description="Permite obtener la bodega segun su id e id_plataforma proporcionado.",
     *     @OA\Parameter(
     *         name="uuid",
     *         in="query",
     *         description="UUID del usuario o plataforma",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="id_bodega",
     *         in="query",
     *         description="ID de la bodega",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Bodega obtenida exitosamente"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Faltan datos requeridos"
     *     ),
     * )
     */
    public function obtener_bodega()
    {
        try {
            $this->logRequest('swagger/obtener_bodega', $_SERVER['REQUEST_METHOD'], file_get_contents('php://input'));
            // Obtener los parámetros desde la URL
            $uuid = $_GET['uuid'] ?? null;
            $id_bodega = $_GET['id_bodega'] ?? null;

            // Validar que ambos parámetros estén presentes
            if (!$uuid || !$id_bodega) {
                http_response_code(400);
                echo json_encode(['status' => 400, 'message' => 'Faltan campos requeridos']);
                return;
            }

            // Llamar al modelo para obtener las bodegas
            $response = $this->model->obtenerBodega($uuid, $id_bodega);
            echo json_encode($response);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['status' => 500, 'message' => 'Error interno', 'error' => $e->getMessage()]);
        }
    }

    /**
     * @OA\Get(
     *     path="/swagger/obtener_bodegas",
     *     tags={"Productos"},
     *     summary="Obtener bodegas por plataforma",
     *     description="Permite obtener las bodegas segun su plataforma correspondiente.",
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
     *         description="Bodegas obtenidas exitosamente"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Faltan datos requeridos"
     *     ),
     * )
     */
    public function obtener_bodegas()
    {
        try {
            $this->logRequest('swagger/obtener_bodegas', $_SERVER['REQUEST_METHOD'], file_get_contents('php://input'));
            // Obtener los parámetros desde la URL
            $uuid = $_GET['uuid'] ?? null;

            // Validar que ambos parámetros estén presentes
            if (!$uuid) {
                http_response_code(400);
                echo json_encode(['status' => 400, 'message' => 'Faltan campos requeridos']);
                return;
            }

            // Llamar al modelo para obtener las bodegas
            $response = $this->model->obtenerBodegas($uuid);
            echo json_encode($response);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['status' => 500, 'message' => 'Error interno', 'error' => $e->getMessage()]);
        }
    }

    /**
     * @OA\Get(
     *     path="/swagger/obtener_producto_tienda",
     *     tags={"Productos"},
     *     summary="Obtener producto por plataforma",
     *     description="Obtiene los detalles de un producto específico en productos_tienda y su relación con las tablas productos e inventario_bodegas, donde coincidan id_producto y id_inventario, filtrado por id_producto_tienda.",
     *     @OA\Parameter(
     *         name="uuid",
     *         in="query",
     *         description="UUID del usuario o plataforma",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="id_producto",
     *         in="query",
     *         description="ID del producto",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Bodegas obtenidas exitosamente"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Faltan datos requeridos"
     *     ),
     * )
     */
    public function obtener_producto_tienda()
    {
        try {
            $this->logRequest('swagger/obtener_producto_tienda', $_SERVER['REQUEST_METHOD'], file_get_contents('php://input'));
            // Obtener los parámetros desde la URL
            $uuid = $_GET['uuid'] ?? null;
            $id_producto = $_GET['id_producto'] ?? null;


            // Validar que ambos parámetros estén presentes
            if (!$uuid || !$id_producto) {
                http_response_code(400);
                echo json_encode(['status' => 400, 'message' => 'UUID e ID del producto son requeridos']);
                return;
            }

            // Llamar al modelo para obtener los productos 
            $response = $this->model->obtenerProductoTienda($uuid, $id_producto);
            echo json_encode($response);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['status' => 500, 'message' => 'Error interno', 'error' => $e->getMessage()]);
        }
    }

    /**
     * @OA\Post(
     *     path="/swagger/agregar_bodega",
     *     tags={"Productos"},
     *     summary="Agregar una nueva bodega",
     *     description="Permite agregar una nueva bodega asociada a una plataforma específica.",
     *     @OA\Parameter(
     *         name="uuid",
     *         in="query",
     *         description="UUID del usuario o plataforma",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="nombre", type="string", description="Nombre de la bodega"),
     *                 @OA\Property(property="direccion_completa", type="string", description="Dirección completa de la bodega"),
     *                 @OA\Property(property="telefono", type="string", description="Teléfono de contacto de la bodega"),
     *                 @OA\Property(property="ciudad_entrega", type="string", description="Ciudad de entrega"),
     *                 @OA\Property(property="provincia", type="string", description="Provincia de la bodega"),
     *                 @OA\Property(property="nombre_contacto", type="string", description="Nombre del contacto de la bodega"),
     *                 @OA\Property(property="numero_casa", type="string", description="Número de casa o edificio"),
     *                 @OA\Property(property="referencia", type="string", description="Referencia de la dirección"),
     *                 @OA\Property(property="longitud", type="integer", description="Longitud geográfica"),
     *                 @OA\Property(property="latitud", type="integer", description="Latitud geográfica")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Bodega agregada con éxito"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Faltan datos requeridos"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno al agregar la bodega"
     *     )
     * )
     */
    public function agregar_bodega()
    {
        try {
            $this->logRequest('swagger/agregar_bodega', $_SERVER['REQUEST_METHOD'], file_get_contents('php://input'));
            $data = json_decode(file_get_contents("php://input"), true);

            if (!$data) {
                http_response_code(400);
                echo json_encode(['status' => 400, 'message' => 'Datos inválidos']);
                return;
            }

            // Capturar UUID desde query parameters
            $uuid = $_GET['uuid'] ?? null;

            // Asignar datos del cuerpo
            $nombre = $data['nombre'] ?? null;
            $direccion_completa = $data['direccion_completa'] ?? null;
            $telefono = $data['telefono'] ?? null;
            $ciudad_entrega = $data['ciudad_entrega'] ?? null;
            $provincia = $data['provincia'] ?? null;
            $nombre_contacto = $data['nombre_contacto'] ?? null;
            $numero_casa = $data['numero_casa'] ?? null;
            $referencia = $data['referencia'] ?? null;
            $longitud = $data['longitud'] ?? null;
            $latitud = $data['latitud'] ?? null;

            // Validación de campos requeridos
            if (!$uuid || !$nombre || !$direccion_completa || !$telefono || !$ciudad_entrega || !$provincia || !$nombre_contacto) {
                http_response_code(400);
                echo json_encode(['status' => 400, 'message' => 'Faltan datos requeridos']);
                return;
            }

            // Llamar al modelo
            $response = $this->model->agregarBodega($uuid, $nombre, $direccion_completa, $telefono, $ciudad_entrega, $provincia, $nombre_contacto, $numero_casa, $referencia, $longitud, $latitud);
            $this->handleResponse($response);
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }

    /**
     * @OA\POST(
     *     path="/swagger/editar_bodega",
     *     tags={"Productos"},
     *     summary="Editar una bodega existente",
     *     description="Permite editar una bodega existente segun el id proporcionado.",
     *     @OA\Parameter(
     *         name="uuid",
     *         in="query",
     *         description="UUID del usuario o plataforma",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="id_bodega", type="integer", description="ID de la bodega"),
     *                 @OA\Property(property="nombre", type="string", description="Nombre de la bodega"),
     *                 @OA\Property(property="direccion_completa", type="string", description="Dirección completa de la bodega"),
     *                 @OA\Property(property="telefono", type="string", description="Teléfono de contacto de la bodega"),
     *                 @OA\Property(property="ciudad_entrega", type="string", description="Ciudad de entrega"),
     *                 @OA\Property(property="provincia", type="string", description="Provincia de la bodega"),
     *                 @OA\Property(property="nombre_contacto", type="string", description="Nombre del contacto de la bodega"),
     *                 @OA\Property(property="numero_casa", type="string", description="Número de casa o edificio"),
     *                 @OA\Property(property="referencia", type="string", description="Referencia de la dirección"),
     *                 @OA\Property(property="longitud", type="integer", description="Longitud geográfica"),
     *                 @OA\Property(property="latitud", type="integer", description="Latitud geográfica")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Bodega editada con éxito"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Faltan datos requeridos"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno al editar la bodega"
     *     )
     * )
     */
    public function editar_bodega()
    {
        try {
            $this->logRequest('swagger/editar_bodega', $_SERVER['REQUEST_METHOD'], file_get_contents('php://input'));
            $data = json_decode(file_get_contents("php://input"), true);

            if (!$data) {
                http_response_code(400);
                echo json_encode(['status' => 400, 'message' => 'Datos inválidos']);
                return;
            }

            // Capturar UUID desde query parameters
            $uuid = $_GET['uuid'] ?? null;

            // Asignar datos del cuerpo
            $id_bodega = $data['id_bodega'] ?? null;
            $nombre = $data['nombre'] ?? null;
            $direccion_completa = $data['direccion_completa'] ?? null;
            $telefono = $data['telefono'] ?? null;
            $ciudad_entrega = $data['ciudad_entrega'] ?? null;
            $provincia = $data['provincia'] ?? null;
            $nombre_contacto = $data['nombre_contacto'] ?? null;
            $numero_casa = $data['numero_casa'] ?? null;
            $referencia = $data['referencia'] ?? null;
            $longitud = $data['longitud'] ?? null;
            $latitud = $data['latitud'] ?? null;

            // Validación de campos requeridos
            if (!$uuid || !$id_bodega || !$nombre || !$direccion_completa || !$telefono || !$ciudad_entrega || !$provincia || !$nombre_contacto) {
                http_response_code(400);
                echo json_encode(['status' => 400, 'message' => 'Faltan datos requeridos']);
                return;
            }

            // Llamar al modelo
            $response = $this->model->editarBodega($uuid, $id_bodega, $nombre, $direccion_completa, $telefono, $ciudad_entrega, $provincia, $nombre_contacto, $numero_casa, $referencia, $longitud, $latitud);
            $this->handleResponse($response);
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }

    /**
     * @OA\Get(
     *     path="/swagger/listar_bodegas",
     *     tags={"Productos"},
     *     summary="Listar las bodegas asociadas a la plataforma de un usuario",
     *     description="Devuelve la lista de bodegas asociadas a la plataforma del usuario, validado por su UUID.",
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
     *         description="Listado de bodegas obtenido exitosamente"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Faltan datos requeridos"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No existe un usuario con el UUID proporcionado"
     *     )
     * )
     */
    public function listar_bodegas()
    {
        try {
            // Registrar la solicitud en logs (si utilizas ese método).
            $this->logRequest('swagger/listar_bodegas', $_SERVER['REQUEST_METHOD'], file_get_contents('php://input'));

            // Obtener parámetros
            $uuid = $_GET['uuid'] ?? null;

            // Validar parámetros requeridos
            if (!$uuid) {
                http_response_code(400);
                echo json_encode(['status' => 400, 'message' => 'Faltan campos requeridos: uuid']);
                return;
            }

            // Llamar a tu modelo (Swagger Model) que internamente validará usuario y llamará a productosModel
            $response = $this->model->listarBodegas($uuid);

            // Responder
            echo json_encode($response);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'status'  => 500,
                'message' => 'Error interno',
                'error'   => $e->getMessage()
            ]);
        }
    }

    /**
     * @OA\Get(
     *     path="/swagger/cargar_bodegas",
     *     tags={"Productos"},
     *     summary="Listar las bodegas asociadas a la plataforma de un usuario",
     *     description="Devuelve las bodegas vinculadas a una plataforma. Dependiendo de la configuración de la plataforma, puede incluir también bodegas globales asociadas a la matriz correspondiente.",
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
     *         description="Listado de bodegas obtenido exitosamente"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Faltan datos requeridos"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No existe un usuario con el UUID proporcionado"
     *     )
     * )
     */
    public function cargar_bodegas()
    {
        try {
            // Registrar la solicitud en logs (si utilizas ese método).
            $this->logRequest('swagger/cargar_bodegas', $_SERVER['REQUEST_METHOD'], file_get_contents('php://input'));

            // Obtener parámetros
            $uuid = $_GET['uuid'] ?? null;

            // Validar parámetros requeridos
            if (!$uuid) {
                http_response_code(400);
                echo json_encode(['status' => 400, 'message' => 'Faltan campos requeridos: uuid']);
                return;
            }

            // Llamar a tu modelo (Swagger Model) que internamente validará usuario y llamará a productosModel
            $response = $this->model->cargarBodegas($uuid);

            // Responder
            echo json_encode($response);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'status'  => 500,
                'message' => 'Error interno',
                'error'   => $e->getMessage()
            ]);
        }
    }

    /**
     * @OA\Post(
     *     path="/swagger/agregar_categoria",
     *     tags={"Productos"},
     *     summary="Agregar una nueva categoría",
     *     description="Permite agregar una nueva categoría asociada a la plataforma.",
     *     @OA\Parameter(
     *         name="uuid",
     *         in="query",
     *         description="UUID del usuario o plataforma",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="nombre_linea", type="string", description="Nombre de la categoría"),
     *                 @OA\Property(property="descripcion_linea", type="string", description="Descripción de la categoría"),
     *                 @OA\Property(property="online", type="integer", description="Estado: 1=online, 0=offline"),
     *                 @OA\Property(property="imagen", type="string", description="URL o nombre de la imagen"),
     *                 @OA\Property(property="tipo", type="string", description="Tipo de la categoría"),
     *                 @OA\Property(property="padre", type="integer", description="ID de la categoría padre (0 si no existe)"),
     *                 @OA\Property(property="orden", type="integer", description="Orden de visualización")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Categoría agregada con éxito"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Faltan datos requeridos"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno al agregar la categoría"
     *     )
     * )
     */
    public function agregar_categoria()
    {
        try {
            $this->logRequest('swagger/agregar_bodega', $_SERVER['REQUEST_METHOD'], file_get_contents('php://input'));
            $data = json_decode(file_get_contents("php://input"), true);

            // Validamos que vengan datos en el cuerpo
            if (!$data) {
                http_response_code(400);
                echo json_encode(['status' => 400, 'message' => 'Datos inválidos']);
                return;
            }
            // Capturar UUID desde query parameters
            $uuid = $_GET['uuid'] ?? null;

            // Extraer campos del JSON
            $nombre_linea       = $data['nombre_linea']       ?? null;
            $descripcion_linea  = $data['descripcion_linea']  ?? null;
            $online             = $data['online']             ?? null;
            $imagen             = $data['imagen']             ?? '';
            $tipo               = $data['tipo']               ?? '';
            $padre              = $data['padre']              ?? 0;
            $orden              = $data['orden']              ?? 0;

            // Campos fijos o por defecto
            $estado_linea = 1;
            $date_added   = date("Y-m-d H:i:s");

            // Validar campos requeridos mínimos (adaptar a tu necesidad)
            if (!$uuid || !$nombre_linea || !$descripcion_linea || !$online || !$imagen || !$tipo || !$padre || !$orden) {
                http_response_code(400);
                echo json_encode(['status' => 400, 'message' => 'Faltan datos requeridos']);
                return;
            }

            $response = $this->model->agregarCategoria(
                $uuid,
                $nombre_linea,
                $descripcion_linea,
                $estado_linea,
                $date_added,
                $online,
                $imagen,
                $tipo,
                $padre,
                $orden
            );
            $this->handleResponse($response);
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }
    /**
     * @OA\Post(
     *     path="/swagger/editar_categoria",
     *     tags={"Productos"},
     *     summary="Editar una categoría existente",
     *     description="Permite editar una categoría existente asociada a la plataforma.",
     *     @OA\Parameter(
     *         name="uuid",
     *         in="query",
     *         description="UUID del usuario o plataforma",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="id_categoria", type="integer", description="ID de la categoría"),
     *                 @OA\Property(property="nombre_linea", type="string", description="Nombre de la categoría"),
     *                 @OA\Property(property="descripcion_linea", type="string", description="Descripción de la categoría"),
     *                 @OA\Property(property="online", type="integer", description="Estado: 1=online, 0=offline"),
     *                 @OA\Property(property="imagen", type="string", description="URL o nombre de la imagen"),
     *                 @OA\Property(property="tipo", type="string", description="Tipo de la categoría"),
     *                 @OA\Property(property="padre", type="integer", description="ID de la categoría padre (0 si no existe)"),
     *                 @OA\Property(property="orden", type="integer", description="Orden de visualización")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Categoría editada con éxito"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Faltan datos requeridos"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno al editar la categoría"
     *     )
     * )
     */
    public function editar_categoria()
    {
        try {
            $this->logRequest('swagger/agregar_bodega', $_SERVER['REQUEST_METHOD'], file_get_contents('php://input'));
            $data = json_decode(file_get_contents("php://input"), true);

            // Validamos que vengan datos en el cuerpo
            if (!$data) {
                http_response_code(400);
                echo json_encode(['status' => 400, 'message' => 'Datos inválidos']);
                return;
            }
            // Capturar UUID desde query parameters
            $uuid = $_GET['uuid'] ?? null;

            // Extraer campos del JSON
            $id_categoria       = $data['id_categoria']       ?? null;
            $nombre_linea       = $data['nombre_linea']       ?? null;
            $descripcion_linea  = $data['descripcion_linea']  ?? null;
            $online             = $data['online']             ?? null;
            $imagen             = $data['imagen']             ?? '';
            $tipo               = $data['tipo']               ?? '';
            $padre              = $data['padre']              ?? 0;
            $orden              = $data['orden']              ?? 0;

            // Campos fijos o por defecto
            $estado_linea = 1;
            $date_added   = date("Y-m-d H:i:s");

            // Validar campos requeridos mínimos (adaptar a tu necesidad)
            if (!$uuid || !$id_categoria || !$nombre_linea || !$descripcion_linea || !$online || !$imagen || !$tipo || !$padre || !$orden) {
                http_response_code(400);
                echo json_encode(['status' => 400, 'message' => 'Faltan datos requeridos']);
                return;
            }

            $response = $this->model->editarCategoria(
                $uuid,
                $id_categoria,
                $nombre_linea,
                $descripcion_linea,
                $estado_linea,
                $date_added,
                $online,
                $imagen,
                $tipo,
                $padre,
                $orden
            );
            $this->handleResponse($response);
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }

    /**
     * @OA\Post(
     *     path="/swagger/listar_categoria",
     *     tags={"Productos"},
     *     summary="Listar las lineas/categorias asociadas a la plataforma de un usuario",
     *     description="Devuelve la lista de lineas/categoria asociadas a la plataforma del usuario, validado por su UUID.",
     *     @OA\Parameter(
     *         name="uuid",
     *         in="query",
     *         description="UUID del usuario o plataforma",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="id_categoria",
     *                     type="integer"
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Listado de lineas/categoria obtenido exitosamente"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Faltan datos requeridos"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No existe un usuario con el UUID proporcionado"
     *     )
     * )
     */
    public function listar_categoria()
    {
        try {
            // Registrar la solicitud en logs (si utilizas ese método).
            $this->logRequest('swagger/listar_categoria', $_SERVER['REQUEST_METHOD'], file_get_contents('php://input'));
            $data = json_decode(file_get_contents("php://input"), true);

            // Obtener parámetros
            $uuid = $_GET['uuid'] ?? null;
            $id_categoria = $data['id_categoria'] ?? null;

            // Validar parámetros requeridos
            if (!$uuid || !$id_categoria) {
                http_response_code(400);
                echo json_encode(['status' => 400, 'message' => 'Faltan campos requeridos: uuid']);
                return;
            }

            // Llamar a tu modelo (Swagger Model) que internamente validará usuario y llamará a productosModel
            $response = $this->model->listarCategoria($id_categoria, $uuid);

            // Responder
            echo json_encode($response);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'status'  => 500,
                'message' => 'Error interno',
                'error'   => $e->getMessage()
            ]);
        }
    }


    /**
     * @OA\Post(
     *     path="/swagger/guardar_imagen_categorias",
     *     tags={"Productos"},
     *     summary="Subir imagen para la categoría",
     *     description="Permite subir y asociar una imagen a una categoría existente.",
     *     @OA\Parameter(
     *         name="uuid",
     *         in="query",
     *         description="UUID del usuario o plataforma.",
     *         required=false,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="imagen",
     *                     type="string",
     *                     format="binary",
     *                     description="Archivo de imagen a subir."
     *                 ),
     *                 @OA\Property(
     *                     property="id_linea",
     *                     type="integer",
     *                     description="ID de la categoría (linea) a la que se subirá la imagen."
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Imagen guardada con éxito"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Faltan datos requeridos"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno al agregar la imagen"
     *     )
     * )
     */
    public function guardar_imagen_categorias()
    {
        try {
            // Si aún quieres loguear, revisa $_FILES
            $this->logRequest('swagger/guardar_imagen_categorias', $_SERVER['REQUEST_METHOD'], $_FILES['imagen'] ?? null);

            // Capturar uuid desde query (si lo utilizas)
            $uuid = $_GET['uuid'] ?? null;

            // En multipart/form-data, la imagen llega por $_FILES, y los campos texto por $_POST
            $imagen  = $_FILES['imagen'] ?? null;
            $id_linea = $_POST['id_linea'] ?? null;

            // Valida si llegaron
            if (!$uuid || !$imagen || !$id_linea) {
                http_response_code(400);
                echo json_encode(['status' => 400, 'message' => 'Faltan datos requeridos']);
                return;
            }

            // Llamas al método del modelo
            $response = $this->model->guardarImagenCategorias(
                $uuid,
                $imagen,
                $id_linea
            );

            $this->handleResponse($response);
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }

    /**
     * @OA\Post(
     *     path="/swagger/guardar_imagen_productos",
     *     tags={"Productos"},
     *     summary="Subir imagen para el producto",
     *     description="Permite subir y asociar una imagen a un producto existente.",
     *     @OA\Parameter(
     *         name="uuid",
     *         in="query",
     *         description="UUID del usuario o plataforma.",
     *         required=false,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="imagen",
     *                     type="string",
     *                     format="binary",
     *                     description="Archivo de imagen a subir."
     *                 ),
     *                 @OA\Property(
     *                     property="id_producto",
     *                     type="integer",
     *                     description="ID del producto en el que se subirá la imagen."
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Imagen guardada con éxito"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Faltan datos requeridos"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno al agregar la imagen"
     *     )
     * )
     */
    public function guardar_imagen_productos()
    {
        try {
            // Si aún quieres loguear, revisa $_FILES
            $this->logRequest('swagger/guardar_imagen_productos', $_SERVER['REQUEST_METHOD'], $_FILES['imagen'] ?? null);

            // Capturar uuid desde query (si lo utilizas)
            $uuid = $_GET['uuid'] ?? null;

            // En multipart/form-data, la imagen llega por $_FILES, y los campos texto por $_POST
            $imagen  = $_FILES['imagen'] ?? null;
            $id_producto = $_POST['id_producto'] ?? null;

            // Valida si llegaron
            if (!$uuid || !$imagen || !$id_producto) {
                http_response_code(400);
                echo json_encode(['status' => 400, 'message' => 'Faltan datos requeridos']);
                return;
            }

            // Llamas al método del modelo
            $response = $this->model->guardarImagenProductos(
                $uuid,
                $imagen,
                $id_producto
            );

            $this->handleResponse($response);
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }
    /**
     * @OA\Post(
     *     path="/swagger/guardar_imagen_adicional_productos",
     *     tags={"Productos"},
     *     summary="Subir imagen adicional para el producto",
     *     description="Permite subir y asociar imagenes adicionales a un producto existente.",
     *     @OA\Parameter(
     *         name="uuid",
     *         in="query",
     *         description="UUID del usuario o plataforma.",
     *         required=false,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="imagen",
     *                     type="string",
     *                     format="binary",
     *                     description="Archivo de imagen a subir."
     *                 ),
     *                 @OA\Property(
     *                     property="num_imagen",
     *                     type="integer",
     *                     description="Numero de imagen a subir."
     *                 ),
     *                 @OA\Property(
     *                     property="id_producto",
     *                     type="integer",
     *                     description="ID del producto en el que se subirá la imagen."
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Imagen guardada con éxito"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Faltan datos requeridos"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno al agregar la imagen"
     *     )
     * )
     */
    public function guardar_imagen_adicional_productos()
    {
        try {
            // Si aún quieres loguear, revisa $_FILES
            $this->logRequest('swagger/guardar_imagen_adicional_productos', $_SERVER['REQUEST_METHOD'], $_FILES['imagen'] ?? null);

            // Capturar uuid desde query (si lo utilizas)
            $uuid = $_GET['uuid'] ?? null;

            // En multipart/form-data, la imagen llega por $_FILES, y los campos texto por $_POST
            $num_imagen  = $_POST['num_imagen'] ?? null;
            $imagen  = $_FILES['imagen'] ?? null;
            $id_producto = $_POST['id_producto'] ?? null;

            // Valida si llegaron
            if (!$uuid || !$num_imagen || !$imagen || !$id_producto) {
                http_response_code(400);
                echo json_encode(['status' => 400, 'message' => 'Faltan datos requeridos']);
                return;
            }

            // Llamas al método del modelo
            $response = $this->model->guardarImagenAdicionalProductos(
                $uuid,
                $num_imagen,
                $imagen,
                $id_producto
            );

            $this->handleResponse($response);
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }

    /**
     * @OA\Post(
     *     path="/swagger/guardar_imagen_productos_tienda",
     *     tags={"Productos"},
     *     summary="Subir imagen para productos tienda",
     *     description="Permite subir y asociar imagenes a un producto de la tabla productos_tienda.",
     *     @OA\Parameter(
     *         name="uuid",
     *         in="query",
     *         description="UUID del usuario o plataforma.",
     *         required=false,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="imagen",
     *                     type="string",
     *                     format="binary",
     *                     description="Archivo de imagen a subir."
     *                 ),
     *                 @OA\Property(
     *                     property="id_producto",
     *                     type="integer",
     *                     description="ID del producto en el que se subirá la imagen."
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Imagen guardada con éxito"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Faltan datos requeridos"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno al agregar la imagen"
     *     )
     * )
     */
    public function guardar_imagen_productos_tienda()
    {
        try {
            // Si aún quieres loguear, revisa $_FILES
            $this->logRequest('swagger/guardar_imagen_productos_tienda', $_SERVER['REQUEST_METHOD'], $_FILES['imagen'] ?? null);

            // Capturar uuid desde query (si lo utilizas)
            $uuid = $_GET['uuid'] ?? null;

            // En multipart/form-data, la imagen llega por $_FILES, y los campos texto por $_POST
            $imagen  = $_FILES['imagen'] ?? null;
            $id_producto = $_POST['id_producto'] ?? null;

            // Valida si llegaron
            if (!$uuid || !$imagen || !$id_producto) {
                http_response_code(400);
                echo json_encode(['status' => 400, 'message' => 'Faltan datos requeridos']);
                return;
            }

            // Llamas al método del modelo
            $response = $this->model->guardarImagenProductosTienda(
                $uuid,
                $imagen,
                $id_producto
            );

            $this->handleResponse($response);
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }
    /**
     * @OA\Post(
     *     path="/swagger/guardar_imagen_adicional_productos_tienda",
     *     tags={"Productos"},
     *     summary="Subir imagen adicional para productos tienda",
     *     description="Permite subir y asociar imagenes adicionales a un producto de la tabla imagens_adicionales_productoTienda.",
     *     @OA\Parameter(
     *         name="uuid",
     *         in="query",
     *         description="UUID del usuario o plataforma.",
     *         required=false,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="imagen",
     *                     type="string",
     *                     format="binary",
     *                     description="Archivo de imagen a subir."
     *                 ),
     *                 @OA\Property(
     *                     property="num_imagen",
     *                     type="integer",
     *                     description="Número de imagen a subir."
     *                 ),
     *                 @OA\Property(
     *                     property="id_producto",
     *                     type="integer",
     *                     description="ID del producto en el que se subirá la imagen."
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Imagen guardada con éxito"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Faltan datos requeridos"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno al agregar la imagen"
     *     )
     * )
     */
    public function guardar_imagen_adicional_productos_tienda()
    {
        try {
            // Si aún quieres loguear, revisa $_FILES
            $this->logRequest('swagger/guardar_imagen_adicional_productos_tienda', $_SERVER['REQUEST_METHOD'], $_FILES['imagen'] ?? null);

            // Capturar uuid desde query (si lo utilizas)
            $uuid = $_GET['uuid'] ?? null;

            // En multipart/form-data, la imagen llega por $_FILES, y los campos texto por $_POST
            $imagen  = $_FILES['imagen'] ?? null;
            $id_producto = $_POST['id_producto'] ?? null;
            $num_imagen = $_POST['num_imagen'] ?? null;

            // Valida si llegaron
            if (!$uuid || !$imagen || !$num_imagen) {
                http_response_code(400);
                echo json_encode(['status' => 400, 'message' => 'Faltan datos requeridos']);
                return;
            }

            // Llamas al método del modelo
            $response = $this->model->guardarImagenAdicionalProductosTienda(
                $uuid,
                $imagen,
                $num_imagen,
                $id_producto
            );

            $this->handleResponse($response);
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }

    /**
     * @OA\Post(
     *     path="/swagger/listar_imagen_adicional_productos",
     *     tags={"Productos"},
     *     summary="Listar imagenes adicionales de productos",
     *     description="Devuelve la lista de imagenes adicionales asociadas a la plataforma del usuario e id del producto, validado por su UUID.",
     *     @OA\Parameter(
     *         name="uuid",
     *         in="query",
     *         description="UUID del usuario o plataforma",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *          @OA\RequestBody(
     *          required=true,
     *              @OA\MediaType(
     *              mediaType="application/json",
     *                  @OA\Schema(
     *                      @OA\Property(
     *                      property="id_producto",
     *                      type="integer"
     *                  ),
     *              )
     *        )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Listado de imagenes adicionales obtenidas exitosamente"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Faltan datos requeridos"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No existe un usuario con el UUID proporcionado"
     *     )
     * )
     */
    public function listar_imagen_adicional_productos()
    {
        try {
            // Registrar la solicitud en logs (si utilizas ese método).
            $this->logRequest('swagger/listar_imagen_adicional_productos', $_SERVER['REQUEST_METHOD'], file_get_contents('php://input'));
            $data = json_decode(file_get_contents("php://input"), true);

            // Obtener parámetros
            $uuid = $_GET['uuid'] ?? null;
            $id_producto = $data['id_producto'] ?? null;

            // Validar parámetros requeridos
            if (!$uuid || !$id_producto) {
                http_response_code(400);
                echo json_encode(['status' => 400, 'message' => 'Faltan campos requeridos']);
                return;
            }

            // Llamar a tu modelo (Swagger Model) que internamente validará usuario y llamará a productosModel
            $response = $this->model->listarImagenAdicionalProductos($uuid, $id_producto);

            // Responder
            echo json_encode($response);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'status'  => 500,
                'message' => 'Error interno',
                'error'   => $e->getMessage()
            ]);
        }
    }
    /**
     * @OA\Post(
     *     path="/swagger/listar_imagen_adicional_productos_tienda",
     *     tags={"Productos"},
     *     summary="Listar imagenes adicionales de productos tienda",
     *     description="Devuelve la lista de imagenes adicionales asociadas a la plataforma del usuario e id del producto, validado por su UUID.",
     *     @OA\Parameter(
     *         name="uuid",
     *         in="query",
     *         description="UUID del usuario o plataforma",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *          @OA\RequestBody(
     *          required=true,
     *              @OA\MediaType(
     *              mediaType="application/json",
     *                  @OA\Schema(
     *                      @OA\Property(
     *                      property="id_producto",
     *                      type="integer"
     *                  ),
     *              )
     *        )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Listado de imagenes adicionales obtenidas exitosamente"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Faltan datos requeridos"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No existe un usuario con el UUID proporcionado"
     *     )
     * )
     */
    public function listar_imagen_adicional_productos_tienda()
    {
        try {
            // Registrar la solicitud en logs (si utilizas ese método).
            $this->logRequest('swagger/listar_imagen_adicional_productos_tienda', $_SERVER['REQUEST_METHOD'], file_get_contents('php://input'));
            $data = json_decode(file_get_contents("php://input"), true);

            // Obtener parámetros
            $uuid = $_GET['uuid'] ?? null;
            $id_producto = $data['id_producto'] ?? null;

            // Validar parámetros requeridos
            if (!$uuid || !$id_producto) {
                http_response_code(400);
                echo json_encode(['status' => 400, 'message' => 'Faltan campos requeridos']);
                return;
            }

            // Llamar a tu modelo (Swagger Model) que internamente validará usuario y llamará a productosModel
            $response = $this->model->listarImagenAdicionalProductosTienda($uuid, $id_producto);

            // Responder
            echo json_encode($response);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'status'  => 500,
                'message' => 'Error interno',
                'error'   => $e->getMessage()
            ]);
        }
    }

    /**
     * @OA\Post(
     *     path="/swagger/obtener_productos_bodegas",
     *     tags={"Productos"},
     *     summary="Listar el inventario de bodega",
     *     description="Devuelve la lista del inventario de bodega asocada a la plataforma del usuario, validado por su UUID.",
     *     @OA\Parameter(
     *         name="uuid",
     *         in="query",
     *         description="UUID del usuario o plataforma",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *          @OA\RequestBody(
     *          required=true,
     *              @OA\MediaType(
     *              mediaType="application/json",
     *                  @OA\Schema(
     *                      @OA\Property(
     *                      property="id_bodega",
     *                      type="integer"
     *                  ),
     *              )
     *        )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Listado de inventario de bodega obtenido exitosamente"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Faltan datos requeridos"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No existe un usuario con el UUID proporcionado"
     *     )
     * )
     */
    public function obtener_productos_bodegas()
    {
        try {
            // Registrar la solicitud en logs (si utilizas ese método).
            $this->logRequest('swagger/obtener_productos_bodegas', $_SERVER['REQUEST_METHOD'], file_get_contents('php://input'));
            $data = json_decode(file_get_contents("php://input"), true);

            // Obtener parámetros
            $uuid = $_GET['uuid'] ?? null;
            $id_bodega = $data['id_bodega'] ?? null;

            // Validar parámetros requeridos
            if (!$uuid || !$id_bodega) {
                http_response_code(400);
                echo json_encode(['status' => 400, 'message' => 'Faltan campos requeridos']);
                return;
            }

            // Llamar a tu modelo (Swagger Model) que internamente validará usuario y llamará a productosModel
            $response = $this->model->obtenerProductosBodegas($uuid, $id_bodega);

            // Responder
            echo json_encode($response);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'status'  => 500,
                'message' => 'Error interno',
                'error'   => $e->getMessage()
            ]);
        }
    }

    /**
     * @OA\Post(
     *     path="/swagger/cargar_categorias",
     *     tags={"Productos"},
     *     summary="Listar categorias",
     *     description="Devuelve la lista de categorías/lineas de una plataforma específica o marcadas como globales.",
     *     @OA\Parameter(
     *         name="uuid",
     *         in="query",
     *         description="UUID del usuario o plataforma",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *          @OA\RequestBody(
     *          required=true,
     *              @OA\MediaType(
     *              mediaType="application/json",
     *                  @OA\Schema(
     *                      @OA\Property(
     *                      property="id_plataforma",
     *                      type="integer"
     *                  ),
     *              )
     *        )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Listado de categorias obtenidas exitosamente"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Faltan datos requeridos"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No existe un usuario con el UUID proporcionado"
     *     )
     * )
     */
    public function cargar_categorias()
    {
        try {
            // Registrar la solicitud en logs (si utilizas ese método).
            $this->logRequest('swagger/cargar_categorias', $_SERVER['REQUEST_METHOD'], file_get_contents('php://input'));
            $data = json_decode(file_get_contents("php://input"), true);

            // Obtener parámetros
            $uuid = $_GET['uuid'] ?? null;
            $id_plataforma = $data['id_plataforma'] ?? null;

            // Validar parámetros requeridos
            if (!$uuid || !$id_plataforma) {
                http_response_code(400);
                echo json_encode(['status' => 400, 'message' => 'Faltan campos requeridos']);
                return;
            }

            // Llamar a tu modelo (Swagger Model) que internamente validará usuario y llamará a productosModel
            $response = $this->model->cargarCategorias($uuid, $id_plataforma);

            // Responder
            echo json_encode($response);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'status'  => 500,
                'message' => 'Error interno',
                'error'   => $e->getMessage()
            ]);
        }
    }

    /**
     * @OA\Post(
     *     path="/swagger/cargar_templates",
     *     tags={"Productos"},
     *     summary="Listar templates",
     *     description="Devuelve la lista de templates del chat center segun su plataforma.",
     *     @OA\Parameter(
     *         name="uuid",
     *         in="query",
     *         description="UUID del usuario o plataforma",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *          @OA\RequestBody(
     *          required=true,
     *              @OA\MediaType(
     *              mediaType="application/json",
     *                  @OA\Schema(
     *                      @OA\Property(
     *                      property="id_plataforma",
     *                      type="integer"
     *                  ),
     *              )
     *        )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Listado de templates obtenido exitosamente"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Faltan datos requeridos"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No existe un usuario con el UUID proporcionado"
     *     )
     * )
     */
    public function cargar_templates()
    {
        try {
            // Registrar la solicitud en logs (si utilizas ese método).
            $this->logRequest('swagger/cargar_templates', $_SERVER['REQUEST_METHOD'], file_get_contents('php://input'));
            $data = json_decode(file_get_contents("php://input"), true);

            // Obtener parámetros
            $uuid = $_GET['uuid'] ?? null;
            $id_plataforma = $data['id_plataforma'] ?? null;

            // Validar parámetros requeridos
            if (!$uuid || !$id_plataforma) {
                http_response_code(400);
                echo json_encode(['status' => 400, 'message' => 'Faltan campos requeridos']);
                return;
            }

            // Llamar a tu modelo (Swagger Model) que internamente validará usuario y llamará a productosModel
            $response = $this->model->cargarTemplates($uuid, $id_plataforma);

            // Responder
            echo json_encode($response);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'status'  => 500,
                'message' => 'Error interno',
                'error'   => $e->getMessage()
            ]);
        }
    }

    /**
     * @OA\Post(
     *     path="/swagger/cargar_etiquetas",
     *     tags={"Productos"},
     *     summary="Listar etiquetas",
     *     description="Devuelve la lista de etiquetas del chat center segun su plataforma.",
     *     @OA\Parameter(
     *         name="uuid",
     *         in="query",
     *         description="UUID del usuario o plataforma",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *          @OA\RequestBody(
     *          required=true,
     *              @OA\MediaType(
     *              mediaType="application/json",
     *                  @OA\Schema(
     *                      @OA\Property(
     *                      property="id_plataforma",
     *                      type="integer"
     *                  ),
     *              )
     *        )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Listado de etiquetas obtenido exitosamente"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Faltan datos requeridos"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No existe un usuario con el UUID proporcionado"
     *     )
     * )
     */
    public function cargar_etiquetas()
    {
        try {
            // Registrar la solicitud en logs (si utilizas ese método).
            $this->logRequest('swagger/cargar_templates', $_SERVER['REQUEST_METHOD'], file_get_contents('php://input'));
            $data = json_decode(file_get_contents("php://input"), true);

            // Obtener parámetros
            $uuid = $_GET['uuid'] ?? null;
            $id_plataforma = $data['id_plataforma'] ?? null;

            // Validar parámetros requeridos
            if (!$uuid || !$id_plataforma) {
                http_response_code(400);
                echo json_encode(['status' => 400, 'message' => 'Faltan campos requeridos']);
                return;
            }

            // Llamar a tu modelo (Swagger Model) que internamente validará usuario y llamará a productosModel
            $response = $this->model->cargarEtiquetas($uuid, $id_plataforma);

            // Responder
            echo json_encode($response);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'status'  => 500,
                'message' => 'Error interno',
                'error'   => $e->getMessage()
            ]);
        }
    }


































    /**
     * @OA\Post(
     *     path="/swagger/agregar_boveda",
     *     tags={"Productos"},
     *     summary="Agregar boveda",
     *     description="Permite agregar bovedas para la visualización de los usuarios estudiantes.",
     *     @OA\Parameter(
     *        name="uuid",
     *        in="query",
     *        description="UUID del usuario o plataforma",
     *        required=true,
     *        @OA\Schema(
     *            type="string"
     *        )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="idProducto",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="idLinea",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="imagen",
     *                     type="string",
     *                     format= "binary",
     *                     description = "Archivo de imagen a subir." 
     *                 ),
     *                 @OA\Property(
     *                     property="plantillaVentas",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="idProveedor",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="ejemploLanding",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="duplicarFunnel",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="videos",
     *                     type="string"
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Boveda creada con exito"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error en la creación de la boveda"
     *     )
     * )
     */

    public function agregar_boveda()
    {
        try {
            $this->logRequest('swagger/agregar_boveda', $_SERVER['REQUEST_METHOD'], $_FILES['imagen'] ?? null);



            $uuid = $_GET['uuid'] ?? null; // Capturar UUID desde query parameters

            $idProducto = $_POST['idProducto'] ?? null;
            $idLinea = $_POST['idLinea'] ?? null;
            $imagen = $_FILES['imagen'] ?? null;
            $plantillaVentas = $_POST['plantillaVentas'] ?? null;
            $idProveedor = $_POST['idProveedor'] ?? null;
            $ejemploLanding = $_POST['ejemploLanding'] ?? null;
            $duplicarFunnel = $_POST['duplicarFunnel'] ?? null;
            $videos = $_POST['videos'] ?? null;

            // Validación de campos requeridos
            if (!$uuid || !$imagen || !$plantillaVentas || !$idProducto || !$idLinea || !$idProveedor || !$ejemploLanding || !$duplicarFunnel || !$videos) {
                http_response_code(400);
                echo json_encode(['status' => 400, 'message' => 'Faltan datos requeridos']);
                return;
            }

            // Llamar al modelo
            $response = $this->model->agregarBoveda($uuid, $idProducto, $idLinea, $imagen, $plantillaVentas, $idProveedor, $ejemploLanding, $duplicarFunnel, $videos);
            $this->handleResponse($response);
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }

    /**
     * @OA\Post(
     *     path="/swagger/editar_boveda",
     *     tags={"Productos"},
     *     summary="Editar boveda",
     *     description="Permite editar las bovedas para la visualización de los usuarios estudiantes.",
     *     @OA\Parameter(
     *        name="uuid",
     *        in="query",
     *        description="UUID del usuario o plataforma",
     *        required=true,
     *        @OA\Schema(
     *            type="string"
     *        )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="id_produto",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="id_boveda",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="id_linea",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="imagen",
     *                     type="string",
     *                     format = "binary",
     *                     description = "Arhivo de imagen subir"
     *                 ),
     *                 @OA\Property(
     *                     property="plantillaVentas",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="id_plataforma",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="ejemploLanding",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="duplicarFunnel",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="videos",
     *                     type="string"
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Boveda actualizada con exito"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error en la actualización de la boveda"
     *     )
     * )
     */

    public function editar_boveda()
    {
        try {
            $this->logRequest('swagger/editar__boveda', $_SERVER['REQUEST_METHOD'], $_FILES['imagen'] ?? null);

            $uuid = $_GET['uuid'] ?? null; // Capturar UUID desde query parameters

            $id_produto = $_POST['id_produto'] ?? null;
            $id_boveda = $_POST['id_boveda'] ?? null;
            $id_linea = $_POST['id_linea'] ?? null;
            $imagen = $_FILES['imagen'] ?? null;
            $plantillaVentas = $_POST['plantillaVentas'] ?? null;
            $id_plataforma = $_POST['id_plataforma'] ?? null;
            $ejemploLanding = $_POST['ejemploLanding'] ?? null;
            $duplicarFunnel = $_POST['duplicarFunnel'] ?? null;
            $videos = $_POST['videos'] ?? null;

            // Validación de campos requeridos
            if (!$uuid || !$id_produto || !$id_boveda || !$id_linea || !$id_plataforma || !$plantillaVentas || !$ejemploLanding || !$duplicarFunnel || !$videos) {
                http_response_code(400);
                echo json_encode(['status' => 400, 'message' => 'Faltan datos requeridos']);
                return;
            }

            // Llamar al modelo
            $response = $this->model->editarBoveda($uuid, $id_boveda, $id_linea, $id_plataforma, $id_produto, $imagen, $plantillaVentas, $ejemploLanding, $duplicarFunnel, $videos);
            $this->handleResponse($response);
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }

    /**
     * @OA\Post(
     *     path="/swagger/agregar_producto",
     *     tags={"Productos"},
     *     summary="Agregar un nuevo producto",
     *     description="Permite agregar un nuevo producto asociado a las tablas historial_productos, inventario_bodegas, productos.",
     *     @OA\Parameter(
     *         name="uuid",
     *         in="query",
     *         description="UUID del usuario o plataforma",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="codigo_producto", type="string", description="Codigo del producto"),
     *                 @OA\Property(property="nombre_producto", type="string", description="Nombre del producto"),
     *                 @OA\Property(property="descripcion_producto", type="string", description="Descripción del producto"),
     *                 @OA\Property(property="id_linea_producto", type="integer", description="ID linea del producto"),
     *                 @OA\Property(property="inv_producto", type="integer", description="Estado inv del producto"),
     *                 @OA\Property(property="producto_variable", type="integer", description="Estado producto variable"),
     *                 @OA\Property(property="aplica_iva", type="integer", description="Estado aplica IVA el producto"),
     *                 @OA\Property(property="estado_producto", type="integer", description="Estado del producto"),
     *                 @OA\Property(property="id_imp_producto", type="integer", description="Id Imp del producto"),
     *                 @OA\Property(property="pagina_web", type="integer", description="Estado de página web del producto"),
     *                 @OA\Property(property="formato", type="integer", description="Estado de formato del producto"),
     *                 @OA\Property(property="drogshipin", type="integer", description="Estado de drogshipin del producto"),
     *                 @OA\Property(property="destacado", type="integer", description="Estado destacado del producto"),
     *                 @OA\Property(property="envio_prioritario", type="integer", description="Estado de envio priotario del producto"),
     *                 @OA\Property(property="enlace_funnelish", type="string", description="Enlace funnelish del producto"),
     *                 @OA\Property(property="stock_inicial", type="integer", description="Stock inicial del producto"),
     *                 @OA\Property(property="bodega", type="integer", description="Bodega del producto"),
     *                 @OA\Property(property="pcp", type="integer", description="Pcp del producto"),
     *                 @OA\Property(property="pvp", type="integer", description="Pvp del producto")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Producto agregado con éxito"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Faltan datos requeridos"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno al agregar el producto"
     *     )
     * )
     */
    public function agregar_producto()
    {
        try {
            $this->logRequest('swagger/agregar_producto', $_SERVER['REQUEST_METHOD'], file_get_contents('php://input'));
            $data = json_decode(file_get_contents("php://input"), true);

            // Validamos que vengan datos en el cuerpo
            if (!$data) {
                http_response_code(400);
                echo json_encode(['status' => 400, 'message' => 'Datos inválidos']);
                return;
            }
            // Capturar UUID desde query parameters
            $uuid = $_GET['uuid'] ?? null;

            // Extraer campos del JSON
            $codigo_producto      = $data['codigo_producto']      ?? null;
            $nombre_producto      = $data['nombre_producto']      ?? null;
            $descripcion_producto = $data['descripcion_producto'] ?? null;
            $id_linea_producto    = $data['id_linea_producto']    ?? null;
            $inv_producto         = $data['inv_producto']         ?? null;
            $producto_variable    = $data['producto_variable']    ?? null;
            $aplica_iva           = $data['aplica_iva']           ?? 0;
            $estado_producto      = $data['estado_producto']      ?? null;
            $id_imp_producto      = $data['id_imp_producto']      ?? 12;
            $pagina_web           = $data['pagina_web']           ?? 0;
            $formato              = $data['formato']              ?? null;
            $drogshipin           = $data['drogshipin']           ?? 0;
            $destacado            = $data['destacado']            ?? 0;
            $envio_prioritario    = $data['envio_prioritario']    ?? 0;
            $enlace_funnelish     = $data['enlace_funnelish']     ?? "";
            $stock_inicial        = $data['stock_inicial']        ?? 0;
            $bodega               = $data['bodega']               ?? 0;
            $pcp                  = $data['pcp']                  ?? 0;
            $pvp                  = $data['pvp']                  ?? 0;

            // Campos fijos o por defecto
            $costo_producto = 0;
            $date_added     = date("Y-m-d H:i:s");
            $image_path     = "";
            $pref           = 0;

            // Reemplaza la condición actual por algo que cheque si es `=== null`
            if (
                $uuid === null ||
                $codigo_producto === null ||
                $nombre_producto === null ||
                $descripcion_producto === null ||
                $id_linea_producto === null ||
                $inv_producto === null ||
                $producto_variable === null ||
                $estado_producto === null ||
                $formato === null
            ) {
                http_response_code(400);
                echo json_encode(['status' => 400, 'message' => 'Faltan datos requeridos']);
                return;
            }


            $response = $this->model->agregarProducto(
                $uuid,
                $codigo_producto,
                $nombre_producto,
                $descripcion_producto,
                $id_linea_producto,
                $inv_producto,
                $producto_variable,
                $aplica_iva,
                $estado_producto,
                $id_imp_producto,
                $pagina_web,
                $formato,
                $drogshipin,
                $destacado,
                $envio_prioritario,
                $enlace_funnelish,
                $stock_inicial,
                $bodega,
                $pcp,
                $pvp,
                $costo_producto,
                $date_added,
                $image_path,
                $pref,
            );
            $this->handleResponse($response);
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }

    /**
     * @OA\Post(
     *     path="/swagger/editar_producto",
     *     tags={"Productos"},
     *     summary="Editar un producto existente",
     *     description="Permite editar un producto existente asociado a las tablas historial_productos, inventario_bodegas, productos.",
     *     @OA\Parameter(
     *         name="uuid",
     *         in="query",
     *         description="UUID del usuario o plataforma",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="id_producto", type="integer", description="Id del producto"),
     *                 @OA\Property(property="codigo_producto", type="string", description="Codigo del producto"),
     *                 @OA\Property(property="nombre_producto", type="string", description="Nombre del producto"),
     *                 @OA\Property(property="descripcion_producto", type="string", description="Descripción del producto"),
     *                 @OA\Property(property="id_linea_producto", type="integer", description="ID linea del producto"),
     *                 @OA\Property(property="inv_producto", type="integer", description="Estado inv del producto"),
     *                 @OA\Property(property="producto_variable", type="integer", description="Estado producto variable"),
     *                 @OA\Property(property="aplica_iva", type="integer", description="Estado aplica IVA el producto"),
     *                 @OA\Property(property="estado_producto", type="integer", description="Estado del producto"),
     *                 @OA\Property(property="id_imp_producto", type="integer", description="Id Imp del producto"),
     *                 @OA\Property(property="pagina_web", type="integer", description="Estado de página web del producto"),
     *                 @OA\Property(property="formato", type="integer", description="Estado de formato del producto"),
     *                 @OA\Property(property="drogshipin", type="integer", description="Estado de drogshipin del producto"),
     *                 @OA\Property(property="destacado", type="integer", description="Estado destacado del producto"),
     *                 @OA\Property(property="envio_prioritario", type="integer", description="Estado de envio priotario del producto"),
     *                 @OA\Property(property="enlace_funnelish", type="string", description="Enlace funnelish del producto"),
     *                 @OA\Property(property="stock_inicial", type="integer", description="Stock inicial del producto"),
     *                 @OA\Property(property="bodega", type="integer", description="Bodega del producto"),
     *                 @OA\Property(property="pcp", type="integer", description="Pcp del producto"),
     *                 @OA\Property(property="pvp", type="integer", description="Pvp del producto")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Producto editado con éxito"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Faltan datos requeridos"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno al editar el producto"
     *     )
     * )
     */
    public function editar_producto()
    {
        try {
            $this->logRequest('swagger/editar_producto', $_SERVER['REQUEST_METHOD'], file_get_contents('php://input'));
            $data = json_decode(file_get_contents("php://input"), true);

            // Validamos que vengan datos en el cuerpo
            if (!$data) {
                http_response_code(400);
                echo json_encode(['status' => 400, 'message' => 'Datos inválidos']);
                return;
            }
            // Capturar UUID desde query parameters
            $uuid = $_GET['uuid'] ?? null;

            // Extraer campos del JSON
            $id_producto          = $data['id_producto']          ?? null;
            $codigo_producto      = $data['codigo_producto']      ?? null;
            $nombre_producto      = $data['nombre_producto']      ?? null;
            $descripcion_producto = $data['descripcion_producto'] ?? null;
            $id_linea_producto    = $data['id_linea_producto']    ?? null;
            $inv_producto         = $data['inv_producto']         ?? null;
            $producto_variable    = $data['producto_variable']    ?? null;
            $aplica_iva           = $data['aplica_iva']           ?? 0;
            $estado_producto      = $data['estado_producto']      ?? null;
            $id_imp_producto      = $data['id_imp_producto']      ?? 12;
            $pagina_web           = $data['pagina_web']           ?? 0;
            $formato              = $data['formato']              ?? null;
            $drogshipin           = $data['drogshipin']           ?? 0;
            $destacado            = $data['destacado']            ?? 0;
            $envio_prioritario    = $data['envio_prioritario']    ?? 0;
            $enlace_funnelish     = $data['enlace_funnelish']     ?? "";
            $stock_inicial        = $data['stock_inicial']        ?? 0;
            $bodega               = $data['bodega']               ?? 0;
            $pcp                  = $data['pcp']                  ?? 0;
            $pvp                  = $data['pvp']                  ?? 0;

            // Campos fijos o por defecto
            $costo_producto = 0;
            $date_added     = date("Y-m-d H:i:s");
            $image_path     = "";
            $pref           = 0;

            // Reemplaza la condición actual por algo que cheque si es `=== null`
            if (
                $uuid === null ||
                $codigo_producto === null ||
                $nombre_producto === null ||
                $descripcion_producto === null ||
                $id_linea_producto === null ||
                $inv_producto === null ||
                $producto_variable === null ||
                $estado_producto === null ||
                $formato === null
            ) {
                http_response_code(400);
                echo json_encode(['status' => 400, 'message' => 'Faltan datos requeridos']);
                return;
            }

            $response = $this->model->editarProducto(
                $uuid,
                $id_producto,
                $codigo_producto,
                $nombre_producto,
                $descripcion_producto,
                $id_linea_producto,
                $inv_producto,
                $producto_variable,
                $aplica_iva,
                $estado_producto,
                $id_imp_producto,
                $pagina_web,
                $formato,
                $drogshipin,
                $destacado,
                $envio_prioritario,
                $enlace_funnelish,
                $stock_inicial,
                $bodega,
                $pcp,
                $pvp,
                $costo_producto,
                $date_added,
                $image_path,
                $pref,
            );
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
