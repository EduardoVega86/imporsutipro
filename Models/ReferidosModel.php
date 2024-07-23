<?php

class ReferidosModel extends Query
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getReferidos()
    {
        $sql = "SELECT * FROM referidos";
        return $this->select($sql);
    }


    public function crearReferido($id)
    {
        $sql = "UPDATE plataformas set token_referido = ? WHERE id_plataforma = ?";
        $data = array($id, $id);
        return $this->update($sql, $data);
    }
}
