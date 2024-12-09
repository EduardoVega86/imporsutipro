<?php

class PruebaModel extends Query
{
    //modelo siempre termina con un return
    public function __construct()
    {
        parent::__construct();
    }

    public function getPlataformas()
    {
        $response = $this->initialResponse();

        $sql = "SELECT * FROM plataformas";
        $data = $this->select($sql);

        $response['data'] = $data;

        return $response;
    }
}
