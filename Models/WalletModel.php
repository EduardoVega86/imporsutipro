<?php

class WalletModel extends Query
{

    public function obtenerTiendas()
    {
        $datos_tienda = $this->select("-- Crear una lista de todas las tiendas
                                        WITH todas_tiendas AS (
                                            SELECT DISTINCT tienda
                                            FROM cabecera_cuenta_pagar
                                        )

                                        -- Contar cuántos registros tienen visto = 0 por tienda
                                        , count_visto_0 AS (
                                            SELECT tienda, COUNT(*) AS count_visto_0
                                            FROM cabecera_cuenta_pagar
                                            WHERE visto = 0
                                            GROUP BY tienda
                                        )

                                        -- Calcular la suma de monto_recibir y total_venta por tienda cuando visto = 1
                                        , sum_visto_1 AS (
                                            SELECT 
                                                tienda, 
                                                ROUND(SUM(monto_recibir), 2) AS utilidad, 
                                                ROUND(SUM(total_venta), 2) AS ventas
                                            FROM cabecera_cuenta_pagar
                                            WHERE visto = 1
                                            GROUP BY tienda
                                        )

                                        -- Combinar todos los resultados y ordenar por count_visto_0 de mayor a menor
                                        SELECT 
                                            t.tienda,
                                            COALESCE(cv0.count_visto_0, 0) AS count_visto_0,
                                            COALESCE(sv1.utilidad, 0) AS utilidad,
                                            COALESCE(sv1.ventas, 0) AS ventas
                                        FROM 
                                            todas_tiendas t
                                        LEFT JOIN 
                                            count_visto_0 cv0 ON t.tienda = cv0.tienda
                                        LEFT JOIN 
                                            sum_visto_1 sv1 ON t.tienda = sv1.tienda
                                        ORDER BY 
                                            count_visto_0 DESC;");
        return json_encode($datos_tienda);
    }

    public function obtenerDatos($tienda)
    {
        $datos_facturas_entregadas = $this->select("SELECT SUM(monto_recibir) as utilidad, sum(total_ventas) as ventas FROM cabecera_cuenta_pagar WHERE tienda = '$tienda' and visto = 1");
        $datos_facturas_devueltas = $this->select("SELECT SUM(monto_recibir) as devoluciones FROM cabecera_cuenta_pagar WHERE tienda = '$tienda' and visto = 1 and estado_guia = 9");
        $guias_pendientes = $this->select("SELECT COUNT(*) as guias_pendientes FROM cabecera_cuenta_pagar WHERE tienda = '$tienda' and visto = 0");
        $data = [
            'utilidad' => $datos_facturas_entregadas[0]['utilidad'],
            'ventas' => $datos_facturas_entregadas[0]['ventas'],
            'devoluciones' => $datos_facturas_devueltas[0]['devoluciones'],
            'guias_pendientes' => $guias_pendientes[0]['guias_pendientes']
        ];

        return json_encode($data);
    }


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
        $sql = "SELECT * FROM billeteras WHERE tienda = '$tienda'";
        $response =  $this->select($sql);
        return $response;
    }

    public function crearBilletera($tienda)
    {
        $sql = "INSERT INTO billeteras (`tienda`, `saldo`) VALUES (?, ?)";
        $response =  $this->insert($sql, array($tienda, 0));
        $responses["status"] = 200;
        return json_encode($responses);
    }

    public function widget($tienda)
    {
        $sql = "SELECT ROUND((SELECT SUM(monto_recibir) from cabecera_cuenta_pagar where tienda like '%$tienda%' and visto= 1 and estado_guia = 7 >0 and monto_recibir) ,2)as venta , ROUND(SUM(monto_recibir),2) as utilidad, (SELECT ROUND(SUM(monto_recibir),2) from cabecera_cuenta_pagar where tienda like '%$tienda%' and estado_guia =9 and visto= 1)as devoluciones FROM `cabecera_cuenta_pagar` where tienda like '%$tienda%' and visto = 1;";
        $response =  $this->select($sql);
        return json_encode($response);
    }
}
