<?php
class DashboardModel extends Query
{
    public function __construct()
    {
        parent::__construct();
    }

    public function filtroInicial($fecha_i, $fecha_f, $plataforma, $id_plataforma)
    {
        // Total Vendido y ganancias historicas para obtener el histórico de ganancias)
        $sql = "SELECT 
                    ROUND(SUM(total_venta),2) as ventas, 
                    ROUND(SUM(monto_recibir),2) as ganancias, 
                    ROUND(SUM(precio_envio),2) as envios
                FROM cabecera_cuenta_pagar 
                WHERE id_plataforma = '$id_plataforma' 
                AND visto = 1
                AND fecha BETWEEN '$fecha_i' AND '$fecha_f'";

        $response = $this->select($sql);

        //Total Guias
        $sql = "SELECT COUNT(DISTINCT fc.id_factura) AS total_guias
                FROM facturas_cot fc
                LEFT JOIN bodega b ON b.id = fc.id_bodega
                LEFT JOIN novedades n ON n.guia_novedad = fc.numero_guia
                WHERE TRIM(fc.numero_guia) <> ''
                AND fc.numero_guia IS NOT NULL
                AND fc.numero_guia <> '0'
                AND fc.anulada = 0
                AND (fc.id_plataforma = '$id_plataforma' OR fc.id_propietario = '$id_plataforma' OR b.id_plataforma = '$id_plataforma')
                AND fc.fecha_guia BETWEEN '$fecha_i' AND '$fecha_f'";

        $response2 = $this->select($sql);

        // Consulta para devoluciones
        $sql = "SELECT 
                    ROUND(SUM(monto_recibir),2) as devoluciones 
                FROM cabecera_cuenta_pagar 
                WHERE fecha BETWEEN '$fecha_i' AND '$fecha_f' 
                AND estado_guia = 9 
                 AND visto = 1
                AND id_plataforma = '$id_plataforma'";
        $response3 = $this->select($sql);

        // Consulta para pedidos
        $sql = "SELECT COUNT(*) as pedidos 
                FROM facturas_cot 
                WHERE anulada = 0
                AND (TRIM(numero_guia) = '' OR numero_guia IS NULL OR numero_guia = '0')
                AND fecha_factura BETWEEN '$fecha_i' AND '$fecha_f'
                AND id_plataforma = '$id_plataforma'";

        $response4 = $this->select($sql);

        //Total Recaudo

        $recaudo = $response[0]['ganancias'] ?? 0;


        //Ventas del ultimo mes

        $sql = "
        SELECT 
            DATE_FORMAT(fecha_factura, '%Y-%m-%d') as dia, 
            ROUND(SUM(monto_factura), 2) as ventas,  
            ROUND(SUM(costo_flete), 2) as envios, 
            COUNT(*) as cantidad 
        FROM facturas_cot
        WHERE anulada = 0
        AND id_plataforma = '$id_plataforma'
        AND fecha_factura BETWEEN '$fecha_i' AND '$fecha_f'
        GROUP BY dia
        ORDER BY dia;
        ";

        $response5 = $this->select($sql);
        // ultimas 5 facturas
        $sql = "SELECT monto_factura, fecha_factura, numero_factura from facturas_cot where id_plataforma = '$id_plataforma' order by fecha_factura desc limit 5;";
        $response6 = $this->select($sql);

        // Estados de las guías

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
                    WHEN estado_guia_sistema = 4 OR (estado_guia_sistema BETWEEN 200 AND 299) THEN 'En Bodega'
                    WHEN estado_guia_sistema = 5 OR (estado_guia_sistema BETWEEN 300 AND 399) THEN 'En Tránsito'
                    WHEN estado_guia_sistema = 6 THEN 'Zona de Entrega'
                    WHEN estado_guia_sistema IN (7, 400, 401, 402, 403, 404, 405, 406, 407, 408, 409, 410) THEN 'Entregado'
                    WHEN estado_guia_sistema IN (8, 101) THEN 'Anulado'
                    WHEN estado_guia_sistema = 9 OR (estado_guia_sistema BETWEEN 500 AND 505) THEN 'Devolución'
                    ELSE 'Otro'
                  END as estado_descripcion
                FROM facturas_cot 
                WHERE id_plataforma = '$id_plataforma'
                AND anulada = 0
                AND (TRIM(numero_guia) <> '' AND numero_guia IS NOT NULL AND numero_guia <> '0')
                AND fecha_factura BETWEEN '$fecha_i' AND '$fecha_f'
            ) AS subquery
            GROUP BY 
                estado_descripcion
            ORDER BY 
                estado_descripcion;
        ";

        $response7 = $this->select($sql);

        $sql = "SELECT ct.ciudad, COUNT(fc.ciudad_cot) AS cantidad_pedidos FROM facturas_cot fc INNER JOIN ciudad_cotizacion ct ON ct.id_cotizacion = fc.ciudad_cot WHERE id_plataforma = $id_plataforma AND estado_guia_sistema not in (1,2,100,101,8,12) AND fecha_guia BETWEEN '$fecha_i' AND '$fecha_f' 
         GROUP BY ct.ciudad ORDER BY cantidad_pedidos DESC LIMIT 5; 
                    ";
        $response8 = $this->select($sql);

        $sql = "SELECT p.nombre_producto, COUNT(df.id_inventario) AS cantidad_despachos, df.id_inventario, p.image_path FROM detalle_fact_cot df INNER JOIN inventario_bodegas ib ON df.id_inventario = ib.id_inventario INNER JOIN productos p ON ib.id_producto = p.id_producto where df.id_plataforma = $id_plataforma  GROUP BY p.nombre_producto ORDER BY cantidad_despachos DESC LIMIT 5;";
        $response9 = $this->select($sql);

        $sql = "SELECT p.nombre_producto, COUNT(df.id_inventario) AS cantidad_despachos, p.image_path FROM detalle_fact_cot df INNER JOIN inventario_bodegas ib ON df.id_inventario = ib.id_inventario INNER JOIN productos p ON ib.id_producto = p.id_producto INNER JOIN facturas_cot fc ON df.numero_factura = fc.numero_factura WHERE fc.estado_guia_sistema IN (7, 400, 401, 402, 403) AND fc.id_plataforma = $id_plataforma  AND fc.fecha_guia BETWEEN '$fecha_i' AND '$fecha_f'  GROUP BY p.nombre_producto ORDER BY cantidad_despachos DESC LIMIT 5;";
        $response10 = $this->select($sql);


        $sql = "SELECT p.nombre_producto, COUNT(df.id_inventario) AS cantidad_despachos, p.image_path FROM detalle_fact_cot df INNER JOIN inventario_bodegas ib ON df.id_inventario = ib.id_inventario INNER JOIN productos p ON ib.id_producto = p.id_producto INNER JOIN facturas_cot fc ON df.numero_factura = fc.numero_factura WHERE fc.estado_guia_sistema IN (9, 500, 501, 502, 503) AND fc.id_plataforma = $id_plataforma  AND fc.fecha_guia BETWEEN '$fecha_i' AND '$fecha_f'  GROUP BY p.nombre_producto ORDER BY cantidad_despachos DESC LIMIT 5;";
        $response11 = $this->select($sql);

        $sql = "SELECT ct.ciudad, COUNT(fc.ciudad_cot) AS cantidad_entregas FROM facturas_cot fc INNER JOIN ciudad_cotizacion ct ON fc.ciudad_cot = ct.id_cotizacion WHERE fc.estado_guia_sistema IN (7, 400, 401, 402, 403) AND fc.id_plataforma = $id_plataforma  AND fc.fecha_guia BETWEEN '$fecha_i' AND '$fecha_f'   GROUP BY ct.ciudad ORDER BY cantidad_entregas DESC LIMIT 5;";
        $response12 = $this->select($sql);

        $sql = "SELECT ct.ciudad, COUNT(fc.ciudad_cot) AS cantidad_entregas FROM facturas_cot fc INNER JOIN ciudad_cotizacion ct ON fc.ciudad_cot = ct.id_cotizacion WHERE fc.estado_guia_sistema IN (9, 500, 501, 502, 503) AND fc.id_plataforma = $id_plataforma   AND fc.fecha_guia BETWEEN '$fecha_i' AND '$fecha_f'  GROUP BY ct.ciudad ORDER BY cantidad_entregas DESC LIMIT 5;";
        $response13 = $this->select($sql);

        $sql = "SELECT AVG(fc.monto_factura) AS promedio_ventas FROM facturas_cot fc WHERE fc.estado_guia_sistema NOT IN (1, 2, 8, 9, 12, 500, 501, 502, 503) AND fc.id_plataforma = $id_plataforma  AND fecha_guia BETWEEN '$fecha_i' AND '$fecha_f' ;";
        $response14 = $this->select($sql);

        $sql = "SELECT AVG(fc.monto_factura) AS promedio_devoluciones FROM facturas_cot fc WHERE fc.estado_guia_sistema NOT IN (1, 2, 8, 7, 12, 9, 400, 401, 402, 403) AND fc.id_plataforma = $id_plataforma  AND fecha_guia BETWEEN '$fecha_i' AND '$fecha_f' ;";
        $response15 = $this->select($sql);

        $sql = "SELECT AVG(fc.costo_flete) AS promedio_flete FROM facturas_cot fc WHERE fc.estado_guia_sistema NOT IN (1, 2, 8, 12) AND fc.id_plataforma = $id_plataforma  AND fecha_guia BETWEEN '$fecha_i' AND '$fecha_f' ;";
        $response16 = $this->select($sql);

        // 1) TOTAL DE PRODUCTOS VENDIDOS
        $sql =
            "SELECT 
                SUM(df.cantidad) AS total_productos_vendidos
            FROM detalle_fact_cot df
            JOIN facturas_cot fc ON df.numero_factura = fc.numero_factura
            WHERE fc.anulada = 0
            AND fc.id_plataforma = '$id_plataforma'
            AND fc.fecha_factura BETWEEN '$fecha_i' AND '$fecha_f';
        ";
        $respTotalProd = $this->select($sql);
        $totalProductosVendidos = $respTotalProd[0]['total_productos_vendidos'] ?? 0;

        // 2) TOP 5 PRODUCTOS VENDIDOS
        $sql =
            "SELECT 
                p.nombre_producto,
                SUM(df.cantidad) AS total_vendido
            FROM detalle_fact_cot df
            JOIN facturas_cot fc ON df.numero_factura = fc.numero_factura
            JOIN inventario_bodegas ib ON df.id_inventario = ib.id_inventario
            JOIN productos p ON ib.id_producto = p.id_producto
            WHERE fc.anulada = 0
            AND fc.id_plataforma = '$id_plataforma'
            AND fc.fecha_factura BETWEEN '$fecha_i' AND '$fecha_f'
            GROUP BY p.nombre_producto
            ORDER BY total_vendido DESC
            LIMIT 5;
        ";
        $respTop5Prod = $this->select($sql);

        // 3) TOP 5 CATEGORÍAS
        // Ajusta según tus campos reales: la tabla `lineas` (o `categorias`) tiene el campo pk `id_categoria`
        // y `productos` tiene `id_linea_producto` que coincide con `lineas.id_categoria`.
        $sql = "
            SELECT 
                l.nombre_linea,
                SUM(df.cantidad) AS total_categoria
            FROM detalle_fact_cot df
            JOIN facturas_cot fc ON df.numero_factura = fc.numero_factura
            JOIN inventario_bodegas ib ON df.id_inventario = ib.id_inventario
            JOIN productos p ON ib.id_producto = p.id_producto
            JOIN lineas l ON p.id_linea_producto = l.id_categoria
            WHERE fc.anulada = 0
            AND fc.id_plataforma = '$id_plataforma'
            AND fc.fecha_factura BETWEEN '$fecha_i' AND '$fecha_f'
            GROUP BY l.nombre_linea
            ORDER BY total_categoria DESC
            LIMIT 5;
        ";
        $respTop5Cat = $this->select($sql);

        $ventas = $response[0]['ventas'] ?? 0;
        $ganancias = $response[0]['ganancias'] ?? 0;
        $envios = $response[0]['envios'] ?? 0;
        $total_guias = $response2[0]['total_guias'] ?? 0;
        $devoluciones = $response3[0]['devoluciones'] ?? 0;
        $pedidos = $response4[0]['pedidos'] ?? 0;
        $ciudad_pedidos = $response8;
        $productos_despachos = $response9;
        $productos_despachos_entregados = $response10;
        $productos_despachos_devueltos = $response11;
        $ciudades_entregas = $response12;
        $ciudades_devoluciones = $response13;
        $ticket_promedio = $response14[0]['promedio_ventas'] ?? 0;
        $devolucion_promedio = $response15[0]['promedio_devoluciones'] ?? 0;
        $flete_promedio = $response16[0]['promedio_flete'] ?? 0;
        $datos = [
            'ventas' => $ventas,
            'envios' => $envios,
            'ganancias' => $ganancias,
            'total_guias' => $total_guias,
            'devoluciones' => $devoluciones,
            'pedidos' => $pedidos,
            'ventas_diarias' => $response5,
            'facturas' => $response6,
            'estados' => $response7,
            'ciudad_pedidos' => $ciudad_pedidos,
            'productos_despachos' => $productos_despachos,
            'productos_despachos_entregados' => $productos_despachos_entregados,
            'productos_despachos_devueltos' => $productos_despachos_devueltos,
            'ciudades_entregas' => $ciudades_entregas,
            'ciudades_devoluciones' => $ciudades_devoluciones,
            'ticket_promedio' => $ticket_promedio,
            'devolucion_promedio' => $devolucion_promedio,
            'flete_promedio' => $flete_promedio,
            'productos_vendidos' => $totalProductosVendidos,
            'top_productos'      => $respTop5Prod,
            'top_categorias'     => $respTop5Cat
        ];

        return $datos;
    }
}
