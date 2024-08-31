<?php
require 'vendor/autoload.php';
class Api extends Controller
{
    public $openapi = \OpenApi\Generator::scan(['Controllers']);   // Scan the controllers folder
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

    public function json()
    {
        header('Content-Type: application/json');
        echo $this->openapi->toJson();
    }

    public function html()
    {
        header('Content-Type: text/html');
        echo $this->openapi->toHtml();
    }

    public function markdown()
    {
        header('Content-Type: text/markdown');
        echo $this->openapi->toMarkdown();
    }

    public function xml()
    {
        header('Content-Type: application/xml');
        echo $this->openapi->toXml();
    }

    public function array()
    {
        header('Content-Type: application/json');
        echo json_encode($this->openapi->toArray());
    }

    public function validate()
    {
        header('Content-Type: application/json');
        echo json_encode($this->openapi->validate());
    }

    public function get($path)
    {
        header('Content-Type: application/json');
        echo json_encode($this->openapi->get($path));
    }

    public function post($path)
    {
        header('Content-Type: application/json');
        echo json_encode($this->openapi->post($path));
    }

    public function put($path)
    {
        header('Content-Type: application/json');
        echo json_encode($this->openapi->put($path));
    }

    public function delete($path)
    {
        header('Content-Type: application/json');
        echo json_encode($this->openapi->delete($path));
    }

    public function patch($path)
    {
        header('Content-Type: application/json');
        echo json_encode($this->openapi->patch($path));
    }

    public function options($path)
    {
        header('Content-Type: application/json');
        echo json_encode($this->openapi->options($path));
    }

    public function head($path)
    {
        header('Content-Type: application/json');
        echo json_encode($this->openapi->head($path));
    }

    public function trace($path)

    {
        header('Content-Type: application/json');
        echo json_encode($this->openapi->trace($path));
    }

    public function connect($path)
    {
        header('Content-Type: application/json');
        echo json_encode($this->openapi->connect($path));
    }

    public function search($path)
    {
        header('Content-Type: application/json');
        echo json_encode($this->openapi->search($path));
    }
}
