<?php
class PedidosModel extends Query
{
    public function __construct()
    {
        parent::__construct();
    }

    public function cargarPedidosIngresados($filtro)
    {
        if (empty($filtro) || $filtro == "") {

            $sql = "SELECT * FROM facturas_cot where guia IS NULL and anulado = 0";
        } else {

            $separar_filtro = explode(",", $filtro);
        }
        return $this->select($sql);
    }

    public function cargarGuias($filtro)
    {
        if (empty($filtro) || $filtro == "") {

            $sql = "SELECT * FROM facturas_cot where guia IS NOT NULL and anulado = 0";
        } else {
            $sql = "SELECT * FROM facturas_cot where guia IS NOT NULL and $filtro";
        }
        return $this->select($sql);
    }

    public function cargarAnuladas($filtro)
    {
        $sql = "SELECT * FROM facturas_cot where anulado = 1";

        return $this->select($sql);
    }

    public function generarPedido($fecha, $id_cliente, $id_vendedor, $condiciones, $monto_factura, $estado_factura, $id_user_factura, $validez, $id_sucursal, $nombre, $telefono, $celular, $calle_principal, $calle_secundaria, $numero_casa, $referencia, $ciudad, $provincia, $observacion, $guia_enviada, $anulado, $transporte, $identificacion, $cod, $valor_segura, $dropshipping, $tienda, $plataforma_importa, $estado_guia_sistema, $id_factura_origen, $factura_numero, $numero_guia, $plataforma)
    {
        $response = $this->initialResponse();

        //obtiene ultimo numero de factura
        $sql = "SELECT MAX(numero_factura) FROM facturas_cot  ORDER BY numero_factura DESC LIMIT 1";
        $numero_factura = $this->select($sql);
        if ($numero_factura && isset($numero_factura[0]['MAX(numero_factura)'])) {
            $numero_factura = $numero_factura[0]['MAX(numero_factura)'];
            $numero = intval(substr($numero_factura, 4));
            $numero++;
            $nuevo_numero_factura = 'COT-' . str_pad($numero, 6, '0', STR_PAD_LEFT);
        } else {
            $nuevo_numero_factura = 'COT-000001';
        }

        $sql = "INSERT INTO facturas_cot (
            fecha, numero_factura, id_cliente, id_vendedor, condiciones, monto_factura, 
            estado_factura, id_user_factura, validez, id_sucursal, 
            nombre, telefono, celular, calle_principal, calle_secundaria, 
            numero_casa, referencia, ciudad, provincia, observacion, 
            guia_enviada, anulado, transporte, identificacion, cod, 
            valor_segura, dropshipping, tienda, plataforma_importa, 
            estado_guia_sistema, id_factura_origen, factura_numero, 
            guia, id_plataforma
        ) VALUES (
            ?, ?, ?, ?, ?, 
            ?, ?, ?, ?, ?, 
            ?, ?, ?, ?, ?, 
            ?, ?, ?, ?, ?, 
            ?, ?, ?, ?, ?, 
            ?, ?, ?, ?, ?, 
            ?, ?, ?, ?
        )";

        $data = [
            $fecha, $nuevo_numero_factura, $id_cliente, $id_vendedor, $condiciones, $monto_factura,
            $estado_factura, $id_user_factura, $validez, $id_sucursal,
            $nombre, $telefono, $celular, $calle_principal, $calle_secundaria,
            $numero_casa, $referencia, $ciudad, $provincia, $observacion,
            $guia_enviada, $anulado, $transporte, $identificacion, $cod,
            $valor_segura, $dropshipping, $tienda, $plataforma_importa,
            $estado_guia_sistema, $id_factura_origen, $factura_numero,
            $numero_guia, $plataforma
        ];

        $insertar_pedido = $this->insert($sql, $data);

        if ($insertar_pedido == 1) {
            $response['status'] = 200;
            $response['title'] = 'Peticion exitosa';
            $response['message'] = 'Pedido generado correctamente';
        } else {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = 'Error al generar el pedido';
        }

        return $response;
    }
}
