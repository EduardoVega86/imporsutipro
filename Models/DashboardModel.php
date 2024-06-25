<?php
class DashboardModel extends Query
{
    public function __construct()
    {
        parent::__construct();
    }

    public function filtroInicial($fecha_i, $fecha_f, $plataforma)
    {
        // Consulta para ventas, ganancias, envíos y total de guías
        $sql = "SELECT 
                    ROUND(SUM(total_venta),2) as ventas, 
                    ROUND(SUM(monto_recibir),2) as ganancias, 
                    ROUND(SUM(precio_envio),2) as envios,
                    COUNT(*) as total_guias
                FROM cabecera_cuenta_pagar 
                WHERE fecha BETWEEN '$fecha_i' AND '$fecha_f' 
                AND tienda = '$plataforma' 
                AND estado_guia IN (7, 9)";
        $response = $this->select($sql);

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
                AND tienda = '$plataforma'";
        $response4 = $this->select($sql);

        $ventas = $response[0]['ventas'] ?? 0;
        $ganancias = $response[0]['ganancias'] ?? 0;
        $envios = $response[0]['envios'] ?? 0;
        $total_guias = $response[0]['total_guias'] ?? 0;
        $devoluciones = $response3[0]['devoluciones'] ?? 0;
        $pedidos = $response4[0]['pedidos'] ?? 0;

        $datos = [
            'ventas' => $ventas,
            'envios' => $envios,
            'ganancias' => $ganancias,
            'total_guias' => $total_guias,
            'devoluciones' => $devoluciones,
            'pedidos' => $pedidos
        ];

        return $datos;
    }
}
