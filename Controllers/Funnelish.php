<?php

class Funnelish extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function data()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $this->model->saveData($data);
    }
}
