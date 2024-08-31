<?php
require 'vendor/autoload.php';

class Api extends Controller
{
    public $openapi;
    public function __construct()
    {
        $this->openapi = \OpenApi\Generator::scan(['Controllers']);
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
