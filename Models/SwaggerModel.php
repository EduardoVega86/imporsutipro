<?php

require_once 'PHPMailer/PHPMailer.php';
require_once 'PHPMailer/SMTP.php';
require_once 'PHPMailer/Exception.php';

require_once 'Models/HomeModel.php';
require_once 'Models/AccesoModel.php';
require_once 'Models/ProductosModel.php';

use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

class SwaggerModel extends Query
{
    private $accesoModel;
    private $homeModel;
    private $productosModel;
    public function __construct()
    {
        parent::__construct();
        $this->accesoModel = new AccesoModel();
        $this->homeModel = new HomeModel;
        $this->productosModel = new ProductosModel();
    }

    //Manejo de las respuestas
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
    public function registro($nombre, $correo, $pais, $telefono, $contrasena, $tienda)
    {
        try {
            // Llamamos al método registro del AccesoModel
            $response = $this->accesoModel->registro($nombre, $correo, $pais, $telefono, $contrasena, $tienda);

            // Manejo de la respuesta 
            return $response;
        } catch (Exception $e) {
            //$this->handleException($e);
        }
    }

    public function registro_referido($nombre, $correo, $pais, $telefono, $contrasena, $tienda, $referido)
    {
        try {
            // Llamamos al método registro del AccesoModel
            $response = $this->accesoModel->registro_referido($nombre, $correo, $pais, $telefono, $contrasena, $tienda, $referido);

            // Manejo de la respuesta 
            return $response;
        } catch (Exception $e) {
            //$this->handleException($e);
        }
    }

    public function login($usuario, $password)
    {
        try {
            // Llamamos al método registro del AccesoModel
            $response = $this->accesoModel->login($usuario, $password);

            // Manejo de la respuesta 
            return $response;
        } catch (Exception $e) {
            /* $this->handleException($e); */
        }
    }

    public function validarTiendas($tienda)
    {
        try {
            // Llamamos al método registro del AccesoModel
            $response = $this->accesoModel->validarTiendas($tienda);

            // Manejo de la respuesta 
            return $response;
        } catch (Exception $e) {
            // Manejo de la excepcion
            //$this->handleException($e);
        }
    }

    public function recuperarContrasena($correo)
    {
        try {
            $response = $this->accesoModel->recuperar_contrasena($correo);

            // Retornar la respuesta obtenida
            return $response;
        } catch (Exception $e) {
            //$this->handleException($e);
        }
    }

    public function validarToken($token)
    {
        try {
            $response = $this->accesoModel->validarToken($token);

            // Retornar la respuesta obtenida
            return $response;
        } catch (Exception $e) {
            //$this->handleException($e);
        }
    }

    public function cambiarContrasena($token, $contrasena)
    {
        try {
            $response = $this->accesoModel->cambiarContrasena($token, $contrasena);

            return $response;
        } catch (Exception $e) {
            //$this->handleException($e);
        }
    }

    public function validarRefiere($id)
    {
        try {
            $response = $this->homeModel->validarRefiere($id);

            return $response;
        } catch (Exception $e) {
            //$this->handleException($e);
        }
    }



    public function obtener_productos($uuid)
    {
        try {
            // 1. Verificar si existe usuario con ese UUID en AccesoModel
            $usuario = $this->accesoModel->getUserByUUID($uuid);
            if (empty($usuario)) {
                return [
                    'status'  => 404,
                    'message' => "No existe un usuario con el UUID: $uuid"
                ];
            }

            // 2. Tomamos el id_users del primer registro encontrado
            $id_users = $usuario[0]['id_users'];

            // 3. Obtener la plataforma asociada
            $plataforma = $this->accesoModel->getPlatformByUserId($id_users);
            //Depurando porque no mostraba  datos en Swagger segun su uuid
            // print_r($plataforma);
            if (empty($plataforma) || !isset($plataforma[0]['id_plataforma'])) {
                return [
                    'status'  => 404,
                    'message' => 'No se encontró la plataforma asociada al usuario'
                ];
            }

            // 4. Obtener los productos de esa plataforma
            $id_plataforma = $plataforma[0]['id_plataforma'];
            $productos = $this->productosModel->getProductosPorPlataforma($id_plataforma);

            // 5. Devolver respuesta exitosa
            return [
                'status'  => 200,
                'message' => 'Productos obtenidos exitosamente',
                'data'    => $productos
            ];
        } catch (Exception $e) {
            // Manejo de excepciones internas
            return [
                'status'  => 500,
                'message' => 'Error interno al obtener productos',
                'error'   => $e->getMessage()
            ];
        }
    }


    public function obtener_bovedas($uuid)
    {
        try {
            // 1. Verificar si existe usuario con ese UUID en AccesoModel
            $usuario = $this->accesoModel->getUserByUUID($uuid);
            if (empty($usuario)) {
                return [
                    'status'  => 404,
                    'message' => "No existe un usuario con el UUID: $uuid"
                ];
            }

            // 2. Tomamos el id_users del primer registro encontrado
            $id_users = $usuario[0]['id_users'];

            // 3. Obtener la plataforma asociada
            $plataforma = $this->accesoModel->getPlatformByUserId($id_users);
            //Depurando porque no mostraba  datos en Swagger segun su uuid
            // print_r($plataforma);
            if (empty($plataforma) || !isset($plataforma[0]['id_plataforma'])) {
                return [
                    'status'  => 404,
                    'message' => 'No se encontró la plataforma asociada al usuario'
                ];
            }

            // 4. Obtener las bovedas de esa plataforma
            $id_plataforma = $plataforma[0]['id_plataforma'];
            $bovedas = $this->productosModel->getBovedasPorPlataforma($id_plataforma);

            // 5. Devolver respuesta exitosa
            return [
                'status'  => 200,
                'message' => 'Bovedas obtenidas exitosamente',
                'data'    => $bovedas
            ];
        } catch (Exception $e) {
            // Manejo de excepciones internas
            return [
                'status'  => 500,
                'message' => 'Error interno al obtener bovedas',
                'error'   => $e->getMessage()
            ];
        }
    }


    public function obtener_lineas_global($uuid)
    {
        try {
            // 1. Verificar si existe usuario con ese UUID en AccesoModel
            $usuario = $this->accesoModel->getUserByUUID($uuid);
            if (empty($usuario)) {
                return [
                    'status'  => 404,
                    'message' => "No existe un usuario con el UUID: $uuid"
                ];
            }

            // 2. Tomamos el id_users del primer registro encontrado
            $id_users = $usuario[0]['id_users'];

            // 3. Obtener la plataforma asociada
            $plataforma = $this->accesoModel->getPlatformByUserId($id_users);
            //Depurando porque no mostraba  datos en Swagger segun su uuid
            // print_r($plataforma);
            if (empty($plataforma) || !isset($plataforma[0]['id_plataforma'])) {
                return [
                    'status'  => 404,
                    'message' => 'No se encontró la plataforma asociada al usuario'
                ];
            }

            // 4. Obtener los proveedores de esa plataforma
            $id_plataforma = $plataforma[0]['id_plataforma'];
            $lineas_globales = $this->productosModel->getLineasPorPlataforma($id_plataforma);

            // 5. Devolver respuesta exitosa
            return [
                'status'  => 200,
                'message' => 'Lineas globales obtenidas exitosamente',
                'data'    => $lineas_globales
            ];
        } catch (Exception $e) {
            // Manejo de excepciones internas
            return [
                'status'  => 500,
                'message' => 'Error interno al obtener lineas globales',
                'error'   => $e->getMessage()
            ];
        }
    }

    public function obtenerProductosTodos($uuid)
    {
        try {
            // Verificar si existe usuario con ese UUID
            $usuario = $this->accesoModel->getUserByUUID($uuid);
            if (empty($usuario)) {
                return [
                    'status'  => 404,
                    'message' => "No existe un usuario con el UUID: $uuid"
                ];
            }

            // Obtener el ID de plataforma asociado
            $id_users = $usuario[0]['id_users'];
            $plataforma = $this->accesoModel->getPlatformByUserId($id_users);
            if (empty($plataforma) || !isset($plataforma[0]['id_plataforma'])) {
                return [
                    'status'  => 404,
                    'message' => 'No se encontró la plataforma asociada al usuario'
                ];
            }

            return $this->productosModel->obtenerProductosTodos();
        } catch (Exception $e) {
            return [
                'status'  => 500,
                'message' => 'Error interno al obtener los productos',
                'error'   => $e->getMessage()
            ];
        }
    }
    public function obtenerBovedasTodas($uuid)
    {
        try {
            // Verificar si existe usuario con ese UUID
            $usuario = $this->accesoModel->getUserByUUID($uuid);
            if (empty($usuario)) {
                return [
                    'status'  => 404,
                    'message' => "No existe un usuario con el UUID: $uuid"
                ];
            }

            // Obtener el ID de plataforma asociado
            $id_users = $usuario[0]['id_users'];
            $plataforma = $this->accesoModel->getPlatformByUserId($id_users);
            if (empty($plataforma) || !isset($plataforma[0]['id_plataforma'])) {
                return [
                    'status'  => 404,
                    'message' => 'No se encontró la plataforma asociada al usuario'
                ];
            }

            return $this->productosModel->obtenerBovedas();
        } catch (Exception $e) {
            return [
                'status'  => 500,
                'message' => 'Error interno al obtener todas las bovedas',
                'error'   => $e->getMessage()
            ];
        }
    }

    public function obtenerBovedasId($uuid, $id)
    {
        try {
            // Verificar si existe usuario con ese UUID
            $usuario = $this->accesoModel->getUserByUUID($uuid);
            if (empty($usuario)) {
                return [
                    'status'  => 404,
                    'message' => "No existe un usuario con el UUID: $uuid"
                ];
            }

            // Obtener el ID de plataforma asociado
            $id_users = $usuario[0]['id_users'];
            $plataforma = $this->accesoModel->getPlatformByUserId($id_users);
            if (empty($plataforma) || !isset($plataforma[0]['id_plataforma'])) {
                return [
                    'status'  => 404,
                    'message' => 'No se encontró la plataforma asociada al usuario'
                ];
            }

            return $this->productosModel->obtenerBoveda($id);
        } catch (Exception $e) {
            return [
                'status'  => 500,
                'message' => 'Error interno al obtener la boveda',
                'error'   => $e->getMessage()
            ];
        }
    }

    public function obtenerProveedoresUno($uuid)
    {
        try {
            // Verificar si existe usuario con ese UUID
            $usuario = $this->accesoModel->getUserByUUID($uuid);
            if (empty($usuario)) {
                return [
                    'status'  => 404,
                    'message' => "No existe un usuario con el UUID: $uuid"
                ];
            }

            // Obtener el ID de plataforma asociado
            $id_users = $usuario[0]['id_users'];
            $plataforma = $this->accesoModel->getPlatformByUserId($id_users);
            if (empty($plataforma) || !isset($plataforma[0]['id_plataforma'])) {
                return [
                    'status'  => 404,
                    'message' => 'No se encontró la plataforma asociada al usuario'
                ];
            }

            return $this->productosModel->obtenerProveedores();
        } catch (Exception $e) {
            return [
                'status'  => 500,
                'message' => 'Error interno al obtener los proveedores',
                'error'   => $e->getMessage()
            ];
        }
    }

    public function obtenerProductosPrivados($uuid, $id_plataforma)
    {
        try {
            // Verificar si existe usuario con ese UUID
            $usuario = $this->accesoModel->getUserByUUID($uuid);
            if (empty($usuario)) {
                return [
                    'status'  => 404,
                    'message' => "No existe un usuario con el UUID: $uuid"
                ];
            }

            // Obtener el ID de plataforma asociado
            $id_users = $usuario[0]['id_users'];
            $plataforma = $this->accesoModel->getPlatformByUserId($id_users);
            if (empty($plataforma) || !isset($plataforma[0]['id_plataforma'])) {
                return [
                    'status'  => 404,
                    'message' => 'No se encontró la plataforma asociada al usuario'
                ];
            }

            // Validamos que ambos parámetros sean válidos
            if (empty($uuid) || empty($id_plataforma)) {
                return [
                    'status'  => 400,
                    'message' => 'UUID e ID de plataforma son requeridos'
                ];
            }

            return $this->productosModel->obtener_productos_privados($id_plataforma);
        } catch (Exception $e) {
            return [
                'status'  => 500,
                'message' => 'Error interno al obtener los productos privados',
                'error'   => $e->getMessage()
            ];
        }
    }

    public function obtenerProductosTienda($uuid, $id_plataforma)
    {
        try {
            // Verificar si existe usuario con ese UUID
            $usuario = $this->accesoModel->getUserByUUID($uuid);
            if (empty($usuario)) {
                return [
                    'status'  => 404,
                    'message' => "No existe un usuario con el UUID: $uuid"
                ];
            }

            // Obtener el ID de plataforma asociado
            $id_users = $usuario[0]['id_users'];
            $plataforma = $this->accesoModel->getPlatformByUserId($id_users);
            if (empty($plataforma) || !isset($plataforma[0]['id_plataforma'])) {
                return [
                    'status'  => 404,
                    'message' => 'No se encontró la plataforma asociada al usuario'
                ];
            }

            // Validamos que ambos parámetros sean válidos
            if (empty($uuid) || empty($id_plataforma)) {
                return [
                    'status'  => 400,
                    'message' => 'UUID e ID de plataforma son requeridos'
                ];
            }

            return $this->productosModel->obtener_productos_tienda($id_plataforma);
        } catch (Exception $e) {
            return [
                'status'  => 500,
                'message' => 'Error interno al obtener los productos tienda',
                'error'   => $e->getMessage()
            ];
        }
    }
    public function obtenerProductosTiendaAutomatizador($uuid, $id_plataforma)
    {
        try {
            // Verificar si existe usuario con ese UUID
            $usuario = $this->accesoModel->getUserByUUID($uuid);
            if (empty($usuario)) {
                return [
                    'status'  => 404,
                    'message' => "No existe un usuario con el UUID: $uuid"
                ];
            }

            // Obtener el ID de plataforma asociado
            $id_users = $usuario[0]['id_users'];
            $plataforma = $this->accesoModel->getPlatformByUserId($id_users);
            if (empty($plataforma) || !isset($plataforma[0]['id_plataforma'])) {
                return [
                    'status'  => 404,
                    'message' => 'No se encontró la plataforma asociada al usuario'
                ];
            }

            // Validamos que ambos parámetros sean válidos
            if (empty($uuid) || empty($id_plataforma)) {
                return [
                    'status'  => 400,
                    'message' => 'UUID e ID de plataforma son requeridos'
                ];
            }

            return $this->productosModel->obtener_productos_tienda_automatizador($id_plataforma);
        } catch (Exception $e) {
            return [
                'status'  => 500,
                'message' => 'Error interno al obtener los productos tienda automatizador',
                'error'   => $e->getMessage()
            ];
        }
    }
    public function obtenerProductosInventario($uuid, $id_plataforma)
    {
        try {
            // Verificar si existe usuario con ese UUID
            $usuario = $this->accesoModel->getUserByUUID($uuid);
            if (empty($usuario)) {
                return [
                    'status'  => 404,
                    'message' => "No existe un usuario con el UUID: $uuid"
                ];
            }

            // Obtener el ID de plataforma asociado
            $id_users = $usuario[0]['id_users'];
            $plataforma = $this->accesoModel->getPlatformByUserId($id_users);
            if (empty($plataforma) || !isset($plataforma[0]['id_plataforma'])) {
                return [
                    'status'  => 404,
                    'message' => 'No se encontró la plataforma asociada al usuario'
                ];
            }

            // Validamos que ambos parámetros sean válidos
            if (empty($uuid) || empty($id_plataforma)) {
                return [
                    'status'  => 400,
                    'message' => 'UUID e ID de plataforma son requeridos'
                ];
            }

            return $this->productosModel->obtener_productos_inventario($id_plataforma);
        } catch (Exception $e) {
            return [
                'status'  => 500,
                'message' => 'Error interno al obtener los productos de inventario',
                'error'   => $e->getMessage()
            ];
        }
    }


    public function agregarBoveda($uuid, $idProducto, $idLinea, $imagen, $idPlataforma, $ejemploLanding, $duplicarFunnel, $videos)
    {
        try {
            // Verificar si existe usuario con ese UUID
            $usuario = $this->accesoModel->getUserByUUID($uuid);
            if (empty($usuario)) {
                return [
                    'status'  => 404,
                    'message' => "No existe un usuario con el UUID: $uuid"
                ];
            }

            // Obtener el ID de plataforma asociado
            $id_users = $usuario[0]['id_users'];
            $plataforma = $this->accesoModel->getPlatformByUserId($id_users);
            if (empty($plataforma) || !isset($plataforma[0]['id_plataforma'])) {
                return [
                    'status'  => 404,
                    'message' => 'No se encontró la plataforma asociada al usuario'
                ];
            }

            $idPlataforma = $plataforma[0]['id_plataforma'];

            // Llamar a insertarBoveda con los datos y el ID de plataforma
            return $this->productosModel->insertarBoveda($idProducto, $idLinea, $imagen, $idPlataforma, $ejemploLanding, $duplicarFunnel, $videos);
        } catch (Exception $e) {
            return [
                'status'  => 500,
                'message' => 'Error interno al agregar bóveda',
                'error'   => $e->getMessage()
            ];
        }
    }

    public function editarBoveda($uuid, $id_boveda, $id_linea, $id_plataforma, $imagen, $id_produto, $ejemploLanding, $duplicarFunnel, $videos)
    {
        try {
            // Verificar si existe usuario con ese UUID
            $usuario = $this->accesoModel->getUserByUUID($uuid);
            if (empty($usuario)) {
                return [
                    'status'  => 404,
                    'message' => "No existe un usuario con el UUID: $uuid"
                ];
            }

            // Obtener el ID de plataforma asociado
            $id_users = $usuario[0]['id_users'];
            $plataforma = $this->accesoModel->getPlatformByUserId($id_users);
            if (empty($plataforma) || !isset($plataforma[0]['id_plataforma'])) {
                return [
                    'status'  => 404,
                    'message' => 'No se encontró la plataforma asociada al usuario'
                ];
            }

            $id_plataforma = $plataforma[0]['id_plataforma'];

            // Llamar a editarBoveda con los datos y el ID de plataforma
            return $this->productosModel->editarBoveda($id_boveda, $id_linea, $id_plataforma, $id_produto, $imagen, $ejemploLanding, $duplicarFunnel, $videos);
        } catch (Exception $e) {
            return [
                'status'  => 500,
                'message' => 'Error interno al agregar bóveda',
                'error'   => $e->getMessage()
            ];
        }
    }
}
