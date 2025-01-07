<?php

require_once 'PHPMailer/PHPMailer.php';
require_once 'PHPMailer/SMTP.php';
require_once 'PHPMailer/Exception.php';

require 'vendor/autoload.php';

class SwaggerModel extends Query
{

    public function __construct()
    {
        parent::__construct();
    }

    public function registro($nombre, $correo, $pais, $telefono, $contrasena, $tienda)
    {
        try {

            // 2. Instanciar la clase AccesoModel
            $accesoModel = new AccesoModel();

            // 3. Llamar al método registro del AccesoModel
            $response = $accesoModel->registro($nombre, $correo, $pais, $telefono, $contrasena, $tienda);

            // Manejo de la respuesta según tus necesidades
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
}
