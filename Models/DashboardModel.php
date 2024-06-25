<?php
class DashboardModel extends Query
{
    public function __construct()
    {
        parent::__construct();
    }

    public function filtroInicial($fecha_i, $fecha_f, $plataforma)
    {
        $sql = "SELECT ROUND(SUM(total_venta),2) as ventas, ROUND(SUM(monto_recibir),2) as ganancias, ROUND(SUM(precio_envio),2) as envios FROM `cabecera_cuenta_pagar` WHERE fecha BETWEEN '$fecha_i' AND '$fecha_f' and estado_guia in (7,9) and tienda = '$plataforma'";
        $response = $this->select($sql);
        echo $sql;
        $sql = "SELECT COUNT(*) as total_guias FROM `cabecera_cuenta_pagar` WHERE fecha BETWEEN '$fecha_i' AND '$fecha_f' and tienda = '$plataforma'";
        $response2 = $this->select($sql);

        $sql = "SELECT ROUND(SUM(monto_recibir),2) as devoluciones FROM `cabecera_cuenta_pagar` WHERE fecha BETWEEN '$fecha_i' AND '$fecha_f' and estado_guia = 9 and tienda = '$plataforma'";
        $response3 = $this->select($sql);

        $sql = "SELECT COUNT(*) as pedidos from facturas_cot where fecha_factura BETWEEN '$fecha_i' AND '$fecha_f' and tienda = '$plataforma'";
        $response4 = $this->select($sql);

        $datos = [
            'ventas' => $response[0]['ventas'],
            'envios' => $response[0]['envios'],
            'ganancias' => $response[0]['ganancias'],
            'total_guias' => $response2[0]['total_guias'],
            'devoluciones' => $response3[0]['devoluciones'],
            'pedidos' => $response4[0]['pedidos']
        ];

        return $datos;
    }
}
