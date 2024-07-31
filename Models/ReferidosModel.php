<?php

class ReferidosModel extends Query
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getReferidos($plataforma)
    {
        $sql = "SELECT * FROM plataformas where refiere = $plataforma";
        return $this->select($sql);
    }

    public function getReferidosMarketplace()
    {
        $sql = "SELECT * FROM plataformas where referido = 1";
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

    public function solicitar_pago($monto, $cuenta, $plataforma, $otro)
    {
        $id_matriz =  $this->obtenerMatriz();
        $id_matriz = $id_matriz[0]['id_matriz'];
        $sql = "INSERT INTO solicitudes_pago_referidos (cantidad, id_cuenta,id_matriz, id_plataforma, otro) VALUES (?, ?, ?, ?, ?)";
        $data = array($monto, $cuenta, $id_matriz, $plataforma, $otro);
        return $this->insert($sql, $data);
    }

    public function getCabecerasReferencias($plataforma)
    {
        $sql = "SELECT * FROM cabecera_cuenta_referidos WHERE id_plataforma = $plataforma";
        return $this->select($sql);
    }
}
