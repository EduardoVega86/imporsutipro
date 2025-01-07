<?php
require 'vendor/autoload.php';

use OpenApi\Annotations as OA;

/**
 * @OA\OpenApi(
 *   openapi="3.0.0",
 *   @OA\Info(
 *       title="Documentación de la API Externa",
 *       version="1.0.0",
 *       description="Documentación generada con Swagger. Ante cualquier duda, contactar con el administrador",
 *       @OA\Contact(
 *          email="jjara@imporfactorylatam.com"
 *       )
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
        // Escanea SOLO este archivo
        // (lo ideal sería escanear todo el directorio con anotaciones)
        $openapi = \OpenApi\Generator::scan([__DIR__ . "/Swagger.php"]);
        header('Content-Type: application/json');
        echo $openapi->toJson();
    }
}
