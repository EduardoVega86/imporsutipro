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

        if (is_array($data) && count($data) > 0) {
            $response["message"] = "Datos encontrados";
            $response['status'] = 200;
        } else {
            $response['status'] = 404;
            $response["message"] = "No se encontraron datos";
        }

        $response['data'] = $data;

        return $response;
    }
}
