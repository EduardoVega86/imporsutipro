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


    public function docs()
    {
        $openapi = \OpenApi\Generator::scan([__DIR__ . "/Acceso.php"]);
        header('Content-Type: application/json');
        echo $openapi->toJson();
    }
}
