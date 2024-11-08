<?php
require_once 'PHPMailer/PHPMailer.php';
require_once 'PHPMailer/SMTP.php';
require_once 'PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use React\EventLoop\Factory;
use React\Http\Browser;
use Psr\Http\Message\ResponseInterface;

class WalletModel extends Query
{
    public function obtenerTiendas()
    {
        $id_matriz = $this->obtenerMatriz();
        $id_matriz = $id_matriz[0]['idmatriz'];
        $datos_tienda = $this->select("SELECT ccp.id_plataforma, p.url_imporsuit as tienda, (SELECT COUNT(*) FROM cabecera_cuenta_pagar ccp2 WHERE ccp2.id_plataforma = ccp.id_plataforma AND ccp2.estado_guia IN (7, 9) AND ccp2.visto = 0 ) AS count_visto_0, ROUND(SUM(CASE WHEN ccp.estado_guia IN (7, 9) AND ccp.visto = 1 THEN ccp.total_venta ELSE 0 END), 2) AS ventas, ROUND(SUM(CASE WHEN ccp.estado_guia IN (7, 9) AND ccp.visto = 1 THEN ccp.monto_recibir ELSE 0 END), 2) AS utilidad FROM cabecera_cuenta_pagar ccp INNER JOIN plataformas p ON p.id_plataforma = ccp.id_plataforma GROUP BY ccp.id_plataforma, p.url_imporsuit ORDER BY count_visto_0 DESC;");

        return json_encode($datos_tienda);
    }

    public function obtenerCabecera($id_cabecera)
    {
        $sql = "SELECT * FROM cabecera_cuenta_pagar WHERE id_cabecera = $id_cabecera";
        $response =  $this->select($sql);
        return $response;
    }

    public function editar($id_cabecera, $total_venta, $precio_envio, $full, $costo)
    {
        $monto_recibir = $total_venta - $costo - $full - $precio_envio;
        $sql = "UPDATE cabecera_cuenta_pagar set total_venta = ?, precio_envio = ?, full = ?, costo = ?, monto_recibir = ?, valor_pendiente = ? WHERE id_cabecera = ?";
        $response =  $this->update($sql, array($total_venta, $precio_envio, $full, $costo, $monto_recibir, $monto_recibir, $id_cabecera));

        $sql = "SELECT * FROM cabecera_cuenta_pagar WHERE id_cabecera = $id_cabecera";
        $response =  $this->select($sql);
        $numero_factura = $response[0]['numero_factura'];

        $sql = "UPDATE facturas_cot set costo_flete = ? WHERE numero_factura = ?";
        $response =  $this->update($sql, array($precio_envio, $numero_factura));

        if ($response == 1) {
            $responses["status"] = 200;
        } else if ($response == 0) {
            $responses["status"] = 201;
            $responses["message"] = "No se realizaron cambios";
        } else {
            $responses["status"] = 400;
            $responses["message"] = $response["message"];
        }
        return $responses;
    }

    public function eliminar($id_cabecera)
    {
        $sql = "DELETE FROM cabecera_cuenta_pagar WHERE id_cabecera = ?";
        $response =  $this->delete($sql, array($id_cabecera));
        if ($response == 1) {
            $responses["status"] = 200;
        } else {
            $responses["status"] = 400;
            $responses["message"] = $response["message"];
        }
        return $responses;
    }

    public function cambiarEstado($id_cabecera, $estado)
    {
        $sql = "UPDATE cabecera_cuenta_pagar set estado_guia = ? WHERE id_cabecera = ?";
        $response =  $this->update($sql, array($estado, $id_cabecera));

        $sql = "SELECT * FROM cabecera_cuenta_pagar WHERE id_cabecera = $id_cabecera";
        $response =  $this->select($sql);
        $numero_factura = $response[0]['numero_factura'];
        $numero_guia = $response[0]['guia'];
        if ($estado == 7) {

            if (str_contains($numero_guia, 'IMP') || str_contains($numero_guia, 'MKP'))
                $estados = 7;
            else if (str_contains($numero_guia, 'SPD') || str_contains($numero_guia, 'MKL'))
                $estados = 7;
            else if (is_numeric($numero_guia))
                $estados = 400;
            else if (str_contains($numero_guia, 'I000'))
                $estados = 7;
        } else if ($estado == 9) {
            if (str_contains($numero_guia, 'IMP') || str_contains($numero_guia, 'MKP'))
                $estados = 9;
            else if (str_contains($numero_guia, 'SPD') || str_contains($numero_guia, 'MKL'))
                $estados = 9;
            else if (is_numeric($numero_guia))
                $estados = 500;
            else if (str_contains($numero_guia, 'I000'))
                $estados = 8;
        }
        $sql = "UPDATE facturas_cot set estado_guia_sistema = ? WHERE numero_factura = ?";
        $response =  $this->update($sql, array($estados, $numero_factura));



        if ($response == 1) {
            $responses["status"] = 200;
        } else {
            $responses["status"] = 400;
            $responses["message"] = $response["message"];
        }
        return $responses;
    }

    public function obtenerDatos($tienda)
    {
        // Consultas SQL
        $datos_facturas_entregadas = $this->select("SELECT ROUND(SUM(monto_recibir),2) as utilidad, ROUND(SUM(total_venta),2) as ventas FROM cabecera_cuenta_pagar WHERE id_plataforma = '$tienda' and visto = 1");
        $datos_facturas_devueltas = $this->select("SELECT ROUND(SUM(monto_recibir),2) as devoluciones FROM cabecera_cuenta_pagar WHERE id_plataforma = '$tienda' and visto = 1 and estado_guia = 9");
        $guias_pendientes = $this->select("SELECT COUNT(*) as guias_pendientes FROM cabecera_cuenta_pagar WHERE id_plataforma = '$tienda' and visto = 0");
        $pagos = $this->select("SELECT * FROM `pagos` WHERE id_plataforma = '$tienda'");
        $abonos_registrados = $this->select("SELECT ROUND(SUM(valor),2) as pagos FROM `pagos` WHERE id_plataforma = '$tienda' and recargo = 0");
        $plataforma_url = $this->select("SELECT url_imporsuit FROM plataformas WHERE id_plataforma = '$tienda'");
        $billtera = $this->select("SELECT ROUND(saldo,2) as saldo FROM billeteras WHERE id_plataforma = '$tienda'");

        // Garantizar que los valores sean numéricos antes de hacer las operaciones
        $utilidad = round((float)($datos_facturas_entregadas[0]['utilidad'] ?? 0), 2);
        $pagos_registrados = round((float)($abonos_registrados[0]['pagos'] ?? 0), 2);
        $saldo_billetera = round((float)($billtera[0]['saldo'] ?? 0), 2);

        // Realizar la verificación correctamente
        $verificar = round($utilidad - $pagos_registrados, 2) == $saldo_billetera;

        // Armar el array de datos
        $data = [
            'utilidad' => $utilidad,
            'ventas' => round($datos_facturas_entregadas[0]['ventas'] ?? 0, 2),
            'devoluciones' => round($datos_facturas_devueltas[0]['devoluciones'] ?? 0, 2),
            'guias_pendientes' => $guias_pendientes[0]['guias_pendientes'] ?? 0,
            'pagos' => $pagos ?? [],
            'abonos_registrados' => $pagos_registrados,
            'saldo' => $saldo_billetera,
            'plataforma_url' => $plataforma_url[0]['url_imporsuit'] ?? '',
            'verificar' => $verificar,
            'verificarS' => round($utilidad - $pagos_registrados, 2),
            'verificarB' => $saldo_billetera
        ];

        return $data;
    }

    public function obtenerFacturas($id_plataforma, $filtro)
    {
        // Definir la lógica común de trayecto
        $trayecto_case = "
            CASE
                -- LAAR (IMP o MKP)
                WHEN ccp.guia LIKE 'IMP%' OR ccp.guia LIKE 'MKP%' THEN cc.trayecto_laar
                
                -- Servientrega (solo números)
                WHEN ccp.guia REGEXP '^[0-9]+$' THEN cc.trayecto_servientrega
                
                -- Gintracom (comienza con I000)
                WHEN ccp.guia LIKE 'I000%' THEN cc.trayecto_gintracom
                
                -- Speed/Merkalogistic (SPD o MKL)
                WHEN ccp.guia LIKE 'SPD%' OR ccp.guia LIKE 'MKL%' THEN 
                    CASE
                        -- Si la ciudad es QUITO
                        WHEN cc.ciudad = 'QUITO' THEN 'TL'
                        -- Si la ciudad es cualquier otra
                        ELSE 'TS'
                    END
                
                -- Caso predeterminado si no coincide con ninguno de los anteriores
                ELSE 'Desconocido'
            END AS trayecto
        ";

        // Eliminar los sufijos -P y -F en numero_factura antes de comparar
        $factura_sin_sufijo = "REPLACE(REPLACE(ccp.numero_factura, '-P', ''), '-F', '')";

        if ($filtro == 'pendientes') {
            $sql = "SELECT 
                ccp.*, 
                fc.ciudad_cot, 
                cc.ciudad, 
                $trayecto_case
            FROM 
                cabecera_cuenta_pagar ccp 
            INNER JOIN 
                facturas_cot fc ON fc.numero_factura = $factura_sin_sufijo 
            INNER JOIN 
                ciudad_cotizacion cc ON cc.id_cotizacion = fc.ciudad_cot 
            WHERE 
                ccp.id_plataforma = '$id_plataforma' 
                AND ccp.valor_pendiente != 0 
            ORDER BY 
                FIELD(ccp.estado_guia, 9,7) DESC, 
                ccp.estado_guia DESC, 
                ccp.fecha DESC;";
        } else if ($filtro == 'abonadas') {
            $sql = "SELECT 
                ccp.*, 
                fc.ciudad_cot, 
                cc.ciudad, 
                $trayecto_case
            FROM 
                cabecera_cuenta_pagar ccp 
            INNER JOIN 
                facturas_cot fc ON fc.numero_factura = $factura_sin_sufijo 
            INNER JOIN 
                ciudad_cotizacion cc ON cc.id_cotizacion = fc.ciudad_cot 
            WHERE 
                ccp.id_plataforma = '$id_plataforma' 
                AND ccp.valor_pendiente = 0 
            ORDER BY 
                ccp.estado_guia DESC, 
                ccp.fecha DESC;";
        } else if ($filtro == 'devoluciones') {
            $sql = "SELECT 
                ccp.*, 
                fc.ciudad_cot, 
                cc.ciudad, 
                $trayecto_case
            FROM 
                cabecera_cuenta_pagar ccp 
            INNER JOIN 
                facturas_cot fc ON fc.numero_factura = $factura_sin_sufijo 
            INNER JOIN 
                ciudad_cotizacion cc ON cc.id_cotizacion = fc.ciudad_cot 
            WHERE 
                ccp.id_plataforma = '$id_plataforma' 
                AND ccp.estado_guia = 9 
            ORDER BY 
                ccp.estado_guia DESC, 
                ccp.fecha DESC;";
        } else {
            $sql = "SELECT 
                ccp.*, 
                fc.ciudad_cot, 
                cc.ciudad, 
                $trayecto_case
            FROM 
                cabecera_cuenta_pagar ccp 
            INNER JOIN 
                facturas_cot fc ON fc.numero_factura = $factura_sin_sufijo 
            INNER JOIN 
                ciudad_cotizacion cc ON cc.id_cotizacion = fc.ciudad_cot 
            WHERE 
                ccp.id_plataforma = '$id_plataforma' 
            ORDER BY 
                ccp.estado_guia DESC, 
                ccp.fecha DESC;";
        }

        $response = $this->select($sql);
        return $response;
    }

    public function abonarBilletera($id_cabecera, $valor, $usuario)
    {
        if ($valor == 0) {
            return;
        }

        $cabecera = $this->getCabeceraCuentaPagar($id_cabecera);
        if (!$cabecera) {
            return $this->errorResponse('Cabecera no encontrada');
        }

        $saldo = $cabecera['valor_pendiente'];
        if ($saldo == 0) {
            return $this->errorResponse('No hay saldo pendiente');
        }

        // Verificar el estado de la factura
        $cod_factura = $this->esCodFactura($cabecera['numero_factura']);

        if ($this->shouldAbortTransaction($cabecera['estado_guia'], $valor, $cod_factura)) {
            return $this->errorResponse('Condición de guía inválida para la transacción');
        }

        // Verificar el estado de la factura
        $isCodFactura = $this->esCodFactura($cabecera['numero_factura']);
        if ($isCodFactura && $cabecera['estado_guia'] == 7 && $valor < 0 && $isCodFactura == 1) {
            return $this->errorResponse('La guía no permite transacciones negativas');
        }

        // Actualizar el saldo de la cabecera y billetera
        $this->actualizarCabecera($id_cabecera);
        $this->actualizarBilletera($cabecera['id_plataforma'], $valor);

        // Obtener la billetera y registrar en el historial
        $id_billetera = $this->obtenerIdBilletera($cabecera['id_plataforma']);
        $this->registrarHistorialBilletera($id_billetera, $usuario, $valor, $cabecera['guia']);

        // Si la guía está en estado específico, realizar operaciones adicionales
        if ($cabecera['estado_guia'] == 7 || $cabecera['estado_guia'] == 9) {
            $this->manejarGuiaCompleta($cabecera, $usuario, $valor);
        }

        return $this->successResponse();
    }

    private function getCabeceraCuentaPagar($id_cabecera)
    {
        $sql = "SELECT * FROM cabecera_cuenta_pagar WHERE id_cabecera = $id_cabecera";
        $response = $this->select($sql);
        return $response[0] ?? null;
    }

    private function esCodFactura($numero_factura)
    {
        $sql = "SELECT cod FROM facturas_cot WHERE numero_factura = '$numero_factura'";
        $response = $this->select($sql);
        return $response[0]['cod'] ?? null;
    }

    private function shouldAbortTransaction($estado_guia, $valor, $cod_factura)
    {
        // Caso 3: Si la guía está en estado 7, el valor es negativo y no tiene cod_factura o es diferente de 1, permitir.
        if ($estado_guia == 7 && $valor < 0 && $cod_factura != 1) {

            return false; // No abortar, se permite la transacción
        }
        // Caso 1: Si la guía está en estado 9 y el valor es positivo, abortar.
        if ($estado_guia == 9 && $valor > 0) {
            return true; // Abortar transacción
        }
        // Caso 2: Si la guía está en estado 7 y el valor es negativo, abortar.
        if ($estado_guia == 7 && $valor < 0) {
            return true; // Abortar transacción
        }
        return false; // No abortar en ningún otro caso
    }

    private function actualizarCabecera($id_cabecera)
    {
        $sql = "UPDATE cabecera_cuenta_pagar SET valor_pendiente = 0, visto = 1 WHERE id_cabecera = ?";
        $this->update($sql, [$id_cabecera]);
    }

    private function actualizarBilletera($id_plataforma, $valor)
    {
        $sql = "UPDATE billeteras SET saldo = saldo + ? WHERE id_plataforma = ?";
        $this->update($sql, [$valor, $id_plataforma]);
    }

    private function obtenerIdBilletera($id_plataforma)
    {
        $sql = "SELECT id_billetera FROM billeteras WHERE id_plataforma = '$id_plataforma'";
        $response = $this->select($sql);
        return $response[0]['id_billetera'] ?? null;
    }

    private function registrarHistorialBilletera($id_billetera, $usuario, $valor, $guia)
    {
        $tipo = $valor < 0 ? 'SALIDA' : 'ENTRADA';
        $motivo = $valor < 0 ? "Se descontó de la billetera la guía: $guia" : "Se acreditó a la billetera la guía: $guia";

        $sql = "INSERT INTO historial_billetera (id_billetera, id_responsable, tipo, motivo, monto, fecha) 
            VALUES (?, ?, ?, ?, ?, ?)";
        $this->insert($sql, [$id_billetera, $usuario, $tipo, $motivo, $valor, date("Y-m-d H:i:s")]);
    }

    private function manejarGuiaCompleta($cabecera, $usuario, $valor)
    {
        // Abono al proveedor si corresponde
        if ($cabecera['id_proveedor'] && $cabecera['id_proveedor'] != $cabecera['id_plataforma'] && $cabecera['estado_guia'] == 7) {
            $this->manejarProveedor($cabecera, $usuario, $valor);
        }

        // Abono al fullfilment si corresponde
        if ($cabecera['full'] > 0) {
            $this->manejarFullfilment($cabecera, $usuario);
        }
    }

    private function manejarProveedor($cabecera, $usuario, $valor)
    {
        $id_proveedor = $cabecera['id_proveedor'];

        // Verificar si la billetera del proveedor existe, de lo contrario crearla
        if (!$this->existeBilletera($id_proveedor)) {
            $this->crearBilletera($id_proveedor);
        }

        // Crear una nueva cabecera para el proveedor
        $this->crearCabeceraProveedor($cabecera, $id_proveedor);

        // Registrar historial del abono en la billetera del proveedor
        $id_billetera_proveedor = $this->obtenerIdBilletera($id_proveedor);
        $this->registrarHistorialBilletera($id_billetera_proveedor, $usuario, $cabecera['costo'], $cabecera['guia']);

        //obtener la  cabecera del proveedor
        $cabecera_proveedor = $this->select("SELECT * FROM cabecera_cuenta_pagar WHERE id_plataforma = '$id_proveedor' AND numero_factura = '$cabecera[numero_factura]-P'")[0];

        if ($cabecera_proveedor['full'] > 0) {
            $this->manejarFullfilment($cabecera_proveedor, $usuario);
        }

        // Actualizar saldo en la billetera del proveedor
        $this->actualizarBilletera($id_proveedor, $cabecera['costo']);
    }

    private function manejarFullfilment($cabecera, $usuario)
    {
        $id_full = $cabecera['id_full'];

        // Verificar si la billetera del fullfilment existe, de lo contrario crearla
        if (!$this->existeBilletera($id_full)) {
            $this->crearBilletera($id_full);
        }

        // Registrar historial del abono en la billetera del fullfilment
        $id_billetera_full = $this->obtenerIdBilletera($id_full);
        $this->registrarHistorialBilletera($id_billetera_full, $usuario, $cabecera['full'], $cabecera['guia']);

        // Actualizar saldo en la billetera del fullfilment
        $this->actualizarBilletera($id_full, $cabecera['full']);

        // Crear una nueva cabecera para el fullfilment
        $this->crearCabeceraFull($cabecera, $id_full);
    }
    private function crearCabeceraFull($cabecera, $id_full)
    {
        $url_tienda = $this->obtenerEnlace($id_full);
        $sql = "INSERT INTO cabecera_cuenta_pagar 
                (tienda, numero_factura, guia, costo, monto_recibir, valor_pendiente, estado_guia, visto, full, fecha, cliente, id_plataforma, id_matriz) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $this->insert($sql, [
            $url_tienda,
            $cabecera['numero_factura'] . '-F',
            $cabecera['guia'],
            $cabecera['full'],
            $cabecera['full'],
            0,
            7,
            1,
            0,
            $cabecera['fecha'],
            $cabecera['cliente'],
            $id_full,
            $cabecera['id_matriz']
        ]);
    }

    private function obtenerEnlace($id_plataforma)
    {
        $sql = "SELECT url_imporsuit FROM plataformas WHERE id_plataforma = '$id_plataforma'";
        $response = $this->select($sql);
        return $response[0]['url_imporsuit'];
    }

    private function existeBilletera($id_plataforma)
    {
        $sql = "SELECT COUNT(*) as count FROM billeteras WHERE id_plataforma = '$id_plataforma'";
        $response = $this->select($sql);
        return $response[0]['count'] > 0;
    }

    private function crearCabeceraProveedor($cabecera, $id_proveedor)
    {
        $isFulfilment = $this->buscarFull($cabecera['numero_factura'], $id_proveedor);
        $full = $isFulfilment > 0 ? $isFulfilment : 0;
        $id_full = $full > 0 ? $cabecera['id_full'] : NULL;
        $sql = "INSERT INTO cabecera_cuenta_pagar 
            (tienda, numero_factura, guia, costo, monto_recibir, valor_pendiente, estado_guia, visto, full, fecha, cliente, id_plataforma, id_matriz, id_full) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $this->insert($sql, [
            $cabecera['proveedor'],
            $cabecera['numero_factura'] . '-P',
            $cabecera['guia'],
            $cabecera['costo'],
            $cabecera['costo'],
            0,
            7,
            1,
            $full,
            $cabecera['fecha'],
            $cabecera['cliente'],
            $cabecera['id_proveedor'],
            $cabecera['id_matriz'],
            $id_full
        ]);

        if ($full > 0) {
            $this->actualizarCabecera($cabecera['id_cabecera']);
        }
    }

    private function registrarHistorialProveedor($cabecera, $usuario, $valor)
    {
        $id_billetera = $this->obtenerIdBilletera($cabecera['id_proveedor']);
        $this->registrarHistorialBilletera($id_billetera, $usuario, $cabecera['costo'], $cabecera['guia']);
    }

    private function errorResponse($message)
    {
        return ["status" => 500, "message" => $message];
    }

    private function successResponse()
    {
        return ["status" => 200, "message" => "Transacción exitosa"];
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
        $sql = "SELECT * FROM billeteras WHERE id_plataforma = '$tienda'";
        $response =  $this->select($sql);
        return $response;
    }

    public function crearBilletera($tienda)
    {
        $url_imporsuit = $this->select("SELECT url_imporsuit FROM plataformas WHERE id_plataforma = '$tienda'");
        $url_imporsuit = $url_imporsuit[0]['url_imporsuit'];

        $sql = "INSERT INTO billeteras (`tienda`, `saldo`, `id_plataforma`) VALUES (?, ?, ?)";
        $response =  $this->insert($sql, array($url_imporsuit, 0, $tienda));
        return json_encode($response);
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
        $cantidad = $buscar_detalle[0]['cantidad'];

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

    public function solicitarPago($id_cuenta, $valor, $plataforma, $otro, $usuario)
    {
        if ($id_cuenta == NULL || empty($id_cuenta)) {
            $responses["status"] = 400;
            $responses["message"] = "No se ha seleccionado una cuenta para realizar la solicitud";
            return $responses;
        }

        $tipoSolicitud = $otro == 0 ? "PRIMARIO" : "SECUNDARIO";
        $this->historialSolicitud($tipoSolicitud, $valor, $usuario, $id_cuenta, $plataforma);

        $sql = "INSERT INTO solicitudes_pago (`cantidad`, `id_cuenta`, `id_plataforma`, `otro`) VALUES (?, ?, ?, ?)";
        $response =  $this->insert($sql, array($valor, $id_cuenta, $plataforma, $otro));
        $update = "UPDATE billeteras set solicito = 1, valor_solicitud = $valor WHERE id_plataforma = '$plataforma'";
        $response2 =  $this->select($update);

        if ($response == 1) {
            $responses["status"] = 200;
            $responses["message"] = "Solicitud de pago enviada, espere a que sea aprobada dentro de las proximas 72 horas laborales";
        } elseif ($response == 0) {
            $responses["status"] = 400;
            $responses["message"] = "Tuviemos un problema al enviar la solicitud, por favor toma captura de este mensaje y envialo a soporte";
        } else {
            $responses["status"] = 500;
            $responses["message"] = $response["message"];
        }
        return $responses;
    }

    public function obtenerCodigoVerificacion($codigo, $plataforma)
    {
        $sql = "SELECT * FROM billeteras WHERE id_plataforma = '$plataforma' and codigo = '$codigo'";
        $response =  $this->select($sql);
        $fecha = date("Y-m-d H:i:s");
        if (!empty($response)) {
            $fecha_codigo = $response[0]['fecha_codigo'];
            $fecha_codigo = strtotime($fecha_codigo);
            $fecha = strtotime($fecha);
            $diferencia = $fecha - $fecha_codigo;
            if ($diferencia > 1800) {
                $responses["status"] = 400;
                $responses["message"] = "El código ha expirado";
            } else {
                $responses["status"] = 200;
            }
        } else {
            $responses["status"] = 400;
            $responses["message"] = "El código es incorrecto";
        }
        return $responses;
    }

    public function generarCodigoVerificacion($plataforma)
    {
        // generar codigo de verificacion de 3 digitos un guion y 3 digitos
        $codigo = rand(100, 999) . "-" . rand(100, 999);
        $sql = "UPDATE billeteras set codigo = '$codigo', fecha_codigo = now() WHERE id_plataforma = '$plataforma'";
        $response =  $this->select($sql);
        // enviar codigo de verificacion al correo
        $correo = $this->obtenerCorreo2($plataforma);
        //$correo = $correo[0]['correo'];
        $asunto = "Código de verificación";
        $mensaje = "Su código de verificación es: $codigo";
        $enviar = $this->enviarCorreoVerificacion($correo, $asunto, $mensaje);
        if ($enviar == 1) {
            $responses["status"] = 200;
            $responses["message"] = "Se ha enviado un código de verificación a su correo";
        } else {
            $responses["status"] = 400;
            $responses["message"] = "Tuviemos un problema al enviar el código de verificación, por favor toma captura de este mensaje y envialo a soporte";
        }
        return $responses;
    }

    public function obtenerCorreo2($id_plataforma)
    {
        $sql = "SELECT email from plataformas where id_plataforma = '$id_plataforma'";
        $response =  $this->select($sql);
        $response = $response[0]['email'];
        return $response;
    }

    public function enviarCorreoVerificacion($correo, $asunto, $mensaje)
    {
        require_once 'PHPMailer/Mail_codigo.php';
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->SMTPDebug = $smtp_debug;
        $mail->Host = $smtp_host;
        $mail->SMTPAuth = true;
        $mail->Username = $smtp_user;
        $mail->Password = $smtp_pass;
        $mail->Port = 465;
        $mail->SMTPSecure = 'ssl';
        $mail->CharSet = 'UTF-8'; // Establecer el charset a UTF-8
        $mail->setFrom($smtp_user, MARCA);
        $mail->addAddress($correo);
        $mail->isHTML(true);
        $mail->Subject = $asunto;
        $mail->Body = $mensaje;
        $mail->AltBody = $mensaje;

        if ($mail->send()) {
            return 1;
        } else {
            return 0;
        }
    }

    public function subirImagen($imagen)
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
        $sql = "INSERT INTO pagos (`valor`, `numero_documento`, `forma_pago`, `fecha`, `imagen`, `id_plataforma`) VALUES ( ?, ?, ?, ?, ?, ?)";
        $response =  $this->insert($sql, array($valor, $documento, $forma_pago, $fecha, $imagen,  $plataforma));
        if ($response == 1) {
            $responses["status"] = 200;

            $update = "UPDATE billeteras set solicito = 0, valor_solicitud = 0, saldo = saldo - $valor WHERE id_plataforma = '$plataforma'";
            $response =  $this->select($update);
        } else {
            $responses["status"] = 400;
            $responses["message"] = $response["message"];
        }
        return $responses;
    }

    public function obtenerHistorial($tienda)
    {
        $sql = "SELECT * FROM billeteras WHERE id_plataforma = '$tienda'";

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
        $sql = "SELECT correo from datos_banco_usuarios where id_plataforma = '$id'";
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
            $mail->Subject = 'Solicitud de Pago';
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
        $sql = "SELECT * FROM billeteras WHERE id_plataforma = '$tienda'";
        $response =  $this->select($sql);
        $saldo = $response[0]['saldo'] ?? 0;
        if ($saldo <= 0) {
            return false;
        }
        if ($saldo < $valor) {
            return false;
        }
        $solicito = $response[0]['solicito'] ?? 0;
        if ($solicito == 1) {
            return false;
        }
        return true;
    }

    public function devolucion($id)
    {
        $sql = "UPDATE cabecera_cuenta_pagar set estado_guia = 9, monto_recibir=(precio_envio + full) * -1, valor_pendiente=(precio_envio + full) * -1 WHERE id_cabecera = ?;";
        $response =  $this->update($sql, array($id));

        $sql = "SELECT * FROM cabecera_cuenta_pagar WHERE id_cabecera = $id";
        $response =  $this->select($sql);
        $numero_factura = $response[0]['numero_factura'];
        $numero_guia = $response[0]['guia'];


        if (str_contains($numero_guia, 'IMP') || str_contains($numero_guia, 'MKP'))
            $estados = 9;
        else if (str_contains($numero_guia, 'SPD') || str_contains($numero_guia, 'MKL'))
            $estados = 9;
        else if (is_numeric($numero_guia))
            $estados = 500;
        else if (str_contains($numero_guia, 'I000'))
            $estados = 8;


        $sql = "UPDATE facturas_cot set estado_guia_sistema = ? WHERE numero_factura = ?";
        $response =  $this->update($sql, array($estados, $numero_factura));


        if ($response == 1) {
            $responses["status"] = 200;
        } else {
            $responses["status"] = 200;
        }
        return $responses;
    }

    public function entregar($id)
    {
        $sql = "UPDATE cabecera_cuenta_pagar set estado_guia = 7, monto_recibir=(total_venta - costo - precio_envio - full), valor_pendiente=(total_venta - costo - precio_envio - full)  WHERE id_cabecera = ?;";
        $response =  $this->update($sql, array($id));

        $sql = "SELECT * FROM cabecera_cuenta_pagar WHERE id_cabecera = $id";
        $response =  $this->select($sql);
        $numero_factura = $response[0]['numero_factura'];
        $numero_guia = $response[0]['guia'];


        if (str_contains($numero_guia, 'IMP') || str_contains($numero_guia, 'MKP'))
            $estados = 7;
        else if (str_contains($numero_guia, 'SPD') || str_contains($numero_guia, 'MKL'))
            $estados = 7;
        else if (is_numeric($numero_guia))
            $estados = 400;
        else if (str_contains($numero_guia, 'I000'))
            $estados = 7;


        $sql = "UPDATE facturas_cot set estado_guia_sistema = ? WHERE numero_factura = ?";
        $response =  $this->update($sql, array($estados, $numero_factura));



        if ($response == 1) {
            $responses["status"] = 200;
        } else {
            $responses["status"] = 200;
        }
        return $responses;
    }

    public function transito($id)
    {
        //buscar la guia
        $sql = "SELECT * FROM cabecera_cuenta_pagar WHERE id_cabecera = $id";
        $response =  $this->select($sql);
        $guia = $response[0]['guia'];
        $tipo = "";
        switch ($guia) {
            case str_contains($guia, 'IMP'):
            case str_contains($guia, 'SPD'):
            case str_contains($guia, 'MKP'):
            case str_contains($guia, 'MKL'):
                $tipo = "IMP";
                break;
            case is_numeric($guia):
                $tipo = "SER";
                break;
            case str_contains($guia, 'I000'):
                $tipo = "GIM";
                break;
        }

        $estado = 0;
        if ($tipo == "IMP") {
            $estado = 5;
        } else if ($tipo == "SER") {
            $estado = 300;
        } else if ($tipo == "GIM") {
            $estado = 4;
        }

        $sql = "UPDATE cabecera_cuenta_pagar set estado_guia = $estado WHERE id_cabecera = $id";
        $response =  $this->update($sql, array($id));

        if ($response == 1) {
            $responses["message"] = "Se ha actualizado el estado de la guia";
            $responses["status"] = 200;
        } else {
            $responses["message"] = "Se ha actualizado el estado de la guia";
            $responses["status"] = 200;
        }
        return $responses;
    }

    public function agregarOtroPago($tipo, $cuenta, $plataforma, $red)
    {
        $sql = "INSERT INTO `metodo_pagos`(`tipo`, `cuenta`, `id_plataforma`, `red`) VALUES (?, ?, ?, ?)";
        $response =  $this->insert($sql, array($tipo, $cuenta, $plataforma, $red));
        if ($response == 1) {
            $responses["status"] = 200;
        } else {
            $responses["status"] = 400;
            $responses["message"] = $response["message"];
        }
        return $responses;
    }

    public function obtenerMetodos($plataforma)
    {
        $sql = "SELECT * FROM metodo_pagos WHERE id_plataforma = '$plataforma'";
        $response =  $this->select($sql);
        return $response;
    }

    public function eliminarMetodo($id)
    {
        $sql = "DELETE FROM metodo_pagos WHERE id_pago = ?";
        $response =  $this->delete($sql, array($id));
        if ($response == 1) {
            $responses["status"] = 200;
        } else {
            $responses["status"] = 400;
            $responses["message"] = $response["message"];
        }
        return $responses;
    }

    public function obtenerSolicitudes()
    {
        $id_matriz = $this->obtenerMatriz();
        $id_matriz = $id_matriz[0]['idmatriz'];

        $sql = "SELECT *, (SELECT nombre_tienda FROM plataformas WHERE id_plataforma = solicitudes_pago.id_plataforma) as nombre_tienda FROM solicitudes_pago inner join datos_banco_usuarios on solicitudes_pago.id_cuenta = datos_banco_usuarios.id_cuenta;"; // where solicitudes_pago.id_matriz = $id_matriz";
        $response =  $this->select($sql);
        return $response;
    }

    public function eliminarSolicitudes($id)
    {
        $sql = "DELETE FROM solicitudes_pago WHERE id_solicitud = ?";
        $response =  $this->delete($sql, array($id));
        if ($response == 1) {
            $responses["status"] = 200;
        } else {
            $responses["status"] = 400;
            $responses["message"] = $response["message"];
        }
        return $responses;
    }

    public function obtenerSolicitudes_otrasFormasPago()
    {
        $id_matriz = $this->obtenerMatriz();
        $id_matriz = $id_matriz[0]['idmatriz'];

        $sql = "SELECT *, (SELECT nombre_tienda FROM plataformas WHERE id_plataforma = solicitudes_pago.id_plataforma) as nombre_tienda FROM solicitudes_pago inner join metodo_pagos on solicitudes_pago.id_cuenta = metodo_pagos.id_pago;";
        $response =  $this->select($sql);
        return $response;
    }

    public function obtenerSolicitudes_otrasFormasPagosReferidos()
    {
        $id_matriz = $this->obtenerMatriz();
        $id_matriz = $id_matriz[0]['idmatriz'];

        $sql = "SELECT *, (SELECT nombre_tienda FROM plataformas WHERE id_plataforma = solicitudes_pago_referidos.id_plataforma) as nombre_tienda FROM solicitudes_pago_referidos inner join metodo_pagos on solicitudes_pago_referidos.id_cuenta = metodo_pagos.id_pago;";
        $response =  $this->select($sql);
        return $response;
    }

    public function eliminarSolicitudes_referidos($id)
    {
        $sql = "DELETE FROM solicitudes_pago_referidos WHERE id_solicitud = ?";
        $response =  $this->delete($sql, array($id));
        if ($response == 1) {
            $responses["status"] = 200;
        } else {
            $responses["status"] = 400;
            $responses["message"] = $response["message"];
        }
        return $responses;
    }

    public function verificarPago($id_solicitud)
    {
        $sql = "UPDATE solicitudes_pago set visto = 1 WHERE id_solicitud = ?";
        $response =  $this->update($sql, array($id_solicitud));
        if ($response == 1) {
            $responses["status"] = 200;
        } else {
            $responses["status"] = 400;
            $responses["message"] = $response["message"];
        }
        return $responses;
    }

    public function obtenerOtroPago($id_platafor)
    {
        $sql = "SELECT * FROM metodo_pagos WHERE id_plataforma = '$id_platafor'";
        $response =  $this->select($sql);
        return $response;
    }

    public function obtenerGuiasAuditoria($estado, $transportadora)
    {
        $where = '';

        if ($transportadora != 0) {
            $where = " and id_transporte=$transportadora";
        } else {
            $where = "";
        }
        $sql = "SELECT DISTINCT
                fc.numero_factura,
                fc.numero_guia,
                fc.drogshipin,
                fc.cod,
                fc.estado_guia_sistema,
                fc.monto_factura,
                fc.id_transporte,
                fc.costo_flete,
                fc.id_plataforma,
                fc.id_propietario,
                
                FORMAT(
                CASE 
                    WHEN fc.id_transporte = 1 THEN (
                        SELECT cl.precio
                        FROM cobertura_laar cl
                        JOIN ciudad_cotizacion cc 
                            ON cl.tipo_cobertura = cc.trayecto_laar COLLATE utf8mb4_general_ci
                        WHERE cc.id_cotizacion COLLATE utf8mb4_general_ci = fc.ciudad_cot COLLATE utf8mb4_general_ci
                        LIMIT 1
                    )
                    WHEN fc.id_transporte = 2 THEN (
                        SELECT cs.precio
                        FROM cobertura_servientrega cs
                        JOIN ciudad_cotizacion cc 
                            ON cs.tipo_cobertura = cc.trayecto_servientrega COLLATE utf8mb4_general_ci
                        WHERE cc.id_cotizacion COLLATE utf8mb4_general_ci = fc.ciudad_cot COLLATE utf8mb4_general_ci
                        LIMIT 1
                    )
                    WHEN fc.id_transporte = 3 THEN (
                        SELECT cg.precio
                        FROM cobertura_gintracom cg
                        JOIN ciudad_cotizacion cc 
                            ON cg.trayecto = cc.trayecto_gintracom COLLATE utf8mb4_general_ci
                        WHERE cc.id_cotizacion COLLATE utf8mb4_general_ci = fc.ciudad_cot COLLATE utf8mb4_general_ci
                        LIMIT 1
                    )
                    WHEN fc.id_transporte = 4 THEN 
                        CASE 
                            WHEN fc.ciudad_cot = 599 THEN 5.5
                            ELSE 6.5
                        END
                    ELSE NULL
                END, 2) AS precio,
                FORMAT(
                    CASE 
                        WHEN fc.id_transporte = 1 THEN (
                            SELECT cl.costo
                            FROM cobertura_laar cl
                            JOIN ciudad_cotizacion cc ON cl.tipo_cobertura = cc.trayecto_laar COLLATE utf8mb4_general_ci
                            WHERE cc.id_cotizacion COLLATE utf8mb4_general_ci = fc.ciudad_cot COLLATE utf8mb4_general_ci
                            LIMIT 1
                        )
                        WHEN fc.id_transporte = 2 THEN (
                            SELECT cs.costo
                            FROM cobertura_servientrega cs
                            JOIN ciudad_cotizacion cc ON cs.tipo_cobertura = cc.trayecto_servientrega COLLATE utf8mb4_general_ci
                            WHERE cc.id_cotizacion COLLATE utf8mb4_general_ci = fc.ciudad_cot COLLATE utf8mb4_general_ci
                            LIMIT 1
                        )
                        WHEN fc.id_transporte = 3 THEN (
                            SELECT cg.costo
                            FROM cobertura_gintracom cg
                            JOIN ciudad_cotizacion cc ON cg.trayecto = cc.trayecto_gintracom COLLATE utf8mb4_general_ci
                            WHERE cc.id_cotizacion COLLATE utf8mb4_general_ci = fc.ciudad_cot COLLATE utf8mb4_general_ci
                            LIMIT 1
                        )
                        WHEN fc.id_transporte = 4 THEN 
                        CASE 
                            WHEN fc.ciudad_cot = 599 THEN 4
                            ELSE 5
                        END
                        ELSE NULL
                    END, 2) AS costo,
                FORMAT(fc.costo_flete - 
                    CASE 
                        WHEN fc.id_transporte = 1 THEN (
                            SELECT cl.precio
                            FROM cobertura_laar cl
                            JOIN ciudad_cotizacion cc ON cl.tipo_cobertura = cc.trayecto_laar COLLATE utf8mb4_general_ci
                            WHERE cc.id_cotizacion COLLATE utf8mb4_general_ci = fc.ciudad_cot COLLATE utf8mb4_general_ci
                            LIMIT 1
                        )
                        WHEN fc.id_transporte = 2 THEN (
                            SELECT cs.precio
                            FROM cobertura_servientrega cs
                            JOIN ciudad_cotizacion cc ON cs.tipo_cobertura = cc.trayecto_servientrega COLLATE utf8mb4_general_ci
                            WHERE cc.id_cotizacion COLLATE utf8mb4_general_ci = fc.ciudad_cot COLLATE utf8mb4_general_ci
                            LIMIT 1
                        )
                        WHEN fc.id_transporte = 3 THEN (
                            SELECT cg.precio
                            FROM cobertura_gintracom cg
                            JOIN ciudad_cotizacion cc ON cg.trayecto = cc.trayecto_gintracom COLLATE utf8mb4_general_ci
                            WHERE cc.id_cotizacion COLLATE utf8mb4_general_ci = fc.ciudad_cot COLLATE utf8mb4_general_ci
                            LIMIT 1
                        )
                        WHEN fc.id_transporte = 4 THEN 
                        CASE 
                            WHEN fc.ciudad_cot = 599 THEN 0
                            ELSE 0
                        END
                        ELSE NULL
                    END, 2) AS valor_cod,
                FORMAT(
                    (CASE 
                        WHEN fc.id_transporte = 1 THEN (
                            SELECT cl.precio
                            FROM cobertura_laar cl
                            JOIN ciudad_cotizacion cc ON cl.tipo_cobertura = cc.trayecto_laar COLLATE utf8mb4_general_ci
                            WHERE cc.id_cotizacion COLLATE utf8mb4_general_ci = fc.ciudad_cot COLLATE utf8mb4_general_ci
                            LIMIT 1
                        )
                        WHEN fc.id_transporte = 2 THEN (
                            SELECT cs.precio
                            FROM cobertura_servientrega cs
                            JOIN ciudad_cotizacion cc ON cs.tipo_cobertura = cc.trayecto_servientrega COLLATE utf8mb4_general_ci
                            WHERE cc.id_cotizacion COLLATE utf8mb4_general_ci = fc.ciudad_cot COLLATE utf8mb4_general_ci
                            LIMIT 1
                        )
                        WHEN fc.id_transporte = 3 THEN (
                            SELECT cg.precio
                            FROM cobertura_gintracom cg
                            JOIN ciudad_cotizacion cc ON cg.trayecto = cc.trayecto_gintracom COLLATE utf8mb4_general_ci
                            WHERE cc.id_cotizacion COLLATE utf8mb4_general_ci = fc.ciudad_cot COLLATE utf8mb4_general_ci
                            LIMIT 1
                        )WHEN fc.id_transporte = 4 THEN 
                        CASE 
                            WHEN fc.ciudad_cot = 599 THEN 5.5
                            ELSE 6.5
                        END
                        ELSE NULL
                    END -
                    CASE 
                        WHEN fc.id_transporte = 1 THEN (
                            SELECT cl.costo
                            FROM cobertura_laar cl
                            JOIN ciudad_cotizacion cc ON cl.tipo_cobertura = cc.trayecto_laar COLLATE utf8mb4_general_ci
                            WHERE cc.id_cotizacion COLLATE utf8mb4_general_ci = fc.ciudad_cot COLLATE utf8mb4_general_ci
                            LIMIT 1
                        )
                        WHEN fc.id_transporte = 2 THEN (
                            SELECT cs.costo
                            FROM cobertura_servientrega cs
                            JOIN ciudad_cotizacion cc ON cs.tipo_cobertura = cc.trayecto_servientrega COLLATE utf8mb4_general_ci
                            WHERE cc.id_cotizacion COLLATE utf8mb4_general_ci = fc.ciudad_cot COLLATE utf8mb4_general_ci
                            LIMIT 1
                        )
                        WHEN fc.id_transporte = 3 THEN (
                            SELECT cg.costo
                            FROM cobertura_gintracom cg
                            JOIN ciudad_cotizacion cc ON cg.trayecto = cc.trayecto_gintracom COLLATE utf8mb4_general_ci
                            WHERE cc.id_cotizacion COLLATE utf8mb4_general_ci = fc.ciudad_cot COLLATE utf8mb4_general_ci
                            LIMIT 1
                        )
                        WHEN fc.id_transporte = 4 THEN 
                        CASE 
                            WHEN fc.ciudad_cot = 599 THEN 4
                            ELSE 5
                        END
                        ELSE NULL
                    END), 2) AS utilidad,
                pt.valor,
                pt.comision,
                ccp.monto_recibir,
                ccp.valor_pendiente,
                ccp.envio_wallet,
                ccp.costo_wallet,
                (SELECT SUM(monto)
                    FROM historial_billetera hb
                    WHERE hb.motivo LIKE CONCAT('%', fc.numero_guia, '%')
                ) AS monto_total_historial,
                CASE 
                    WHEN (SELECT SUM(monto)
                        FROM historial_billetera hb
                        WHERE hb.motivo LIKE CONCAT('%', fc.numero_guia, '%')
                    ) > fc.monto_factura THEN 1
                    ELSE 0
                END AS comparacion_historial
            FROM 
                facturas_cot fc
            LEFT JOIN 
                pagos_transportadora pt ON fc.numero_guia = pt.guia
            LEFT JOIN (
                SELECT 
                    guia,
                    SUM(monto_recibir) AS monto_recibir,
                    SUM(valor_pendiente) AS valor_pendiente,
                    SUM(precio_envio) AS envio_wallet,
                    SUM(costo) AS costo_wallet
                FROM 
                    cabecera_cuenta_pagar
                WHERE visto = 1
                GROUP BY 
                    guia
            ) ccp ON fc.numero_guia = ccp.guia
            WHERE 
                fc.estado_guia_sistema IN (9, 7, 500, 501, 502, 400, 401, 402, 403, 13)
                AND ccp.guia IS NOT NULL $where
            ORDER BY 
                fc.fecha_factura;
            ";
        //echo $sql;
        $response =  $this->select($sql);
        return $response;
    }

    public function obtenerTotalGuiasAuditoria($estado, $transportadora)
    {
        $where = '';

        if ($transportadora != 0) {
            $where = " and id_transporte=$transportadora";
        } else {
            $where = "";
        }
        $sql = "SELECT 
        (SELECT SUM(monto) FROM historial_billetera) AS total_cabecera_cuenta_pagar,
        (SELECT SUM(valor) FROM pagos) AS total_pagos,
        (SELECT SUM(monto) FROM historial_billetera) - (SELECT SUM(valor) FROM pagos) AS diferencia";
        echo $sql;
        $response =  $this->select($sql);
        return $response;
    }

    public function habilitarAuditoria($guia, $estado)
    {
        $response = $this->initialResponse();
        $usuario = $_SESSION['id'];
        $sql = "UPDATE facturas_cot set valida_transportadora = ? WHERE numero_guia = ?";
        $response =  $this->update($sql, array($estado, $guia));
        if ($response == 1) {
            $sql = "INSERT INTO auditoria_guia (`guia`, `usuario`) VALUES (?, ?)";
            $response =  $this->insert($sql, array($guia, $usuario));
            if ($response == 1) {
                $responses["status"] = 200;
                $responses["message"] = 'Exito';
            } else {
                $responses["status"] = 400;
                $responses["message"] = $response["message"];
            }
        } else {
            $responses["status"] = 400;
            $responses["message"] = $response["message"];
        }
        return $responses;
    }

    public function buscarTienda($numero_factura)
    {
        $numero_factura = str_replace("-F", "", $numero_factura);

        $sql = "SELECT * FROM cabecera_cuenta_pagar WHERE numero_factura = '$numero_factura'";
        $response =  $this->select($sql);
        $id_plataforma = $response[0]['id_plataforma'];
        $sql = "SELECT * FROM plataformas WHERE id_plataforma = '$id_plataforma'";
        $response =  $this->select($sql);

        $sql = "select * from facturas_cot fc, detalle_fact_cot dfc, productos p where dfc.id_producto=p.id_producto and fc.id_factura=dfc.id_factura and fc.numero_factura = '$numero_factura';";
        $response2 =  $this->select($sql);
        $response2[0]['url'] = $response[0]['nombre_tienda'];

        return $response2;
    }

    public function agregarAuditoria($guia, $fecha, $valor, $comision, $transportadora)
    {
        $response = $this->initialResponse();
        //  echo $descripcion_producto;
        //echo '------';

        // $descripcion_producto = "<p><strong>2 EN 1 x 100 CAPSULAS</strong></p><p>- Mejora la atención y la memoria.</p><p>- Mejora el rendimiento cerebral.</p><p>- Reduce la capacidad de concentración.</p><p>- Mejora la función cognitiva.</p>";
        // echo $descripcion_producto;
        $sql = "INSERT INTO pagos_transportadora (id_transportadora, guia, valor, comision,  fecha) VALUES (?, ?, ?, ?, ?)";
        $data = [$transportadora, $guia, $valor, $comision, $fecha];
        $insertar_producto = $this->insert($sql, $data);

        //print_r($insertar_producto);
        if ($insertar_producto == 1) {
            $response['message'] = 'Producto y stock agregado correctamente';
            $response['status'] = 200;
            $response['title'] = 'Peticion exitosa';
            $response['message'] = 'Producto agregado correctamente';
        } else {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = 'Error al agregar el producto';
        }

        return $response;
    }

    public function eliminarOtroPago($id)
    {
        $sql = "DELETE FROM solicitudes_pago WHERE id_solicitud = ?";
        $response =  $this->delete($sql, array($id));
        if ($response == 1) {
            $responses["status"] = 200;
        } else {
            $responses["status"] = 400;
            $responses["message"] = $response["message"];
        }
        return $responses;
    }

    public function solicitudesReferidos()
    {
        $sql = "SELECT *, (SELECT nombre_tienda FROM plataformas WHERE id_plataforma = solicitudes_pago_referidos.id_plataforma) as nombre_tienda FROM solicitudes_pago_referidos inner join datos_banco_usuarios on solicitudes_pago_referidos.id_cuenta = datos_banco_usuarios.id_cuenta";
        $response =  $this->select($sql);
        return $response;
    }

    public function aprobarSolicitud($id_solicitud)
    {
        $sql = "UPDATE solicitudes_pago_referidos set visto = 1 WHERE id_solicitud = ?";
        $response =  $this->update($sql, array($id_solicitud));
        if ($response == 1) {
            $responses["status"] = 200;
        } else {
            $responses["status"] = 400;
            $responses["message"] = $response["message"];
        }
        return $responses;
    }

    public function obtenerDatosTienda($id)
    {
        $sql = "SELECT * FROM plataformas WHERE id_plataforma = '$id'";
        $response =  $this->select($sql);
        return $response;
    }

    public function guardarArchivo($fileTmpPath, $fileName, $id_transportadora)
    {
        // Definir la ruta donde se guardará el archivo
        $uploadDir = 'public/transportadoras/';

        // Obtener la extensión del archivo
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

        // Crear un nombre de archivo único con la fecha
        $date = date('Ymd_His');
        $newFileName = uniqid() . '.' . $fileExtension;
        $destPath = $uploadDir . $newFileName;

        // Mover el archivo a la ruta definida
        if (move_uploaded_file($fileTmpPath, $destPath)) {
            // Guardar la URL del archivo en la base de datos
            $url = $destPath;
            $sql = "INSERT INTO archivos_transportadoras (`url`) VALUES (?)";
            $response = $this->insert($sql, array($url));

            if ($response) {
                return [
                    'status' => 200,
                    'url' => $url,
                    'message' => 'Archivo subido y guardado correctamente'
                ];
            } else {
                return [
                    'status' => 500,
                    'message' => 'Error al guardar el archivo en la base de datos'
                ];
            }
        } else {
            return [
                'status' => 500,
                'message' => 'Error al mover el archivo al servidor'
            ];
        }
    }

    public function historialSolicitud($tipo, $cantidad, $usuario, $cuenta, $id_plataforma)
    {
        $sql = "INSERT INTO historial_solicitudes (`tipo`, `cantidad`, `id_plataforma`, `usuario`, `id_cuenta`) VALUES (?, ?, ?, ?, ?)";
        $response =  $this->insert($sql, array($tipo, $cantidad, $id_plataforma, $usuario, $cuenta));
        if ($response == 1) {
            $responses["status"] = 200;
        } else {
            $responses["status"] = 400;
            $responses["message"] = $response["message"];
        }
        return $responses;
    }

    public function obtenerHistorialSolicitudes($id_plataforma)
    {
        $sql = "SELECT * FROM historial_solicitudes WHERE id_plataforma = '$id_plataforma'";
        $response =  $this->select($sql);
        return $response;
    }

    public function obtenerBilleteraTienda($id_plataforma)
    {
        $sql = "SELECT * FROM `billeteras` WHERE id_plataforma=$id_plataforma;";
        $response =  $this->select($sql);
        return $response;
    }

    public function obtenerBilleteraTienda_plataforma($id_plataforma)
    {
        $sql = "SELECT * FROM `billeteras` WHERE id_plataforma=$id_plataforma;";
        $response =  $this->select($sql);
        return $response;
    }

    public function historialSolicitudes($plataforma)
    {
        $sql = "SELECT * FROM historial_solicitudes WHERE id_plataforma = '$plataforma'";
        $response =  $this->select($sql);

        foreach ($response as $key => $value) {
            $id_cuenta = $value['id_cuenta'];
            $tipo = $value['tipo'];
            $usuario = $value['usuario'];

            $sql = "SELECT * FROM users WHERE id_users = '$usuario'";
            $response2 =  $this->select($sql);
            $response[$key]['usuario'] = $response2[0]['nombre_users'];

            if ($tipo == "PRIMARIO") {
                $sql = "SELECT * FROM datos_banco_usuarios WHERE id_cuenta = '$id_cuenta'";
                $response2 =  $this->select($sql);
                if (empty($response2)) {
                    $modal = "No hay datos";
                } else {

                    $modal = "
                    <button type='button' class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#modal" . $key . "'>
                    Ver detalles
                    </button>
                    
                    <div class='modal fade' id='modal" . $key . "' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
                        <div class='modal-dialog' role='document'>
                            <div class='modal-content'>
                                <div class='modal-header'>
                                    <h5 class='modal-title' id='exampleModalLabel'>Detalles</h5>
                                    <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                    
                                </div>
                                <div class='modal-body' style='text-align: left;'>
                                    <p><strong>Nombre:</strong> " . $response2[0]['nombre'] . "</p>
                                    <p><strong>Banco:</strong> " . $response2[0]['banco'] . "</p>
                                    <p><strong>Cedula:</strong> " . $response2[0]['cedula'] . "</p>
                                    <p><strong>Numero de cuenta:</strong> " . $response2[0]['numero_cuenta'] . "</p>
                                    <p><strong>Tipo de cuenta:</strong> " . $response2[0]['tipo_cuenta'] . "</p>
                                    <p><strong>Telefono:</strong> " . $response2[0]['telefono'] . "</p>
                                </div>
                                <div class='modal-footer'>
                                <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Cerrar</button>
                                </div>
                            </div>
                        </div>
                    
                    </div>
                    
                    ";
                }

                $response[$key]['modal'] = $modal;
            } else {
                $sql = "SELECT * FROM metodo_pagos WHERE id_pago = '$id_cuenta'";
                $response2 =  $this->select($sql);

                if (empty($response2)) {
                    $modal = "No hay datos";
                } else {

                    $modal = "
                <button type='button' class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#modal" . $key . "'>
                    Ver detalles
                </button>

                <div class='modal fade' id='modal" . $key . "' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
                    <div class='modal-dialog' role='document'>
                        <div class='modal-content'>
                            <div class='modal-header'>
                                <h5 class='modal-title' id='exampleModalLabel'>Detalles</h5>
                               <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                    
                            </div>
                            <div class='modal-body
                            '>
                                <p><strong>Tipo:</strong> " . $response2[0]['tipo'] . "</p>
                                <p><strong>Cuenta:</strong> " . $response2[0]['cuenta'] . "</p>
                                <p><strong>Red:</strong> " . $response2[0]['red'] . "</p>
                            </div>
                            <div class='modal-footer'>
                                <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>

               ";
                }

                $response[$key]['modal'] =  $modal;
            }
        }


        $respuesta['data'] = $response;
        $respuesta['status'] = 200;
        return $respuesta;
    }


    public function retener($id_plataforma)
    {
        // Consulta para obtener el conteo de guías en novedad
        $sql = "SELECT COUNT(*) as guias_novedad
                    FROM cabecera_cuenta_pagar
                    WHERE id_plataforma = $id_plataforma
                    AND (
                        (
                            -- Para laar: guías que empiezan con IMP, MKP, RKT y estado_guia es 14
                            (guia LIKE 'IMP%' OR guia LIKE 'MKP%' OR guia LIKE 'RKT%')
                            AND estado_guia = 14
                        )
                        OR
                        (
                            -- Para servientrega: guías solo numéricas y estado_guia entre 320 y 399
                            (guia REGEXP '^[0-9]+$')
                            AND estado_guia BETWEEN 320 AND 399
                        )
                        OR
                        (
                            -- Para gintracom: guías que empiezan con I00 y estado_guia es 6
                            guia LIKE 'I00%'
                            AND estado_guia = 6
                        )
                        OR
                        (
                            -- Para speed: guías que empiezan con SPD o MKL y estado_guia es 14
                            (guia LIKE 'SPD%' OR guia LIKE 'MKL%')
                            AND estado_guia = 14
                        )
                    );";
        $response_guias =  $this->select($sql);

        // Consulta para obtener el saldo de la billetera
        $sql = "SELECT * FROM billeteras WHERE id_plataforma = '$id_plataforma'";
        $response_billeteras =  $this->select($sql);
        $saldo = round($response_billeteras[0]['saldo'], 2);

        // Verificar si hay guías en novedad
        if ($response_guias[0]['guias_novedad'] > 0) {
            $guias_novedad = $response_guias[0]['guias_novedad'];
            $valor_retenido = 3 * $guias_novedad;

            if ($saldo < $valor_retenido) {
                $respuesta['status'] = 400;
                $respuesta['message'] = "Lo sentimos, pero hemos retenido $" . $valor_retenido . " porque tienes guías en novedad y tu saldo es insuficiente en caso de que se generen devoluciones.";
            } else {
                $respuesta['status'] = 200;
                $respuesta['message'] = "Se ha retenido $" . $valor_retenido . " porque tienes guías en novedad.";
            }
        } else {
            $valor_retenido = 0;
            $respuesta['status'] = 200;
            $respuesta['message'] = "No se ha retenido nada.";
        }

        // Preparar la respuesta final
        $response = [
            'valor_retenido' => $valor_retenido,
            'saldo' => $saldo,
            'status' => $respuesta['status'],
            'message' => $respuesta['message']
        ];

        return $response;
    }


    /////////////////////////////// DEBUGS //////////////////////////////////////

    public function devolucionAwallet($numero_guia)
    {
        $sql_select = "SELECT * FROM `facturas_cot` WHERE numero_guia = '$numero_guia'";
        $response =  $this->select($sql_select);
        $id_plataforma = $response[0]['id_plataforma'];
        $id_proveedor = $response[0]['id_propietario'];
        if ($id_proveedor == $id_plataforma) {
            $id_proveedor = NULL;
            $url_proveedor = NULL;
        } else {
            $sql_select = "SELECT * FROM `plataformas` WHERE id_plataforma = '$id_proveedor'";
            $response2 =  $this->select($sql_select);
            $url_proveedor = $response2[0]['url_imporsuit'];
        }

        $cliente = $response[0]['nombre'];
        $fecha_factura = $response[0]['fecha_factura'];
        $url_tienda_sql = "SELECT * FROM plataformas WHERE id_plataforma = '$id_plataforma'";
        $url_tienda =  $this->select($url_tienda_sql);
        $url_tienda = $url_tienda[0]['url_imporsuit'];

        $estado_guia = 9;
        $total_venta = $response[0]["monto_factura"];
        $costo = $response[0]["costo_producto"];
        $precio_envio = $response[0]["costo_flete"];

        $monto_recibir = (-$precio_envio);
        $valor_pendiente = (-$precio_envio);
        $id_matriz = 1;
        $cod = $response[0]["cod"];
        $numero_factura = $response[0]["numero_factura"];

        $sql_insert = "INSERT INTO `cabecera_cuenta_pagar`(`numero_factura`, `id_plataforma`, `id_proveedor`, `cliente`, `fecha`, `tienda`, `proveedor`, `estado_guia`, `total_venta`, `costo`, `precio_envio`, `monto_recibir`, `valor_pendiente`, `id_matriz`, `cod`, `guia`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

        $response =  $this->insert($sql_insert, array($numero_factura, $id_plataforma, $id_proveedor, $cliente, $fecha_factura, $url_tienda, $url_proveedor, $estado_guia, $total_venta, $costo, $precio_envio, $monto_recibir, $valor_pendiente, $id_matriz, $cod, $numero_guia));
        if ($response == 1) {
            $responses["status"] = 200;
        } else {
            $responses["status"] = 400;
            $responses["message"] = $response["message"];
        }
        return $responses;
    }

    public function guiasAhistorial($numero_guia)
    {
        $sql = "SELECT * FROM facturas_cot WHERE numero_guia = '$numero_guia'";
        $response =  $this->select($sql);
        $id_plataforma = $response[0]['id_plataforma'];
        $id_proveedor = $response[0]['id_propietario'];
        if ($id_proveedor == $id_plataforma) {
            $id_proveedor = NULL;
            $url_proveedor = NULL;
            $this->procesarHistorial($id_plataforma, $numero_guia);
        } else {
            echo 'Entro a proceso';
            $this->procesarHistorial($id_proveedor, $numero_guia);
            $this->procesarHistorial($id_plataforma, $numero_guia);
            echo 'Salio de proceso';
        }
    }

    public function procesarHistorial($id_plataforma, $numero_guia)
    {
        $sql = "SELECT * FROM cabecera_cuenta_pagar WHERE guia = '$numero_guia' AND id_plataforma = '$id_plataforma'";
        echo $sql;
        $response =  $this->select($sql);
        print_r($response);
        $monto_recibir = $response[0]['monto_recibir'];

        $sql = "SELECT * FROM billeteras WHERE id_plataforma = '$id_plataforma'";
        $response =  $this->select($sql);
        $id_billetera = $response[0]['id_billetera'] ?? 0;

        if ($id_billetera == 0) {
            $this->crearBilletera($id_plataforma);
            $response =  $this->select($sql);
            $id_billetera = $response[0]['id_billetera'];
        }
        $id_responsable = 2206;

        $tipo = $monto_recibir > 0 ? 'ENTRADA' : 'SALIDA';
        $motivo = $monto_recibir > 0 ? 'Se acredito a la billetera la guia: ' . $numero_guia : 'Se desconto de la billetera la guia: ' . $numero_guia;

        $sql = "INSERT INTO historial_billetera (`id_billetera`, `id_responsable`, `tipo`, `monto`, `motivo`) VALUES (?, ?, ?, ?, ?)";
        $response =  $this->insert($sql, array($id_billetera, $id_responsable, $tipo, $monto_recibir, $motivo));

        print_r($response);
    }
    public function guiasAproveedor($guia)
    {
        $sql = "SELECT * FROM cabecera_cuenta_pagar WHERE guia = '$guia'";
        $response =  $this->select($sql);

        $sql_insert = "INSERT INTO `cabecera_cuenta_pagar`(`numero_factura`, `id_plataforma`, `cliente`, `fecha`, `tienda`, `estado_guia`, `costo`, `monto_recibir`, `id_matriz`, `cod`, `guia`) VALUES (?,?,?,?,?,?,?,?,?,?,?)";
        $response =  $this->insert($sql_insert, array($response[0]['numero_factura'] . '-P', $response[0]['id_proveedor'], $response[0]['cliente'], $response[0]['fecha'], $response[0]['proveedor'], 7, $response[0]['costo'], $response[0]['costo'], 1, $response[0]['cod'], $guia));
        return $response;
    }
    public function guiasAcuadre()
    {
        $sql = "SELECT * FROM `cabecera_cuenta_pagar` WHERE guia like 'MKP%' and estado_guia = 7 and numero_factura not like '%-P' and numero_factura not like '%-F' and precio_envio != 5.99;";
        $response =  $this->select($sql);

        foreach ($response as $key => $value) {
            $id_cabecera = $value['id_cabecera'];
            $sql = "UPDATE cabecera_cuenta_pagar set precio_envio = 5.99 , monto_recibir = total_venta - costo - 5.99 - full where id_cabecera = ?";
            $response =  $this->update($sql, array($id_cabecera));
        }

        $sql = "SELECT * FROM `cabecera_cuenta_pagar` WHERE guia like 'MKP%' and estado_guia = 9 and numero_factura not like '%-P' and numero_factura not like '%-F' and precio_envio != 5.99;";
        $response =  $this->select($sql);

        foreach ($response as $key => $value) {
            $id_cabecera = $value['id_cabecera'];
            $sql = "UPDATE cabecera_cuenta_pagar set precio_envio = 5.99 , monto_recibir = - 5.99 - full where id_cabecera = ?";
            $response =  $this->update($sql, array($id_cabecera));
        }

        return $response;
    }


    // Procesos de PAGO AUTOMATICO NO COLOCAR CODIGO AQUI NI MODIFICAR ABSOLUTAMENTE NADA
    public function pagar_laar()
    {
        $guias = $this->obtenerTodasLasGuias();

        // Obtener pesos de todas las guías concurrentemente
        $pesos_guias = $this->verdaderoPeso($guias);

        $guias_con_exito = [];
        $guias_con_fallo = [];

        foreach ($guias as $guia) {
            // Llama a verificar_envio y recoge los resultados
            list($exito, $fallo) = $this->verificar_envio($guia, $pesos_guias[$guia]['pesoKilos']);

            // Combina los resultados
            $guias_con_exito = array_merge($guias_con_exito, $exito);
            $guias_con_fallo = array_merge($guias_con_fallo, $fallo);
        }

        // Al final, tienes dos arreglos: $guias_con_exito y $guias_con_fallo
        return [
            'exito' => $guias_con_exito,
            'fallo' => $guias_con_fallo
        ];
    }

    public function obtenerTodasLasGuias()
    {
        $sql = "SELECT * FROM cabecera_cuenta_pagar WHERE estado_guia IN (7, 9) AND visto = 0 AND (guia LIKE 'IMP%' OR guia LIKE 'MKP%') and id_plataforma not in (1160,1190);";
        $response = $this->select($sql);
        $guias = [];
        foreach ($response as $value) {
            $guias[] = $value['guia'];
        }
        return $guias;
    }

    public function verificar_envio($numero_guia, $peso)
    {
        // Buscar en facturas_cot
        $sql = "SELECT * FROM facturas_cot WHERE numero_guia = '$numero_guia'";
        $response = $this->select($sql);
        $id_plataforma = $response[0]['id_plataforma'];
        $precio_envio = $response[0]['costo_flete'];
        $ciudad_cot = $response[0]['ciudad_cot'];
        $id_transporte = $response[0]['id_transporte'];
        $monto_factura = $response[0]['monto_factura'];
        $cod = $response[0]['cod'];

        // Buscar en ciudad_cotizacion
        $sql = "SELECT * FROM ciudad_cotizacion WHERE id_cotizacion = '$ciudad_cot'";
        $response = $this->select($sql);
        $trayecto = $id_transporte == 1 ? $response[0]['trayecto_laar'] : ($id_transporte == 2 ? $response[0]['trayecto_servientrega'] : ($id_transporte == 3 ? $response[0]['trayecto_gintracom'] : ($id_transporte == 4 ? ($ciudad_cot == 599 ? 5.5 : 6.5) : 0)));

        // Obtener valor de cobertura
        $valor_cobertura = $this->obtenerValorCobertura($trayecto, $id_transporte, $ciudad_cot);

        // Calcular el precio total del envío
        $precioTotalEnvio = $this->calcularPrecioEnvio($id_transporte, $cod, $monto_factura, $valor_cobertura, $numero_guia);

        // Verificar si hay peso adicional y calcular precio extra
        if ($peso > 2) {
            $peso_extra = $peso - 2;
            $precio_por_kilo_extra = $this->obtenerPrecioPorTrayecto($trayecto);
            $precioTotalEnvio += $precio_por_kilo_extra * $peso_extra;
        }

        // Comparar el precio del envío
        $guias_con_exito = [];
        $guias_con_fallo = [];
        if ($precio_envio == $precioTotalEnvio) {
            $guias_con_exito[] = $numero_guia;
        } else {
            $guias_con_fallo[] = $numero_guia;
        }

        return [$guias_con_exito, $guias_con_fallo];
    }

    // Función para obtener el precio por trayecto
    public function obtenerPrecioPorTrayecto($trayecto)
    {
        $precios = [
            'TP' => 0.86,
            'TE' => 1.15,
            'TL' => 0.86,
            'TS' => 0.86,
            'TO' => 1.15,
            'GAL' => 2.88
        ];
        return isset($precios[$trayecto]) ? $precios[$trayecto] : 0;
    }

    // Función para obtener valor de cobertura
    public function obtenerValorCobertura($trayecto, $id_transporte, $ciudad_cot)
    {
        $valor_cobertura = 0;
        if ($id_transporte == 1) {
            $sql = "SELECT * FROM cobertura_laar WHERE tipo_cobertura = '$trayecto'";
            $response = $this->select($sql);
            $valor_cobertura = $response[0]['precio'];
        } else if ($id_transporte == 2) {
            $sql = "SELECT * FROM cobertura_servientrega WHERE tipo_cobertura = '$trayecto'";
            $response = $this->select($sql);
            $valor_cobertura = $response[0]['precio'];
        } else if ($id_transporte == 3) {
            $sql = "SELECT * FROM cobertura_gintracom WHERE trayecto = '$trayecto'";
            $response = $this->select($sql);
            $valor_cobertura = $response[0]['precio'];
        } else if ($id_transporte == 4) {
            $valor_cobertura = $ciudad_cot == 599 ? 5.5 : 6.5;
        }
        return $valor_cobertura;
    }

    // Función para calcular el precio total del envío
    public function calcularPrecioEnvio($id_transporte, $cod, $monto_factura, $valor_cobertura, $numero_guia)
    {
        if ($cod == 1 && str_contains($numero_guia, 'IMP')) {
            return round($monto_factura * 0.03 + $valor_cobertura, 2);
        } elseif ($cod != 1 && str_contains($numero_guia, 'IMP')) {
            return $valor_cobertura;
        } elseif (str_contains($numero_guia, 'MKP')) {
            return 5.99;
        } else {
            return $valor_cobertura;
        }
    }

    // Función para obtener pesos de forma concurrente
    public function obtenerPesosConcurrencia($guias)
    {
        $mh = curl_multi_init();
        $curl_array = [];
        $results = [];

        foreach ($guias as $guia) {
            $url = "https://api.laarcourier.com:9727/guias/" . $guia;
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");

            curl_multi_add_handle($mh, $curl);
            $curl_array[$guia] = $curl;
        }

        $running = null;
        do {
            curl_multi_exec($mh, $running);
            curl_multi_select($mh);
        } while ($running > 0);

        foreach ($curl_array as $guia => $curl) {
            $results[$guia] = json_decode(curl_multi_getcontent($curl), true);
            curl_multi_remove_handle($mh, $curl);
        }

        curl_multi_close($mh);
        return $results;
    }

    // Modificar verdaderoPeso para usar la concurrencia
    public function verdaderoPeso($guias)
    {
        return $this->obtenerPesosConcurrencia($guias);
    }

    // Validar si la guias es con o sin recaudo
    public function validarRecaudo($numero_guia, $cod, $guias)
    {
        $sql = "SELECT * FROM facturas_cot WHERE numero_guia = '$numero_guia'";
        $response = $this->select($sql);
        $codAlmacenada = $response[0]['cod'];
        $validar = false;
        if ($codAlmacenada == $cod) {
            $validar  = true;
        }

        if ($validar) {
            /*  $total_venta =  */
        }
    }
}
