<?php

require_once 'PHPMailer/PHPMailer.php';
require_once 'PHPMailer/SMTP.php';
require_once 'PHPMailer/Exception.php';

require_once 'Models/HomeModel.php';
require_once 'Models/AccesoModel.php';


class SwaggerModel extends Query
{
    private $accesoModel;
    private $homeModel;
    public function __construct()
    {
        parent::__construct();
        $this->accesoModel = new AccesoModel();
        $this->homeModel = new HomeModel;
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

    public function jwt($token)
    {
        try {
            $response = $this->accesoModel->jwt($token);

            return $response;
        } catch (Exception $e) {
            // $this->handleException($e);
        }
    }
}
