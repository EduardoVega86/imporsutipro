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
            $response['title'] = 'Éxito';
        } else {
            $response['status'] = 404;
            $response['title'] = 'Advertencia';
            $response["message"] = "No se encontraron datos";
        }

        $response['data'] = $data;

        return $response;
    }
}
