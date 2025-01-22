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

    public function obtenerProductosPrivados($uuid)
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
            // Tomamos el id_plataforma
            $id_plataforma = $plataforma[0]['id_plataforma'];

            return $this->productosModel->obtener_productos_privados($id_plataforma);
        } catch (Exception $e) {
            return [
                'status'  => 500,
                'message' => 'Error interno al obtener los productos privados',
                'error'   => $e->getMessage()
            ];
        }
    }

    public function obtenerProductosTienda($uuid)
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
            // Tomamos el id_plataforma
            $id_plataforma = $plataforma[0]['id_plataforma'];

            return $this->productosModel->obtener_productos_tienda($id_plataforma);
        } catch (Exception $e) {
            return [
                'status'  => 500,
                'message' => 'Error interno al obtener los productos tienda',
                'error'   => $e->getMessage()
            ];
        }
    }
    public function obtenerProductosTiendaAutomatizador($uuid)
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

            return $this->productosModel->obtener_productos_tienda_automatizador($id_plataforma);
        } catch (Exception $e) {
            return [
                'status'  => 500,
                'message' => 'Error interno al obtener los productos tienda automatizador',
                'error'   => $e->getMessage()
            ];
        }
    }
    public function obtenerProductosInventario($uuid)
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

            return $this->productosModel->obtener_productos_inventario($id_plataforma);
        } catch (Exception $e) {
            return [
                'status'  => 500,
                'message' => 'Error interno al obtener los productos de inventario',
                'error'   => $e->getMessage()
            ];
        }
    }

    public function obtenerProducto($uuid, $id_producto)
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

            return $this->productosModel->obtenerProducto($id_producto);
        } catch (Exception $e) {
            return [
                'status'  => 500,
                'message' => 'Error interno al obtener producto de inventario',
                'error'   => $e->getMessage()
            ];
        }
    }
    public function obtenerBodegas($uuid)
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
            return $this->productosModel->obtener_bodegas($id_plataforma);
        } catch (Exception $e) {
            return [
                'status'  => 500,
                'message' => 'Error interno al obtener producto de inventario',
                'error'   => $e->getMessage()
            ];
        }
    }
    public function obtenerBodega($uuid, $id_bodega)
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
            return $this->productosModel->obtenerBodega($id_bodega, $id_plataforma);
        } catch (Exception $e) {
            return [
                'status'  => 500,
                'message' => 'Error interno al obtener producto de inventario',
                'error'   => $e->getMessage()
            ];
        }
    }

    public function obtenerProductoTienda($uuid, $id_producto)
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

            return $this->productosModel->obtenerProductoTienda($id_producto);
        } catch (Exception $e) {
            return [
                'status'  => 500,
                'message' => 'Error interno al obtener producto de inventario',
                'error'   => $e->getMessage()
            ];
        }
    }


    public function agregarBodega($uuid, $nombre, $direccion_completa, $telefono, $ciudad_entrega, $provincia, $nombre_contacto, $numero_casa, $referencia, $longitud, $latitud)
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

            // Obtener ID de plataforma
            $idPlataforma = $plataforma[0]['id_plataforma'];

            // Usar el mismo teléfono como teléfono de contacto
            $telefono_contacto = $telefono;

            // Llamar al modelo de productos para insertar la bodega
            return $this->productosModel->agregarBodega(
                $nombre,
                $direccion_completa,
                $telefono,
                $ciudad_entrega,
                $provincia,
                $nombre_contacto,
                $telefono_contacto,
                $numero_casa,
                $referencia,
                $idPlataforma,
                $longitud,
                $latitud
            );
        } catch (Exception $e) {
            return [
                'status'  => 500,
                'message' => 'Error interno al agregar bodega',
                'error'   => $e->getMessage()
            ];
        }
    }

    public function editarBodega($uuid, $id_bodega, $nombre, $direccion_completa, $telefono, $ciudad_entrega, $provincia, $nombre_contacto, $numero_casa, $referencia, $longitud, $latitud)
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

            // Obtener ID de plataforma
            $idPlataforma = $plataforma[0]['id_plataforma'];

            // Usar el mismo teléfono como teléfono de contacto
            $telefono_contacto = $telefono;

            // Llamar al modelo de productos para insertar la bodega
            return $this->productosModel->editarBodega(
                $id_bodega,
                $nombre,
                $direccion_completa,
                $telefono,
                $ciudad_entrega,
                $provincia,
                $nombre_contacto,
                $telefono_contacto,
                $numero_casa,
                $referencia,
                $idPlataforma,
                $longitud,
                $latitud
            );
        } catch (Exception $e) {
            return [
                'status'  => 500,
                'message' => 'Error interno al agregar bodega',
                'error'   => $e->getMessage()
            ];
        }
    }

    public function listarBodegas($uuid)
    {
        try {
            // 1) Verificar si existe usuario con ese UUID
            $usuario = $this->accesoModel->getUserByUUID($uuid);
            if (empty($usuario)) {
                return [
                    'status'  => 404,
                    'message' => "No existe un usuario con el UUID: $uuid"
                ];
            }

            // 2) Obtener la plataforma asociada al usuario
            $id_users = $usuario[0]['id_users'];
            $plataforma = $this->accesoModel->getPlatformByUserId($id_users);

            if (empty($plataforma) || !isset($plataforma[0]['id_plataforma'])) {
                return [
                    'status'  => 404,
                    'message' => 'No se encontró la plataforma asociada al usuario'
                ];
            }

            // Tomamos el id_plataforma
            $id_plataforma = $plataforma[0]['id_plataforma'];

            // 3) Llamamos al modelo de productos para listar las bodegas
            return $this->productosModel->listarBodegas($id_plataforma);
        } catch (Exception $e) {
            return [
                'status'  => 500,
                'message' => 'Error interno al listar bodegas',
                'error'   => $e->getMessage()
            ];
        }
    }

    public function cargarBodegas($uuid)
    {
        try {
            // 1) Verificar si existe usuario con ese UUID
            $usuario = $this->accesoModel->getUserByUUID($uuid);
            if (empty($usuario)) {
                return [
                    'status'  => 404,
                    'message' => "No existe un usuario con el UUID: $uuid"
                ];
            }

            // 2) Obtener la plataforma asociada al usuario
            $id_users = $usuario[0]['id_users'];
            $plataforma = $this->accesoModel->getPlatformByUserId($id_users);

            if (empty($plataforma) || !isset($plataforma[0]['id_plataforma'])) {
                return [
                    'status'  => 404,
                    'message' => 'No se encontró la plataforma asociada al usuario'
                ];
            }

            // Tomamos el id_plataforma
            $id_plataforma = $plataforma[0]['id_plataforma'];

            // 3) Llamamos al modelo de productos para listar las bodegas
            return $this->productosModel->cargarBodegas($id_plataforma);
        } catch (Exception $e) {
            return [
                'status'  => 500,
                'message' => 'Error interno al listar bodegas',
                'error'   => $e->getMessage()
            ];
        }
    }

    public function agregarCategoria(
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
    ) {
        try {
            // 1. Verificar si existe usuario con ese UUID
            $usuario = $this->accesoModel->getUserByUUID($uuid);
            if (empty($usuario)) {
                return [
                    'status'  => 404,
                    'message' => "No existe un usuario con el UUID: $uuid"
                ];
            }

            // 2. Obtener el ID de usuario y luego el ID de plataforma asociado
            $id_users   = $usuario[0]['id_users'];
            $plataforma = $this->accesoModel->getPlatformByUserId($id_users);

            if (empty($plataforma) || !isset($plataforma[0]['id_plataforma'])) {
                return [
                    'status'  => 404,
                    'message' => 'No se encontró la plataforma asociada al usuario'
                ];
            }

            // 3. Obtener ID de plataforma
            $idPlataforma = $plataforma[0]['id_plataforma'];

            // 4. Llamar al método de productos para insertar la categoría
            return $this->productosModel->agregarCategoria(
                $nombre_linea,
                $descripcion_linea,
                $estado_linea,
                $date_added,
                $online,
                $imagen,
                $tipo,
                $padre,
                $idPlataforma,
                $orden
            );
        } catch (Exception $e) {
            return [
                'status'  => 500,
                'message' => 'Error interno al agregar la categoría',
                'error'   => $e->getMessage()
            ];
        }
    }
    public function editarCategoria(
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
    ) {
        try {
            // 1. Verificar si existe usuario con ese UUID
            $usuario = $this->accesoModel->getUserByUUID($uuid);
            if (empty($usuario)) {
                return [
                    'status'  => 404,
                    'message' => "No existe un usuario con el UUID: $uuid"
                ];
            }

            // 2. Obtener el ID de usuario y luego el ID de plataforma asociado
            $id_users   = $usuario[0]['id_users'];
            $plataforma = $this->accesoModel->getPlatformByUserId($id_users);

            if (empty($plataforma) || !isset($plataforma[0]['id_plataforma'])) {
                return [
                    'status'  => 404,
                    'message' => 'No se encontró la plataforma asociada al usuario'
                ];
            }

            // 3. Obtener ID de plataforma
            $idPlataforma = $plataforma[0]['id_plataforma'];

            // 4. Llamar al método de productos para insertar la categoría
            return $this->productosModel->editarCategoria(
                $id_categoria,
                $nombre_linea,
                $descripcion_linea,
                $estado_linea,
                $date_added,
                $online,
                $imagen,
                $tipo,
                $padre,
                $idPlataforma,
                $orden
            );
        } catch (Exception $e) {
            return [
                'status'  => 500,
                'message' => 'Error interno al agregar la categoría',
                'error'   => $e->getMessage()
            ];
        }
    }

    public function listarCategoria($id_categoria, $uuid)
    {
        try {
            // 1) Verificar si existe usuario con ese UUID
            $usuario = $this->accesoModel->getUserByUUID($uuid);
            if (empty($usuario)) {
                return [
                    'status'  => 404,
                    'message' => "No existe un usuario con el UUID: $uuid"
                ];
            }

            // 2) Obtener la plataforma asociada al usuario
            $id_users = $usuario[0]['id_users'];
            $plataforma = $this->accesoModel->getPlatformByUserId($id_users);

            if (empty($plataforma) || !isset($plataforma[0]['id_plataforma'])) {
                return [
                    'status'  => 404,
                    'message' => 'No se encontró la plataforma asociada al usuario'
                ];
            }

            // Tomamos el id_plataforma
            $id_plataforma = $plataforma[0]['id_plataforma'];

            return $this->productosModel->listarCategoria($id_categoria, $id_plataforma);
        } catch (Exception $e) {
            return [
                'status'  => 500,
                'message' => 'Error interno al listar bodegas',
                'error'   => $e->getMessage()
            ];
        }
    }


    public function guardarImagenCategorias($uuid, $imagen, $id_linea)
    {
        try {
            // 1. Verificar si existe usuario con ese UUID
            $usuario = $this->accesoModel->getUserByUUID($uuid);
            if (empty($usuario)) {
                return [
                    'status'  => 404,
                    'message' => "No existe un usuario con el UUID: $uuid"
                ];
            }

            // 2. Obtener el ID de usuario y luego el ID de plataforma asociado
            $id_users   = $usuario[0]['id_users'];
            $plataforma = $this->accesoModel->getPlatformByUserId($id_users);

            if (empty($plataforma) || !isset($plataforma[0]['id_plataforma'])) {
                return [
                    'status'  => 404,
                    'message' => 'No se encontró la plataforma asociada al usuario'
                ];
            }

            // 3. Obtener ID de plataforma
            $idPlataforma = $plataforma[0]['id_plataforma'];

            // 4. Llamar al método de productos para subir y guardar la imagen
            return $this->productosModel->guardar_imagen_categorias(
                $imagen,
                $id_linea,
                $idPlataforma
            );
        } catch (Exception $e) {
            // Manejo de excepciones internas
            return [
                'status'  => 500,
                'message' => 'Error interno al subir la imagen',
                'error'   => $e->getMessage()
            ];
        }
    }

    public function guardarImagenProductos($uuid, $imagen, $id_producto)
    {
        try {
            // 1. Verificar si existe usuario con ese UUID
            $usuario = $this->accesoModel->getUserByUUID($uuid);
            if (empty($usuario)) {
                return [
                    'status'  => 404,
                    'message' => "No existe un usuario con el UUID: $uuid"
                ];
            }

            // 2. Obtener el ID de usuario y luego el ID de plataforma asociado
            $id_users   = $usuario[0]['id_users'];
            $plataforma = $this->accesoModel->getPlatformByUserId($id_users);

            if (empty($plataforma) || !isset($plataforma[0]['id_plataforma'])) {
                return [
                    'status'  => 404,
                    'message' => 'No se encontró la plataforma asociada al usuario'
                ];
            }

            // 3. Obtener ID de plataforma
            $idPlataforma = $plataforma[0]['id_plataforma'];

            // 4. Llamar al método de productos para subir y guardar la imagen
            return $this->productosModel->guardar_imagen_productos(
                $imagen,
                $id_producto,
                $idPlataforma
            );
        } catch (Exception $e) {
            // Manejo de excepciones internas
            return [
                'status'  => 500,
                'message' => 'Error interno al subir la imagen en el producto',
                'error'   => $e->getMessage()
            ];
        }
    }
    public function guardarImagenAdicionalProductos($uuid, $num_imagen, $imagen, $id_producto)
    {
        try {
            // 1. Verificar si existe usuario con ese UUID
            $usuario = $this->accesoModel->getUserByUUID($uuid);
            if (empty($usuario)) {
                return [
                    'status'  => 404,
                    'message' => "No existe un usuario con el UUID: $uuid"
                ];
            }

            // 2. Obtener el ID de usuario y luego el ID de plataforma asociado
            $id_users   = $usuario[0]['id_users'];
            $plataforma = $this->accesoModel->getPlatformByUserId($id_users);

            if (empty($plataforma) || !isset($plataforma[0]['id_plataforma'])) {
                return [
                    'status'  => 404,
                    'message' => 'No se encontró la plataforma asociada al usuario'
                ];
            }

            // 3. Obtener ID de plataforma
            $idPlataforma = $plataforma[0]['id_plataforma'];

            // 4. Llamar al método de productos para subir y guardar la imagen
            return $this->productosModel->guardar_imagenAdicional_productos(
                $num_imagen,
                $imagen,
                $id_producto,
                $idPlataforma
            );
        } catch (Exception $e) {
            // Manejo de excepciones internas
            return [
                'status'  => 500,
                'message' => 'Error interno al subir la imagen en el producto',
                'error'   => $e->getMessage()
            ];
        }
    }



    public function agregarBoveda($uuid, $idProducto, $idLinea, $imagen, $idProveedor, $ejemploLanding, $duplicarFunnel, $videos)
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
            return $this->productosModel->insertarBoveda($idProducto, $idLinea, $imagen, $idProveedor, $idPlataforma, $ejemploLanding, $duplicarFunnel, $videos);
        } catch (Exception $e) {
            return [
                'status'  => 500,
                'message' => 'Error interno al agregar bóveda',
                'error'   => $e->getMessage()
            ];
        }
    }

    public function editarBoveda($uuid, $id_boveda, $id_linea, $id_plataforma, $id_produto, $imagen, $ejemploLanding, $duplicarFunnel, $videos)
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
