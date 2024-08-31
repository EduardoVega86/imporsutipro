<?php
require 'vendor/autoload.php';

use OpenApi\Logger;

use Swagger\Annotations as SWG;

/**
 * @SWG\Swagger(
 *     basePath="/api",
 *     schemes={"http", "https"},
 *     @SWG\Info(
 *         version="1.0.0",
 *         title="My API",
 *         description="API documentation for My API",
 *         @SWG\Contact(name="My Company"),
 *         @SWG\License(name="MIT")
 *     )
 * )
 */
class Api extends Controller
{
    public $openapi;
    public function __construct()
    {

        $this->openapi = \OpenApi\Generator::scan(['Controllers/Pedidos.php']);
    }

    public function index()
    {
        header('Content-Type: application/json');
        echo $this->openapi->toJson();
    }

    public function yaml()
    {
        header('Content-Type: application/yaml');
        echo $this->openapi->toYaml();
    }
}
