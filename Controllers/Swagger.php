<?php
// Asegúrate de no tener espacios / líneas antes de este <?php
require 'vendor/autoload.php';

use OpenApi\Annotations as OA;

/**
 * @OA\OpenApi(
 *   openapi="3.0.0",
 *   @OA\Info(
 *     title="Documentación de la API Externa",
 *     version="1.0.0",
 *     description="Documentación generada con Swagger. Ante cualquier duda, contactar con el administrador",
 *     @OA\Contact(
 *       email="jjara@imporfactorylatam.com"
 *     )
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
        // Lo ideal es escanear TODA la carpeta con tus controladores y anotaciones
        // Si solo escaneas "Swagger.php", no verá las anotaciones de otros archivos
        $openapi = \OpenApi\Generator::scan([__DIR__]);
        header('Content-Type: application/json');
        echo $openapi->toJson();
    }
}
