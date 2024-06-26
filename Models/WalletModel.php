<?php
require_once 'PHPMailer/PHPMailer.php';
require_once 'PHPMailer/SMTP.php';
require_once 'PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;

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
        $pagos = $this->select("SELECT * FROM `pagos` WHERE tienda = '$tienda'");
        $abonos_registrados = $this->select("SELECT SUM(valor) as pagos  FROM `pagos` WHERE tienda = '$tienda' and recargo = 0");

        $billtera = $this->select("SELECT saldo FROM billeteras WHERE tienda = '$tienda'");
        $data = [
            'utilidad' => $datos_facturas_entregadas[0]['utilidad'] ?? 0,
            'ventas' => $datos_facturas_entregadas[0]['ventas'] ?? 0,
            'devoluciones' => $datos_facturas_devueltas[0]['devoluciones'] ?? 0,
            'guias_pendientes' => $guias_pendientes[0]['guias_pendientes'] ?? 0,
            'pagos' => $pagos ?? 0,
            'abonos_registrados' => $abonos_registrados[0]['pagos'] ?? 0,
            'saldo' => $billtera[0]['saldo'] ?? 0
        ];

        return $data;
    }

    public function obtenerFacturas($tienda, $filtro)
    {
        if ($filtro == 'pendientes') {
            $sql = "SELECT * FROM cabecera_cuenta_pagar WHERE tienda = '$tienda' and valor_pendiente != 0 ORDER BY `cabecera_cuenta_pagar`.`estado_guia` DESC, `cabecera_cuenta_pagar`.`fecha` DESC";
        } else if ($filtro == 'abonadas') {
            $sql = "SELECT * FROM cabecera_cuenta_pagar WHERE tienda = '$tienda' and valor_pendiente = 0 ORDER BY `cabecera_cuenta_pagar`.`estado_guia` DESC, `cabecera_cuenta_pagar`.`fecha` DESC";
        } else if ($filtro == 'devoluciones') {
            $sql = "SELECT * FROM cabecera_cuenta_pagar WHERE tienda = '$tienda' and estado_guia = 9 ORDER BY `cabecera_cuenta_pagar`.`estado_guia` DESC, `cabecera_cuenta_pagar`.`fecha` DESC";
        } else {
            $sql = "SELECT * FROM cabecera_cuenta_pagar WHERE tienda = '$tienda' ORDER BY `cabecera_cuenta_pagar`.`estado_guia` DESC, `cabecera_cuenta_pagar`.`fecha` DESC";
        }
        $response =  $this->select($sql);
        return $response;
    }

    public function abonarBilletera($id_cabecera, $valor, $usuario)
    {
        if ($valor == 0) {
            return;
        }
        $sql_select = "SELECT * FROM `cabecera_cuenta_pagar` WHERE id_cabecera = $id_cabecera";
        $response =  $this->select($sql_select);
        $saldo = $response[0]['valor_pendiente'];
        $guia = $response[0]['guia'];
        $costo = $response[0]['costo'];
        $proveedor = $response[0]['proveedor'];
        $tienda = $response[0]['tienda'];
        $numero_factura = $response[0]['numero_factura'];
        $fecha = $response[0]['fecha'];
        $cliente = $response[0]['cliente'];

        if ($saldo === 0) {
            return;
        }
        $sql_update = "UPDATE cabecera_cuenta_pagar set valor_pendiente = 0, visto = 1 WHERE id_cabecera = ?";
        $response =  $this->update($sql_update, array($id_cabecera));

        $sql = "UPDATE billeteras set saldo = saldo + $valor WHERE tienda = ?";
        $response =  $this->update($sql,  array($tienda));

        $id_billetera = $this->select("SELECT id_billetera FROM billeteras WHERE tienda = '$tienda'")[0]['id_billetera'];

        $sql = "INSERT INTO historial_billetera (`id_billetera`, `id_responsable`, `tipo`, `motivo`, `monto`, `fecha`) VALUES (?, ?, ?, ?, ?, ?)";
        if ($valor < 0) {
            $response =  $this->insert($sql, array($id_billetera, $usuario, "SALIDA", "Se desconto de la billetera la guia: $guia", $valor, date("Y-m-d H:i:s")));
        } else {

            $response =  $this->insert($sql, array($id_billetera, $usuario, "ENTRADA", "Se acredito a la billetera la guia: $guia", $valor, date("Y-m-d H:i:s")));
        }

        $id_plataforma = $this->select("SELECT id_plataforma FROM plataformas WHERE url_imporsuit = '$proveedor'")[0]['id_plataforma'];

        if ($proveedor != NULL || $proveedor != $tienda) {
            $full = $this->buscarFull($numero_factura, $id_plataforma);

            $sql = "INSERT INTO cabecera_cuenta_pagar (`tienda`, `numero_factura`, `guia`, `costo`, `monto_recibir`, `valor_pendiente`, `estado_guia`, `visto`, `full`, `fecha`, `cliente`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $response =  $this->insert($sql, array($proveedor, $numero_factura . '-P', $guia, $costo, $costo - $full, $costo - $full, 7, 0, $full, $fecha, $cliente));
        }

        $responses["status"] = 200;
        return $responses;
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

        $insert_pagos = "INSERT INTO pagos (`id_billetera`, `monto`, `fecha`, `id_responsable`) VALUES (?, ?, ?, ?)";

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
        $sql = "SELECT ROUND((SELECT SUM(monto_recibir) from cabecera_cuenta_pagar where tienda like '%$tienda%' and visto= 1 and estado_guia = 7 and monto_recibir) ,2)as venta , ROUND(SUM(monto_recibir),2) as utilidad, (SELECT ROUND(SUM(monto_recibir),2) from cabecera_cuenta_pagar where tienda like '%$tienda%' and estado_guia =9 and visto= 1)as devoluciones FROM `cabecera_cuenta_pagar` where tienda like '%$tienda%' and visto = 1;";
        $response =  $this->select($sql);
        return json_encode($response);
    }

    public function buscarFull($numero_factura, $id_plataforma)
    {
        $buscar_detalle = $this->select("SELECT * FROM detalle_fact_cot WHERE numero_factura = '$numero_factura'");
        $id_inventario = $buscar_detalle[0]['id_inventario'];

        $buscar_inventario = $this->select("SELECT * FROM inventario_bodegas WHERE id_inventario = '$id_inventario'");
        $id_producto = $buscar_inventario[0]['id_producto'];
        $id_bodega = $buscar_inventario[0]['bodega'];

        $buscar_producto = $this->select("SELECT * FROM productos WHERE id_producto = '$id_producto'");
        $id__producto = $buscar_producto[0]['id_plataforma'];

        $buscar_bodega = $this->select("SELECT * FROM bodega WHERE id = '$id_bodega'");
        $id__bodega = $buscar_bodega[0]['id_plataforma'];
        $valor_full = $buscar_bodega[0]['full_filme'];

        if ($id__producto == $id__bodega) {
            $full = 0;
        } else if ($id__producto == $id_plataforma) {
            $full =  $valor_full;
        } else {
            $full = 0;
        }

        return $full;
    }

    public function obtenerDatosBancarios($plataformas)
    {
        $sql = "SELECT * from datos_banco_usuarios  where id_plataforma = '$plataformas'";
        $response =  $this->select($sql);
        return $response;
    }

    public function obtenerDatosFacturacion($plataformas)
    {
        $sql = "SELECT * from facturacion  where id_plataforma = '$plataformas'";
        $response =  $this->select($sql);
        return $response;
    }

    public function guardarDatosBancarios($banco, $tipo_cuenta, $numero_cuenta, $nombre, $cedula, $correo, $telefono, $plataforma)
    {
        $response = $this->initialResponse();
        $id_matriz = $this->obtenerMatriz();
        $id_matriz = $id_matriz[0]['idmatriz'];
        $sql = "INSERT INTO datos_banco_usuarios (`banco`, `tipo_cuenta`, `numero_cuenta`, `nombre`, `cedula`, `correo`, `telefono`, `id_plataforma`, `id_matriz`) VALUES (?, ?, ?, ?, ?, ?, ?, ?,?)";
        $responses =  $this->insert($sql, array($banco, $tipo_cuenta, $numero_cuenta, $nombre, $cedula, $correo, $telefono, $plataforma, $id_matriz));
        if ($responses == 1) {
            $response["status"] = 200;
        } else {
            $response["status"] = 400;
            $response["message"] = $responses["message"];
        }
        return $response;
    }

    public function eliminarDatoBancario($id_cuenta)
    {
        $sql = "DELETE FROM datos_banco_usuarios WHERE id_cuenta = ?";
        $response =  $this->delete($sql, array($id_cuenta));
        if ($response == 1) {
            $responses["status"] = 200;
        } else {
            $responses["status"] = 400;
            $responses["message"] = $response["message"];
        }

        return $responses;
    }

    public function guardarDatosFacturacion($ruc, $razon, $direccion, $correo, $telefono, $plataforma)
    {
        $id_matriz = $this->obtenerMatriz();
        $id_matriz = $id_matriz[0]['idmatriz'];
        $sql = "INSERT INTO facturacion (`ruc`, `razon_social`, `direccion`, `correo`, `telefono`, `id_plataforma`, `id_matriz`) VALUES (?, ?, ?, ?, ?, ?,?)";
        $response =  $this->insert($sql, array($ruc, $razon, $direccion, $correo, $telefono, $plataforma, $id_matriz));
        if ($response == 1) {
            $responses["status"] = 200;
        } else {
            $responses["status"] = 400;
            $responses["message"] = $response["message"];
        }
        return $responses;
    }

    public function eliminarDatoFacturacion($id_facturacion)
    {
        $sql = "DELETE FROM facturacion WHERE id_facturacion = ?";
        $response =  $this->delete($sql, array($id_facturacion));
        if ($response == 1) {
            $responses["status"] = 200;
        } else {
            $responses["status"] = 400;
            $responses["message"] = $response["message"];
        }
        return $responses;
    }

    public function solicitarPago($id_cuenta, $valor, $fecha, $tienda)
    {
        $matriz = $this->obtenerMatriz();
        $matriz = $matriz[0]['idmatriz'];
        $sql = "INSERT INTO solicitudes_pago (`cantidad`, `id_cuenta`, `fecha`, `id_matriz`) VALUES (?, ?, ?, ?)";
        $response =  $this->insert($sql, array($valor, $id_cuenta, $fecha, $matriz));
        $update = "UPDATE billeteras set solicito = 1, valor_solicitud = $valor WHERE tienda = '$tienda'";
        $response2 =  $this->select($update);

        if ($response == 1) {
            $responses["status"] = 200;
            $responses["message"] = "Solicitud de pago enviada, espere a que sea aprobada dentro de las proximas 72 horas laborales";
        } else {
            $responses["status"] = 400;
            $responses["message"] = $response["message"];
        }
        return $responses;
    }

    public function subirImage($imagen)
    {
        $response = $this->initialResponse();
        $target_dir = "public/img/pagos/";
        $target_file = $target_dir . basename($imagen["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $check = getimagesize($imagen["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = 'El archivo no es una imagen';
            $uploadOk = 0;
        }
        if ($imagen["size"] > 500000) {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = 'El archivo es muy grande';
            $uploadOk = 0;
        }
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = 'Solo se permiten archivos JPG, JPEG, PNG';
            $uploadOk = 0;
        } else {
            if (move_uploaded_file($imagen["tmp_name"], $target_file)) {
                $response["dir"] = $target_file;
                $response['status'] = 1;
            } else {
                $response['status'] = 500;
                $response['title'] = 'Error';
                $response['message'] = 'Error al subir la imagen';
            }
        }
        return $response;
    }

    public function pagarFactura($valor, $documento, $forma_pago, $fecha, $imagen, $plataforma)
    {
        $matriz = $this->obtenerMatriz();
        $matriz = $matriz[0]['idmatriz'];
        $sql = "INSERT INTO pagos (`valor`, `numero_documento`, `forma_pago`, `fecha`, `imagen`, `id_matriz`, `id_plataforma`) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $response =  $this->insert($sql, array($valor, $documento, $forma_pago, $fecha, $imagen, $matriz, $plataforma));
        if ($response == 1) {
            $responses["status"] = 200;
            $sql = "SELECT * FROM plataformas where id_plataforma = '$plataforma'";
            $response =  $this->select($sql);
            $url_imporsuit = $response[0]['url_imporsuit'];
            $sql = "SELECT * FROM billeteras where tienda = '$url_imporsuit'";
            $response =  $this->select($sql);
            $update = "UPDATE billeteras set solicito = 0, valor_solicitado = 0 WHERE tienda = '$url_imporsuit'";
            $response =  $this->select($update);
        } else {
            $responses["status"] = 400;
            $responses["message"] = $response["message"];
        }
        return $responses;
    }

    public function obtenerHistorial($tienda)
    {
        $sql = "SELECT * FROM billeteras WHERE tienda = '$tienda'";
        $response =  $this->select($sql);
        if (!empty($response)) {

            $plataforma = $response[0]['id_billetera'];
            $sql = "SELECT hb.*, u.nombre_users as nombre FROM historial_billetera hb INNER join users u on u.id_users = hb.id_responsable WHERE hb.id_billetera = '$plataforma'";
            $response =  $this->select($sql);
        } else {
            $response = [];
        }
        return $response;
    }

    public function obtenerCuentas($plataforma)
    {
        $sql = "SELECT * FROM datos_banco_usuarios WHERE id_plataforma = '$plataforma'";
        $response =  $this->select($sql);
        return $response;
    }

    public function obtenerCorreo($id)
    {
        $sql = "SELECT email_users FROM users WHERE id_users = '$id'";
        $response =  $this->select($sql);
        return $response;
    }

    public function enviarMensaje($mensaje, $correo, $cantidad)
    {
        $datos_usuario = $this->select("SELECT * FROM datos_banco_usuarios WHERE correo = '$correo'");
        $nombre =  $datos_usuario[0]['nombre'];
        $banco =  $datos_usuario[0]['banco'];
        $cedula =  $datos_usuario[0]['cedula'];
        $numero_cuenta =  $datos_usuario[0]['numero_cuenta'];
        $tipo_cuenta =  $datos_usuario[0]['tipo_cuenta'];
        $telefono =  $datos_usuario[0]['telefono'];
        $tienda = $datos_usuario[0]['id_plataforma'];

        $tienda = $this->select("SELECT * FROM plataformas WHERE id_plataforma = '$tienda'");
        $tienda = $tienda[0]['url_imporsuit'];


        if ($mensaje == "solicitud") {
            require_once 'PHPMailer/Mail_pago.php';
            $mail = new PHPMailer();
            $mail->isSMTP();
            $mail->SMTPDebug = $smtp_debug;
            $mail->Host = $smtp_host;
            $mail->SMTPAuth = true;
            $mail->Username = $smtp_user;
            $mail->Password = $smtp_pass;
            $mail->Port = 465;
            $mail->SMTPSecure = $smtp_secure;
            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->setFrom($smtp_from, $smtp_from_name);
            $mail->addAddress($correo);
            $mail->Subject = 'Solicitud de Pago en Imporsuitpro';
            $mail->Body = $message_body2;
            // $this->crearSubdominio($tienda);

            if ($mail->send()) {
                //echo "Correo enviado";
            } else {
                //  echo "Error al enviar el correo: " . $mail->ErrorInfo;
            }
        } else if ($mensaje == "pago") {
            $mensaje = "Se ha realizado un pago";
        }
    }

    public function puedeSolicitar($tienda, $valor)
    {
        $sql = "SELECT * FROM billeteras WHERE tienda = '$tienda'";
        $response =  $this->select($sql);
        $saldo = $response[0]['saldo'];
        if ($saldo <= 0) {
            return false;
        }
        if ($saldo < $valor) {
            return false;
        }
        $solicito = $response[0]['solicito'];
        if ($solicito == 1) {
            return false;
        }
        return true;
    }
}
