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
    public function crearBilletera($id)
    {
        // Consulta para verificar si ya existe una billetera para la plataforma dada
        $sql = "SELECT * FROM billetera_referidos WHERE id_plataforma = $id";
        $result = $this->select($sql);

        // Si no existe una billetera, crea una nueva
        if (empty($result)) {
            $sql = "INSERT INTO billetera_referidos (`saldo`, `id_plataforma`) VALUES (?, ?)";
            $data = array(0, $id);
            $this->insert($sql, $data);
            return true;
        }

        // Retorna algún valor o mensaje si la billetera ya existe
        return false; // O algún mensaje que haga sentido en tu lógica de negocio
    }


    public function crearReferido($id)
    {
        $sql = "UPDATE plataformas set token_referido = ? WHERE id_plataforma = ?";
        $data = array($id, $id);
        return $this->update($sql, $data);
    }
}
