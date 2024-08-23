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
        $response =  $this->update($sql, $data);
        if ($response == 1) {
            $response = array(
                "status" => 200,
                "message" => "Referido creado"
            );
        } else {
            $response = array(
                "status" => 500,
                "message" => "Error al crear el referido, ya existe"
            );
        }
        return $response;
    }

    public function solicitar_pago($monto, $cuenta, $plataforma, $otro)
    {
        $id_matriz =  $this->obtenerMatriz();
        $id_matriz = $id_matriz[0]['idmatriz'];
        $sql = "INSERT INTO solicitudes_pago_referidos (cantidad, id_cuenta,id_matriz, id_plataforma, otro) VALUES (?, ?, ?, ?, ?)";
        $data = array($monto, $cuenta, $id_matriz, $plataforma, $otro);
        $respuesta = $this->insert($sql, $data);
        if ($respuesta == 1) {
            $response = array(
                "status" => 200,
                "message" => "Solicitud de pago realizada"
            );
        } else {
            $response = array(
                "status" => 500,
                "message" => "Error al realizar la solicitud de pago"
            );
        }
        return $response;
    }

    public function puede_solicitar($monto, $plataforma)
    {
        $sql = "SELECT saldo FROM billetera_referidos WHERE id_plataforma = $plataforma";
        $saldo = $this->select($sql);
        $saldo = $saldo[0]['saldo'];
        if ($saldo >= $monto) {
            return true;
        }
        return false;
    }

    public function getCabecerasReferencias($plataforma)
    {
        $sql = "SELECT * FROM cabecera_cuenta_referidos WHERE id_plataforma = $plataforma";
        return $this->select($sql);
    }

    public function getGananciasTotal($plataforma)
    {
        $sql = "SELECT SUM(monto) as total FROM cabecera_cuenta_referidos WHERE id_plataforma = $plataforma";
        return $this->select($sql);
    }

    public function refiere($plataforma)
    {
        $sql = "SELECT * FROM plataformas WHERE token_referido = $plataforma";
        return $this->select($sql);
    }

    public function getSaldoActual($plataforma)
    {
        $sql = "SELECT saldo FROM billetera_referidos WHERE id_plataforma = $plataforma";
        return $this->select($sql);
    }

    public function esReferido($id)
    {
        $sql = "SELECT * FROM plataformas WHERE token_referido = $id";
        $result = $this->select($sql);
        if (empty($result)) {
            return false;
        }
        return true;
    }

    public function getTarifas()
    {
        $sql = "SELECT * FROM tarifa_referido";
        return $this->select($sql);
    }

    public function setTarifa($id_tarifa, $monto)
    {
        $sql = "UPDATE tarifa_referido SET tarifa = ? WHERE id_tarifa = ?";
        $data = array($monto, $id_tarifa);
        $response = $this->update($sql, $data);
        if ($response == 1) {
            $response = array(
                "status" => 200,
                "message" => "Tarifa actualizada"
            );
        } else {
            $response = array(
                "status" => 500,
                "message" => "Error al actualizar la tarifa"
            );
        }
        return $response;
    }
}
