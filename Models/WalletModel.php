<?php

class WalletModel extends Query
{
    public function abonarBilletera($id_cabecera, $valor, $tienda, $usuario)
    {
        if ($valor == 0) {
            return;
        }
        $sql_select = "SELECT * FROM cabecera_cuenta_pagar WHERE id_cabecera = ?";
        $response =  $this->select($sql_select);
        $saldo = $response[0]['valor_pendiente'];
        $guia = $response[0]['guia'];
        if ($saldo === 0) {
            return;
        }
        $sql_update = "UPDATE cabecera_cuenta_pagar set valor_pendiente = 0 and visto = 1 WHERE id_cabecera = ?";
        $response =  $this->update($sql_update, array($id_cabecera));

        $sql = "UPDATE billeteras set saldo = saldo + $valor WHERE tienda = ?";
        $response =  $this->update($sql,  array($tienda));

        $id_billetera = $this->select("SELECT id_billetera FROM billeteras WHERE tienda = $tienda")[0]['id_billetera'];

        $sql = "INSERT INTO historial_billetera (`id_billetera`, `id_responsable`, `tipo`, `motivo`, `monto`, `fecha`) VALUES (?, ?, ?, ?, ?, ?)";
        if ($valor < 0) {
            $response =  $this->insert($sql, array($id_billetera, $usuario, "SALIDA", "Se desconto de la billetera la guia: $guia", $valor, date("Y-m-d H:i:s")));
        } else {

            $response =  $this->insert($sql, array($id_billetera, $usuario, "ENTRADA", "Se acredito a la billetera la guia: $guia", $valor, date("Y-m-d H:i:s")));
        }
        $responses["status"] = 200;
        return json_encode($responses);
    }

    public function reversarAbono($id_cabecera, $valor, $tienda, $usuario)
    {
        $sql_select = "SELECT * FROM cabecera_cuenta_pagar WHERE id_cabecera = ?";
        $response =  $this->select($sql_select);
        $saldo = $response[0]['valor_pendiente'];
        $guia = $response[0]['guia'];
        $monto_recibir = $response[0]['monto_recibir'];

        $sql_update = "UPDATE cabecera_cuenta_pagar set valor_pendiente = $monto_recibir and visto = 0 WHERE id_cabecera = ?";
        $response =  $this->update($sql_update, array($id_cabecera));

        $sql = "UPDATE billeteras set saldo = saldo - $valor WHERE tienda = ?";
        $response =  $this->update($sql,  array($tienda));

        $id_billetera = $this->select("SELECT id_billetera FROM billeteras WHERE tienda = $tienda")[0]['id_billetera'];

        $sql = "INSERT INTO historial_billetera (`id_billetera`, `id_responsable`, `tipo`, `motivo`, `monto`, `fecha`) VALUES (?, ?, ?, ?, ?, ?)";
        if ($valor < 0) {
            $response =  $this->insert($sql, array($id_billetera, $usuario, "SALIDA", "Se desconto de la billetera la guia: $guia", $valor, date("Y-m-d H:i:s")));
        } else {

            $response =  $this->insert($sql, array($id_billetera, $usuario, "ENTRADA", "Se acredito a la billetera la guia: $guia", $valor, date("Y-m-d H:i:s")));
        }
        $responses["status"] = 200;
        return json_encode($responses);
    }

    public function guiasPendientes($tienda)
    {
        $sql = "SELECT * FROM cabecera_cuenta_pagar WHERE tienda = '$tienda' and valor_pendiente > 0";
        $response =  $this->select($sql);
        return json_encode($response);
    }

    public function guiasAbonadas($tienda)
    {
        $sql = "SELECT * FROM cabecera_cuenta_pagar WHERE tienda = '$tienda' and valor_pendiente = 0";
        $response =  $this->select($sql);
        return json_encode($response);
    }

    public function pagar($monto, $tienda, $usuario)
    {
        $sql = "UPDATE billeteras set saldo = saldo - $monto WHERE tienda = ?";
        $response =  $this->update($sql,  array($tienda));

        $id_billetera = $this->select("SELECT id_billetera FROM billeteras WHERE tienda = $tienda")[0]['id_billetera'];

        $sql = "INSERT INTO historial_billetera (`id_billetera`, `id_responsable`, `tipo`, `motivo`, `monto`, `fecha`) VALUES (?, ?, ?, ?, ?, ?)";
        $response =  $this->insert($sql, array($id_billetera, $usuario, "SALIDA", "Se pago de la billetera el monto: $monto", $monto, date("Y-m-d H:i:s")));
        $responses["status"] = 200;
        return json_encode($responses);
    }

    public function saldo($tienda)
    {
        $sql = "SELECT saldo FROM billeteras WHERE tienda = $tienda";
        $response =  $this->select($sql);
        return json_encode($response);
    }

    public function existeTienda($tienda)
    {
        $sql = "SELECT * FROM billeteras WHERE tienda = $tienda";
        $response =  $this->select($sql);
        return json_encode($response);
    }

    public function crearBilletera($tienda)
    {
        $sql = "INSERT INTO billeteras (`tienda`, `saldo`) VALUES (?, ?)";
        $response =  $this->insert($sql, array($tienda, 0));
        $responses["status"] = 200;
        return json_encode($responses);
    }
}
