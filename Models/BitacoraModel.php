<?php

class BitacoraModel extends Query
{

    public function __construct()
    {
        parent::__construct();
    }

    public function estados($guia, $estado, $transportadora, $fecha)
    {
        $sql = "INSERT INTO bitacora (guia, estado, transportadora, fecha) VALUES (?, ?, ?, ?)";
        $data = [
            $guia,
            $estado,
            $transportadora,
            $fecha
        ];
        return $this->insert($sql, $data);
    }
}
