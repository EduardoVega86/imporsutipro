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


    /**
     * @OA\Get(
     *   path="/swagger/pedidos",
     *  tags={"Pedidos"},
     * summary="Obtener pedidos",
     * description="Obtener pedidos por plataforma",
     * @OA\Parameter(
     *  name="uuid",
     * in="query",
     * description="UUID de la plataforma",
     * required=true,
     * @OA\Schema(
     * type="string"
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="Pedidos obtenidos"
     * ),
     * @OA\Response(
     * response=400,
     * description="Error al obtener pedidos"
     * )
     * )
     */

    public function docs()
    {
        $openapi = \OpenApi\Generator::scan([__DIR__ . "/Swagger.php"]);
        header('Content-Type: application/json');
        echo $openapi->toJson();
    }
}
