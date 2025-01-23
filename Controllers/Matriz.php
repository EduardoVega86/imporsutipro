<?php

declare(strict_types=1);

class Matriz extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function agregar()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $response = $this->model->agregar($data);
        echo json_encode($response);
    }
}
