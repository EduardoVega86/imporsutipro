<?php
class DashboardModel extends Query
{
    public function __construct()
    {
        parent::__construct();
    }

    public function filtroInicial($fecha_i, $fecha_f, $plataforma, $id_plataforma)
    {
        // Consulta para ventas, ganancias, envíos y total de guías
        $sql = "SELECT 
                    ROUND(SUM(total_venta),2) as ventas, 
                    ROUND(SUM(monto_recibir),2) as ganancias, 
                    ROUND(SUM(precio_envio),2) as envios,
                   
                FROM cabecera_cuenta_pagar 
                WHERE fecha BETWEEN '$fecha_i' AND '$fecha_f' 
                AND tienda = '$plataforma' 
                AND estado_guia IN (7, 9)";
        $response = $this->select($sql);

        $sql = "SELECT 
                    COUNT(*) as total_guias 
                FROM cabecera_cuenta_pagar 
                WHERE fecha BETWEEN '$fecha_i' AND '$fecha_f' 
                AND tienda = '$plataforma' 
                ;";

        $response2 = $this->select($sql);

        // Consulta para devoluciones
        $sql = "SELECT 
                    ROUND(SUM(monto_recibir),2) as devoluciones 
                FROM cabecera_cuenta_pagar 
                WHERE fecha BETWEEN '$fecha_i' AND '$fecha_f' 
                AND estado_guia = 9 
                AND tienda = '$plataforma'";
        $response3 = $this->select($sql);

        // Consulta para pedidos
        $sql = "SELECT 
                    COUNT(*) as pedidos 
                FROM facturas_cot 
                WHERE fecha_factura BETWEEN '$fecha_i' AND '$fecha_f' 
                AND id_plataforma = '$id_plataforma'";
        $response4 = $this->select($sql);

        /*         $sql = "SELECT DATE_FORMAT(fecha, '%Y-%m-%d') as dia, ROUND(SUM(total_venta),2) as ventas, ROUND(SUM(monto_recibir),2) as ganancias, ROUND(SUM(precio_envio),2) as envios, COUNT(*) as cantidad FROM cabecera_cuenta_pagar WHERE fecha BETWEEN DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH) + INTERVAL 1 DAY - INTERVAL 1 MONTH, '%Y-%m-%d') AND LAST_DAY(NOW() - INTERVAL 1 MONTH) and tienda like '%$plataforma%' and estado_guia = 7 GROUP BY dia ORDER BY dia;";
        */
        $sql = "
        SELECT 
            DATE_FORMAT(fecha, '%Y-%m-%d') as dia, 
            ROUND(SUM(total_venta), 2) as ventas, 
            ROUND(SUM(monto_recibir), 2) as ganancias, 
            ROUND(SUM(precio_envio), 2) as envios, 
            COUNT(*) as cantidad 
        FROM 
            cabecera_cuenta_pagar 
        WHERE 
            fecha BETWEEN DATE_FORMAT(NOW(), '%Y-%m-01') AND LAST_DAY(NOW()) 
            AND tienda LIKE '%$plataforma%' 
            AND estado_guia = 7 
        GROUP BY 
            dia 
        ORDER BY 
            dia;
        ";

        $response5 = $this->select($sql);

        $sql = "SELECT monto_factura, fecha_factura, numero_factura from facturas_cot where id_plataforma = '$id_plataforma' order by fecha_factura desc limit 5;";
        $response6 = $this->select($sql);

        $sql = "
           SELECT 
                estado_descripcion,
                COUNT(*) as cantidad
            FROM (
                SELECT 
                    CASE 
                        WHEN estado_guia_sistema IN (1, 100) THEN 'Generado'
                        WHEN estado_guia_sistema IN (2, 102) THEN 'Por Recolectar'
                        WHEN estado_guia_sistema IN (3) THEN 'Recolectado'
                        WHEN estado_guia_sistema = 4 OR (estado_guia_sistema >= 200 AND estado_guia_sistema < 300) THEN 'En Bodega'
                        WHEN estado_guia_sistema = 5 OR (estado_guia_sistema >= 300 AND estado_guia_sistema < 400) THEN 'En Transito'
                        WHEN estado_guia_sistema = 6 THEN 'Zona de Entrega'
                        WHEN estado_guia_sistema IN (7, 400, 401, 402, 403, 404, 405, 406, 407, 408, 409, 410) THEN 'Entregado'
                        WHEN estado_guia_sistema IN (8, 101) THEN 'Anulado'
                        WHEN estado_guia_sistema = 9 OR (estado_guia_sistema >= 500 AND estado_guia_sistema <= 505) THEN 'Devolucion'
                        ELSE 'Otro'
                    END as estado_descripcion
                FROM 
                    facturas_cot
            ) subquery
            GROUP BY 
                estado_descripcion
            ORDER BY 
                estado_descripcion;
        ";

        $response7 = $this->select($sql);




        $ventas = $response[0]['ventas'] ?? 0;
        $ganancias = $response[0]['ganancias'] ?? 0;
        $envios = $response[0]['envios'] ?? 0;
        $total_guias = $response2[0]['total_guias'] ?? 0;
        $devoluciones = $response3[0]['devoluciones'] ?? 0;
        $pedidos = $response4[0]['pedidos'] ?? 0;

        $datos = [
            'ventas' => $ventas,
            'envios' => $envios,
            'ganancias' => $ganancias,
            'total_guias' => $total_guias,
            'devoluciones' => $devoluciones,
            'pedidos' => $pedidos,
            'ventas_diarias' => $response5,
            'facturas' => $response6,
            'estados' => $response7
        ];

        return $datos;
    }
}
