<?php
require 'vendor/autoload.php';

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="Documentación de Prueba",
 *     version="1.0.0",
 *     description="Documentación mínima para prueba"
 * )
 */
class TestSwagger
{
    /**
     * @OA\Get(
     *     path="/test",
     *     summary="Prueba de endpoint",
     *     tags={"Prueba"},
     *     @OA\Response(
     *         response=200,
     *         description="Prueba exitosa"
     *     )
     * )
     */
    public function test()
    {
        echo "Prueba de Swagger";
    }
}
